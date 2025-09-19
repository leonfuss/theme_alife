<?php

defined("MOODLE_INTERNAL") || die();

if ($ADMIN->fulltree) {
    // Boost provides a nice setting page which splits settings onto separate tabs.
    $settings = new theme_boost_admin_settingspage_tabs(
        "themesettingalife",
        get_string("configtitle", "theme_alife"),
    );

    // Impressum settings page.
    $page = new admin_settingpage(
        "theme_alife_impressum",
        get_string("impressum_heading", "theme_alife"),
    );

    // German impressum content
    $setting = new admin_setting_confightmleditor(
        "theme_alife/impressum_content_de",
        get_string("impressum_content_de", "theme_alife"),
        get_string("impressum_content_de_desc", "theme_alife"),
        "",
        PARAM_RAW,
    );
    $page->add($setting);

    // English impressum content
    $setting = new admin_setting_confightmleditor(
        "theme_alife/impressum_content_en",
        get_string("impressum_content_en", "theme_alife"),
        get_string("impressum_content_en_desc", "theme_alife"),
        "",
        PARAM_RAW,
    );
    $page->add($setting);

    // Must add the page after defining all the settings!
    $settings->add($page);

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
