<?php
defined("MOODLE_INTERNAL") || die();

$plugin->type = "theme";
$plugin->version = "2025100602"; // Added course subnumbering field and admin visibility for hidden courses
$plugin->requires = 2024042200; // Moodle 4.4+ required.
$plugin->component = "theme_alife"; // Full name of the plugin.
$plugin->dependencies = [
    "theme_boost" => 2024042200, // Requires Boost theme.
];
