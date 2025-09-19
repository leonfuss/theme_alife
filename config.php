<?php
defined("MOODLE_INTERNAL") || die();

$THEME->name = "alife";
$THEME->sheets = [];		// we use scss instead of css
$THEME->editor_sheets = []; 	// config of legacy text editor (TinyMCE)
$THEME->parents = ["boost"];
$THEME->enable_dock = false;	// floating persisting elements
$THEME->yuicssmodules = [];

// default override renderer
$THEME->rendererfactory = "theme_overridden_renderer_factory";

// blocks that are required to exist on all pages - boost handles this differently
$THEME->requiredblocks = "";

$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;

$THEME->haseditswitch = true;
$THEME->usescourseindex = true;

$THEME->scss = function($theme) {
    return theme_alife_get_main_scss_content($theme);
};

// $THEME->layouts = [
//     'frontpage' => array(
//         'file' => 'frontpage.php',
//         'regions' => array(),
//         'defaultregion' => '',
//         'options' => array('nonavbar' => true, 'noheader' => true, 'nofooter' => false),
//     ),
// ];
