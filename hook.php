<?php

/**
 * Install hook
 *
 * @return boolean
 */
function plugin_gamification_install()
{
   global $DB;

   //instanciate migration with version
   $migration = new Migration(100);

   //Create table only if it does not exists yet!
   if (!$DB->tableExists('glpi_plugin_gamification_score')) {
      //table creation query
      $query = "CREATE TABLE `glpi_plugin_gamification_score` (
                  `id` INT(11) auto_increment NOT NULL,
                  `score` NUMERIC,
                  `user_id` INT(11) NOT NULL,
                  PRIMARY KEY  (`id`)
               ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
      $DB->queryOrDie($query, $DB->error());
   }

   //table creation query
   if (!$DB->tableExists('glpi_plugin_gamification_configs')) {
      $query = "CREATE TABLE `glpi_plugin_gamification_configs`(
         `id` int(11) auto_increment NOT NULL,
         `agent_fast_ticket_solve` NUMERIC NULL,
         `agent_on_time_ticket_solve` NUMERIC NULL,
         `agent_late_ticket_solve` NUMERIC NULL,
         `bonus_first_call` NUMERIC NULL,
         `bonus_happy_customer` NUMERIC NULL,
         `bonus_unhappy_customer` NUMERIC NULL,
         `level_beginner` NUMERIC NULL,
         `level_intermediate` NUMERIC NULL,
         `level_professional` NUMERIC NULL,
         `level_expert` NUMERIC NULL,
         `level_master` NUMERIC NULL,
         `level_jedi_master` NUMERIC NULL,
         PRIMARY KEY  (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

      $DB->queryOrDie($query, $DB->error());

      $query = "INSERT INTO `glpi_plugin_gamification_configs` 
         (`id`,`agent_fast_ticket_solve`,`agent_on_time_ticket_solve`,`agent_late_ticket_solve`,`bonus_first_call`,`bonus_happy_customer`,`bonus_unhappy_customer`,`level_beginner`,`level_intermediate`,`level_professional`,`level_expert`,`level_master`,`level_jedi_master`)
         VALUES (1,10,5,-5,5,10,-10,100,2500,1000,25000,50000,100000);";

      $DB->query($query) or die("error populate glpi_plugin_gamification_configs " . $DB->error());
   }
   //execute the whole migration
   $migration->executeMigration();

   return true;
}

/**
 * Uninstall hook
 *
 * @return boolean
 */
function plugin_gamification_uninstall()
{
   global $DB;

   $tables = [
      'score',
      'configs'
   ];

   foreach ($tables as $table) {
      $tablename = 'glpi_plugin_gamification_' . $table;
      //Create table only if it does not exists yet!
      if ($DB->tableExists($tablename)) {
         $DB->queryOrDie(
            "DROP TABLE `$tablename`",
            $DB->error()
         );
      }
   }

   return true;
}

function plugin_add_score($item)
{
   if ($item->input['status'] == 6) {
      $user_id = plugin_get_user_by_ticket_id($item->input['id']);
      if (plugin_check_exists($user_id['users_id']) > 0) {
         plugin_update_score($user_id['users_id'], $item);
      } else {
         plugin_add_point($user_id['users_id']);
      }
   }
   return true;
}

function plugin_add_score_satisfaction($item)
{
   global $DB;
   $config = plugin_get_configs();
   $user_id = plugin_get_user_by_ticket_id($item->input['id']);
   $value = 0;
   $empty = 0;

   if (!empty($item->oldvalues)) {
      foreach ($item->oldvalues as $key => $value) {
         if (empty($value)) {
            if ($key == 'satisfaction') {
               $empty++;
            }
         }
      }
   }

   if ($empty >= 1) {
      if ($item->input['satisfaction'] >= 3) {
         $value = $config["bonus_happy_customer"];
      } else {
         $value = $config["bonus_unhappy_customer"];
      }

      $query = "
         UPDATE glpi_plugin_gamification_score
         SET score= (select score +" . $value . " from glpi_plugin_gamification_score where user_id = " . $user_id['users_id'] . ")
         WHERE user_id=" . $user_id['users_id'] . ";
      ";
      $DB->query($query) or die("error" . $DB->error());
   }
}

function plugin_add_point($user_id)
{
   global $DB;

   $query = "
      INSERT INTO glpi_plugin_gamification_score (score,user_id)
	   VALUES (5," . $user_id . ");
   ";

   $DB->query($query) or die("error" . $DB->error());
}

function plugin_update_score($user_id, $item)
{
   global $DB;

   $config = plugin_get_configs();
   $data = plugin_check_time($item, $config);
   // $bonus = plugin_check_bonus($item->input['id'], $config);
   $query = "
      UPDATE glpi_plugin_gamification_score
      SET score= (select score +" . $data . " from glpi_plugin_gamification_score where user_id = " . $user_id . ")
      WHERE user_id=" . $user_id . ";
   ";
   $DB->query($query) or die("error" . $DB->error());
}

function plugin_check_exists($user_id)
{
   global $DB;

   $query = "
      select * from glpi_plugin_gamification_score gppc
      where user_id = " . $user_id . "
   ";

   $data = $DB->query($query) or die("error" . $DB->error());

   return ($DB->numrows($data));
}

function plugin_get_configs()
{
   global $DB;

   $query = "SELECT * FROM glpi_plugin_gamification_configs gpgc";
   $return = $DB->query($query) or die("error" . $DB->error());

   return ($DB->fetch_row($return));
}

function plugin_check_time($item, $config)
{

   global $DB;

   $query = "SELECT gt.date, gt.solvedate, gt.time_to_resolve FROM glpi_tickets gt 
   WHERE gt.id = " . $item->input['id'] . "";

   $return = $DB->query($query) or die("error" . $DB->error());

   $date = $DB->fetchArray($return);

   $date_init = new DateTime($date['date']);
   $date_solve = new DateTime($date['solvedate']);
   $date_resolve = new DateTime($date['time_to_resolve']);

   $interval = $date_init->diff($date_solve);
   $time_solve = $date_solve->diff($date_resolve);

   //Fast (<1h)
   if ($interval->d == 0 && $interval->h == 0) {
      return $config["agent_fast_ticket_solve"];
   }
   //On Time 
   else if ($time_solve->invert == 0) {
      return $config["agent_on_time_ticket_solve"];
   }
   //Late
   else if ($time_solve->invert == 1) {
      return $config["agent_late_ticket_solve"];
   }
}

function plugin_check_bonus($id, $config)
{
   global $DB;

   $query = "select gt.satisfaction from glpi_ticketsatisfactions gt 
   where gt.tickets_id = " . $id . "";

   $return = $DB->query($query) or die("error" . $DB->error());
   $data = $DB->fetchArray($return);
}

function plugin_get_user_by_ticket_id($ticket_id)
{
   global $DB;
   $query = "
         select gtu.users_id from glpi_tickets_users gtu 
         where gtu.tickets_id = " . $ticket_id . " and 
         gtu.type = 2
      ";
   $return = $DB->query($query) or die("error" . $DB->error());
   return ($DB->fetchArray($return));
}