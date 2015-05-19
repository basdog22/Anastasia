<?php
/**
 * @summary Accept shortcode registration like WordPress. Useful for theme creators
 *
 * @param $name
 * @param $func
 * @param int $priority
 */
function add_shortcode($name, $func, $priority = 1)
{
    register_shortcode($name, $func, '', $priority);
}

/**
 * @summary Return an array with the attributes of the shortcode.
 *
 * @param $atts
 * @param $shortcode
 * @return array
 */
function shortcode_atts($atts, $shortcode)
{
    $attr = array();
    foreach ($atts as $k => $v) {
        $attr[$k] = ($shortcode->$k) ? $shortcode->$k : $v;
    }
    return $attr;
}

/**
 * @param string $doctype
 */
function language_attributes($doctype = 'html')
{
    $attributes = array();

    if (function_exists('is_rtl') && is_rtl())
        $attributes[] = 'dir="rtl"';

    if ($lang = get_current_locale()) {
        if (get_config_value('html_type') == 'text/html' || $doctype == 'html')
            $attributes[] = "lang=\"$lang\"";

        if (get_config_value('html_type') != 'text/html' || $doctype == 'xhtml')
            $attributes[] = "xml:lang=\"$lang\"";
    }

    $output = implode(' ', $attributes);

    /**
     * Filter the language attributes for display in the html tag.
     *
     * @since 2.5.0
     *
     * @param string $output A space-separated list of language attributes.
     */
    return $output;
}

/**
 * Checks if current locale is RTL.
 *
 *
 * @return bool Whether locale is RTL.
 */
function is_rtl() {
    return 'rtl' == get_config_value('text_direction');
}