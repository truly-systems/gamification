<?php
// define('GLPI_SESSION_DIR', '/var/lib/php/sessions/');
define('GLPI_ROOT', '../..');
include(GLPI_ROOT . "/inc/includes.php");

Session::checkRight("config", UPDATE);

// To be available when plugin in not activated
Plugin::load('example');

Html::header("TITRE", $_SERVER['PHP_SELF'], "config", "plugins");

$query = " select * from glpi_plugin_gamification_configs";

$return = $DB->query($query) or die("error" . $DB->error());
$data = $DB->fetchArray($return);

if (isset($_GET['happyCustomer'])) {
    $query = "update
        `glpi_plugin_gamification_configs`
        set
        `bonus_happy_customer` = " . $_GET['happyCustomer'] . ",
        `bonus_first_call` = " . $_GET['firstCall'] . ",
        `level_expert` = " . $_GET['expert'] . ",
        `level_master` = " . $_GET['master'] . ",
        `level_professional` = " . $_GET['professional'] . ",
        `level_intermediate` = " . $_GET['intermediate'] . ",
        `level_jedi_master` = " . $_GET['jediMaster'] . ",
        `bonus_unhappy_customer` =" . $_GET['unhappyCustomer'] . ",
        `agent_fast_ticket_solve` = " . $_GET['fast'] . ",
        `agent_on_time_ticket_solve` = " . $_GET['onTime'] . ",
        `level_beginner` = " . $_GET['beginner'] . ",
        `agent_late_ticket_solve` =" . $_GET['late'] . "
        where
        `id` = 1;";

    $DB->query($query) or die("error update glpi_plugin_gamification_configs " . $DB->error());
    ?>
    <script>
        window.location.replace('<?= GLPI_ROOT ?>/plugins/gamification/config.php');
    </script>
<?php    
}

?>

<div class="ui-tabs-panel ui-corner-bottom ui-widget-content">
    <form action="" method="get" name="form_ticket" enctype="multipart/form-data">
        <div class="spaced">
            <table class="tab_cadre_fixe">
                <tbody>
                    <tr class="headerRow responsive_hidden">
                        <th colspan="6" class="align-center">When agent resolves a ticket:</th>
                    </tr>
                    <tr>
                        <th class="bg-white">
                            <label>Fast (<1h)</label>
                        </th>
                        <td>
                            <input name="fast" value="<?php echo $data['agent_fast_ticket_solve'] ?>" type="text">
                        </td>
                        <th class="bg-white">
                            <label>On Time</label>
                        </th>
                        <td>
                            <input value="<?php echo $data['agent_on_time_ticket_solve'] ?>" name="onTime" type="text">
                        </td>
                        <th class="bg-white">
                            <label name="late">Late</label>
                        </th>
                        <td>
                            <input value="<?php echo $data['agent_late_ticket_solve'] ?>" name="late" type="text">
                        </td>
                    </tr>
                    <tr class="headerRow responsive_hidden align-center">
                        <th colspan="6">Bonus points for: </th>
                    </tr>
                    <tr>
                        <th class="bg-white">
                            <label>First Call Resolution</label>
                        </th>
                        <td>
                            <input value="<?php echo $data['bonus_first_call'] ?>" name="firstCall" type="text">
                        </td>
                        <th class="bg-white">
                            <label>Happy customer</label>
                        </th>
                        <td>
                            <input value="<?php echo $data['bonus_happy_customer'] ?>" name="happyCustomer" type="text">
                        </td>
                        <th class="bg-white">
                            <label>Unhappy Customer</label>
                        </th>
                        <td>
                            <input value="<?php echo $data['bonus_unhappy_customer'] ?>" name="unhappyCustomer" type="text">
                        </td>
                    </tr>
                    <tr class="headerRow responsive_hidden">
                        <th colspan="6" class="align-center">Agent Levels</th>
                    </tr>
                    <tr>
                        <th class="bg-white">
                            <label>Beginner</label>
                        </th>
                        <td>
                            <input value="<?php echo $data['level_beginner'] ?>" name="beginner" type="text">
                        </td>
                        <th class="bg-white">
                            <label>Professional</label>
                        </th>
                        <td>
                            <input value="<?php echo $data['level_professional'] ?>" name="professional" type="text">
                        </td>
                        <th class="bg-white">
                            <label>Master</label>
                        </th>
                        <td>
                            <input value="<?php echo $data['level_master'] ?>" name="master" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-white">
                            <label>Intermediate</label>
                        </th>
                        <td>
                            <input value="<?php echo $data['level_intermediate'] ?>" name="intermediate" type="text">
                        </td>
                        <th class="bg-white">
                            <label>Expert</label>
                        </th>
                        <td>
                            <input value="<?php echo $data['level_expert'] ?>" name="expert" type="text">
                        </td>
                        <th class="bg-white">
                            <label>Jedi Master</label>
                        </th>
                        <td>
                            <input value="<?php echo $data['level_jedi_master'] ?>" name="jediMaster" type="text">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="align-center">
            <button class='vsubmit' type="submit">Save</button>
        </div>
    </form>
</div>

<?php
Html::footer();
