<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

namespace theme_alife\output\core;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/renderer.php');

use coursecat_helper;
use core_course_list_element;
use core_course_category;
use html_writer;
use moodle_url;
use lang_string;
use context_system;
use context_course;

/**
 * Course renderer for alife theme
 * Customizes how courses are displayed on the frontpage
 */
class course_renderer extends \core_course_renderer {

    /** @var array Course numbering from custom fields */
    protected $course_numbering = array();

    /** @var array Course subnumbering from custom fields */
    protected $course_subnumbering = array();

    /**
     * Renders HTML to display a course content on frontpage
     *
     * @param coursecat_helper $chelper
     * @param core_course_list_element|\stdClass $course
     * @param string $additionalclasses
     * @return string
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        global $PAGE;

        if (!isset($this->strings->summary)) {
            $this->strings->summary = get_string('summary');
        }
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof \stdClass) {
            $course = new core_course_list_element($course);
        }

        $classes = trim('coursebox clearfix '. $additionalclasses);
        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $classes .= ' collapsed';
        }

        $content = '';

        // Course URL for click handler
        $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));

        // Get course numbering and subnumbering from custom field
        $coursenumber = '';
        $mainnum = null;
        $subnum = null;

        if (isset($this->course_numbering[$course->id])) {
            $mainnum = $this->course_numbering[$course->id];
        }

        if (isset($this->course_subnumbering[$course->id])) {
            $subnum = $this->course_subnumbering[$course->id];
        }

        // If not in arrays, get directly from custom fields (for All Courses page)
        if ($mainnum === null || $subnum === null) {
            $handler = \core_customfield\handler::get_handler('core_course', 'course');
            $datas = $handler->get_instance_data($course->id);

            foreach ($datas as $data) {
                $fieldname = $data->get_field()->get('shortname');

                if (($fieldname === 'coursenumbering' || $fieldname === 'frontpagepriority') && $mainnum === null) {
                    $value = $data->get_value();
                    if ($value !== null && $value !== '' && $value !== false) {
                        $mainnum = (int)$value;
                    }
                }

                if ($fieldname === 'coursesubnumbering' && $subnum === null) {
                    $value = $data->get_value();
                    if ($value !== null && $value !== '' && $value !== false) {
                        $subnum = (int)$value;
                    }
                }
            }
        }

        // Format the course number with optional subnumber
        if ($mainnum !== null) {
            $coursenumber = str_pad($mainnum, 2, '0', STR_PAD_LEFT);
            if ($subnum !== null && $subnum > 0) {
                $coursenumber .= '-' . $subnum;
            }
        }

        // Start coursebox wrapper with onclick handler
        $content .= html_writer::start_tag('div', array(
            'class' => $classes,
            'data-courseid' => $course->id,
            'data-type' => self::COURSECAT_TYPE_COURSE,
            'data-coursenumber' => $coursenumber,
            'onclick' => 'window.location.href=\'' . $courseurl->out() . '\'',
            'style' => 'cursor: pointer;'
        ));

        // Course image first
        $content .= html_writer::start_tag('div', array('class' => 'courseimage-wrapper'));
        $content .= $this->course_overview_files($course);

        // Add caret icon circle
        $content .= html_writer::tag('div', '', array('class' => 'course-caret-circle'));

        $content .= html_writer::end_tag('div');

        // Course info section (with shortname and number badge)
        $content .= html_writer::start_tag('div', array('class' => 'info'));

        // Add number badge if available
        if (!empty($coursenumber)) {
            $content .= html_writer::tag('div', $coursenumber, array('class' => 'course-number-badge'));
        }

        // Use shortname instead of fullname
        $coursename = html_writer::tag('h3',
            html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)),
                format_string($course->shortname, true, array('context' => context_course::instance($course->id)))),
            array('class' => 'coursename')
        );
        $content .= $coursename;

        $content .= html_writer::end_tag('div');

        // Hide the content section
        $content .= html_writer::start_tag('div', array('class' => 'content d-none'));
        $content .= html_writer::end_tag('div');

        $content .= html_writer::end_tag('div'); // .coursebox

        return $content;
    }

    /**
     * Returns HTML to display course content (description, files, etc)
     * Override to hide unnecessary elements
     *
     * @param coursecat_helper $chelper
     * @param \stdClass|core_course_list_element $course
     * @return string
     */
    protected function coursecat_coursebox_content(coursecat_helper $chelper, $course) {
        // Return empty - we don't want to show summary/description
        return '';
    }

