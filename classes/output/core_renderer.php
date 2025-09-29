<?php

namespace theme_alife\output;

defined("MOODLE_INTERNAL") || die();

class core_renderer extends \theme_boost\output\core_renderer
{
    /**
     * Check if user is logged in and not a guest
     *
     * @return bool
     */
    public function user_logged_in()
    {
        return isloggedin() && !isguestuser();
    }
}
