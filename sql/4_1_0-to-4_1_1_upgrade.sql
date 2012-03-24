--
--  Comment Meta Language Constructs:
--
--  #IfNotTable
--    argument: table_name
--    behavior: if the table_name does not exist,  the block will be executed

--  #IfTable
--    argument: table_name
--    behavior: if the table_name does exist, the block will be executed

--  #IfMissingColumn
--    arguments: table_name colname
--    behavior:  if the colname in the table_name table does not exist,  the block will be executed

--  #IfNotColumnType
--    arguments: table_name colname value
--    behavior:  If the table table_name does not have a column colname with a data type equal to value, then the block will be executed

--  #IfNotRow
--    arguments: table_name colname value
--    behavior:  If the table table_name does not have a row where colname = value, the block will be executed.

--  #IfNotRow2D
--    arguments: table_name colname value colname2 value2
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2, the block will be executed.

--  #IfNotRow3D
--    arguments: table_name colname value colname2 value2 colname3 value3
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3, the block will be executed.

--  #IfNotRow4D
--    arguments: table_name colname value colname2 value2 colname3 value3 colname4 value4
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3 AND colname4 = value4, the block will be executed.

--  #IfNotRow2Dx2
--    desc:      This is a very specialized function to allow adding items to the list_options table to avoid both redundant option_id and title in each element.
--    arguments: table_name colname value colname2 value2 colname3 value3
--    behavior:  The block will be executed if both statements below are true:
--               1) The table table_name does not have a row where colname = value AND colname2 = value2.
--               2) The table table_name does not have a row where colname = value AND colname3 = value3.

--  #IfRow2D
--   arguments: table_name colname value colname2 value2
--    behavior:  If the table table_name does have a row where colname = value AND colname2 = value2, the block will be executed.

--  #IfNotIndex
--    desc:      This function will allow adding of indexes/keys.
--    arguments: table_name colname
--    behavior:  If the index does not exist, it will be created

--  #EndIf
--    all blocks are terminated with and #EndIf statement.


#IfNotIndex lists type
CREATE INDEX `type` ON `lists` (`type`);
#EndIf

#IfNotIndex lists pid
CREATE INDEX `pid` ON `lists` (`pid`);
#EndIf

#IfNotIndex form_vitals pid
CREATE INDEX `pid` ON `form_vitals` (`pid`);
#EndIf

#IfNotIndex forms pid
CREATE INDEX `pid` ON `forms` (`pid`);
#EndIf

#IfNotIndex form_encounter pid
CREATE INDEX `pid` ON `form_encounter` (`pid`);
#EndIf

#IfNotIndex immunizations patient_id
CREATE INDEX `patient_id` ON `immunizations` (`patient_id`);
#EndIf

#IfNotIndex procedure_order patient_id
CREATE INDEX `patient_id` ON `procedure_order` (`patient_id`);
#EndIf

#IfNotIndex pnotes pid
CREATE INDEX `pid` ON `pnotes` (`pid`);
#EndIf

#IfNotIndex transactions pid
CREATE INDEX `pid` ON `transactions` (`pid`);
#EndIf

#IfNotIndex extended_log patient_id
CREATE INDEX `patient_id` ON `extended_log` (`patient_id`);
#EndIf

#IfNotIndex prescriptions patient_id
CREATE INDEX `patient_id` ON `prescriptions` (`patient_id`);
#EndIf

#IfMissingColumn version v_realpatch
ALTER TABLE `version` ADD COLUMN `v_realpatch` int(11) NOT NULL DEFAULT 0;
#EndIf

#IfMissingColumn prescriptions drug_info_erx
ALTER TABLE `prescriptions` ADD COLUMN `drug_info_erx` TEXT DEFAULT NULL;
#EndIf

