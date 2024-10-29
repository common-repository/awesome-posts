<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

class ARIFIX_AP_Helper
{
    public function __construct()
    {
        add_filter('wp_kses_allowed_html', [$this, 'arifix_ap_allow_svg_in_wp_kses']);
        add_filter('upload_mimes', [$this, 'arifix_ap_add_font_upload_mimes']);
        add_filter('wp_check_filetype_and_ext', [$this, 'arifix_ap_add_allow_upload_extension_exception'], 99, 4);
    }

    function arifix_ap_allow_svg_in_wp_kses($allowed_tags)
    {
        $allowed_tags['svg'] = array(
            'fill' => true,
            'stroke' => true,
            'class' => true,
            'aria-hidden' => true,
            'aria-labelledby' => true,
            'role' => true,
            'xmlns' => true,
            'width' => true,
            'height' => true,
            'viewbox' => true,
        );

        $allowed_tags['g'] = array('fill' => true);
        $allowed_tags['title'] = array('title' => true);
        $allowed_tags['path'] = array(
            'd' => true,
            'fill' => true,
            'stroke-linecap' => true,
            'stroke-linejoin' => true,
            'stroke-width' => true,
        );

        return $allowed_tags;
    }

    static function arifix_ap_fonts_url_to_name($font_url)
    {
        $font_url_ar = explode("/", $font_url);
        $font_name = explode(".", $font_url_ar[count($font_url_ar) - 1]);
        return $font_name[0];
    }

    function arifix_ap_add_font_upload_mimes($existing_mimes)
    {
        $existing_mimes['ttf'] = 'font/ttf';
        $existing_mimes['otf'] = 'font/otf';
        $existing_mimes['woff'] = 'font/woff';
        $existing_mimes['woff2'] = 'font/woff2';
        $existing_mimes['json'] = 'application/json';

        return $existing_mimes;
    }

    function arifix_ap_add_allow_upload_extension_exception($types, $file, $filename, $mimes)
    {
        $wp_filetype = wp_check_filetype($filename, $mimes);
        $ext         = $wp_filetype['ext'];
        $type        = $wp_filetype['type'];
        if (in_array($ext, array('ttf', 'otf', 'woff', 'woff2', 'json'))) {
            $types['ext'] = $ext;
            $types['type'] = $type;
        }
        return $types;
    }

    static function arifix_ap_get_text_limit_by($text, $limit, $type = 'character', $rest = '...')
    {
        if ($limit) {
            if ($type === 'word') {
                $words = explode(' ', $text);
                if (count($words) > $limit) {
                    return implode(' ', array_slice($words, 0, $limit)) . $rest;
                }
            } elseif ($type === 'character') {
                if (strlen($text) > $limit) {
                    return substr($text, 0, $limit) . $rest;
                }
            }
        }

        return $text;
    }

    static function arifix_ap_check_string_contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}

new ARIFIX_AP_Helper();
