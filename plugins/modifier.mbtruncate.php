<?php
/**
 * Multibyte truncate
 *
 * This plugin come from Smarty truncate modifier plugin.
 */
function smarty_modifier_mbtruncate($string, $length = 80, $etc = '…')
{
    if ($length == 0) {
        return '';
    }
    if (mb_strlen($string, 'utf-8') <= $length) {
        return $string;
    }
    if ($length < mb_strlen($etc, 'utf-8')) {
        return mb_substr($string, 0, $length, 'utf-8');
    }
    return mb_substr($string, 0, $length - mb_strlen($etc, 'utf-8'), 'utf-8') . $etc;
}
