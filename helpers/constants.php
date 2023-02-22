<?php

if (! defined('VIG_THEME_EDITOR_MODULE_SCREEN_NAME')) {
    define('VIG_THEME_EDITOR_MODULE_SCREEN_NAME', 'vig-theme-editor');
}

if (! function_exists('vig_iteration_loop')) {
    function vig_iteration_loop($loop)
    {
        $string = '⎸⏤';
        $get_loop = $loop;
        for ($i =  2; $i <= $loop->depth; $i++) {
            $get_loop = $get_loop->parent;
            $string = $string . '⏤';
        }

        return $string;
    }
}
