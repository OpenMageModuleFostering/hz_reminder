<?php
$installer = $this;

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('reminder_subscriber')};
CREATE TABLE {$this->getTable('reminder_subscriber')} (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `title` varchar(255) NOT NULL DEFAULT '',
   `email` varchar(255) NOT NULL DEFAULT '',
   `product_sku` varchar(255) NOT NULL DEFAULT '',
   `product_name` varchar(255) NOT NULL DEFAULT '',
   `product_url` varchar(255) NOT NULL DEFAULT '',
   `register_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `reminder_date` date NOT NULL DEFAULT '0000-00-00',
   `ip` varchar(255) NOT NULL DEFAULT '',
   `sort_order` int(10) unsigned NOT NULL DEFAULT '0',
   `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
   PRIMARY KEY  (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 -- DROP TABLE IF EXISTS {$this->getTable('reminder_report')};
CREATE TABLE {$this->getTable('reminder_report')} (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `subscriber_id` int(11) DEFAULT NULL,
   `title` varchar(255) NOT NULL DEFAULT '',
   `email` varchar(255) NOT NULL DEFAULT '',
   `remind_date` date NOT NULL,
   `report_date` date NOT NULL,
   `report_date_and_time` datetime NOT NULL,
   `send_email_status` enum('yes','no') DEFAULT 'yes',
   `process_run` enum('admin','cron') DEFAULT 'cron',
   PRIMARY KEY  (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();