/*
Navicat MySQL Data Transfer

Source Server         : beta.lmis
Source Server Version : 50641
Source Host           : 202.83.174.107:3306
Source Database       : beta_vlmis15032018

Target Server Type    : MYSQL
Target Server Version : 50641
File Encoding         : 65001

Date: 2019-04-26 17:45:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for aefi_form
-- ----------------------------
DROP TABLE IF EXISTS `aefi_form`;
CREATE TABLE `aefi_form` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NULL DEFAULT NULL COMMENT 'List Detail Table - MasterId 33',
  `district_id` int(11) DEFAULT NULL,
  `tehsil_id` int(11) DEFAULT NULL,
  `uc_id` int(11) DEFAULT NULL,
  `hf_id` int(11) DEFAULT NULL,
  `hf_name` varchar(255) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL COMMENT 'List Detail Table - MasterId 34',
  `dob` datetime DEFAULT NULL,
  `age_in_months` int(11) DEFAULT NULL COMMENT 'Auto populated from dob',
  `date_vaccine_given` datetime DEFAULT NULL,
  `date_aefi_onset` datetime DEFAULT NULL,
  `date_of_investigation` datetime DEFAULT NULL,
  `aefi` int(11) DEFAULT NULL COMMENT 'List Detail Table- MasterID 35',
  `aefi_other` varchar(255) DEFAULT NULL,
  `hospitalization` int(11) DEFAULT NULL COMMENT 'List Detail Table - MasterID 36',
  `type_case` int(11) DEFAULT NULL COMMENT 'List Detail Table - - MasterID 38',
  `death` int(11) DEFAULT NULL COMMENT 'List Detail Table - - MasterID 37',
  `vaccine_antigen` int(11) DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `child_name` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `address_village` varchar(255) DEFAULT NULL,
  `age_unit` varchar(255) DEFAULT NULL,
  `week` varchar(255) DEFAULT NULL,
  `reporting_status` int(11) DEFAULT NULL,
  `date_notification` timestamp NULL DEFAULT NULL COMMENT 'List Detail Table - MasterId 33',
  PRIMARY KEY (`pk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for alerts
-- ----------------------------
DROP TABLE IF EXISTS `alerts`;
CREATE TABLE `alerts` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `person_name` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `cell_number` varchar(255) DEFAULT NULL,
  `stkid` varchar(100) DEFAULT NULL,
  `prov_id` int(11) DEFAULT NULL,
  `level` enum('Summary','Detail','All') DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for alerts_log
-- ----------------------------
DROP TABLE IF EXISTS `alerts_log`;
CREATE TABLE `alerts_log` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `to` text,
  `cc` text,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `response` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `interface` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15764 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for barcode_scanner_warehouses
-- ----------------------------
DROP TABLE IF EXISTS `barcode_scanner_warehouses`;
CREATE TABLE `barcode_scanner_warehouses` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `scanner_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `scanner_id` (`scanner_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `barcode_scanner_warehouses_ibfk_1` FOREIGN KEY (`scanner_id`) REFERENCES `barcode_scanners` (`pk_id`),
  CONSTRAINT `barcode_scanner_warehouses_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `barcode_scanner_warehouses_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `barcode_scanner_warehouses_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for barcode_scanners
-- ----------------------------
DROP TABLE IF EXISTS `barcode_scanners`;
CREATE TABLE `barcode_scanners` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `scanner_name` varchar(200) DEFAULT NULL,
  `licence_number` varchar(30) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `barcode_scanners_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `barcode_scanners_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_data
-- ----------------------------
DROP TABLE IF EXISTS `campaign_data`;
CREATE TABLE `campaign_data` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_day` int(11) DEFAULT NULL,
  `daily_target` int(11) DEFAULT NULL,
  `target_age_six_months` int(11) DEFAULT NULL,
  `target_age_sixty_months` int(11) DEFAULT NULL,
  `household_visited` int(11) DEFAULT NULL,
  `multiple_family_household` int(11) DEFAULT NULL,
  `total_coverage` varchar(255) DEFAULT NULL,
  `refusal_covered` varchar(255) DEFAULT NULL,
  `record_reference` varchar(255) DEFAULT NULL,
  `coverage_not_accessible` varchar(255) DEFAULT NULL,
  `record_not_accessible` varchar(255) DEFAULT NULL,
  `record_refusal` varchar(255) DEFAULT NULL,
  `coverage_mobile_children` varchar(255) DEFAULT NULL,
  `reported_with_weakness` varchar(255) DEFAULT NULL,
  `zero_doses` varchar(255) DEFAULT NULL,
  `coverage_reference` varchar(255) DEFAULT NULL,
  `inaccessible_coverage` varchar(255) DEFAULT NULL,
  `teams_reported` int(11) DEFAULT NULL,
  `vials_given` varchar(255) DEFAULT NULL,
  `vials_used` varchar(255) DEFAULT NULL,
  `vials_returned` varchar(255) DEFAULT NULL,
  `vials_expired` varchar(255) DEFAULT NULL,
  `recon_syr_wasted` int(11) DEFAULT NULL,
  `ad_syr_wasted` int(255) DEFAULT NULL,
  `warehouse_id` int(11) NOT NULL,
  `item_pack_size_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `union_council_id` int(11) NOT NULL,
  `campaign_target_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `campaign_data_campaigns_fk3` (`campaign_id`) USING BTREE,
  KEY `campaign_data_locations_fk4` (`district_id`) USING BTREE,
  KEY `campaign_data_campaign_targets_fk6` (`campaign_target_id`) USING BTREE,
  KEY `campaign_data_users_fk7` (`created_by`) USING BTREE,
  KEY `campaign_data_users_fk8` (`modified_by`) USING BTREE,
  KEY `campaign_data_locations_fk5` (`union_council_id`) USING BTREE,
  KEY `campaign_data_warehouses_fk1` (`warehouse_id`) USING BTREE,
  KEY `campaign_data_item_pack_sizes_fk2` (`item_pack_size_id`) USING BTREE,
  CONSTRAINT `campaign_data_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `campaign_data_ibfk_2` FOREIGN KEY (`item_pack_size_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `campaign_data_ibfk_3` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`pk_id`),
  CONSTRAINT `campaign_data_ibfk_4` FOREIGN KEY (`district_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `campaign_data_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `campaign_data_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5340 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_districts
-- ----------------------------
DROP TABLE IF EXISTS `campaign_districts`;
CREATE TABLE `campaign_districts` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `campaign_districts_campaigns_fk1` (`campaign_id`) USING BTREE,
  KEY `campaign_districts_locations_fk2` (`district_id`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `campaign_districts_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`pk_id`),
  CONSTRAINT `campaign_districts_ibfk_2` FOREIGN KEY (`district_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `campaign_districts_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `campaign_districts_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=548 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_item_pack_sizes
-- ----------------------------
DROP TABLE IF EXISTS `campaign_item_pack_sizes`;
CREATE TABLE `campaign_item_pack_sizes` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_pack_size_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `campaign_item_pack_sizes_item_pack_sizes_fk1` (`item_pack_size_id`) USING BTREE,
  KEY `campaign_item_pack_sizes_campaigns_fk2` (`campaign_id`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `campaign_item_pack_sizes_ibfk_1` FOREIGN KEY (`item_pack_size_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `campaign_item_pack_sizes_ibfk_2` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`pk_id`),
  CONSTRAINT `campaign_item_pack_sizes_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `campaign_item_pack_sizes_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_lqas_data
-- ----------------------------
DROP TABLE IF EXISTS `campaign_lqas_data`;
CREATE TABLE `campaign_lqas_data` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `surveyor` varchar(255) DEFAULT NULL,
  `checked` varchar(255) DEFAULT NULL,
  `unvaccinated` varchar(255) DEFAULT NULL,
  `remarks` text,
  `campaign_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `union_council_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `campaign_lqas_data_ibfk_1` (`campaign_id`) USING BTREE,
  KEY `campaign_lqas_data_ibfk_2` (`district_id`) USING BTREE,
  KEY `campaign_lqas_data_ibfk_3` (`created_by`) USING BTREE,
  KEY `campaign_lqas_data_ibfk_4` (`modified_by`) USING BTREE,
  KEY `campaign_lqas_data_ibfk_5` (`union_council_id`) USING BTREE,
  CONSTRAINT `campaign_lqas_data_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`pk_id`),
  CONSTRAINT `campaign_lqas_data_ibfk_2` FOREIGN KEY (`district_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `campaign_lqas_data_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `campaign_lqas_data_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `campaign_lqas_data_ibfk_5` FOREIGN KEY (`union_council_id`) REFERENCES `warehouses` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_readiness
-- ----------------------------
DROP TABLE IF EXISTS `campaign_readiness`;
CREATE TABLE `campaign_readiness` (
  `pk_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vaccine_arrival_date` datetime DEFAULT NULL,
  `dco_attended_meeting` tinyint(1) DEFAULT NULL,
  `edo_attended_meeting` tinyint(1) DEFAULT NULL,
  `all_members_attended_meeting` tinyint(1) DEFAULT NULL,
  `num_tally_sheets` int(11) DEFAULT NULL,
  `num_finger_markers` int(11) DEFAULT NULL,
  `arrival_date_mobilization_material` datetime DEFAULT NULL,
  `dpec_meeting_date` datetime DEFAULT NULL,
  `remarks` text,
  `campaign_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `campaign_readiness_campaigns_fk1` (`campaign_id`) USING BTREE,
  KEY `campaign_readiness_locations_fk2` (`district_id`) USING BTREE,
  KEY `campaign_readiness_users_fk4` (`modified_by`) USING BTREE,
  KEY `campaign_readiness_users_fk3` (`created_by`) USING BTREE,
  CONSTRAINT `campaign_readiness_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`pk_id`),
  CONSTRAINT `campaign_readiness_ibfk_2` FOREIGN KEY (`district_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `campaign_readiness_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `campaign_readiness_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_readiness_union_council
-- ----------------------------
DROP TABLE IF EXISTS `campaign_readiness_union_council`;
CREATE TABLE `campaign_readiness_union_council` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `inaccessible_children` varchar(100) DEFAULT NULL,
  `inaccessible_area` varchar(100) DEFAULT NULL,
  `number_mobile_teams` int(11) DEFAULT NULL,
  `number_fixed_teams` int(11) DEFAULT NULL,
  `number_transit_points` int(11) DEFAULT NULL,
  `aic_trained` int(11) DEFAULT NULL,
  `number_teams_trained` int(11) DEFAULT NULL,
  `mobile_population_areas` int(11) DEFAULT NULL,
  `upec_meeting_date` datetime DEFAULT NULL,
  `union_council_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `campaign_readiness_union_council_users_fk3` (`created_by`) USING BTREE,
  KEY `campaign_readiness_union_council_users_fk4` (`modified_by`) USING BTREE,
  KEY `campaign_readiness_union_council_campaigns_fk2` (`campaign_id`) USING BTREE,
  KEY `campaign_readiness_union_council_warehouses_fk1` (`union_council_id`) USING BTREE,
  CONSTRAINT `campaign_readiness_union_council_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`pk_id`),
  CONSTRAINT `campaign_readiness_union_council_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `campaign_readiness_union_council_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_targets
-- ----------------------------
DROP TABLE IF EXISTS `campaign_targets`;
CREATE TABLE `campaign_targets` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `daily_target` int(11) DEFAULT NULL,
  `campaign_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `item_pack_size_id` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `campaign_targets_campaigns_fk1` (`campaign_id`) USING BTREE,
  KEY `campaign_targets_users_fk3` (`created_by`) USING BTREE,
  KEY `campaign_targets_users_fk4` (`modified_by`) USING BTREE,
  KEY `campaign_targets_locations_fk2` (`warehouse_id`) USING BTREE,
  KEY `item_pack_size_id` (`item_pack_size_id`),
  CONSTRAINT `campaign_targets_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`pk_id`),
  CONSTRAINT `campaign_targets_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `campaign_targets_ibfk_3` FOREIGN KEY (`item_pack_size_id`) REFERENCES `item_pack_sizes` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2286 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_types
-- ----------------------------
DROP TABLE IF EXISTS `campaign_types`;
CREATE TABLE `campaign_types` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `camapign_type_name` varchar(100) DEFAULT NULL,
  `list_rank` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `campaign_types_users_fk1` (`created_by`) USING BTREE,
  KEY `campaign_types_users_fk2` (`modified_by`) USING BTREE,
  CONSTRAINT `campaign_types_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `campaign_types_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaigns
-- ----------------------------
DROP TABLE IF EXISTS `campaigns`;
CREATE TABLE `campaigns` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_name` varchar(100) DEFAULT NULL,
  `date_from` datetime DEFAULT NULL,
  `date_to` datetime DEFAULT NULL,
  `catch_up_days` int(11) DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL,
  `campaign_type_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `campaigns_campaign_types_fk1` (`campaign_type_id`) USING BTREE,
  KEY `campaigns_users_fk3` (`created_by`) USING BTREE,
  KEY `campaigns_users_fk4` (`modified_by`) USING BTREE,
  CONSTRAINT `campaigns_ibfk_1` FOREIGN KEY (`campaign_type_id`) REFERENCES `campaign_types` (`pk_id`),
  CONSTRAINT `campaigns_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `campaigns_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_asset_types
-- ----------------------------
DROP TABLE IF EXISTS `ccm_asset_types`;
CREATE TABLE `ccm_asset_types` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_type_name` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '0=inactive, 1=active',
  `parent_id` int(11) DEFAULT NULL,
  `ccm_equipment_type_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_asset_types_ccm_equipment_types_fk1` (`ccm_equipment_type_id`) USING BTREE,
  KEY `ccm_asset_types_user_fk2` (`created_by`) USING BTREE,
  KEY `ccm_asset_types_user_fk3` (`modified_by`) USING BTREE,
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `ccm_asset_types_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_asset_types_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_asset_types_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `ccm_asset_types` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_cold_rooms
-- ----------------------------
DROP TABLE IF EXISTS `ccm_cold_rooms`;
CREATE TABLE `ccm_cold_rooms` (
  `pk_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cooling_system` int(11) DEFAULT NULL,
  `has_voltage` tinyint(1) DEFAULT NULL,
  `ccm_asset_sub_type_id` int(11) DEFAULT NULL,
  `ccm_id` int(11) NOT NULL,
  `temperature_recording_system` int(11) DEFAULT NULL,
  `type_recording_system` int(11) DEFAULT NULL,
  `refrigerator_gas_type` int(11) DEFAULT NULL,
  `backup_generator` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_cold_rooms_ccm_asset_types_fk1` (`ccm_asset_sub_type_id`) USING BTREE,
  KEY `ccm_cold_rooms_coldchain_fk2` (`ccm_id`) USING BTREE,
  KEY `ccm_cold_rooms_users_fk7` (`created_by`) USING BTREE,
  KEY `ccm_cold_rooms_users_fk8` (`modified_by`) USING BTREE,
  KEY `ccm_cold_rooms_temperature_recording_system__list_detail_fk3` (`temperature_recording_system`) USING BTREE,
  KEY `ccm_cold_rooms_type_recording_system_list_detail_fk4` (`type_recording_system`) USING BTREE,
  KEY `ccm_cold_rooms_refrigerator_gas_type_list_detail_fk5` (`refrigerator_gas_type`) USING BTREE,
  KEY `ccm_cold_rooms_backup_generator_list_detail_fk6` (`backup_generator`) USING BTREE,
  CONSTRAINT `ccm_cold_rooms_ibfk_1` FOREIGN KEY (`ccm_asset_sub_type_id`) REFERENCES `ccm_asset_types` (`pk_id`),
  CONSTRAINT `ccm_cold_rooms_ibfk_2` FOREIGN KEY (`ccm_id`) REFERENCES `cold_chain` (`pk_id`),
  CONSTRAINT `ccm_cold_rooms_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_cold_rooms_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=394 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_generators
-- ----------------------------
DROP TABLE IF EXISTS `ccm_generators`;
CREATE TABLE `ccm_generators` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `power_source` int(11) NOT NULL,
  `power_rating` varchar(50) DEFAULT NULL,
  `automatic_start_mechanism` tinyint(1) DEFAULT NULL,
  `use_for` varchar(50) DEFAULT NULL,
  `ccm_id` int(11) DEFAULT NULL,
  `cold_room_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_generator_users_fk3` (`created_by`) USING BTREE,
  KEY `ccm_generator_users_fk4` (`modified_by`) USING BTREE,
  KEY `ccm_generator_cold_chain_fk2` (`ccm_id`) USING BTREE,
  KEY `ccm_generator_list_detail_fk1` (`power_source`) USING BTREE,
  CONSTRAINT `ccm_generators_ibfk_1` FOREIGN KEY (`ccm_id`) REFERENCES `cold_chain` (`pk_id`),
  CONSTRAINT `ccm_generators_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_generators_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1972 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_history
-- ----------------------------
DROP TABLE IF EXISTS `ccm_history`;
CREATE TABLE `ccm_history` (
  `pk_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `quantity` int(11) DEFAULT NULL,
  `action` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `ccm_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_history_warehouses_fk2` (`warehouse_id`) USING BTREE,
  KEY `ccm_history_cold_chain_fk3` (`ccm_id`) USING BTREE,
  KEY `ccm_history_users_fk4` (`created_by`) USING BTREE,
  KEY `ccm_history_list_detail_fk1` (`action`) USING BTREE,
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `ccm_history_ibfk_1` FOREIGN KEY (`ccm_id`) REFERENCES `cold_chain` (`pk_id`),
  CONSTRAINT `ccm_history_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_history_ibfk_3` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `ccm_history_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_history_ibfk_5` FOREIGN KEY (`action`) REFERENCES `list_detail` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_human_resources
-- ----------------------------
DROP TABLE IF EXISTS `ccm_human_resources`;
CREATE TABLE `ccm_human_resources` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `designation` varchar(50) DEFAULT NULL,
  `mobile_number` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `log_date` datetime DEFAULT NULL,
  `ccm_person_type` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_human_resources_list_detail_fk1` (`ccm_person_type`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `ccm_human_resources_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_human_resources_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_makes
-- ----------------------------
DROP TABLE IF EXISTS `ccm_makes`;
CREATE TABLE `ccm_makes` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `ccm_make_name` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '0=inactive, 1=active',
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_make_users_fk1` (`created_by`) USING BTREE,
  KEY `ccm_make_users_fk2` (`modified_by`) USING BTREE,
  CONSTRAINT `ccm_makes_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_makes_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=736 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_models
-- ----------------------------
DROP TABLE IF EXISTS `ccm_models`;
CREATE TABLE `ccm_models` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `ccm_model_name` varchar(100) DEFAULT NULL,
  `asset_dimension_length` decimal(11,2) DEFAULT NULL,
  `asset_dimension_width` decimal(11,2) DEFAULT NULL,
  `asset_dimension_height` decimal(11,2) DEFAULT NULL,
  `gross_capacity_20` double DEFAULT NULL,
  `gross_capacity_4` double DEFAULT NULL,
  `net_capacity_20` double DEFAULT NULL,
  `net_capacity_4` double DEFAULT NULL,
  `cfc_free` tinyint(1) DEFAULT NULL,
  `gas_type` int(11) DEFAULT NULL,
  `no_of_phases` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '0=inactive, 1=active, 2=draft',
  `reasons` varchar(100) DEFAULT '',
  `utilizations` varchar(100) DEFAULT NULL,
  `temperature_type` tinyint(1) DEFAULT NULL COMMENT '0=positive, 1=negative, 2=both',
  `catalogue_id` varchar(50) DEFAULT NULL,
  `ccm_make_id` int(11) DEFAULT NULL,
  `ccm_asset_type_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cold_life` varchar(50) DEFAULT NULL COMMENT 'cold life without opening',
  `product_price` int(11) DEFAULT NULL,
  `power_source` int(11) DEFAULT NULL,
  `internal_dimension_length` int(11) DEFAULT NULL,
  `internal_dimension_width` int(11) DEFAULT NULL,
  `internal_dimension_height` int(11) DEFAULT NULL,
  `storage_dimension_length` int(11) DEFAULT NULL,
  `storage_dimension_width` int(11) DEFAULT NULL,
  `storage_dimension_height` int(11) DEFAULT NULL,
  `is_pqs` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_models_ibfk_3` (`modified_by`) USING BTREE,
  KEY `ccm_models_ibfk_2` (`created_by`) USING BTREE,
  KEY `ccm_models_ibfk_4` (`ccm_make_id`) USING BTREE,
  KEY `ccm_models_ibfk_1` (`ccm_asset_type_id`) USING BTREE,
  KEY `ccm_models_ibfk_5` (`gas_type`) USING BTREE,
  CONSTRAINT `ccm_models_ibfk_1` FOREIGN KEY (`ccm_asset_type_id`) REFERENCES `ccm_asset_types` (`pk_id`),
  CONSTRAINT `ccm_models_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_models_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_models_ibfk_4` FOREIGN KEY (`ccm_make_id`) REFERENCES `ccm_makes` (`pk_id`),
  CONSTRAINT `ccm_models_ibfk_5` FOREIGN KEY (`gas_type`) REFERENCES `list_detail` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13992 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_status_history
-- ----------------------------
DROP TABLE IF EXISTS `ccm_status_history`;
CREATE TABLE `ccm_status_history` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `temperature_alarm` int(11) DEFAULT NULL,
  `freeze_alarm` int(11) DEFAULT NULL,
  `heat_alarm` int(11) DEFAULT NULL,
  `working_quantity` int(11) DEFAULT NULL,
  `comments` text,
  `status_date` datetime DEFAULT NULL,
  `ccm_id` int(11) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `ccm_status_list_id` int(11) DEFAULT NULL,
  `ccm_asset_type_id` int(11) DEFAULT NULL,
  `reason_id` int(11) DEFAULT NULL,
  `utilization_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_status_history_warehouses_fk2` (`warehouse_id`) USING BTREE,
  KEY `ccm_status_history_ccm_status_list_fk3` (`ccm_status_list_id`) USING BTREE,
  KEY `ccm_status_history_ccm_asset_types_fk4` (`ccm_asset_type_id`) USING BTREE,
  KEY `ccm_status_history_ccm_status_list_pk_id_fk5` (`reason_id`) USING BTREE,
  KEY `ccm_status_history_cold_chain_fk1` (`ccm_id`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `utilization_id` (`utilization_id`),
  CONSTRAINT `ccm_status_history_ibfk_1` FOREIGN KEY (`ccm_id`) REFERENCES `cold_chain` (`pk_id`),
  CONSTRAINT `ccm_status_history_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_status_history_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_status_history_ibfk_4` FOREIGN KEY (`ccm_status_list_id`) REFERENCES `ccm_status_list` (`pk_id`),
  CONSTRAINT `ccm_status_history_ibfk_5` FOREIGN KEY (`reason_id`) REFERENCES `ccm_status_list` (`pk_id`),
  CONSTRAINT `ccm_status_history_ibfk_6` FOREIGN KEY (`ccm_asset_type_id`) REFERENCES `ccm_asset_types` (`pk_id`),
  CONSTRAINT `ccm_status_history_ibfk_7` FOREIGN KEY (`utilization_id`) REFERENCES `ccm_status_list` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_status_list
-- ----------------------------
DROP TABLE IF EXISTS `ccm_status_list`;
CREATE TABLE `ccm_status_list` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `ccm_status_list_name` varchar(100) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `reason_type` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '0=inactive, 1=active',
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_status_list_users_fk1` (`created_by`) USING BTREE,
  KEY `ccm_status_list_users_fk2` (`modified_by`) USING BTREE,
  CONSTRAINT `ccm_status_list_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_status_list_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_transfer_history
-- ----------------------------
DROP TABLE IF EXISTS `ccm_transfer_history`;
CREATE TABLE `ccm_transfer_history` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(11) DEFAULT NULL,
  `transfer_date` datetime DEFAULT NULL,
  `ccm_id` int(11) NOT NULL,
  `from_warehouse_id` int(11) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `ccm_status_list_id` int(11) NOT NULL,
  `transfer_by` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_transfer_history_cold_chain_fk1` (`ccm_id`) USING BTREE,
  KEY `ccm_transfer_history_warehouses_fk2` (`from_warehouse_id`) USING BTREE,
  KEY `ccm_transfer_history_warehouses_fk3` (`to_warehouse_id`) USING BTREE,
  KEY `ccm_transfer_history_ccm_status_list_fk4` (`ccm_status_list_id`) USING BTREE,
  KEY `ccm_transfer_history_users_fk5` (`transfer_by`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `ccm_transfer_history_ibfk_1` FOREIGN KEY (`ccm_id`) REFERENCES `cold_chain` (`pk_id`),
  CONSTRAINT `ccm_transfer_history_ibfk_2` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `ccm_transfer_history_ibfk_3` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `ccm_transfer_history_ibfk_4` FOREIGN KEY (`ccm_status_list_id`) REFERENCES `ccm_status_list` (`pk_id`),
  CONSTRAINT `ccm_transfer_history_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_transfer_history_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_vehicles
-- ----------------------------
DROP TABLE IF EXISTS `ccm_vehicles`;
CREATE TABLE `ccm_vehicles` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_no` varchar(100) DEFAULT NULL,
  `used_for_epi` int(11) DEFAULT NULL,
  `comments` text,
  `ccm_id` int(11) DEFAULT NULL,
  `ccm_asset_sub_type_id` int(11) DEFAULT NULL COMMENT 'its list_detail_id',
  `fuel_type_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_vechicles_cold_chain_fk1` (`ccm_id`) USING BTREE,
  KEY `ccm_vechicles_users_fk4` (`created_by`) USING BTREE,
  KEY `ccm_vechicles_users_fk5` (`modified_by`) USING BTREE,
  KEY `ccm_vechicles_fuel_type_id_list_detail_fk3` (`fuel_type_id`) USING BTREE,
  KEY `ccm_vechicles_ccm_asset_types_fk2` (`ccm_asset_sub_type_id`) USING BTREE,
  CONSTRAINT `ccm_vehicles_ibfk_1` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_vehicles_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_vehicles_ibfk_3` FOREIGN KEY (`ccm_asset_sub_type_id`) REFERENCES `ccm_asset_types` (`pk_id`),
  CONSTRAINT `ccm_vehicles_ibfk_4` FOREIGN KEY (`ccm_id`) REFERENCES `cold_chain` (`pk_id`),
  CONSTRAINT `ccm_vehicles_ibfk_5` FOREIGN KEY (`fuel_type_id`) REFERENCES `list_detail` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9468 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_voltage_regulators
-- ----------------------------
DROP TABLE IF EXISTS `ccm_voltage_regulators`;
CREATE TABLE `ccm_voltage_regulators` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `nominal_voltage` int(11) DEFAULT NULL,
  `continous_power` int(11) DEFAULT NULL,
  `frequency` varchar(50) DEFAULT NULL,
  `input_voltage_range` varchar(50) DEFAULT NULL,
  `output_voltage_range` varchar(50) DEFAULT NULL,
  `ccm_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_generator_users_fk3` (`created_by`) USING BTREE,
  KEY `ccm_generator_users_fk4` (`modified_by`) USING BTREE,
  KEY `ccm_generator_cold_chain_fk2` (`ccm_id`) USING BTREE,
  CONSTRAINT `ccm_voltage_regulators_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_voltage_regulators_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_warehouses
-- ----------------------------
DROP TABLE IF EXISTS `ccm_warehouses`;
CREATE TABLE `ccm_warehouses` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `routine_immunization_icepack_requirments` varchar(100) DEFAULT NULL,
  `campaign_icepack_requirments` varchar(100) DEFAULT NULL,
  `electricity_availability_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `vaccine_supply_mode` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_warehouses_warehouses_fk1` (`warehouse_id`) USING BTREE,
  KEY `ccm_warehouses_list_detail_fk2` (`electricity_availability_id`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `ccm_warehouses_ibfk_1` FOREIGN KEY (`electricity_availability_id`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `ccm_warehouses_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `ccm_warehouses_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_warehouses_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13121 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_warehouses_solar_energy
-- ----------------------------
DROP TABLE IF EXISTS `ccm_warehouses_solar_energy`;
CREATE TABLE `ccm_warehouses_solar_energy` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `ccm_warehouse_id` int(11) DEFAULT NULL,
  `solar_energy_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_warehouses_solar_energy_ccm_warehouses_fk1` (`ccm_warehouse_id`) USING BTREE,
  KEY `ccm_warehouses_solar_energy_list_detail_fk2` (`solar_energy_id`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `ccm_warehouses_solar_energy_ibfk_1` FOREIGN KEY (`ccm_warehouse_id`) REFERENCES `ccm_warehouses` (`pk_id`),
  CONSTRAINT `ccm_warehouses_solar_energy_ibfk_2` FOREIGN KEY (`solar_energy_id`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `ccm_warehouses_solar_energy_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_warehouses_solar_energy_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ccm_warehouses_vaccination_staff
-- ----------------------------
DROP TABLE IF EXISTS `ccm_warehouses_vaccination_staff`;
CREATE TABLE `ccm_warehouses_vaccination_staff` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `ccm_warehouse_id` int(11) DEFAULT NULL,
  `vaccination_staff_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `ccm_warehouses_vaccination_staff_ccm_warehouses_fk1` (`ccm_warehouse_id`) USING BTREE,
  KEY `ccm_warehouses_vaccination_staff_list_detail_fk2` (`vaccination_staff_id`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `ccm_warehouses_vaccination_staff_ibfk_1` FOREIGN KEY (`ccm_warehouse_id`) REFERENCES `ccm_warehouses` (`pk_id`),
  CONSTRAINT `ccm_warehouses_vaccination_staff_ibfk_2` FOREIGN KEY (`vaccination_staff_id`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `ccm_warehouses_vaccination_staff_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `ccm_warehouses_vaccination_staff_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for cold_chain
-- ----------------------------
DROP TABLE IF EXISTS `cold_chain`;
CREATE TABLE `cold_chain` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` varchar(50) DEFAULT NULL,
  `auto_asset_id` int(11) DEFAULT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `estimate_life` smallint(6) DEFAULT NULL,
  `working_since` datetime DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `manufacture_year` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '0=inactive, 1=active',
  `ccm_status_history_id` int(11) DEFAULT NULL,
  `ccm_asset_type_id` int(11) DEFAULT NULL,
  `ccm_model_id` int(11) DEFAULT '0',
  `source_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_on` datetime DEFAULT NULL,
  `has_voltage` tinyint(4) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `temperature_monitor` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `cold_chain_ccm_asset_types_fk2` (`ccm_asset_type_id`) USING BTREE,
  KEY `cold_chain_ccm_models_fk3` (`ccm_model_id`) USING BTREE,
  KEY `cold_chain_warehouses_fk5` (`warehouse_id`) USING BTREE,
  KEY `cold_chain_users_fk6` (`created_by`) USING BTREE,
  KEY `cold_chain_stakeholders_fk4` (`source_id`) USING BTREE,
  KEY `cold_chain_ccm_status_history_fk1` (`ccm_status_history_id`) USING BTREE,
  KEY `modified_by` (`modified_by`),
  KEY `temperature_monitor` (`temperature_monitor`),
  CONSTRAINT `cold_chain_ibfk_1` FOREIGN KEY (`ccm_asset_type_id`) REFERENCES `ccm_asset_types` (`pk_id`),
  CONSTRAINT `cold_chain_ibfk_2` FOREIGN KEY (`ccm_model_id`) REFERENCES `ccm_models` (`pk_id`),
  CONSTRAINT `cold_chain_ibfk_3` FOREIGN KEY (`source_id`) REFERENCES `stakeholders` (`pk_id`),
  CONSTRAINT `cold_chain_ibfk_4` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `cold_chain_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `cold_chain_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `cold_chain_ibfk_7` FOREIGN KEY (`temperature_monitor`) REFERENCES `list_detail` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=93708 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for consumption_comments
-- ----------------------------
DROP TABLE IF EXISTS `consumption_comments`;
CREATE TABLE `consumption_comments` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `reporting_start_date` datetime DEFAULT NULL COMMENT 'application version',
  `warehouse_id` int(11) NOT NULL,
  `comments` text,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `warehouse_id` (`warehouse_id`),
  CONSTRAINT `consumption_comments_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `consumption_comments_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `consumption_comments_ibfk_3` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8 COMMENT='contains system wide global information.';

-- ----------------------------
-- Table structure for consumption_summary
-- ----------------------------
DROP TABLE IF EXISTS `consumption_summary`;
CREATE TABLE `consumption_summary` (
  `pk_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `total_reporting_points` int(11) DEFAULT NULL,
  `in_time_reporting` int(11) DEFAULT NULL,
  `late_reporting` int(11) DEFAULT NULL,
  `no_reporting` int(11) DEFAULT NULL,
  `consumption` decimal(10,0) DEFAULT NULL,
  `amc` decimal(10,2) DEFAULT NULL,
  `mos` decimal(10,2) DEFAULT NULL,
  `soh` decimal(10,0) DEFAULT NULL,
  `open_wastage` decimal(10,0) DEFAULT NULL,
  `close_wastage` decimal(10,0) DEFAULT NULL,
  `reporting_start_date` date DEFAULT NULL,
  `vaccinated_male` int(10) unsigned DEFAULT '0',
  `vacinated_female` int(10) unsigned DEFAULT '0',
  `vacinated_fixed` int(10) unsigned DEFAULT '0',
  `vaccinated_outreach` int(10) unsigned DEFAULT '0',
  `vaccinated_inside` int(10) unsigned DEFAULT '0',
  `vaccinated_outside` int(10) unsigned DEFAULT '0',
  `created_by` int(11) DEFAULT '1',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT '1',
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status_reporting` tinyint(4) NOT NULL DEFAULT '1',
  `status_vaccination` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
/*!50100 PARTITION BY HASH (pk_id)
(PARTITION p0 ENGINE = InnoDB,
 PARTITION p1 ENGINE = InnoDB,
 PARTITION p2 ENGINE = InnoDB,
 PARTITION p3 ENGINE = InnoDB,
 PARTITION p4 ENGINE = InnoDB,
 PARTITION p5 ENGINE = InnoDB) */;

-- ----------------------------
-- Table structure for countries
-- ----------------------------
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `countryName` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM AUTO_INCREMENT=253 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for cron_log
-- ----------------------------
DROP TABLE IF EXISTS `cron_log`;
CREATE TABLE `cron_log` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `cron_id` int(11) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for cron_management
-- ----------------------------
DROP TABLE IF EXISTS `cron_management`;
CREATE TABLE `cron_management` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `cron_title` varchar(255) DEFAULT NULL,
  `cron_url` varchar(255) DEFAULT NULL,
  `status` enum('To do','In Progress') DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for demand_detail
-- ----------------------------
DROP TABLE IF EXISTS `demand_detail`;
CREATE TABLE `demand_detail` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `demand_quantity` bigint(20) NOT NULL,
  `approved_quantity` bigint(20) DEFAULT NULL,
  `demand_master_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `pair_product_id` int(11) DEFAULT NULL,
  `draft` tinyint(1) DEFAULT NULL,
  `comments` text,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`pk_id`),
  KEY `demand_detail_ibfk_1` (`demand_master_id`),
  KEY `demand_detail_ibfk_2` (`product_id`),
  KEY `demand_detail_ibfk_3` (`pair_product_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `demand_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `demand_detail_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `demand_detail_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for demand_files
-- ----------------------------
DROP TABLE IF EXISTS `demand_files`;
CREATE TABLE `demand_files` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `demand_master_id` int(11) NOT NULL,
  `file_name` text CHARACTER SET utf8,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`pk_id`),
  KEY `files_ibfk_1` (`demand_master_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `demand_files_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `demand_files_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for demand_master
-- ----------------------------
DROP TABLE IF EXISTS `demand_master`;
CREATE TABLE `demand_master` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `suggested_date` date DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `requisition_number` varchar(100) DEFAULT NULL,
  `requisition_counter` int(11) DEFAULT NULL,
  `requisition_reference` varchar(255) DEFAULT NULL,
  `transaction_type_id` int(11) DEFAULT NULL,
  `draft` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `comments` text,
  `from_warehouse_id` int(11) DEFAULT NULL,
  `to_warehouse_id` int(11) DEFAULT NULL,
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `stock_master_id` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT '1',
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `demand_master_ibfk_1` (`transaction_type_id`),
  KEY `demand_master_ibfk_2` (`from_warehouse_id`),
  KEY `demand_master_ibfk_3` (`to_warehouse_id`),
  KEY `demand_master_ibfk_4` (`stock_master_id`),
  KEY `demand_master_ibfk_5` (`modified_by`),
  KEY `demand_master_ibfk_6` (`created_by`),
  KEY `stakeholder_activity_id` (`stakeholder_activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for demand_warehouses_logic
-- ----------------------------
DROP TABLE IF EXISTS `demand_warehouses_logic`;
CREATE TABLE `demand_warehouses_logic` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `from_warehouse_id` int(11) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `demand_warehouses_logic_ibfk_1` (`from_warehouse_id`),
  KEY `demand_warehouses_logic_ibfk_2` (`to_warehouse_id`),
  CONSTRAINT `demand_warehouses_logic_ibfk_1` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `demand_warehouses_logic_ibfk_2` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for distribution_plan
-- ----------------------------
DROP TABLE IF EXISTS `distribution_plan`;
CREATE TABLE `distribution_plan` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_warehouse_id` int(11) DEFAULT NULL,
  `receiver_warehouse_id` int(11) DEFAULT NULL,
  `geo_level_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `sender_warehouse_id` (`sender_warehouse_id`),
  KEY `receiver_warehouse_id` (`receiver_warehouse_id`),
  KEY `stakeholder_activity_id` (`stakeholder_activity_id`),
  KEY `level` (`geo_level_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `distribution_plan_ibfk_1` FOREIGN KEY (`sender_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `distribution_plan_ibfk_2` FOREIGN KEY (`receiver_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `distribution_plan_ibfk_3` FOREIGN KEY (`geo_level_id`) REFERENCES `geo_levels` (`pk_id`),
  CONSTRAINT `distribution_plan_ibfk_4` FOREIGN KEY (`stakeholder_activity_id`) REFERENCES `stakeholder_activities` (`pk_id`),
  CONSTRAINT `distribution_plan_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `distribution_plan_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9006 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for document_categories
-- ----------------------------
DROP TABLE IF EXISTS `document_categories`;
CREATE TABLE `document_categories` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `category_title` varchar(100) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `document_categories_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `document_categories_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for documents
-- ----------------------------
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_title` varchar(200) DEFAULT NULL,
  `doc_path` varchar(1000) DEFAULT NULL,
  `doc_category_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rank` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `doc_category_id` (`doc_category_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`doc_category_id`) REFERENCES `document_categories` (`pk_id`),
  CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `documents_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for epi_amc
-- ----------------------------
DROP TABLE IF EXISTS `epi_amc`;
CREATE TABLE `epi_amc` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `amc` decimal(10,0) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `amc_year` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `item_id` (`item_id`),
  KEY `warehouse_id` (`warehouse_id`),
  CONSTRAINT `epi_amc_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `epi_amc_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for evac
-- ----------------------------
DROP TABLE IF EXISTS `evac`;
CREATE TABLE `evac` (
  `id` varchar(255) DEFAULT NULL,
  `imei_no_1` varchar(255) DEFAULT NULL,
  `vaccinator_name` varchar(255) DEFAULT NULL,
  `vaccinator_father_name` varchar(255) DEFAULT NULL,
  `lmis_dist_id` int(11) DEFAULT NULL,
  `district_name` varchar(255) DEFAULT NULL,
  `district_idFk` varchar(255) DEFAULT NULL,
  `lmis_tehsil_id` int(11) DEFAULT NULL,
  `tehsil_town_name` varchar(255) DEFAULT NULL,
  `tehsil_idFk` varchar(255) DEFAULT NULL,
  `uc_idFk` varchar(255) DEFAULT NULL,
  `uc_no` varchar(255) DEFAULT NULL,
  `uc_name` varchar(255) DEFAULT NULL,
  `uc_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for evac_app
-- ----------------------------
DROP TABLE IF EXISTS `evac_app`;
CREATE TABLE `evac_app` (
  `id` varchar(255) DEFAULT NULL,
  `imei_no_1` varchar(255) DEFAULT NULL,
  `is_har_zindagi` varchar(255) DEFAULT NULL,
  `old_imei_no` varchar(255) DEFAULT NULL,
  `imei_no_2` varchar(255) DEFAULT NULL,
  `official_sim_no` varchar(255) DEFAULT NULL,
  `user_no` varchar(255) DEFAULT NULL,
  `vaccinator_name` varchar(255) DEFAULT NULL,
  `vaccinator_father_name` varchar(255) DEFAULT NULL,
  `cnic_no` varchar(255) DEFAULT NULL,
  `registration_effective_date` varchar(255) DEFAULT NULL,
  `district_name` varchar(255) DEFAULT NULL,
  `district_idFk` varchar(255) DEFAULT NULL,
  `tehsil_town_name` varchar(255) DEFAULT NULL,
  `tehsil_idFk` varchar(255) DEFAULT NULL,
  `uc_idFk` varchar(255) DEFAULT NULL,
  `uc_no` varchar(255) DEFAULT NULL,
  `uc_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT NULL,
  `is_manual_attendance` varchar(255) DEFAULT NULL,
  `created_datetime` varchar(255) DEFAULT NULL,
  `updated_datetime` varchar(255) DEFAULT NULL,
  `created_by_idFk` varchar(255) DEFAULT NULL,
  `updated_by_idFk` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for evac_consumption
-- ----------------------------
DROP TABLE IF EXISTS `evac_consumption`;
CREATE TABLE `evac_consumption` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `uc_code` int(11) DEFAULT NULL,
  `reporting_month_date` date DEFAULT NULL,
  `transaction_id` bigint(20) DEFAULT NULL,
  `transaction_date_time` datetime DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_qty` bigint(20) DEFAULT NULL,
  `created_by` int(11) DEFAULT '1',
  `created_date` datetime NOT NULL,
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for fata_consumption
-- ----------------------------
DROP TABLE IF EXISTS `fata_consumption`;
CREATE TABLE `fata_consumption` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `dose_no` int(11) DEFAULT NULL,
  `issuance` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `reporting_date` date DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for fav_graph_settings
-- ----------------------------
DROP TABLE IF EXISTS `fav_graph_settings`;
CREATE TABLE `fav_graph_settings` (
  `user` varchar(100) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sel_user` varchar(100) DEFAULT NULL,
  `period` varchar(25) DEFAULT NULL,
  `sel_stakeholder` varchar(100) DEFAULT NULL,
  `year` varchar(100) DEFAULT NULL,
  `arrproducts` text,
  `compare_opt` varchar(50) DEFAULT NULL,
  `optvals` varchar(50) DEFAULT NULL,
  `arryearcomp` text,
  `arrstakecomp` text,
  `titles` text,
  `allfiles` text,
  `col` varchar(200) DEFAULT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `xaxis` varchar(100) DEFAULT NULL,
  `ctype` varchar(100) DEFAULT NULL,
  `rep_title1` varchar(100) DEFAULT NULL,
  `rep_title2` varchar(100) DEFAULT NULL,
  `rep_title3` varchar(100) DEFAULT NULL,
  `rep_logo` varchar(100) DEFAULT NULL,
  `period_lable` varchar(100) DEFAULT NULL,
  `comparison_title` varchar(100) DEFAULT NULL,
  `arrgroupcomp` text,
  `count1` int(5) DEFAULT NULL,
  `log_type` enum('Graph','Report') DEFAULT NULL,
  `arrparam` text,
  `arrleftcol` text,
  `leftcol` varchar(5) DEFAULT NULL,
  `arrprovinces` text,
  `arrdistricts` text,
  `provinces` text,
  `districts` text,
  `sel_prov` int(2) DEFAULT NULL,
  `rep_desc` text,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `fav_graph_settings_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `fav_graph_settings_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for future_arrivals
-- ----------------------------
DROP TABLE IF EXISTS `future_arrivals`;
CREATE TABLE `future_arrivals` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_number` varchar(50) DEFAULT NULL,
  `transaction_counter` int(11) DEFAULT NULL,
  `expected_arrival_date` datetime DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `description` text,
  `item_pack_size_id` int(11) DEFAULT NULL COMMENT 'manufacture_item_id for history',
  `batch_number` varchar(255) DEFAULT NULL,
  `production_date` datetime DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `vvm_type_id` int(11) DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `received_quantity` int(11) DEFAULT '0',
  `from_warehouse_id` int(11) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_master_transaction_types_fk1` (`unit_price`),
  KEY `stock_master_warehouses_fk2` (`quantity`),
  KEY `stock_master_warehouses_fk3` (`received_quantity`),
  KEY `stock_master_users_fk4` (`created_by`),
  KEY `stock_master_ibfk_1` (`stakeholder_activity_id`),
  KEY `item_pack_size_id` (`item_pack_size_id`),
  KEY `manufacturer_id` (`manufacturer_id`),
  KEY `vvm_type_id` (`vvm_type_id`),
  KEY `from_warehouse_id` (`from_warehouse_id`),
  KEY `to_warehouse_id` (`to_warehouse_id`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `future_arrivals_ibfk_1` FOREIGN KEY (`stakeholder_activity_id`) REFERENCES `stakeholder_activities` (`pk_id`),
  CONSTRAINT `future_arrivals_ibfk_2` FOREIGN KEY (`item_pack_size_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `future_arrivals_ibfk_3` FOREIGN KEY (`manufacturer_id`) REFERENCES `stakeholders` (`pk_id`),
  CONSTRAINT `future_arrivals_ibfk_4` FOREIGN KEY (`vvm_type_id`) REFERENCES `vvm_types` (`pk_id`),
  CONSTRAINT `future_arrivals_ibfk_5` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `future_arrivals_ibfk_6` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `future_arrivals_ibfk_7` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `future_arrivals_ibfk_8` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for future_arrivals_draft
-- ----------------------------
DROP TABLE IF EXISTS `future_arrivals_draft`;
CREATE TABLE `future_arrivals_draft` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `expected_arrival_date` datetime DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `description` text,
  `item_pack_size_id` int(11) DEFAULT NULL COMMENT 'manufacture_item_id for history',
  `batch_number` varchar(255) DEFAULT NULL,
  `production_date` datetime DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `vvm_type_id` int(11) DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `from_warehouse_id` int(11) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_master_transaction_types_fk1` (`unit_price`),
  KEY `stock_master_warehouses_fk2` (`quantity`),
  KEY `stock_master_users_fk4` (`created_by`),
  KEY `stock_master_ibfk_1` (`stakeholder_activity_id`),
  KEY `item_pack_size_id` (`item_pack_size_id`),
  KEY `manufacturer_id` (`manufacturer_id`),
  KEY `vvm_type_id` (`vvm_type_id`),
  KEY `from_warehouse_id` (`from_warehouse_id`),
  KEY `to_warehouse_id` (`to_warehouse_id`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `future_arrivals_draft_ibfk_1` FOREIGN KEY (`stakeholder_activity_id`) REFERENCES `stakeholder_activities` (`pk_id`),
  CONSTRAINT `future_arrivals_draft_ibfk_2` FOREIGN KEY (`item_pack_size_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `future_arrivals_draft_ibfk_3` FOREIGN KEY (`manufacturer_id`) REFERENCES `stakeholders` (`pk_id`),
  CONSTRAINT `future_arrivals_draft_ibfk_4` FOREIGN KEY (`vvm_type_id`) REFERENCES `vvm_types` (`pk_id`),
  CONSTRAINT `future_arrivals_draft_ibfk_5` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `future_arrivals_draft_ibfk_6` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `future_arrivals_draft_ibfk_7` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `future_arrivals_draft_ibfk_8` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for gatepass_detail
-- ----------------------------
DROP TABLE IF EXISTS `gatepass_detail`;
CREATE TABLE `gatepass_detail` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` varchar(255) DEFAULT NULL,
  `stock_detail_id` int(11) DEFAULT NULL,
  `gatepass_master_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `gatepass_detail_ibfk_2` (`gatepass_master_id`) USING BTREE,
  KEY `gatepass_detail_ibfk_1` (`stock_detail_id`) USING BTREE,
  KEY `gatepass_detail_ibfk_3` (`created_by`) USING BTREE,
  KEY `gatepass_detail_ibfk_4` (`modified_by`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for gatepass_master
-- ----------------------------
DROP TABLE IF EXISTS `gatepass_master`;
CREATE TABLE `gatepass_master` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(100) DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `gatepass_vehicle_id` int(11) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comments` text CHARACTER SET utf8,
  PRIMARY KEY (`pk_id`),
  KEY `gatepass_master_ibfk_1` (`gatepass_vehicle_id`) USING BTREE,
  KEY `gatepass_master_ibfk_2` (`warehouse_id`) USING BTREE,
  KEY `gatepass_master_ibfk_3` (`created_by`) USING BTREE,
  KEY `gatepass_master_ibfk_4` (`modified_by`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for gatepass_vehicles
-- ----------------------------
DROP TABLE IF EXISTS `gatepass_vehicles`;
CREATE TABLE `gatepass_vehicles` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(255) DEFAULT NULL,
  `vehicle_type_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `gatepass_vehicles_ibfk_1` (`vehicle_type_id`) USING BTREE,
  KEY `gatepass_vehicles_ibfk_2` (`created_by`) USING BTREE,
  KEY `gatepass_vehicles_ibfk_3` (`modified_by`) USING BTREE,
  CONSTRAINT `gatepass_vehicles_ibfk_1` FOREIGN KEY (`vehicle_type_id`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `gatepass_vehicles_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `gatepass_vehicles_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for geo_color
-- ----------------------------
DROP TABLE IF EXISTS `geo_color`;
CREATE TABLE `geo_color` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `color_code` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `geo_color_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `geo_color_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for geo_indicator_values
-- ----------------------------
DROP TABLE IF EXISTS `geo_indicator_values`;
CREATE TABLE `geo_indicator_values` (
  `id` int(11) NOT NULL,
  `geo_indicator_id` int(11) DEFAULT NULL,
  `start_value` varchar(255) DEFAULT NULL,
  `end_value` varchar(255) DEFAULT NULL,
  `interval` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `geo_color_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `geo_color_id` (`geo_color_id`),
  KEY `geo_indicator_id` (`geo_indicator_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `geo_indicator_values_ibfk_1` FOREIGN KEY (`geo_indicator_id`) REFERENCES `geo_indicators` (`pk_id`),
  CONSTRAINT `geo_indicator_values_ibfk_2` FOREIGN KEY (`geo_color_id`) REFERENCES `geo_color` (`pk_id`),
  CONSTRAINT `geo_indicator_values_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `geo_indicator_values_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for geo_indicators
-- ----------------------------
DROP TABLE IF EXISTS `geo_indicators`;
CREATE TABLE `geo_indicators` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `geo_indicator_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `geo_indicators_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `geo_indicators_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for geo_levels
-- ----------------------------
DROP TABLE IF EXISTS `geo_levels`;
CREATE TABLE `geo_levels` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'distribution level id',
  `geo_level_name` varchar(100) DEFAULT NULL,
  `description` text,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `geo_levels_users_fk1` (`created_by`),
  KEY `geo_levels_users_fk2` (`modified_by`),
  CONSTRAINT `geo_levels_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `geo_levels_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='distribution level like district, province, national';

-- ----------------------------
-- Table structure for geo_locations
-- ----------------------------
DROP TABLE IF EXISTS `geo_locations`;
CREATE TABLE `geo_locations` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `gis_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `geo_map_id` int(11) DEFAULT NULL,
  `geo_level_id` int(11) DEFAULT NULL,
  `stakeholder_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `geo_locations_locations_fk1` (`location_id`) USING BTREE,
  KEY `geo_locations_geo_levels_fk3` (`geo_level_id`) USING BTREE,
  KEY `geo_locations_geo_maps_fk2` (`geo_map_id`) USING BTREE,
  KEY `geo_locations_stakeholders_fk4` (`stakeholder_id`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `geo_locations_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `geo_locations_ibfk_2` FOREIGN KEY (`geo_map_id`) REFERENCES `geo_maps` (`pk_id`),
  CONSTRAINT `geo_locations_ibfk_3` FOREIGN KEY (`geo_level_id`) REFERENCES `geo_levels` (`pk_id`),
  CONSTRAINT `geo_locations_ibfk_4` FOREIGN KEY (`stakeholder_id`) REFERENCES `stakeholders` (`pk_id`),
  CONSTRAINT `geo_locations_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `geo_locations_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for geo_maps
-- ----------------------------
DROP TABLE IF EXISTS `geo_maps`;
CREATE TABLE `geo_maps` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `classification` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `no_of_classes` int(11) DEFAULT NULL,
  `resource_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `geo_maps_resources_fk1` (`resource_id`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `geo_maps_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`pk_id`),
  CONSTRAINT `geo_maps_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `geo_maps_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for help_messages
-- ----------------------------
DROP TABLE IF EXISTS `help_messages`;
CREATE TABLE `help_messages` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text,
  `resource_id` int(11) DEFAULT NULL,
  `status` int(2) DEFAULT NULL COMMENT '0=deactive, 1=active, 2=deleted',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `resource_id` (`resource_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `help_messages_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`pk_id`),
  CONSTRAINT `help_messages_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `help_messages_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for hf_data_detail
-- ----------------------------
DROP TABLE IF EXISTS `hf_data_detail`;
CREATE TABLE `hf_data_detail` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `fixed_inside_uc_male` decimal(10,0) DEFAULT NULL,
  `fixed_inside_uc_female` decimal(10,0) DEFAULT NULL,
  `fixed_outside_uc_male` decimal(10,0) DEFAULT NULL,
  `fixed_outside_uc_female` decimal(10,0) DEFAULT NULL,
  `referal_male` decimal(10,0) DEFAULT NULL,
  `referal_female` decimal(10,0) DEFAULT NULL,
  `outreach_male` decimal(10,0) DEFAULT NULL,
  `outreach_female` decimal(10,0) DEFAULT NULL,
  `pregnant_women` decimal(10,0) DEFAULT NULL,
  `non_pregnant_women` decimal(10,0) DEFAULT NULL,
  `age_group_id` int(11) DEFAULT NULL,
  `vaccine_schedule_id` int(11) DEFAULT NULL,
  `hf_data_master_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `outreach_outside_male` decimal(10,0) DEFAULT NULL,
  `outreach_outside_female` decimal(10,0) DEFAULT NULL,
  `outside_pregnant_women` decimal(10,0) DEFAULT NULL,
  `outside_non_pregnant_women` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `hf_data_detail_ibfk_1` (`age_group_id`) USING BTREE,
  KEY `hf_data_detail_ibfk_2` (`hf_data_master_id`) USING BTREE,
  KEY `hf_data_detail_ibfk_3` (`created_by`) USING BTREE,
  KEY `hf_data_detail_ibfk_4` (`modified_by`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for hf_data_detail_draft
-- ----------------------------
DROP TABLE IF EXISTS `hf_data_detail_draft`;
CREATE TABLE `hf_data_detail_draft` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `fixed_inside_uc_male` decimal(10,0) DEFAULT NULL,
  `fixed_inside_uc_female` decimal(10,0) DEFAULT NULL,
  `fixed_outside_uc_male` decimal(10,0) DEFAULT NULL,
  `fixed_outside_uc_female` decimal(10,0) DEFAULT NULL,
  `referal_male` decimal(10,0) DEFAULT NULL,
  `referal_female` decimal(10,0) DEFAULT NULL,
  `outreach_male` decimal(10,0) DEFAULT NULL,
  `outreach_female` decimal(10,0) DEFAULT NULL,
  `pregnant_women` decimal(10,0) DEFAULT NULL,
  `non_pregnant_women` decimal(10,0) DEFAULT NULL,
  `age_group_id` int(11) DEFAULT NULL,
  `vaccine_schedule_id` int(11) DEFAULT NULL,
  `hf_data_master_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `outreach_outside_male` decimal(10,0) DEFAULT NULL,
  `outreach_outside_female` decimal(10,0) DEFAULT NULL,
  `outside_pregnant_women` decimal(10,0) DEFAULT NULL,
  `outside_non_pregnant_women` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `age_group_id` (`age_group_id`),
  KEY `vaccine_schedule_id` (`vaccine_schedule_id`),
  KEY `hf_data_master_id` (`hf_data_master_id`),
  KEY `modified_by` (`modified_by`),
  KEY `hf_data_detail_draft_ibfk_2` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for hf_data_master
-- ----------------------------
DROP TABLE IF EXISTS `hf_data_master`;
CREATE TABLE `hf_data_master` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `opening_balance` int(11) DEFAULT NULL,
  `received_balance` int(11) DEFAULT NULL,
  `issue_balance` int(11) DEFAULT NULL,
  `closing_balance` int(11) DEFAULT NULL,
  `wastages` int(11) DEFAULT NULL,
  `vials_used` int(11) DEFAULT NULL,
  `adjustments` int(11) DEFAULT NULL,
  `reporting_start_date` datetime DEFAULT NULL,
  `nearest_expiry` datetime DEFAULT NULL,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `item_amc` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pk_id`),
  KEY `hf_data_master_ibfk_2` (`warehouse_id`) USING BTREE,
  KEY `hf_data_master_ibfk_1` (`item_pack_size_id`) USING BTREE,
  KEY `hf_data_master_ibfk_3` (`created_by`) USING BTREE,
  KEY `hf_data_master_ibfk_4` (`modified_by`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hf_data_master_draft
-- ----------------------------
DROP TABLE IF EXISTS `hf_data_master_draft`;
CREATE TABLE `hf_data_master_draft` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `opening_balance` int(11) DEFAULT NULL,
  `received_balance` int(11) DEFAULT NULL,
  `issue_balance` int(11) DEFAULT NULL,
  `closing_balance` int(11) DEFAULT NULL,
  `wastages` int(11) DEFAULT NULL,
  `vials_used` int(11) DEFAULT NULL,
  `adjustments` int(11) DEFAULT NULL,
  `reporting_start_date` datetime DEFAULT NULL,
  `nearest_expiry` datetime DEFAULT NULL,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `item_amc` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pk_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `warehouses_data_ibfk_1` (`item_pack_size_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hf_sessions
-- ----------------------------
DROP TABLE IF EXISTS `hf_sessions`;
CREATE TABLE `hf_sessions` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) NOT NULL,
  `warehouse_status` int(11) DEFAULT NULL,
  `fixed_planned_sessions` int(11) DEFAULT NULL,
  `fixed_actually_held_sessions` int(11) DEFAULT NULL,
  `outreach_planned_sessions` int(11) DEFAULT NULL,
  `outreach_actually_held_sessions` int(11) DEFAULT NULL,
  `reporting_start_date` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pk_id`),
  KEY `hf_sessions_ibfk_1` (`warehouse_id`) USING BTREE,
  KEY `hf_sessions_ibfk_2` (`warehouse_status`) USING BTREE,
  KEY `hf_sessions_ibfk_3` (`modified_by`) USING BTREE,
  KEY `hf_sessions_ibfk_4` (`created_by`) USING BTREE,
  CONSTRAINT `hf_sessions_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `hf_sessions_ibfk_2` FOREIGN KEY (`warehouse_status`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `hf_sessions_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `hf_sessions_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for import_ccem
-- ----------------------------
DROP TABLE IF EXISTS `import_ccem`;
CREATE TABLE `import_ccem` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_date` varchar(255) DEFAULT NULL,
  `equipment_ccem_code` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `primary` varchar(255) DEFAULT NULL,
  `subnational_1` varchar(255) DEFAULT NULL,
  `subnational_2` varchar(255) DEFAULT NULL,
  `lowest_distribution` varchar(255) DEFAULT NULL,
  `hf_id` varchar(255) DEFAULT NULL,
  `service_point` varchar(255) DEFAULT NULL,
  `health_structure_name` varchar(255) DEFAULT NULL,
  `pqs_code` varchar(255) DEFAULT NULL,
  `equipment_type` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `net_positive_storage_volume` varchar(255) DEFAULT NULL,
  `net_negative_storage_volume1` varchar(255) DEFAULT NULL,
  `waterpack_freezing_capacity_24` varchar(255) DEFAULT NULL,
  `waterpack_storage_capacity` varchar(255) DEFAULT NULL,
  `serial_bar_code_number` varchar(255) DEFAULT NULL,
  `energy_source_used` varchar(255) DEFAULT NULL,
  `installation_year` varchar(255) DEFAULT NULL,
  `working_status` varchar(255) DEFAULT NULL,
  `waiting_repair` varchar(255) DEFAULT NULL,
  `waiting_parts` varchar(255) DEFAULT NULL,
  `electricity_availability` varchar(255) DEFAULT NULL,
  `generator_status` varchar(255) DEFAULT NULL,
  `yes` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `remove_from_the_inventory` varchar(255) DEFAULT NULL,
  `replacement_year` varchar(255) DEFAULT '',
  `inventory_by` varchar(255) DEFAULT NULL,
  `at_5` varchar(255) DEFAULT NULL,
  `at_20` varchar(255) DEFAULT NULL,
  `waterpack_freezing_capacity` varchar(255) DEFAULT NULL,
  `waterpack_storage_capacity1` varchar(255) DEFAULT NULL,
  `2018` varchar(255) DEFAULT NULL,
  `2019` varchar(255) DEFAULT NULL,
  `2020` varchar(255) DEFAULT NULL,
  `2021` varchar(255) DEFAULT NULL,
  `2022` varchar(255) DEFAULT NULL,
  `Total` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for import_data
-- ----------------------------
DROP TABLE IF EXISTS `import_data`;
CREATE TABLE `import_data` (
  `province_id` int(11) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `tehsil_id` int(11) DEFAULT NULL,
  `tehsil` varchar(255) DEFAULT NULL,
  `uc_id` int(11) DEFAULT NULL,
  `uc` varchar(255) DEFAULT NULL,
  `hf` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for item_activities
-- ----------------------------
DROP TABLE IF EXISTS `item_activities`;
CREATE TABLE `item_activities` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `item_activities_ibfk_2` (`created_by`) USING BTREE,
  KEY `item_activities_ibfk_3` (`modified_by`) USING BTREE,
  KEY `item_activities_ibfk_4` (`item_pack_size_id`) USING BTREE,
  KEY `item_activities_ibfk_1` (`stakeholder_activity_id`) USING BTREE,
  CONSTRAINT `item_activities_ibfk_1` FOREIGN KEY (`stakeholder_activity_id`) REFERENCES `stakeholder_activities` (`pk_id`),
  CONSTRAINT `item_activities_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `item_activities_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `item_activities_ibfk_4` FOREIGN KEY (`item_pack_size_id`) REFERENCES `item_pack_sizes` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for item_categories
-- ----------------------------
DROP TABLE IF EXISTS `item_categories`;
CREATE TABLE `item_categories` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_category_name` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `item_categories_users_fk1` (`created_by`) USING BTREE,
  KEY `item_categories_users_fk2` (`modified_by`) USING BTREE,
  CONSTRAINT `item_categories_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `item_categories_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for item_doses
-- ----------------------------
DROP TABLE IF EXISTS `item_doses`;
CREATE TABLE `item_doses` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `dose_id` int(11) DEFAULT NULL,
  `evacc_item_id` int(11) DEFAULT NULL,
  `evacc_item_name` varchar(255) DEFAULT NULL,
  `evacc_item_description` varchar(255) DEFAULT NULL,
  `evacc_item_status` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

-- ----------------------------
-- Table structure for item_mapping
-- ----------------------------
DROP TABLE IF EXISTS `item_mapping`;
CREATE TABLE `item_mapping` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `epi_mis_item_id` int(11) DEFAULT NULL,
  `epi_mis_item_name` varchar(255) DEFAULT NULL,
  `epi_mis_batch_item_name` varchar(255) DEFAULT NULL,
  `epi_mis_item_description` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

-- ----------------------------
-- Table structure for item_pack_sizes
-- ----------------------------
DROP TABLE IF EXISTS `item_pack_sizes`;
CREATE TABLE `item_pack_sizes` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'liters',
  `item_name` varchar(100) DEFAULT NULL,
  `description` text,
  `number_of_doses` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `list_rank` int(11) DEFAULT NULL COMMENT 'not implemented in v1',
  `multiplier` int(11) DEFAULT NULL COMMENT 'Person population covered by the product(Vaccine)',
  `wastage_rate_allowed` float DEFAULT NULL,
  `item_category_id` int(11) DEFAULT NULL,
  `item_unit_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `vvm_group_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `item_pack_sizes_item_categories_fk1` (`item_category_id`) USING BTREE,
  KEY `item_pack_sizes_item_units_fk2` (`item_unit_id`) USING BTREE,
  KEY `item_pack_sizes_items_fk3` (`item_id`) USING BTREE,
  KEY `vvm_group_id` (`vvm_group_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `item_pack_sizes_ibfk_1` FOREIGN KEY (`item_category_id`) REFERENCES `item_categories` (`pk_id`),
  CONSTRAINT `item_pack_sizes_ibfk_2` FOREIGN KEY (`item_unit_id`) REFERENCES `item_units` (`pk_id`),
  CONSTRAINT `item_pack_sizes_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `items` (`pk_id`),
  CONSTRAINT `item_pack_sizes_ibfk_4` FOREIGN KEY (`vvm_group_id`) REFERENCES `vvm_groups` (`pk_id`),
  CONSTRAINT `item_pack_sizes_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `item_pack_sizes_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='contain information about product attributes';

-- ----------------------------
-- Table structure for item_pairs_logic
-- ----------------------------
DROP TABLE IF EXISTS `item_pairs_logic`;
CREATE TABLE `item_pairs_logic` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'liters',
  `item_id` int(11) DEFAULT NULL,
  `item_pair_id` int(11) DEFAULT NULL,
  `unit_of_quantity` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `item_pairs_logic_ibfk_1` (`unit_of_quantity`),
  KEY `item_pairs_logic_ibfk_2` (`item_id`),
  KEY `item_pairs_logic_ibfk_3` (`item_pair_id`),
  KEY `item_pairs_logic_ibfk_4` (`created_by`),
  KEY `item_pairs_logic_ibfk_5` (`modified_by`),
  CONSTRAINT `item_pairs_logic_ibfk_1` FOREIGN KEY (`unit_of_quantity`) REFERENCES `item_units` (`pk_id`),
  CONSTRAINT `item_pairs_logic_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `item_pairs_logic_ibfk_3` FOREIGN KEY (`item_pair_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `item_pairs_logic_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `item_pairs_logic_ibfk_5` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for item_schedule
-- ----------------------------
DROP TABLE IF EXISTS `item_schedule`;
CREATE TABLE `item_schedule` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `number_of_doses` int(11) DEFAULT NULL,
  `starting_no` int(11) DEFAULT NULL,
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `province_id` int(11) DEFAULT '0',
  PRIMARY KEY (`pk_id`),
  KEY `item_pack_size_id` (`item_pack_size_id`),
  KEY `stakeholder_activity_id` (`stakeholder_activity_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `item_schedule_ibfk_1` FOREIGN KEY (`item_pack_size_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `item_schedule_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `item_schedule_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `item_schedule_ibfk_4` FOREIGN KEY (`stakeholder_activity_id`) REFERENCES `stakeholder_activities` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for item_units
-- ----------------------------
DROP TABLE IF EXISTS `item_units`;
CREATE TABLE `item_units` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_unit_name` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `item_units_users_fk1` (`created_by`) USING BTREE,
  KEY `item_units_users_fk2` (`modified_by`) USING BTREE,
  CONSTRAINT `item_units_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `item_units_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for items
-- ----------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pack_volume` float DEFAULT NULL,
  `doses_per_year` int(11) DEFAULT NULL,
  `pack_diluent_volume` float DEFAULT NULL,
  `target_population_factor` int(11) DEFAULT NULL,
  `item_category_id` int(11) DEFAULT NULL,
  `multiplier` int(11) DEFAULT NULL,
  `wastage_rate_allowed` float DEFAULT NULL,
  `population_percent_increase_per_year` float DEFAULT NULL,
  `child_surviving_percent_per_year` float DEFAULT NULL,
  `child_surviving_percent_till_second_year` float DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `items_users_fk1` (`created_by`),
  KEY `items_users_fk2` (`modified_by`),
  CONSTRAINT `items_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `items_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for kpk_hfs
-- ----------------------------
DROP TABLE IF EXISTS `kpk_hfs`;
CREATE TABLE `kpk_hfs` (
  `Facility_Code` varchar(255) DEFAULT NULL,
  `Facility_Name` varchar(255) DEFAULT NULL,
  `Tehsil_Code` varchar(255) DEFAULT NULL,
  `Tehsil_Name` varchar(255) DEFAULT NULL,
  `Unioncouncil_Code` varchar(255) DEFAULT NULL,
  `Unioncouncil_Name` varchar(255) DEFAULT NULL,
  `District_Code` varchar(255) DEFAULT NULL,
  `District_Name` varchar(255) DEFAULT NULL,
  `Facility_Status` varchar(255) DEFAULT NULL,
  `tehsil_id` int(11) DEFAULT NULL,
  `uc_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for list_detail
-- ----------------------------
DROP TABLE IF EXISTS `list_detail`;
CREATE TABLE `list_detail` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `list_value` varchar(100) DEFAULT NULL,
  `description` text,
  `rank` int(11) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `list_master_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `list_detail_list_master_id_list_master_fk1` (`list_master_id`) USING BTREE,
  KEY `list_detail_created_by_users_fk2` (`created_by`) USING BTREE,
  KEY `list_detail_modified_by_users_fk3` (`modified_by`) USING BTREE,
  CONSTRAINT `list_detail_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `list_detail_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `list_detail_ibfk_3` FOREIGN KEY (`list_master_id`) REFERENCES `list_master` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=299 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for list_master
-- ----------------------------
DROP TABLE IF EXISTS `list_master`;
CREATE TABLE `list_master` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `list_master_name` varchar(100) DEFAULT NULL,
  `description` text,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `category` enum('IM','CC') DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `list_master_created_by_users_fk1` (`created_by`) USING BTREE,
  KEY `list_master_modified_by_users_fk2` (`modified_by`) USING BTREE,
  CONSTRAINT `list_master_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `list_master_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for loc_teh
-- ----------------------------
DROP TABLE IF EXISTS `loc_teh`;
CREATE TABLE `loc_teh` (
  `uc_id` varchar(255) DEFAULT NULL,
  `pop` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for location_mapping
-- ----------------------------
DROP TABLE IF EXISTS `location_mapping`;
CREATE TABLE `location_mapping` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `integration_source_id` int(11) DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `lmis_location_id` int(11) DEFAULT NULL,
  `evac_location_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `functional_date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `level` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`)
) ENGINE=MyISAM AUTO_INCREMENT=809 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for location_populations
-- ----------------------------
DROP TABLE IF EXISTS `location_populations`;
CREATE TABLE `location_populations` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `population` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `estimation_date` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `location_population_location_id_list_detail_pk_id` (`location_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `location_populations_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `location_populations_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `location_populations_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `locations` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28266 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for location_types
-- ----------------------------
DROP TABLE IF EXISTS `location_types`;
CREATE TABLE `location_types` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_type_name` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `geo_level_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `location_types_geo_levels_fk1` (`geo_level_id`),
  KEY `location_types_users_fk2` (`created_by`),
  KEY `location_types_users_fk3` (`modified_by`),
  CONSTRAINT `location_types_ibfk_1` FOREIGN KEY (`geo_level_id`) REFERENCES `geo_levels` (`pk_id`),
  CONSTRAINT `location_types_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `location_types_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for locations
-- ----------------------------
DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(100) DEFAULT NULL,
  `geo_level_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `location_type_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `ccm_location_id` int(11) DEFAULT NULL,
  `sdms_name` varchar(255) DEFAULT NULL,
  `dhis_code` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `geo_level_id` (`geo_level_id`),
  KEY `location_type_id` (`location_type_id`),
  KEY `province_id` (`province_id`),
  KEY `parent_id` (`parent_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `district_id` (`district_id`),
  CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`geo_level_id`) REFERENCES `geo_levels` (`pk_id`),
  CONSTRAINT `locations_ibfk_2` FOREIGN KEY (`location_type_id`) REFERENCES `location_types` (`pk_id`),
  CONSTRAINT `locations_ibfk_3` FOREIGN KEY (`province_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `locations_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `locations_ibfk_5` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `locations_ibfk_6` FOREIGN KEY (`parent_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `locations_ibfk_7` FOREIGN KEY (`district_id`) REFERENCES `locations` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9665 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for log_book
-- ----------------------------
DROP TABLE IF EXISTS `log_book`;
CREATE TABLE `log_book` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `gender` enum('Female','Male') DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `union_council_id` int(11) DEFAULT NULL,
  `vaccination_date` datetime DEFAULT NULL,
  `refer_to_warehouse_id` int(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reporting_start_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pk_id`),
  KEY `district_id` (`district_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `log_book_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `log_book_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `log_book_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `log_book_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for log_book_item_doses
-- ----------------------------
DROP TABLE IF EXISTS `log_book_item_doses`;
CREATE TABLE `log_book_item_doses` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `log_book_id` int(11) DEFAULT NULL,
  `doses` int(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `item_pack_size_id` (`item_pack_size_id`),
  KEY `log_book_id` (`log_book_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for map_district_mapping
-- ----------------------------
DROP TABLE IF EXISTS `map_district_mapping`;
CREATE TABLE `map_district_mapping` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `mapping_id` int(11) DEFAULT NULL,
  `district_name` varchar(255) DEFAULT NULL,
  `province_name` varchar(255) DEFAULT NULL,
  `default_district` int(11) DEFAULT NULL,
  `pilot_district_status` tinyint(1) DEFAULT NULL,
  `district_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `mapping_id` (`mapping_id`),
  KEY `district_id` (`district_id`),
  KEY `province_id` (`province_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `map_district_mapping_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `map_district_mapping_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `map_district_mapping_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `map_district_mapping_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mos_scale
-- ----------------------------
DROP TABLE IF EXISTS `mos_scale`;
CREATE TABLE `mos_scale` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `short_term` varchar(5) DEFAULT NULL,
  `long_term` varchar(30) DEFAULT NULL,
  `scale_start` float DEFAULT '0' COMMENT 'Scale start at',
  `scale_end` float DEFAULT '0' COMMENT 'scale ends at',
  `extra` varchar(10) DEFAULT NULL,
  `color_code` varchar(30) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `stakeholder_id` int(11) NOT NULL COMMENT 'Foreign Key: Stakeholder',
  `geo_level_id` int(11) NOT NULL COMMENT 'Foreign Key: distribution level',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `mos_scale_geo_levels_fk3` (`geo_level_id`),
  KEY `mos_scale_items_fk1` (`item_id`) USING BTREE,
  KEY `mos_scale_stakeholders_fk2` (`stakeholder_id`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `mos_scale_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `mos_scale_ibfk_2` FOREIGN KEY (`stakeholder_id`) REFERENCES `stakeholders` (`pk_id`),
  CONSTRAINT `mos_scale_ibfk_3` FOREIGN KEY (`geo_level_id`) REFERENCES `geo_levels` (`pk_id`),
  CONSTRAINT `mos_scale_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `mos_scale_ibfk_5` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18596 DEFAULT CHARSET=utf8 COMMENT='contain inforamtion about min/max values of product code etc';

-- ----------------------------
-- Table structure for non_ccm_locations
-- ----------------------------
DROP TABLE IF EXISTS `non_ccm_locations`;
CREATE TABLE `non_ccm_locations` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(100) DEFAULT NULL,
  `warehouse_id` int(11) NOT NULL,
  `rack_information_id` int(11) DEFAULT NULL,
  `area` int(11) NOT NULL,
  `row` int(11) NOT NULL,
  `rack` int(11) NOT NULL,
  `pallet` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `non_ccm_locations_ibfk_1` (`warehouse_id`) USING BTREE,
  KEY `non_ccm_locations_ibfk_2` (`rack_information_id`) USING BTREE,
  KEY `non_ccm_locations_ibfk_3` (`area`) USING BTREE,
  KEY `non_ccm_locations_ibfk_4` (`row`) USING BTREE,
  KEY `non_ccm_locations_ibfk_5` (`rack`) USING BTREE,
  KEY `non_ccm_locations_ibfk_6` (`pallet`) USING BTREE,
  KEY `non_ccm_locations_ibfk_7` (`level`) USING BTREE,
  KEY `non_ccm_locations_ibfk_8` (`created_by`) USING BTREE,
  KEY `non_ccm_locations_ibfk_9` (`modified_by`) USING BTREE,
  CONSTRAINT `non_ccm_locations_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `non_ccm_locations_ibfk_2` FOREIGN KEY (`rack_information_id`) REFERENCES `rack_information` (`pk_id`),
  CONSTRAINT `non_ccm_locations_ibfk_3` FOREIGN KEY (`area`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `non_ccm_locations_ibfk_4` FOREIGN KEY (`row`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `non_ccm_locations_ibfk_5` FOREIGN KEY (`rack`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `non_ccm_locations_ibfk_6` FOREIGN KEY (`pallet`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `non_ccm_locations_ibfk_7` FOREIGN KEY (`level`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `non_ccm_locations_ibfk_8` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `non_ccm_locations_ibfk_9` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1647 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for overall_consumption
-- ----------------------------
DROP TABLE IF EXISTS `overall_consumption`;
CREATE TABLE `overall_consumption` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `dose_no` int(11) DEFAULT NULL,
  `issuance` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `reporting_date` date DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for overall_consumption_dec
-- ----------------------------
DROP TABLE IF EXISTS `overall_consumption_dec`;
CREATE TABLE `overall_consumption_dec` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `dose_no` int(11) DEFAULT NULL,
  `issuance` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `reporting_date` date DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4742 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for pack_info
-- ----------------------------
DROP TABLE IF EXISTS `pack_info`;
CREATE TABLE `pack_info` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'stakeholder id (primary key)',
  `stakeholder_item_pack_size_id` int(11) DEFAULT NULL,
  `pack_size_description` text,
  `length` decimal(11,2) DEFAULT NULL,
  `width` decimal(11,2) DEFAULT NULL,
  `height` decimal(11,2) DEFAULT NULL,
  `quantity_per_pack` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `list_rank` int(11) DEFAULT NULL,
  `volum_per_vial` decimal(10,2) DEFAULT NULL,
  `item_gtin` varchar(20) DEFAULT NULL,
  `packaging_level` int(11) DEFAULT '140',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `pack_info_ibfk_1` (`modified_by`) USING BTREE,
  KEY `pack_info_ibfk_2` (`stakeholder_item_pack_size_id`) USING BTREE,
  KEY `pack_info_ibfk_3` (`packaging_level`) USING BTREE,
  KEY `pack_info_ibfk_4` (`created_by`) USING BTREE,
  CONSTRAINT `pack_info_ibfk_1` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `pack_info_ibfk_2` FOREIGN KEY (`stakeholder_item_pack_size_id`) REFERENCES `stakeholder_item_pack_sizes` (`pk_id`),
  CONSTRAINT `pack_info_ibfk_3` FOREIGN KEY (`packaging_level`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `pack_info_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=909 DEFAULT CHARSET=utf8 COMMENT='contains detail information of stakeholder and itm_info_tab';

-- ----------------------------
-- Table structure for period
-- ----------------------------
DROP TABLE IF EXISTS `period`;
CREATE TABLE `period` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `period_name` text,
  `period_code` int(11) NOT NULL COMMENT 'period code',
  `is_month` text,
  `begin_month` int(11) DEFAULT NULL COMMENT 'beginning month of the period',
  `end_month` int(11) DEFAULT NULL COMMENT 'ending month of the period',
  `month_count` int(11) DEFAULT NULL COMMENT 'month count in period',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `period_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `period_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for pilot_districts
-- ----------------------------
DROP TABLE IF EXISTS `pilot_districts`;
CREATE TABLE `pilot_districts` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `district_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `pilot_districts_locations_fk1` (`district_id`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `pilot_districts_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `pilot_districts_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `pilot_districts_ibfk_3` FOREIGN KEY (`district_id`) REFERENCES `locations` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for pipeline_consignments
-- ----------------------------
DROP TABLE IF EXISTS `pipeline_consignments`;
CREATE TABLE `pipeline_consignments` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_number` varchar(50) DEFAULT NULL,
  `transaction_counter` int(11) DEFAULT NULL,
  `expected_arrival_date` datetime DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `description` text,
  `item_pack_size_id` int(11) DEFAULT NULL COMMENT 'manufacture_item_id for history',
  `batch_number` varchar(255) DEFAULT NULL,
  `production_date` datetime DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `vvm_type_id` int(11) DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `received_quantity` int(11) DEFAULT '0',
  `from_warehouse_id` int(11) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `master_id` int(11) DEFAULT '0' COMMENT 'This will be use for original stock master id',
  `status` enum('Received','Receiving','Planned') DEFAULT 'Planned',
  `stock_batch_warehouse_id` int(11) DEFAULT NULL,
  `transaction_type_id` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_master_transaction_types_fk1` (`unit_price`),
  KEY `stock_master_warehouses_fk2` (`quantity`),
  KEY `stock_master_warehouses_fk3` (`received_quantity`),
  KEY `stock_master_users_fk4` (`created_by`),
  KEY `stock_master_ibfk_1` (`stakeholder_activity_id`),
  KEY `item_pack_size_id` (`item_pack_size_id`),
  KEY `vvm_type_id` (`vvm_type_id`),
  KEY `from_warehouse_id` (`from_warehouse_id`),
  KEY `to_warehouse_id` (`to_warehouse_id`),
  KEY `stock_batch_warehouse_id` (`stock_batch_warehouse_id`),
  KEY `transaction_type_id` (`transaction_type_id`),
  KEY `modified_by` (`modified_by`),
  KEY `pipeline_consignments_ibfk_3` (`manufacturer_id`),
  CONSTRAINT `pipeline_consignments_ibfk_1` FOREIGN KEY (`stakeholder_activity_id`) REFERENCES `stakeholder_activities` (`pk_id`),
  CONSTRAINT `pipeline_consignments_ibfk_10` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `pipeline_consignments_ibfk_2` FOREIGN KEY (`item_pack_size_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `pipeline_consignments_ibfk_3` FOREIGN KEY (`manufacturer_id`) REFERENCES `pack_info` (`pk_id`),
  CONSTRAINT `pipeline_consignments_ibfk_4` FOREIGN KEY (`vvm_type_id`) REFERENCES `vvm_types` (`pk_id`),
  CONSTRAINT `pipeline_consignments_ibfk_5` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `pipeline_consignments_ibfk_6` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `pipeline_consignments_ibfk_7` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `pipeline_consignments_ibfk_9` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_types` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pipeline_consignments_draft
-- ----------------------------
DROP TABLE IF EXISTS `pipeline_consignments_draft`;
CREATE TABLE `pipeline_consignments_draft` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `expected_arrival_date` datetime DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `description` text,
  `item_pack_size_id` int(11) DEFAULT NULL COMMENT 'manufacture_item_id for history',
  `batch_number` varchar(255) DEFAULT NULL,
  `production_date` datetime DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `vvm_type_id` int(11) DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `from_warehouse_id` int(11) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `stock_batch_warehouse_id` int(11) DEFAULT NULL,
  `transaction_type_id` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_master_users_fk4` (`created_by`),
  KEY `stock_master_ibfk_1` (`stakeholder_activity_id`),
  KEY `item_pack_size_id` (`item_pack_size_id`),
  KEY `manufacturer_id` (`manufacturer_id`),
  KEY `vvm_type_id` (`vvm_type_id`),
  KEY `from_warehouse_id` (`from_warehouse_id`),
  KEY `to_warehouse_id` (`to_warehouse_id`),
  KEY `created_by` (`created_by`),
  KEY `stock_batch_id` (`stock_batch_warehouse_id`),
  KEY `transaction_type_id` (`transaction_type_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pipeline_consignments_placements
-- ----------------------------
DROP TABLE IF EXISTS `pipeline_consignments_placements`;
CREATE TABLE `pipeline_consignments_placements` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `pipeline_consignment_id` int(11) DEFAULT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `quantity` decimal(10,0) DEFAULT NULL,
  `placement_location_id` int(11) DEFAULT NULL,
  `vvm_stage` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `pipeline_consignment_id` (`pipeline_consignment_id`),
  KEY `placement_location_id` (`placement_location_id`),
  KEY `vvm_stage` (`vvm_stage`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for placement_locations
-- ----------------------------
DROP TABLE IF EXISTS `placement_locations`;
CREATE TABLE `placement_locations` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_type` int(11) DEFAULT NULL,
  `location_barcode` varchar(100) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL COMMENT 'Its rack locations for ccm and non ccm assets.',
  `capacity_percentage` decimal(10,0) DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `placement_locations_ibfk_1` (`location_type`) USING BTREE,
  KEY `placement_locations_ibfk_2` (`created_by`) USING BTREE,
  KEY `placement_locations_ibfk_3` (`modified_by`) USING BTREE,
  CONSTRAINT `placement_locations_ibfk_1` FOREIGN KEY (`location_type`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `placement_locations_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `placement_locations_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for placement_summary
-- ----------------------------
DROP TABLE IF EXISTS `placement_summary`;
CREATE TABLE `placement_summary` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) DEFAULT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `stock_batch_warehouse_id` int(11) DEFAULT NULL,
  `placement_location_id` int(11) DEFAULT NULL,
  `vvm_stage` int(11) DEFAULT NULL,
  `quantity` decimal(10,0) DEFAULT NULL,
  `cartons` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_batch_warehouse_id` (`stock_batch_warehouse_id`),
  KEY `placement_location_id` (`placement_location_id`),
  KEY `vvm_stage` (`vvm_stage`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for placements
-- ----------------------------
DROP TABLE IF EXISTS `placements`;
CREATE TABLE `placements` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(11) DEFAULT '0',
  `vvm_stage` int(11) DEFAULT '0',
  `is_placed` int(2) unsigned DEFAULT '1' COMMENT '1 when placed 0 when picked',
  `placement_location_id` int(11) DEFAULT NULL COMMENT 'it can be cold chain and non cold chain id',
  `stock_batch_warehouse_id` int(11) DEFAULT NULL,
  `stock_detail_id` int(11) DEFAULT NULL,
  `placement_transaction_type_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `placements_ibfk_1` (`stock_batch_warehouse_id`) USING BTREE,
  KEY `placements_ibfk_5` (`placement_location_id`) USING BTREE,
  KEY `placements_ibfk_3` (`created_by`) USING BTREE,
  KEY `placements_ibfk_2` (`placement_transaction_type_id`) USING BTREE,
  KEY `placements_ibfk_4` (`modified_by`) USING BTREE,
  KEY `placements_ibfk_6` (`vvm_stage`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for punjab_cluster
-- ----------------------------
DROP TABLE IF EXISTS `punjab_cluster`;
CREATE TABLE `punjab_cluster` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `cluster` varchar(255) DEFAULT NULL,
  `cluster_name` varchar(255) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `tehsil_name` varchar(255) DEFAULT NULL,
  `tehsil_code` varchar(255) DEFAULT NULL,
  `uc_name` varchar(255) DEFAULT NULL,
  `uc_no` varchar(255) DEFAULT NULL,
  `uc_code` varchar(255) DEFAULT NULL,
  `hf_name` varchar(255) DEFAULT NULL,
  `hf_code` varchar(255) DEFAULT NULL,
  `hf_type` varchar(255) DEFAULT NULL,
  `population` varchar(255) DEFAULT NULL,
  `tehsil_id` int(11) DEFAULT NULL,
  `uc_id` int(11) DEFAULT NULL,
  `hf_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for purpose_transfer_history
-- ----------------------------
DROP TABLE IF EXISTS `purpose_transfer_history`;
CREATE TABLE `purpose_transfer_history` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `from_activity_id` int(11) DEFAULT NULL,
  `to_activity_id` int(11) DEFAULT NULL,
  `from_stock_batch_warehouse_id` int(11) DEFAULT NULL,
  `to_stock_batch_warehouse_id` int(11) DEFAULT NULL,
  `quantity` decimal(10,0) DEFAULT NULL,
  `transaction_type_id` int(11) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `from_activity_id` (`from_activity_id`),
  KEY `to_activity_id` (`to_activity_id`),
  KEY `from_batch_id` (`from_stock_batch_warehouse_id`),
  KEY `to_batch_id` (`to_stock_batch_warehouse_id`),
  KEY `transaction_type_id` (`transaction_type_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for rack_information
-- ----------------------------
DROP TABLE IF EXISTS `rack_information`;
CREATE TABLE `rack_information` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `rack_type` varchar(50) DEFAULT NULL,
  `no_of_bins` smallint(11) DEFAULT NULL,
  `bin_net_capacity` float DEFAULT NULL,
  `gross_capacity` float DEFAULT NULL,
  `capacity_unit` varchar(50) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `rack_information_users_fk1` (`created_by`) USING BTREE,
  KEY `rack_information_users_fk2` (`modified_by`) USING BTREE,
  CONSTRAINT `rack_information_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `rack_information_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for report_options
-- ----------------------------
DROP TABLE IF EXISTS `report_options`;
CREATE TABLE `report_options` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` varchar(100) DEFAULT NULL,
  `report_title_sql` varchar(1000) DEFAULT NULL,
  `report_data_sql` varchar(1000) DEFAULT NULL,
  `report_stakeholder` int(1) NOT NULL DEFAULT '0' COMMENT 'indicates if config is for specific stakeholder of all stakeholders',
  `report_comparision` int(1) DEFAULT NULL COMMENT 'comparision option id as in select box of graph parameter form',
  `report_data_position` int(1) DEFAULT NULL COMMENT 'position of data returned from stored procedure',
  `report_comparision_flag` int(1) DEFAULT NULL COMMENT 'indicates whether grap is simple or comparison type',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `report_options_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `report_options_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for reports
-- ----------------------------
DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
  `report_id` varchar(60) NOT NULL DEFAULT '',
  `report_group` varchar(100) DEFAULT NULL,
  `report_type` decimal(1,0) DEFAULT NULL COMMENT 'graph or report',
  `report_title` varchar(60) DEFAULT NULL,
  `report_xaxis` varchar(60) DEFAULT NULL,
  `report_yaxis` varchar(60) DEFAULT NULL,
  `report_units` varchar(60) DEFAULT NULL,
  `report_factor` decimal(8,0) DEFAULT NULL COMMENT 'factor to scale down y-axix',
  `report_field` varchar(100) DEFAULT NULL,
  `report_description` text,
  `staticpage` varchar(100) DEFAULT NULL,
  `footer_staticpage` varchar(100) DEFAULT NULL,
  `report_order` smallint(6) DEFAULT NULL,
  `report_show_simple` smallint(1) DEFAULT '1',
  `report_show_comp` smallint(1) DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`report_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for resource_types
-- ----------------------------
DROP TABLE IF EXISTS `resource_types`;
CREATE TABLE `resource_types` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_type` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `resource_types_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `resource_types_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for resources
-- ----------------------------
DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_name` varchar(100) DEFAULT NULL,
  `description` text,
  `page_title` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `rank` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `resource_type_id` int(11) NOT NULL,
  `icon_class` varchar(50) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `resource_type_id` (`resource_type_id`),
  KEY `resources_created_by_users_pk` (`created_by`),
  KEY `resources_created_by_users_pk2` (`modified_by`),
  CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`resource_type_id`) REFERENCES `resource_types` (`pk_id`),
  CONSTRAINT `resources_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `resources_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1124 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for role_resources
-- ----------------------------
DROP TABLE IF EXISTS `role_resources`;
CREATE TABLE `role_resources` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` enum('DENY','ALLOW') NOT NULL DEFAULT 'ALLOW',
  `role_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `is_default` int(1) DEFAULT '1',
  PRIMARY KEY (`pk_id`),
  KEY `role_resources_roles_fk1` (`role_id`),
  KEY `role_resources_resources_fk2` (`resource_id`),
  CONSTRAINT `role_resources_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`pk_id`),
  CONSTRAINT `role_resources_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21467 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(100) DEFAULT NULL,
  `description` text,
  `category_id` int(11) NOT NULL COMMENT 'Its role category i-e inventory management, cold chain, campaign etc',
  `role_level_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `roles_users_fk2` (`created_by`) USING BTREE,
  KEY `roles_users_fk3` (`modified_by`) USING BTREE,
  KEY `roles_list_detail_fk1` (`category_id`) USING BTREE,
  CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `list_detail` (`pk_id`),
  CONSTRAINT `roles_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `roles_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COMMENT='contain user type information';

-- ----------------------------
-- Table structure for sentinel_sites
-- ----------------------------
DROP TABLE IF EXISTS `sentinel_sites`;
CREATE TABLE `sentinel_sites` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `sentinel_site_name` varchar(255) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `starting_on` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `location_id` (`location_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2336 DEFAULT CHARSET=utf8 COMMENT='contain information about warehouse';

-- ----------------------------
-- Table structure for sheet1
-- ----------------------------
DROP TABLE IF EXISTS `sheet1`;
CREATE TABLE `sheet1` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `district_id` int(11) DEFAULT NULL,
  `District` varchar(255) DEFAULT NULL,
  `tehsil_id` int(11) DEFAULT NULL,
  `Tehsil` varchar(255) DEFAULT NULL,
  `UC` varchar(255) DEFAULT NULL,
  `warehouse` varchar(255) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `clusterLead` varchar(255) DEFAULT NULL,
  `clusterUC` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for shipment_detail
-- ----------------------------
DROP TABLE IF EXISTS `shipment_detail`;
CREATE TABLE `shipment_detail` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipment_id` int(11) DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `received_quantity` decimal(10,0) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `shipment_id` (`shipment_id`) USING BTREE,
  KEY `warehouse_id` (`warehouse_id`) USING BTREE,
  KEY `created_by` (`created_by`) USING BTREE,
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `shipment_detail_ibfk_1` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`pk_id`),
  CONSTRAINT `shipment_detail_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `shipment_detail_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `shipment_detail_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for shipment_history
-- ----------------------------
DROP TABLE IF EXISTS `shipment_history`;
CREATE TABLE `shipment_history` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipment_id` int(11) DEFAULT NULL,
  `status` enum('Received','International Order Placed (UNICEF)','Local Order Placed') DEFAULT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `shipment_id` (`shipment_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `shipment_history_ibfk_1` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`pk_id`),
  CONSTRAINT `shipment_history_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `shipment_history_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for shipment_product_batch
-- ----------------------------
DROP TABLE IF EXISTS `shipment_product_batch`;
CREATE TABLE `shipment_product_batch` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(100) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `production_date` date DEFAULT NULL,
  `vvm_type_id` int(11) DEFAULT NULL,
  `item_pack_size_id` int(11) DEFAULT NULL COMMENT 'manufacture_item_id for history',
  `shipment_id` int(11) DEFAULT NULL,
  `stakeholder_item_pack_size_id` int(11) DEFAULT NULL,
  `draft` tinyint(4) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `quantity` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `stock_batch_ibfk_2` (`created_by`),
  KEY `stock_batch_ibfk_3` (`modified_by`),
  KEY `stock_batch_ibfk_4` (`item_pack_size_id`) USING BTREE,
  KEY `stock_batch_ibfk_1` (`vvm_type_id`) USING BTREE,
  CONSTRAINT `shipment_product_batch_ibfk_1` FOREIGN KEY (`vvm_type_id`) REFERENCES `vvm_types` (`pk_id`),
  CONSTRAINT `shipment_product_batch_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `shipment_product_batch_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `shipment_product_batch_ibfk_4` FOREIGN KEY (`item_pack_size_id`) REFERENCES `item_pack_sizes` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for shipments
-- ----------------------------
DROP TABLE IF EXISTS `shipments`;
CREATE TABLE `shipments` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_number` varchar(50) DEFAULT NULL,
  `transaction_number` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `transaction_counter` int(11) DEFAULT NULL,
  `eta` date DEFAULT NULL,
  `draft` tinyint(1) DEFAULT NULL,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `shipment_date` date DEFAULT NULL,
  `shipment_quantity` decimal(10,0) DEFAULT NULL,
  `funding_source_id` int(11) DEFAULT NULL,
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `item_pack_size_id` (`item_pack_size_id`),
  KEY `funding_source_id` (`funding_source_id`),
  KEY `stakeholder_activity_id` (`stakeholder_activity_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `shipments_ibfk_1` FOREIGN KEY (`item_pack_size_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `shipments_ibfk_2` FOREIGN KEY (`stakeholder_activity_id`) REFERENCES `stakeholder_activities` (`pk_id`),
  CONSTRAINT `shipments_ibfk_3` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `shipments_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `shipments_ibfk_5` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for shipments_receive
-- ----------------------------
DROP TABLE IF EXISTS `shipments_receive`;
CREATE TABLE `shipments_receive` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipment_product_batch_id` int(11) DEFAULT NULL,
  `quantity` decimal(10,0) DEFAULT NULL,
  `placement_location_id` int(11) DEFAULT NULL,
  `vvm_stage` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `counter` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `pipeline_consignment_id` (`shipment_product_batch_id`),
  KEY `placement_location_id` (`placement_location_id`),
  KEY `vvm_stage` (`vvm_stage`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `shipments_receive_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `shipments_receive_ibfk_5` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sindh_pop
-- ----------------------------
DROP TABLE IF EXISTS `sindh_pop`;
CREATE TABLE `sindh_pop` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `uc_id` int(11) DEFAULT NULL,
  `pop` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1118 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for stakeholder_activities
-- ----------------------------
DROP TABLE IF EXISTS `stakeholder_activities`;
CREATE TABLE `stakeholder_activities` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `activity` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `stakeholder_activities_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stakeholder_activities_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for stakeholder_item_pack_sizes
-- ----------------------------
DROP TABLE IF EXISTS `stakeholder_item_pack_sizes`;
CREATE TABLE `stakeholder_item_pack_sizes` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'stakeholder id (primary key)',
  `stakeholder_id` int(11) DEFAULT NULL,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stakeholder_item_pack_sizes_ibfk_2` (`stakeholder_id`) USING BTREE,
  KEY `stakeholder_item_pack_sizes_ibfk_1` (`item_pack_size_id`) USING BTREE,
  KEY `stakeholder_item_pack_sizes_ibfk_3` (`created_by`) USING BTREE,
  KEY `stakeholder_item_pack_sizes_ibfk_4` (`modified_by`) USING BTREE,
  CONSTRAINT `stakeholder_item_pack_sizes_ibfk_1` FOREIGN KEY (`item_pack_size_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `stakeholder_item_pack_sizes_ibfk_2` FOREIGN KEY (`stakeholder_id`) REFERENCES `stakeholders` (`pk_id`),
  CONSTRAINT `stakeholder_item_pack_sizes_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stakeholder_item_pack_sizes_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=907 DEFAULT CHARSET=utf8 COMMENT='contains detail information of stakeholder and itm_info_tab';

-- ----------------------------
-- Table structure for stakeholder_sectors
-- ----------------------------
DROP TABLE IF EXISTS `stakeholder_sectors`;
CREATE TABLE `stakeholder_sectors` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `stakeholder_sector_name` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stakeholder_sectors_users_fk1` (`created_by`),
  KEY `stakeholder_sectors_users_fk2` (`modified_by`),
  CONSTRAINT `stakeholder_sectors_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stakeholder_sectors_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for stakeholder_types
-- ----------------------------
DROP TABLE IF EXISTS `stakeholder_types`;
CREATE TABLE `stakeholder_types` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `stakeholder_type_name` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stakeholder_types_users_fk1` (`created_by`),
  KEY `stakeholder_types_users_fk2` (`modified_by`),
  CONSTRAINT `stakeholder_types_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stakeholder_types_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for stakeholders
-- ----------------------------
DROP TABLE IF EXISTS `stakeholders`;
CREATE TABLE `stakeholders` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'stakeholder id',
  `stakeholder_name` varchar(100) DEFAULT NULL,
  `list_rank` int(11) DEFAULT NULL COMMENT 'the order in which stakeholder will appear in report or data entry form',
  `parent_id` int(11) NOT NULL,
  `stakeholder_type_id` int(11) NOT NULL DEFAULT '0',
  `stakeholder_sector_id` int(11) NOT NULL,
  `geo_level_id` int(11) NOT NULL,
  `main_stakeholder` int(11) DEFAULT NULL COMMENT 'parent of parent',
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stakeholders_ibfk_7` (`parent_id`) USING BTREE,
  KEY `stakeholders_ibfk_1` (`stakeholder_type_id`) USING BTREE,
  KEY `stakeholders_ibfk_2` (`stakeholder_sector_id`) USING BTREE,
  KEY `stakeholders_ibfk_3` (`geo_level_id`) USING BTREE,
  KEY `stakeholders_ibfk_8` (`main_stakeholder`) USING BTREE,
  KEY `stakeholders_ibfk_4` (`stakeholder_activity_id`) USING BTREE,
  KEY `stakeholders_ibfk_5` (`created_by`) USING BTREE,
  KEY `stakeholders_ibfk_6` (`modified_by`) USING BTREE,
  CONSTRAINT `stakeholders_ibfk_1` FOREIGN KEY (`stakeholder_type_id`) REFERENCES `stakeholder_types` (`pk_id`),
  CONSTRAINT `stakeholders_ibfk_2` FOREIGN KEY (`stakeholder_sector_id`) REFERENCES `stakeholder_sectors` (`pk_id`),
  CONSTRAINT `stakeholders_ibfk_3` FOREIGN KEY (`geo_level_id`) REFERENCES `geo_levels` (`pk_id`),
  CONSTRAINT `stakeholders_ibfk_4` FOREIGN KEY (`stakeholder_activity_id`) REFERENCES `stakeholder_activities` (`pk_id`),
  CONSTRAINT `stakeholders_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stakeholders_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stakeholders_ibfk_7` FOREIGN KEY (`parent_id`) REFERENCES `stakeholders` (`pk_id`),
  CONSTRAINT `stakeholders_ibfk_8` FOREIGN KEY (`main_stakeholder`) REFERENCES `stakeholders` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8 COMMENT='contain information about stakeholders';

-- ----------------------------
-- Table structure for stock_batch
-- ----------------------------
DROP TABLE IF EXISTS `stock_batch`;
CREATE TABLE `stock_batch` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(100) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `production_date` date DEFAULT NULL,
  `vvm_type_id` int(11) DEFAULT NULL,
  `pack_info_id` int(11) DEFAULT NULL COMMENT 'manufacture_item_id for history',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_batch_ibfk_2` (`created_by`),
  KEY `stock_batch_ibfk_3` (`modified_by`),
  KEY `stock_batch_ibfk_4` (`pack_info_id`) USING BTREE,
  KEY `stock_batch_ibfk_1` (`vvm_type_id`) USING BTREE,
  CONSTRAINT `stock_batch_ibfk_1` FOREIGN KEY (`vvm_type_id`) REFERENCES `vvm_types` (`pk_id`),
  CONSTRAINT `stock_batch_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stock_batch_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stock_batch_ibfk_4` FOREIGN KEY (`pack_info_id`) REFERENCES `pack_info` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for stock_batch_vvm
-- ----------------------------
DROP TABLE IF EXISTS `stock_batch_vvm`;
CREATE TABLE `stock_batch_vvm` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_batch_warehouse_id` int(11) DEFAULT NULL,
  `vvm_stage` int(11) DEFAULT NULL,
  `quantity` decimal(10,0) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_batch_id` (`stock_batch_warehouse_id`),
  KEY `vvm_stage` (`vvm_stage`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `stock_batch_vvm_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stock_batch_vvm_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=403 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for stock_batch_vvm_history
-- ----------------------------
DROP TABLE IF EXISTS `stock_batch_vvm_history`;
CREATE TABLE `stock_batch_vvm_history` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_batch_vvm_id` int(11) DEFAULT NULL,
  `stock_batch_warehouse_id` int(11) DEFAULT NULL,
  `vvm_stage` int(11) DEFAULT NULL,
  `quantity` decimal(10,0) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_batch_vvm_id` (`stock_batch_vvm_id`),
  KEY `stock_batch_id` (`stock_batch_warehouse_id`),
  KEY `vvm_stage` (`vvm_stage`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB AUTO_INCREMENT=403 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for stock_batch_warehouses
-- ----------------------------
DROP TABLE IF EXISTS `stock_batch_warehouses`;
CREATE TABLE `stock_batch_warehouses` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` bigint(20) DEFAULT NULL,
  `status` enum('Finished','Stacked','Running') DEFAULT 'Stacked',
  `warehouse_id` int(11) NOT NULL,
  `stock_batch_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_batch_warehouses_ibfk_1` (`modified_by`) USING BTREE,
  KEY `stock_batch_warehouses_ibfk_2` (`warehouse_id`) USING BTREE,
  KEY `stock_batch_warehouses_ibfk_3` (`created_by`) USING BTREE,
  KEY `stock_batch_warehouses_ibfk_4` (`stock_batch_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for stock_batch_warehouses_history
-- ----------------------------
DROP TABLE IF EXISTS `stock_batch_warehouses_history`;
CREATE TABLE `stock_batch_warehouses_history` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_batch_warehouse_id` int(11) DEFAULT NULL,
  `number` varchar(100) DEFAULT NULL,
  `batch_master_id` int(11) DEFAULT NULL COMMENT 'system generated id',
  `expiry_date` datetime DEFAULT NULL,
  `quantity` bigint(20) DEFAULT NULL,
  `status` enum('Finished','Stacked','Running') DEFAULT 'Stacked',
  `unit_price` float DEFAULT NULL,
  `production_date` datetime DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `item_pack_size_id` int(11) NOT NULL,
  `vvm_type_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) NOT NULL,
  `stakeholder_item_pack_size_id` int(11) DEFAULT NULL COMMENT 'manufacture_item_id for history',
  `action_type` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `item_pack_size_id` (`item_pack_size_id`),
  KEY `vvm_type_id` (`vvm_type_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `batch_master_id` (`batch_master_id`),
  KEY `stakeholder_item_pack_size_id` (`stakeholder_item_pack_size_id`),
  KEY `stock_batch_warehouse_id` (`stock_batch_warehouse_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `stock_batch_warehouses_history_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stock_batch_warehouses_history_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for stock_detail
-- ----------------------------
DROP TABLE IF EXISTS `stock_detail`;
CREATE TABLE `stock_detail` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` bigint(20) DEFAULT NULL,
  `temporary` tinyint(1) DEFAULT NULL,
  `vvm_stage` int(11) DEFAULT '0',
  `is_received` int(11) DEFAULT NULL,
  `adjustment_type` int(4) DEFAULT NULL,
  `stock_master_id` int(11) NOT NULL,
  `stock_batch_warehouse_id` int(11) DEFAULT NULL,
  `item_unit_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_detail_ibfk_2` (`stock_master_id`) USING BTREE,
  KEY `stock_detail_ibfk_3` (`stock_batch_warehouse_id`) USING BTREE,
  KEY `stock_detail_ibfk_4` (`item_unit_id`) USING BTREE,
  KEY `stock_detail_ibfk_1` (`vvm_stage`) USING BTREE,
  KEY `stock_detail_ibfk_5` (`created_by`) USING BTREE,
  KEY `stock_detail_ibfk_6` (`modified_by`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for stock_detail_history
-- ----------------------------
DROP TABLE IF EXISTS `stock_detail_history`;
CREATE TABLE `stock_detail_history` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `detail_id` int(11) NOT NULL,
  `quantity` bigint(20) DEFAULT NULL,
  `temporary` tinyint(1) DEFAULT NULL,
  `vvm_stage` int(11) DEFAULT NULL,
  `is_received` int(11) DEFAULT NULL,
  `adjustment_type` tinyint(1) DEFAULT NULL,
  `stock_master_id` int(11) NOT NULL,
  `stock_batch_warehouse_id` int(11) DEFAULT NULL,
  `item_unit_id` int(11) NOT NULL,
  `action_type` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_detail_stock_master_fk1` (`stock_master_id`),
  KEY `stock_detail_stock_batch_fk2` (`stock_batch_warehouse_id`),
  KEY `stock_detail_item_units_fk3` (`item_unit_id`) USING BTREE,
  KEY `vvm_stage` (`vvm_stage`),
  KEY `adjustment_type` (`adjustment_type`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `stock_detail_history_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stock_detail_history_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stock_detail_history_ibfk_4` FOREIGN KEY (`vvm_stage`) REFERENCES `vvm_stages` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for stock_master
-- ----------------------------
DROP TABLE IF EXISTS `stock_master`;
CREATE TABLE `stock_master` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_date` datetime DEFAULT NULL,
  `transaction_number` varchar(100) DEFAULT NULL,
  `transaction_counter` int(11) DEFAULT NULL,
  `transaction_reference` varchar(255) DEFAULT NULL,
  `dispatch_by` varchar(255) DEFAULT NULL,
  `draft` tinyint(1) DEFAULT NULL,
  `comments` text,
  `transaction_type_id` int(11) NOT NULL,
  `from_warehouse_id` int(11) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `campaign_id` int(11) DEFAULT NULL,
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `shipment_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `issue_from` date DEFAULT NULL,
  `issue_to` date DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_master_ibfk_1` (`transaction_type_id`) USING BTREE,
  KEY `stock_master_ibfk_2` (`from_warehouse_id`) USING BTREE,
  KEY `stock_master_ibfk_3` (`to_warehouse_id`) USING BTREE,
  KEY `stock_master_ibfk_4` (`created_by`) USING BTREE,
  KEY `stock_master_ibfk_5` (`stakeholder_activity_id`) USING BTREE,
  KEY `stock_master_ibfk_6` (`modified_by`) USING BTREE,
  CONSTRAINT `stock_master_ibfk_1` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_types` (`pk_id`),
  CONSTRAINT `stock_master_ibfk_2` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `stock_master_ibfk_3` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `stock_master_ibfk_4` FOREIGN KEY (`stakeholder_activity_id`) REFERENCES `stakeholder_activities` (`pk_id`),
  CONSTRAINT `stock_master_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stock_master_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for stock_master_history
-- ----------------------------
DROP TABLE IF EXISTS `stock_master_history`;
CREATE TABLE `stock_master_history` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `master_id` int(11) NOT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `transaction_number` varchar(100) DEFAULT NULL,
  `transaction_counter` int(11) DEFAULT NULL,
  `transaction_reference` varchar(255) DEFAULT NULL,
  `draft` tinyint(1) DEFAULT NULL,
  `comments` text,
  `transaction_type_id` int(11) NOT NULL,
  `from_warehouse_id` int(11) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `campaign_id` int(11) DEFAULT NULL,
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action_type` tinyint(4) NOT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_master_transaction_types_fk1` (`transaction_type_id`),
  KEY `stock_master_warehouses_fk2` (`from_warehouse_id`),
  KEY `stock_master_warehouses_fk3` (`to_warehouse_id`),
  KEY `stock_master_users_fk4` (`created_by`),
  KEY `stock_master_ibfk_1` (`stakeholder_activity_id`),
  KEY `modified_by` (`modified_by`),
  KEY `master_id` (`master_id`),
  CONSTRAINT `stock_master_history_ibfk_1` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_types` (`pk_id`),
  CONSTRAINT `stock_master_history_ibfk_2` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `stock_master_history_ibfk_3` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `stock_master_history_ibfk_4` FOREIGN KEY (`stakeholder_activity_id`) REFERENCES `stakeholder_activities` (`pk_id`),
  CONSTRAINT `stock_master_history_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `stock_master_history_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for stock_used_for
-- ----------------------------
DROP TABLE IF EXISTS `stock_used_for`;
CREATE TABLE `stock_used_for` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` decimal(10,0) DEFAULT NULL,
  `stock_detail_id` int(11) DEFAULT NULL,
  `transaction_type_id` int(11) DEFAULT NULL,
  `item_pair_logic_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stock_detail_id` (`stock_detail_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `transaction_type_id` (`transaction_type_id`),
  KEY `item_pair_logic_id` (`item_pair_logic_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for survey
-- ----------------------------
DROP TABLE IF EXISTS `survey`;
CREATE TABLE `survey` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `office` varchar(255) NOT NULL,
  `cell_number` varchar(25) NOT NULL,
  `q1_data_difficulty` text,
  `q2_report` text,
  `comment` text,
  `q1_y_n` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `q2_y_n` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `survey_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `survey_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `survey_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=302 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for system
-- ----------------------------
DROP TABLE IF EXISTS `system`;
CREATE TABLE `system` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` datetime DEFAULT NULL COMMENT 'application version',
  `tag_line` varchar(100) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `system_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `system_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='contains system wide global information.';

-- ----------------------------
-- Table structure for transaction_types
-- ----------------------------
DROP TABLE IF EXISTS `transaction_types`;
CREATE TABLE `transaction_types` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_type_name` varchar(100) DEFAULT NULL,
  `nature` varchar(1) DEFAULT NULL,
  `is_adjustment` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `transaction_types_users_fk1` (`created_by`),
  KEY `transaction_types_users_fk2` (`modified_by`),
  CONSTRAINT `transaction_types_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `transaction_types_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for user_click_paths
-- ----------------------------
DROP TABLE IF EXISTS `user_click_paths`;
CREATE TABLE `user_click_paths` (
  `pk_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `user_id` (`user_id`),
  KEY `resource_id` (`resource_id`),
  KEY `pk_id` (`pk_id`),
  CONSTRAINT `user_click_paths_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `user_click_paths_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for user_documents
-- ----------------------------
DROP TABLE IF EXISTS `user_documents`;
CREATE TABLE `user_documents` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(100) DEFAULT NULL,
  `doc_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `system_ip` varchar(50) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `user_id` (`user_id`),
  KEY `doc_id` (`doc_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `user_documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `user_documents_ibfk_2` FOREIGN KEY (`doc_id`) REFERENCES `documents` (`pk_id`),
  CONSTRAINT `user_documents_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `user_documents_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for user_feedback
-- ----------------------------
DROP TABLE IF EXISTS `user_feedback`;
CREATE TABLE `user_feedback` (
  `pk_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `message` longtext,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for user_login_log
-- ----------------------------
DROP TABLE IF EXISTS `user_login_log`;
CREATE TABLE `user_login_log` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(15) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `user_id` (`user_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `pk_id` (`pk_id`),
  CONSTRAINT `user_login_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `user_login_log_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `user_login_log_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `password` char(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `cell_number` varchar(25) DEFAULT NULL,
  `record_id` varchar(25) DEFAULT NULL,
  `login_id` varchar(150) DEFAULT NULL,
  `designation` varchar(150) DEFAULT NULL,
  `department` varchar(150) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL COMMENT 'date when account was opened',
  `address` varchar(200) DEFAULT NULL,
  `phone_number` varchar(25) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `logged_at` datetime DEFAULT NULL,
  `failed_at` datetime DEFAULT NULL,
  `failed_quantity` int(11) DEFAULT '0',
  `role_id` int(11) NOT NULL,
  `stakeholder_id` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `auth` varchar(50) DEFAULT NULL,
  `organization` varchar(50) DEFAULT NULL,
  `country` int(11) unsigned zerofill DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `users_roles_fk1` (`role_id`),
  KEY `users_stakeholders_fk2` (`stakeholder_id`) USING BTREE,
  KEY `users_users_fk4` (`created_by`) USING BTREE,
  KEY `users_locations_fk3` (`location_id`) USING BTREE,
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`pk_id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`stakeholder_id`) REFERENCES `stakeholders` (`pk_id`),
  CONSTRAINT `users_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `users_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `users_ibfk_5` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2416 DEFAULT CHARSET=utf8 COMMENT='contain user information';

-- ----------------------------
-- Table structure for var_detail
-- ----------------------------
DROP TABLE IF EXISTS `var_detail`;
CREATE TABLE `var_detail` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(255) DEFAULT NULL,
  `report_no` varchar(255) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `place_of_inspection` varchar(255) DEFAULT NULL,
  `date_of_inspection` date DEFAULT NULL,
  `cold_store` varchar(255) DEFAULT NULL,
  `date_of_vaccines_entered_cold_store` date DEFAULT NULL,
  `pre_advice_date` date DEFAULT NULL,
  `shipping_notification_date` date DEFAULT NULL,
  `awb` int(11) DEFAULT NULL,
  `packing_list` int(11) DEFAULT NULL,
  `invoice` int(11) DEFAULT NULL,
  `release_certificate` int(11) DEFAULT NULL,
  `advance_note_other_document` varchar(255) DEFAULT NULL,
  `awb_number` varchar(255) DEFAULT NULL,
  `airport_of_destination` varchar(255) DEFAULT NULL,
  `flight_no` varchar(255) DEFAULT NULL,
  `eta_date` date DEFAULT NULL,
  `actual_time_arrival` date DEFAULT NULL,
  `name_of_clearing_agent` varchar(255) DEFAULT NULL,
  `on_behalf` varchar(255) DEFAULT NULL,
  `quantity_received` int(11) DEFAULT NULL,
  `detail_short_shipment` int(11) DEFAULT NULL,
  `quantity_received_comments` varchar(255) DEFAULT NULL,
  `detail_short_shipment_comments` varchar(255) DEFAULT NULL,
  `documents_invoice` int(11) DEFAULT NULL,
  `documents_packing_list` int(11) DEFAULT NULL,
  `documents_release_certificate` int(11) DEFAULT NULL,
  `documents_var` varchar(255) DEFAULT NULL,
  `document_other` varchar(255) DEFAULT NULL,
  `part4_comments` varchar(255) DEFAULT NULL,
  `total_number_boxes_inspected` varchar(255) DEFAULT NULL,
  `dry_ice` int(11) DEFAULT NULL,
  `ice_packs` int(11) DEFAULT NULL,
  `no_coolant` int(11) DEFAULT NULL,
  `coolant_type_empty` int(11) DEFAULT NULL,
  `vvm` int(11) DEFAULT NULL,
  `cold_chain_card` int(11) DEFAULT NULL,
  `electronic_device` int(11) DEFAULT NULL,
  `temperature_monitors_empty` int(11) DEFAULT NULL,
  `condition_of_boxes_arrival` varchar(255) DEFAULT NULL,
  `labels_attached` varchar(255) DEFAULT NULL,
  `other_comments_electronic_device` longtext,
  `shipment_id` int(11) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `purchase_order_no` varchar(255) DEFAULT NULL,
  `consignee` varchar(255) DEFAULT NULL,
  `vaccine_description` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `country_part3` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `item_pack_size_id` (`report_no`),
  KEY `funding_source_id` (`date_of_inspection`),
  KEY `stakeholder_activity_id` (`cold_store`),
  KEY `warehouse_id` (`date_of_vaccines_entered_cold_store`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `var_detail_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `var_detail_ibfk_5` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for var_electronic_device
-- ----------------------------
DROP TABLE IF EXISTS `var_electronic_device`;
CREATE TABLE `var_electronic_device` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `box_number` varchar(255) DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `less_45` varchar(255) DEFAULT NULL,
  `less_30` varchar(255) DEFAULT NULL,
  `less_10` varchar(255) DEFAULT NULL,
  `less_5` varchar(255) DEFAULT NULL,
  `cold_chain_a` varchar(255) DEFAULT NULL,
  `cold_chain_b` varchar(255) DEFAULT NULL,
  `cold_chain_c` varchar(255) DEFAULT NULL,
  `cold_chain_d` varchar(255) DEFAULT NULL,
  `day_of_inspection` varchar(255) DEFAULT NULL,
  `date_of_inspection` date DEFAULT NULL,
  `var_detail_id` int(11) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `item_pack_size_id` (`lot_no`),
  KEY `funding_source_id` (`less_10`),
  KEY `stakeholder_activity_id` (`less_5`),
  KEY `warehouse_id` (`cold_chain_a`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `var_electronic_device_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `var_electronic_device_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for viewstats1
-- ----------------------------
DROP TABLE IF EXISTS `viewstats1`;
CREATE TABLE `viewstats1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ParentMenu` text,
  `ChildMenu` text,
  `PageUrl` varchar(121) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=latin1
/*!50100 PARTITION BY HASH (id)
PARTITIONS 8 */;

-- ----------------------------
-- Table structure for vpd_clinical_presentation
-- ----------------------------
DROP TABLE IF EXISTS `vpd_clinical_presentation`;
CREATE TABLE `vpd_clinical_presentation` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `vpd_form_id` int(11) DEFAULT NULL,
  `clinical_presentation_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `vpd_clinical_presentation_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `vpd_clinical_presentation_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for vpd_form
-- ----------------------------
DROP TABLE IF EXISTS `vpd_form`;
CREATE TABLE `vpd_form` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NULL DEFAULT NULL,
  `week` varchar(255) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `tehsil_id` int(11) DEFAULT NULL,
  `uc_id` int(11) DEFAULT NULL,
  `hf_id` int(11) DEFAULT NULL,
  `hf_name` varchar(255) DEFAULT NULL,
  `type_case` int(11) DEFAULT NULL COMMENT 'List Detail Table - - MasterID 38',
  `epid_number` varchar(255) DEFAULT NULL,
  `child_name` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `cross_notification` int(11) DEFAULT NULL COMMENT 'List Detail Table - MasterID 44',
  `dob` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `age` int(11) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `date_onset` date DEFAULT NULL,
  `date_notification` date DEFAULT NULL,
  `date_investigation` date DEFAULT NULL,
  `specific_dose_received` int(11) DEFAULT NULL COMMENT 'List Detail Table - MasterID 45',
  `date_last_dose_received` datetime DEFAULT NULL,
  `specimen_collection` int(11) DEFAULT NULL COMMENT 'List Detail Table - - MasterID 39',
  `date_specimen_sent` datetime DEFAULT NULL,
  `sign_symptoms` varchar(255) DEFAULT NULL,
  `outcome` int(11) DEFAULT NULL COMMENT 'List Detail Table - - MasterID 41',
  `lab_result` int(11) DEFAULT NULL COMMENT 'List Detail Table - - MasterID 42',
  `final_classification` int(11) DEFAULT NULL COMMENT 'List Detail Table - - MasterID 43',
  `stool_sample_date1` datetime DEFAULT NULL,
  `stool_sample_date2` datetime DEFAULT NULL,
  `blood_sample_date` datetime DEFAULT NULL,
  `blood_sample_month` varchar(255) DEFAULT NULL,
  `clinical_presentation` varchar(255) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `from_uc_id` int(11) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `address_village` varchar(255) DEFAULT NULL,
  `age_unit` varchar(255) DEFAULT NULL,
  `patient_visited_date` datetime DEFAULT NULL,
  `reporting_status` int(11) DEFAULT NULL,
  `has_child_received_opv` int(11) DEFAULT NULL,
  `is_the_child_protected` int(11) DEFAULT NULL,
  `specimen_collection_date` datetime DEFAULT NULL,
  `number_of_months` int(11) DEFAULT NULL,
  `date_of_lab_resulted` datetime DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for vpd_weeks
-- ----------------------------
DROP TABLE IF EXISTS `vpd_weeks`;
CREATE TABLE `vpd_weeks` (
  `pk_Id` int(11) NOT NULL AUTO_INCREMENT,
  `week` varchar(255) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `report_date` datetime DEFAULT NULL,
  PRIMARY KEY (`pk_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=266 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for vstats2
-- ----------------------------
DROP TABLE IF EXISTS `vstats2`;
CREATE TABLE `vstats2` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `ParentMenu` text,
  `ChildMenu` text,
  `PageURL` varchar(121) DEFAULT NULL,
  `clickCounts` bigint(21) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=latin1
/*!50100 PARTITION BY HASH (pk_id)
PARTITIONS 16 */;

-- ----------------------------
-- Table structure for vstats3
-- ----------------------------
DROP TABLE IF EXISTS `vstats3`;
CREATE TABLE `vstats3` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ParentMenu` text,
  `ChildMenu` text,
  `PageUrl` varchar(121) DEFAULT NULL,
  `clickCounts` bigint(21) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=latin1
/*!50100 PARTITION BY HASH (id)
PARTITIONS 8 */;

-- ----------------------------
-- Table structure for vtbl_dataentryusers
-- ----------------------------
DROP TABLE IF EXISTS `vtbl_dataentryusers`;
CREATE TABLE `vtbl_dataentryusers` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `login_id` int(11) NOT NULL DEFAULT '0',
  `role` int(11) NOT NULL,
  `province_id` int(11) DEFAULT NULL COMMENT 'province of warehouse',
  `location_name` varchar(100) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
/*!50100 PARTITION BY HASH (pk_id)
PARTITIONS 8 */;

-- ----------------------------
-- Table structure for vtbl_reporting_points
-- ----------------------------
DROP TABLE IF EXISTS `vtbl_reporting_points`;
CREATE TABLE `vtbl_reporting_points` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `province` varchar(100) DEFAULT NULL,
  `LocName` varchar(100) DEFAULT NULL,
  `NoOfPoints` bigint(21) NOT NULL DEFAULT '0',
  `district_id` int(11) DEFAULT NULL,
  `prov_id` int(11) DEFAULT NULL COMMENT 'province of warehouse',
  `hf_type` varchar(100) NOT NULL DEFAULT '',
  `gLevel` int(11) NOT NULL DEFAULT '0' COMMENT 'distribution level id',
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
/*!50100 PARTITION BY HASH (pk_id)
PARTITIONS 8 */;

-- ----------------------------
-- Table structure for vvm_groups
-- ----------------------------
DROP TABLE IF EXISTS `vvm_groups`;
CREATE TABLE `vvm_groups` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `vvm_group_id` int(11) DEFAULT NULL,
  `vvm_stage_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `vvm_group_id` (`vvm_group_id`),
  KEY `vvm_stage_id` (`vvm_stage_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `vvm_groups_ibfk_1` FOREIGN KEY (`vvm_stage_id`) REFERENCES `vvm_stages` (`pk_id`),
  CONSTRAINT `vvm_groups_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `vvm_groups_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `vvm_groups_ibfk_4` FOREIGN KEY (`vvm_group_id`) REFERENCES `vvm_groups` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for vvm_stages
-- ----------------------------
DROP TABLE IF EXISTS `vvm_stages`;
CREATE TABLE `vvm_stages` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `vvm_stage_value` varchar(50) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `vvm_stages_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `vvm_stages_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for vvm_transfer_history
-- ----------------------------
DROP TABLE IF EXISTS `vvm_transfer_history`;
CREATE TABLE `vvm_transfer_history` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_batch_warehouse_id` int(11) DEFAULT NULL,
  `from_vvm_stage_id` int(11) DEFAULT NULL,
  `to_vvm_stage_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `batch_id` (`stock_batch_warehouse_id`),
  KEY `from_vvm_stage_id` (`from_vvm_stage_id`),
  KEY `to_vvm_stage_id` (`to_vvm_stage_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for vvm_types
-- ----------------------------
DROP TABLE IF EXISTS `vvm_types`;
CREATE TABLE `vvm_types` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `vvm_type_name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `vvm_types_users_fk1` (`created_by`),
  KEY `vvm_types_users_fk2` (`modified_by`),
  CONSTRAINT `vvm_types_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `vvm_types_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for w_target
-- ----------------------------
DROP TABLE IF EXISTS `w_target`;
CREATE TABLE `w_target` (
  `HF ID` varchar(255) DEFAULT NULL,
  `Total Population` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for warehouse_population
-- ----------------------------
DROP TABLE IF EXISTS `warehouse_population`;
CREATE TABLE `warehouse_population` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `facility_total_pouplation` int(11) DEFAULT NULL,
  `live_births_per_year` int(11) DEFAULT NULL,
  `pregnant_women_per_year` int(11) DEFAULT NULL,
  `women_of_child_bearing_age` int(11) DEFAULT NULL,
  `estimation_year` datetime DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `requirments_4degree` double(11,2) DEFAULT NULL,
  `requirments_20degree` double(11,2) DEFAULT NULL,
  `capacity_4degree` double(11,2) DEFAULT NULL,
  `capacity_20degree` double(11,2) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `surviving_children_0_11` int(11) DEFAULT NULL,
  `children_aged_12_23` int(11) DEFAULT NULL,
  `above_2_year` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `warehouses_population_warehouses_fk1` (`warehouse_id`) USING BTREE,
  KEY `warehouses_population_users_fk2` (`created_by`) USING BTREE,
  KEY `warehouses_population_users_fk3` (`modified_by`) USING BTREE,
  CONSTRAINT `warehouse_population_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `warehouse_population_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouse_population_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15257 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for warehouse_population_archieve
-- ----------------------------
DROP TABLE IF EXISTS `warehouse_population_archieve`;
CREATE TABLE `warehouse_population_archieve` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `facility_total_pouplation` int(255) DEFAULT NULL,
  `live_births_per_year` int(255) DEFAULT NULL,
  `pregnant_women_per_year` int(255) DEFAULT NULL,
  `women_of_child_bearing_age` int(255) DEFAULT NULL,
  `estimation_year` datetime DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `warehouses_population_archieve_users_fk2` (`created_by`) USING BTREE,
  KEY `warehouses_population_archieve_users_fk3` (`modified_by`) USING BTREE,
  KEY `warehouses_population_archieve_warehouses_fk1` (`warehouse_id`) USING BTREE,
  CONSTRAINT `warehouse_population_archieve_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `warehouse_population_archieve_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouse_population_archieve_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for warehouse_storage_types
-- ----------------------------
DROP TABLE IF EXISTS `warehouse_storage_types`;
CREATE TABLE `warehouse_storage_types` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `storage_temperature` int(11) DEFAULT NULL,
  `stakeholder_item_pack_size_id` int(11) DEFAULT NULL,
  `warehouse_type_id` int(11) DEFAULT NULL,
  `stakeholder_activity_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `warehouse_storage_types_ibfk_3` (`stakeholder_item_pack_size_id`) USING BTREE,
  KEY `warehouse_storage_types_ibfk_4` (`warehouse_type_id`) USING BTREE,
  KEY `warehouse_storage_types_ibfk_5` (`stakeholder_activity_id`) USING BTREE,
  KEY `warehouse_storage_types_ibfk_1` (`created_by`) USING BTREE,
  KEY `warehouse_storage_types_ibfk_2` (`modified_by`) USING BTREE,
  CONSTRAINT `warehouse_storage_types_ibfk_1` FOREIGN KEY (`stakeholder_item_pack_size_id`) REFERENCES `stakeholder_item_pack_sizes` (`pk_id`),
  CONSTRAINT `warehouse_storage_types_ibfk_2` FOREIGN KEY (`warehouse_type_id`) REFERENCES `warehouse_types` (`pk_id`),
  CONSTRAINT `warehouse_storage_types_ibfk_3` FOREIGN KEY (`stakeholder_activity_id`) REFERENCES `stakeholder_activities` (`pk_id`),
  CONSTRAINT `warehouse_storage_types_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouse_storage_types_ibfk_5` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for warehouse_type_categories
-- ----------------------------
DROP TABLE IF EXISTS `warehouse_type_categories`;
CREATE TABLE `warehouse_type_categories` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(200) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `warehouse_type_categories_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouse_type_categories_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for warehouse_types
-- ----------------------------
DROP TABLE IF EXISTS `warehouse_types`;
CREATE TABLE `warehouse_types` (
  `pk_id` int(11) NOT NULL,
  `warehouse_type_name` varchar(100) DEFAULT NULL,
  `resupply_interval` int(11) DEFAULT NULL,
  `reserved_stock` int(11) DEFAULT NULL,
  `usage_percentage` decimal(2,0) DEFAULT NULL,
  `geo_level_id` int(11) DEFAULT NULL,
  `warehouse_type_category_id` int(11) DEFAULT NULL,
  `list_rank` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `warehouse_types_users_fk1` (`created_by`) USING BTREE,
  KEY `warehouse_types_users_fk2` (`modified_by`) USING BTREE,
  KEY `geo_level_id` (`geo_level_id`),
  KEY `warehouse_type_category_id` (`warehouse_type_category_id`),
  KEY `list_rank` (`list_rank`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `warehouse_types_ibfk_1` FOREIGN KEY (`warehouse_type_category_id`) REFERENCES `warehouse_type_categories` (`pk_id`),
  CONSTRAINT `warehouse_types_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouse_types_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for warehouse_users
-- ----------------------------
DROP TABLE IF EXISTS `warehouse_users`;
CREATE TABLE `warehouse_users` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `warehouse_users_users_fk1` (`user_id`),
  KEY `warehouse_id` (`warehouse_id`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `warehouse_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouse_users_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `warehouse_users_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouse_users_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23824 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for warehouse_vaccine_storage_types
-- ----------------------------
DROP TABLE IF EXISTS `warehouse_vaccine_storage_types`;
CREATE TABLE `warehouse_vaccine_storage_types` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `warehouse_type_id` int(11) DEFAULT NULL,
  `temperature_type` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `warehouse_vaccine_storage_types_ibfk_1` (`item_id`) USING BTREE,
  KEY `warehouse_vaccine_storage_types_ibfk_2` (`warehouse_type_id`) USING BTREE,
  KEY `warehouse_vaccine_storage_types_ibfk_3` (`created_by`) USING BTREE,
  KEY `warehouse_vaccine_storage_types_ibfk_4` (`modified_by`) USING BTREE,
  CONSTRAINT `warehouse_vaccine_storage_types_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item_pack_sizes` (`pk_id`),
  CONSTRAINT `warehouse_vaccine_storage_types_ibfk_2` FOREIGN KEY (`warehouse_type_id`) REFERENCES `warehouse_types` (`pk_id`),
  CONSTRAINT `warehouse_vaccine_storage_types_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouse_vaccine_storage_types_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for warehouses
-- ----------------------------
DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE `warehouses` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `warehouse_name` varchar(255) DEFAULT NULL,
  `population` double(255,0) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL COMMENT 'province of warehouse',
  `stakeholder_id` int(11) DEFAULT NULL COMMENT 'stakeholder',
  `location_id` int(11) DEFAULT NULL,
  `stakeholder_office_id` int(11) DEFAULT NULL,
  `warehouse_type_id` int(11) DEFAULT NULL,
  `ccem_id` varchar(50) DEFAULT NULL,
  `starting_on` datetime DEFAULT NULL,
  `from_edit` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `dhis_code` int(11) DEFAULT NULL,
  `is_placement_enable` tinyint(1) DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `stakeholder_office_id` (`stakeholder_office_id`),
  KEY `district_id` (`district_id`),
  KEY `province_id` (`province_id`),
  KEY `province_id_2` (`province_id`),
  KEY `location_id` (`location_id`),
  KEY `warehouse_type_id` (`warehouse_type_id`),
  KEY `stakeholder_id` (`stakeholder_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `warehouses_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `warehouses_ibfk_2` FOREIGN KEY (`stakeholder_id`) REFERENCES `stakeholders` (`pk_id`),
  CONSTRAINT `warehouses_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `locations` (`pk_id`),
  CONSTRAINT `warehouses_ibfk_4` FOREIGN KEY (`stakeholder_office_id`) REFERENCES `stakeholders` (`pk_id`),
  CONSTRAINT `warehouses_ibfk_5` FOREIGN KEY (`warehouse_type_id`) REFERENCES `warehouse_types` (`pk_id`),
  CONSTRAINT `warehouses_ibfk_6` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouses_ibfk_7` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouses_ibfk_8` FOREIGN KEY (`district_id`) REFERENCES `locations` (`pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16305 DEFAULT CHARSET=utf8 COMMENT='contain information about warehouse';

-- ----------------------------
-- Table structure for warehouses_data
-- ----------------------------
DROP TABLE IF EXISTS `warehouses_data`;
CREATE TABLE `warehouses_data` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `opening_balance` int(11) DEFAULT NULL,
  `received_balance` int(11) DEFAULT NULL,
  `issue_balance` int(11) DEFAULT NULL,
  `closing_balance` int(11) DEFAULT NULL,
  `wastages` int(11) DEFAULT NULL,
  `vials_used` int(11) DEFAULT NULL,
  `adjustments` int(11) DEFAULT NULL,
  `reporting_start_date` datetime DEFAULT NULL,
  `nearest_expiry` datetime DEFAULT NULL,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_calculated` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`pk_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `warehouses_data_ibfk_1` (`item_pack_size_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for warehouses_data_draft
-- ----------------------------
DROP TABLE IF EXISTS `warehouses_data_draft`;
CREATE TABLE `warehouses_data_draft` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `opening_balance` int(11) DEFAULT NULL,
  `received_balance` int(11) DEFAULT NULL,
  `issue_balance` int(11) DEFAULT NULL,
  `closing_balance` int(11) DEFAULT NULL,
  `wastages` int(11) DEFAULT NULL,
  `vials_used` int(11) DEFAULT NULL,
  `adjustments` int(11) DEFAULT NULL,
  `reporting_start_date` datetime DEFAULT NULL,
  `nearest_expiry` datetime DEFAULT NULL,
  `item_pack_size_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `item_pack_size_id` (`item_pack_size_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for warehouses_service_types
-- ----------------------------
DROP TABLE IF EXISTS `warehouses_service_types`;
CREATE TABLE `warehouses_service_types` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) DEFAULT NULL,
  `service_type_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_id`),
  KEY `warehouses_list_detail_pk_id_fk3` (`service_type_id`) USING BTREE,
  KEY `warehouses_warehouses_fk1` (`warehouse_id`) USING BTREE,
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `warehouses_service_types_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouses_service_types_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouses_service_types_ibfk_3` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`),
  CONSTRAINT `warehouses_service_types_ibfk_4` FOREIGN KEY (`service_type_id`) REFERENCES `list_detail` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for warehouses_update_history
-- ----------------------------
DROP TABLE IF EXISTS `warehouses_update_history`;
CREATE TABLE `warehouses_update_history` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `ip_address` varchar(20) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `warehouses_update_history_warehouses_fk1` (`warehouse_id`) USING BTREE,
  KEY `warehouses_update_history_users_fk2` (`modified_by`) USING BTREE,
  KEY `created_by` (`created_by`),
  CONSTRAINT `warehouses_update_history_ibfk_1` FOREIGN KEY (`modified_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouses_update_history_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`pk_id`),
  CONSTRAINT `warehouses_update_history_ibfk_3` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- View structure for v_dataentryusers
-- ----------------------------
DROP VIEW IF EXISTS `v_dataentryusers`;
CREATE ALGORITHM=UNDEFINED DEFINER=`vlmisr2user`@`203.99.49.122` SQL SECURITY DEFINER VIEW `v_dataentryusers` AS select distinct `beta_vlmis15032018`.`users`.`pk_id` AS `UserID`,`beta_vlmis15032018`.`user_login_log`.`pk_id` AS `login_id`,`beta_vlmis15032018`.`users`.`role_id` AS `role`,`beta_vlmis15032018`.`warehouses`.`province_id` AS `province_id`,`beta_vlmis15032018`.`locations`.`location_name` AS `location_name`,`beta_vlmis15032018`.`user_login_log`.`login_time` AS `login_time` from ((((`warehouse_users` join `users` on((`beta_vlmis15032018`.`users`.`pk_id` = `beta_vlmis15032018`.`warehouse_users`.`user_id`))) join `user_login_log` on((`beta_vlmis15032018`.`users`.`pk_id` = `beta_vlmis15032018`.`user_login_log`.`user_id`))) join `warehouses` on((`beta_vlmis15032018`.`warehouse_users`.`warehouse_id` = `beta_vlmis15032018`.`warehouses`.`pk_id`))) join `locations` on((`beta_vlmis15032018`.`warehouses`.`province_id` = `beta_vlmis15032018`.`locations`.`pk_id`))) where (`beta_vlmis15032018`.`user_login_log`.`login_time` >= (now() - interval 6 month)) order by `beta_vlmis15032018`.`locations`.`pk_id`,`beta_vlmis15032018`.`user_login_log`.`login_time` ;

-- ----------------------------
-- View structure for v_reporting_points
-- ----------------------------
DROP VIEW IF EXISTS `v_reporting_points`;
CREATE ALGORITHM=UNDEFINED DEFINER=`vlmisr2user`@`203.99.49.122` SQL SECURITY DEFINER VIEW `v_reporting_points` AS select `prov`.`location_name` AS `province`,`dist`.`location_name` AS `LocName`,count(distinct `beta_vlmis15032018`.`warehouses`.`pk_id`) AS `NoOfPoints`,`beta_vlmis15032018`.`warehouses`.`district_id` AS `district_id`,`beta_vlmis15032018`.`warehouses`.`province_id` AS `prov_id`,ifnull(`beta_vlmis15032018`.`warehouse_types`.`warehouse_type_name`,'EPI Store') AS `hf_type`,`beta_vlmis15032018`.`geo_levels`.`pk_id` AS `gLevel` from (((((`warehouses` join `stakeholders` on((`beta_vlmis15032018`.`warehouses`.`stakeholder_office_id` = `beta_vlmis15032018`.`stakeholders`.`pk_id`))) join `geo_levels` on((`beta_vlmis15032018`.`stakeholders`.`geo_level_id` = `beta_vlmis15032018`.`geo_levels`.`pk_id`))) join `locations` `dist` on((`beta_vlmis15032018`.`warehouses`.`district_id` = `dist`.`pk_id`))) join `locations` `prov` on((`beta_vlmis15032018`.`warehouses`.`province_id` = `prov`.`pk_id`))) left join `warehouse_types` on((`beta_vlmis15032018`.`warehouses`.`warehouse_type_id` = `beta_vlmis15032018`.`warehouse_types`.`pk_id`))) where (`beta_vlmis15032018`.`stakeholders`.`stakeholder_type_id` = 1) group by `beta_vlmis15032018`.`warehouses`.`pk_id` order by `prov`.`location_name`,`dist`.`location_name`,`beta_vlmis15032018`.`warehouse_types`.`warehouse_type_name` ;

-- ----------------------------
-- View structure for viewstats
-- ----------------------------
DROP VIEW IF EXISTS `viewstats`;
CREATE ALGORITHM=UNDEFINED DEFINER=`iftikhar`@`localhost` SQL SECURITY DEFINER VIEW `viewstats` AS select distinct `parent`.`description` AS `ParentMenu`,`resources`.`description` AS `ChildMenu`,concat('http://v.lmis.gov.pk/',`resources`.`resource_name`) AS `PageURL`,count(`user_click_paths`.`pk_id`) AS `clickCounts` from ((((`user_login_log` join `users` on((`user_login_log`.`user_id` = `users`.`pk_id`))) join `user_click_paths` on((`user_click_paths`.`user_id` = `users`.`pk_id`))) join `resources` on((`user_click_paths`.`resource_id` = `resources`.`pk_id`))) join `resources` `parent` on((`resources`.`parent_id` = `parent`.`pk_id`))) where ((`user_login_log`.`login_time` >= (now() - interval 3 month)) and (`user_click_paths`.`created_date` >= (now() - interval 3 month)) and (`resources`.`resource_type_id` = 1) and (`users`.`role_id` not in (1,2,17,35,36,49))) group by `resources`.`pk_id` ;

-- ----------------------------
-- View structure for vstats
-- ----------------------------
DROP VIEW IF EXISTS `vstats`;
CREATE ALGORITHM=UNDEFINED DEFINER=`vlmisr2user`@`203.99.49.122` SQL SECURITY DEFINER VIEW `vstats` AS select distinct `parent`.`description` AS `ParentMenu`,`beta_vlmis15032018`.`resources`.`description` AS `ChildMenu`,concat('http://v.lmis.gov.pk/',`beta_vlmis15032018`.`resources`.`resource_name`) AS `PageURL` from ((((`user_login_log` join `users` on((`beta_vlmis15032018`.`user_login_log`.`user_id` = `beta_vlmis15032018`.`users`.`pk_id`))) join `user_click_paths` on((`beta_vlmis15032018`.`user_click_paths`.`user_id` = `beta_vlmis15032018`.`users`.`pk_id`))) join `resources` on((`beta_vlmis15032018`.`user_click_paths`.`resource_id` = `beta_vlmis15032018`.`resources`.`pk_id`))) join `resources` `parent` on((`beta_vlmis15032018`.`resources`.`parent_id` = `parent`.`pk_id`))) where ((`beta_vlmis15032018`.`user_login_log`.`login_time` >= (now() - interval 3 month)) and (`beta_vlmis15032018`.`user_click_paths`.`created_date` >= (now() - interval 3 month)) and (`beta_vlmis15032018`.`resources`.`resource_type_id` = 1) and (`beta_vlmis15032018`.`users`.`role_id` not in (1,2,17,35,36,49))) ;

-- ----------------------------
-- Procedure structure for _test_copy
-- ----------------------------
DROP PROCEDURE IF EXISTS `_test_copy`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `_test_copy`(in_type char(2), in_rpt_date DATE, in_location_id INT, in_item INT, in_stakeholder INT)
    DETERMINISTIC
BEGIN
SET @num := 0;
SET @loc := 0;
CASE
WHEN (in_type = 'N') THEN
	SELECT
		B.pk_id,
		B.item_name,
		A.AMC,
		C.CB
	FROM
		(
			SELECT
				B.pk_id,
				B.item_name,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.item_name,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							item_pack_sizes.pk_id,
							item_pack_sizes.item_name,
							SUM(
								hf_data_master.issue_balance
							) AS csum
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
						WHERE
							stakeholders.geo_level_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						GROUP BY
							item_pack_sizes.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							item_pack_sizes.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			item_pack_sizes.pk_id,
			item_pack_sizes.item_name
		FROM
			item_pack_sizes
		ORDER BY
			item_pack_sizes.list_rank
	) B ON A.pk_id = B.pk_id
	LEFT JOIN (
		SELECT
			D.pk_id,
			D.CB + (
				SELECT
					IFNULL(
						SUM(stock_detail.quantity),
						0
					)
				FROM
					stock_master
				INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
				INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
				INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
				INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
				INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
				INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
				INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
				WHERE
					DATE_FORMAT(
						stock_master.transaction_date,
						'%Y-%m-%d'
					) <= in_rpt_date
				AND stakeholder_item_pack_sizes.item_pack_size_id = D.pk_id
				AND stock_detail.is_received != 0
			) AS CB
		FROM
			(
				SELECT
					item_pack_sizes.pk_id,
					item_pack_sizes.item_name,
					SUM(
						hf_data_master.closing_balance
					) AS CB
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
				WHERE
					DATE_FORMAT(
						hf_data_master.reporting_start_date,
						'%Y-%m-%d'
					) = in_rpt_date
				AND stakeholders.geo_level_id >= 1
				GROUP BY
					item_pack_sizes.pk_id
			) D
	) C 
	ON C.pk_id = B.pk_id;
WHEN (in_type = 'NP') THEN
		SELECT
			b.item_pack_size_id,
			AVG(b.csum) AS AMC,
			C.CB
		FROM
			(
				SELECT
					A.item_pack_size_id,
					A.csum,
					@num :=
				IF (@loc = item_pack_size_id, @num + 1, 1) AS row_number,
				@loc := item_pack_size_id
			FROM
				(
					SELECT
						hf_data_master.item_pack_size_id,
						SUM(
							hf_data_master.issue_balance
						) AS csum
					FROM
						warehouses
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
					INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
					WHERE
						stakeholders.geo_level_id = 6
					AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
					AND hf_data_master.item_pack_size_id = in_item
					GROUP BY
						hf_data_master.item_pack_size_id,
						hf_data_master.reporting_start_date
					ORDER BY
						hf_data_master.item_pack_size_id,
						hf_data_master.reporting_start_date DESC
				) AS A
			) AS b
		JOIN (SELECT
						hf_data_master.item_pack_size_id,
						SUM(hf_data_master.closing_balance) + (
							SELECT
								IFNULL(SUM(stock_detail.quantity), 0)
							FROM
								stock_master
							INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
							INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
							INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
							INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
							INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
							INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
							INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
							WHERE
								DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= in_rpt_date
							AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
							AND stock_detail.is_received != 0
						) AS CB
					FROM
						warehouses
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
					WHERE
						DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
					AND hf_data_master.item_pack_size_id = in_item
					AND stakeholders.geo_level_id >= 1
					GROUP BY
						hf_data_master.item_pack_size_id) C
		ON C.item_pack_size_id = b.item_pack_size_id
		WHERE
			row_number <= 3
		GROUP BY
			item_pack_size_id;
WHEN (in_type = 'PP') THEN
	SELECT
		B.pk_id,
		B.location_name,
		A.AMC,
		C.CB
	FROM
		(
			SELECT
				b.province_id,
				AVG(b.csum) AS AMC
			FROM
				(
					SELECT
						A.province_id,
						A.csum,
						@num :=
					IF (@loc = province_id, @num + 1, 1) AS row_number,
					@loc := province_id
				FROM
					(
						SELECT
							warehouses.province_id,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
						WHERE
							stakeholders.geo_level_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.province_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.province_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS b
			WHERE
				row_number <= 3
			GROUP BY
				province_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 2
		ORDER BY
			locations.pk_id ASC
	) B ON A.province_id = B.pk_id
	LEFT JOIN (
		SELECT
		D.province_id,
		D.CB + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0)
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
			WHERE
				DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= in_rpt_date
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
			AND stock_detail.is_received != 0
			AND warehouses.province_id = D.province_id
		) AS CB
	FROM
		(
			SELECT
				warehouses.province_id,
				SUM(
					hf_data_master.closing_balance
				) AS CB
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
				stakeholders.geo_level_id >= 2
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.province_id
		) D
	) C 
	ON C.province_id = B.pk_id;
WHEN (in_type = 'DP') THEN
	IF(in_location_id != 0) THEN
		SELECT
			B.pk_id,
			B.province_id,
			B.location_name,
			A.AMC,
			C.field_store,
			C.district_store,
			C.field_store + C.district_store AS CB
		FROM
			(
				SELECT
					b.district_id,
					AVG(b.csum) AS AMC
				FROM
					(
						SELECT
							A.district_id,
							A.csum,
							@num :=
						IF (@loc = district_id, @num + 1, 1) AS row_number,
						@loc := district_id
					FROM
						(
							SELECT
								warehouses.district_id,
								SUM(hf_data_master.issue_balance) AS csum
							FROM
								warehouses
							INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
							INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
							INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
							WHERE
								stakeholders.geo_level_id = 6
							AND warehouses.province_id = in_location_id
							AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
							AND hf_data_master.item_pack_size_id = in_item
							GROUP BY
								warehouses.district_id,
								hf_data_master.reporting_start_date
							ORDER BY
								warehouses.district_id,
								hf_data_master.reporting_start_date DESC
						) AS A
					) AS b
				WHERE
					row_number <= 3
				GROUP BY
					district_id
			) A
		RIGHT JOIN (
			SELECT
				locations.pk_id,
				locations.location_name,
				locations.province_id
			FROM
				locations
			INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
			WHERE
				locations.geo_level_id = 4
			AND locations.parent_id = in_location_id
			ORDER BY
				locations.location_name ASC
		) B ON A.district_id = B.pk_id
	LEFT JOIN (
		SELECT
			A.pk_id,
			A.province_id,
			A.location_name,
			A.field_store,
			A.district_store,
			A.field_store + A.district_store AS CB
		FROM
			(
				SELECT
					B.pk_id,
					B.province_id,
					B.location_name,
					A.CB AS field_store,
					(
						SELECT
							IFNULL(SUM(stock_detail.quantity), 0)
						FROM
							stock_master
						INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
						INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
						INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
						INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
						INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
						INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
						WHERE
							DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= in_rpt_date
						AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
						AND stock_detail.is_received != 0
						AND warehouses.district_id = A.district_id
					) AS district_store
				FROM
					(
						SELECT
							warehouses.district_id,
							SUM(hf_data_master.closing_balance) AS CB
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						WHERE
							stakeholders.geo_level_id >= 4
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.district_id
					) A
				RIGHT JOIN (
					SELECT
						locations.pk_id,
						locations.location_name,
						locations.province_id
					FROM
						locations
					INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
					WHERE
						locations.geo_level_id = 4
					AND locations.parent_id = in_location_id
					ORDER BY
						locations.location_name ASC
				) B ON A.district_id = B.pk_id
			) A
	) C 
	ON C.pk_id = B.pk_id;
	ELSEIF(in_location_id = 0) THEN
		SELECT
			B.pk_id,
			B.province_id,
			B.location_name,
			A.AMC,
			C.field_store,
			C.district_store,
			C.field_store + C.district_store AS CB
		FROM
			(
				SELECT
					b.district_id,
					AVG(b.csum) AS AMC
				FROM
					(
						SELECT
							A.district_id,
							A.csum,
							@num :=
						IF (@loc = district_id, @num + 1, 1) AS row_number,
						@loc := district_id
					FROM
						(
							SELECT
								warehouses.district_id,
								SUM(hf_data_master.issue_balance) AS csum
							FROM
								warehouses
							INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
							INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
							INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
							WHERE
								stakeholders.geo_level_id = 6
							AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
							AND hf_data_master.item_pack_size_id = in_item
							GROUP BY
								warehouses.district_id,
								hf_data_master.reporting_start_date
							ORDER BY
								warehouses.district_id,
								hf_data_master.reporting_start_date DESC
						) AS A
					) AS b
				WHERE
					row_number <= 3
				GROUP BY
					district_id
			) A
		RIGHT JOIN (
			SELECT
				locations.pk_id,
				locations.location_name,
				locations.province_id
			FROM
				locations
			INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
			WHERE
				locations.geo_level_id = 4
			ORDER BY
				locations.province_id ASC,
				locations.location_name ASC
		) B ON A.district_id = B.pk_id
	LEFT JOIN (
		SELECT
			A.pk_id,
			A.province_id,
			A.location_name,
			A.field_store,
			A.district_store,
			A.field_store + A.district_store AS CB
		FROM
			(
				SELECT
					B.pk_id,
					B.province_id,
					B.location_name,
					A.CB AS field_store,
					(
						SELECT
							IFNULL(SUM(stock_detail.quantity), 0)
						FROM
							stock_master
						INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
						INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
						INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
						INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
						INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
						INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
						WHERE
							DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= in_rpt_date
						AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
						AND stock_detail.is_received != 0
						AND warehouses.district_id = A.district_id
					) AS district_store
				FROM
					(
						SELECT
							warehouses.district_id,
							SUM(hf_data_master.closing_balance) AS CB
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						WHERE
							stakeholders.geo_level_id >= 4
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.district_id
					) A
				RIGHT JOIN (
					SELECT
						locations.pk_id,
						locations.location_name,
						locations.province_id
					FROM
						locations
					INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
					WHERE
						locations.geo_level_id = 4
					ORDER BY
						locations.province_id ASC,
						locations.location_name ASC
				) B ON A.district_id = B.pk_id
			) A
	) C 
	ON C.pk_id = B.pk_id;
	END IF;
WHEN (in_type = 'TP') THEN
	SELECT
		B.pk_id,
		B.location_name,
		A.AMC,
		C.CB
	FROM
		(
			SELECT
				b.pk_id,
				AVG(b.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							Tehsil.pk_id,
							Tehsil.location_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN locations ON warehouses.location_id = locations.pk_id
						INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.district_id = in_location_id
						AND warehouses.stakeholder_office_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							Tehsil.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							Tehsil.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS b
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 5
		AND locations.district_id = in_location_id
		ORDER BY
			locations.location_name ASC
	) B ON A.pk_id = B.pk_id
	LEFT JOIN (
		SELECT
		D.pk_id,
		D.CB + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0)
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
			WHERE
				DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= in_rpt_date
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
			AND stock_detail.is_received != 0
			AND warehouses.location_id = D.pk_id
		) AS CB
	FROM
		(
			SELECT
				Tehsil.pk_id,
				Tehsil.location_name,
				SUM(hf_data_master.closing_balance) AS CB
			FROM
				warehouses
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			WHERE
				warehouses.district_id = in_location_id
			AND warehouses.stakeholder_office_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				Tehsil.pk_id
		) D
	) C 
	ON C.pk_id = B.pk_id;
WHEN (in_type = 'UP') THEN
	SELECT
		B.pk_id,
		B.tehsil_id,
		B.location_name,
		A.AMC,
		C.CB
	FROM
		(
			SELECT
				b.pk_id,
				AVG(b.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							locations.pk_id,
							locations.location_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN locations ON warehouses.location_id = locations.pk_id
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.district_id = in_location_id
						AND warehouses.stakeholder_office_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.location_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.location_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS b
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.parent_id AS tehsil_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 6
		AND locations.district_id = in_location_id
		ORDER BY
			locations.location_name ASC
	) B ON A.pk_id = B.pk_id
	LEFT JOIN (
		SELECT
			D.pk_id,
			D.CB + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0)
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
			WHERE
				DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= in_rpt_date
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
			AND stock_detail.is_received != 0
			AND warehouses.location_id = D.pk_id
		) AS CB
	FROM
		(
			SELECT
				locations.pk_id,
				locations.location_name,
				SUM(hf_data_master.closing_balance) AS CB
			FROM
				warehouses
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			WHERE
				warehouses.district_id = in_location_id
			AND warehouses.stakeholder_office_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.location_id
			ORDER BY
				locations.location_name ASC
		) D
	) C 
	ON C.pk_id = B.pk_id;
WHEN (in_type = 'WP') THEN
	SELECT
		B.pk_id,
		B.tehsil_id,
		B.warehouse_name AS location_name,
		A.AMC,
		C.CB
	FROM
		(
			SELECT
				b.pk_id,
				AVG(b.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							warehouses.pk_id,
							warehouses.warehouse_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.location_id = in_location_id
						AND warehouses.stakeholder_office_id = 6 
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS b
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			warehouses.pk_id,
			warehouses.warehouse_name,
			locations.parent_id AS tehsil_id
		FROM
			warehouses
		INNER JOIN locations ON warehouses.location_id = locations.pk_id
		WHERE
			warehouses.location_id = in_location_id
		ORDER BY
			warehouses.warehouse_name ASC
	) B ON A.pk_id = B.pk_id
	LEFT JOIN (
		SELECT
			D.pk_id,
			D.CB + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0)
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
			WHERE
				DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= in_rpt_date
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
			AND stock_detail.is_received != 0
			AND warehouses.pk_id = D.pk_id
		) AS CB
	FROM
		(
			SELECT
				warehouses.pk_id,
				warehouses.warehouse_name,
				SUM(hf_data_master.closing_balance) AS CB
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			WHERE
				warehouses.location_id = in_location_id
			AND warehouses.stakeholder_office_id = 6 
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.pk_id
			ORDER BY
				warehouses.warehouse_name ASC
		) D
	) C 
	ON C.pk_id = B.pk_id;
WHEN (in_type = 'P') THEN
	SELECT
		b.district_id as location_id,
		b.location_name,
		AVG(b.csum) AS AMC,
		C.CB
	FROM
		(
			SELECT
				A.reporting_start_date,
				A.csum,
				A.district_id,
				A.location_name,
				@num :=
			IF (@loc = district_id, @num + 1, 1) AS row_number,
			@loc := district_id as location_id
		FROM
			(
				SELECT
					hf_data_master.reporting_start_date,
					SUM(hf_data_master.issue_balance) AS csum,
					warehouses.district_id,
					locations.location_name
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN locations ON locations.pk_id = warehouses.district_id
        INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
				WHERE
					stakeholders.geo_level_id = 6
				AND hf_data_master.issue_balance > 0
				AND warehouses.stakeholder_id = 1
				AND issue_balance > 0
				AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.province_id = in_location_id
				GROUP BY
					warehouses.district_id,
					hf_data_master.reporting_start_date
				ORDER BY
					warehouses.district_id,
					hf_data_master.reporting_start_date DESC
			) AS A
		) AS b
		JOIN(SELECT
					hf_data_master.reporting_start_date,
					SUM(hf_data_master.closing_balance) AS CB,
					warehouses.district_id as location_id,
					locations.location_name
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN locations ON locations.pk_id = warehouses.district_id
				INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
				WHERE
					stakeholders.geo_level_id >= 4
				AND warehouses.stakeholder_id = in_stakeholder
				AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.province_id = in_location_id
				GROUP BY
					warehouses.district_id
				ORDER BY
					warehouses.district_id)C
		ON C.location_id = b.location_id
	WHERE
		row_number <= 3
	GROUP BY
		district_id;
WHEN (in_type = 'D') THEN
	SELECT
		b.location_id,
		b.location_name,
		AVG(b.csum) AS AMC,
		C.CB
	FROM
		(
			SELECT
				A.reporting_start_date,
				A.csum,
				A.location_id,
				A.location_name,
				@num :=
			IF (@loc = location_id, @num + 1, 1) AS row_number,
			@loc := location_id
		FROM
			(
				SELECT
					hf_data_master.reporting_start_date,
					SUM(hf_data_master.issue_balance) AS csum,
					warehouses.location_id,
					locations.location_name
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN locations ON locations.pk_id = warehouses.location_id
        INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id 
				WHERE
					stakeholders.geo_level_id >= 6
				AND hf_data_master.issue_balance > 0
				AND warehouses.stakeholder_id = 1
				AND issue_balance > 0
				AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.district_id = in_location_id
				GROUP BY
					warehouses.location_id,
					hf_data_master.reporting_start_date
				ORDER BY
					warehouses.location_id,
					hf_data_master.reporting_start_date DESC
			) AS A
		) AS b
		JOIN(SELECT
					hf_data_master.reporting_start_date,
					SUM(hf_data_master.closing_balance) AS CB,
					warehouses.location_id,
					locations.location_name
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN locations ON locations.pk_id = warehouses.location_id
				INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
				WHERE
					stakeholders.geo_level_id >= 6
				AND warehouses.stakeholder_id = in_stakeholder
				AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.district_id = in_location_id
				GROUP BY
					warehouses.location_id
				ORDER BY
					warehouses.location_id)C
		ON C.location_id = b.location_id
	WHERE
		row_number <= 3
	GROUP BY
		location_id;
END CASE;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for AdjustPlacements
-- ----------------------------
DROP PROCEDURE IF EXISTS `AdjustPlacements`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`115.186.139.239` PROCEDURE `AdjustPlacements`(stock_detail_id INT)
    READS SQL DATA
    DETERMINISTIC
BEGIN
	DELETE
FROM
	placements
WHERE
	placements.stock_detail_id = stock_detail_id;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for GetIllegalWastageofDistrict
-- ----------------------------
DROP PROCEDURE IF EXISTS `GetIllegalWastageofDistrict`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `GetIllegalWastageofDistrict`(in_item INT, in_rpt_date DATE, in_location_id INT, in_allowable_wastage_rate FLOAT, in_stakeholder INT)
    DETERMINISTIC
BEGIN
	SELECT
		A.district_id,
    A.location_name,
		SUM(wastagePer) AS wastage_uc,
		COUNT(pk_id) AS totalUcs,
		SUM(COALESCE (wastagePer, NULL, 0))/count(pk_id)*100 wastage_rate
	FROM
		(
			SELECT
			IF (
				ROUND(
					IFNULL(
						(
							sum(hf_data_master.wastages) / (
								SUM(
									hf_data_master.issue_balance
								) + sum(hf_data_master.wastages)
							)
						) * 100,
						0
					),
					1
				) > in_allowable_wastage_rate,
				1,
				0
			) AS wastagePer,
			UC.pk_id,
			warehouses.district_id,
      district.location_name
		FROM
			locations AS UC
    
		INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
    INNER JOIN locations as district ON warehouses.district_id = district.pk_id
		INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
		WHERE
			UC.geo_level_id = 6
		AND warehouses.stakeholder_id = in_stakeholder
		AND hf_data_master.reporting_start_date = in_rpt_date
		AND hf_data_master.issue_balance IS NOT NULL
		AND hf_data_master.item_pack_size_id = in_item
		AND warehouses.province_id = in_location_id
		GROUP BY
			UC.pk_id
		) A
	GROUP BY 
	A.district_id;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for GetIllegalWastageofDistrictHF
-- ----------------------------
DROP PROCEDURE IF EXISTS `GetIllegalWastageofDistrictHF`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `GetIllegalWastageofDistrictHF`(in_item INT, in_rpt_date DATE, in_location_id INT, in_allowable_wastage_rate FLOAT, in_stakeholder INT)
    DETERMINISTIC
BEGIN
	SELECT
		A.district_id,
    A.location_name,
		SUM(wastagePer) AS wastage_uc,
		COUNT(pk_id) AS totalUcs,
		SUM(COALESCE (wastagePer, NULL, 0))/count(pk_id)*100 wastage_rate
	FROM
		(
			SELECT
			IF (
				ROUND(
					IFNULL(
						(
							sum(hf_data_master.wastages) / (
								SUM(
									hf_data_master.issue_balance
								) + sum(hf_data_master.wastages)
							)
						) * 100,
						0
					),
					1
				) > in_allowable_wastage_rate,
				1,
				0
			) AS wastagePer,
			UC.pk_id,
			warehouses.district_id,
      district.location_name
		FROM
			locations AS UC
		INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
    INNER JOIN locations as district ON warehouses.district_id = district.pk_id 
		INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
		WHERE
			UC.geo_level_id = 6
		AND warehouses.stakeholder_id = in_stakeholder
		AND hf_data_master.reporting_start_date = in_rpt_date
		AND hf_data_master.issue_balance IS NOT NULL
		AND hf_data_master.item_pack_size_id = in_item
		AND warehouses.province_id = in_location_id
		GROUP BY
			UC.pk_id
		) A
	GROUP BY 
	A.district_id;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for PlacementSummary
-- ----------------------------
DROP PROCEDURE IF EXISTS `PlacementSummary`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `PlacementSummary`(locationid INT, batchid INT)
    READS SQL DATA
    DETERMINISTIC
BEGIN
DELETE FROM placement_summary WHERE placement_summary.placement_location_id = locationid AND placement_summary.stock_batch_warehouse_id = batchid;
INSERT INTO placement_summary (
	placement_summary.item_name,
	placement_summary.batch_number,
	placement_summary.stock_batch_warehouse_id,
	placement_summary.placement_location_id,
	placement_summary.vvm_stage,
	placement_summary.quantity,
	placement_summary.cartons
) SELECT
	item_pack_sizes.item_name,
	stock_batch.number,
	placements.stock_batch_warehouse_id,
	placements.placement_location_id,
	placements.vvm_stage,
	Sum(placements.quantity) AS qty,
	ROUND(Sum(placements.quantity) / pack_info.quantity_per_pack) as cartons
FROM
	placements
INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
WHERE
	placements.placement_location_id = locationid
AND placements.stock_batch_warehouse_id = batchid
AND pack_info.packaging_level = 140
GROUP BY
	placements.vvm_stage,
	placements.stock_batch_warehouse_id,
	placements.placement_location_id
HAVING
	qty > 0;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPAdjustQty
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPAdjustQty`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPAdjustQty`(batchId INT)
    READS SQL DATA
    DETERMINISTIC
BEGIN
DECLARE	wh_id INT;
DECLARE temp DOUBLE;
SELECT
	warehouse_id INTO wh_id
FROM
	stock_batch_warehouses
WHERE
	stock_batch_warehouses.pk_id = batchId LIMIT 1;
SET temp = AdjustQty(batchId,wh_id);
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPgetAMC
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPgetAMC`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPgetAMC`(in_type char(5), in_rpt_date DATE, in_location_id INT, in_item INT, in_stakeholder INT)
    DETERMINISTIC
BEGIN
SET @num := 0;
SET @loc := 0;
CASE
WHEN (in_type = 'N') THEN
	SELECT
		B.pk_id,
		B.item_name,
		A.AMC
	FROM
		(
			SELECT
				B.pk_id,
				B.item_name,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.item_name,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							item_pack_sizes.pk_id,
							item_pack_sizes.item_name,
							SUM(
								hf_data_master.issue_balance
							) AS csum
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
						WHERE
							stakeholders.geo_level_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						GROUP BY
							item_pack_sizes.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							item_pack_sizes.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			item_pack_sizes.pk_id,
			item_pack_sizes.item_name
		FROM
			item_pack_sizes
		ORDER BY
			item_pack_sizes.list_rank
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'NP') THEN
		SELECT
			B.item_pack_size_id,
			AVG(B.csum) AS AMC
		FROM
			(
				SELECT
					A.item_pack_size_id,
					A.csum,
					@num :=
				IF (@loc = item_pack_size_id, @num + 1, 1) AS row_number,
				@loc := item_pack_size_id
			FROM
				(
					SELECT
						hf_data_master.item_pack_size_id,
						SUM(
							hf_data_master.issue_balance
						) AS csum
					FROM
						warehouses
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
					INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
					WHERE
						stakeholders.geo_level_id = 6
					AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
					AND hf_data_master.item_pack_size_id = in_item
					GROUP BY
						hf_data_master.item_pack_size_id,
						hf_data_master.reporting_start_date
					ORDER BY
						hf_data_master.item_pack_size_id,
						hf_data_master.reporting_start_date DESC
				) AS A
			) AS B
		WHERE
			row_number <= 3
		GROUP BY
			item_pack_size_id;
WHEN (in_type = 'PP') THEN
	SELECT
		B.pk_id,
		B.location_name,
		A.AMC
	FROM
		(
			SELECT
				B.province_id,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.province_id,
						A.csum,
						@num :=
					IF (@loc = province_id, @num + 1, 1) AS row_number,
					@loc := province_id
				FROM
					(
						SELECT
							warehouses.province_id,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
						WHERE
							stakeholders.geo_level_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.province_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.province_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				province_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 2
		ORDER BY
			locations.pk_id ASC
	) B ON A.province_id = B.pk_id;
WHEN (in_type = 'DP') THEN
	IF(in_location_id != 0) THEN
		SELECT
			B.province_id,
			B.province_name,
			B.pk_id AS district_id,
			B.location_name AS district_name,
			A.AMC
		FROM
			(
				SELECT
					B.district_id,
					AVG(B.csum) AS AMC
				FROM
					(
						SELECT
							A.district_id,
							A.csum,
							@num :=
						IF (@loc = district_id, @num + 1, 1) AS row_number,
						@loc := district_id
					FROM
						(
							SELECT
								warehouses.district_id,
								SUM(hf_data_master.issue_balance) AS csum
							FROM
								warehouses
							INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
							INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
							INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
							WHERE
								stakeholders.geo_level_id = 6
							AND warehouses.province_id = in_location_id
							AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
							AND hf_data_master.item_pack_size_id = in_item
							GROUP BY
								warehouses.district_id,
								hf_data_master.reporting_start_date
							ORDER BY
								warehouses.district_id,
								hf_data_master.reporting_start_date DESC
						) AS A
					) AS B
				WHERE
					row_number <= 3
				GROUP BY
					district_id
			) A
		RIGHT JOIN (
			SELECT
				locations.pk_id,
				locations.location_name,
				locations.province_id,
				Province.location_name AS province_name
			FROM
				locations
			INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
			INNER JOIN locations AS Province ON locations.province_id = Province.pk_id
			WHERE
				locations.geo_level_id = 4
			AND locations.parent_id = in_location_id
			ORDER BY
				locations.location_name ASC
		) B ON A.district_id = B.pk_id;
	ELSEIF(in_location_id = 0) THEN
		SELECT
			B.pk_id,
			B.province_id,
			B.location_name,
			A.AMC
		FROM
			(
				SELECT
					B.district_id,
					AVG(B.csum) AS AMC
				FROM
					(
						SELECT
							A.district_id,
							A.csum,
							@num :=
						IF (@loc = district_id, @num + 1, 1) AS row_number,
						@loc := district_id
					FROM
						(
							SELECT
								warehouses.district_id,
								SUM(hf_data_master.issue_balance) AS csum
							FROM
								warehouses
							INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
							INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
							INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
							WHERE
								stakeholders.geo_level_id = 6
							AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
							AND hf_data_master.item_pack_size_id = in_item
							GROUP BY
								warehouses.district_id,
								hf_data_master.reporting_start_date
							ORDER BY
								warehouses.district_id,
								hf_data_master.reporting_start_date DESC
						) AS A
					) AS B
				WHERE
					row_number <= 3
				GROUP BY
					district_id
			) A
		RIGHT JOIN (
			SELECT
				locations.pk_id,
				locations.location_name,
				locations.province_id
			FROM
				locations
			INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
			WHERE
				locations.geo_level_id = 4
			ORDER BY
				locations.province_id ASC,
				locations.location_name ASC
		) B ON A.district_id = B.pk_id;
	END IF;
WHEN (in_type = 'TP') THEN
	SELECT
		B.district_id,
		B.district_name,
		B.pk_id AS tehsil_id,
		B.tehsil_name,
		A.AMC
	FROM
		(
			SELECT
				B.pk_id,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							Tehsil.pk_id,
							Tehsil.location_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN locations ON warehouses.location_id = locations.pk_id
						INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.district_id = in_location_id
						AND warehouses.stakeholder_office_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							Tehsil.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							Tehsil.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			District.pk_id AS district_id,
			District.location_name AS district_name,
			locations.pk_id,
			locations.location_name AS tehsil_name
		FROM
			locations
		INNER JOIN locations AS District ON locations.district_id = District.pk_id
		WHERE
			locations.geo_level_id = 5
		AND locations.district_id = in_location_id
		ORDER BY
			district_name ASC,
			tehsil_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'PTP') THEN
	SELECT
		B.district_id,
		B.district_name,
		B.pk_id AS tehsil_id,
		B.tehsil_name,
		A.AMC
	FROM
		(
			SELECT
				B.pk_id,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							Tehsil.pk_id,
							Tehsil.location_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN locations ON warehouses.location_id = locations.pk_id
						INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.province_id = in_location_id
						AND warehouses.stakeholder_office_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							Tehsil.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							Tehsil.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			District.pk_id AS district_id,
			District.location_name AS district_name,
			locations.pk_id,
			locations.location_name AS tehsil_name
		FROM
			locations
		INNER JOIN locations AS District ON locations.district_id = District.pk_id
		WHERE
			locations.geo_level_id = 5
		AND locations.province_id = in_location_id
		ORDER BY
			district_name ASC,
			tehsil_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'UP') THEN
	SELECT
		B.pk_id,
		B.tehsil_id,
		B.location_name,
		A.AMC
	FROM
		(
			SELECT
				B.pk_id,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							locations.pk_id,
							locations.location_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN locations ON warehouses.location_id = locations.pk_id
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.district_id = in_location_id
						AND warehouses.stakeholder_office_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.location_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.location_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.parent_id AS tehsil_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 6
		AND locations.district_id = in_location_id
		ORDER BY
			locations.location_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'WP') THEN
	SELECT
		B.pk_id,
		B.tehsil_id,
		B.warehouse_name AS location_name,
		A.AMC
	FROM
		(
			SELECT
				B.pk_id,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							warehouses.pk_id,
							warehouses.warehouse_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.location_id = in_location_id
						AND warehouses.stakeholder_office_id = 6 
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			warehouses.pk_id,
			warehouses.warehouse_name,
			locations.parent_id AS tehsil_id
		FROM
			warehouses
		INNER JOIN locations ON warehouses.location_id = locations.pk_id
		WHERE
			warehouses.location_id = in_location_id
		ORDER BY
			warehouses.warehouse_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'P') THEN
	SELECT
		B.district_id as location_id,
		B.location_name,
		AVG(B.csum) AS AMC
	FROM
		(
			SELECT
				A.reporting_start_date,
				A.csum,
				A.district_id,
				A.location_name,
				@num :=
			IF (@loc = district_id, @num + 1, 1) AS row_number,
			@loc := district_id as location_id
		FROM
			(
				SELECT
					hf_data_master.reporting_start_date,
					SUM(hf_data_master.issue_balance) AS csum,
					warehouses.district_id,
					locations.location_name
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN locations ON locations.pk_id = warehouses.district_id
        INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
				WHERE
					stakeholders.geo_level_id = 6
				AND hf_data_master.issue_balance > 0
				AND warehouses.stakeholder_id = 1
				AND issue_balance > 0
				AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.province_id = in_location_id
				GROUP BY
					warehouses.district_id,
					hf_data_master.reporting_start_date
				ORDER BY
					warehouses.district_id,
					hf_data_master.reporting_start_date DESC
			) AS A
		) AS B
	WHERE
		row_number <= 3
	GROUP BY
		district_id;
WHEN (in_type = 'D') THEN
	SELECT
		B.location_id,
		B.location_name,
		AVG(B.csum) AS AMC
	FROM
		(
			SELECT
				A.reporting_start_date,
				A.csum,
				A.location_id,
				A.location_name,
				@num :=
			IF (@loc = location_id, @num + 1, 1) AS row_number,
			@loc := location_id
		FROM
			(
				SELECT
					hf_data_master.reporting_start_date,
					SUM(hf_data_master.issue_balance) AS csum,
					warehouses.location_id,
					locations.location_name
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN locations ON locations.pk_id = warehouses.location_id
        INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id 
				WHERE
					stakeholders.geo_level_id >= 6
				AND hf_data_master.issue_balance > 0
				AND warehouses.stakeholder_id = 1
				AND issue_balance > 0
				AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.district_id = in_location_id
				GROUP BY
					warehouses.location_id,
					hf_data_master.reporting_start_date
				ORDER BY
					warehouses.location_id,
					hf_data_master.reporting_start_date DESC
			) AS A
		) AS B
	WHERE
		row_number <= 3
	GROUP BY
		location_id;
END CASE;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPgetAMC_copy
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPgetAMC_copy`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPgetAMC_copy`(in_type char(2), in_rpt_date DATE, in_location_id INT, in_item INT, in_stakeholder INT)
    DETERMINISTIC
BEGIN
SET @num := 0;
SET @loc := 0;
CASE
WHEN (in_type = 'N') THEN
	SELECT
		B.pk_id,
		B.item_name,
		A.AMC
	FROM
		(
			SELECT
				B.pk_id,
				B.item_name,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.item_name,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							item_pack_sizes.pk_id,
							item_pack_sizes.item_name,
							SUM(
								hf_data_master.issue_balance
							) AS csum
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
						WHERE
							stakeholders.geo_level_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						GROUP BY
							item_pack_sizes.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							item_pack_sizes.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			item_pack_sizes.pk_id,
			item_pack_sizes.item_name
		FROM
			item_pack_sizes
		ORDER BY
			item_pack_sizes.list_rank
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'NP') THEN
		SELECT
			b.item_pack_size_id,
			AVG(b.csum) AS AMC
		FROM
			(
				SELECT
					A.item_pack_size_id,
					A.csum,
					@num :=
				IF (@loc = item_pack_size_id, @num + 1, 1) AS row_number,
				@loc := item_pack_size_id
			FROM
				(
					SELECT
						hf_data_master.item_pack_size_id,
						SUM(
							hf_data_master.issue_balance
						) AS csum
					FROM
						warehouses
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
					INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
					WHERE
						stakeholders.geo_level_id = 6
					AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
					AND hf_data_master.item_pack_size_id = in_item
					GROUP BY
						hf_data_master.item_pack_size_id,
						hf_data_master.reporting_start_date
					ORDER BY
						hf_data_master.item_pack_size_id,
						hf_data_master.reporting_start_date DESC
				) AS A
			) AS b
		WHERE
			row_number <= 3
		GROUP BY
			item_pack_size_id;
WHEN (in_type = 'PP') THEN
	SELECT
		B.pk_id,
		B.location_name,
		A.AMC
	FROM
		(
			SELECT
				b.province_id,
				AVG(b.csum) AS AMC
			FROM
				(
					SELECT
						A.province_id,
						A.csum,
						@num :=
					IF (@loc = province_id, @num + 1, 1) AS row_number,
					@loc := province_id
				FROM
					(
						SELECT
							warehouses.province_id,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
						WHERE
							stakeholders.geo_level_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.province_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.province_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS b
			WHERE
				row_number <= 3
			GROUP BY
				province_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 2
		ORDER BY
			locations.pk_id ASC
	) B ON A.province_id = B.pk_id;
WHEN (in_type = 'DP') THEN
	IF(in_location_id != 0) THEN
		SELECT
			B.pk_id,
			B.province_id,
			B.location_name,
			A.AMC
		FROM
			(
				SELECT
					b.district_id,
					AVG(b.csum) AS AMC
				FROM
					(
						SELECT
							A.district_id,
							A.csum,
							@num :=
						IF (@loc = district_id, @num + 1, 1) AS row_number,
						@loc := district_id
					FROM
						(
							SELECT
								warehouses.district_id,
								SUM(hf_data_master.issue_balance) AS csum
							FROM
								warehouses
							INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
							INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
							INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
							WHERE
								stakeholders.geo_level_id = 6
							AND warehouses.province_id = in_location_id
							AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
							AND hf_data_master.item_pack_size_id = in_item
							GROUP BY
								warehouses.district_id,
								hf_data_master.reporting_start_date
							ORDER BY
								warehouses.district_id,
								hf_data_master.reporting_start_date DESC
						) AS A
					) AS b
				WHERE
					row_number <= 3
				GROUP BY
					district_id
			) A
		RIGHT JOIN (
			SELECT
				locations.pk_id,
				locations.location_name,
				locations.province_id
			FROM
				locations
			INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
			WHERE
				locations.geo_level_id = 4
			AND locations.parent_id = in_location_id
			ORDER BY
				locations.location_name ASC
		) B ON A.district_id = B.pk_id;
	ELSEIF(in_location_id = 0) THEN
		SELECT
			B.pk_id,
			B.province_id,
			B.location_name,
			A.AMC
		FROM
			(
				SELECT
					b.district_id,
					AVG(b.csum) AS AMC
				FROM
					(
						SELECT
							A.district_id,
							A.csum,
							@num :=
						IF (@loc = district_id, @num + 1, 1) AS row_number,
						@loc := district_id
					FROM
						(
							SELECT
								warehouses.district_id,
								SUM(hf_data_master.issue_balance) AS csum
							FROM
								warehouses
							INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
							INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
							INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
							WHERE
								stakeholders.geo_level_id = 6
							AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
							AND hf_data_master.item_pack_size_id = in_item
							GROUP BY
								warehouses.district_id,
								hf_data_master.reporting_start_date
							ORDER BY
								warehouses.district_id,
								hf_data_master.reporting_start_date DESC
						) AS A
					) AS b
				WHERE
					row_number <= 3
				GROUP BY
					district_id
			) A
		RIGHT JOIN (
			SELECT
				locations.pk_id,
				locations.location_name,
				locations.province_id
			FROM
				locations
			INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
			WHERE
				locations.geo_level_id = 4
			ORDER BY
				locations.province_id ASC,
				locations.location_name ASC
		) B ON A.district_id = B.pk_id;
	END IF;
WHEN (in_type = 'TP') THEN
	SELECT
		B.pk_id,
		B.location_name,
		A.AMC
	FROM
		(
			SELECT
				b.pk_id,
				AVG(b.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							Tehsil.pk_id,
							Tehsil.location_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN locations ON warehouses.location_id = locations.pk_id
						INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.district_id = in_location_id
						AND warehouses.stakeholder_office_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							Tehsil.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							Tehsil.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS b
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 5
		AND locations.district_id = in_location_id
		ORDER BY
			locations.location_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'UP') THEN
	SELECT
		B.pk_id,
		B.tehsil_id,
		B.location_name,
		A.AMC
	FROM
		(
			SELECT
				b.pk_id,
				AVG(b.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							locations.pk_id,
							locations.location_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN locations ON warehouses.location_id = locations.pk_id
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.district_id = in_location_id
						AND warehouses.stakeholder_office_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.location_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.location_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS b
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.parent_id AS tehsil_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 6
		AND locations.district_id = in_location_id
		ORDER BY
			locations.location_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'WP') THEN
	SELECT
		B.pk_id,
		B.tehsil_id,
		B.warehouse_name AS location_name,
		A.AMC
	FROM
		(
			SELECT
				b.pk_id,
				AVG(b.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							warehouses.pk_id,
							warehouses.warehouse_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.location_id = in_location_id
						AND warehouses.stakeholder_office_id = 6 
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS b
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			warehouses.pk_id,
			warehouses.warehouse_name,
			locations.parent_id AS tehsil_id
		FROM
			warehouses
		INNER JOIN locations ON warehouses.location_id = locations.pk_id
		WHERE
			warehouses.location_id = in_location_id
		ORDER BY
			warehouses.warehouse_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'P') THEN
	SELECT
		b.district_id as location_id,
		b.location_name,
		AVG(b.csum) AS AMC
	FROM
		(
			SELECT
				A.reporting_start_date,
				A.csum,
				A.district_id,
				A.location_name,
				@num :=
			IF (@loc = district_id, @num + 1, 1) AS row_number,
			@loc := district_id as location_id
		FROM
			(
				SELECT
					hf_data_master.reporting_start_date,
					SUM(hf_data_master.issue_balance) AS csum,
					warehouses.district_id,
					locations.location_name
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN locations ON locations.pk_id = warehouses.district_id
        INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
				WHERE
					stakeholders.geo_level_id = 6
				AND hf_data_master.issue_balance > 0
				AND warehouses.stakeholder_id = 1
				AND issue_balance > 0
				AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.province_id = in_location_id
				GROUP BY
					warehouses.district_id,
					hf_data_master.reporting_start_date
				ORDER BY
					warehouses.district_id,
					hf_data_master.reporting_start_date DESC
			) AS A
		) AS b
	WHERE
		row_number <= 3
	GROUP BY
		district_id;
WHEN (in_type = 'D') THEN
	SELECT
		b.location_id,
		b.location_name,
		AVG(b.csum) AS AMC
	FROM
		(
			SELECT
				A.reporting_start_date,
				A.csum,
				A.location_id,
				A.location_name,
				@num :=
			IF (@loc = location_id, @num + 1, 1) AS row_number,
			@loc := location_id
		FROM
			(
				SELECT
					hf_data_master.reporting_start_date,
					SUM(hf_data_master.issue_balance) AS csum,
					warehouses.location_id,
					locations.location_name
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN locations ON locations.pk_id = warehouses.location_id
        INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id 
				WHERE
					stakeholders.geo_level_id >= 6
				AND hf_data_master.issue_balance > 0
				AND warehouses.stakeholder_id = 1
				AND issue_balance > 0
				AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.district_id = in_location_id
				GROUP BY
					warehouses.location_id,
					hf_data_master.reporting_start_date
				ORDER BY
					warehouses.location_id,
					hf_data_master.reporting_start_date DESC
			) AS A
		) AS b
	WHERE
		row_number <= 3
	GROUP BY
		location_id;
END CASE;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPgetAMCHF
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPgetAMCHF`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPgetAMCHF`(in_type char(2), in_rpt_date DATE, in_location_id INT, in_item INT, in_stakeholder INT)
    DETERMINISTIC
BEGIN
SET @num := 0;
SET @loc := 0;
CASE
WHEN (in_type = 'P') THEN
	SELECT
		AVG(b.csum) AS AMC,
		b.district_id as location_id,
		b.location_name
	FROM
		(
			SELECT
				A.Reporting_start_date,
				A.csum,
				A.district_id,
				A.location_name,
				@num :=
			IF (@loc = district_id, @num + 1, 1) AS row_number,
			@loc := district_id as location_id
		FROM
			(
				SELECT
					Reporting_start_date,
					SUM(hf_data_master.issue_balance) AS csum,
					warehouses.district_id,
					locations.location_name
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN locations ON locations.pk_id = warehouses.district_id
        INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
				WHERE
					stakeholders.geo_level_id = 6
				AND hf_data_master.issue_balance > 0
				AND warehouses.stakeholder_id = 1
				AND issue_balance > 0
				AND Reporting_start_date <= in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.province_id = in_location_id
				GROUP BY
					warehouses.district_id,
					Reporting_start_date
				ORDER BY
					warehouses.district_id,
					Reporting_start_date DESC
			) AS A
		) AS b
	WHERE
		row_number <= 3
	GROUP BY
		district_id;
WHEN (in_type = 'D') THEN
	SELECT
		AVG(b.csum) AS AMC,
		b.location_id,
		b.location_name
	FROM
		(
			SELECT
				A.Reporting_start_date,
				A.csum,
				A.location_id,
				A.location_name,
				@num :=
			IF (@loc = location_id, @num + 1, 1) AS row_number,
			@loc := location_id
		FROM
			(
				SELECT
					Reporting_start_date,
					SUM(hf_data_master.issue_balance) AS csum,
					warehouses.location_id,
					locations.location_name
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN locations ON locations.pk_id = warehouses.location_id
        INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id  
				WHERE
					stakeholders.geo_level_id = 6
				AND hf_data_master.issue_balance > 0
				AND warehouses.stakeholder_id = 1
				AND issue_balance > 0
				AND Reporting_start_date <= in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.district_id = in_location_id
				GROUP BY
					warehouses.location_id,
					Reporting_start_date
				ORDER BY
					warehouses.location_id,
					Reporting_start_date DESC
			) AS A
		) AS b
	WHERE
		row_number <= 3
	GROUP BY
		location_id;
END CASE;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPgetCB
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPgetCB`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPgetCB`(in_type char(5), in_rpt_date DATE, in_location_id INT, in_item INT, in_stakeholder INT)
    DETERMINISTIC
BEGIN
CASE
WHEN (in_type = 'N') THEN
	SELECT
		B.pk_id,
		B.item_name,
		A.CB + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
      INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
      INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
			WHERE
				DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
			AND stakeholder_item_pack_sizes.item_pack_size_id = A.pk_id
			 
		) AS CB
	FROM
		(
			SELECT
				item_pack_sizes.pk_id,
				item_pack_sizes.item_name,
				SUM(hf_data_master.closing_balance) AS CB
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
			WHERE
				DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND stakeholders.geo_level_id >= 1
			GROUP BY
				item_pack_sizes.pk_id
		) A
	RIGHT JOIN (
		SELECT
			item_pack_sizes.pk_id,
			item_pack_sizes.item_name
		FROM
			item_pack_sizes
		ORDER BY
			item_pack_sizes.list_rank
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'NP') THEN
	SELECT
		hf_data_master.item_pack_size_id,
		SUM(hf_data_master.closing_balance) + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
      INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
      INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id 
			WHERE
				DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
			AND stakeholder_item_pack_sizes.item_pack_size_id = hf_data_master.item_pack_size_id
			 
		) AS CB
	FROM
		warehouses
	INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
	INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
	WHERE
		DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
	AND hf_data_master.item_pack_size_id = in_item
	AND stakeholders.geo_level_id = 6
	GROUP BY
		hf_data_master.item_pack_size_id;
WHEN (in_type = 'PP') THEN
	SELECT
		B.pk_id,
		B.location_name,
		A.CB + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
      INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
      INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
			WHERE
				DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
			AND warehouses.stakeholder_office_id IN (2,3,4,5) 
			AND warehouses.province_id = A.province_id
		) AS CB
	FROM
		(
			SELECT
				warehouses.province_id,
				SUM(
					hf_data_master.closing_balance
				) AS CB
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
				stakeholders.geo_level_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.province_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 2
		ORDER BY
			locations.pk_id ASC
	) B ON A.province_id = B.pk_id;
WHEN (in_type = 'DP') THEN
	IF(in_location_id != 0) THEN
		SELECT
			A.province_id,
			A.province_name,
			A.pk_id AS district_id,
			A.location_name AS district_name,
			A.field_store,
			A.district_store,
			A.field_store + A.district_store AS CB
		FROM
			(
				SELECT
					B.province_id,
					B.province_name,
					B.pk_id,
					B.location_name,
					A.CB AS field_store,
					(
						SELECT
							IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
						FROM
							stock_master
						INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
						INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
						INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
						INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
						INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
						INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
						WHERE
							DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
						AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
						 
						AND warehouses.district_id = A.district_id
					) AS district_store
				FROM
					(
						SELECT
							warehouses.district_id,
							SUM(hf_data_master.closing_balance) AS CB
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						WHERE
							stakeholders.geo_level_id >= 4
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.district_id
					) A
				RIGHT JOIN (
					SELECT
						locations.pk_id,
						locations.location_name,
						locations.province_id,
						Province.location_name AS province_name
					FROM
						locations
					INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
					INNER JOIN locations AS Province ON locations.province_id = Province.pk_id
					WHERE
						locations.geo_level_id = 4
					AND locations.parent_id = in_location_id
					ORDER BY
						locations.location_name ASC
				) B ON A.district_id = B.pk_id
			) A;
	ELSEIF(in_location_id = 0) THEN
		SELECT
			A.province_id,
			A.province_name,
			A.pk_id AS district_id,
			A.location_name AS district_name,
			A.field_store,
			A.district_store,
			A.field_store + A.district_store AS CB
		FROM
			(
				SELECT
					B.pk_id,
					B.province_id,
					B.location_name,
					B.province_name,
					A.CB AS field_store,
					(
						SELECT
							IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
						FROM
							stock_master
						INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
						INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
						INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
						INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
						INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
						INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
						WHERE
							DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
						AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
						 
						AND warehouses.district_id = A.district_id
					) AS district_store
				FROM
					(
						SELECT
							warehouses.district_id,
							SUM(
								hf_data_master.closing_balance
							) AS CB
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						WHERE
							stakeholders.geo_level_id >= 4
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.district_id
					) A
				RIGHT JOIN (
					SELECT
						locations.pk_id,
						locations.location_name,
						locations.province_id,
						Province.location_name AS province_name
					FROM
						locations
					INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
					INNER JOIN locations AS Province ON locations.province_id = Province.pk_id
					WHERE
						locations.geo_level_id = 4
					ORDER BY
						locations.province_id ASC,
						locations.location_name ASC
				) B ON A.district_id = B.pk_id
			) A;
	END IF;
WHEN (in_type = 'DPT') THEN
	IF(in_location_id != 0) THEN
		SELECT
			A.province_id,
			A.province_name,
			A.pk_id AS district_id,
			A.location_name AS district_name,
			A.field_store,
			A.district_store,
      A.tehsil_store, 
			A.field_store + A.district_store + A.tehsil_store AS CB
		FROM
			(
				SELECT
					B.province_id,
					B.province_name,
					B.pk_id,
					B.location_name,
					A.CB AS field_store,
					IFNULL((
						SELECT
							ABS(SUM(stock_detail.quantity)) * item_pack_sizes.number_of_doses
						FROM
							stock_master
						INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
						INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
						INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
						INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
						INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
						INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
						WHERE
							DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
						AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
            AND warehouses.stakeholder_office_id = 4
            
            AND warehouses.district_id = A.district_id
					),0) AS district_store,
IFNULL((
						SELECT
							ABS(SUM(stock_detail.quantity)) * item_pack_sizes.number_of_doses
						FROM
							stock_master
						INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
						INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
						INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
						INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
						INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
						INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
						WHERE
							DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
						AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
            AND warehouses.stakeholder_office_id = 5
            
            AND warehouses.district_id = A.district_id
					),0) AS tehsil_store
				FROM
					(
						SELECT
							warehouses.district_id,
							IFNULL(SUM(hf_data_master.closing_balance),0) AS CB
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						WHERE
             	stakeholders.geo_level_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.district_id
					) A
				RIGHT JOIN (
					SELECT
						locations.pk_id,
						locations.location_name,
						locations.province_id,
						Province.location_name AS province_name
					FROM
						locations
					INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
					INNER JOIN locations AS Province ON locations.province_id = Province.pk_id
					WHERE
						locations.geo_level_id = 4
					AND locations.parent_id = in_location_id
					ORDER BY
						locations.location_name ASC
				) B ON A.district_id = B.pk_id
			) A;
	ELSEIF(in_location_id = 0) THEN
		SELECT
			A.province_id,
			A.province_name,
			A.pk_id AS district_id,
			A.location_name AS district_name,
			A.field_store,
			A.district_store,
      A.tehsil_store, 
			A.field_store + A.district_store + A.tehsil_store AS CB
		FROM
			(
				SELECT
					B.pk_id,
					B.province_id,
					B.location_name,
					B.province_name,
					
					IFNULL((
						SELECT
							ABS(SUM(stock_detail.quantity)) * item_pack_sizes.number_of_doses
						FROM
							stock_master
						INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
						INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
						INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
						INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
						INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
						INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
						WHERE
							DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
						AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
						 AND warehouses.stakeholder_office_id = 4
						AND warehouses.district_id = B.pk_id
					),0)  AS district_store,
	IFNULL((
						SELECT
							ABS(SUM(stock_detail.quantity)) * item_pack_sizes.number_of_doses
						FROM
							stock_master
						INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
						INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
						INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
						INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
						INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
						INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
						WHERE
							DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
						AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
						 AND warehouses.stakeholder_office_id = 5
						AND warehouses.district_id = B.pk_id
					),0)  AS tehsil_store
				,
					(
						SELECT							
							IFNULL(SUM(
								hf_data_master.closing_balance
							),0) AS CB
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						WHERE
             	stakeholders.geo_level_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						AND warehouses.district_id = B.pk_id
					) as field_store
				FROM(
					SELECT
						locations.pk_id,
						locations.location_name,
						locations.province_id,
						Province.location_name AS province_name
					FROM
						locations
					INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
					INNER JOIN locations AS Province ON locations.province_id = Province.pk_id
					WHERE
						locations.geo_level_id = 4
					ORDER BY
						locations.province_id ASC,
						locations.location_name ASC
				) B 
			) A;
	END IF;
WHEN (in_type = 'TP') THEN
	SELECT
		B.district_id,
		B.district_name,
		B.pk_id AS tehsil_id,
		B.tehsil_name,
		A.CB + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
      INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
      INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id 
			WHERE
				DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
			 
			AND warehouses.location_id = A.pk_id
		) AS CB
	FROM
		(
			SELECT
				Tehsil.pk_id,
				Tehsil.location_name,
				SUM(hf_data_master.closing_balance) AS CB
			FROM
				warehouses
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			WHERE
				warehouses.district_id = in_location_id
			AND warehouses.stakeholder_office_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				Tehsil.pk_id
		) A
	RIGHT JOIN (
		SELECT
			District.pk_id AS district_id,
			District.location_name AS district_name,
			locations.pk_id,
			locations.location_name AS tehsil_name
		FROM
			locations
		INNER JOIN locations AS District ON locations.district_id = District.pk_id
		WHERE
			locations.geo_level_id = 5
		AND locations.district_id = in_location_id
		ORDER BY
			district_name ASC,
			tehsil_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'PTP') THEN
	SELECT
		B.district_id,
		B.district_name,
		B.pk_id AS tehsil_id,
		B.tehsil_name,
		A.CB + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
      INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
      INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
			WHERE
				DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
			 
			AND warehouses.location_id = A.pk_id
		) AS CB
	FROM
		(
			SELECT
				Tehsil.pk_id,
				Tehsil.location_name,
				SUM(hf_data_master.closing_balance) AS CB
			FROM
				warehouses
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			WHERE
				warehouses.province_id = in_location_id
			AND warehouses.stakeholder_office_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				Tehsil.pk_id
		) A
	RIGHT JOIN (
		SELECT
			District.pk_id AS district_id,
			District.location_name AS district_name,
			locations.pk_id,
			locations.location_name AS tehsil_name
		FROM
			locations
		INNER JOIN locations AS District ON locations.district_id = District.pk_id
		WHERE
			locations.geo_level_id = 5
		AND locations.province_id = in_location_id
		ORDER BY
			district_name ASC,
			tehsil_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'UP') THEN
	SELECT
		B.pk_id,
		B.tehsil_id,
		B.location_name,
		A.CB + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
      INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
      INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
			WHERE
				DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
			 
			AND warehouses.location_id = A.pk_id
		) AS CB
	FROM
		(
			SELECT
				locations.pk_id,
				locations.location_name,
				SUM(hf_data_master.closing_balance) AS CB
			FROM
				warehouses
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			WHERE
				warehouses.district_id = in_location_id
			AND warehouses.stakeholder_office_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.location_id
			ORDER BY
				locations.location_name ASC
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.parent_id AS tehsil_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 6
		AND locations.district_id = in_location_id
		ORDER BY
			locations.location_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'WP') THEN
	SELECT
		B.pk_id,
		B.tehsil_id,
		B.warehouse_name AS location_name,
		A.CB + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
      INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
      INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
			WHERE
				DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
			 
			AND warehouses.pk_id = A.pk_id
		) AS CB
	FROM
		(
			SELECT
				warehouses.pk_id,
				warehouses.warehouse_name,
				SUM(hf_data_master.closing_balance) AS CB
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			WHERE
				warehouses.location_id = in_location_id
			AND warehouses.stakeholder_office_id = 6 
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.pk_id
			ORDER BY
				warehouses.warehouse_name ASC
		) A
	RIGHT JOIN (
		SELECT
			warehouses.pk_id,
			warehouses.warehouse_name,
			locations.parent_id AS tehsil_id
		FROM
			warehouses
		INNER JOIN locations ON warehouses.location_id = locations.pk_id
		WHERE
			warehouses.location_id = in_location_id
		ORDER BY
			warehouses.warehouse_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'P') THEN
	SELECT
		warehouses.district_id as location_id,
		locations.location_name,		
		hf_data_master.reporting_start_date,
		SUM(hf_data_master.closing_balance) AS CB
	FROM
		warehouses
	INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
	INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
  INNER JOIN locations ON locations.pk_id = warehouses.district_id
  INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
	WHERE
		stakeholders.geo_level_id >= 4
	AND warehouses.stakeholder_id = in_stakeholder
	AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
	AND hf_data_master.item_pack_size_id = in_item
	AND warehouses.province_id = in_location_id
	GROUP BY
		warehouses.district_id
	ORDER BY
		warehouses.district_id;
WHEN (in_type = 'D') THEN
	SELECT
		warehouses.location_id,
		locations.location_name,
		hf_data_master.reporting_start_date,
		SUM(hf_data_master.closing_balance) AS CB
	FROM
		warehouses
	INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
	INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
INNER JOIN locations ON locations.pk_id = warehouses.location_id
INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
	WHERE 
		stakeholders.geo_level_id >= 6
	AND warehouses.stakeholder_id = in_stakeholder
	AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
	AND hf_data_master.item_pack_size_id = in_item
	AND warehouses.district_id = in_location_id
	GROUP BY
		warehouses.location_id
	ORDER BY
		warehouses.location_id;
END CASE;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPgetCBHF
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPgetCBHF`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPgetCBHF`(in_type char(2), in_rpt_date DATE, in_location_id INT, in_item INT, in_stakeholder INT)
    DETERMINISTIC
BEGIN
CASE
WHEN (in_type = 'P') THEN
	SELECT
	A.province_id,
	A.province_name,
	A.pk_id AS location_id,
	A.location_name,
	A.field_store,
	A.district_store,
	A.tehsil_store,
	A.field_store + A.district_store + A.tehsil_store AS CB
  
FROM
	(
		SELECT
			B.province_id,
			B.province_name,
			B.pk_id,
			B.location_name,
			A.CB AS field_store,
			(
				SELECT
					ABS(SUM(stock_detail.quantity)) * item_pack_sizes.number_of_doses
				FROM
					stock_master
				INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
				INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
				INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
				INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
				INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
				INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
				INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
				INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
				INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
				WHERE
					DATE_FORMAT(
						stock_master.transaction_date,
						'%Y-%m'
					) <= DATE_FORMAT(in_rpt_date, '%Y-%m')
				AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
				AND warehouses.stakeholder_office_id = 4
				AND warehouses.district_id = A.district_id
			) AS district_store,
			(
				SELECT
					ABS(SUM(stock_detail.quantity)) * item_pack_sizes.number_of_doses
				FROM
					stock_master
				INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
				INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
				INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
				INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
				INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
				INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
				INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
				INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
				INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
				WHERE
					DATE_FORMAT(
						stock_master.transaction_date,
						'%Y-%m'
					) <= DATE_FORMAT(in_rpt_date, '%Y-%m')
				AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
				AND warehouses.stakeholder_office_id = 5
				AND warehouses.district_id = A.district_id
			) AS tehsil_store
		FROM
			(
				SELECT
					warehouses.district_id,
					SUM(
						hf_data_master.closing_balance
					) AS CB
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				WHERE
					stakeholders.geo_level_id = 6
				AND DATE_FORMAT(
					hf_data_master.reporting_start_date,
					'%Y-%m-%d'
				) =  in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				GROUP BY
					warehouses.district_id
			) A
		RIGHT JOIN (
			SELECT
				locations.pk_id,
				locations.location_name,
				locations.province_id,
				Province.location_name AS province_name
			FROM
				locations
			INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
			INNER JOIN locations AS Province ON locations.province_id = Province.pk_id
			WHERE
				locations.geo_level_id = 4
			AND locations.parent_id = in_location_id
			ORDER BY
				locations.location_name ASC
		) B ON A.district_id = B.pk_id
	) A;
WHEN (in_type = 'D') THEN
	SELECT
		Reporting_start_date,
		SUM(hf_data_master.closing_balance) AS CB,
		warehouses.location_id,
		locations.location_name
	FROM
		warehouses
	INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
	INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
INNER JOIN locations ON locations.pk_id = warehouses.location_id
INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
	WHERE
		stakeholders.geo_level_id >= 6
	AND warehouses.stakeholder_id = in_stakeholder
	AND Reporting_start_date = in_rpt_date
	AND hf_data_master.item_pack_size_id = in_item
	AND warehouses.district_id = in_location_id
	GROUP BY
		warehouses.location_id,
		Reporting_start_date
	ORDER BY
		warehouses.location_id,
		Reporting_start_date DESC;
END CASE;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPgetConsumption
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPgetConsumption`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPgetConsumption`(in_type char(2), in_rpt_date DATE, in_location_id INT, in_item INT, in_stakeholder INT)
    DETERMINISTIC
BEGIN
CASE
WHEN (in_type = 'N') THEN
	SELECT
		B.pk_id,
		B.item_name,
		A.consumption
	FROM
		(
			SELECT
				item_pack_sizes.pk_id,
				item_pack_sizes.item_name,
				SUM(hf_data_master.issue_balance) AS consumption
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
			WHERE
				stakeholders.geo_level_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			GROUP BY
				item_pack_sizes.pk_id
		) A
	RIGHT JOIN (
		SELECT
			item_pack_sizes.pk_id,
			item_pack_sizes.item_name
		FROM
			item_pack_sizes
		ORDER BY
			item_pack_sizes.list_rank
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'PP') THEN
	SELECT
		B.pk_id,
		B.location_name,
		A.consumption
	FROM
		(
			SELECT
				warehouses.province_id,
				SUM(hf_data_master.issue_balance) AS consumption
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
			WHERE
				stakeholders.geo_level_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item			
			GROUP BY
				warehouses.province_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 2
		ORDER BY
			locations.pk_id ASC
	) B ON A.province_id = B.pk_id;
WHEN (in_type = 'DP') THEN
	IF(in_location_id != 0) THEN
		SELECT
			B.pk_id,
			B.location_name,
			A.consumption
		FROM
			(
				SELECT
					warehouses.district_id,
					SUM(hf_data_master.issue_balance) AS consumption
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				WHERE
					stakeholders.geo_level_id = 6
				AND warehouses.province_id = in_location_id
				AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				GROUP BY
					warehouses.district_id
			) A
		RIGHT JOIN (
			SELECT
				locations.pk_id,
				locations.location_name
			FROM
				locations
			INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
			WHERE
				locations.geo_level_id = 4
			AND locations.parent_id = in_location_id
			ORDER BY
				locations.location_name ASC
		) B ON A.district_id = B.pk_id;
	ELSEIF(in_location_id = 0) THEN
		SELECT
			B.pk_id,
			B.province_id,
			B.location_name,
			A.consumption
		FROM
			(
				SELECT
					warehouses.district_id,
					SUM(hf_data_master.issue_balance) AS consumption
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				WHERE
					stakeholders.geo_level_id = 6
				AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				GROUP BY
					warehouses.district_id
			) A
		RIGHT JOIN (
			SELECT
				locations.pk_id,
				locations.location_name,
				locations.province_id
			FROM
				locations
			INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
			WHERE
				locations.geo_level_id = 4
			ORDER BY
				locations.province_id ASC,
				locations.location_name ASC
		) B ON A.district_id = B.pk_id;
END IF;
WHEN (in_type = 'TP') THEN
	SELECT
		B.pk_id,
		B.location_name,
		A.consumption
	FROM
		(
			SELECT
				Tehsil.pk_id,
				Tehsil.location_name,
				SUM(hf_data_master.issue_balance) AS consumption
			FROM
				warehouses
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			WHERE
				warehouses.district_id = in_location_id
			AND warehouses.stakeholder_office_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				Tehsil.pk_id
			ORDER BY
				Tehsil.pk_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 5
		AND locations.district_id = in_location_id
		ORDER BY
			locations.location_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'UP') THEN
	SELECT
		B.pk_id,
		B.tehsil_id,
		B.location_name,
		A.consumption
	FROM
		(
			SELECT
				locations.pk_id,
				locations.location_name,
				SUM(hf_data_master.issue_balance) AS consumption
			FROM
				warehouses
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			WHERE
				warehouses.district_id = in_location_id
			AND warehouses.stakeholder_office_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.location_id
			ORDER BY
				locations.location_name ASC
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.parent_id AS tehsil_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 6
		AND locations.district_id = in_location_id
		ORDER BY
			locations.location_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'WP') THEN
	SELECT
		B.pk_id,
		B.tehsil_id,
		B.warehouse_name AS location_name,
		A.consumption
	FROM
		(
			SELECT
				warehouses.pk_id,
				warehouses.warehouse_name,
				SUM(hf_data_master.issue_balance) AS consumption
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			WHERE
				warehouses.location_id = in_location_id
			AND warehouses.stakeholder_office_id = 6 
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.pk_id
			ORDER BY
				warehouses.warehouse_name ASC
		) A
	RIGHT JOIN (
		SELECT
			warehouses.pk_id,
			warehouses.warehouse_name,
			locations.parent_id AS tehsil_id
		FROM
			warehouses
		INNER JOIN locations ON warehouses.location_id = locations.pk_id
		WHERE
			warehouses.location_id = in_location_id
		ORDER BY
			warehouses.warehouse_name ASC
	) B ON A.pk_id = B.pk_id;
WHEN (in_type = 'P') THEN
	SELECT
		hf_data_master.reporting_start_date,
		SUM(hf_data_master.issue_balance) AS consumption,
		SUM(hf_data_master.closing_balance) AS SOH,
		warehouses.district_id AS location_id,
		locations.location_name,
		ROUND(
			(
				(
					(
						(
							location_populations.population / 100
						) * items.population_percent_increase_per_year
					) / 100 * items.child_surviving_percent_per_year
				) * items.doses_per_year
			) / 12
		) AS target
	FROM
		warehouses
	INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
	INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
	INNER JOIN locations ON locations.pk_id = warehouses.district_id
  INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
	INNER JOIN location_populations ON warehouses.district_id = location_populations.location_id
	INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
	INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
	WHERE
		stakeholders.geo_level_id >= 4
	AND warehouses.stakeholder_id = in_stakeholder
	AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
	AND hf_data_master.item_pack_size_id = in_item
	AND warehouses.province_id = in_location_id
	AND YEAR (location_populations.estimation_date) = YEAR(in_rpt_date)
	GROUP BY
		warehouses.district_id,
		Reporting_start_date
	ORDER BY
		warehouses.district_id,
		Reporting_start_date DESC;
WHEN (in_type = 'D') THEN
	SELECT
		warehouses.location_id,
		locations.location_name,
		SUM(hf_data_master.closing_balance) AS CB,
		ROUND(
			(
				(
					(
						(
							location_populations.population / 100
						) * items.population_percent_increase_per_year
					) / 100 * items.child_surviving_percent_per_year
				) * items.doses_per_year
			) / 12
		) AS target
	FROM
		warehouses
	INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
	INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
	INNER JOIN locations ON locations.pk_id = warehouses.location_id
	INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
	INNER JOIN location_populations ON location_populations.location_id = locations.pk_id
	INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
	INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
	WHERE
		stakeholders.geo_level_id >= 6
	AND warehouses.stakeholder_id = in_stakeholder
	AND DATE_FORMAT(
		hf_data_master.reporting_start_date,
		'%Y-%m-%d'
	) = in_rpt_date
	AND hf_data_master.item_pack_size_id = in_item
	AND warehouses.district_id = in_location_id
	AND YEAR (
		location_populations.estimation_date
	) = YEAR (in_rpt_date)
	GROUP BY
		warehouses.location_id
	ORDER BY
		warehouses.location_id;
END CASE;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPgetConsumptionHF
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPgetConsumptionHF`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPgetConsumptionHF`(in_type char(2), in_rpt_date DATE, in_location_id INT, in_item INT, in_stakeholder INT)
    DETERMINISTIC
BEGIN
CASE
WHEN (in_type = 'P') THEN
	SELECT
		hf_data_master.reporting_start_date,
		SUM(hf_data_master.issue_balance) AS consumption,
		warehouses.district_id AS location_id,
    hf_data_master.wastages,
    item_pack_sizes.wastage_rate_allowed,
		locations.location_name,
		ROUND(
			(
				(
					(
						(
							location_populations.population / 100
						) * items.population_percent_increase_per_year
					) / 100 * items.child_surviving_percent_per_year
				) * items.doses_per_year
			) / 12
		) AS target
	FROM
		warehouses
	INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
	INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
	INNER JOIN locations ON locations.pk_id = warehouses.district_id
  INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
	INNER JOIN location_populations ON warehouses.district_id = location_populations.location_id
	INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
	INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
	WHERE
		stakeholders.geo_level_id >= 4
  AND warehouses.status = 1
	AND warehouses.stakeholder_id = in_stakeholder
	AND hf_data_master.reporting_start_date = in_rpt_date
	AND hf_data_master.item_pack_size_id = in_item
	AND warehouses.province_id = in_location_id
	AND YEAR (location_populations.estimation_date) = YEAR(in_rpt_date)
	GROUP BY
		warehouses.district_id,
		Reporting_start_date
	ORDER BY
		warehouses.district_id,
		Reporting_start_date DESC;
WHEN (in_type = 'D') THEN
	SELECT
		A.reporting_start_date,
    A.wastages,
		SUM(A.issue_balance) AS consumption,
		A.location_id AS location_id,
    A.wastage_rate_allowed,
		A.location_name,
		ROUND(
			(
				(
					(
						(
							B.population / 100
						) * A.population_percent_increase_per_year
					) / 100 * A.child_surviving_percent_per_year
				) * A.doses_per_year
			) / 12
		) AS target
FROM
	(
		SELECT
			hf_data_master.reporting_start_date,
			hf_data_master.wastages,
			SUM(
				hf_data_master.issue_balance
			) AS issue_balance,
			warehouses.location_id AS location_id,
			item_pack_sizes.wastage_rate_allowed,
			locations.location_name,
			items.child_surviving_percent_per_year,
			items.population_percent_increase_per_year,
items.doses_per_year
		FROM
			warehouses
		INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
		INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		INNER JOIN locations ON locations.pk_id = warehouses.location_id
		INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
		INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
		INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
		WHERE
			stakeholders.geo_level_id = 6
		AND warehouses. STATUS = 1
		AND warehouses.stakeholder_id = in_stakeholder
		AND hf_data_master.reporting_start_date = in_rpt_date
		AND hf_data_master.item_pack_size_id = in_item
		AND locations.parent_id = in_location_id
		GROUP BY
			warehouses.location_id,
			Reporting_start_date
		ORDER BY
			warehouses.location_id,
			Reporting_start_date DESC
	) A
left JOIN (
	SELECT
		locations.pk_id location_id,
		location_populations.population,
		locations.parent_id
	FROM
		location_populations
	INNER JOIN locations ON location_populations.location_id = locations.pk_id
	WHERE
		YEAR (
			location_populations.estimation_date
		) = YEAR(in_rpt_date)
	AND locations.parent_id = in_location_id
) B ON A.location_id = B.location_id
	GROUP BY
			A.location_id,
			A.Reporting_start_date
		ORDER BY
			A.location_id,
			A.Reporting_start_date DESC;
END CASE;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPgetData
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPgetData`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPgetData`(in_type char(5), in_rpt_date DATE, in_location_id INT, in_item INT, in_stakeholder INT)
    DETERMINISTIC
BEGIN
SET @num := 0;
SET @loc := 0;
CASE
WHEN (in_type = 'N') THEN
	SELECT
		B.pk_id AS item_id,
		B.item_name,
		C.Vaccinated,
		ROUND(A.AMC, 2) AS AMC,
		C.SOH,
		ROUND((C.SOH/A.AMC), 2) AS MOS
	FROM
		(
			SELECT
				B.pk_id,
				B.item_name,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.item_name,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							item_pack_sizes.pk_id,
							item_pack_sizes.item_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
						WHERE
							stakeholders.geo_level_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						GROUP BY
							item_pack_sizes.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							item_pack_sizes.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			item_pack_sizes.pk_id,
			item_pack_sizes.item_name
		FROM
			item_pack_sizes
		ORDER BY
			item_pack_sizes.list_rank
	) B ON A.pk_id = B.pk_id
	LEFT JOIN (
		SELECT
			D.pk_id,
			D.Vaccinated,
			D.SOH + (
				SELECT
					IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
				FROM
					stock_master
				INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
				INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
				INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
				INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
				INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
				INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
				INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
        INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
				WHERE
					 DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <=  DATE_FORMAT(in_rpt_date, '%Y-%m')
				AND stakeholder_item_pack_sizes.item_pack_size_id = D.pk_id
				 
			) AS SOH
		FROM
			(
				SELECT
					item_pack_sizes.pk_id,
					item_pack_sizes.item_name,
					SUM(IF(stakeholders.geo_level_id = 6, hf_data_master.issue_balance, 0)) AS Vaccinated,
					SUM(hf_data_master.closing_balance) AS SOH
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
				WHERE
					DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
				AND stakeholders.geo_level_id = 6
				GROUP BY
					item_pack_sizes.pk_id
			) D
	) C 
	ON C.pk_id = B.pk_id;
WHEN (in_type = 'NP') THEN
		SELECT
			B.item_pack_size_id AS item_id,
			C.Vaccinated,
			ROUND(AVG(B.csum), 2) AS AMC,
			C.SOH,
			ROUND((C.SOH/AVG(B.csum)), 2) AS MOS
		FROM
			(
				SELECT
					A.item_pack_size_id,
					A.csum,
					@num :=
				IF (@loc = item_pack_size_id, @num + 1, 1) AS row_number,
				@loc := item_pack_size_id
			FROM
				(
					SELECT
						hf_data_master.item_pack_size_id,
						SUM(hf_data_master.issue_balance) AS csum
					FROM
						warehouses
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
					INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
					WHERE
						stakeholders.geo_level_id = 6
					AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
					AND hf_data_master.item_pack_size_id = in_item
					GROUP BY
						hf_data_master.item_pack_size_id,
						hf_data_master.reporting_start_date
					ORDER BY
						hf_data_master.item_pack_size_id,
						hf_data_master.reporting_start_date DESC
				) AS A
			) AS B
		JOIN (
			SELECT
				hf_data_master.item_pack_size_id,
				SUM(IF(stakeholders.geo_level_id = 6, hf_data_master.issue_balance, 0)) AS Vaccinated,
				SUM(hf_data_master.closing_balance) + (
					SELECT
						IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
					FROM
						stock_master
					INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
					INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
					INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
					INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
					INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
					INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
					INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
          INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
          INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
					WHERE
						 DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <=  DATE_FORMAT(in_rpt_date, '%Y-%m')
					AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
					 
				) AS SOH
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
				DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			AND stakeholders.geo_level_id >= 1
			GROUP BY
				hf_data_master.item_pack_size_id
		) C
		ON C.item_pack_size_id = B.item_pack_size_id
		WHERE
			row_number <= 3
		GROUP BY
			item_id;
WHEN (in_type = 'P') THEN
	SELECT
		B.pk_id AS province_id,
		B.location_name AS province_name,
		C.Vaccinated,
		ROUND(A.AMC, 2) AS AMC,
		C.SOH,
		ROUND((C.SOH/A.AMC), 2) AS MOS
	FROM
		(
			SELECT
				B.province_id,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.province_id,
						A.csum,
						@num :=
					IF (@loc = province_id, @num + 1, 1) AS row_number,
					@loc := province_id
				FROM
					(
						SELECT
							warehouses.province_id,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
						WHERE
							stakeholders.geo_level_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.province_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.province_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				province_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 2
      AND locations.pk_id <> 10
	) B ON A.province_id = B.pk_id
	LEFT JOIN (
		SELECT
		D.province_id,
		D.Vaccinated,
		D.SOH + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
      INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
      INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
			WHERE
				DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <=  DATE_FORMAT(in_rpt_date, '%Y-%m')
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
		  AND warehouses.stakeholder_office_id IN (2,4,5)
			AND warehouses.province_id = D.province_id
		) AS SOH
	FROM
		(
			SELECT
				warehouses.province_id,
				SUM(IF(stakeholders.geo_level_id = 6, hf_data_master.issue_balance, 0)) AS Vaccinated,
				SUM(hf_data_master.closing_balance) AS SOH
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
				stakeholders.geo_level_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.province_id
		) D
	) C 
	ON C.province_id = B.pk_id
	ORDER BY
		B.pk_id ASC;
WHEN (in_type = 'D') THEN
	#Districts of the given Province
	IF(in_location_id != 0) THEN
		SELECT
			B.province_id,
			B.province_name,
			B.pk_id AS district_id,
			B.location_name AS district_name,
			C.Vaccinated,
			ROUND(A.AMC, 2) AS AMC,
			ABS(C.field_store_SOH) AS field_store_SOH,
			ABS(C.district_store_SOH) AS district_store_SOH,
			ABS(C.total_SOH) AS total_SOH,
			ROUND((ABS(C.district_store_SOH/A.AMC)), 2) AS district_store_MOS,
			ROUND((ABS(C.field_store_SOH/A.AMC)), 2) AS field_store_MOS,
			ROUND((ABS(C.total_SOH/A.AMC)), 2) AS total_MOS
		FROM
			(
				SELECT
					B.district_id,
					AVG(B.csum) AS AMC
				FROM
					(
						SELECT
							A.district_id,
							A.csum,
							@num :=
						IF (@loc = district_id, @num + 1, 1) AS row_number,
						@loc := district_id
					FROM
						(
							SELECT
								warehouses.district_id,
								SUM(hf_data_master.issue_balance) AS csum
							FROM
								warehouses
							INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
							INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
							INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
							WHERE
								stakeholders.geo_level_id = 6
							AND warehouses.province_id = in_location_id
							AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
							AND hf_data_master.item_pack_size_id = in_item
							GROUP BY
								warehouses.district_id,
								hf_data_master.reporting_start_date
							ORDER BY
								warehouses.district_id,
								hf_data_master.reporting_start_date DESC
						) AS A
					) AS B
				WHERE
					row_number <= 3
				GROUP BY
					district_id
			) A
		RIGHT JOIN (
			SELECT
				locations.pk_id,
				locations.location_name,
				locations.province_id,
				Province.location_name AS province_name
			FROM
				locations
			INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
			INNER JOIN locations AS Province ON locations.province_id = Province.pk_id
			WHERE
				locations.geo_level_id = 4
			AND locations.province_id = in_location_id
		) B ON A.district_id = B.pk_id
	LEFT JOIN (
		SELECT
			A.pk_id,
			A.province_id,
			A.location_name,
			A.Vaccinated,
			A.field_store_SOH,
			A.district_store_SOH,
			ABS(A.field_store_SOH) + ABS(A.district_store_SOH) AS total_SOH
		FROM
			(
				SELECT
					B.pk_id,
					B.province_id,
					B.location_name,
					A.Vaccinated,
					A.SOH AS field_store_SOH,
					(
						SELECT
							IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
						FROM
							stock_master
						INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
						INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
						INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
						INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
						INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
						INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
						WHERE
							DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
						AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
						AND warehouses.stakeholder_office_id IN (4,5)
						AND warehouses.district_id = A.district_id
					) AS district_store_SOH
				FROM
					(
						SELECT
							warehouses.district_id,
							SUM(IF(stakeholders.geo_level_id = 6, hf_data_master.issue_balance, 0)) AS Vaccinated,
							SUM(hf_data_master.closing_balance) AS SOH
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						WHERE
							stakeholders.geo_level_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.district_id
					) A
				RIGHT JOIN (
					SELECT
						locations.pk_id,
						locations.location_name,
						locations.province_id,
						Province.location_name AS province_name
					FROM
						locations
					INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
					INNER JOIN locations AS Province ON locations.province_id = Province.pk_id
					WHERE
						locations.geo_level_id = 4
					AND locations.province_id = in_location_id
				) B ON A.district_id = B.pk_id
			) A
	) C 
	ON C.pk_id = B.pk_id
	ORDER BY
		B.province_id,
		B.location_name;
	#All Districts
	ELSEIF(in_location_id = 0) THEN
		SELECT
			B.province_id,
			B.province_name,
			B.pk_id AS district_id,
			B.location_name AS district_name,
			C.Vaccinated,
			ROUND(A.AMC, 2) AS AMC,
			C.field_store_SOH,
			C.district_store_SOH,
			C.total_SOH,
			ROUND((C.district_store_SOH/A.AMC), 2) AS district_store_MOS,
			ROUND((C.field_store_SOH/A.AMC), 2) AS field_store_MOS,
			ROUND((C.total_SOH/A.AMC), 2) AS total_MOS
		FROM
			(
				SELECT
					B.district_id,
					AVG(B.csum) AS AMC
				FROM
					(
						SELECT
							A.district_id,
							A.csum,
							@num :=
						IF (@loc = district_id, @num + 1, 1) AS row_number,
						@loc := district_id
					FROM
						(
							SELECT
								warehouses.district_id,
								SUM(hf_data_master.issue_balance) AS csum
							FROM
								warehouses
							INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
							INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
							INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
							WHERE
								stakeholders.geo_level_id = 6
							AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
							AND hf_data_master.item_pack_size_id = in_item
							GROUP BY
								warehouses.district_id,
								hf_data_master.reporting_start_date
							ORDER BY
								warehouses.district_id,
								hf_data_master.reporting_start_date DESC
						) AS A
					) AS B
				WHERE
					row_number <= 3
				GROUP BY
					district_id
			) A
		RIGHT JOIN (
			SELECT
				locations.pk_id,
				locations.location_name,
				locations.province_id,
				Province.location_name AS province_name
			FROM
				locations
			INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
			INNER JOIN locations AS Province ON locations.province_id = Province.pk_id
			WHERE
				locations.geo_level_id = 4
		) B ON A.district_id = B.pk_id
	LEFT JOIN (
		SELECT
			A.pk_id,
			A.province_id,
			A.location_name,
			A.Vaccinated,
			A.field_store_SOH,
			A.district_store_SOH,
			A.field_store_SOH + A.district_store_SOH AS total_SOH
		FROM
			(
				SELECT
					B.pk_id,
					B.province_id,
					B.location_name,
					A.Vaccinated,
					A.SOH AS field_store_SOH,
					(
						SELECT
							IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
						FROM
							stock_master
						INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
						INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
						INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
						INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
						INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
						INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
						INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
						WHERE
							DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <= DATE_FORMAT(in_rpt_date, '%Y-%m')
						AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
						AND warehouses.stakeholder_office_id IN (4,5)
						AND warehouses.district_id = A.district_id
					) AS district_store_SOH
				FROM
					(
						SELECT
							warehouses.district_id,
							SUM(IF(stakeholders.geo_level_id = 6, hf_data_master.issue_balance, 0)) AS Vaccinated,
							SUM(hf_data_master.closing_balance) AS SOH
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						WHERE
							stakeholders.geo_level_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.district_id
					) A
				RIGHT JOIN (
					SELECT
						locations.pk_id,
						locations.location_name,
						locations.province_id,
						Province.location_name AS province_name
					FROM
						locations
					INNER JOIN pilot_districts ON locations.pk_id = pilot_districts.district_id
					INNER JOIN locations AS Province ON locations.province_id = Province.pk_id
					WHERE
						locations.geo_level_id = 4
				) B ON A.district_id = B.pk_id
			) A
	) C
	ON C.pk_id = B.pk_id
	ORDER BY
		B.province_id,
		B.location_name;
	END IF;
WHEN (in_type = 'TD') THEN
	SELECT
		B.district_id,
		B.district_name,
		B.pk_id AS tehsil_id,
		B.tehsil_name,
		C.Vaccinated,
		ROUND(A.AMC, 2) AS AMC,
		C.SOH,
		ROUND((C.SOH/A.AMC), 2) AS MOS
	FROM
		(
			SELECT
				B.pk_id,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							Tehsil.pk_id,
							Tehsil.location_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN locations ON warehouses.location_id = locations.pk_id
						INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.district_id = in_location_id
						AND warehouses.stakeholder_office_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							Tehsil.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							Tehsil.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			District.pk_id AS district_id,
			District.location_name AS district_name,
			locations.pk_id,
			locations.location_name AS tehsil_name
		FROM
			locations
		INNER JOIN locations AS District ON locations.district_id = District.pk_id
		WHERE
			locations.geo_level_id = 5
		AND locations.district_id = in_location_id
	) B ON A.pk_id = B.pk_id
	LEFT JOIN (
		SELECT
		D.pk_id,
		D.Vaccinated,
		D.SOH + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
      INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
      INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
			WHERE
				 DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <=  DATE_FORMAT(in_rpt_date, '%Y-%m')
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
			AND warehouses.stakeholder_office_id = 5 
			AND warehouses.location_id = D.pk_id
		) AS SOH
	FROM
		(
			SELECT
				Tehsil.pk_id,
				Tehsil.location_name,
				SUM(IF(stakeholders.geo_level_id = 6, hf_data_master.issue_balance, 0)) AS Vaccinated,
				SUM(hf_data_master.closing_balance) AS SOH
			FROM
				warehouses
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
				warehouses.district_id = in_location_id
			AND warehouses.stakeholder_office_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				Tehsil.pk_id
		) D
	) C 
	ON C.pk_id = B.pk_id
	ORDER BY
		B.district_name,
		B.tehsil_name;
WHEN (in_type = 'TP') THEN
	SELECT
		B.district_id,
		B.district_name,
		B.pk_id AS tehsil_id,
		B.tehsil_name,
		C.Vaccinated,
		ROUND(A.AMC, 2) AS AMC,
		C.SOH,
		ROUND((C.SOH/A.AMC), 2) AS MOS
	FROM
		(
			SELECT
				B.pk_id,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							Tehsil.pk_id,
							Tehsil.location_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN locations ON warehouses.location_id = locations.pk_id
						INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.province_id = in_location_id
						AND warehouses.stakeholder_office_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							Tehsil.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							Tehsil.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			District.pk_id AS district_id,
			District.location_name AS district_name,
			locations.pk_id,
			locations.location_name AS tehsil_name
		FROM
			locations
		INNER JOIN locations AS District ON locations.district_id = District.pk_id
    INNER JOIN pilot_districts  ON locations.district_id = pilot_districts.district_id
		WHERE
			locations.geo_level_id = 5
		AND locations.province_id = in_location_id
	) B ON A.pk_id = B.pk_id
	LEFT JOIN (
		SELECT
		D.pk_id,
		D.Vaccinated,
		D.SOH + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
      INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
      INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
			WHERE
				 DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <=  DATE_FORMAT(in_rpt_date, '%Y-%m')
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
			 
			AND warehouses.location_id = D.pk_id
		) AS SOH
	FROM
		(
			SELECT
				Tehsil.pk_id,
				Tehsil.location_name,
				SUM(IF(stakeholders.geo_level_id = 6, hf_data_master.issue_balance, 0)) AS Vaccinated,
				SUM(hf_data_master.closing_balance) AS SOH
			FROM
				warehouses
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
				warehouses.province_id = in_location_id
			AND warehouses.stakeholder_office_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				Tehsil.pk_id
		) D
	) C 
	ON C.pk_id = B.pk_id
	ORDER BY
		B.district_name,
		B.tehsil_name;
WHEN (in_type = 'UP') THEN
	SELECT
		B.tehsil_id,
		B.tehsil_name,
		B.pk_id AS uc_id,
		B.location_name AS uc_name,
		C.Vaccinated,
		ROUND(A.AMC, 2) AS AMC,
		C.SOH,
		ROUND((C.SOH/A.AMC), 2) AS MOS
	FROM
		(
			SELECT
				B.pk_id,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							locations.pk_id,
							locations.location_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN locations ON warehouses.location_id = locations.pk_id
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.province_id = in_location_id
						AND warehouses.stakeholder_office_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.location_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.location_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			Tehsil.pk_id AS tehsil_id,
			Tehsil.location_name AS tehsil_name,
			locations.pk_id,
			locations.location_name
		FROM
			locations
		INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
		WHERE
			locations.geo_level_id = 6
		AND Tehsil.province_id = in_location_id
	) B ON A.pk_id = B.pk_id
	LEFT JOIN (
		SELECT
			D.pk_id,
			D.Vaccinated,
			D.SOH 
	FROM
		(
			SELECT
				locations.pk_id,
				locations.location_name,
				SUM(IF(stakeholders.geo_level_id = 6, hf_data_master.issue_balance, 0)) AS Vaccinated,
				SUM(hf_data_master.closing_balance) AS SOH
			FROM
				warehouses
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
				warehouses.province_id = in_location_id
			AND warehouses.stakeholder_office_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.location_id
		) D
	) C 
	ON C.pk_id = B.pk_id
	ORDER BY
		B.tehsil_name,
		B.location_name;
WHEN (in_type = 'UD') THEN
	SELECT
		B.tehsil_id,
		B.tehsil_name,
		B.pk_id AS uc_id,
		B.location_name AS uc_name,
		C.Vaccinated,
		ROUND(A.AMC, 2) AS AMC,
		C.SOH,
		ROUND((C.SOH/A.AMC), 2) AS MOS
	FROM
		(
			SELECT
				B.pk_id,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							locations.pk_id,
							locations.location_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN locations ON warehouses.location_id = locations.pk_id
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.district_id = in_location_id
						AND warehouses.stakeholder_office_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.location_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.location_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			Tehsil.pk_id AS tehsil_id,
			Tehsil.location_name AS tehsil_name,
			locations.pk_id,
			locations.location_name
		FROM
			locations
		INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
		WHERE
			locations.geo_level_id = 6
		AND Tehsil.parent_id = in_location_id
	) B ON A.pk_id = B.pk_id
	LEFT JOIN (
		SELECT
			D.pk_id,
			D.Vaccinated,
			D.SOH 
	FROM
		(
			SELECT
				locations.pk_id,
				locations.location_name,
				SUM(IF(stakeholders.geo_level_id = 6, hf_data_master.issue_balance, 0)) AS Vaccinated,
				SUM(hf_data_master.closing_balance) AS SOH
			FROM
				warehouses
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
				warehouses.district_id = in_location_id
			AND warehouses.stakeholder_office_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.location_id
		) D
	) C 
	ON C.pk_id = B.pk_id
	ORDER BY
		B.tehsil_name,
		B.location_name;
WHEN (in_type = 'UT') THEN
	SELECT
		B.tehsil_id,
		B.tehsil_name,
		B.pk_id AS uc_id,
		B.location_name AS uc_name,
		C.Vaccinated,
		ROUND(A.AMC, 2) AS AMC,
		C.SOH,
		ROUND((C.SOH/A.AMC), 2) AS MOS
	FROM
		(
			SELECT
				B.pk_id,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							locations.pk_id,
							locations.location_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN locations ON warehouses.location_id = locations.pk_id
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							locations.parent_id = in_location_id
						AND warehouses.stakeholder_office_id = 6
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.location_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.location_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			Tehsil.pk_id AS tehsil_id,
			Tehsil.location_name AS tehsil_name,
			locations.pk_id,
			locations.location_name
		FROM
			locations
		INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
		WHERE
			locations.geo_level_id = 6
		AND Tehsil.pk_id = in_location_id
	) B ON A.pk_id = B.pk_id
	LEFT JOIN (
		SELECT
			D.pk_id,
			D.Vaccinated,
			D.SOH 
	FROM
		(
			SELECT
				locations.pk_id,
				locations.location_name,
				SUM(IF(stakeholders.geo_level_id = 6, hf_data_master.issue_balance, 0)) AS Vaccinated,
				SUM(hf_data_master.closing_balance) AS SOH
			FROM
				warehouses
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
				locations.parent_id = in_location_id
			AND warehouses.stakeholder_office_id = 6
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.location_id
		) D
	) C 
	ON C.pk_id = B.pk_id
	ORDER BY
		B.tehsil_name,
		B.location_name;
WHEN (in_type = 'WU') THEN
	SELECT
		B.location_id,
		B.location_name,
		B.pk_id,
		B.warehouse_name,
		C.Vaccinated,
		ROUND(A.AMC, 2) AS AMC,
		C.SOH,
		ROUND((C.SOH/A.AMC), 2) AS MOS
	FROM
		(
			SELECT
				B.pk_id,
				AVG(B.csum) AS AMC
			FROM
				(
					SELECT
						A.pk_id,
						A.csum,
						@num :=
					IF (@loc = pk_id, @num + 1, 1) AS row_number,
					@loc := pk_id
				FROM
					(
						SELECT
							warehouses.pk_id,
							warehouses.warehouse_name,
							SUM(hf_data_master.issue_balance) AS csum
						FROM
							warehouses
						INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
						WHERE
							warehouses.location_id = in_location_id
						AND warehouses.stakeholder_office_id = 6 
						AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
						AND hf_data_master.item_pack_size_id = in_item
						GROUP BY
							warehouses.pk_id,
							hf_data_master.reporting_start_date
						ORDER BY
							warehouses.pk_id,
							hf_data_master.reporting_start_date DESC
					) AS A
				) AS B
			WHERE
				row_number <= 3
			GROUP BY
				pk_id
		) A
	RIGHT JOIN (
		SELECT
			warehouses.pk_id,
			warehouses.warehouse_name,
			locations.pk_id AS location_id,
			locations.location_name AS location_name
		FROM
			warehouses
		INNER JOIN locations ON warehouses.location_id = locations.pk_id
		WHERE
			warehouses.location_id = in_location_id
		ORDER BY
			warehouses.warehouse_name ASC
	) B ON A.pk_id = B.pk_id
	LEFT JOIN (
		SELECT
			D.pk_id,
			D.Vaccinated,
			D.SOH + (
			SELECT
				IFNULL(SUM(stock_detail.quantity), 0) * item_pack_sizes.number_of_doses
			FROM
				stock_master
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
      INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
      INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id 
			WHERE
				 DATE_FORMAT(stock_master.transaction_date, '%Y-%m') <=  DATE_FORMAT(in_rpt_date, '%Y-%m')
			AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
			 
			AND warehouses.pk_id = D.pk_id
		) AS SOH
	FROM
		(
			SELECT
				warehouses.pk_id,
				warehouses.warehouse_name,
				SUM(IF(stakeholders.geo_level_id = 6, hf_data_master.issue_balance, 0)) AS Vaccinated,
				SUM(hf_data_master.closing_balance) AS SOH
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
				warehouses.location_id = in_location_id
			AND warehouses.stakeholder_office_id = 6 
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			GROUP BY
				warehouses.pk_id
			ORDER BY
				warehouses.warehouse_name ASC
		) D
	) C 
	ON C.pk_id = B.pk_id;
/*WHEN (in_type = 'P') THEN
	SELECT
		B.district_id,
		B.location_name AS dstrcit_name,
		C.Vaccinated,
		ROUND(AVG(B.csum), 2) AS AMC,
		C.SOH,
		ROUND((C.SOH/AVG(B.csum)), 2) AS MOS,
		C.target
	FROM
		(
			SELECT
				A.csum,
				A.district_id,
				A.location_name,
				@num :=
			IF (@loc = district_id, @num + 1, 1) AS row_number,
			@loc := district_id as location_id
		FROM
			(
				SELECT
					SUM(hf_data_master.issue_balance) AS csum,
					warehouses.district_id,
					locations.location_name
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN locations ON locations.pk_id = warehouses.district_id
        INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
				WHERE
					stakeholders.geo_level_id = 6
				AND hf_data_master.issue_balance > 0
				AND warehouses.stakeholder_id = 1
				AND issue_balance > 0
				AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.province_id = in_location_id
				GROUP BY
					warehouses.district_id,
					hf_data_master.reporting_start_date
				ORDER BY
					warehouses.district_id,
					hf_data_master.reporting_start_date DESC
			) AS A
		) AS B
		JOIN(
			SELECT
				warehouses.district_id AS location_id,
				locations.location_name,
				SUM(IF(stakeholders.geo_level_id = 6, hf_data_master.issue_balance, 0)) AS Vaccinated,
				SUM(hf_data_master.closing_balance) AS SOH,
				ROUND(
					(
						(
							(
								(
									location_populations.population / 100
								) * items.population_percent_increase_per_year
							) / 100 * items.child_surviving_percent_per_year
						) * items.doses_per_year
					) / 12
				) AS target
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			INNER JOIN locations ON locations.pk_id = warehouses.district_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
			INNER JOIN location_populations ON warehouses.district_id = location_populations.location_id
			INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
			INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
			WHERE
				stakeholders.geo_level_id >= 4
			AND warehouses.stakeholder_id = in_stakeholder
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			AND warehouses.province_id = in_location_id
			AND YEAR (location_populations.estimation_date) = YEAR(in_rpt_date)
			GROUP BY
				warehouses.district_id,
				Reporting_start_date
			ORDER BY
				warehouses.district_id,
				Reporting_start_date DESC
		)C
		ON C.location_id = B.location_id
	WHERE
		row_number <= 3
	GROUP BY
		district_id;
WHEN (in_type = 'D') THEN
	SELECT
		B.location_id,
		B.location_name,
		C.target,
		C.Vaccinated,
		ROUND(AVG(B.csum), 2) AS AMC,
		C.SOH,
		ROUND((C.SOH/AVG(B.csum)), 2) AS MOS
	FROM
		(
			SELECT
				A.reporting_start_date,
				A.csum,
				A.location_id,
				A.location_name,
				@num :=
			IF (@loc = location_id, @num + 1, 1) AS row_number,
			@loc := location_id
		FROM
			(
				SELECT
					hf_data_master.reporting_start_date,
					SUM(hf_data_master.issue_balance) AS csum,
					warehouses.location_id,
					locations.location_name
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				INNER JOIN locations ON locations.pk_id = warehouses.location_id
        INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id 
				WHERE
					stakeholders.geo_level_id >= 6
				AND hf_data_master.issue_balance > 0
				AND warehouses.stakeholder_id = 1
				AND issue_balance > 0
				AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') <= in_rpt_date
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.district_id = in_location_id
				GROUP BY
					warehouses.location_id,
					hf_data_master.reporting_start_date
				ORDER BY
					warehouses.location_id,
					hf_data_master.reporting_start_date DESC
			) AS A
		) AS B
		JOIN(
			SELECT
				warehouses.location_id,
				locations.location_name,
				SUM(hf_data_master.issue_balance) AS Vaccinated,
				ROUND(
					(
						(
							(
								(
									location_populations.population / 100
								) * items.population_percent_increase_per_year
							) / 100 * items.child_surviving_percent_per_year
						) * items.doses_per_year
					) / 12
				) AS target,
				SUM(hf_data_master.closing_balance) AS SOH
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			INNER JOIN locations ON locations.pk_id = warehouses.location_id
			INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
			INNER JOIN location_populations ON location_populations.location_id = locations.pk_id
			INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
			INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
			WHERE
				stakeholders.geo_level_id >= 6
			AND warehouses.stakeholder_id = in_stakeholder
			AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = in_rpt_date
			AND hf_data_master.item_pack_size_id = in_item
			AND warehouses.district_id = in_location_id
			AND YEAR (location_populations.estimation_date) = YEAR (in_rpt_date)
			GROUP BY
				warehouses.location_id
			ORDER BY
				warehouses.location_id
		)C
		ON C.location_id = B.location_id
	WHERE
		row_number <= 3
	GROUP BY
		location_id;*/
END CASE;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPStockBatchHistory
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPStockBatchHistory`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPStockBatchHistory`(in_id INT, in_action INT)
    READS SQL DATA
    DETERMINISTIC
BEGIN
INSERT INTO stock_batch_warehouses_history (
	stock_batch_warehouses_history.stock_batch_warehouse_id,
	stock_batch_warehouses_history.quantity,
	stock_batch_warehouses_history.`status`,
	stock_batch_warehouses_history.action_type,
	stock_batch_warehouses_history.created_by,
	stock_batch_warehouses_history.created_date,
	stock_batch_warehouses_history.modified_by,
	stock_batch_warehouses_history.modified_date
) SELECT
		stock_batch_warehouses.pk_id,
		stock_batch_warehouses.quantity,
		stock_batch_warehouses.`status`,
		in_action,
		stock_batch_warehouses.created_by,
		stock_batch_warehouses.created_date,
		stock_batch_warehouses.modified_by,
		stock_batch_warehouses.modified_date
	FROM
		stock_batch_warehouses
	WHERE
		stock_batch_warehouses.pk_id = in_id;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPStockDetailHistory
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPStockDetailHistory`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPStockDetailHistory`(in_id INT, in_action INT)
    READS SQL DATA
    DETERMINISTIC
BEGIN
	INSERT INTO stock_detail_history (
		stock_detail_history.detail_id,
		stock_detail_history.quantity,
		stock_detail_history.`temporary`,
		stock_detail_history.vvm_stage,
		stock_detail_history.is_received,
		stock_detail_history.adjustment_type,
		stock_detail_history.stock_master_id,
		stock_detail_history.stock_batch_warehouse_id,
		stock_detail_history.item_unit_id,
		stock_detail_history.action_type,
		stock_detail_history.created_by,
		stock_detail_history.created_date,
		stock_detail_history.modified_by,
		stock_detail_history.modified_date
	) SELECT
		stock_detail.pk_id,
		stock_detail.quantity,
		stock_detail.`temporary`,
		stock_detail.vvm_stage,
		stock_detail.is_received,
		stock_detail.adjustment_type,
		stock_detail.stock_master_id,
		stock_detail.stock_batch_warehouse_id,
		stock_detail.item_unit_id,
		in_action,
		stock_detail.created_by,
		NOW(),
		stock_detail.modified_by,
		NOW()
	FROM
		stock_detail
	WHERE
		stock_detail.pk_id = in_id;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPStockMasterHistory
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPStockMasterHistory`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPStockMasterHistory`(in_id INT, in_action INT)
    READS SQL DATA
    DETERMINISTIC
BEGIN
	INSERT INTO stock_master_history (
		stock_master_history.master_id,
		stock_master_history.transaction_date,
		stock_master_history.transaction_number,
		stock_master_history.transaction_counter,
		stock_master_history.transaction_reference,
		stock_master_history.draft,
		stock_master_history.comments,
		stock_master_history.transaction_type_id,
		stock_master_history.from_warehouse_id,
		stock_master_history.to_warehouse_id,
		stock_master_history.parent_id,
		stock_master_history.campaign_id,
		stock_master_history.stakeholder_activity_id,
		stock_master_history.created_by,
		stock_master_history.created_date,
		stock_master_history.modified_by,
		stock_master_history.modified_date,
		stock_master_history.action_type
	) SELECT
		stock_master.pk_id,
		stock_master.transaction_date,
		stock_master.transaction_number,
		stock_master.transaction_counter,
		stock_master.transaction_reference,
		stock_master.draft,
		stock_master.comments,
		stock_master.transaction_type_id,
		stock_master.from_warehouse_id,
		stock_master.to_warehouse_id,
		stock_master.parent_id,
		stock_master.campaign_id,
		stock_master.stakeholder_activity_id,
		stock_master.created_by,
		NOW(),
		stock_master.modified_by,
		NOW(),
		in_action
	FROM
		stock_master
	WHERE
		stock_master.pk_id = in_id;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPUpdateSummaryDistrict
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPUpdateSummaryDistrict`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPUpdateSummaryDistrict`(in_stk INT, in_item INT, in_rpt_date DATETIME, in_loc INT)
    READS SQL DATA
    DETERMINISTIC
BEGIN
DECLARE locId INTEGER;
DECLARE provId INTEGER;
DECLARE consumption DOUBLE;
DECLARE AMC DOUBLE;
DECLARE SOH DOUBLE;
DECLARE SOHStore DOUBLE;
DECLARE RRPer DOUBLE;
DECLARE totalRecord DOUBLE;
DECLARE totalUCs DOUBLE;
DECLARE wastages DECIMAL(5, 2);
DECLARE pkId INTEGER;
DECLARE in_month INTEGER;
DECLARE in_year INTEGER;
SELECT SUBSTRING(in_rpt_date, 1, 4) INTO in_year;
SELECT SUBSTRING(in_rpt_date, 6, 2) INTO in_month;
SELECT
	locations.pk_id,
	locations.province_id
	INTO locId, provId
FROM
	locations
WHERE
	locations.pk_id = in_loc;
SELECT COALESCE(REPgetConsumption(in_month, in_year, in_item, in_stk, 'D', locId), NULL, 0) INTO consumption FROM DUAL;
SELECT COALESCE(REPgetConsumptionAVG('D',  in_month, in_year, in_item, in_stk, locId, locId), NULL, 0) INTO AMC FROM DUAL;
SELECT COALESCE(REPgetCB('D',  in_month, in_year, in_item, in_stk, locId, locId), NULL, 0) INTO SOH FROM DUAL;
SELECT COALESCE(REPgetCB('DW',  in_month, in_year, in_item, in_stk, locId, locId), NULL, 0) INTO SOHStore FROM DUAL;
IF (consumption != 0 OR AMC != 0 OR SOH != 0 OR SOHStore != 0) THEN
	# Get wastages Rate
	SELECT COALESCE(REPgetWastage('D',  CONCAT(in_year, '-', LPAD(in_month,2,0), '-01'), CONCAT(in_year, '-', LPAD(in_month,2,0), '-30'), in_stk, in_item, locId, 1), NULL, 0) INTO wastages FROM DUAL;
	# Get Reporting Rate
	SELECT COALESCE(REPgetRR('D',  CONCAT(in_year, '-', LPAD(in_month,2,0), '-01'), CONCAT(in_year, '-', LPAD(in_month,2,0), '-30'), in_stk, in_item, locId, 1), NULL, 0) INTO RRPer FROM DUAL;
	# Get Total UCs
	SELECT REPgetTotalUCs('D', locId, in_stk) INTO totalUCs FROM DUAL;
	#Check if data already present in the Summary Table
	SELECT
		summary_district.pk_id,
		COUNT(summary_district.pk_id)
		INTO pkId, totalRecord
	FROM
		summary_district
	WHERE
		summary_district.district_id = locId
	AND summary_district.item_id = in_item
	AND summary_district.stakeholder_id = in_stk
	AND summary_district.reporting_date = in_rpt_date;
	IF(totalRecord > 0) THEN
		UPDATE summary_district SET
			stakeholder_id = in_stk,
			item_id = in_item,
			district_id = locId,
			province_id = provId,
			reporting_date = in_rpt_date,
			average_consumption = AMC,
			consumption = consumption,
			soh_store = SOHStore,
			soh_level = SOH,
			reporting_rate = RRPer,
			wastages_percentage = wastages,
			total_uc = totalUCs
		WHERE
			pk_id = pkId;
	ELSE
		INSERT INTO summary_district SET
			stakeholder_id = in_stk,
			item_id = in_item,
			district_id = locId,
			province_id = provId,
			reporting_date = in_rpt_date,
			average_consumption = AMC,
			consumption = consumption,
			soh_store = SOHStore,
			soh_level = SOH,
			reporting_rate = RRPer,
			wastages_percentage = wastages,
			total_uc = totalUCs;
	END IF;
END IF;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPUpdateSummaryNational
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPUpdateSummaryNational`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPUpdateSummaryNational`(in_stk INT, in_item INT, in_rpt_date DATETIME)
    READS SQL DATA
    DETERMINISTIC
BEGIN
DECLARE locId INTEGER;
DECLARE consumption DOUBLE;
DECLARE AMC DOUBLE;
DECLARE SOH DOUBLE;
DECLARE SOHStore DOUBLE;
DECLARE RRPer DOUBLE;
DECLARE totalRecord DOUBLE;
DECLARE totalUCs DOUBLE;
DECLARE wastages DECIMAL(5, 2);
DECLARE pkId INTEGER;
DECLARE in_month INTEGER;
DECLARE in_year INTEGER;
SELECT SUBSTRING(in_rpt_date, 1, 4) INTO in_year;
SELECT SUBSTRING(in_rpt_date, 6, 2) INTO in_month;
SET locId = 0;
SELECT COALESCE(REPgetConsumption(in_month, in_year, in_item, in_stk, 'N', locId), NULL, 0) INTO consumption FROM DUAL;
SELECT COALESCE(REPgetConsumptionAVG('N',  in_month, in_year, in_item, in_stk, locId, locId), NULL, 0) INTO AMC FROM DUAL;
SELECT COALESCE(REPgetCB('N',  in_month, in_year, in_item, in_stk, locId, locId), NULL, 0) INTO SOH FROM DUAL;
SELECT COALESCE(REPgetCB('NW',  in_month, in_year, in_item, in_stk, locId, locId), NULL, 0) INTO SOHStore FROM DUAL;
IF (consumption != 0 OR AMC != 0 OR SOH != 0 OR SOHStore != 0) THEN
	# Get wastages Rate
	SELECT COALESCE(REPgetWastage('N',  CONCAT(in_year, '-', LPAD(in_month,2,0), '-01'), CONCAT(in_year, '-', LPAD(in_month,2,0), '-30'), in_stk, in_item, locId, 1), NULL, 0) INTO wastages FROM DUAL;
	# Get Reporting Rate
	SELECT COALESCE(REPgetRR('N',  CONCAT(in_year, '-', LPAD(in_month,2,0), '-01'), CONCAT(in_year, '-', LPAD(in_month,2,0), '-30'), in_stk, in_item, locId, 1), NULL, 0) INTO RRPer FROM DUAL;
	# Get Total UCs
	SELECT REPgetTotalUCs('N', locId, in_stk) INTO totalUCs FROM DUAL;
	#Check if data already present in the Summary Table
	SELECT
		summary_national.pk_id,
		COUNT(summary_national.pk_id)
		INTO pkId, totalRecord
	FROM
		summary_national
	WHERE
		summary_national.item_id = in_item
	AND summary_national.stakeholder_id = in_stk
	AND summary_national.reporting_date = in_rpt_date;
	IF(totalRecord > 0) THEN
		UPDATE summary_national SET
			stakeholder_id = in_stk,
			item_id = in_item,
			reporting_date = in_rpt_date,
			average_consumption = AMC,
			consumption = consumption,
			soh_store = SOHStore,
			soh_level = SOH,
			reporting_rate = RRPer,
			wastages_percentage = wastages,
			total_uc = totalUCs
		WHERE
			pk_id = pkId;
	ELSE
		INSERT INTO summary_national SET
			stakeholder_id = in_stk,
			item_id = in_item,
			reporting_date = in_rpt_date,
			average_consumption = AMC,
			consumption = consumption,
			soh_store = SOHStore,
			soh_level = SOH,
			reporting_rate = RRPer,
			wastages_percentage = wastages,
			total_uc = totalUCs;
	END IF;
END IF;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPUpdateSummaryProvince
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPUpdateSummaryProvince`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPUpdateSummaryProvince`(in_stk INT, in_item INT, in_rpt_date DATETIME, in_loc INT)
    READS SQL DATA
    DETERMINISTIC
BEGIN
DECLARE locId INTEGER;
DECLARE provId INTEGER;
DECLARE consumption DOUBLE;
DECLARE AMC DOUBLE;
DECLARE SOH DOUBLE;
DECLARE SOHStore DOUBLE;
DECLARE RRPer DOUBLE;
DECLARE totalRecord DOUBLE;
DECLARE totalUCs DOUBLE;
DECLARE wastages DECIMAL(5, 2);
DECLARE pkId INTEGER;
DECLARE in_month INTEGER;
DECLARE in_year INTEGER;
SELECT SUBSTRING(in_rpt_date, 1, 4) INTO in_year;
SELECT SUBSTRING(in_rpt_date, 6, 2) INTO in_month;
SET locId = in_loc;
SELECT COALESCE(REPgetConsumption(in_month, in_year, in_item, in_stk, 'P', locId), NULL, 0) INTO consumption FROM DUAL;
SELECT COALESCE(REPgetConsumptionAVG('P',  in_month, in_year, in_item, in_stk, locId, locId), NULL, 0) INTO AMC FROM DUAL;
SELECT COALESCE(REPgetCB('P',  in_month, in_year, in_item, in_stk, locId, locId), NULL, 0) INTO SOH FROM DUAL;
SELECT COALESCE(REPgetCB('PW',  in_month, in_year, in_item, in_stk, locId, locId), NULL, 0) INTO SOHStore FROM DUAL;
IF (consumption != 0 OR AMC != 0 OR SOH != 0 OR SOHStore != 0) THEN
	# Get wastages Rate
	SELECT COALESCE(REPgetWastage('P',  CONCAT(in_year, '-', LPAD(in_month,2,0), '-01'), CONCAT(in_year, '-', LPAD(in_month,2,0), '-30'), in_stk, in_item, locId, 1), NULL, 0) INTO wastages FROM DUAL;
	# Get Reporting Rate
	SELECT COALESCE(REPgetRR('P',  CONCAT(in_year, '-', LPAD(in_month,2,0), '-01'), CONCAT(in_year, '-', LPAD(in_month,2,0), '-30'), in_stk, in_item, locId, 1), NULL, 0) INTO RRPer FROM DUAL;
	# Get Total UCs
	SELECT REPgetTotalUCs('P', locId, in_stk) INTO totalUCs FROM DUAL;
	#Check if data already present in the Summary Table
	SELECT
		summary_provincial.pk_id,
		COUNT(summary_provincial.pk_id)
		INTO pkId, totalRecord
	FROM
		summary_provincial
	WHERE
		summary_provincial.province_id = locId
	AND summary_provincial.item_id = in_item
	AND summary_provincial.stakeholder_id = in_stk
	AND summary_provincial.reporting_date = in_rpt_date;
	IF(totalRecord > 0) THEN
		UPDATE summary_provincial SET
			stakeholder_id = in_stk,
			item_id = in_item,
			province_id = locId,
			reporting_date = in_rpt_date,
			average_consumption = AMC,
			consumption = consumption,
			soh_store = SOHStore,
			soh_level = SOH,
			reporting_rate = RRPer,
			wastages_percentage = wastages,
			total_uc = totalUCs
		WHERE
			pk_id = pkId;
	ELSE
		INSERT INTO summary_provincial SET
			stakeholder_id = in_stk,
			item_id = in_item,
			province_id = locId,
			reporting_date = in_rpt_date,
			average_consumption = AMC,
			consumption = consumption,
			soh_store = SOHStore,
			soh_level = SOH,
			reporting_rate = RRPer,
			wastages_percentage = wastages,
			total_uc = totalUCs;
	END IF;
END IF;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPUpdateSummaryTehsil
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPUpdateSummaryTehsil`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPUpdateSummaryTehsil`(in_stk INT, in_item INT, in_rpt_date DATETIME, in_loc INT)
    READS SQL DATA
    DETERMINISTIC
BEGIN
DECLARE distId DOUBLE;
DECLARE locId INTEGER;
DECLARE provId INTEGER;
DECLARE consumption DOUBLE;
DECLARE AMC DOUBLE;
DECLARE SOH DOUBLE;
DECLARE SOHStore DOUBLE;
DECLARE RRPer DOUBLE;
DECLARE totalRecord DOUBLE;
DECLARE totalUCs DOUBLE;
DECLARE wastages DECIMAL(5, 2);
DECLARE pkId INTEGER;
DECLARE in_month INTEGER;
DECLARE in_year INTEGER;
SELECT SUBSTRING(in_rpt_date, 1, 4) INTO in_year;
SELECT SUBSTRING(in_rpt_date, 6, 2) INTO in_month;
SELECT
	locations.pk_id,
	locations.district_id,
	locations.province_id
	INTO locId, distId, provId
FROM
	locations
WHERE
	locations.pk_id = in_loc;
SELECT COALESCE(REPgetConsumption(in_month, in_year, in_item, in_stk, 'H', locId), NULL, 0) INTO consumption FROM DUAL;
SELECT COALESCE(REPgetConsumptionAVG('H',  in_month, in_year, in_item, in_stk, locId, locId), NULL, 0) INTO AMC FROM DUAL;
SELECT COALESCE(REPgetCB('H',  in_month, in_year, in_item, in_stk, locId, locId), NULL, 0) INTO SOH FROM DUAL;
SELECT COALESCE(REPgetCB('HW',  in_month, in_year, in_item, in_stk, locId, locId), NULL, 0) INTO SOHStore FROM DUAL;
IF (consumption != 0 OR AMC != 0 OR SOH != 0 OR SOHStore != 0) THEN
	# Get wastages Rate
	SELECT COALESCE(REPgetWastage('T',  CONCAT(in_year, '-', LPAD(in_month,2,0), '-01'), CONCAT(in_year, '-', LPAD(in_month,2,0), '-30'), in_stk, in_item, locId, 1), NULL, 0) INTO wastages FROM DUAL;
	# Get Reporting Rate
	SELECT COALESCE(REPgetRR('T',  CONCAT(in_year, '-', LPAD(in_month,2,0), '-01'), CONCAT(in_year, '-', LPAD(in_month,2,0), '-30'), in_stk, in_item, locId, 1), NULL, 0) INTO RRPer FROM DUAL;
	# Get Total UCs
	SELECT REPgetTotalUCs('T', locId, in_stk) INTO totalUCs FROM DUAL;
	#Check if data already present in the Summary Table
	SELECT
		summary_tehsil.pk_id,
		COUNT(summary_tehsil.pk_id)
		INTO pkId, totalRecord
	FROM
		summary_tehsil
	WHERE
		summary_tehsil.tehsil_id = locId
	AND summary_tehsil.item_id = in_item
	AND summary_tehsil.stakeholder_id = in_stk
	AND summary_tehsil.reporting_date = in_rpt_date;
	IF(totalRecord > 0) THEN
		UPDATE summary_tehsil SET
			stakeholder_id = in_stk,
			item_id = in_item,
			district_id = distId,
			province_id = provId,
			tehsil_id = locId,
			reporting_date = in_rpt_date,
			average_consumption = AMC,
			consumption = consumption,
			soh_store = SOHStore,
			soh_level = SOH,
			reporting_rate = RRPer,
			wastages_percentage = wastages,
			total_uc = totalUCs
		WHERE
			pk_id = pkId;
	ELSE
		INSERT INTO summary_tehsil SET
			stakeholder_id = in_stk,
			item_id = in_item,
			district_id = distId,
			province_id = provId,
			tehsil_id = locId,
			reporting_date = in_rpt_date,
			average_consumption = AMC,
			consumption = consumption,
			soh_store = SOHStore,
			soh_level = SOH,
			reporting_rate = RRPer,
			wastages_percentage = wastages,
			total_uc = totalUCs;
	END IF;
END IF;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for REPUpdateSummaryUC
-- ----------------------------
DROP PROCEDURE IF EXISTS `REPUpdateSummaryUC`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` PROCEDURE `REPUpdateSummaryUC`(in_wh_id INTEGER, in_item VARCHAR(10), in_rpt_date DATETIME)
    READS SQL DATA
    DETERMINISTIC
BEGIN
DECLARE distId DOUBLE;
DECLARE stkId INTEGER;
DECLARE locId INTEGER;
DECLARE provId INTEGER;
DECLARE tehsilId INTEGER;
DECLARE consumption DOUBLE;
DECLARE AMC DOUBLE;
DECLARE SOH DOUBLE;
DECLARE SOHStore DOUBLE;
DECLARE RRPer DOUBLE;
DECLARE totalRecord DOUBLE;
DECLARE totalUCs DOUBLE;
DECLARE wastages DECIMAL(5, 2);
DECLARE pkId INTEGER;
DECLARE in_month INTEGER;
DECLARE in_year INTEGER;
SELECT SUBSTRING(in_rpt_date, 1, 4) INTO in_year;
SELECT SUBSTRING(in_rpt_date, 6, 2) INTO in_month;
SELECT
	warehouses.location_id,
	warehouses.district_id,
	warehouses.stakeholder_id,
	warehouses.province_id,
	locations.parent_id
INTO locId, distId, stkId, provId, tehsilId
FROM
	warehouses
INNER JOIN locations ON warehouses.location_id = locations.pk_id
WHERE
	warehouses.pk_id = in_wh_id;
SELECT COALESCE(REPgetConsumption(in_month, in_year, in_item, stkid, 'U', locId), NULL, 0) INTO consumption FROM DUAL;
SELECT COALESCE(REPgetConsumptionAVG('U',  in_month, in_year, in_item, stkId, locId, locId), NULL, 0) INTO AMC FROM DUAL;
SELECT COALESCE(REPgetCB('U',  in_month, in_year, in_item, stkId, locId, locId), NULL, 0) INTO SOH FROM DUAL;
SELECT COALESCE(REPgetCB('UW',  in_month, in_year, in_item, stkId, locId, locId), NULL, 0) INTO SOHStore FROM DUAL;
IF (consumption != 0 OR AMC != 0 OR SOH != 0 OR SOHStore != 0) THEN
	# Get wastages Rate
	SELECT COALESCE(REPgetWastage('U',  CONCAT(in_year, '-', LPAD(in_month,2,0), '-01'), CONCAT(in_year, '-', LPAD(in_month,2,0), '-30'), stkId, in_item, locId, 1), NULL, 0) INTO wastages FROM DUAL;
	# Get Reporting Rate
	SELECT COALESCE(REPgetRR('U',  CONCAT(in_year, '-', LPAD(in_month,2,0), '-01'), CONCAT(in_year, '-', LPAD(in_month,2,0), '-30'), stkId, in_item, locId, 1), NULL, 0) INTO RRPer FROM DUAL;
	# Get Total UCs
	SELECT REPgetTotalUCs('U', locId, stkId) INTO totalUCs FROM DUAL;
	#Check if data already present in the Summary Table
	SELECT
		summary_union_council.pk_id,
		COUNT(summary_union_council.pk_id)
		INTO pkId, totalRecord
	FROM
		summary_union_council
	WHERE
		summary_union_council.union_council_id = distId
	AND summary_union_council.item_id = in_item
	AND summary_union_council.stakeholder_id = stkId
	AND summary_union_council.reporting_date = in_rpt_date;
	IF(totalRecord > 0) THEN
		UPDATE summary_union_council SET
			stakeholder_id = stkId,
			item_id = in_item,
			district_id = distId,
			province_id = provId,
			tehsil_id = tehsilId,
			union_council_id = locId,
			reporting_date = in_rpt_date,
			average_consumption = AMC,
			consumption = consumption,
			soh_store = SOHStore,
			soh_level = SOH,
			reporting_rate = RRPer,
			wastages_percentage = wastages,
			total_uc = totalUCs
		WHERE
			pk_id = pkId;
	ELSE
		INSERT INTO summary_union_council SET
			stakeholder_id = stkId,
			item_id = in_item,
			district_id = distId,
			province_id = provId,
			tehsil_id = tehsilId,
			union_council_id = locId,
			reporting_date = in_rpt_date,
			average_consumption = AMC,
			consumption = consumption,
			soh_store = SOHStore,
			soh_level = SOH,
			reporting_rate = RRPer,
			wastages_percentage = wastages,
			total_uc = totalUCs;
	END IF;
END IF;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for AdjustQty
-- ----------------------------
DROP FUNCTION IF EXISTS `AdjustQty`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `AdjustQty`(`batchId` int,`whId` int) RETURNS bigint(20)
    DETERMINISTIC
BEGIN 
  -- DECLARE receive INT;
  -- DECLARE issue INT;
  -- DECLARE adjustment INT;
  DECLARE qty BIGINT;
SELECT
	 IFNULL(Sum(stock_detail.quantity),0) INTO qty
	 FROM
	 stock_detail
	INNER JOIN stock_batch_warehouses ON  stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
  INNER JOIN stock_master ON   stock_detail.stock_master_id = stock_master.pk_id
	WHERE
	stock_detail.stock_batch_warehouse_id = batchId AND
	stock_batch_warehouses.warehouse_id= whId;
  /*
SELECT
	 IFNULL(Sum(stock_detail.quantity),0) INTO receive
	 FROM
	 stock_detail
	INNER JOIN stock_batch ON  stock_detail.stock_batch_id = stock_batch.pk_id
  INNER JOIN stock_master ON   stock_detail.stock_master_id = stock_master.pk_id
	WHERE
	stock_master.transaction_type_id = 1 AND
	stock_detail.stock_batch_id = batchId AND
	stock_master.to_warehouse_id= whId;
	SELECT
	IFNULL(Sum(stock_detail.quantity),0) INTO issue
	FROM
	stock_detail 
	INNER JOIN  stock_batch  ON  stock_detail.stock_batch_id = stock_batch.pk_id
  INNER JOIN   stock_master  ON   stock_detail.stock_master_id = stock_master.pk_id
	WHERE
	stock_master.transaction_type_id = 2 AND
	stock_detail.stock_batch_id = batchId AND
	stock_master.from_warehouse_id  = whId;
	SELECT
	IFNULL(Sum(stock_detail.quantity),0)  INTO  adjustment
	FROM
	stock_detail
	INNER JOIN  stock_batch  ON  stock_detail.stock_batch_id  = stock_batch.pk_id
  INNER JOIN  stock_master ON  stock_detail.stock_master_id  = stock_master.pk_id
	WHERE
	stock_master.transaction_type_id > 2 AND
	stock_detail.stock_batch_id = batchId AND
	stock_master.from_warehouse_id  = whId;
	SET qty = receive + issue + adjustment;*/
	 IF(qty >= 0) THEN 
		 UPDATE  stock_batch_warehouses  SET  quantity = qty	WHERE  pk_id=batchId AND warehouse_id = whId;
	 END IF;
   IF(qty < 0) THEN
		 UPDATE  stock_batch_warehouses  SET  quantity = 0, status='Finished'	WHERE  pk_id=batchId AND warehouse_id = whId;
	 END IF;
 
	 /*UPDATE stock_batch_warehouses SET status='Finished'	WHERE quantity <= 0;
   UPDATE stock_batch_warehouses SET status='Stacked'	WHERE quantity > 0 and status!='Running'	;*/
  RETURN(qty);
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for BatchBalance
-- ----------------------------
DROP FUNCTION IF EXISTS `BatchBalance`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `BatchBalance`(`batchId` int,`trDate` date) RETURNS bigint(20)
    READS SQL DATA
    DETERMINISTIC
BEGIN 
  DECLARE qty BIGINT;
	SELECT
		SUM(stock_detail.quantity) INTO qty
	FROM
		stock_detail
	INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
	WHERE
		stock_detail.stock_batch_warehouse_id = batchId
	AND DATE_FORMAT(
		stock_master.transaction_date,
		"%Y-%m-%d"
	) <= trDate;
  RETURN(qty);
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for ExpireOn
-- ----------------------------
DROP FUNCTION IF EXISTS `ExpireOn`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`203.99.49.122` FUNCTION `ExpireOn`(`start_month` int,`end_month` int,`item_id` int,`wh_id` int) RETURNS bigint(20)
    DETERMINISTIC
BEGIN
	#Routine body goes here...
DECLARE QTY BIGINT;
SELECT
IFNULL(sum(stock_batch_warehouses.quantity),0) INTO QTY
FROM
stock_batch
INNER JOIN stock_batch_warehouses ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
WHERE
stock_batch_warehouses.warehouse_id = wh_id AND
stakeholder_item_pack_sizes.item_pack_size_id = item_id and 
stock_batch_warehouses.quantity >0 
and stock_batch.expiry_date > ADDDATE(now(), INTERVAL start_month MONTH) 
and stock_batch.expiry_date <= ADDDATE(now(), INTERVAL end_month MONTH);
	RETURN(QTY);
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for GetIllegalWastageofDistrict
-- ----------------------------
DROP FUNCTION IF EXISTS `GetIllegalWastageofDistrict`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `GetIllegalWastageofDistrict`(item_id integer, reporting_date varchar(10),district_id integer,allowable_wastage_rate integer,stakeholderid integer) RETURNS varchar(255) CHARSET latin1
    READS SQL DATA
    DETERMINISTIC
BEGIN 
   DECLARE wastage_uc DOUBLE;
	 DECLARE total_ucs DOUBLE;
	 DECLARE wastage_rate DOUBLE;
	 DECLARE retstr VARCHAR(255);
  
	SELECT 
sum(COALESCE (wastagePer, NULL, 0)),
count(UCs),
sum(COALESCE (wastagePer, NULL, 0))/count(UCs)*100 into wastage_uc,total_ucs,wastage_rate
FROM
	(
		SELECT
			if(ROUND(
				IFNULL(
					(
						sum(hf_data_master.wastages) / (
							SUM(
								hf_data_master.issue_balance
							) + sum(hf_data_master.wastages)
						)
					) * 100,
					0
				),
				1
			)>allowable_wastage_rate,1,0) AS wastagePer,
			 UC.pk_id as UCs
		FROM
			locations AS UC
		INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
		INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
		WHERE
			UC.geo_level_id = 6
		AND warehouses.stakeholder_id = stakeholderid
		AND hf_data_master.reporting_start_date = reporting_date 
		AND hf_data_master.issue_balance IS NOT NULL
		AND hf_data_master.item_pack_size_id = item_id
		and UC.district_id=district_id  
GROUP BY  UC.pk_id 
UNION
SELECT
			if(ROUND(
				IFNULL(
					(
						sum(hf_data_master.wastages) / (
							SUM(
								hf_data_master.issue_balance
							) + sum(hf_data_master.wastages)
						)
					) * 100,
					0
				),
				1
			)>allowable_wastage_rate,1,0) AS wastagePer,
			 UC.pk_id as UCs
		FROM
			locations AS UC
		INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
		INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
		WHERE
			UC.geo_level_id = 6
		AND warehouses.stakeholder_id = stakeholderid
		AND hf_data_master.reporting_start_date = reporting_date 
		AND hf_data_master.issue_balance IS NOT NULL
		AND hf_data_master.item_pack_size_id = item_id
		and UC.district_id=district_id  
GROUP BY  UC.pk_id 
	) AS A;
			
	 SET retstr = CONCAT(wastage_uc,'|',total_ucs,'|',wastage_rate);
   RETURN(retstr);  
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for getMonthlyRcvQtyWH
-- ----------------------------
DROP FUNCTION IF EXISTS `getMonthlyRcvQtyWH`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `getMonthlyRcvQtyWH`(`in_month` int,`in_year` int,`in_item` int,`in_wh` int) RETURNS int(11)
    DETERMINISTIC
BEGIN
	DECLARE retval INTEGER;
SELECT
		IFNULL(SUM(ABS(stock_detail.quantity)),0) INTO retval 
	FROM
		stock_master
	INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
	INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
	WHERE
		stock_master.to_warehouse_id = in_wh
	AND stock_master.transaction_type_id = 1
	AND stock_master.transaction_date < DATE_ADD(CONCAT(in_year,'-',in_month,'-01'),INTERVAL 1 MONTH);
RETURN (retval);
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for getMosColor
-- ----------------------------
DROP FUNCTION IF EXISTS `getMosColor`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `getMosColor`(`in_id` float,`in_itemrec_id` int,`in_stkid` int,`in_lvlid` int) RETURNS varchar(100) CHARSET latin1
    DETERMINISTIC
BEGIN 
   DECLARE vName VARCHAR(100);
  SET vName = "#000000";
   
   -- check if mos  is a number
   IF (in_id  <> 'UNK') THEN
        SELECT mos_scale.color_code
            INTO  vName 
          FROM  mos_scale
         WHERE in_id  BETWEEN  mos_scale.scale_start  and  mos_scale.scale_end
         and mos_scale. item_id  = in_itemrec_id
         and mos_scale. geo_level_id  =  in_lvlid
         and mos_scale.stakeholder_id =  in_stkid
         LIMIT 1; 
  END IF;
   RETURN(vName);  
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for GetPicked
-- ----------------------------
DROP FUNCTION IF EXISTS `GetPicked`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `GetPicked`(stock_detail_id integer) RETURNS int(11)
    READS SQL DATA
    DETERMINISTIC
BEGIN
   DECLARE PickedQty INTEGER;
  
			SELECT
				IFNULL(SUM(placements.quantity),0) INTO PickedQty
			FROM
				placements
			WHERE
				placements.stock_detail_id = stock_detail_id
			AND placements.placement_transaction_type_id IN (115,116)
			LIMIT 1;
   RETURN(PickedQty);
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for GetPlaced
-- ----------------------------
DROP FUNCTION IF EXISTS `GetPlaced`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `GetPlaced`(stock_detail_id integer) RETURNS int(11)
    READS SQL DATA
    DETERMINISTIC
BEGIN
   DECLARE PlacedQty INTEGER;
  
			SELECT
				IFNULL(SUM(placements.quantity),0) INTO PlacedQty
			FROM
				placements
			WHERE
				placements.stock_detail_id = stock_detail_id
			AND placements.placement_transaction_type_id IN (114,116)
			LIMIT 1;
   RETURN(PlacedQty);
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for getStkItemCount
-- ----------------------------
DROP FUNCTION IF EXISTS `getStkItemCount`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `getStkItemCount`(`in_id` int) RETURNS int(11)
    DETERMINISTIC
BEGIN
     DECLARE retval  INTEGER;
   IF (in_id = 0) THEN
      SELECT count(*)
            INTO retval
          FROM  item_pack_sizes;
   ELSE
   --  SELECT count(*)
     --       INTO retval
       --   FROM  stakeholder_item_pack_sizes
        -- WHERE  stakeholder_item_pack_sizes.stakeholders_id =in_id;
				SET retval=1;
   END IF;
     RETURN(retval);
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for GetUnplacedBatches
-- ----------------------------
DROP FUNCTION IF EXISTS `GetUnplacedBatches`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `GetUnplacedBatches`(`item_id` int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE item_id INT;
	DECLARE batch_id INT;
  /*SELECT
	IFNULL(Sum(stock_detail.quantity),0) INTO item_id
	FROM
	stock_detail
	INNER JOIN stock_batch ON  stock_detail.stock_batch_id = stock_batch.pk_id
  INNER JOIN stock_master ON   stock_detail.stock_master_id = stock_master.pk_id
	WHERE
	stock_detail.adjustment_type = 1 AND
	stock_detail.stock_batch_id = batchId AND
	stock_batch.warehouse_id = whId;*/
	SELECT DISTINCT
	stock_batch_warehouses.pk_id INTO batch_id
FROM
	stock_batch_warehouses
INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
WHERE
	stakeholder_item_pack_sizes.item_pack_size_id = item_id;
  RETURN(batch_id);
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for GetVacdoses
-- ----------------------------
DROP FUNCTION IF EXISTS `GetVacdoses`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `GetVacdoses`(item_id integer) RETURNS int(11)
    READS SQL DATA
    DETERMINISTIC
BEGIN 
   DECLARE NoOfDoses INTEGER;
  
			SELECT
			IFNULL(pack_sizes.description,0) INTO NoOfDoses
			FROM
			item_pack_sizes
			INNER JOIN pack_sizes ON item_pack_sizes.pack_sizes_id = pack_sizes.pk_id
			WHERE
			item_pack_sizes.pk_id = item_id
			LIMIT 1;
   RETURN(NoOfDoses);  
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for getWarehouseType
-- ----------------------------
DROP FUNCTION IF EXISTS `getWarehouseType`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `getWarehouseType`(in_wh INTEGER) RETURNS int(11)
    DETERMINISTIC
BEGIN
	DECLARE warehouseType INTEGER;
	SELECT
		warehouses.warehouse_type_id INTO warehouseType
	FROM
		warehouses
	WHERE
		warehouses.pk_id = in_wh;
return warehouseType;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for ProductBalance
-- ----------------------------
DROP FUNCTION IF EXISTS `ProductBalance`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`203.99.49.122` FUNCTION `ProductBalance`(`productId` int,`trDate` date, `whId` int, `stkId` int, `locId` int) RETURNS bigint(20)
    READS SQL DATA
    DETERMINISTIC
BEGIN 
  DECLARE qty BIGINT;
	SELECT
	SUM(stock_detail.quantity) INTO qty
FROM
	stock_master
INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
/*INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id*/
WHERE
	stakeholder_item_pack_sizes.item_pack_size_id = productId
AND stock_batch_warehouses.warehouse_id = whId
AND DATE_FORMAT(
	stock_master.transaction_date,
	'%Y-%m-%d'
) <= trDate
AND stakeholder_item_pack_sizes.stakeholder_id = stkId;
/*AND placement_locations.location_id = locId;*/
  RETURN(qty);
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for ReceiveStockAtUC
-- ----------------------------
DROP FUNCTION IF EXISTS `ReceiveStockAtUC`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `ReceiveStockAtUC`(master_id int, trans_no char(50),batch_id int,bnumber char(50),item_pack_size_id int,wh_id int, countr int) RETURNS int(11)
    DETERMINISTIC
BEGIN
 DECLARE new_master_id INTEGER;
 DECLARE new_batch_id INTEGER;
 DECLARE in_month INTEGER;
 DECLARE in_year INTEGER;
 DECLARE in_item INTEGER;
 DECLARE in_wh INTEGER;
 DECLARE in_user INTEGER;
 DECLARE c DOUBLE DEFAULT 0;
 DECLARE batchId INTEGER;
 DECLARE cnt INTEGER;
SET in_user = 1;
INSERT INTO stock_master (
 stock_master.transaction_date,
 stock_master.transaction_number,
 stock_master.transaction_counter,
 stock_master.transaction_reference,
 stock_master.draft,
 stock_master.comments,
 stock_master.transaction_type_id,
 stock_master.from_warehouse_id,
 stock_master.to_warehouse_id,
 stock_master.parent_id,
 stock_master.campaign_id,
 stock_master.stakeholder_activity_id,
 stock_master.created_by,
 stock_master.created_date,
 stock_master.modified_by,
 stock_master.modified_date
) SELECT
 stock_master.transaction_date,
 trans_no,
 countr,
 stock_master.transaction_reference,
 0,
 stock_master.comments,
 1,
 stock_master.from_warehouse_id,
 stock_master.to_warehouse_id,
 stock_master.parent_id,
 stock_master.campaign_id,
 stock_master.stakeholder_activity_id,
 stock_master.created_by,
 stock_master.created_date,
 stock_master.modified_by,
 stock_master.modified_date
FROM
 stock_master
WHERE
 stock_master.pk_id = master_id;
SET new_master_id = LAST_INSERT_ID();
SELECT
 stock_batch_warehouses.pk_id,
 COUNT(stock_batch_warehouses.pk_id) INTO batchId,cnt
FROM
 stock_batch_warehouses
INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
WHERE
 stock_batch.number = bnumber
AND stakeholder_item_pack_sizes.item_pack_size_id = item_pack_size_id
AND stock_batch_warehouses.warehouse_id = wh_id;
IF(cnt=0) THEN
 INSERT INTO stock_batch_warehouses (
	stock_batch_warehouses.stock_batch_id,
  stock_batch_warehouses.warehouse_id,
  stock_batch_warehouses.quantity,
	stock_batch_warehouses.created_by,
	stock_batch_warehouses.created_date,
	stock_batch_warehouses.modified_by,
	stock_batch_warehouses.modified_date
 ) SELECT
	stock_batch_warehouses.stock_batch_id,
  wh_id,
  stock_batch_warehouses.quantity,
	in_user,
	NOW(),
	in_user,
	NOW()
 FROM
  stock_batch_warehouses
 WHERE stock_batch_warehouses.pk_id = batch_id;
 SET new_batch_id = LAST_INSERT_ID();
ELSE
SET new_batch_id = batchId;
END IF;
INSERT INTO stock_detail (
 stock_detail.quantity,
 stock_detail.`temporary`,
 stock_detail.vvm_stage,
 stock_detail.is_received,
 stock_detail.adjustment_type,
 stock_detail.stock_master_id,
 stock_detail.stock_batch_id,
 stock_detail.item_unit_id
) SELECT
 ABS(stock_detail.quantity),
 0,
 stock_detail.vvm_stage,
 1,
 1,
 new_master_id,
 new_batch_id,
 stock_detail.item_unit_id
FROM
 stock_detail
WHERE
 stock_detail.stock_master_id = master_id;
SELECT
MONTH(stock_master.transaction_date),
YEAR(stock_master.transaction_date),
stakeholder_item_pack_sizes.item_pack_size_id,
stock_master.to_warehouse_id,
stock_master.created_by
INTO in_month, in_year, in_item, in_wh, in_user
FROM
stock_master
INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_idstock_batch_warehouses
INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
WHERE
stock_detail.stock_master_id = new_master_id LIMIT 1;
SELECT REPUpdateData(in_month, in_year, in_item, in_wh, in_user) INTO c from DUAL;
SELECT AdjustQty(new_batch_id,wh_id) INTO c from DUAL;
 RETURN 1;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetAvailabilityRateStr
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetAvailabilityRateStr`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetAvailabilityRateStr`(`in_type` varchar(8),`in_month` int,`in_year` int,`in_item` varchar(8),`in_WF` varchar(1),`in_stk` int,`in_prov` int,`in_dist` varchar(8)) RETURNS varchar(20) CHARSET latin1
    DETERMINISTIC
BEGIN
DECLARE retval DECIMAL(5,2) ;
DECLARE numExptd INTEGER;
DECLARE numReptd INTEGER;
DECLARE retvalstr VARCHAR(5);
 IF (in_WF = 'F') THEN
     CASE
       WHEN (in_type ='N') THEN
 -- 1. Get total reports expected 
        SELECT count(warehouses.pk_id) * getStkItemCount(0)
            INTO numExptd
          FROM warehouses  INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
         WHERE stakeholders.geo_level_id =6;
      SELECT count( hf_data_master.warehouse_id)
          INTO numReptd
         FROM warehouses
         Inner Join hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				 INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
         WHERE hf_data_master.closing_balance > 0  and  MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year AND
							stakeholders.geo_level_id =6;
    WHEN (in_type ='P') THEN
        SELECT count(warehouses.pk_id)* getStkItemCount(in_item)
            INTO numExptd
          FROM warehouses INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
         WHERE stakeholders.geo_level_id = 6;
				SELECT count( hf_data_master.warehouse_id)
          INTO numReptd
         FROM warehouses
         Inner Join hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				 INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
         WHERE hf_data_master.closing_balance > 0 and hf_data_master.item_pack_size_id=in_item
         AND MONTH(hf_data_master.reporting_start_date) = in_month 
				 AND YEAR(hf_data_master.reporting_start_date) = in_year 
				 AND stakeholders.geo_level_id = 6;   
  WHEN (in_type ='D') THEN
        SELECT count(warehouses.pk_id)* getStkItemCount(in_item)
            INTO numExptd
          FROM warehouses INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
         WHERE stakeholders.geo_level_id = 6;
				SELECT count( hf_data_master.warehouse_id)
          INTO numReptd
         FROM warehouses
         Inner Join hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				 INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
         WHERE hf_data_master.closing_balance > 0 and hf_data_master.item_pack_size_id=in_item 
         AND MONTH(hf_data_master.reporting_start_date) = in_month 
				 AND YEAR(hf_data_master.reporting_start_date) = in_year 
				 AND stakeholders.geo_level_id = 6;   
  END CASE;
 ELSE
    CASE
        WHEN (in_type ='N') THEN
       --  1. Get total reports expected 
        SELECT count(warehouses.pk_id)* getStkItemCount(0)
            INTO numExptd
          FROM warehouses INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
         WHERE stakeholders.geo_level_id = 4 ;
      SELECT count( hf_data_master.warehouse_id)
          INTO numReptd
         FROM warehouses
         Inner Join hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				 INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
         WHERE hf_data_master.closing_balance > 0 and MONTH(hf_data_master.reporting_start_date) = in_month 
					AND YEAR(hf_data_master.reporting_start_date) = in_year 
					AND stakeholders.geo_level_id = 4;
 WHEN (in_type = 'P') THEN
       SELECT count(warehouses.pk_id)* getStkItemCount(in_item)
            INTO numExptd
          FROM warehouses INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
         WHERE stakeholders.geo_level_id = 4;
			
				SELECT count( hf_data_master.warehouse_id)
          INTO numReptd
         FROM warehouses
         Inner Join hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				 INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
         WHERE hf_data_master.closing_balance > 0 and hf_data_master.item_pack_size_id=in_item
					AND MONTH(hf_data_master.reporting_start_date) = in_month 
					AND YEAR(hf_data_master.reporting_start_date) = in_year 
					AND stakeholders.geo_level_id = 4; 
 WHEN (in_type = 'D') THEN
       SELECT count(warehouses.pk_id)* getStkItemCount(in_item)
            INTO numExptd
          FROM warehouses INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
         WHERE stakeholders.geo_level_id = 4;
			
			SELECT count( hf_data_master.warehouse_id)
          INTO numReptd
         FROM warehouses
         Inner Join hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				 INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
         WHERE hf_data_master.closing_balance > 0 and hf_data_master.item_pack_size_id=in_item 
					AND MONTH(hf_data_master.reporting_start_date) = in_month 
					AND YEAR(hf_data_master.reporting_start_date) = in_year 
					AND stakeholders.geo_level_id = 4; 
  END CASE;
END IF;
    SET numExptd = IFNULL(numExptd,0);
    SET numReptd = IFNULL(numReptd,0);
		IF (numExptd > 0 and numReptd > 0) THEN
      set retval = numReptd / numExptd * 100;
      SET retvalstr = round(retval);
		else
       SET retvalstr = 'NA';
    end if;
     /*set retvalstr = CONCAT(in_type,',',in_id,',',in_month,',',in_year,',',in_item,',',in_WF,',',in_stk,',',in_prov);*/
 RETURN(retvalstr);
    END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetCB
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetCB`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetCB`(`in_type` varchar(8),`in_month` int,`in_year` int,`in_item` varchar(20),`in_stk` int,`in_prov` int,`in_dist` varchar(8)) RETURNS double
    DETERMINISTIC
BEGIN
 
  DECLARE retval DOUBLE DEFAULT 0;
    -- Declare '_val' variables to read in each record from the cursor
 
 --   stakeholders, province, district flags
/*
IF INSTR(in_type,'1') > 0 THEN
 SET wflag = 'CWH';
 SET in_type = REPLACE(in_type,1,'');
END IF;
IF INSTR(in_type,'2') > 0 THEN
 SET wflag = 'PPIU';
 SET in_type = REPLACE(in_type,2,'');
END IF;
*/
   CASE
  WHEN (in_type = 'N') THEN
		SELECT sum(closing_balance) INTO retval from (
    
    SELECT
			sum(hf_data_master.closing_balance) as closing_balance
		FROM
			warehouses
		INNER JOIN  hf_data_master  ON  warehouses.pk_id = hf_data_master.warehouse_id
		INNER JOIN stakeholders ON   warehouses.stakeholder_office_id=  stakeholders.pk_id
		WHERE
			hf_data_master.item_pack_size_id = in_item
		AND    MONTH(hf_data_master.reporting_start_date) = in_month
		AND   YEAR(hf_data_master.reporting_start_date) = in_year
		AND    stakeholders.geo_level_id = 6 ) A;
WHEN (in_type = 'ND') THEN
		SELECT
	SUM(A.total)  INTO retval
FROM
	(
		SELECT
			ABS(SUM(stock_detail.quantity)) * item_pack_sizes.number_of_doses AS total,
			warehouses.district_id
		FROM
			stock_master
		INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
		INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
		INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
		INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
		INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
		INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
		INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
		INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
		INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
		WHERE
			warehouses.stakeholder_office_id IN  (4,5)
		
		AND DATE_FORMAT(
			stock_master.transaction_date,
			'%Y-%m'
		) <= CONCAT(
			in_year,
			'-',
			LPAD(in_month, 2, '0')
		)
		AND item_pack_sizes.pk_id = in_item) A;
  WHEN (in_type = 'NW') THEN
		SELECT sum(closing_balance) INTO retval from (
     SELECT
			sum(hf_data_master.closing_balance) as closing_balance
		FROM
			warehouses
		INNER JOIN   hf_data_master   ON    warehouses.pk_id  =   hf_data_master.warehouse_id 
		INNER JOIN  stakeholders  ON   warehouses.stakeholder_office_id =   stakeholders.pk_id
		WHERE
			 hf_data_master.item_pack_size_id = in_item
		AND MONTH(hf_data_master.reporting_start_date) = in_month
		AND YEAR(hf_data_master.reporting_start_date) = in_year
		AND   stakeholders.geo_level_id = 1 
    UNION
    	SELECT
			sum(hf_data_master.closing_balance) as closing_balance
		FROM
			warehouses
		INNER JOIN   hf_data_master   ON    warehouses.pk_id  =   hf_data_master.warehouse_id 
		INNER JOIN  stakeholders  ON   warehouses.stakeholder_office_id =   stakeholders.pk_id
		WHERE
			 hf_data_master.item_pack_size_id = in_item
		AND MONTH(hf_data_master.reporting_start_date) = in_month
		AND YEAR(hf_data_master.reporting_start_date) = in_year
		AND   stakeholders.geo_level_id = 1 ) A;
  WHEN (in_type = 'P') THEN
   SELECT sum(closing_balance) INTO retval from (
   SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.province_id = in_prov 
		AND   stakeholders.geo_level_id = 6)A;
  WHEN (in_type = 'POBRA') THEN
   SELECT opening_balance + adjustments + received_balance INTO retval from (
   SELECT sum(hf_data_master.opening_balance) AS opening_balance,
			sum(hf_data_master.adjustments) AS adjustments,
			sum(hf_data_master.received_balance) AS received_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.province_id = in_prov 
		AND   stakeholders.geo_level_id = 6)A;
 WHEN (in_type = 'PD') THEN
  SELECT
	SUM(A.total)  INTO retval
FROM
	(
		SELECT
			ABS(SUM(stock_detail.quantity)) * item_pack_sizes.number_of_doses AS total,
			warehouses.district_id
		FROM
			stock_master
		INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
		INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
		INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
		INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
		INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
		INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
		INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
		INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
		INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
		WHERE
			item_pack_sizes.item_category_id <> 3
		AND warehouses. STATUS = 1
		AND warehouses.stakeholder_office_id IN  (4,5)
		
		AND DATE_FORMAT(
			stock_master.transaction_date,
			'%Y-%m'
		) <= CONCAT(
			in_year,
			'-',
			LPAD(in_month, 2, '0')
		)
 AND  warehouses.province_id = in_prov 
		AND item_pack_sizes.pk_id = in_item) A;
 WHEN (in_type = 'PW') THEN
Select sum(closing_balance) INTO retval from
  ( SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders  ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.province_id = in_prov 
		AND   stakeholders.geo_level_id=2
    UNION
    SELECT sum(hf_data_master.closing_balance) as closing_balance
    FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders  ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.province_id = in_prov 
		AND   stakeholders.geo_level_id=2 ) A;
WHEN (in_type = 'V') THEN
SELECT sum(closing_balance) INTO retval from   
(SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.district_id  in (SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.geo_level_id = 4
																				AND   locations.parent_id = in_prov)
		AND   stakeholders.geo_level_id>=3
   UNION
   SELECT sum(hf_data_master.closing_balance)  as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.district_id  in (SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.geo_level_id = 4
																				AND   locations.parent_id = in_prov)
		AND   stakeholders.geo_level_id>=3 ) A ;
  
WHEN (in_type = 'D') THEN
SELECT
	SUM(A.total) INTO retval
	
FROM
	(
		SELECT
			ABS(SUM(stock_detail.quantity)) * item_pack_sizes.number_of_doses AS total,
			warehouses.district_id
		FROM
			stock_master
		INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
		INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
		INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
		INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
		INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
		INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
		INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
		INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
		INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
		WHERE
			item_pack_sizes.item_category_id <> 3
		AND warehouses. STATUS = 1
		AND warehouses.stakeholder_office_id > 3
		AND warehouses.stakeholder_office_id < 6
		AND DATE_FORMAT(
			stock_master.transaction_date,
			'%Y-%m'
		) <= CONCAT(
			in_year,
			'-',
			LPAD(in_month, 2, '0')
		)
		AND item_pack_sizes.pk_id = in_item
		AND warehouses.district_id = in_prov
		UNION
			SELECT
				SUM(
					hf_data_master.closing_balance
				) AS total,
				warehouses.district_id
			FROM
				item_pack_sizes
			INNER JOIN hf_data_master ON item_pack_sizes.pk_id = hf_data_master.item_pack_size_id
			INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
				item_pack_sizes.item_category_id <> 3
			AND warehouses. STATUS = 1
			AND stakeholders.geo_level_id = 6
			AND DATE_FORMAT(
				hf_data_master.reporting_start_date,
				'%Y-%m'
			) = CONCAT(
				in_year,
				'-',
				LPAD(in_month, 2, '0')
			)
			AND hf_data_master.item_pack_size_id = in_item
			AND warehouses.district_id = in_prov
	) A; 
WHEN (in_type = 'DP') THEN
   SELECT sum(closing_balance) INTO retval from
  (SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.province_id = in_prov 
		AND   stakeholders.geo_level_id = 4
    UNION 
     SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.province_id = in_prov 
		AND   stakeholders.geo_level_id = 4 ) A ;
WHEN (in_type = 'DW') THEN
   SELECT sum(closing_balance) INTO retval from
  (SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND  MONTH(hf_data_master.reporting_start_date) = in_month 
		AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.district_id = in_prov 
		AND   stakeholders.geo_level_id = 4
    UNION 
    SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND  MONTH(hf_data_master.reporting_start_date) = in_month 
		AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.district_id = in_prov 
		AND   stakeholders.geo_level_id = 4) A;
WHEN (in_type = 'H') THEN
   SELECT sum(closing_balance) INTO retval from 
   (SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND  MONTH(hf_data_master.reporting_start_date) = in_month 
		AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.location_id    in (
																				SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.parent_id = in_prov)   
		AND   stakeholders.geo_level_id>=5 
   UNION
   SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND  MONTH(hf_data_master.reporting_start_date) = in_month 
		AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.location_id    in (
																				SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.parent_id = in_prov)   
		AND   stakeholders.geo_level_id>=5) A;
WHEN (in_type = 'HW') THEN
   SELECT sum(closing_balance) INTO retval 
from 
(SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
		AND warehouses.location_id = in_prov
		AND stakeholders.geo_level_id=5
   UNION
   SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
		AND warehouses.location_id = in_prov
		AND stakeholders.geo_level_id=5) A;
WHEN (in_type = 'U') THEN
    SELECT
	
	SUM(D.SOH + (
		SELECT
			IFNULL(
				SUM(stock_detail.quantity),
				0
			)
		FROM
			stock_master
		INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
		INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
		INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
		INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
		INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
		INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
		INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
		WHERE
		 DATE_FORMAT(
			stock_master.transaction_date,
			'%Y-%m-%d'
		) <= CONCAT(in_year, '-', LPAD(in_month, 2, '0'), '-01')
		AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
		AND stock_detail.is_received != 0
		AND warehouses.location_id = D.pk_id
	)) INTO retval
FROM
	(
		SELECT
			locations.pk_id,
			locations.location_name,
			SUM(
				IF (
					stakeholders.geo_level_id = 6,
					hf_data_master.issue_balance,
					0
				)
			) AS Vaccinated,
			SUM(
				hf_data_master.closing_balance
			) AS SOH
		FROM
			warehouses
		INNER JOIN locations ON warehouses.location_id = locations.pk_id
		INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
		INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		WHERE
			warehouses.district_id = in_prov
		AND warehouses.stakeholder_office_id = 6
		AND DATE_FORMAT(
			hf_data_master.reporting_start_date,
			'%Y-%m-%d'
		) = CONCAT(in_year, '-', LPAD(in_month, 2, '0'), '-01')
		AND hf_data_master.item_pack_size_id = in_item
GROUP BY
				warehouses.location_id
	) D;
WHEN (in_type = 'UP') THEN
   SELECT sum(closing_balance) INTO retval FROM
  (SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND  MONTH(hf_data_master.reporting_start_date) = in_month 
		AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.province_id= in_prov 
		AND   stakeholders.geo_level_id=6  
   UNION 
SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND  MONTH(hf_data_master.reporting_start_date) = in_month 
		AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.province_id= in_prov 
		AND   stakeholders.geo_level_id=6        
 ) A;
WHEN (in_type = 'UD') THEN
  SELECT sum(closing_balance) INTO retval FROM
( SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
   warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
   Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
warehouses. STATUS = 1
AND hf_data_master.item_pack_size_id = in_item 
  AND  MONTH(hf_data_master.reporting_start_date) = in_month 
  AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.district_id  = in_prov 
  AND   stakeholders.geo_level_id=6 
 )A;
WHEN (in_type = 'UW') THEN
   SELECT sum(closing_balance) INTO retval from
   (SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND  MONTH(hf_data_master.reporting_start_date) = in_month 
		AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.pk_id  = in_prov 
		AND   stakeholders.geo_level_id=6
    UNION 
    SELECT sum(hf_data_master.closing_balance) as closing_balance
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND  MONTH(hf_data_master.reporting_start_date) = in_month 
		AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.pk_id  = in_prov 
		AND   stakeholders.geo_level_id=6)A  ;
/*
   WHEN (in_type = 'WS') THEN
 -- Aggreate warehouse closing balance for selected  stakeholders
    IF (wflag='CWH') THEN
			SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk  AND   stakeholders.stakeholder_type_id=0 and   stakeholders.geo_level_id=1;
    ELSE
     	SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
        WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk and   stakeholders.geo_level_id=1;
    END IF;
  WHEN (in_type = 'WSP') THEN
			SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
        WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk AND  warehouses.province_id = in_prov and   stakeholders.geo_level_id>=2;
 WHEN (in_type = 'WPD') THEN
  SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.location_id   = in_dist and   stakeholders.geo_level_id>=3;
  WHEN (in_type = 'WSPD') THEN
   SELECT       sum(hf_data_master.closing_balance)
  INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
        WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk AND  warehouses.location_id   = in_dist and   stakeholders.geo_level_id=3;
 WHEN (in_type = 'T') THEN
   SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
        WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND   stakeholders.stakeholder_type_id=0;
   WHEN (in_type = 'TS') THEN
   SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk;
  
  WHEN (in_type = 'TSP') THEN
  SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk AND  warehouses.province_id = in_prov
         AND  (stakeholders.geo_level_id>=2);
 WHEN (in_type = 'TPD') THEN
  SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.location_id   = in_dist
         AND   stakeholders.stakeholder_type_id=0 and (  stakeholders.geo_level_id>=3);
WHEN (in_type = 'XPD') THEN
  SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.location_id   = in_dist
         AND   stakeholders.stakeholder_type_id=1 and (stakeholders.geo_level_id=3);
  WHEN (in_type = 'TSPD') THEN
   SELECT '100' as a
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk  AND  warehouses.location_id   = in_dist
         AND   stakeholders.stakeholder_type_id=0 and (  stakeholders.geo_level_id>=3) limit 1;
*/
 END CASE;
 -- SET retval = IFNULL(retval,0);
 return retval;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetCB_copy
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetCB_copy`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetCB_copy`(`in_type` varchar(8),`in_month` int,`in_year` int,`in_item` varchar(20),`in_stk` int,`in_prov` int,`in_dist` varchar(8)) RETURNS double
    DETERMINISTIC
BEGIN
 
  DECLARE retval DOUBLE DEFAULT 0;
    -- Declare '_val' variables to read in each record from the cursor
 
 --   stakeholders, province, district flags
/*
IF INSTR(in_type,'1') > 0 THEN
 SET wflag = 'CWH';
 SET in_type = REPLACE(in_type,1,'');
END IF;
IF INSTR(in_type,'2') > 0 THEN
 SET wflag = 'PPIU';
 SET in_type = REPLACE(in_type,2,'');
END IF;
*/
   CASE
  WHEN (in_type = 'N') THEN
		SELECT
			sum(hf_data_master.closing_balance) INTO retval
		FROM
			warehouses
		INNER JOIN  hf_data_master  ON  warehouses.pk_id = hf_data_master.warehouse_id
		INNER JOIN stakeholders ON   warehouses.stakeholder_office_id=  stakeholders.pk_id
		WHERE
			hf_data_master.item_pack_size_id = in_item
		AND    MONTH(hf_data_master.reporting_start_date) = in_month
		AND   YEAR(hf_data_master.reporting_start_date) = in_year
		AND    stakeholders.geo_level_id >= 1;
  WHEN (in_type = 'NW') THEN
		SELECT
			sum(hf_data_master.closing_balance) INTO retval
		FROM
			warehouses
		INNER JOIN   hf_data_master   ON    warehouses.pk_id  =   hf_data_master.warehouse_id 
		INNER JOIN  stakeholders  ON   warehouses.stakeholder_office_id =   stakeholders.pk_id
		WHERE
			 hf_data_master.item_pack_size_id = in_item
		AND MONTH(hf_data_master.reporting_start_date) = in_month
		AND YEAR(hf_data_master.reporting_start_date) = in_year
		AND   stakeholders.geo_level_id = 1;
  WHEN (in_type = 'P') THEN
   SELECT sum(hf_data_master.closing_balance) INTO retval
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.province_id = in_prov 
		AND   stakeholders.geo_level_id>=2;
 WHEN (in_type = 'PW') THEN
   SELECT sum(hf_data_master.closing_balance) INTO retval
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders  ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.province_id = in_prov 
		AND   stakeholders.geo_level_id=2;
WHEN (in_type = 'V') THEN
   SELECT sum(hf_data_master.closing_balance) INTO retval
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.district_id  in (SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.geo_level_id = 4
																				AND   locations.parent_id = in_prov)
		AND   stakeholders.geo_level_id>=3;
  
WHEN (in_type = 'D') THEN
   SELECT sum(hf_data_master.closing_balance) INTO retval
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.district_id = in_prov 
		AND   stakeholders.geo_level_id >= 4;
WHEN (in_type = 'DP') THEN
   SELECT sum(hf_data_master.closing_balance) INTO retval
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.province_id = in_prov 
		AND   stakeholders.geo_level_id = 4;
WHEN (in_type = 'DW') THEN
   SELECT sum(hf_data_master.closing_balance) INTO retval
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND  MONTH(hf_data_master.reporting_start_date) = in_month 
		AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.district_id = in_prov 
		AND   stakeholders.geo_level_id = 4;
WHEN (in_type = 'H') THEN
   SELECT sum(hf_data_master.closing_balance) INTO retval
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND  MONTH(hf_data_master.reporting_start_date) = in_month 
		AND  YEAR(hf_data_master.reporting_start_date) = in_year
    
AND  warehouses.location_id    in (
																				SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.parent_id = in_prov)   
		AND   stakeholders.geo_level_id>=5;
WHEN (in_type = 'U') THEN
   SELECT sum(hf_data_master.closing_balance) INTO retval
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND MONTH(hf_data_master.reporting_start_date) = in_month 
		AND YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.location_id   = in_prov 
		AND   stakeholders.geo_level_id=6;
WHEN (in_type = 'UP') THEN
   SELECT sum(hf_data_master.closing_balance) INTO retval
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND  MONTH(hf_data_master.reporting_start_date) = in_month 
		AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.province_id= in_prov 
		AND   stakeholders.geo_level_id=6;
WHEN (in_type = 'UD') THEN
   SELECT sum(hf_data_master.closing_balance) INTO retval
   FROM 
   warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
   Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
    hf_data_master.item_pack_size_id = in_item 
  AND  MONTH(hf_data_master.reporting_start_date) = in_month 
  AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.district_id  = in_prov 
  AND   stakeholders.geo_level_id=6;
WHEN (in_type = 'UW') THEN
   SELECT sum(hf_data_master.closing_balance) INTO retval
   FROM 
			warehouses
   Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 	 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
   WHERE 
			 hf_data_master.item_pack_size_id = in_item 
		AND  MONTH(hf_data_master.reporting_start_date) = in_month 
		AND  YEAR(hf_data_master.reporting_start_date) = in_year
    AND  warehouses.pk_id  = in_prov 
		AND   stakeholders.geo_level_id=6;
/*
   WHEN (in_type = 'WS') THEN
 -- Aggreate warehouse closing balance for selected  stakeholders
    IF (wflag='CWH') THEN
			SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk  AND   stakeholders.stakeholder_type_id=0 and   stakeholders.geo_level_id=1;
    ELSE
     	SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
        WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk and   stakeholders.geo_level_id=1;
    END IF;
  WHEN (in_type = 'WSP') THEN
			SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
        WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk AND  warehouses.province_id = in_prov and   stakeholders.geo_level_id>=2;
 WHEN (in_type = 'WPD') THEN
  SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.location_id   = in_dist and   stakeholders.geo_level_id>=3;
  WHEN (in_type = 'WSPD') THEN
   SELECT       sum(hf_data_master.closing_balance)
  INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
        WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk AND  warehouses.location_id   = in_dist and   stakeholders.geo_level_id=3;
 WHEN (in_type = 'T') THEN
   SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
 				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
        WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND   stakeholders.stakeholder_type_id=0;
   WHEN (in_type = 'TS') THEN
   SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk;
  
  WHEN (in_type = 'TSP') THEN
  SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk AND  warehouses.province_id = in_prov
         AND  (stakeholders.geo_level_id>=2);
 WHEN (in_type = 'TPD') THEN
  SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.location_id   = in_dist
         AND   stakeholders.stakeholder_type_id=0 and (  stakeholders.geo_level_id>=3);
WHEN (in_type = 'XPD') THEN
  SELECT sum(hf_data_master.closing_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.location_id   = in_dist
         AND   stakeholders.stakeholder_type_id=1 and (stakeholders.geo_level_id=3);
  WHEN (in_type = 'TSPD') THEN
   SELECT '100' as a
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.stakeholder_id   = in_stk  AND  warehouses.location_id   = in_dist
         AND   stakeholders.stakeholder_type_id=0 and (  stakeholders.geo_level_id>=3) limit 1;
*/
 END CASE;
 -- SET retval = IFNULL(retval,0);
 return retval;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetCBWHData
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetCBWHData`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetCBWHData`(in_month INTEGER, in_year INTEGER,  in_item INTEGER, in_wh INTEGER) RETURNS int(20)
    READS SQL DATA
    DETERMINISTIC
BEGIN
	DECLARE retval INTEGER;
	DECLARE receive INTEGER;
	DECLARE issue INTEGER;
	DECLARE adjustment INTEGER;
	SELECT
		IFNULL(SUM(ABS(stock_detail.quantity)),0) INTO receive 
	FROM
		stock_master
	INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
	INNER JOIN stock_batch_warehouses ON   stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
  INNER JOIN stock_batch ON   stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
  INNER JOIN pack_info ON   stock_batch.pack_info_id = pack_info.pk_id
  INNER JOIN stakeholder_item_pack_sizes ON   pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
	
	WHERE
		stock_master.to_warehouse_id = in_wh
	AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
	AND stock_master.transaction_type_id = 1 
	AND stock_master.transaction_date < DATE_ADD(CONCAT(in_year,'-',in_month,'-01'),INTERVAL 1 MONTH);
  SELECT
		IFNULL(SUM(ABS(stock_detail.quantity)),0) INTO issue 
	FROM
		stock_master
	INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
	INNER JOIN stock_batch_warehouses ON   stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
  INNER JOIN stock_batch ON   stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
  INNER JOIN pack_info ON   stock_batch.pack_info_id = pack_info.pk_id
  INNER JOIN stakeholder_item_pack_sizes ON   pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
	
	WHERE
		stock_master.from_warehouse_id = in_wh
	AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
  AND stock_master.transaction_type_id = 2
	AND stock_master.transaction_date < DATE_ADD(CONCAT(in_year,'-',in_month,'-01'),INTERVAL 1 MONTH);
  SELECT
		IFNULL(SUM(stock_detail.quantity),0) INTO adjustment
	FROM
		stock_master
	INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
	INNER JOIN stock_batch_warehouses ON   stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
  INNER JOIN stock_batch ON   stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
  INNER JOIN pack_info ON   stock_batch.pack_info_id = pack_info.pk_id
  INNER JOIN stakeholder_item_pack_sizes ON   pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
	
	WHERE
		stock_master.from_warehouse_id = in_wh
	AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
	AND stock_master.transaction_type_id > 2
	AND stock_master.transaction_date < DATE_ADD(CONCAT(in_year,'-',in_month,'-01'),INTERVAL 1 MONTH);
  SET retval = (receive + adjustment) - issue;
 
	RETURN (retval);
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetConsumption
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetConsumption`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetConsumption`(`in_month` int,`in_year` int,`in_item` int,`in_stk` int,`in_loc_level` varchar(8),`in_loc` int) RETURNS varchar(10) CHARSET latin1
    DETERMINISTIC
BEGIN
/* Written by Wasif Raza Mirza - Oct 13 */
DECLARE retval DOUBLE DEFAULT 0;
/* consumption is present on what level: in vLMIS it is on UC level which is 6th level*/
DECLARE c_level INTEGER DEFAULT 6; 
CASE 
WHEN (in_loc_level = 'N') THEN
  /*  Aggreate National Consumption */
   SELECT sum(issue_balance) INTO retval from (
         SELECT sum( hf_data_master.issue_balance) as issue_balance
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item
         AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
         AND MONTH(hf_data_master.reporting_start_date) = in_month 
         AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND   stakeholders.geo_level_id=c_level
         UNION 
         SELECT sum( hf_data_master.issue_balance) as issue_balance
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item
         AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
         AND MONTH(hf_data_master.reporting_start_date) = in_month 
         AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND   stakeholders.geo_level_id=c_level) A ;
WHEN (in_loc_level  = 'P') THEN
/*Aggreate Consumption for selected province*/
  SELECT sum(issue_balance) INTO retval FROM
   ( SELECT sum( hf_data_master.issue_balance) as issue_balance
          
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.province_id = in_loc
         AND   stakeholders.geo_level_id=c_level
   UNION
   SELECT sum( hf_data_master.issue_balance) as issue_balance
          
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.province_id = in_loc
         AND   stakeholders.geo_level_id=c_level) A;
WHEN (in_loc_level  = 'UP') THEN
/*Aggreate Consumption for selected province*/
 SELECT sum(issue_balance) INTO retval FROM
  ( SELECT sum( hf_data_master.issue_balance) as issue_balance
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.province_id = in_loc
         AND   stakeholders.geo_level_id=c_level
    UNION 
   SELECT sum( hf_data_master.issue_balance) as issue_balance
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.province_id = in_loc
         AND   stakeholders.geo_level_id=c_level ) A;
 WHEN (in_loc_level  = 'V') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum(issue_balance) INTO retval FROM
(SELECT sum( hf_data_master.issue_balance) as issue_balance
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.district_id in (SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.geo_level_id = 4
																				AND   locations.parent_id = in_loc)
         AND   stakeholders.geo_level_id=c_level
 UNION 
   SELECT sum( hf_data_master.issue_balance) as issue_balance
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.district_id in (SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.geo_level_id = 4
																				AND   locations.parent_id = in_loc)
         AND   stakeholders.geo_level_id=c_level) A;
WHEN (in_loc_level  = 'D') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum(issue_balance) INTO retval FROM
       (SELECT sum( hf_data_master.issue_balance) as issue_balance
        
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders  ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.district_id = in_loc
         AND   stakeholders.geo_level_id=c_level
   UNION  
   SELECT sum( hf_data_master.issue_balance) as issue_balance
        
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders  ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.district_id = in_loc
         AND   stakeholders.geo_level_id=c_level) A;
WHEN (in_loc_level  = 'DW') THEN
/*Aggreate Consumption for selected province*/
SELECT sum(issue_balance) INTO retval from
   (SELECT sum( hf_data_master.issue_balance) as issue_balance
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders  ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.district_id = in_loc
         AND   stakeholders.geo_level_id=c_level
         UNION 
         SELECT sum( hf_data_master.issue_balance) as issue_balance
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders  ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.district_id = in_loc
         AND   stakeholders.geo_level_id=c_level ) A  ;
WHEN (in_loc_level  = 'DP') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum(issue_balance) INTO retval FROM
   (SELECT sum( hf_data_master.issue_balance) as issue_balance
         
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.province_id = in_loc
         AND   stakeholders.geo_level_id=c_level
    UNION 
   SELECT sum( hf_data_master.issue_balance) as issue_balance
          
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.province_id = in_loc
         AND   stakeholders.geo_level_id=c_level)A ;
 WHEN (in_loc_level  = 'H') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum(issue_balance) INTO retval FROM
   (SELECT sum( hf_data_master.issue_balance) as issue_balance
        
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.location_id   in (
																				SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.parent_id = in_loc
																			)
         AND   stakeholders.geo_level_id=c_level
   UNION 
SELECT sum( hf_data_master.issue_balance) as issue_balance
   
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.location_id   in (
																				SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.parent_id = in_loc
																			)
         AND   stakeholders.geo_level_id=c_level) A  ;
WHEN (in_loc_level  = 'U') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum(issue_balance) INTO retval FROM
(SELECT sum( hf_data_master.issue_balance) as issue_balance
          
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders  ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.location_id   = in_loc
         AND   stakeholders.geo_level_id=c_level
  UNION 
 SELECT sum( hf_data_master.issue_balance) as issue_balance
         
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders  ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.location_id   = in_loc
         AND   stakeholders.geo_level_id=c_level) A;
WHEN (in_loc_level  = 'UW') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum(issue_balance) INTO retval FROM
(SELECT sum( hf_data_master.issue_balance) as issue_balance
        
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  = in_loc 
         AND   stakeholders.geo_level_id=c_level
UNION 
 SELECT sum( hf_data_master.issue_balance) as issue_balance
      
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  = in_loc 
         AND   stakeholders.geo_level_id=c_level) A;
 ELSE 
		BEGIN 
		END;
 END CASE;
SET retval = ROUND(retval,0);
 return retval;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetConsumptionAVG
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetConsumptionAVG`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetConsumptionAVG`(`in_type` varchar(8),`in_month` int,`in_year` int,`in_item` varchar(20),`in_stk` varchar(255),`in_prov` int,`in_dist` varchar(8)) RETURNS double
    DETERMINISTIC
BEGIN
  DECLARE retval DOUBLE DEFAULT 0;
  DECLARE lmt INTEGER;
 SET lmt = UTILgetLimitParam(in_month,in_year);
IF lmt > 2 THEN
	SET lmt = 3;
ELSEIF lmt < 1 THEN
	SET lmt = 1;
END IF;
 -- Declare the cursor
 
  CASE
  WHEN (in_type = 'N') THEN
	SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			
         SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date 
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
 WHEN (in_type = 'NW') THEN
	SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			
       SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
  WHEN (in_type = 'P') THEN
	SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			
       SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.province_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date   
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
  WHEN (in_type = 'PW') THEN
	SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			
         SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.province_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date 
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'UP') THEN
	SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			
       SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.province_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'V' ) THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			
			   SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.district_id in (SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.geo_level_id = 4
																				AND   locations.parent_id = in_prov) and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date  
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'D' ) THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
		
       SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.district_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'DW') THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			
         SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.district_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'DU') THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			
         SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.district_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'DP') THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			
         SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.province_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'H' ) THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
		
SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.location_id   in (
																				SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.parent_id = in_prov) 
								and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
       
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'U' ) THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
		
      SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.location_id   = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'UW' ) THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			
     SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.pk_id  = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
 END CASE; 
 -- SET retval = IFNULL(retval,0);
 return retval;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetConsumptionAVGMap
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetConsumptionAVGMap`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetConsumptionAVGMap`(`in_type` varchar(8),`in_month` int,`in_year` int,`in_item` varchar(20),`in_stk` varchar(255),`in_prov` int,`in_dist` varchar(8)) RETURNS double
    DETERMINISTIC
BEGIN
  DECLARE retval DOUBLE DEFAULT 0;
  DECLARE lmt INTEGER;
 SET lmt = UTILgetLimitParam(in_month,in_year);
IF lmt > 2 THEN
	SET lmt = 3;
ELSEIF lmt < 1 THEN
	SET lmt = 1;
END IF;
 -- Declare the cursor
 
  CASE
  WHEN (in_type = 'N') THEN
	SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
 WHEN (in_type = 'NW') THEN
	SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
  WHEN (in_type = 'P') THEN
	SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item AND  warehouses.province_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
  WHEN (in_type = 'PW') THEN
	SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item AND  warehouses.province_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'UP') THEN
	SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item AND  warehouses.province_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'V' ) THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.district_id in (SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.geo_level_id = 4
																				AND   locations.parent_id = in_prov) and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'D' ) THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.district_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'DW' ) THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.district_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'DP' ) THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.province_id = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'H' ) THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.location_id   in (
																				SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.parent_id = in_prov) 
								and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'U' ) THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.location_id   = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
WHEN (in_type = 'UW' ) THEN
SELECT AVG(csum) INTO retval FROM (
	SELECT Reporting_start_date, csum FROM (
			SELECT  Reporting_start_date,sum( hf_data_master.issue_balance) As csum
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND  warehouses.pk_id  = in_prov and   stakeholders.geo_level_id=6
         GROUP BY Reporting_start_date
			) As A
		WHERE csum > 0
		AND Reporting_start_date <= CONCAT(in_year,CONCAT('-',in_month),'-1')
		ORDER BY Reporting_start_date DESC
		LIMIT lmt
	) As B;
 END CASE; 
 -- SET retval = IFNULL(retval,0);
 return retval;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetData
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetData`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetData`(`in_col` varchar(255),`in_rg` varchar(1),`in_type` varchar(8),`in_month` int,`in_year` int,`in_item` int,`in_stk` int,`in_prov` int,`in_dist` varchar(8)) RETURNS varchar(255) CHARSET latin1
    DETERMINISTIC
BEGIN
/*
in_col
 C - Consumption
 A - Average Monthly Consumption
 B - Closing Balance
 M - Month of Stock
 Y - Couple Year Protection
in_type
 F - field
 W - warehouse
 T - total
 S -  stakeholders
 P - province
 D - district
in_rg;
 G - graph
 N - national
 S -  stakeholders
 P - Provincial
 D - district
 X - Private Group
*/
  DECLARE retstr VARCHAR(255);
  DECLARE tmp VARCHAR(255);
 /* report variables */
  DECLARE  c DOUBLE DEFAULT 0;
  DECLARE  a DOUBLE DEFAULT 0;
  DECLARE  b DOUBLE DEFAULT 0;
  DECLARE  m VARCHAR(8) DEFAULT '0';
  DECLARE  y DOUBLE DEFAULT 0;
  DECLARE  r DOUBLE DEFAULT 0;
  DECLARE  v DOUBLE DEFAULT 0;
 /* report variables */
  DECLARE  cf DOUBLE DEFAULT 0;
  DECLARE  ct DOUBLE DEFAULT 0;
  DECLARE  af DOUBLE DEFAULT 0;
  DECLARE  aw DOUBLE DEFAULT 0;
  DECLARE  bf DOUBLE DEFAULT 0;
  DECLARE  bw DOUBLE DEFAULT 0;
  DECLARE  mf DOUBLE DEFAULT 0;
  DECLARE  mw DOUBLE DEFAULT 0;
  DECLARE  yf DOUBLE DEFAULT 0;
  DECLARE  yw DOUBLE DEFAULT 0;
/* report variables */
  DECLARE  cflag DOUBLE DEFAULT 0;
  DECLARE  aflag DOUBLE DEFAULT 0;
  DECLARE  bflag DOUBLE DEFAULT 0;
  DECLARE  mflag DOUBLE DEFAULT 0;
  DECLARE  yflag DOUBLE DEFAULT 0;
  DECLARE  rflag DOUBLE DEFAULT 0;
  DECLARE  vflag DOUBLE DEFAULT 0;
/* warehouse, field flags*/
  DECLARE  fflag DOUBLE DEFAULT 0;
  DECLARE  wflag DOUBLE DEFAULT 0;
  DECLARE  tflag DOUBLE DEFAULT 0;
/* stakehoder, province, district flags*/
  DECLARE  sflag DOUBLE DEFAULT 0;
  DECLARE  pflag DOUBLE DEFAULT 0;
  DECLARE  dflag DOUBLE DEFAULT 0;
  DECLARE  lflag DOUBLE DEFAULT 0;
  DECLARE  ctotal DOUBLE DEFAULT 0;
  DECLARE  atotal DOUBLE DEFAULT 0;
  DECLARE  btotal DOUBLE DEFAULT 0;
  DECLARE  mtotal DOUBLE DEFAULT 0;
  DECLARE  ytotal DOUBLE DEFAULT 0;
  DECLARE  ccyp DOUBLE DEFAULT 0;
  DECLARE  dc VARCHAR(1) DEFAULT '*';
  DECLARE cypfactor FLOAT DEFAULT 0.0;
  DECLARE  total DOUBLE DEFAULT 0;
  DECLARE  val DOUBLE DEFAULT 0;
 -- Declare variables used just for cursor and loop control
  DECLARE no_more_rows BOOLEAN;
  DECLARE loop_cntr INT DEFAULT 0;
  DECLARE num_rows INT DEFAULT 0;
  DECLARE stkid_var INT DEFAULT 0;
  DECLARE CONTINUE HANDLER FOR NOT FOUND
    SET no_more_rows = TRUE;
/* Consumtion, Average Monthly Consumption, Closing Blance, Months of Stock, CYP*/
IF INSTR(in_col,'C') > 0 THEN
 SET cflag = 1;
END IF;
IF INSTR(in_col,'A') > 0 THEN
 SET aflag = 1;
END IF;
IF INSTR(in_col,'B') > 0 THEN
 SET bflag = 1;
END IF;
IF INSTR(in_col,'M') > 0 THEN
 SET mflag = 1;
END IF;
IF INSTR(in_col,'Y') > 0 THEN
 SET yflag = 1;
END IF;
IF INSTR(in_col,'R') > 0 THEN
 SET rflag = 1;
END IF;
IF INSTR(in_col,'V') > 0 THEN
 SET vflag = 1;
END IF;
/* field, warehouse, total flags*/
IF INSTR(in_type,'F') > 0 THEN
 SET fflag = 1;
END IF;
IF INSTR(in_type,'W') > 0 THEN
 SET wflag = 1;
END IF;
IF INSTR(in_type,'T') > 0 THEN
 SET tflag = 1;
END IF;
/*  stakeholders, province, district flags, field flags*/
IF INSTR(in_type,'S') > 0 THEN
 SET sflag = 1;
END IF;
IF INSTR(in_type,'P') > 0 THEN
 SET pflag = 1;
END IF;
IF INSTR(in_type,'D') > 0 THEN
 SET dflag = 1;
END IF;
IF INSTR(in_type,'L') > 0 THEN
 SET lflag = 1;
END IF;
   IF  (cflag > 0) THEN
   -- caller requested consumption data 
   SELECT  REPgetConsumption(in_month, in_year, in_item, in_stk, in_type, in_prov) INTO c from DUAL;
   END IF;
  IF  (aflag > 0) THEN
 -- INSERT INTO testing(tmp) VALUES('inside aflag');
   -- caller request average monthly consumption  
    SELECT REPgetConsumptionAVG(in_type,in_month, in_year, in_item, in_stk, in_prov, in_dist) INTO a from DUAL;
  END IF;
  IF  (bflag > 0) THEN
   -- caller request closing balance  
    SELECT REPgetCB(in_type,in_month, in_year, in_item, in_stk, in_prov, in_dist) INTO b from DUAL;
  END IF;
  IF  (mflag > 0) THEN
   -- caller request months of stock  
    SELECT REPgetMOS(in_type,in_month, in_year, in_item, in_stk, in_prov, in_dist) INTO m from DUAL;
  IF ( m = 'UNK') THEN
 -- graph can not show UNK, convert it into 0
   IF (in_rg = 'G') THEN
    SET m = 0;
   END IF;
  END IF;
  END IF;
/*
  IF  (yflag > 0) THEN
   -- caller request couple year protection   
     SELECT REPgetCYP(in_type,in_month, in_year, in_item, in_stk, in_prov, in_dist) INTO y from DUAL;
  END IF;
  IF  (rflag > 0) THEN
   -- caller request reporting rate 
    SELECT   REPgetCYP(in_type,in_month, in_year, in_item, in_stk, in_prov, in_dist) INTO r from DUAL;
  END IF;
 IF  (vflag > 0) THEN
   -- caller request availability 
    SELECT REPgetCYP(in_type,in_month, in_year, in_item, in_stk, in_prov, in_dist) INTO r from DUAL;
  END IF;
 
*/
  SET retstr = '';
  -- final value
  IF (in_rg = 'G') THEN
     SET tmp = IFNULL(c,0);
     SET retstr = CONCAT(retstr,tmp,dc);   
     SET tmp = IFNULL(a,0);
     SET retstr = CONCAT(retstr,tmp,dc); 
     SET tmp = IFNULL(b,0);
     SET retstr = CONCAT(retstr,tmp,dc); 
     SET tmp = IFNULL(m,0);
     SET retstr = CONCAT(retstr,tmp,dc); 
  ELSE
     SET tmp = IFNULL(c,'UNK');     
     SET retstr = CONCAT(retstr,tmp,dc);   
     SET tmp = IFNULL(a,'UNK');
     SET retstr = CONCAT(retstr,tmp,dc); 
 
     SET tmp = IFNULL(b,'UNK');
     SET retstr = CONCAT(retstr,tmp,dc); 
 
     SET tmp = IFNULL(m,'UNK');
     IF LENGTH(tmp) = 0 THEN
      SET tmp = 'UNK';
     END IF;
     SET retstr = CONCAT(retstr,tmp,dc); 
 
    SET tmp = IFNULL(y,'UNK');
    SET retstr = CONCAT(retstr,tmp);
 END IF;
 
  -- send requested data in delimited string
  -- SET retstr = CONCAT(c,dc,a,dc,b,dc,m,dc,y);
   RETURN(retstr);
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetMapConsumption
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetMapConsumption`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetMapConsumption`(`in_month` int,`in_year` int,`in_item` int,`in_stk` int,`in_loc_level` varchar(8),`in_loc` int) RETURNS varchar(10) CHARSET latin1
    DETERMINISTIC
BEGIN
/* Written by Wasif Raza Mirza - Oct 13 */
DECLARE retval DOUBLE DEFAULT 0;
/* consumption is present on what level: in vLMIS it is on UC level which is 6th level*/
DECLARE c_level INTEGER DEFAULT 6; 
CASE 
WHEN (in_loc_level = 'N') THEN
  /*  Aggreate National Consumption */
   SELECT sum( hf_data_master.issue_balance)
          INTO retval
					FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item
         AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
         AND MONTH(hf_data_master.reporting_start_date) = in_month 
         AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND   stakeholders.geo_level_id=c_level;
WHEN (in_loc_level  = 'P') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum( hf_data_master.issue_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.province_id = in_loc
         AND   stakeholders.geo_level_id=c_level;
WHEN (in_loc_level  = 'UP') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum( hf_data_master.issue_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.province_id = in_loc
         AND   stakeholders.geo_level_id=c_level;
 WHEN (in_loc_level  = 'V') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum( hf_data_master.issue_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.district_id in (SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.geo_level_id = 4
																				AND   locations.parent_id = in_loc)
         AND   stakeholders.geo_level_id=c_level;
WHEN (in_loc_level  = 'D') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum(hf_data_master.issue_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders  ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.district_id = in_loc
         AND   stakeholders.geo_level_id=c_level;
WHEN (in_loc_level  = 'DW') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum( hf_data_master.issue_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders  ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.district_id = in_loc
         AND   stakeholders.geo_level_id=c_level;
WHEN (in_loc_level  = 'DP') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum( hf_data_master.issue_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.province_id = in_loc
         AND   stakeholders.geo_level_id=c_level;
 WHEN (in_loc_level  = 'H') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum( hf_data_master.issue_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.location_id   in (
																				SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.parent_id = in_loc
																			)
         AND   stakeholders.geo_level_id=c_level;
WHEN (in_loc_level  = 'U') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum( hf_data_master.issue_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders  ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  =   hf_data_master.warehouse_id  
				 AND  warehouses.location_id   = in_loc
         AND   stakeholders.geo_level_id=c_level;
WHEN (in_loc_level  = 'UW') THEN
/*Aggreate Consumption for selected province*/
   SELECT sum( hf_data_master.issue_balance)
          INTO retval
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master .item_pack_size_id = in_item 
				 AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
				 AND  warehouses.pk_id  = in_loc 
         AND   stakeholders.geo_level_id=c_level;
 ELSE 
		BEGIN 
		END;
 END CASE;
SET retval = ROUND(retval,0);
 return retval;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetMapData
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetMapData`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetMapData`(`in_col` varchar(255),`in_rg` varchar(1),`in_type` varchar(8),`in_month` int,`in_year` int,`in_item` int,`in_stk` int,`in_prov` int,`in_dist` varchar(8)) RETURNS varchar(255) CHARSET latin1
    DETERMINISTIC
BEGIN
/*
in_col
 C - Consumption
 A - Average Monthly Consumption
 B - Closing Balance
 M - Month of Stock
 Y - Couple Year Protection
in_type
 F - field
 W - warehouse
 T - total
 S -  stakeholders
 P - province
 D - district
in_rg;
 G - graph
 N - national
 S -  stakeholders
 P - Provincial
 D - district
 X - Private Group
*/
  DECLARE retstr VARCHAR(255);
  DECLARE tmp VARCHAR(255);
 /* report variables */
  DECLARE  c DOUBLE DEFAULT 0;
  DECLARE  a DOUBLE DEFAULT 0;
  DECLARE  b DOUBLE DEFAULT 0;
  DECLARE  m VARCHAR(8) DEFAULT '0';
  DECLARE  y DOUBLE DEFAULT 0;
  DECLARE  r DOUBLE DEFAULT 0;
  DECLARE  v DOUBLE DEFAULT 0;
 /* report variables */
  DECLARE  cf DOUBLE DEFAULT 0;
  DECLARE  ct DOUBLE DEFAULT 0;
  DECLARE  af DOUBLE DEFAULT 0;
  DECLARE  aw DOUBLE DEFAULT 0;
  DECLARE  bf DOUBLE DEFAULT 0;
  DECLARE  bw DOUBLE DEFAULT 0;
  DECLARE  mf DOUBLE DEFAULT 0;
  DECLARE  mw DOUBLE DEFAULT 0;
  DECLARE  yf DOUBLE DEFAULT 0;
  DECLARE  yw DOUBLE DEFAULT 0;
/* report variables */
  DECLARE  cflag DOUBLE DEFAULT 0;
  DECLARE  aflag DOUBLE DEFAULT 0;
  DECLARE  bflag DOUBLE DEFAULT 0;
  DECLARE  mflag DOUBLE DEFAULT 0;
  DECLARE  yflag DOUBLE DEFAULT 0;
  DECLARE  rflag DOUBLE DEFAULT 0;
  DECLARE  vflag DOUBLE DEFAULT 0;
/* warehouse, field flags*/
  DECLARE  fflag DOUBLE DEFAULT 0;
  DECLARE  wflag DOUBLE DEFAULT 0;
  DECLARE  tflag DOUBLE DEFAULT 0;
/* stakehoder, province, district flags*/
  DECLARE  sflag DOUBLE DEFAULT 0;
  DECLARE  pflag DOUBLE DEFAULT 0;
  DECLARE  dflag DOUBLE DEFAULT 0;
  DECLARE  lflag DOUBLE DEFAULT 0;
  DECLARE  ctotal DOUBLE DEFAULT 0;
  DECLARE  atotal DOUBLE DEFAULT 0;
  DECLARE  btotal DOUBLE DEFAULT 0;
  DECLARE  mtotal DOUBLE DEFAULT 0;
  DECLARE  ytotal DOUBLE DEFAULT 0;
  DECLARE  ccyp DOUBLE DEFAULT 0;
  DECLARE  dc VARCHAR(1) DEFAULT '*';
  DECLARE cypfactor FLOAT DEFAULT 0.0;
  DECLARE  total DOUBLE DEFAULT 0;
  DECLARE  val DOUBLE DEFAULT 0;
 -- Declare variables used just for cursor and loop control
  DECLARE no_more_rows BOOLEAN;
  DECLARE loop_cntr INT DEFAULT 0;
  DECLARE num_rows INT DEFAULT 0;
  DECLARE stkid_var INT DEFAULT 0;
  DECLARE CONTINUE HANDLER FOR NOT FOUND
    SET no_more_rows = TRUE;
/* Consumtion, Average Monthly Consumption, Closing Blance, Months of Stock, CYP*/
IF INSTR(in_col,'C') > 0 THEN
 SET cflag = 1;
END IF;
IF INSTR(in_col,'A') > 0 THEN
 SET aflag = 1;
END IF;
IF INSTR(in_col,'B') > 0 THEN
 SET bflag = 1;
END IF;
IF INSTR(in_col,'M') > 0 THEN
 SET mflag = 1;
END IF;
IF INSTR(in_col,'Y') > 0 THEN
 SET yflag = 1;
END IF;
IF INSTR(in_col,'R') > 0 THEN
 SET rflag = 1;
END IF;
IF INSTR(in_col,'V') > 0 THEN
 SET vflag = 1;
END IF;
/* field, warehouse, total flags*/
IF INSTR(in_type,'F') > 0 THEN
 SET fflag = 1;
END IF;
IF INSTR(in_type,'W') > 0 THEN
 SET wflag = 1;
END IF;
IF INSTR(in_type,'T') > 0 THEN
 SET tflag = 1;
END IF;
/*  stakeholders, province, district flags, field flags*/
IF INSTR(in_type,'S') > 0 THEN
 SET sflag = 1;
END IF;
IF INSTR(in_type,'P') > 0 THEN
 SET pflag = 1;
END IF;
IF INSTR(in_type,'D') > 0 THEN
 SET dflag = 1;
END IF;
IF INSTR(in_type,'L') > 0 THEN
 SET lflag = 1;
END IF;
   IF  (cflag > 0) THEN
   -- caller requested consumption data 
   SELECT  REPgetConsumption(in_month, in_year, in_item, in_stk, in_type, in_prov) INTO c from DUAL;
   END IF;
  IF  (aflag > 0) THEN
 -- INSERT INTO testing(tmp) VALUES('inside aflag');
   -- caller request average monthly consumption  
    SELECT REPgetConsumptionAVG(in_type,in_month, in_year, in_item, in_stk, in_prov, in_dist) INTO a from DUAL;
  END IF;
  IF  (bflag > 0) THEN
   -- caller request closing balance  
    SELECT REPgetCB(in_type,in_month, in_year, in_item, in_stk, in_prov, in_dist) INTO b from DUAL;
  END IF;
  IF  (mflag > 0) THEN
   -- caller request months of stock  
    SELECT REPgetMOS(in_type,in_month, in_year, in_item, in_stk, in_prov, in_dist) INTO m from DUAL;
  IF ( m = 'UNK') THEN
 -- graph can not show UNK, convert it into 0
   IF (in_rg = 'G') THEN
    SET m = 0;
   END IF;
  END IF;
  END IF;
/*
  IF  (yflag > 0) THEN
   -- caller request couple year protection   
     SELECT REPgetCYP(in_type,in_month, in_year, in_item, in_stk, in_prov, in_dist) INTO y from DUAL;
  END IF;
  IF  (rflag > 0) THEN
   -- caller request reporting rate 
    SELECT   REPgetCYP(in_type,in_month, in_year, in_item, in_stk, in_prov, in_dist) INTO r from DUAL;
  END IF;
 IF  (vflag > 0) THEN
   -- caller request availability 
    SELECT REPgetCYP(in_type,in_month, in_year, in_item, in_stk, in_prov, in_dist) INTO r from DUAL;
  END IF;
 
*/
  SET retstr = '';
  -- final value
  IF (in_rg = 'G') THEN
     SET tmp = IFNULL(c,0);
     SET retstr = CONCAT(retstr,tmp,dc);   
     SET tmp = IFNULL(a,0);
     SET retstr = CONCAT(retstr,tmp,dc); 
     SET tmp = IFNULL(b,0);
     SET retstr = CONCAT(retstr,tmp,dc); 
     SET tmp = IFNULL(m,0);
     SET retstr = CONCAT(retstr,tmp,dc); 
  ELSE
     SET tmp = IFNULL(c,'0');     
     SET retstr = CONCAT(retstr,tmp,dc);   
     SET tmp = IFNULL(a,'0');
     SET retstr = CONCAT(retstr,tmp,dc); 
 
     SET tmp = IFNULL(b,'0');
     SET retstr = CONCAT(retstr,tmp,dc); 
 
     SET tmp = IFNULL(m,'0');
     IF LENGTH(tmp) = 0 THEN
      SET tmp = '0';
     END IF;
     SET retstr = CONCAT(retstr,tmp,dc); 
 
    SET tmp = IFNULL(y,'0');
    SET retstr = CONCAT(retstr,tmp);
 END IF;
 
  -- send requested data in delimited string
  -- SET retstr = CONCAT(c,dc,a,dc,b,dc,m,dc,y);
   RETURN(retstr);
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetMOS
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetMOS`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetMOS`(`in_type` varchar(8),`in_month` int,`in_year` int,`in_item` varchar(20),`in_stk` int,`in_prov` int,`in_dist` varchar(8)) RETURNS varchar(15) CHARSET latin1
    DETERMINISTIC
BEGIN
  DECLARE retval DOUBLE DEFAULT 0;
  DECLARE retval2 DOUBLE DEFAULT 0;
  DECLARE retstr VARCHAR(8) DEFAULT '';
    -- Declare '_val' variables to read in each record from the cursor
  DECLARE name_val VARCHAR(255);
  DECLARE status_update_val VARCHAR(255);
  -- Declare variables used just for cursor and loop control
  DECLARE no_more_rows BOOLEAN;
  DECLARE loop_cntr INT DEFAULT 0;
  DECLARE num_rows INT DEFAULT 0;
  DECLARE wflag  VARCHAR(10);
 CASE 
 WHEN (in_type = 'NW') THEN
SELECT sum(closing_balance) INTO retval from (
 
          
        
  SELECT sum(hf_data_master.closing_balance) as closing_balance
          
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE     stakeholders.geo_level_id=1
         AND  hf_data_master.item_pack_size_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
) A;
  SELECT REPgetConsumptionAVG(in_type, in_month, in_year, in_item, in_stk, in_prov, in_dist)
         INTO retval2
    FROM DUAL;
 
 WHEN (in_type = 'N') THEN
Select sum(closing_balance) INTO retval from (
   
    SELECT sum(hf_data_master.closing_balance) as closing_balance
          
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE     stakeholders.geo_level_id>=1
         AND  hf_data_master.item_pack_size_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
) A;
  SELECT REPgetConsumptionAVG(in_type, in_month, in_year, in_item, in_stk, in_prov, in_dist)
         INTO retval2
    FROM DUAL;
  
  WHEN (in_type = 'P') THEN
SELECT sum(closing_balance) INTO retval from (
  
SELECT sum(hf_data_master.closing_balance) as closing_balance
         
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.province_id = in_prov and   stakeholders.geo_level_id>=2
) A;
  SELECT REPgetConsumptionAVG(in_type, in_month, in_year, in_item, in_stk, in_prov, in_dist)
         INTO retval2
    FROM DUAL;
WHEN (in_type = 'PW') THEN
   SELECT sum(closing_balance) INTO retval from
(
SELECT sum(hf_data_master.closing_balance) as closing_balance
          
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.province_id = in_prov and   stakeholders.geo_level_id=2
) A;
  SELECT REPgetConsumptionAVG(in_type, in_month, in_year, in_item, in_stk, in_prov, in_dist)
         INTO retval2
    FROM DUAL;
WHEN (in_type = 'V') THEN
SELECT sum(closing_balance) INTO retval from (
   
SELECT sum(hf_data_master.closing_balance) as closing_balance
         
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
          AND   warehouses.district_id in (SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.geo_level_id = 4
																				AND   locations.parent_id = in_prov)  and   stakeholders.geo_level_id>=3
) A;
  SELECT REPgetConsumptionAVG(in_type, in_month, in_year, in_item, in_stk, in_prov, in_dist)
         INTO retval2
    FROM DUAL;
  WHEN (in_type = 'D') THEN
SELECT sum(closing_balance) INTO retval from
 ( 
 SELECT sum(hf_data_master.closing_balance) as closing_balance
          
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND   warehouses.district_id = in_prov AND   stakeholders.geo_level_id>=4
) A ;
 SELECT REPgetConsumptionAVG(in_type, in_month, in_year, in_item, in_stk, in_prov, in_dist)
         INTO retval2
    FROM DUAL;
 WHEN (in_type = 'DP') THEN
SELECT sum(closing_balance) INTO retval from
  (
SELECT sum(hf_data_master.closing_balance) as closing_balance
        
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND   warehouses.province_id = in_prov AND   stakeholders.geo_level_id=4
) A;
 SELECT REPgetConsumptionAVG(in_type, in_month, in_year, in_item, in_stk, in_prov, in_dist)
         INTO retval2
    FROM DUAL;
  WHEN (in_type = 'DW') THEN
SELECT sum(closing_balance) INTO retval from
  (
SELECT sum(hf_data_master.closing_balance) as closing_balance
         
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND   warehouses.district_id = in_prov AND   stakeholders.geo_level_id=4
) A;
 SELECT REPgetConsumptionAVG(in_type, in_month, in_year, in_item, in_stk, in_prov, in_dist)
         INTO retval2
    FROM DUAL;
  WHEN (in_type = 'DU') THEN
SELECT sum(closing_balance) INTO retval from
  (
SELECT sum(hf_data_master.closing_balance) as closing_balance
         
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND   warehouses.district_id = in_prov AND   stakeholders.geo_level_id=6
) A;
 SELECT REPgetConsumptionAVG(in_type, in_month, in_year, in_item, in_stk, in_prov, in_dist)
         INTO retval2
    FROM DUAL;
WHEN (in_type = 'H') THEN
SELECT sum(closing_balance) INTO retval from 
  (
SELECT sum(hf_data_master.closing_balance) as closing_balance
         
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND  warehouses.location_id   in (
																				SELECT
																					 locations.pk_id
																				FROM
																					 locations
																				WHERE
																					  locations.parent_id = in_prov)  AND   stakeholders.geo_level_id>=5
) A;
 SELECT REPgetConsumptionAVG(in_type, in_month, in_year, in_item, in_stk, in_prov, in_dist)
         INTO retval2
    FROM DUAL;
WHEN (in_type = 'U') THEN
SELECT sum(closing_balance) INTO retval from 
		(
SELECT sum(hf_data_master.closing_balance) as closing_balance
       
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND   warehouses.location_id   = in_prov AND   stakeholders.geo_level_id=6
) A ;
		SELECT REPgetConsumptionAVG(in_type, in_month, in_year, in_item, in_stk, in_prov, in_dist)
         INTO retval2
    FROM DUAL;
WHEN (in_type = 'UW') THEN
SELECT sum(closing_balance) INTO retval from
		(
SELECT sum(hf_data_master.closing_balance) as closing_balance
          
        FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 Inner Join  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id = in_item AND MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year
         AND   warehouses.pk_id  = in_prov AND   stakeholders.geo_level_id=6
) A;
		SELECT REPgetConsumptionAVG(in_type, in_month, in_year, in_item, in_stk, in_prov, in_dist)
         INTO retval2
    FROM DUAL;
 END CASE;
SET retval = IFNULL(retval,-1);
SET retval2 = IFNULL(retval2,-1);
 IF (retval > 0 AND retval2 > 0) THEN
  SET retstr = retval / retval2;
 END IF;
 return retstr;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetReportingRateStr
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetReportingRateStr`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetReportingRateStr`(`in_type` varchar(8),`in_month` int,`in_year` int,`in_item` varchar(8),`in_WF` varchar(8),`in_stk` int,`in_prov` int,`in_dist` varchar(8)) RETURNS varchar(20) CHARSET latin1
    DETERMINISTIC
BEGIN
DECLARE retval DECIMAL(5,2) ;
DECLARE numExptd INTEGER;
DECLARE numReptd INTEGER;
DECLARE retvalstr VARCHAR(5);
 IF (in_WF = 'F') THEN
     CASE
       WHEN (in_type ='N') THEN
 -- 1. Get total reports expected 
        SELECT count( warehouses.pk_id )
            INTO numExptd
          FROM warehouses INNER JOIN  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE   stakeholders.geo_level_id =6;
      SELECT count(distinct   hf_data_master.warehouse_id )
          INTO numReptd
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 INNER JOIN  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE MONTH(hf_data_master.reporting_start_date) = in_month AND YEAR(hf_data_master.reporting_start_date) = in_year AND
							  stakeholders.geo_level_id =6;
    WHEN (in_type ='P') THEN
        SELECT count( warehouses.pk_id )
            INTO numExptd
          FROM warehouses INNER JOIN  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE   stakeholders.geo_level_id = 6;
				SELECT count(distinct   hf_data_master.warehouse_id )
          INTO numReptd
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 INNER JOIN  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id=in_item
         AND MONTH(hf_data_master.reporting_start_date) = in_month 
				 AND YEAR(hf_data_master.reporting_start_date) = in_year 
				 AND   stakeholders.geo_level_id = 6;   
  WHEN (in_type ='D') THEN
        SELECT count( warehouses.pk_id )
            INTO numExptd
          FROM warehouses INNER JOIN  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE   stakeholders.geo_level_id = 6;
				SELECT count(distinct   hf_data_master.warehouse_id )
          INTO numReptd
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 INNER JOIN  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id=in_item
         AND MONTH(hf_data_master.reporting_start_date) = in_month 
				 AND YEAR(hf_data_master.reporting_start_date) = in_year 
				 AND   stakeholders.geo_level_id = 6;   
  END CASE;
 ELSE
    CASE
        WHEN (in_type ='N') THEN
       --  1. Get total reports expected 
        SELECT count( warehouses.pk_id )
            INTO numExptd
          FROM warehouses INNER JOIN  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE   stakeholders.geo_level_id = 4 ;
      SELECT count(distinct   hf_data_master.warehouse_id )
          INTO numReptd
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 INNER JOIN  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE MONTH(hf_data_master.reporting_start_date) = in_month 
					AND YEAR(hf_data_master.reporting_start_date) = in_year 
					AND   stakeholders.geo_level_id = 4;
 WHEN (in_type = 'P') THEN
       SELECT count( warehouses.pk_id )
            INTO numExptd
          FROM warehouses INNER JOIN  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE   stakeholders.geo_level_id = 4;
			
				SELECT count(distinct   hf_data_master.warehouse_id )
          INTO numReptd
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 INNER JOIN  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE  hf_data_master.item_pack_size_id=in_item
					AND MONTH(hf_data_master.reporting_start_date) = in_month 
					AND YEAR(hf_data_master.reporting_start_date) = in_year 
					AND   stakeholders.geo_level_id = 4; 
 WHEN (in_type = 'D') THEN
       SELECT count( warehouses.pk_id )
            INTO numExptd
          FROM warehouses INNER JOIN  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE   stakeholders.geo_level_id = 4;
			
			SELECT count(distinct hf_data_master.warehouse_id )
          INTO numReptd
         FROM warehouses
         Inner Join  hf_data_master  ON  warehouses.pk_id  =   hf_data_master.warehouse_id 
				 INNER JOIN  stakeholders ON  warehouses.stakeholder_office_id =   stakeholders.pk_id
         WHERE   hf_data_master.item_pack_size_id=in_item
					AND MONTH(hf_data_master.reporting_start_date) = in_month 
					AND YEAR(hf_data_master.reporting_start_date) = in_year 
					AND   stakeholders.geo_level_id = 4; 
  END CASE;
END IF;
    SET numExptd = IFNULL(numExptd,0);
    SET numReptd = IFNULL(numReptd,0);
		IF (numExptd > 0 and numReptd > 0) THEN
      set retval = numReptd / numExptd * 100;
      SET retvalstr = round(retval);
		else
       SET retvalstr = 'NA';
    end if;
     /*set retvalstr = CONCAT(in_type,',',in_id,',',in_month,',',in_year,',',in_item,',',in_WF,',',in_stk,',',in_prov);*/
 RETURN(retvalstr);
    END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetRR
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetRR`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetRR`(in_lvl VARCHAR(10), in_start_date DATE, in_end_date DATE, in_stk INT, in_item INT, in_loc INT, aggregation INT) RETURNS varchar(300) CHARSET latin1
    DETERMINISTIC
BEGIN
DECLARE retval VARCHAR(300);
DECLARE totalWH INT;
DECLARE reportedWH INT;
/* Written by Muhammad Waqas Azeem waqasazeemcs06@gmail.com */
CASE 
	WHEN (aggregation = 1) THEN
		CASE
			WHEN (in_lvl = 'N') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND warehouses.status = 1;
			
				SELECT
					ROUND((COUNT(DISTINCT UC.pk_id) / totalWH) * 100) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.status = 1;
			WHEN (in_lvl = 'P') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND warehouses.status = 1
				AND UC.province_id = in_loc;
			
				SELECT
					ROUND((COUNT(DISTINCT UC.pk_id) / totalWH) * 100) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.status = 1
				AND UC.province_id = in_loc;
			WHEN (in_lvl = 'D') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND warehouses.status = 1
				AND UC.district_id = in_loc;
			
				SELECT
					ROUND((COUNT(DISTINCT UC.pk_id) / totalWH) * 100) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.status = 1
				AND UC.district_id = in_loc;
			WHEN (in_lvl = 'T') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND warehouses.status = 1
				AND UC.parent_id = in_loc;
			
				SELECT
					ROUND((COUNT(DISTINCT UC.pk_id) / totalWH) * 100) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.status = 1
				AND UC.parent_id = in_loc;
			WHEN (in_lvl = 'U') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND warehouses.status = 1
				AND UC.pk_id = in_loc;
			
				SELECT
					ROUND((COUNT(DISTINCT UC.pk_id) / totalWH) * 100) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.status = 1
				AND UC.pk_id = in_loc;
		END CASE;
	WHEN (aggregation = 0) THEN
		CASE
			WHEN (in_lvl = 'N') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND warehouses.status = 1;
			
				SELECT GROUP_CONCAT(ROUND(A.RR) SEPARATOR '*') INTO retval FROM (
					SELECT
						(COUNT(DISTINCT UC.pk_id) / totalWH) * 100 AS RR
					FROM
						locations AS UC
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					WHERE
						UC.geo_level_id = 6
					AND warehouses.stakeholder_id = in_stk
					AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
					AND hf_data_master.issue_balance IS NOT NULL
					AND hf_data_master.item_pack_size_id = in_item
					AND warehouses.status = 1
					GROUP BY
						hf_data_master.reporting_start_date) A;
			WHEN (in_lvl = 'P') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND warehouses.status = 1
				AND UC.province_id = in_loc;
			
				SELECT GROUP_CONCAT(ROUND(A.RR) SEPARATOR '*') INTO retval FROM (
					SELECT
						(COUNT(DISTINCT UC.pk_id) / totalWH) * 100 AS RR
					FROM
						locations AS UC
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					WHERE
						UC.geo_level_id = 6
					AND warehouses.stakeholder_id = in_stk
					AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
					AND hf_data_master.issue_balance IS NOT NULL
					AND hf_data_master.item_pack_size_id = in_item
					AND warehouses.status = 1
					AND UC.province_id = in_loc
					GROUP BY
						hf_data_master.reporting_start_date) A;
			WHEN (in_lvl = 'D') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND warehouses.status = 1
				AND UC.district_id = in_loc;
			
				SELECT GROUP_CONCAT(ROUND(A.RR) SEPARATOR '*') INTO retval FROM (
					SELECT
						(COUNT(DISTINCT UC.pk_id) / totalWH) * 100 AS RR
					FROM
						locations AS UC
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					WHERE
						UC.geo_level_id = 6
					AND warehouses.stakeholder_id = in_stk
					AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
					AND hf_data_master.issue_balance IS NOT NULL
					AND hf_data_master.item_pack_size_id = in_item
					AND warehouses.status = 1
					AND UC.district_id = in_loc
					GROUP BY
						hf_data_master.reporting_start_date) A;
			WHEN (in_lvl = 'T') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND warehouses.status = 1
				AND UC.parent_id = in_loc;
			
				SELECT GROUP_CONCAT(ROUND(A.RR) SEPARATOR '*') INTO retval FROM (
					SELECT
						(COUNT(DISTINCT UC.pk_id) / totalWH) * 100 AS RR
					FROM
						locations AS UC
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					WHERE
						UC.geo_level_id = 6
					AND warehouses.stakeholder_id = in_stk
					AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
					AND hf_data_master.issue_balance IS NOT NULL
					AND hf_data_master.item_pack_size_id = in_item
					AND warehouses.status = 1
					AND UC.parent_id = in_loc
					GROUP BY
						hf_data_master.reporting_start_date) A;
		END CASE;
END CASE;
return retval;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetRR_copy
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetRR_copy`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetRR_copy`(in_lvl VARCHAR(10), in_start_date DATE, in_end_date DATE, in_stk INT, in_item INT, in_loc INT, aggregation INT) RETURNS varchar(300) CHARSET latin1
    DETERMINISTIC
BEGIN
DECLARE retval VARCHAR(300);
DECLARE totalWH INT;
DECLARE reportedWH INT;
/* Written by Muhammad Waqas Azeem waqasazeemcs06@gmail.com */
CASE 
	WHEN (aggregation = 1) THEN
		CASE
			WHEN (in_lvl = 'N') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk;
			
				SELECT
					ROUND((COUNT(DISTINCT UC.pk_id) / totalWH) * 100) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item;
			WHEN (in_lvl = 'P') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND UC.province_id = in_loc;
			
				SELECT
					ROUND((COUNT(DISTINCT UC.pk_id) / totalWH) * 100) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item
				AND UC.province_id = in_loc;
			WHEN (in_lvl = 'D') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND UC.district_id = in_loc;
			
				SELECT
					ROUND((COUNT(DISTINCT UC.pk_id) / totalWH) * 100) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item
				AND UC.district_id = in_loc;
		END CASE;
	WHEN (aggregation = 0) THEN
		CASE
			WHEN (in_lvl = 'N') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk;
			
				SELECT GROUP_CONCAT(ROUND(A.RR) SEPARATOR '*') INTO retval FROM (
					SELECT
						(COUNT(DISTINCT UC.pk_id) / totalWH) * 100 AS RR
					FROM
						locations AS UC
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					WHERE
						UC.geo_level_id = 6
					AND warehouses.stakeholder_id = in_stk
					AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
					AND hf_data_master.issue_balance IS NOT NULL
					AND hf_data_master.item_pack_size_id = in_item
					GROUP BY
						hf_data_master.reporting_start_date) A;
			WHEN (in_lvl = 'P') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND UC.province_id = in_loc;
			
				SELECT GROUP_CONCAT(ROUND(A.RR) SEPARATOR '*') INTO retval FROM (
					SELECT
						(COUNT(DISTINCT UC.pk_id) / totalWH) * 100 AS RR
					FROM
						locations AS UC
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					WHERE
						UC.geo_level_id = 6
					AND warehouses.stakeholder_id = in_stk
					AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
					AND hf_data_master.issue_balance IS NOT NULL
					AND hf_data_master.item_pack_size_id = in_item
					AND UC.province_id = in_loc
					GROUP BY
						hf_data_master.reporting_start_date) A;
			WHEN (in_lvl = 'D') THEN
				SELECT
					COUNT(DISTINCT UC.pk_id) INTO totalWH
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND UC.district_id = in_loc;
			
				SELECT GROUP_CONCAT(ROUND(A.RR) SEPARATOR '*') INTO retval FROM (
					SELECT
						(COUNT(DISTINCT UC.pk_id) / totalWH) * 100 AS RR
					FROM
						locations AS UC
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					WHERE
						UC.geo_level_id = 6
					AND warehouses.stakeholder_id = in_stk
					AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
					AND hf_data_master.issue_balance IS NOT NULL
					AND hf_data_master.item_pack_size_id = in_item
					AND UC.district_id = in_loc
					GROUP BY
						hf_data_master.reporting_start_date) A;
		END CASE;
END CASE;
return retval;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetTotalUCs
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetTotalUCs`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetTotalUCs`(in_lvl varchar(8), in_loc INT, in_stk INT) RETURNS int(11)
    DETERMINISTIC
BEGIN
DECLARE totalUCs INTEGER;
/* Written by Muhammad Waqas Azeem waqasazeemcs06@gmail.com */
CASE
	WHEN (in_lvl = 'N') THEN
		SELECT
			COUNT(DISTINCT UC.pk_id) INTO totalUCs
		FROM
			locations AS UC
		INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
		WHERE
			UC.geo_level_id = 6
		AND warehouses.stakeholder_id = in_stk;
	WHEN (in_lvl = 'P') THEN
		SELECT
			COUNT(DISTINCT UC.pk_id) INTO totalUCs
		FROM
			locations AS UC
		INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
		WHERE
			UC.geo_level_id = 6
		AND warehouses.stakeholder_id = in_stk
		AND UC.province_id = in_loc;
	WHEN (in_lvl = 'D') THEN
		SELECT
			COUNT(DISTINCT UC.pk_id) INTO totalUCs
		FROM
			locations AS UC
		INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
		WHERE
			UC.geo_level_id = 6
		AND warehouses.stakeholder_id = in_stk
		AND UC.district_id = in_loc;
	WHEN (in_lvl = 'T') THEN
		SELECT
			COUNT(DISTINCT UC.pk_id) INTO totalUCs
		FROM
			locations AS UC
		INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
		WHERE
			UC.geo_level_id = 6
		AND warehouses.stakeholder_id = in_stk
		AND UC.parent_id = in_loc;
	WHEN (in_lvl = 'U') THEN
		SELECT
			COUNT(DISTINCT UC.pk_id) INTO totalUCs
		FROM
			locations AS UC
		INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
		WHERE
			UC.geo_level_id = 6
		AND warehouses.stakeholder_id = in_stk
		AND UC.pk_id = in_loc;
END CASE;
return totalUCs;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetTransWHData
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetTransWHData`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetTransWHData`(in_type INTEGER,  in_month INTEGER, in_year INTEGER, in_item VARCHAR(20), in_wh INTEGER) RETURNS double
    READS SQL DATA
    DETERMINISTIC
BEGIN
 
  DECLARE retval INTEGER;
   CASE
  WHEN (in_type = 1) THEN
	SELECT
		IFNULL(SUM(ABS(stock_detail.quantity)),0) INTO retval 
	FROM
		stock_master
	INNER JOIN  stock_detail ON   stock_detail.stock_master_id =  stock_master.pk_id
	INNER JOIN stock_batch_warehouses ON   stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
  INNER JOIN stock_batch ON   stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
  INNER JOIN pack_info ON   stock_batch.pack_info_id = pack_info.pk_id
  INNER JOIN stakeholder_item_pack_sizes ON   pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
	WHERE
		stock_master.to_warehouse_id = in_wh
	AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
	AND MONTH ( stock_master.transaction_date) = in_month
	AND YEAR ( stock_master.transaction_date) = in_year AND
stock_master.transaction_type_id = 1;
  
  WHEN (in_type = 2) THEN
  SELECT
		IFNULL(SUM(ABS(stock_detail.quantity)),0) INTO retval 
	FROM
		stock_master
	INNER JOIN  stock_detail ON   stock_detail.stock_master_id =  stock_master.pk_id
	INNER JOIN stock_batch_warehouses ON   stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
  INNER JOIN stock_batch ON   stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
  INNER JOIN pack_info ON   stock_batch.pack_info_id = pack_info.pk_id
  INNER JOIN stakeholder_item_pack_sizes ON   pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
	
	WHERE
		stock_master.from_warehouse_id = in_wh
	AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
	AND MONTH ( stock_master.transaction_date) = in_month
	AND YEAR ( stock_master.transaction_date) = in_year AND
stock_master.transaction_type_id = 2;
WHEN (in_type = 3) THEN
   SELECT
		IFNULL(SUM(stock_detail.quantity),0) INTO retval
	FROM
		stock_master
	INNER JOIN  stock_detail ON   stock_detail.stock_master_id =  stock_master.pk_id
	INNER JOIN stock_batch_warehouses ON   stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
  INNER JOIN stock_batch ON   stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
  INNER JOIN pack_info ON   stock_batch.pack_info_id = pack_info.pk_id
  INNER JOIN stakeholder_item_pack_sizes ON   pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
	
	WHERE
		stock_master.from_warehouse_id = in_wh
	AND stakeholder_item_pack_sizes.item_pack_size_id = in_item
	AND MONTH ( stock_master.transaction_date) = in_month
	AND YEAR ( stock_master.transaction_date) = in_year AND
stock_master.transaction_type_id > 2;
  
 END CASE;
 -- SET retval = IFNULL(retval,0);
 return retval;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPgetWastage
-- ----------------------------
DROP FUNCTION IF EXISTS `REPgetWastage`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPgetWastage`(in_lvl VARCHAR(10), in_start_date DATE, in_end_date DATE, in_stk INT, in_item INT, in_loc INT, aggregation INT) RETURNS varchar(300) CHARSET latin1
    DETERMINISTIC
BEGIN
DECLARE retval VARCHAR(300);
/* Written by Muhammad Waqas Azeem waqasazeemcs06@gmail.com */
CASE 
	WHEN (aggregation = 1) THEN
		CASE
			WHEN (in_lvl = 'N') THEN
				SELECT
					ROUND(IFNULL((sum(hf_data_master.wastages) / (SUM(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.status = 1;
			WHEN (in_lvl = 'P') THEN
				SELECT
					ROUND(IFNULL((sum(hf_data_master.wastages) / (SUM(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.status = 1
				AND UC.province_id = in_loc;
			WHEN (in_lvl = 'D') THEN
				SELECT
					ROUND(IFNULL((sum(hf_data_master.wastages) / (SUM(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.status = 1
				AND UC.district_id = in_loc;
			WHEN (in_lvl = 'T') THEN
				SELECT
					ROUND(IFNULL((sum(hf_data_master.wastages) / (SUM(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN locations AS Tehsil ON UC.pk_id = Tehsil.parent_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.status = 1
				AND Tehsil.pk_id = in_loc;
			WHEN (in_lvl = 'U') THEN
				SELECT
					ROUND(IFNULL((sum(hf_data_master.wastages) / (SUM(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) INTO retval
				FROM
					locations AS UC
				INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				WHERE
					UC.geo_level_id = 6
				AND warehouses.stakeholder_id = in_stk
				AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = in_item
				AND warehouses.status = 1
				AND UC.pk_id = in_loc;
		END CASE;
	WHEN (aggregation = 0) THEN
	
		CASE
			WHEN (in_lvl = 'N') THEN
				#SET retval = 1000;
				SELECT GROUP_CONCAT(A.wastages SEPARATOR '*') INTO retval FROM (
					SELECT
						ROUND(IFNULL((sum(hf_data_master.wastages) / (SUM(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) AS wastages
					FROM
						locations AS UC
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					WHERE
						UC.geo_level_id = 6
					AND warehouses.stakeholder_id = in_stk
					AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
					AND hf_data_master.issue_balance IS NOT NULL
					AND hf_data_master.item_pack_size_id = in_item
					AND warehouses.status = 1
					GROUP BY hf_data_master.reporting_start_date) A;
			WHEN (in_lvl = 'P') THEN
				SELECT GROUP_CONCAT(A.wastages SEPARATOR '*') INTO retval FROM (
					SELECT
						ROUND(IFNULL((sum(hf_data_master.wastages) / (SUM(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) AS wastages
					FROM
						locations AS UC
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					WHERE
						UC.geo_level_id = 6
					AND warehouses.stakeholder_id = in_stk
					AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
					AND hf_data_master.issue_balance IS NOT NULL
					AND hf_data_master.item_pack_size_id = in_item
					AND warehouses.status = 1
					AND UC.province_id = in_loc
					GROUP BY hf_data_master.reporting_start_date) A;
			WHEN (in_lvl = 'D') THEN
				SELECT GROUP_CONCAT(A.wastages SEPARATOR '*') INTO retval FROM (
					SELECT
						ROUND(IFNULL((sum(hf_data_master.wastages) / (SUM(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) AS wastages
					FROM
						locations AS UC
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					WHERE
						UC.geo_level_id = 6
					AND warehouses.stakeholder_id = in_stk
					AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
					AND hf_data_master.issue_balance IS NOT NULL
					AND hf_data_master.item_pack_size_id = in_item
					AND warehouses.status = 1
					AND UC.district_id = in_loc
					GROUP BY hf_data_master.reporting_start_date) A;
			WHEN (in_lvl = 'T') THEN
				SELECT GROUP_CONCAT(A.wastages SEPARATOR '*') INTO retval FROM (
					SELECT
						ROUND(IFNULL((sum(hf_data_master.wastages) / (SUM(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) AS wastages
					FROM
						locations AS UC
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN locations AS Tehsil ON UC.pk_id = Tehsil.parent_id
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					WHERE
						UC.geo_level_id = 6
					AND warehouses.stakeholder_id = in_stk
					AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
					AND hf_data_master.issue_balance IS NOT NULL
					AND hf_data_master.item_pack_size_id = in_item
					AND warehouses.status = 1
					AND Tehsil.pk_id = in_loc
					GROUP BY hf_data_master.reporting_start_date) A;
			WHEN (in_lvl = 'U') THEN
				SELECT GROUP_CONCAT(A.wastages SEPARATOR '*') INTO retval FROM (	
					SELECT
						ROUND(IFNULL((sum(hf_data_master.wastages) / (SUM(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) wastages
					FROM
						locations AS UC
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					WHERE
						UC.geo_level_id = 6
					AND warehouses.stakeholder_id = in_stk
					AND hf_data_master.reporting_start_date BETWEEN in_start_date AND in_end_date
					AND hf_data_master.issue_balance IS NOT NULL
					AND hf_data_master.item_pack_size_id = in_item
					AND warehouses.status = 1
					AND UC.pk_id = in_loc
					GROUP BY hf_data_master.reporting_start_date) A;
	END CASE;
END CASE;
return retval;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPUpdateCapacity
-- ----------------------------
DROP FUNCTION IF EXISTS `REPUpdateCapacity`;
DELIMITER ;;
CREATE DEFINER=`root`@`115.186.139.239` FUNCTION `REPUpdateCapacity`(in_wh INTEGER, in_start_date DATE) RETURNS int(11)
    READS SQL DATA
    DETERMINISTIC
BEGIN
DECLARE capacity4 DOUBLE;
DECLARE capacity20 DOUBLE;
SELECT
	ROUND(SUM(ccm_models.net_capacity_20), 4),
	ROUND(SUM(ccm_models.net_capacity_4), 4)
	INTO
	capacity20, capacity4
FROM
	cold_chain
INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
INNER JOIN ccm_asset_types AS subtype ON cold_chain.ccm_asset_type_id = subtype.pk_id
WHERE
	cold_chain.warehouse_id = in_wh
	AND (
		subtype.parent_id IN(1,3)
		OR subtype.pk_id IN(1,3)
	);
UPDATE warehouse_population
SET warehouse_population.capacity_4degree = capacity4,
 warehouse_population.capacity_20degree = capacity20
WHERE
	warehouse_id = in_wh 
AND YEAR(estimation_year) = YEAR(in_start_date);
return 1;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPUpdateCarryForward
-- ----------------------------
DROP FUNCTION IF EXISTS `REPUpdateCarryForward`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPUpdateCarryForward`(in_rpt_date DATE, in_item INTEGER, in_wh INTEGER) RETURNS int(4)
    READS SQL DATA
    DETERMINISTIC
BEGIN
DECLARE OB DOUBLE;
DECLARE preMonthCB DOUBLE;
DECLARE CB DOUBLE;
DECLARE receive DOUBLE;
DECLARE issue DOUBLE;
DECLARE wastages DOUBLE;
DECLARE wID INTEGER;
SELECT
	hf_data_master.closing_balance INTO preMonthCB
FROM
	hf_data_master
WHERE
	hf_data_master.warehouse_id = in_wh
AND hf_data_master.item_pack_size_id = in_item
AND hf_data_master.reporting_start_date = DATE_ADD(in_rpt_date, INTERVAL -1 MONTH) LIMIT 1;
SELECT
	hf_data_master.pk_id,
	COALESCE(hf_data_master.opening_balance, NULL, 0),
	COALESCE(hf_data_master.received_balance, NULL, 0),
	COALESCE(hf_data_master.issue_balance, NULL, 0),
	COALESCE(hf_data_master.closing_balance, NULL, 0),
	COALESCE(hf_data_master.wastages, NULL, 0)
INTO wID, OB, receive, issue, CB, wastages
FROM
	hf_data_master
WHERE
	hf_data_master.warehouse_id = in_wh
AND hf_data_master.item_pack_size_id = in_item
AND hf_data_master.reporting_start_date = in_rpt_date LIMIT 1;
SET CB = (preMonthCB + receive) - (issue + wastages);
IF (wID IS NULL) THEN
	INSERT INTO hf_data_master SET
	`reporting_start_date` = in_rpt_date,	
	`item_pack_size_id` = in_item,
	`warehouse_id` = in_wh,	
	`opening_balance` = preMonthCB,
  `closing_balance` = preMonthCB;
ELSE
	# Run update query
	UPDATE hf_data_master SET
		`opening_balance` = preMonthCB,
		`received_balance` = receive,
		`issue_balance` = issue,
		`closing_balance` = CB,
		`wastages` = wastages
	WHERE
		pk_id = wID;
END IF;
return 1;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPUpdateData
-- ----------------------------
DROP FUNCTION IF EXISTS `REPUpdateData`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPUpdateData`(in_month INTEGER, in_year INTEGER, in_item INTEGER, in_wh INTEGER, in_created_by INTEGER) RETURNS int(11)
    READS SQL DATA
    DETERMINISTIC
BEGIN
 
DECLARE openingb INTEGER;
DECLARE receive INTEGER;
DECLARE issue INTEGER;
DECLARE adjustment INTEGER;
DECLARE closingb INTEGER;
DECLARE lastrecid INTEGER;
DECLARE lastmonth INTEGER;
DECLARE lastyear INTEGER;
DECLARE noofdoses INTEGER;
SELECT  number_of_doses   INTO  noofdoses FROM  item_pack_sizes   WHERE  pk_id = in_item;
SELECT MONTH(DATE_ADD(CONCAT(in_year, '-', in_month, '-01'),INTERVAL -1 MONTH)) into lastmonth from DUAL;
SELECT YEAR(DATE_ADD(CONCAT(in_year, '-', in_month, '-01'),INTERVAL -1 MONTH)) into lastyear from DUAL;
SET openingb = REPgetCBWHData(lastmonth, lastyear, in_item, in_wh) * noofdoses;
SET receive = REPgetTransWHData(1,in_month, in_year, in_item, in_wh) * noofdoses;
SET issue = REPgetTransWHData(2,in_month, in_year, in_item, in_wh) * noofdoses;
SET adjustment = REPgetTransWHData(3,in_month, in_year, in_item, in_wh) * noofdoses;
SET closingb = (openingb + receive + adjustment) - issue;
DELETE hf_data_detail.* from  hf_data_detail,hf_data_master  WHERE hf_data_master.pk_id = hf_data_detail.hf_data_master_id AND MONTH(hf_data_master.reporting_start_date) =in_month and YEAR(hf_data_master.reporting_start_date)=in_year  and  hf_data_master.item_pack_size_id=in_item and  warehouse_id =in_wh;
DELETE from  hf_data_master  WHERE  MONTH(reporting_start_date) =in_month and YEAR(reporting_start_date)=in_year  and  item_pack_size_id=in_item and  warehouse_id =in_wh;
INSERT into  hf_data_master  (item_pack_size_id,warehouse_id,opening_balance,received_balance,issue_balance,closing_balance,adjustments,reporting_start_date,created_by,created_date,`status`) 
VALUES(in_item,in_wh,openingb,receive,issue,closingb,adjustment,CONCAT(in_year, '-', in_month, '-01'),in_created_by,DATE(NOW()),'0');
return 1;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPUpdateDataCampaign
-- ----------------------------
DROP FUNCTION IF EXISTS `REPUpdateDataCampaign`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `REPUpdateDataCampaign`(in_date DATE, in_item INTEGER, in_wh INTEGER, in_created_by INTEGER, in_c_id INTEGER) RETURNS int(11)
    READS SQL DATA
    DETERMINISTIC
BEGIN
DECLARE totalCoverage INTEGER;
DECLARE vialsGiven INTEGER;
DECLARE vialsExpired INTEGER;
DECLARE vialsUsed INTEGER;
DECLARE OB INTEGER;
DECLARE receive INTEGER;
DECLARE issue INTEGER;
DECLARE adjustment INTEGER;
DECLARE CB INTEGER;
DECLARE noOfDoses INTEGER;
DECLARE wastages INTEGER;
DECLARE dossesUsed INTEGER;
SELECT number_of_doses INTO noOfDoses FROM item_pack_sizes WHERE item_id = in_item LIMIT 1;
SELECT
	SUM(campaign_data.total_coverage),
	SUM(campaign_data.vials_given),
	SUM(campaign_data.vials_expired),
	SUM(campaign_data.vials_used)
INTO totalCoverage, vialsGiven, vialsExpired, vialsUsed
FROM
	campaign_data
WHERE
	campaign_data.warehouse_id = in_wh
AND campaign_data.campaign_id = in_c_id
AND campaign_data.item_pack_size_id = in_item
LIMIT 1;
SET OB = 0;
SET receive = vialsGiven * noOfDoses;
SET dossesUsed = vialsUsed * noOfDoses;
SET issue = totalCoverage;
SET wastages = (dossesUsed - totalCoverage) + (vialsExpired * noOfDoses);
SET CB = (OB + receive) - (dossesUsed + wastages);
DELETE from hf_data_master WHERE reporting_start_date=in_date and item_pack_size_id=in_item and warehouse_id=in_wh;
INSERT into hf_data_master (reporting_start_date,item_pack_size_id,warehouse_id,opening_balance,received_balance,issue_balance,closing_balance,wastages,vials_used,created_by,created_date) 
VALUES(in_date,in_item,in_wh,OB,receive,dossesUsed,CB,wastages,vialsUsed,in_created_by,NOW());
return 1;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for REPUpdateRequirement
-- ----------------------------
DROP FUNCTION IF EXISTS `REPUpdateRequirement`;
DELIMITER ;;
CREATE DEFINER=`root`@`115.186.139.239` FUNCTION `REPUpdateRequirement`(in_wh INTEGER, in_start_date DATE) RETURNS int(11)
    READS SQL DATA
    DETERMINISTIC
BEGIN
DECLARE required4 DOUBLE;
DECLARE required20 DOUBLE;
DECLARE warehouseType INTEGER;
DECLARE birthsPerYear INTEGER;
DECLARE WomenCBA INTEGER;
DECLARE itemId INTEGER;
DECLARE tempType INTEGER;
DECLARE exitLoop INTEGER;
DECLARE packVolume DOUBLE;
DECLARE dosePeryear DOUBLE;
DECLARE populationFactor DOUBLE;
DECLARE multiplier DOUBLE;
DECLARE wastagesAllowed DOUBLE;
DECLARE TargetNumber DOUBLE;
DECLARE itemsCursor CURSOR FOR
SELECT
	warehouse_vaccine_storage_types.item_id,
	warehouse_vaccine_storage_types.temperature_type,
	items.pack_volume,
	items.doses_per_year,
	items.target_population_factor,
	items.multiplier,
	items.wastage_rate_allowed
FROM
	warehouse_vaccine_storage_types
INNER JOIN items ON warehouse_vaccine_storage_types.item_id = items.pk_id
WHERE
	warehouse_vaccine_storage_types.warehouse_type_id = getWarehouseType(in_wh)
AND items.item_category_id = 1 
LIMIT 1;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET exitLoop = 1;
SELECT
	warehouse_population.live_births_per_year INTO birthsPerYear
FROM
	warehouse_population
WHERE
	warehouse_population.warehouse_id = in_wh
  LIMIT 1;
SELECT
	warehouse_population.women_of_child_bearing_age INTO WomenCBA
FROM
	warehouse_population
WHERE
	warehouse_population.warehouse_id = in_wh 
  and YEAR(warehouse_population.estimation_year) = YEAR(in_start_date) 
 LIMIT 1;
SET required4 = 0;
SET required20 = 0;
OPEN itemsCursor;
	itemsLoop: LOOP
		FETCH itemsCursor INTO itemId, tempType, packVolume, dosePeryear, populationFactor, multiplier, wastagesAllowed;
	 
			-- check if the exit_loop flag has been set by mysql, 
			-- if it has been set we close the cursor and exit 
			-- the loop
			IF exitLoop THEN
					CLOSE itemsCursor;
					LEAVE itemsLoop;
			END IF;
				CASE 
				WHEN (itemId = 11) THEN
					SET TargetNumber=WomenCBA;
				ELSE
					SET TargetNumber=birthsPerYear;
				END CASE;
			IF(tempType = 20) THEN
				SET required20 = required20 + ((multiplier * TargetNumber / 100) * dosePeryear * packVolume * (1 / (1-(wastagesAllowed / 100))) / 1000 / 12);
			ELSE 
				SET required4 = required4 + ((multiplier * TargetNumber / 100) * dosePeryear * packVolume * (1 / (1-(wastagesAllowed / 100))) / 1000 / 12);
			END IF;			
	 
	END LOOP itemsLoop;
	UPDATE warehouse_population SET requirments_4degree = required4, requirments_20degree = required20 WHERE warehouse_id = in_wh AND YEAR(estimation_year) = YEAR(in_start_date);
return 1;
END
;;
DELIMITER ;

-- ----------------------------
-- Function structure for UTILgetLimitParam
-- ----------------------------
DROP FUNCTION IF EXISTS `UTILgetLimitParam`;
DELIMITER ;;
CREATE DEFINER=`vlmisr2user`@`localhost` FUNCTION `UTILgetLimitParam`(`in_month` int,`in_year` int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE retval INTEGER;  
  DECLARE start_date VARCHAR(20);  
  SELECT  system.start_date into start_date from system where pk_id = 1;
  SET retval = PERIOD_DIFF(CONCAT(in_year, LPAD(in_month,2,'0')),start_date) + 1;
  RETURN(retval);  
 END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Add Placements`;
DELIMITER ;;
CREATE TRIGGER `Add Placements` AFTER INSERT ON `placements` FOR EACH ROW BEGIN
    CALL PlacementSummary(NEW.placement_location_id,NEW.stock_batch_warehouse_id);
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Update Placements`;
DELIMITER ;;
CREATE TRIGGER `Update Placements` AFTER UPDATE ON `placements` FOR EACH ROW BEGIN
        CALL PlacementSummary(OLD.placement_location_id,NEW.stock_batch_warehouse_id);
        CALL PlacementSummary(NEW.placement_location_id,NEW.stock_batch_warehouse_id);
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Delete Placements`;
DELIMITER ;;
CREATE TRIGGER `Delete Placements` AFTER DELETE ON `placements` FOR EACH ROW BEGIN
    CALL PlacementSummary(OLD.placement_location_id,OLD.stock_batch_warehouse_id);
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Add Stock Batch`;
DELIMITER ;;
CREATE TRIGGER `Add Stock Batch` AFTER INSERT ON `stock_batch_warehouses` FOR EACH ROW BEGIN
    CALL REPStockBatchHistory(NEW.pk_id, 1);
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Update Stock Batch`;
DELIMITER ;;
CREATE TRIGGER `Update Stock Batch` AFTER UPDATE ON `stock_batch_warehouses` FOR EACH ROW BEGIN
    IF (NEW.stock_batch_id != OLD.stock_batch_id OR NEW.warehouse_id != OLD.warehouse_id) THEN
        CALL REPStockBatchHistory(NEW.pk_id, 2);
    END IF;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Delete Stock Batch`;
DELIMITER ;;
CREATE TRIGGER `Delete Stock Batch` BEFORE DELETE ON `stock_batch_warehouses` FOR EACH ROW BEGIN
    CALL REPStockBatchHistory(OLD.pk_id, 3);
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Add Stock Detail`;
DELIMITER ;;
CREATE TRIGGER `Add Stock Detail` AFTER INSERT ON `stock_detail` FOR EACH ROW BEGIN
    CALL REPAdjustQty(NEW.stock_batch_warehouse_id);
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Update Stock Detail`;
DELIMITER ;;
CREATE TRIGGER `Update Stock Detail` AFTER UPDATE ON `stock_detail` FOR EACH ROW BEGIN
    CALL REPStockDetailHistory(NEW.pk_id, 2);
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Delete Stock Detail`;
DELIMITER ;;
CREATE TRIGGER `Delete Stock Detail` BEFORE DELETE ON `stock_detail` FOR EACH ROW BEGIN
    CALL REPStockDetailHistory(OLD.pk_id, 3);
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Adjust Quantity`;
DELIMITER ;;
CREATE TRIGGER `Adjust Quantity` AFTER DELETE ON `stock_detail` FOR EACH ROW BEGIN
    CALL REPAdjustQty(OLD.stock_batch_warehouse_id);
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Update Stock Master`;
DELIMITER ;;
CREATE TRIGGER `Update Stock Master` AFTER UPDATE ON `stock_master` FOR EACH ROW BEGIN
    CALL REPStockMasterHistory(NEW.pk_id, 2);
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Delete Stock Master`;
DELIMITER ;;
CREATE TRIGGER `Delete Stock Master` BEFORE DELETE ON `stock_master` FOR EACH ROW BEGIN
    CALL REPStockMasterHistory(OLD.pk_id, 3);
END
;;
DELIMITER ;
SET FOREIGN_KEY_CHECKS=1;
