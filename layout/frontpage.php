<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

defined("MOODLE_INTERNAL") || die();

require_once $CFG->libdir . "/behat/lib.php";
require_once $CFG->dirroot . "/course/lib.php";

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

if (isloggedin()) {
    $courseindexopen = get_user_preferences("drawer-open-index", true) == true;
    $blockdraweropen = get_user_preferences("drawer-open-block") == true;
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (
    defined("BEHAT_SITE_RUNNING") &&
    get_user_preferences("behat_keep_drawer_closed") != 1
) {
    $blockdraweropen = true;
}

$extraclasses = ["uses-drawers"];
if ($courseindexopen) {
    $extraclasses[] = "drawer-open-index";
}

$blockshtml = $OUTPUT->blocks("side-pre");
$hasblocks =
    strpos($blockshtml, "data-block=") !== false || !empty($addblockbutton);
if (!$hasblocks) {
    $blockdraweropen = false;
}
$courseindex = core_course_drawer();
if (!$courseindex) {
    $courseindexopen = false;
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = "";
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu(
        $PAGE->secondarynav,
        "nav-tabs",
        true,
        $tablistnav,
    );
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer("core");
$primarymenu = $primary->export_for_template($renderer);

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

// ============================================
// Continue Learning Logic
// ============================================
global $USER, $DB;

$continuelearning_url = null;
$has_continuelearning = false;

if (isloggedin() && !isguestuser()) {
    // Method 1: Try to get last viewed course module from logs
    // Check if logstore_standard is enabled
    $logstores = get_log_manager()->get_readers();
    $logstore_enabled = false;

    foreach ($logstores as $logstore) {
        if ($logstore instanceof \logstore_standard\log\store) {
            $logstore_enabled = true;
            break;
        }
    }

    if ($logstore_enabled) {
        // Try logstore_standard_log
        $sql =
            "SELECT cm.id as cmid, cm.course, cm.section, c.fullname
                FROM {logstore_standard_log} l
                JOIN {course_modules} cm ON cm.id = l.contextinstanceid
                JOIN {course} c ON c.id = cm.course
                JOIN {context} ctx ON ctx.id = l.contextid
                WHERE l.userid = :userid
                  AND l.target = 'course_module'
                  AND l.action = 'viewed'
                  AND ctx.contextlevel = 70
                  AND c.id != " .
            SITEID .
            "
                ORDER BY l.timecreated DESC
                LIMIT 1";

        try {
            $lastactivity = $DB->get_record_sql($sql, ["userid" => $USER->id]);

            if ($lastactivity) {
                // Jump to course with anchor to activity
                $continuelearning_url = new moodle_url(
                    "/course/view.php",
                    [
                        "id" => $lastactivity->course,
                    ],
                    "module-" . $lastactivity->cmid,
                );
                $has_continuelearning = true;
            }
        } catch (Exception $e) {
            // Fallback if query fails
            debugging(
                "Continue learning query failed: " . $e->getMessage(),
                DEBUG_DEVELOPER,
            );
        }
    }

    // Method 2: Fallback - Get last accessed course
    if (!$has_continuelearning) {
        $sql =
            "SELECT courseid
                FROM {user_lastaccess}
                WHERE userid = :userid
                  AND courseid != " .
            SITEID .
            "
                ORDER BY timeaccess DESC
                LIMIT 1";

        $lastcourse = $DB->get_record_sql($sql, ["userid" => $USER->id]);

        if ($lastcourse) {
            $continuelearning_url = new moodle_url("/course/view.php", [
                "id" => $lastcourse->courseid,
            ]);
            $has_continuelearning = true;
        }
    }
}

$templatecontext = [
    "sitename" => format_string($SITE->shortname, true, [
        "context" => context_course::instance(SITEID),
        "escape" => false,
    ]),
    "output" => $OUTPUT,
    "sidepreblocks" => $blockshtml,
    "hasblocks" => $hasblocks,
    "bodyattributes" => $bodyattributes,
    "courseindexopen" => $courseindexopen,
    "blockdraweropen" => $blockdraweropen,
    "courseindex" => $courseindex,
    "primarymoremenu" => $primarymenu["moremenu"],
    "secondarymoremenu" => $secondarynavigation ?: false,
    "mobileprimarynav" => $primarymenu["mobileprimarynav"],
    "usermenu" => $primarymenu["user"],
    "langmenu" => $primarymenu["lang"],
    "forceblockdraweropen" => $forceblockdraweropen,
    "regionmainsettingsmenu" => $regionmainsettingsmenu,
    "hasregionmainsettingsmenu" => !empty($regionmainsettingsmenu),
    "overflow" => $overflow,
    "headercontent" => $headercontent,
    "addblockbutton" => $addblockbutton,
    "adaptive_icon" => $OUTPUT->image_url(
        "icons/sliders_purple",
        "theme_alife",
    ),
    "evidence_icon" => $OUTPUT->image_url(
        "icons/chart-donut_purple",
        "theme_alife",
    ),
    "effective_icon" => $OUTPUT->image_url(
        "icons/list-checks_purple",
        "theme_alife",
    ),
    "up_to_date_icon" => $OUTPUT->image_url(
        "icons/arrows-clockwise_purple",
        "theme_alife",
    ),
    "continuelearning_url" => $continuelearning_url
        ? $continuelearning_url->out(false)
        : "#",
    "has_continuelearning" => $has_continuelearning,
];

echo $OUTPUT->render_from_template("theme_alife/frontpage", $templatecontext);
