<?php

define('Gamification', '1.0');

/**
 * Init the hooks of the plugins - Needed
 *
 * @return void
 */
function plugin_init_gamification() {
   global $PLUGIN_HOOKS;

   //required!
   $PLUGIN_HOOKS['csrf_compliant']['gamification'] = true;

   // $PLUGIN_HOOKS['item_add']['gamification']        = ['Computer' => ['PluginExampleExample',
   //                                                               'item_add_test']];
   
   
   $PLUGIN_HOOKS['item_update']['gamification']     = ['Ticket' => 'plugin_add_score',
   'TicketSatisfaction' => 'plugin_add_score_satisfaction'];

   $PLUGIN_HOOKS['display_central']['gamification'] = "plugin_example_display_central";
   
   // $PLUGIN_HOOKS['item_update']['gamification']     = ['TicketSatisfaction' => 'plugin_add_score_satisfaction'];

   if (Session::haveRight('config', UPDATE)) {
      $PLUGIN_HOOKS['config_page']['gamification'] = 'config.php';
   }

   $PLUGIN_HOOKS['add_css']['gamification']        = 'style.css';

}

/**
 * Get the name and the version of the plugin - Needed
 *
 * @return array
 */
function plugin_version_gamification() {
   return [
      'name'           => 'Gamification',
      'version'        => '1.0',
      'author'         => 'Truly Systems',
      'license'        => 'GLPv3',
      'homepage'       => 'https://www.trulysystems.com/',
      'requirements'   => [
         'glpi'   => [
            'min' => '9.5'
         ]
      ]
   ];
}

/**
 * Optional : check prerequisites before install : may print errors or add to message after redirect
 *
 * @return boolean
 */
function plugin_gamification_check_prerequisites() {
   //do what the checks you want
   return true;
}

/**
 * Check configuration process for plugin : need to return true if succeeded
 * Can display a message only if failure and $verbose is true
 *
 * @param boolean $verbose Enable verbosity. Default to false
 *
 * @return boolean
 */
function plugin_gamification_check_config($verbose = false) {
   if (true) { // Your configuration check
      return true;
   }

   if ($verbose) {
      echo "Installed, but not configured";
   }
   return false;
}