<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifierCompiler
 */

/**
 * Smarty amp modifier plugin
 * 
 * 
 * @link http://smarty.php.net/manual/en/language.modifier.amp.php lower (Smarty online manual)
 * Example:  {$var|amp} {$var|amp:"&nbsp;"}<br>
 */
function smarty_modifiercompiler_amp($params, $compiler)
{
    $params[0] = str_replace('&amp;','&',$params[0]);
    if (!isset($params[1])) $params[0] = str_replace('&','&amp;',$params[0]);
    return $params[0];
} 

?>