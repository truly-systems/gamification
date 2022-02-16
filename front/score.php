<?php
include("../../../inc/includes.php");

// Check if plugin is activated...
$plugin = new Plugin();
if (!$plugin->isInstalled('gamification') || !$plugin->isActivated('gamification')) {
   Html::displayNotFoundError();
}

function get_points()
{
   global $DB;

   $query = "
      select gpgs.score from glpi_plugin_gamification_score gpgs 
      where user_id = " . Session::getLoginUserID() . "";

   $return = $DB->query($query) or die("error" . $DB->error());
   $data = $DB->fetchArray($return);

   return $data['score'];
}

function plugin_check_score($score)
{
   global $DB;
   $rank_name = 'N/A';

   $query = "SELECT * FROM glpi_plugin_gamification_configs gpgc";
   $return = $DB->query($query);

   $config = $DB->fetch_assoc($return);

   foreach ($config as $key => $value) {
      if (strpos($key, 'level_') === 0) {
         if ($score <= $value) {
            $rank_name = plugin_return_rank_name($key);
            break;
         }
      }
   }

   return $rank_name;
}

function plugin_return_rank_name($key)
{
   switch ($key) {
      case 'level_beginner':
         return 'Beginner';
         break;

      case 'level_intermediate':
         return 'Intermediate';
         break;

      case 'level_professional':
         return 'Professional';
         break;

      case 'level_expert':
         return 'Expert';
         break;

      case 'level_master':
         return 'Master';
         break;

      case 'level_jedi_master':
         return 'Jedi Master';
         break;
      default:
         return 'N/A';
         break;
   };
}

//check for ACLs
if (PluginGamificationScore::canView()) {
   get_points();
   //Add page header
   Html::header("Gamification", $_SERVER['PHP_SELF'], "management", "plugingamificationscore", "");

?>
   <div class="ui-tabs-panel ui-corner-bottom ui-widget-content">
         <div class="spaced">
            <table class="tab_cadre_fixe">
               <tbody>
                  <tr class="headerRow responsive_hidden">
                     <th colspan="6" class="align-center">Score:</th>
                  </tr>
                  <tr>
                     <th class="bg-white">
                        <label>Fast (<1h)</label>
                     </th>
                     <td>
                        <input style="font-weight: bold" disabled value="<?php echo get_points() ?>" type="text">
                     </td>
                     <th class="bg-white">
                        <label>On Time</label>
                     </th>
                     <td>
                        <input style="font-weight: bold" disabled value="<?php echo plugin_check_score(get_points()) ?>" name="late" type="text">
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
   </div>
<?php
   Html::footer();
} else {
   //View is not granted.
   Html::displayRightError();
}
