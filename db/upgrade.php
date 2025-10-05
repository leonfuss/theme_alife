<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

defined('MOODLE_INTERNAL') || die();

/**
 * Theme upgrade script
 *
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_theme_alife_upgrade($oldversion) {
    global $DB;

    // Create custom fields for frontpage course control
    if ($oldversion < 2025090302) {

        // Get or create the custom field category
        $category = $DB->get_record('customfield_category', [
            'component' => 'core_course',
            'area' => 'course',
            'name' => 'Frontpage Settings'
        ]);

        if (!$category) {
            $category = new stdClass();
            $category->name = 'Frontpage Settings';
            $category->description = 'Settings for controlling course display on frontpage';
            $category->descriptionformat = FORMAT_HTML;
            $category->sortorder = 0;
            $category->timecreated = time();
            $category->timemodified = time();
            $category->component = 'core_course';
            $category->area = 'course';
            $category->itemid = 0;
            $category->contextid = context_system::instance()->id;

            $category->id = $DB->insert_record('customfield_category', $category);
        }

        // Create "Show on Frontpage" checkbox field
        $showfield = $DB->get_record('customfield_field', [
            'shortname' => 'showonfrontpage',
            'type' => 'checkbox'
        ]);

        if (!$showfield) {
            $showfield = new stdClass();
            $showfield->shortname = 'showonfrontpage';
            $showfield->name = 'Show on Frontpage';
            $showfield->type = 'checkbox';
            $showfield->description = '<p>Enable this to display this course on the site frontpage</p>';
            $showfield->descriptionformat = FORMAT_HTML;
            $showfield->sortorder = 0;
            $showfield->categoryid = $category->id;
            $showfield->configdata = json_encode([
                'required' => 0,
                'uniquevalues' => 0,
                'defaultvalue' => 0,
                'locked' => 0,
                'visibility' => 2
            ]);
            $showfield->timecreated = time();
            $showfield->timemodified = time();

            $DB->insert_record('customfield_field', $showfield);
        }

        // Create "Course Numbering" text field (renamed from priority)
        $numberingfield = $DB->get_record('customfield_field', [
            'shortname' => 'coursenumbering',
            'type' => 'text'
        ]);

        if (!$numberingfield) {
            $numberingfield = new stdClass();
            $numberingfield->shortname = 'coursenumbering';
            $numberingfield->name = 'Course Numbering';
            $numberingfield->type = 'text';
            $numberingfield->description = '<p>Number to display on course card and for sorting. Example: 1, 2, 3, etc.</p>';
            $numberingfield->descriptionformat = FORMAT_HTML;
            $numberingfield->sortorder = 1;
            $numberingfield->categoryid = $category->id;
            $numberingfield->configdata = json_encode([
                'required' => 0,
                'uniquevalues' => 0,
                'defaultvalue' => '999',
                'locked' => 0,
                'visibility' => 2,
                'displaysize' => 10,
                'maxlength' => 10,
                'ispassword' => 0,
                'link' => ''
            ]);
            $numberingfield->timecreated = time();
            $numberingfield->timemodified = time();

            $DB->insert_record('customfield_field', $numberingfield);
        }

        upgrade_plugin_savepoint(true, 2025090302, 'theme', 'alife');
    }

    // Rename frontpagepriority to coursenumbering
    if ($oldversion < 2025090303) {
        // Update the old field if it exists
        $oldfield = $DB->get_record('customfield_field', [
            'shortname' => 'frontpagepriority'
        ]);

        if ($oldfield) {
            $oldfield->shortname = 'coursenumbering';
            $oldfield->name = 'Course Numbering';
            $oldfield->description = '<p>Number to display on course card and for sorting. Example: 1, 2, 3, etc.</p>';
            $oldfield->timemodified = time();
            $DB->update_record('customfield_field', $oldfield);
        }

        upgrade_plugin_savepoint(true, 2025090303, 'theme', 'alife');
    }

    return true;
}
