<?php

$installer = $this;

$installer->startSetup();

//$installer->run("
//
// DROP TABLE IF EXISTS 'couponallopass';
//CREATE TABLE 'couponallopass' (
//  `allopass_id` int(11) unsigned NOT NULL auto_increment,
//  `customer_id` varchar(255) NOT NULL default '',
//  `code` varchar(255) NOT NULL default '',
//  `update_time` datetime NULL,
//  PRIMARY KEY (`allopass_id`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8;
//
//    ");

$installer->endSetup();