    /**
     * Returns HTML to display course overview files
     * Enhanced to always show a placeholder if no image
     *
     * @param core_course_list_element $course
     * @return string
     */
    protected function course_overview_files(core_course_list_element $course): string {
        global $CFG;

        $contentimages = '';
        $files = $course->get_course_overviewfiles();

        foreach ($files as $file) {
            $isimage = $file->is_valid_image();
            if ($isimage) {
                $url = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php",
                    '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                    $file->get_filearea() . $file->get_filepath() . $file->get_filename(), !$isimage);

                $contentimages .= html_writer::tag('div',
                    html_writer::empty_tag('img', ['src' => $url, 'alt' => format_string($course->fullname), 'class' => 'w-100']),
                    ['class' => 'courseimage']);
                break; // Only show first image
            }
        }

        // If no image found, create placeholder
        if (empty($contentimages)) {
            $contentimages = html_writer::tag('div',
                html_writer::tag('div', '', ['class' => 'course-placeholder']),
                ['class' => 'courseimage']);
        }

        return $contentimages;
    }

    /**
     * Returns HTML to display list of available courses for frontpage
     * Enhanced with Bootstrap grid and custom field filtering/sorting
     *
     * @return string
     */
    public function frontpage_available_courses() {
        global $CFG, $DB;

        $chelper = new coursecat_helper();
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED)->
                set_courses_display_options(array(
                    'recursive' => true,
                    'limit' => $CFG->frontpagecourselimit,
                    'viewmoreurl' => new moodle_url('/course/index.php'),
                    'viewmoretext' => new lang_string('fulllistofcourses')));

        $chelper->set_attributes(array('class' => 'frontpage-course-list-all'));
        $courses = core_course_category::top()->get_courses($chelper->get_courses_display_options());

        // Filter and sort courses based on custom fields
        $filteredcourses = array();
        $numbering = array(); // Store course numbering separately
        $subnumbering = array(); // Store course subnumbering separately

        foreach ($courses as $course) {
            // Skip hidden courses unless user has capability to view them
            // Check at system context first (for site admins), then course context
            // Note: has_capability() automatically respects role switching, so admins viewing
            // as another role will see the course list as that role would see it
            $canviewhidden = has_capability('moodle/course:viewhiddencourses', context_system::instance()) ||
                             has_capability('moodle/course:viewhiddencourses', context_course::instance($course->id));

            if (!$course->visible && !$canviewhidden) {
                continue;
            }

            $handler = \core_customfield\handler::get_handler('core_course', 'course');
            $datas = $handler->get_instance_data($course->id, true); // true = return data for all users

            $showonfrontpage = false;
            $coursenumber = null; // No default - only set if custom field has value
            $coursesubnumber = 0; // Default subnumbering

            foreach ($datas as $data) {
                $fieldname = $data->get_field()->get('shortname');

                // Check "Show on Frontpage" checkbox
                if ($fieldname === 'showonfrontpage' && $data->get_value()) {
                    $showonfrontpage = true;
                }

                // Get course numbering (also check old field name for backwards compatibility)
                if ($fieldname === 'coursenumbering' || $fieldname === 'frontpagepriority') {
                    $value = $data->get_value();
                    if ($value !== null && $value !== '' && $value !== false) {
                        $coursenumber = (int)$value;
                    }
                }

                // Get course subnumbering
                if ($fieldname === 'coursesubnumbering') {
                    $value = $data->get_value();
                    if ($value !== null && $value !== '' && $value !== false) {
                        $coursesubnumber = (int)$value;
                    }
                }
            }

            // Only include courses marked to show on frontpage
            if ($showonfrontpage) {
                $filteredcourses[] = $course;
                $numbering[$course->id] = $coursenumber;
                $subnumbering[$course->id] = $coursesubnumber;
            }
        }

