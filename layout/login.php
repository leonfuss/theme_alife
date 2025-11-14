<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);

$bodyattributes = $OUTPUT->body_attributes(['alife-login-page']);
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), 'escape' => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes
];

echo $OUTPUT->render_from_template('theme_alife/login', $templatecontext);
