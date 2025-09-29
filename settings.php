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
}
