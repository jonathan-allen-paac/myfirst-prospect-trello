<?php

function str_trim(string $str): string {
    // Remove leading and trailing whitespace including non-breaking space (U+00A0)
    return preg_replace('/^\s+|\s+$/u', '', $str);
}

function str_clean(string $str): string {
    // Remove all whitespace characters including U+00A0
    return preg_replace('/\s+/u', '', $str);
}
