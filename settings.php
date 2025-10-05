<?php

defined("MOODLE_INTERNAL") || die();

if ($ADMIN->fulltree) {
    // Boost provides a nice setting page which splits settings onto separate tabs.
    $settings = new theme_boost_admin_settingspage_tabs(
        "themesettingalife",
        get_string("configtitle", "theme_alife"),
    );

    // Advanced settings.
    $page = new admin_settingpage(
        "theme_alife_advanced",
        get_string("advancedsettings", "theme_alife"),
    );

    // Raw SCSS to include before the content.
    $setting = new admin_setting_configtextarea(
        "theme_alife/scsspre",
        get_string("rawscsspre", "theme_alife"),
        get_string("rawscsspre_desc", "theme_alife"),
        "",
        PARAM_RAW,
    );
    $setting->set_updatedcallback("theme_reset_all_caches");
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_configtextarea(
        "theme_alife/scss",
        get_string("rawscss", "theme_alife"),
        get_string("rawscss_desc", "theme_alife"),
        "",
        PARAM_RAW,
    );
    $setting->set_updatedcallback("theme_reset_all_caches");
    $page->add($setting);

    $settings->add($page);

    // Team Members settings page
    $page = new admin_settingpage(
        'theme_alife_team',
        get_string('team_settings', 'theme_alife')
    );

    // Number of team members
    $name = 'theme_alife/member_count';
    $title = get_string('member_count', 'theme_alife');
    $description = get_string('member_count_desc', 'theme_alife');
    $default = 5;
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $page->add($setting);

    // Get current member count
    $membercount = get_config('theme_alife', 'member_count');
    if (empty($membercount)) {
        $membercount = 5;
    }

    // Settings for each team member
    for ($i = 1; $i <= $membercount; $i++) {
        // Heading
        $name = "theme_alife/member_{$i}_heading";
        $heading = get_string('team_members', 'theme_alife') . ' ' . $i;
        $setting = new admin_setting_heading($name, $heading, '');
        $page->add($setting);

        // Enable/disable
        $name = "theme_alife/member_{$i}_enabled";
        $title = get_string('member_enabled', 'theme_alife', $i);
        $description = get_string('member_enabled_desc', 'theme_alife', $i);
        $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
        $page->add($setting);

        // Name
        $name = "theme_alife/member_{$i}_name";
        $title = get_string('member_name', 'theme_alife', $i);
        $description = get_string('member_name_desc', 'theme_alife', $i);
        $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_TEXT);
        $page->add($setting);

        // Photo
        $name = "theme_alife/member_{$i}_photo";
        $title = get_string('member_photo', 'theme_alife', $i);
        $description = get_string('member_photo_desc', 'theme_alife', $i);
        $setting = new admin_setting_configstoredfile($name, $title, $description, "member_{$i}_photo");
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Bio DE
        $name = "theme_alife/member_{$i}_bio_de";
        $title = get_string('member_bio_de', 'theme_alife', $i);
        $description = get_string('member_bio_de_desc', 'theme_alife', $i);
        $setting = new admin_setting_confightmleditor($name, $title, $description, '');
        $page->add($setting);

        // Bio EN
        $name = "theme_alife/member_{$i}_bio_en";
        $title = get_string('member_bio_en', 'theme_alife', $i);
        $description = get_string('member_bio_en_desc', 'theme_alife', $i);
        $setting = new admin_setting_confightmleditor($name, $title, $description, '');
        $page->add($setting);

        // URL
        $name = "theme_alife/member_{$i}_url";
        $title = get_string('member_url', 'theme_alife', $i);
        $description = get_string('member_url_desc', 'theme_alife', $i);
        $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
        $page->add($setting);

        // Sort order
        $name = "theme_alife/member_{$i}_sortorder";
        $title = get_string('member_sortorder', 'theme_alife', $i);
        $description = get_string('member_sortorder_desc', 'theme_alife', $i);
        $setting = new admin_setting_configtext($name, $title, $description, $i, PARAM_INT);
        $page->add($setting);
    }

    $settings->add($page);
}
