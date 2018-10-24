<?php

/**
 * DOES NOT SANITIZE: esc_html for tests without loading WordPress
 *
 * @param string $value
 * @return string
 */
function esc_html($value) {
    return $value;
}


/**
 * DOES NOT SANITIZE: esc_attr for tests without loading WordPress
 *
 * @param string $value
 * @return string
 */
function esc_attr($value) {
    return $value;
}