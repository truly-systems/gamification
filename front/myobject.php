<?php
include ("../../../inc/includes.php");

// Check if plugin is activated...
$plugin = new Plugin();
if (!$plugin->isInstalled('myexampleplugin') || !$plugin->isActivated('myexampleplugin')) {
   Html::displayNotFoundError();
}

//check for ACLs
if (PluginMyExampleMyObject::canView()) {
   //View is granted: display the list.

   //Add page header
   Html::header(
      __('My example plugin', 'myexampleplugin'),
      $_SERVER['PHP_SELF'],
      'assets',
      'pluginmyexamplemyobject',
      'myobject'
   );

   Search::show('PluginMyExampleMyObject');

   Html::footer();
} else {
   //View is not granted.
   Html::displayRightError();
}