#IfNotRow2D list_options list_id lists option_id nation_notes_replace_buttons
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`) VALUES ('lists','nation_notes_replace_buttons','Nation Notes Replace Buttons',1);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`) VALUES ('nation_notes_replace_buttons','Yes','Yes',10);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`) VALUES ('nation_notes_replace_buttons','No','No',20);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`) VALUES ('nation_notes_replace_buttons','Normal','Normal',30);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`) VALUES ('nation_notes_replace_buttons','Abnormal','Abnormal',40);
#EndIf

#IfMissingColumn insurance_data policy_type
ALTER TABLE `insurance_data` ADD COLUMN `policy_type` varchar(25) NOT NULL default '';
#EndIf

#IfMissingColumn drugs max_level
ALTER TABLE drugs ADD max_level float NOT NULL DEFAULT 0.0;
ALTER TABLE drugs CHANGE reorder_point reorder_point float NOT NULL DEFAULT 0.0;
#EndIf

#IfNotTable product_warehouse
CREATE TABLE `product_warehouse` (
  `pw_drug_id`   int(11) NOT NULL,
  `pw_warehouse` varchar(31) NOT NULL,
  `pw_min_level` float       DEFAULT 0,
  `pw_max_level` float       DEFAULT 0,
  PRIMARY KEY  (`pw_drug_id`,`pw_warehouse`)
) ENGINE=MyISAM;
#EndIf

# Increase size from 5 to 12 to support 4 modifiers with colon separation
#IfNotColumnType billing modifier varchar(12)
   ALTER TABLE `billing` MODIFY `modifier` varchar(12);
   UPDATE `code_types` SET `ct_mod` = '12' where ct_key = 'CPT4' OR ct_key = 'HCPCS';
#Endif

#IfMissingColumn billing notecodes
ALTER TABLE `billing` ADD `notecodes` varchar(25) NOT NULL default '';
#EndIf



#IfNotTable dated_reminders
CREATE TABLE `dated_reminders` (
            `dr_id` int(11) NOT NULL AUTO_INCREMENT,
            `dr_from_ID` int(11) NOT NULL,
            `dr_message_text` varchar(160) NOT NULL,
            `dr_message_sent_date` datetime NOT NULL,
            `dr_message_due_date` date NOT NULL,
            `pid` int(11) NOT NULL,
            `message_priority` tinyint(1) NOT NULL,
            `message_processed` tinyint(1) NOT NULL DEFAULT '0',
            `processed_date` timestamp NULL DEFAULT NULL,
            `dr_processed_by` int(11) NOT NULL,
            PRIMARY KEY (`dr_id`),
            KEY `dr_from_ID` (`dr_from_ID`,`dr_message_due_date`)
          ) ENGINE=MyISAM AUTO_INCREMENT=1;
#EndIf

#IfNotTable dated_reminders_link
CREATE TABLE `dated_reminders_link` (
            `dr_link_id` int(11) NOT NULL AUTO_INCREMENT,
            `dr_id` int(11) NOT NULL,
            `to_id` int(11) NOT NULL,
            PRIMARY KEY (`dr_link_id`),
            KEY `to_id` (`to_id`),
            KEY `dr_id` (`dr_id`)
          ) ENGINE=MyISAM AUTO_INCREMENT=1;
#EndIf

#IfMissingColumn x12_partners x12_gs03
ALTER TABLE `x12_partners` ADD COLUMN `x12_gs03` VARCHAR(15) NOT NULL DEFAULT '';
#EndIf

#IfRow2D billing code_type COPAY activity 1
DROP TABLE IF EXISTS `temp_table_one`;
CREATE TABLE `temp_table_one` (
  id             int unsigned  NOT NULL AUTO_INCREMENT,
  session_id     int unsigned  NOT NULL,
  payer_id       int(11)       NOT NULL DEFAULT 0,
  user_id        int(11)       NOT NULL,
  pay_total      decimal(12,2) NOT NULL DEFAULT 0,
  payment_type varchar( 50 ) NOT NULL DEFAULT 'patient',
  description text NOT NULL,
  adjustment_code varchar( 50 ) NOT NULL DEFAULT 'patient_payment',
  post_to_date date NOT NULL,
  patient_id int( 11 ) NOT NULL,
  payment_method varchar( 25 ) NOT NULL DEFAULT 'cash',
  pid            int(11)       NOT NULL,
  encounter      int(11)       NOT NULL,
  code           varchar(9)    NOT NULL,
  modifier       varchar(5)    NOT NULL DEFAULT '',
  payer_type     int           NOT NULL DEFAULT 0,
  post_time      datetime      NOT NULL,
  post_user      int(11)       NOT NULL,
  pay_amount     decimal(12,2) NOT NULL DEFAULT 0,
  account_code varchar(15) NOT NULL DEFAULT 'PCP',
  PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=1;
INSERT INTO `temp_table_one` (`user_id`, `pay_total`, `patient_id`, `post_to_date`, `pid`, `encounter`, `post_time`, `post_user`, `pay_amount`, `description`) SELECT `user`, (`fee`*-1), `pid`, `date`, `pid`, `encounter`, `date`, `user`, (`fee`*-1), 'COPAY' FROM `billing` WHERE `code_type`='COPAY' AND `activity`!=0;
UPDATE `temp_table_one` SET `session_id`= ((SELECT MAX(session_id) FROM ar_session)+`id`);
UPDATE `billing`, `code_types`, `temp_table_one` SET temp_table_one.code=billing.code, temp_table_one.modifier=billing.modifier WHERE billing.code_type=code_types.ct_key AND code_types.ct_fee=1 AND temp_table_one.pid=billing.pid AND temp_table_one.encounter=billing.encounter AND billing.activity!=0;
INSERT INTO `ar_session` (`payer_id`, `user_id`, `pay_total`, `payment_type`, `description`, `patient_id`, `payment_method`, `adjustment_code`, `post_to_date`) SELECT `payer_id`, `user_id`, `pay_total`, `payment_type`, `description`, `patient_id`, `payment_method`, `adjustment_code`, `post_to_date` FROM `temp_table_one`; 
INSERT INTO `ar_activity` (`pid`, `encounter`, `code`, `modifier`, `payer_type`, `post_time`, `post_user`, `session_id`, `pay_amount`, `account_code`) SELECT `pid`, `encounter`, `code`, `modifier`, `payer_type`, `post_time`, `post_user`, `session_id`, `pay_amount`, `account_code` FROM `temp_table_one`;
UPDATE `billing` SET `activity`=0 WHERE `code_type`='COPAY';
DROP TABLE IF EXISTS `temp_table_one`;
#EndIf

