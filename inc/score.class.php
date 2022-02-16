<?php
class PluginGamificationScore extends CommonDBTM {

   static function getTypeName($nb = 0) {

      return 'Gamification';
   }

   static function canCreate() {
      if (isset($_SESSION["glpi_plugin_gamification_profile"])) {
         return ($_SESSION["glpi_plugin_gamification_profile"]['gamification'] == 'w');
      }
      return false;
   }

   static function canView() {

      if (isset($_SESSION["glpi_plugin_gamification_profile"])) {
         return ($_SESSION["glpi_plugin_gamification_profile"]['gamification'] == 'w'
                 || $_SESSION["glpi_plugin_gamification_profile"]['gamification'] == 'r');
      }
      return false;
   }   
}