        // If no courses have the custom field set, show all courses (backwards compatibility)
        if (empty($filteredcourses)) {
            $filteredcourses = $courses;
        } else {
            // Sort by numbering and subnumbering (lower number first)
            usort($filteredcourses, function($a, $b) use ($numbering, $subnumbering) {
                // Treat null as 999 for sorting only
                $numbera = isset($numbering[$a->id]) && $numbering[$a->id] !== null ? $numbering[$a->id] : 999;
                $numberb = isset($numbering[$b->id]) && $numbering[$b->id] !== null ? $numbering[$b->id] : 999;

                // If main numbers are equal, sort by subnumber
                if ($numbera === $numberb) {
                    $subnuma = isset($subnumbering[$a->id]) ? $subnumbering[$a->id] : 0;
                    $subnumb = isset($subnumbering[$b->id]) ? $subnumbering[$b->id] : 0;
                    return $subnuma - $subnumb;
                }

                return $numbera - $numberb;
            });
        }

        // Store numbering and subnumbering for use in coursecat_coursebox
        $this->course_numbering = $numbering;
        $this->course_subnumbering = $subnumbering;

        $totalcount = count($filteredcourses);

        if (!$totalcount && !$this->page->user_is_editing() && has_capability('moodle/course:create', context_system::instance())) {
            return $this->add_new_course_button();
        }

        return $this->coursecat_courses($chelper, $filteredcourses, $totalcount);
    }

    /**
     * Renders the list of courses
     * Override to sort by course numbering custom field
     *
     * @param coursecat_helper $chelper various display options
     * @param array $courses the list of courses to display
     * @param int|null $totalcount total number of courses (affects display mode if it is AUTO or pagination if applicable),
     *     defaulted to count($courses)
     * @return string
     */
    protected function coursecat_courses(coursecat_helper $chelper, $courses, $totalcount = null) {
        global $CFG;

        if ($totalcount === null) {
            $totalcount = count($courses);
        }

        if (!$totalcount) {
            return '';
        }

        // Get numbering and subnumbering for all courses and sort them, filter hidden courses
        $numbering = array();
        $subnumbering = array();
        $visiblecourses = array();

        foreach ($courses as $course) {
            // Skip hidden courses unless user has capability to view them
            // Check at system context first (for site admins), then course context
            // Note: has_capability() automatically respects role switching, so admins viewing
            // as another role will see the course list as that role would see it
            $canviewhidden = has_capability('moodle/course:viewhiddencourses', context_system::instance()) ||
                             has_capability('moodle/course:viewhiddencourses', context_course::instance($course->id));

            if (!$course->visible && !$canviewhidden) {
                continue;
            }

            $visiblecourses[] = $course;

            $handler = \core_customfield\handler::get_handler('core_course', 'course');
            $datas = $handler->get_instance_data($course->id, true); // true = return data for all users

            $coursenumber = null; // No default - only set if custom field has value
            $coursesubnumber = 0; // Default

            foreach ($datas as $data) {
                $fieldname = $data->get_field()->get('shortname');

                if ($fieldname === 'coursenumbering' || $fieldname === 'frontpagepriority') {
                    $value = $data->get_value();
                    if ($value !== null && $value !== '' && $value !== false) {
                        $coursenumber = (int)$value;
                    }
                }

                if ($fieldname === 'coursesubnumbering') {
                    $value = $data->get_value();
                    if ($value !== null && $value !== '' && $value !== false) {
                        $coursesubnumber = (int)$value;
                    }
                }
            }

            $numbering[$course->id] = $coursenumber;
            $subnumbering[$course->id] = $coursesubnumber;
        }

        // Use only visible courses
        $courses = $visiblecourses;

        // Sort courses by numbering and subnumbering
        $coursesarray = array_values($courses);
        usort($coursesarray, function($a, $b) use ($numbering, $subnumbering) {
            // Treat null as 999 for sorting only
            $numbera = isset($numbering[$a->id]) && $numbering[$a->id] !== null ? $numbering[$a->id] : 999;
            $numberb = isset($numbering[$b->id]) && $numbering[$b->id] !== null ? $numbering[$b->id] : 999;

            // If main numbers are equal, sort by subnumber
            if ($numbera === $numberb) {
                $subnuma = isset($subnumbering[$a->id]) ? $subnumbering[$a->id] : 0;
                $subnumb = isset($subnumbering[$b->id]) ? $subnumbering[$b->id] : 0;
                return $subnuma - $subnumb;
            }

            return $numbera - $numberb;
        });

        // Store numbering and subnumbering for use in coursecat_coursebox
        $this->course_numbering = $numbering;
        $this->course_subnumbering = $subnumbering;

        // Call parent method with sorted courses
        return parent::coursecat_courses($chelper, $coursesarray, $totalcount);
    }
}
