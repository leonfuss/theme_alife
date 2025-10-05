<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

namespace theme_alife\output\block_myoverview;

use renderer_base;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/myoverview/classes/output/main.php');

/**
 * My overview block main class override for ALIFE theme
 * Adds course numbering from custom field to course data
 */
class main extends \block_myoverview\output\main {

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     * @return \stdClass
     */
    public function export_for_template(renderer_base $output) {
        $data = parent::export_for_template($output);

        // Add course numbers to the template data via JavaScript data attribute
        // The actual course data is loaded via AJAX so we'll use a custom exporter

        return $data;
    }
}
