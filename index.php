<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Core\Settings;
use \Tsugi\UI\SettingsForm;

// The Tsugi PHP API Documentation is available at:
// http://do1.dr-chuck.com/tsugi/phpdoc/

// No parameter means we require CONTEXT, USER, and LINK
$LAUNCH = LTIX::requireData();

// Handle the incoming post first
if ( $LAUNCH->link && $LAUNCH->link->id && SettingsForm::handleSettingsPost() ) {
    header('Location: '.addSession('index.php') ) ;
    return;
}

// Handle Post Data
$p = $CFG->dbprefix;

$menu = false;
if ( $LAUNCH->user->instructor ) {
    $menu = new \Tsugi\UI\MenuSet();
    if ( $CFG->launchactivity ) {
        $menu->addRight(__('Analytics'), 'analytics');
    }
    $menu->addRight(__('Settings'), '#', /* push */ false, SettingsForm::attr());
}


// Render view
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->topNav($menu);
$OUTPUT->flashMessages();

$OUTPUT->welcomeUserCourse();

if ( $LAUNCH->link && $LAUNCH->user && $LAUNCH->user->instructor ) {
    SettingsForm::start();
    SettingsForm::text('color','A color for breakout.');
    SettingsForm::end();
}

$color = Settings::linkGet('color', 'black');
echo('<script> breakout_color = "'.$color.'";</script>');
?>
<canvas id="breakout" width="800" height="500" style="border:1px solid #000000;">
</canvas>

<?php
$OUTPUT->footerStart();
?>
<script type="text/javascript" src="breakout.js" charset="utf-8"></script>
<?php
$OUTPUT->footerEnd();
?>
