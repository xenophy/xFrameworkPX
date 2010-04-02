<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {textformat}{/textformat} block plugin
 *
 * Type:     block function<br>
 * Name:     textformat<br>
 * Purpose:  format text a certain way with preset styles
 *           or custom wrap/indent settings<br>
 * @link http://smarty.php.net/manual/en/language.function.textformat.php {textformat}
 *       (Smarty online manual)
 * @param array
 * <pre>
 * Params:   style: string (email)
 *           indent: integer (0)
 *           wrap: integer (80)
 *           wrap_char string ("\n")
 *           indent_char: string (" ")
 *           wrap_boundary: boolean (true)
 * </pre>
 * @author Monte Ohrt <monte at ohrt dot com>
 * @param string contents of the block
 * @param Smarty clever simulation of a method
 * @return string string $content re-formatted
 */
function smarty_block_clean($params, $content, &$smarty)
{
    return preg_replace('/<!--[\s\S]*?-->/','',$content);
}

/* vim: set expandtab: */

?>
