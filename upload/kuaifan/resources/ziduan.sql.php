<?php
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
$defaultvalue = isset($addziduan['setting']['defaultvalue']) ? $addziduan['setting']['defaultvalue'] : '';
//正整数 UNSIGNED && SIGNED
$minnumber = isset($addziduan['setting']['minnumber']) ? $addziduan['setting']['minnumber'] : 1;
$decimaldigits = isset($addziduan['setting']['decimaldigits']) ? $addziduan['setting']['decimaldigits'] : '';

switch($field_type) {
	case 'varchar':
		if(!$maxlength) $maxlength = 255;
		$maxlength = min($maxlength, 255);
		$sql = "ALTER TABLE `$tablename` ADD `$field` VARCHAR( $maxlength ) NOT NULL DEFAULT '$defaultvalue'";
		$db->query($sql);
	break;

	case 'tinyint':
		if(!$maxlength) $maxlength = 3;
		$minnumber = intval($minnumber);
		$defaultvalue = intval($defaultvalue);
		$db->query("ALTER TABLE `$tablename` ADD `$field` TINYINT( $maxlength ) ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue'");
	break;
	
	case 'number':
		$minnumber = intval($minnumber);
		$defaultvalue = $decimaldigits == 0 ? intval($defaultvalue) : floatval($defaultvalue);
		$sql = "ALTER TABLE `$tablename` ADD `$field` ".($decimaldigits == 0 ? 'INT' : 'FLOAT')." ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue'";
		$db->query($sql);
	break;

	case 'smallint':
		$minnumber = intval($minnumber);
		$db->query("ALTER TABLE `$tablename` ADD `$field` SMALLINT ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL");
	break;

	case 'int':
		$minnumber = intval($minnumber);
		$defaultvalue = intval($defaultvalue);
		$sql = "ALTER TABLE `$tablename` ADD `$field` INT ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue'";
		$db->query($sql);
	break;

	case 'mediumint':
		$minnumber = intval($minnumber);
		$defaultvalue = intval($defaultvalue);
		$sql = "ALTER TABLE `$tablename` ADD `$field` INT ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue'";
		$db->query($sql);
	break;

	case 'mediumtext':
		$db->query("ALTER TABLE `$tablename` ADD `$field` MEDIUMTEXT NOT NULL");
	break;
	
	case 'text':
		$db->query("ALTER TABLE `$tablename` ADD `$field` TEXT NOT NULL");
	break;

	case 'date':
		$db->query("ALTER TABLE `$tablename` ADD `$field` DATE NULL");
	break;
	
	case 'datetime':
		$db->query("ALTER TABLE `$tablename` ADD `$field` DATETIME NULL");
	break;
	
	case 'timestamp':
		$db->query("ALTER TABLE `$tablename` ADD `$field` TIMESTAMP NOT NULL");
	break;
	//特殊自定义字段
	case 'pages':
		$db->query("ALTER TABLE `$tablename` ADD `paginationtype` TINYINT( 1 ) NOT NULL DEFAULT '0'");
		$db->query("ALTER TABLE `$tablename` ADD `maxcharperpage` MEDIUMINT( 6 ) NOT NULL DEFAULT '0'");
	break;
	case 'readpoint':
		$defaultvalue = intval($defaultvalue);
		$db->query("ALTER TABLE `$tablename` ADD `readpoint` smallint(5) unsigned NOT NULL default '$defaultvalue'");
		$db->query("ALTER TABLE `$tablename` ADD `paytype` tinyint(1) unsigned NOT NULL default '0'");
	break;
}
?>