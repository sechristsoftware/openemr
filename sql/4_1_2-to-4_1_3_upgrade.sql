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
--    behavior:  if the table exists but the column does not,  the block will be executed

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
--    arguments: table_name colname value colname2 value2
--    behavior:  If the table table_name does have a row where colname = value AND colname2 = value2, the block will be executed.

--  #IfIndex
--    desc:      This function is most often used for dropping of indexes/keys.
--    arguments: table_name colname
--    behavior:  If the table and index exist the relevant statements are executed, otherwise not.

--  #IfNotIndex
--    desc:      This function will allow adding of indexes/keys.
--    arguments: table_name colname
--    behavior:  If the index does not exist, it will be created

--  #EndIf
--    all blocks are terminated with a #EndIf statement.

#IfNotRow4D supported_external_dataloads load_type ICD9 load_source CMS load_release_date 2013-10-01 load_filename cmsv31-master-descriptions.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD9', 'CMS', '2013-10-01', 'cmsv31-master-descriptions.zip', 'fe0d7f9a5338f5ff187683b4737ad2b7');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2012-10-01 load_filename 2013_PCS_long_and_abbreviated_titles.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2012-10-01', '2013_PCS_long_and_abbreviated_titles.zip', '04458ed0631c2c122624ee0a4ca1c475');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2012-10-01 load_filename 2013-DiagnosisGEMs.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2012-10-01', '2013-DiagnosisGEMs.zip', '773aac2a675d6aefd1d7dd149883be51');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2012-10-01 load_filename ICD10CMOrderFiles_2013.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2012-10-01', 'ICD10CMOrderFiles_2013.zip', '1c175a858f833485ef8f9d3e66b4d8bd');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2012-10-01 load_filename ProcedureGEMs_2013.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2012-10-01', 'ProcedureGEMs_2013.zip', '92aa7640e5ce29b9629728f7d4fc81db');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2012-10-01 load_filename 2013-ReimbursementMapping_dx.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2012-10-01', '2013-ReimbursementMapping_dx.zip', '0d5d36e3f4519bbba08a9508576787fb');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2012-10-01 load_filename ReimbursementMapping_pr_2013.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2012-10-01', 'ReimbursementMapping_pr_2013.zip', '4c3920fedbcd9f6af54a1dc9069a11ca');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2013-10-01 load_filename 2014-PCS-long-and-abbreviated-titles.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2013-10-01', '2014-PCS-long-and-abbreviated-titles.zip', '2d03514a0c66d92cf022a0bc28c83d38');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2013-10-01 load_filename DiagnosisGEMs-2014.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2013-10-01', 'DiagnosisGEMs-2014.zip', '3ed7b7c5a11c766102b12d97d777a11b');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2013-10-01 load_filename 2014-ICD10-Code-Descriptions.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2013-10-01', '2014-ICD10-Code-Descriptions.zip', '5458b95f6f37228b5cdfa03aefc6c8bb');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2013-10-01 load_filename ProcedureGEMs-2014.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2013-10-01', 'ProcedureGEMs-2014.zip', 'be46de29f4f40f97315d04821273acf9');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2013-10-01 load_filename 2014-Reimbursement-Mappings-DX.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2013-10-01', '2014-Reimbursement-Mappings-DX.zip', '614b3957304208e3ef7d3ba8b3618888');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2013-10-01 load_filename 2014-Reimbursement-Mappings-PR.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2013-10-01', '2014-Reimbursement-Mappings-PR.zip', 'f306a0e8c9edb34d28fd6ce8af82b646');
#EndIf

#IfMissingColumn patient_data email_direct
ALTER TABLE `patient_data` ADD COLUMN `email_direct` varchar(255) NOT NULL default '';
INSERT INTO `layout_options` (`form_id`, `field_id`, `group_name`, `title`, `seq`, `data_type`, `uor`, `fld_length`, `max_length`, `list_id`, `titlecols`, `datacols`, `default_value`, `edit_options`, `description`, `fld_rows`) VALUES('DEM', 'email_direct', '2Contact', 'Trusted Email', 14, 2, 1, 30, 95, '', 1, 1, '', '', 'Trusted (Direct) Email Address', 0);
#EndIf

#IfMissingColumn users email_direct
ALTER TABLE `users` ADD COLUMN `email_direct` varchar(255) NOT NULL default '';
#EndIf

#IfNotTable erx_ttl_touch
CREATE TABLE `erx_ttl_touch` (
  `patient_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Patient record Id', 
  `process` ENUM('allergies','medications') NOT NULL COMMENT 'NewCrop eRx SOAP process',
  `updated` DATETIME NOT NULL COMMENT 'Date and time of last process update for patient', 
  PRIMARY KEY (`patient_id`, `process`) ) 
ENGINE = InnoDB COMMENT = 'Store records last update per patient data process';
#EndIf

#IfMissingColumn form_misc_billing_options box_14_date_qual
ALTER TABLE `form_misc_billing_options` 
ADD COLUMN `box_14_date_qual` CHAR(3) NULL DEFAULT NULL;
#EndIf

#IfMissingColumn form_misc_billing_options box_15_date_qual
ALTER TABLE `form_misc_billing_options` 
ADD COLUMN `box_15_date_qual` CHAR(3) NULL DEFAULT NULL;
#EndIf

#IfNotTable esign_signatures
CREATE TABLE `esign_signatures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL COMMENT 'Table row ID for signature',
  `table` varchar(255) NOT NULL COMMENT 'table name for the signature',
  `uid` int(11) NOT NULL COMMENT 'user id for the signing user',
  `datetime` datetime NOT NULL COMMENT 'datetime of the signature action',
  `is_lock` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'sig, lock or amendment',
  `amendment` text COMMENT 'amendment text, if any',
  `hash` varchar(255) NOT NULL COMMENT 'hash of signed data',
  `signature_hash` varchar(255) NOT NULL COMMENT 'hash of signature itself',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `table` (`table`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#EndIf

#IfNotRow2D list_options list_id language option_id declne_to_specfy
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'declne_to_specfy', 'Declined To Specify', 0, 0, 0);
#EndIf

#IfNotRow2D list_options list_id ethrace option_id declne_to_specfy
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('ethrace', 'declne_to_specfy', 'Declined To Specify', 95, 0, 0);
#EndIf

#IfNotRow2D list_options list_id race option_id declne_to_specfy
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('race', 'declne_to_specfy', 'Declined To Specify', 35, 0, 0);
#EndIf

#IfNotRow3D layout_options field_id race data_type 36 form_id DEM
UPDATE  `layout_options` SET  `data_type` =  '36' WHERE  `layout_options`.`form_id` =  'DEM' AND  `layout_options`.`field_id` =  'race';
#EndIf

#IfNotRow3D layout_options field_id language data_type 1 form_id DEM
UPDATE  `layout_options` SET  `data_type` =  '1' WHERE  `layout_options`.`form_id` =  'DEM' AND  `layout_options`.`field_id` =  'language';
#EndIf

#IfNotRow2D list_options list_id language option_id afar
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'afar', 'Afar', 1, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id afar notes aar
UPDATE `list_options` SET `list_options`.`notes` = 'aar' WHERE `list_options`.`option_id` = 'afar';
#EndIf

#IfNotRow2D list_options list_id language option_id abkhazian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'abkhazian', 'Abkhazian', 2, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id abkhazian notes abk
UPDATE `list_options` SET `list_options`.`notes` = 'abk' WHERE `list_options`.`option_id` = 'abkhazian';
#EndIf

#IfNotRow2D list_options list_id language option_id afrikaans
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'afrikaans', 'Afrikaans', 3, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id afrikaans notes afr
UPDATE `list_options` SET `list_options`.`notes` = 'afr' WHERE `list_options`.`option_id` = 'afrikaans';
#EndIf

#IfNotRow2D list_options list_id language option_id akan
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'akan', 'Akan', 4, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id akan notes aka
UPDATE `list_options` SET `list_options`.`notes` = 'aka' WHERE `list_options`.`option_id` = 'akan';
#EndIf

#IfNotRow2D list_options list_id language option_id albanian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'albanian', 'Albanian', 5, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id albanian notes alb (B)|sqi (T)
UPDATE `list_options` SET `list_options`.`notes` = 'alb (B)|sqi (T)' WHERE `list_options`.`option_id` = 'albanian';
#EndIf

#IfNotRow2D list_options list_id language option_id amharic
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'amharic', 'Amharic', 6, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id amharic notes amh
UPDATE `list_options` SET `list_options`.`notes` = 'amh' WHERE `list_options`.`option_id` = 'amharic';
#EndIf

#IfNotRow2D list_options list_id language option_id arabic
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'arabic', 'Arabic', 7, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id arabic notes ara
UPDATE `list_options` SET `list_options`.`notes` = 'ara' WHERE `list_options`.`option_id` = 'arabic';
#EndIf

#IfNotRow2D list_options list_id language option_id aragonese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'aragonese', 'Aragonese', 8, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id aragonese notes arg
UPDATE `list_options` SET `list_options`.`notes` = 'arg' WHERE `list_options`.`option_id` = 'aragonese';
#EndIf

#IfNotRow2D list_options list_id language option_id armenian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'armenian', 'Armenian', 9, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id armenian notes arm (B)|hye (T)
UPDATE `list_options` SET `list_options`.`notes` = 'arm (B)|hye (T)' WHERE `list_options`.`option_id` = 'armenian';
#EndIf

#IfNotRow2D list_options list_id language option_id assamese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'assamese', 'Assamese', 10, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id assamese notes asm
UPDATE `list_options` SET `list_options`.`notes` = 'asm' WHERE `list_options`.`option_id` = 'assamese';
#EndIf

#IfNotRow2D list_options list_id language option_id avaric
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'avaric', 'Avaric', 11, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id avaric notes ava
UPDATE `list_options` SET `list_options`.`notes` = 'ava' WHERE `list_options`.`option_id` = 'avaric';
#EndIf

#IfNotRow2D list_options list_id language option_id avestan
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'avestan', 'Avestan', 12, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id avestan notes ave
UPDATE `list_options` SET `list_options`.`notes` = 'ave' WHERE `list_options`.`option_id` = 'avestan';
#EndIf

#IfNotRow2D list_options list_id language option_id aymara
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'aymara', 'Aymara', 13, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id aymara notes aym
UPDATE `list_options` SET `list_options`.`notes` = 'aym' WHERE `list_options`.`option_id` = 'aymara';
#EndIf

#IfNotRow2D list_options list_id language option_id azerbaijani
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'azerbaijani', 'Azerbaijani', 14, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id azerbaijani notes aze
UPDATE `list_options` SET `list_options`.`notes` = 'aze' WHERE `list_options`.`option_id` = 'azerbaijani';
#EndIf

#IfNotRow2D list_options list_id language option_id bashkir
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'bashkir', 'Bashkir', 15, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id bashkir notes bak
UPDATE `list_options` SET `list_options`.`notes` = 'bak' WHERE `list_options`.`option_id` = 'bashkir';
#EndIf

#IfNotRow2D list_options list_id language option_id bambara
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'bambara', 'Bambara', 16, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id bambara notes bam
UPDATE `list_options` SET `list_options`.`notes` = 'bam' WHERE `list_options`.`option_id` = 'bambara';
#EndIf

#IfNotRow2D list_options list_id language option_id basque
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'basque', 'Basque', 17, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id basque notes baq (B)|eus (T)
UPDATE `list_options` SET `list_options`.`notes` = 'baq (B)|eus (T)' WHERE `list_options`.`option_id` = 'basque';
#EndIf

#IfNotRow2D list_options list_id language option_id belarusian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'belarusian', 'Belarusian', 18, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id belarusian notes bel
UPDATE `list_options` SET `list_options`.`notes` = 'bel' WHERE `list_options`.`option_id` = 'belarusian';
#EndIf

#IfNotRow2D list_options list_id language option_id bengali
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'bengali', 'Bengali', 19, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id bengali notes ben
UPDATE `list_options` SET `list_options`.`notes` = 'ben' WHERE `list_options`.`option_id` = 'bengali';
#EndIf

#IfNotRow2D list_options list_id language option_id bihari languages
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'bihari languages', 'Bihari languages', 20, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id bihari languages notes bih
UPDATE `list_options` SET `list_options`.`notes` = 'bih' WHERE `list_options`.`option_id` = 'bihari languages';
#EndIf

#IfNotRow2D list_options list_id language option_id bislama
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'bislama', 'Bislama', 21, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id bislama notes bis
UPDATE `list_options` SET `list_options`.`notes` = 'bis' WHERE `list_options`.`option_id` = 'bislama';
#EndIf

#IfNotRow2D list_options list_id language option_id tibetan
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'tibetan', 'Tibetan', 22, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id tibetan notes tib (B)|bod (T)
UPDATE `list_options` SET `list_options`.`notes` = 'tib (B)|bod (T)' WHERE `list_options`.`option_id` = 'tibetan';
#EndIf

#IfNotRow2D list_options list_id language option_id bosnian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'bosnian', 'Bosnian', 23, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id bosnian notes bos
UPDATE `list_options` SET `list_options`.`notes` = 'bos' WHERE `list_options`.`option_id` = 'bosnian';
#EndIf

#IfNotRow2D list_options list_id language option_id breton
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'breton', 'Breton', 24, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id breton notes bre
UPDATE `list_options` SET `list_options`.`notes` = 'bre' WHERE `list_options`.`option_id` = 'breton';
#EndIf

#IfNotRow2D list_options list_id language option_id bulgarian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'bulgarian', 'Bulgarian', 25, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id bulgarian notes bul
UPDATE `list_options` SET `list_options`.`notes` = 'bul' WHERE `list_options`.`option_id` = 'bulgarian';
#EndIf

#IfNotRow2D list_options list_id language option_id burmese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'burmese', 'Burmese', 26, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id burmese notes bur (B)|mya (T)
UPDATE `list_options` SET `list_options`.`notes` = 'bur (B)|mya (T)' WHERE `list_options`.`option_id` = 'burmese';
#EndIf

#IfNotRow2D list_options list_id language option_id catalan; valencian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'catalan; valencian', 'Catalan; Valencian', 27, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id catalan; valencian notes cat
UPDATE `list_options` SET `list_options`.`notes` = 'cat' WHERE `list_options`.`option_id` = 'catalan; valencian';
#EndIf

#IfNotRow2D list_options list_id language option_id czech
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'czech', 'Czech', 28, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id czech notes cze (B)|ces (T)
UPDATE `list_options` SET `list_options`.`notes` = 'cze (B)|ces (T)' WHERE `list_options`.`option_id` = 'czech';
#EndIf

#IfNotRow2D list_options list_id language option_id chamorro
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'chamorro', 'Chamorro', 29, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id chamorro notes cha
UPDATE `list_options` SET `list_options`.`notes` = 'cha' WHERE `list_options`.`option_id` = 'chamorro';
#EndIf

#IfNotRow2D list_options list_id language option_id chechen
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'chechen', 'Chechen', 30, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id chechen notes che
UPDATE `list_options` SET `list_options`.`notes` = 'che' WHERE `list_options`.`option_id` = 'chechen';
#EndIf

#IfNotRow2D list_options list_id language option_id chinese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'chinese', 'Chinese', 31, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id chinese notes chi (B)|zho (T)
UPDATE `list_options` SET `list_options`.`notes` = 'chi (B)|zho (T)' WHERE `list_options`.`option_id` = 'chinese';
#EndIf

#IfNotRow2D list_options list_id language option_id church slavic; old slavonic; church slavonic; old bulgarian; old church slavonic
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'church slavic; old slavonic; church slavonic; old bulgarian; old church slavonic', 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic', 32, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id church slavic; old slavonic; church slavonic; old bulgarian; old church slavonic notes chu
UPDATE `list_options` SET `list_options`.`notes` = 'chu' WHERE `list_options`.`option_id` = 'church slavic; old slavonic; church slavonic; old bulgarian; old church slavonic';
#EndIf

#IfNotRow2D list_options list_id language option_id chuvash
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'chuvash', 'Chuvash', 33, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id chuvash notes chv
UPDATE `list_options` SET `list_options`.`notes` = 'chv' WHERE `list_options`.`option_id` = 'chuvash';
#EndIf

#IfNotRow2D list_options list_id language option_id cornish
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'cornish', 'Cornish', 34, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id cornish notes cor
UPDATE `list_options` SET `list_options`.`notes` = 'cor' WHERE `list_options`.`option_id` = 'cornish';
#EndIf

#IfNotRow2D list_options list_id language option_id corsican
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'corsican', 'Corsican', 35, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id corsican notes cos
UPDATE `list_options` SET `list_options`.`notes` = 'cos' WHERE `list_options`.`option_id` = 'corsican';
#EndIf

#IfNotRow2D list_options list_id language option_id cree
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'cree', 'Cree', 36, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id cree notes cre
UPDATE `list_options` SET `list_options`.`notes` = 'cre' WHERE `list_options`.`option_id` = 'cree';
#EndIf

#IfNotRow2D list_options list_id language option_id welsh
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'welsh', 'Welsh', 37, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id welsh notes wel (B)|cym (T)
UPDATE `list_options` SET `list_options`.`notes` = 'wel (B)|cym (T)' WHERE `list_options`.`option_id` = 'welsh';
#EndIf

#IfNotRow2D list_options list_id language option_id danish
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'danish', 'Danish', 38, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id danish notes dan
UPDATE `list_options` SET `list_options`.`notes` = 'dan' WHERE `list_options`.`option_id` = 'danish';
#EndIf

#IfNotRow2D list_options list_id language option_id german
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'german', 'German', 39, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id german notes ger (B)|deu (T)
UPDATE `list_options` SET `list_options`.`notes` = 'ger (B)|deu (T)' WHERE `list_options`.`option_id` = 'german';
#EndIf

#IfNotRow2D list_options list_id language option_id divehi; dhivehi; maldivian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'divehi; dhivehi; maldivian', 'Divehi; Dhivehi; Maldivian', 40, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id divehi; dhivehi; maldivian notes div
UPDATE `list_options` SET `list_options`.`notes` = 'div' WHERE `list_options`.`option_id` = 'divehi; dhivehi; maldivian';
#EndIf

#IfNotRow2D list_options list_id language option_id dutch; flemish
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'dutch; flemish', 'Dutch; Flemish', 41, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id dutch; flemish notes dut (B)|nld (T)
UPDATE `list_options` SET `list_options`.`notes` = 'dut (B)|nld (T)' WHERE `list_options`.`option_id` = 'dutch; flemish';
#EndIf

#IfNotRow2D list_options list_id language option_id dzongkha
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'dzongkha', 'Dzongkha', 42, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id dzongkha notes dzo
UPDATE `list_options` SET `list_options`.`notes` = 'dzo' WHERE `list_options`.`option_id` = 'dzongkha';
#EndIf

#IfNotRow2D list_options list_id language option_id greek, modern (1453-)
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'greek, modern (1453-)', 'Greek, Modern (1453-)', 43, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id greek, modern (1453-) notes gre (B)|ell (T)
UPDATE `list_options` SET `list_options`.`notes` = 'gre (B)|ell (T)' WHERE `list_options`.`option_id` = 'greek, modern (1453-)';
#EndIf

#IfNotRow2D list_options list_id language option_id english
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'english', 'English', 44, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id english notes eng
UPDATE `list_options` SET `list_options`.`notes` = 'eng' WHERE `list_options`.`option_id` = 'english';
#EndIf

#IfNotRow2D list_options list_id language option_id esperanto
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'esperanto', 'Esperanto', 45, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id esperanto notes epo
UPDATE `list_options` SET `list_options`.`notes` = 'epo' WHERE `list_options`.`option_id` = 'esperanto';
#EndIf

#IfNotRow2D list_options list_id language option_id estonian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'estonian', 'Estonian', 46, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id estonian notes est
UPDATE `list_options` SET `list_options`.`notes` = 'est' WHERE `list_options`.`option_id` = 'estonian';
#EndIf

#IfNotRow2D list_options list_id language option_id ewe
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'ewe', 'Ewe', 47, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id ewe notes ewe
UPDATE `list_options` SET `list_options`.`notes` = 'ewe' WHERE `list_options`.`option_id` = 'ewe';
#EndIf

#IfNotRow2D list_options list_id language option_id faroese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'faroese', 'Faroese', 48, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id faroese notes fao
UPDATE `list_options` SET `list_options`.`notes` = 'fao' WHERE `list_options`.`option_id` = 'faroese';
#EndIf

#IfNotRow2D list_options list_id language option_id persian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'persian', 'Persian', 49, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id persian notes per (B)|fas (T)
UPDATE `list_options` SET `list_options`.`notes` = 'per (B)|fas (T)' WHERE `list_options`.`option_id` = 'persian';
#EndIf

#IfNotRow2D list_options list_id language option_id fijian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'fijian', 'Fijian', 50, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id fijian notes fij
UPDATE `list_options` SET `list_options`.`notes` = 'fij' WHERE `list_options`.`option_id` = 'fijian';
#EndIf

#IfNotRow2D list_options list_id language option_id finnish
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'finnish', 'Finnish', 51, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id finnish notes fin
UPDATE `list_options` SET `list_options`.`notes` = 'fin' WHERE `list_options`.`option_id` = 'finnish';
#EndIf

#IfNotRow2D list_options list_id language option_id french
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'french', 'French', 52, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id french notes fre (B)|fra (T)
UPDATE `list_options` SET `list_options`.`notes` = 'fre (B)|fra (T)' WHERE `list_options`.`option_id` = 'french';
#EndIf

#IfNotRow2D list_options list_id language option_id western frisian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'western frisian', 'Western Frisian', 53, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id western frisian notes fry
UPDATE `list_options` SET `list_options`.`notes` = 'fry' WHERE `list_options`.`option_id` = 'western frisian';
#EndIf

#IfNotRow2D list_options list_id language option_id fulah
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'fulah', 'Fulah', 54, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id fulah notes ful
UPDATE `list_options` SET `list_options`.`notes` = 'ful' WHERE `list_options`.`option_id` = 'fulah';
#EndIf

#IfNotRow2D list_options list_id language option_id georgian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'georgian', 'Georgian', 55, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id georgian notes geo (B)|kat (T)
UPDATE `list_options` SET `list_options`.`notes` = 'geo (B)|kat (T)' WHERE `list_options`.`option_id` = 'georgian';
#EndIf

#IfNotRow2D list_options list_id language option_id gaelic; scottish gaelic
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'gaelic; scottish gaelic', 'Gaelic; Scottish Gaelic', 56, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id gaelic; scottish gaelic notes gla
UPDATE `list_options` SET `list_options`.`notes` = 'gla' WHERE `list_options`.`option_id` = 'gaelic; scottish gaelic';
#EndIf

#IfNotRow2D list_options list_id language option_id irish
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'irish', 'Irish', 57, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id irish notes gle
UPDATE `list_options` SET `list_options`.`notes` = 'gle' WHERE `list_options`.`option_id` = 'irish';
#EndIf

#IfNotRow2D list_options list_id language option_id galician
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'galician', 'Galician', 58, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id galician notes glg
UPDATE `list_options` SET `list_options`.`notes` = 'glg' WHERE `list_options`.`option_id` = 'galician';
#EndIf

#IfNotRow2D list_options list_id language option_id manx
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'manx', 'Manx', 59, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id manx notes glv
UPDATE `list_options` SET `list_options`.`notes` = 'glv' WHERE `list_options`.`option_id` = 'manx';
#EndIf

#IfNotRow2D list_options list_id language option_id greek, modern (1453-)
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'greek, modern (1453-)', 'Greek, Modern (1453-)', 60, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id greek, modern (1453-) notes gre (B)|ell (T)
UPDATE `list_options` SET `list_options`.`notes` = 'gre (B)|ell (T)' WHERE `list_options`.`option_id` = 'greek, modern (1453-)';
#EndIf

#IfNotRow2D list_options list_id language option_id guarani
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'guarani', 'Guarani', 61, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id guarani notes grn
UPDATE `list_options` SET `list_options`.`notes` = 'grn' WHERE `list_options`.`option_id` = 'guarani';
#EndIf

#IfNotRow2D list_options list_id language option_id gujarati
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'gujarati', 'Gujarati', 62, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id gujarati notes guj
UPDATE `list_options` SET `list_options`.`notes` = 'guj' WHERE `list_options`.`option_id` = 'gujarati';
#EndIf

#IfNotRow2D list_options list_id language option_id haitian; haitian creole
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'haitian; haitian creole', 'Haitian; Haitian Creole', 63, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id haitian; haitian creole notes hat
UPDATE `list_options` SET `list_options`.`notes` = 'hat' WHERE `list_options`.`option_id` = 'haitian; haitian creole';
#EndIf

#IfNotRow2D list_options list_id language option_id hausa
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'hausa', 'Hausa', 64, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id hausa notes hau
UPDATE `list_options` SET `list_options`.`notes` = 'hau' WHERE `list_options`.`option_id` = 'hausa';
#EndIf

#IfNotRow2D list_options list_id language option_id hebrew
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'hebrew', 'Hebrew', 65, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id hebrew notes heb
UPDATE `list_options` SET `list_options`.`notes` = 'heb' WHERE `list_options`.`option_id` = 'hebrew';
#EndIf

#IfNotRow2D list_options list_id language option_id herero
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'herero', 'Herero', 66, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id herero notes her
UPDATE `list_options` SET `list_options`.`notes` = 'her' WHERE `list_options`.`option_id` = 'herero';
#EndIf

#IfNotRow2D list_options list_id language option_id hindi
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'hindi', 'Hindi', 67, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id hindi notes hin
UPDATE `list_options` SET `list_options`.`notes` = 'hin' WHERE `list_options`.`option_id` = 'hindi';
#EndIf

#IfNotRow2D list_options list_id language option_id hiri motu
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'hiri motu', 'Hiri Motu', 68, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id hiri motu notes hmo
UPDATE `list_options` SET `list_options`.`notes` = 'hmo' WHERE `list_options`.`option_id` = 'hiri motu';
#EndIf

#IfNotRow2D list_options list_id language option_id croatian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'croatian', 'Croatian', 69, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id croatian notes hrv
UPDATE `list_options` SET `list_options`.`notes` = 'hrv' WHERE `list_options`.`option_id` = 'croatian';
#EndIf

#IfNotRow2D list_options list_id language option_id hungarian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'hungarian', 'Hungarian', 70, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id hungarian notes hun
UPDATE `list_options` SET `list_options`.`notes` = 'hun' WHERE `list_options`.`option_id` = 'hungarian';
#EndIf

#IfNotRow2D list_options list_id language option_id igbo
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'igbo', 'Igbo', 71, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id igbo notes ibo
UPDATE `list_options` SET `list_options`.`notes` = 'ibo' WHERE `list_options`.`option_id` = 'igbo';
#EndIf

#IfNotRow2D list_options list_id language option_id icelandic
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'icelandic', 'Icelandic', 72, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id icelandic notes ice (B)|isl (T)
UPDATE `list_options` SET `list_options`.`notes` = 'ice (B)|isl (T)' WHERE `list_options`.`option_id` = 'icelandic';
#EndIf

#IfNotRow2D list_options list_id language option_id ido
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'ido', 'Ido', 73, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id ido notes ido
UPDATE `list_options` SET `list_options`.`notes` = 'ido' WHERE `list_options`.`option_id` = 'ido';
#EndIf

#IfNotRow2D list_options list_id language option_id sichuan yi; nuosu
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'sichuan yi; nuosu', 'Sichuan Yi; Nuosu', 74, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id sichuan yi; nuosu notes iii
UPDATE `list_options` SET `list_options`.`notes` = 'iii' WHERE `list_options`.`option_id` = 'sichuan yi; nuosu';
#EndIf

#IfNotRow2D list_options list_id language option_id inuktitut
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'inuktitut', 'Inuktitut', 75, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id inuktitut notes iku
UPDATE `list_options` SET `list_options`.`notes` = 'iku' WHERE `list_options`.`option_id` = 'inuktitut';
#EndIf

#IfNotRow2D list_options list_id language option_id interlingue; occidental
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'interlingue; occidental', 'Interlingue; Occidental', 76, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id interlingue; occidental notes ile
UPDATE `list_options` SET `list_options`.`notes` = 'ile' WHERE `list_options`.`option_id` = 'interlingue; occidental';
#EndIf

#IfNotRow2D list_options list_id language option_id interlingua (international auxiliary language association)
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'interlingua (international auxiliary language association)', 'Interlingua (International Auxiliary Language Association)', 77, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id interlingua (international auxiliary language association) notes ina
UPDATE `list_options` SET `list_options`.`notes` = 'ina' WHERE `list_options`.`option_id` = 'interlingua (international auxiliary language association)';
#EndIf

#IfNotRow2D list_options list_id language option_id indonesian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'indonesian', 'Indonesian', 78, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id indonesian notes ind
UPDATE `list_options` SET `list_options`.`notes` = 'ind' WHERE `list_options`.`option_id` = 'indonesian';
#EndIf

#IfNotRow2D list_options list_id language option_id inupiaq
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'inupiaq', 'Inupiaq', 79, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id inupiaq notes ipk
UPDATE `list_options` SET `list_options`.`notes` = 'ipk' WHERE `list_options`.`option_id` = 'inupiaq';
#EndIf

#IfNotRow2D list_options list_id language option_id italian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'italian', 'Italian', 80, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id italian notes ita
UPDATE `list_options` SET `list_options`.`notes` = 'ita' WHERE `list_options`.`option_id` = 'italian';
#EndIf

#IfNotRow2D list_options list_id language option_id javanese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'javanese', 'Javanese', 81, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id javanese notes jav
UPDATE `list_options` SET `list_options`.`notes` = 'jav' WHERE `list_options`.`option_id` = 'javanese';
#EndIf

#IfNotRow2D list_options list_id language option_id japanese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'japanese', 'Japanese', 82, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id japanese notes jpn
UPDATE `list_options` SET `list_options`.`notes` = 'jpn' WHERE `list_options`.`option_id` = 'japanese';
#EndIf

#IfNotRow2D list_options list_id language option_id kalaallisut; greenlandic
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'kalaallisut; greenlandic', 'Kalaallisut; Greenlandic', 83, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id kalaallisut; greenlandic notes kal
UPDATE `list_options` SET `list_options`.`notes` = 'kal' WHERE `list_options`.`option_id` = 'kalaallisut; greenlandic';
#EndIf

#IfNotRow2D list_options list_id language option_id kannada
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'kannada', 'Kannada', 84, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id kannada notes kan
UPDATE `list_options` SET `list_options`.`notes` = 'kan' WHERE `list_options`.`option_id` = 'kannada';
#EndIf

#IfNotRow2D list_options list_id language option_id kashmiri
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'kashmiri', 'Kashmiri', 85, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id kashmiri notes kas
UPDATE `list_options` SET `list_options`.`notes` = 'kas' WHERE `list_options`.`option_id` = 'kashmiri';
#EndIf

#IfNotRow2D list_options list_id language option_id kanuri
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'kanuri', 'Kanuri', 86, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id kanuri notes kau
UPDATE `list_options` SET `list_options`.`notes` = 'kau' WHERE `list_options`.`option_id` = 'kanuri';
#EndIf

#IfNotRow2D list_options list_id language option_id kazakh
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'kazakh', 'Kazakh', 87, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id kazakh notes kaz
UPDATE `list_options` SET `list_options`.`notes` = 'kaz' WHERE `list_options`.`option_id` = 'kazakh';
#EndIf

#IfNotRow2D list_options list_id language option_id central khmer
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'central khmer', 'Central Khmer', 88, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id central khmer notes khm
UPDATE `list_options` SET `list_options`.`notes` = 'khm' WHERE `list_options`.`option_id` = 'central khmer';
#EndIf

#IfNotRow2D list_options list_id language option_id kikuyu; gikuyu
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'kikuyu; gikuyu', 'Kikuyu; Gikuyu', 89, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id kikuyu; gikuyu notes kik
UPDATE `list_options` SET `list_options`.`notes` = 'kik' WHERE `list_options`.`option_id` = 'kikuyu; gikuyu';
#EndIf

#IfNotRow2D list_options list_id language option_id kinyarwanda
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'kinyarwanda', 'Kinyarwanda', 90, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id kinyarwanda notes kin
UPDATE `list_options` SET `list_options`.`notes` = 'kin' WHERE `list_options`.`option_id` = 'kinyarwanda';
#EndIf

#IfNotRow2D list_options list_id language option_id kirghiz; kyrgyz
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'kirghiz; kyrgyz', 'Kirghiz; Kyrgyz', 91, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id kirghiz; kyrgyz notes kir
UPDATE `list_options` SET `list_options`.`notes` = 'kir' WHERE `list_options`.`option_id` = 'kirghiz; kyrgyz';
#EndIf

#IfNotRow2D list_options list_id language option_id komi
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'komi', 'Komi', 92, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id komi notes kom
UPDATE `list_options` SET `list_options`.`notes` = 'kom' WHERE `list_options`.`option_id` = 'komi';
#EndIf

#IfNotRow2D list_options list_id language option_id kongo
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'kongo', 'Kongo', 93, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id kongo notes kon
UPDATE `list_options` SET `list_options`.`notes` = 'kon' WHERE `list_options`.`option_id` = 'kongo';
#EndIf

#IfNotRow2D list_options list_id language option_id korean
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'korean', 'Korean', 94, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id korean notes kor
UPDATE `list_options` SET `list_options`.`notes` = 'kor' WHERE `list_options`.`option_id` = 'korean';
#EndIf

#IfNotRow2D list_options list_id language option_id kuanyama; kwanyama
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'kuanyama; kwanyama', 'Kuanyama; Kwanyama', 95, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id kuanyama; kwanyama notes kua
UPDATE `list_options` SET `list_options`.`notes` = 'kua' WHERE `list_options`.`option_id` = 'kuanyama; kwanyama';
#EndIf

#IfNotRow2D list_options list_id language option_id kurdish
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'kurdish', 'Kurdish', 96, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id kurdish notes kur
UPDATE `list_options` SET `list_options`.`notes` = 'kur' WHERE `list_options`.`option_id` = 'kurdish';
#EndIf

#IfNotRow2D list_options list_id language option_id lao
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'lao', 'Lao', 97, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id lao notes lao
UPDATE `list_options` SET `list_options`.`notes` = 'lao' WHERE `list_options`.`option_id` = 'lao';
#EndIf

#IfNotRow2D list_options list_id language option_id latin
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'latin', 'Latin', 98, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id latin notes lat
UPDATE `list_options` SET `list_options`.`notes` = 'lat' WHERE `list_options`.`option_id` = 'latin';
#EndIf

#IfNotRow2D list_options list_id language option_id latvian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'latvian', 'Latvian', 99, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id latvian notes lav
UPDATE `list_options` SET `list_options`.`notes` = 'lav' WHERE `list_options`.`option_id` = 'latvian';
#EndIf

#IfNotRow2D list_options list_id language option_id limburgan; limburger; limburgish
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'limburgan; limburger; limburgish', 'Limburgan; Limburger; Limburgish', 100, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id limburgan; limburger; limburgish notes lim
UPDATE `list_options` SET `list_options`.`notes` = 'lim' WHERE `list_options`.`option_id` = 'limburgan; limburger; limburgish';
#EndIf

#IfNotRow2D list_options list_id language option_id lingala
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'lingala', 'Lingala', 101, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id lingala notes lin
UPDATE `list_options` SET `list_options`.`notes` = 'lin' WHERE `list_options`.`option_id` = 'lingala';
#EndIf

#IfNotRow2D list_options list_id language option_id lithuanian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'lithuanian', 'Lithuanian', 102, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id lithuanian notes lit
UPDATE `list_options` SET `list_options`.`notes` = 'lit' WHERE `list_options`.`option_id` = 'lithuanian';
#EndIf

#IfNotRow2D list_options list_id language option_id luxembourgish; letzeburgesch
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'luxembourgish; letzeburgesch', 'Luxembourgish; Letzeburgesch', 103, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id luxembourgish; letzeburgesch notes ltz
UPDATE `list_options` SET `list_options`.`notes` = 'ltz' WHERE `list_options`.`option_id` = 'luxembourgish; letzeburgesch';
#EndIf

#IfNotRow2D list_options list_id language option_id luba-katanga
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'luba-katanga', 'Luba-Katanga', 104, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id luba-katanga notes lub
UPDATE `list_options` SET `list_options`.`notes` = 'lub' WHERE `list_options`.`option_id` = 'luba-katanga';
#EndIf

#IfNotRow2D list_options list_id language option_id ganda
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'ganda', 'Ganda', 105, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id ganda notes lug
UPDATE `list_options` SET `list_options`.`notes` = 'lug' WHERE `list_options`.`option_id` = 'ganda';
#EndIf

#IfNotRow2D list_options list_id language option_id macedonian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'macedonian', 'Macedonian', 106, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id macedonian notes mac (B)|mkd (T)
UPDATE `list_options` SET `list_options`.`notes` = 'mac (B)|mkd (T)' WHERE `list_options`.`option_id` = 'macedonian';
#EndIf

#IfNotRow2D list_options list_id language option_id marshallese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'marshallese', 'Marshallese', 107, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id marshallese notes mah
UPDATE `list_options` SET `list_options`.`notes` = 'mah' WHERE `list_options`.`option_id` = 'marshallese';
#EndIf

#IfNotRow2D list_options list_id language option_id malayalam
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'malayalam', 'Malayalam', 108, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id malayalam notes mal
UPDATE `list_options` SET `list_options`.`notes` = 'mal' WHERE `list_options`.`option_id` = 'malayalam';
#EndIf

#IfNotRow2D list_options list_id language option_id maori
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'maori', 'Maori', 109, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id maori notes mao (B)|mri (T)
UPDATE `list_options` SET `list_options`.`notes` = 'mao (B)|mri (T)' WHERE `list_options`.`option_id` = 'maori';
#EndIf

#IfNotRow2D list_options list_id language option_id marathi
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'marathi', 'Marathi', 110, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id marathi notes mar
UPDATE `list_options` SET `list_options`.`notes` = 'mar' WHERE `list_options`.`option_id` = 'marathi';
#EndIf

#IfNotRow2D list_options list_id language option_id malay
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'malay', 'Malay', 111, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id malay notes may (B)|msa (T)
UPDATE `list_options` SET `list_options`.`notes` = 'may (B)|msa (T)' WHERE `list_options`.`option_id` = 'malay';
#EndIf

#IfNotRow2D list_options list_id language option_id malagasy
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'malagasy', 'Malagasy', 112, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id malagasy notes mlg
UPDATE `list_options` SET `list_options`.`notes` = 'mlg' WHERE `list_options`.`option_id` = 'malagasy';
#EndIf

#IfNotRow2D list_options list_id language option_id maltese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'maltese', 'Maltese', 113, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id maltese notes mlt
UPDATE `list_options` SET `list_options`.`notes` = 'mlt' WHERE `list_options`.`option_id` = 'maltese';
#EndIf

#IfNotRow2D list_options list_id language option_id mongolian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'mongolian', 'Mongolian', 114, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id mongolian notes mon
UPDATE `list_options` SET `list_options`.`notes` = 'mon' WHERE `list_options`.`option_id` = 'mongolian';
#EndIf

#IfNotRow2D list_options list_id language option_id nauru
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'nauru', 'Nauru', 115, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id nauru notes nau
UPDATE `list_options` SET `list_options`.`notes` = 'nau' WHERE `list_options`.`option_id` = 'nauru';
#EndIf

#IfNotRow2D list_options list_id language option_id navajo; navaho
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'navajo; navaho', 'Navajo; Navaho', 116, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id navajo; navaho notes nav
UPDATE `list_options` SET `list_options`.`notes` = 'nav' WHERE `list_options`.`option_id` = 'navajo; navaho';
#EndIf

#IfNotRow2D list_options list_id language option_id ndebele, south; south ndebele
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'ndebele, south; south ndebele', 'Ndebele, South; South Ndebele', 117, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id ndebele, south; south ndebele notes nbl
UPDATE `list_options` SET `list_options`.`notes` = 'nbl' WHERE `list_options`.`option_id` = 'ndebele, south; south ndebele';
#EndIf

#IfNotRow2D list_options list_id language option_id ndebele, north; north ndebele
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'ndebele, north; north ndebele', 'Ndebele, North; North Ndebele', 118, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id ndebele, north; north ndebele notes nde
UPDATE `list_options` SET `list_options`.`notes` = 'nde' WHERE `list_options`.`option_id` = 'ndebele, north; north ndebele';
#EndIf

#IfNotRow2D list_options list_id language option_id ndonga
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'ndonga', 'Ndonga', 119, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id ndonga notes ndo
UPDATE `list_options` SET `list_options`.`notes` = 'ndo' WHERE `list_options`.`option_id` = 'ndonga';
#EndIf

#IfNotRow2D list_options list_id language option_id nepali
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'nepali', 'Nepali', 120, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id nepali notes nep
UPDATE `list_options` SET `list_options`.`notes` = 'nep' WHERE `list_options`.`option_id` = 'nepali';
#EndIf

#IfNotRow2D list_options list_id language option_id norwegian nynorsk; nynorsk, norwegian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'norwegian nynorsk; nynorsk, norwegian', 'Norwegian Nynorsk; Nynorsk, Norwegian', 121, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id norwegian nynorsk; nynorsk, norwegian notes nno
UPDATE `list_options` SET `list_options`.`notes` = 'nno' WHERE `list_options`.`option_id` = 'norwegian nynorsk; nynorsk, norwegian';
#EndIf

#IfNotRow2D list_options list_id language option_id bokmål, norwegian; norwegian bokmål
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'bokmål, norwegian; norwegian bokmål', 'Bokmål, Norwegian; Norwegian Bokmål', 122, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id bokmål, norwegian; norwegian bokmål notes nob
UPDATE `list_options` SET `list_options`.`notes` = 'nob' WHERE `list_options`.`option_id` = 'bokmål, norwegian; norwegian bokmål';
#EndIf

#IfNotRow2D list_options list_id language option_id norwegian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'norwegian', 'Norwegian', 123, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id norwegian notes nor
UPDATE `list_options` SET `list_options`.`notes` = 'nor' WHERE `list_options`.`option_id` = 'norwegian';
#EndIf

#IfNotRow2D list_options list_id language option_id chichewa; chewa; nyanja
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'chichewa; chewa; nyanja', 'Chichewa; Chewa; Nyanja', 124, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id chichewa; chewa; nyanja notes nya
UPDATE `list_options` SET `list_options`.`notes` = 'nya' WHERE `list_options`.`option_id` = 'chichewa; chewa; nyanja';
#EndIf

#IfNotRow2D list_options list_id language option_id occitan (post 1500)
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'occitan (post 1500)', 'Occitan (post 1500)', 125, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id occitan (post 1500) notes oci
UPDATE `list_options` SET `list_options`.`notes` = 'oci' WHERE `list_options`.`option_id` = 'occitan (post 1500)';
#EndIf

#IfNotRow2D list_options list_id language option_id ojibwa
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'ojibwa', 'Ojibwa', 126, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id ojibwa notes oji
UPDATE `list_options` SET `list_options`.`notes` = 'oji' WHERE `list_options`.`option_id` = 'ojibwa';
#EndIf

#IfNotRow2D list_options list_id language option_id oriya
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'oriya', 'Oriya', 127, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id oriya notes ori
UPDATE `list_options` SET `list_options`.`notes` = 'ori' WHERE `list_options`.`option_id` = 'oriya';
#EndIf

#IfNotRow2D list_options list_id language option_id oromo
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'oromo', 'Oromo', 128, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id oromo notes orm
UPDATE `list_options` SET `list_options`.`notes` = 'orm' WHERE `list_options`.`option_id` = 'oromo';
#EndIf

#IfNotRow2D list_options list_id language option_id ossetian; ossetic
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'ossetian; ossetic', 'Ossetian; Ossetic', 129, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id ossetian; ossetic notes oss
UPDATE `list_options` SET `list_options`.`notes` = 'oss' WHERE `list_options`.`option_id` = 'ossetian; ossetic';
#EndIf

#IfNotRow2D list_options list_id language option_id punjabi
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'punjabi', 'Punjabi', 130, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id punjabi notes pan
UPDATE `list_options` SET `list_options`.`notes` = 'pan' WHERE `list_options`.`option_id` = 'punjabi';
#EndIf

#IfNotRow2D list_options list_id language option_id pali
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'pali', 'Pali', 131, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id pali notes pli
UPDATE `list_options` SET `list_options`.`notes` = 'pli' WHERE `list_options`.`option_id` = 'pali';
#EndIf

#IfNotRow2D list_options list_id language option_id polish
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'polish', 'Polish', 132, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id polish notes pol
UPDATE `list_options` SET `list_options`.`notes` = 'pol' WHERE `list_options`.`option_id` = 'polish';
#EndIf

#IfNotRow2D list_options list_id language option_id portuguese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'portuguese', 'Portuguese', 133, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id portuguese notes por
UPDATE `list_options` SET `list_options`.`notes` = 'por' WHERE `list_options`.`option_id` = 'portuguese';
#EndIf

#IfNotRow2D list_options list_id language option_id pushto; pashto
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'pushto; pashto', 'Pushto; Pashto', 134, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id pushto; pashto notes pus
UPDATE `list_options` SET `list_options`.`notes` = 'pus' WHERE `list_options`.`option_id` = 'pushto; pashto';
#EndIf

#IfNotRow2D list_options list_id language option_id quechua
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'quechua', 'Quechua', 135, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id quechua notes que
UPDATE `list_options` SET `list_options`.`notes` = 'que' WHERE `list_options`.`option_id` = 'quechua';
#EndIf

#IfNotRow2D list_options list_id language option_id romansh
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'romansh', 'Romansh', 136, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id romansh notes roh
UPDATE `list_options` SET `list_options`.`notes` = 'roh' WHERE `list_options`.`option_id` = 'romansh';
#EndIf

#IfNotRow2D list_options list_id language option_id romanian; moldavian; moldovan
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'romanian; moldavian; moldovan', 'Romanian; Moldavian; Moldovan', 137, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id romanian; moldavian; moldovan notes rum (B)|ron (T)
UPDATE `list_options` SET `list_options`.`notes` = 'rum (B)|ron (T)' WHERE `list_options`.`option_id` = 'romanian; moldavian; moldovan';
#EndIf

#IfNotRow2D list_options list_id language option_id rundi
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'rundi', 'Rundi', 138, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id rundi notes run
UPDATE `list_options` SET `list_options`.`notes` = 'run' WHERE `list_options`.`option_id` = 'rundi';
#EndIf

#IfNotRow2D list_options list_id language option_id russian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'russian', 'Russian', 139, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id russian notes rus
UPDATE `list_options` SET `list_options`.`notes` = 'rus' WHERE `list_options`.`option_id` = 'russian';
#EndIf

#IfNotRow2D list_options list_id language option_id sango
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'sango', 'Sango', 140, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id sango notes sag
UPDATE `list_options` SET `list_options`.`notes` = 'sag' WHERE `list_options`.`option_id` = 'sango';
#EndIf

#IfNotRow2D list_options list_id language option_id sanskrit
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'sanskrit', 'Sanskrit', 141, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id sanskrit notes san
UPDATE `list_options` SET `list_options`.`notes` = 'san' WHERE `list_options`.`option_id` = 'sanskrit';
#EndIf

#IfNotRow2D list_options list_id language option_id sinhala; sinhalese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'sinhala; sinhalese', 'Sinhala; Sinhalese', 142, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id sinhala; sinhalese notes sin
UPDATE `list_options` SET `list_options`.`notes` = 'sin' WHERE `list_options`.`option_id` = 'sinhala; sinhalese';
#EndIf

#IfNotRow2D list_options list_id language option_id slovak
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'slovak', 'Slovak', 143, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id slovak notes slo (B)|slk (T)
UPDATE `list_options` SET `list_options`.`notes` = 'slo (B)|slk (T)' WHERE `list_options`.`option_id` = 'slovak';
#EndIf

#IfNotRow2D list_options list_id language option_id slovenian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'slovenian', 'Slovenian', 144, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id slovenian notes slv
UPDATE `list_options` SET `list_options`.`notes` = 'slv' WHERE `list_options`.`option_id` = 'slovenian';
#EndIf

#IfNotRow2D list_options list_id language option_id northern sami
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'northern sami', 'Northern Sami', 145, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id northern sami notes sme
UPDATE `list_options` SET `list_options`.`notes` = 'sme' WHERE `list_options`.`option_id` = 'northern sami';
#EndIf

#IfNotRow2D list_options list_id language option_id samoan
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'samoan', 'Samoan', 146, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id samoan notes smo
UPDATE `list_options` SET `list_options`.`notes` = 'smo' WHERE `list_options`.`option_id` = 'samoan';
#EndIf

#IfNotRow2D list_options list_id language option_id shona
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'shona', 'Shona', 147, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id shona notes sna
UPDATE `list_options` SET `list_options`.`notes` = 'sna' WHERE `list_options`.`option_id` = 'shona';
#EndIf

#IfNotRow2D list_options list_id language option_id sindhi
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'sindhi', 'Sindhi', 148, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id sindhi notes snd
UPDATE `list_options` SET `list_options`.`notes` = 'snd' WHERE `list_options`.`option_id` = 'sindhi';
#EndIf

#IfNotRow2D list_options list_id language option_id somali
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'somali', 'Somali', 149, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id somali notes som
UPDATE `list_options` SET `list_options`.`notes` = 'som' WHERE `list_options`.`option_id` = 'somali';
#EndIf

#IfNotRow2D list_options list_id language option_id sotho, southern
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'sotho, southern', 'Sotho, Southern', 150, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id sotho, southern notes sot
UPDATE `list_options` SET `list_options`.`notes` = 'sot' WHERE `list_options`.`option_id` = 'sotho, southern';
#EndIf

#IfNotRow2D list_options list_id language option_id spanish
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'spanish', 'Spanish', 151, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id spanish notes spa
UPDATE `list_options` SET `list_options`.`notes` = 'spa' WHERE `list_options`.`option_id` = 'spanish';
#EndIf

#IfNotRow2D list_options list_id language option_id sardinian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'sardinian', 'Sardinian', 152, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id sardinian notes srd
UPDATE `list_options` SET `list_options`.`notes` = 'srd' WHERE `list_options`.`option_id` = 'sardinian';
#EndIf

#IfNotRow2D list_options list_id language option_id serbian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'serbian', 'Serbian', 153, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id serbian notes srp
UPDATE `list_options` SET `list_options`.`notes` = 'srp' WHERE `list_options`.`option_id` = 'serbian';
#EndIf

#IfNotRow2D list_options list_id language option_id swati
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'swati', 'Swati', 154, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id swati notes ssw
UPDATE `list_options` SET `list_options`.`notes` = 'ssw' WHERE `list_options`.`option_id` = 'swati';
#EndIf

#IfNotRow2D list_options list_id language option_id sundanese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'sundanese', 'Sundanese', 155, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id sundanese notes sun
UPDATE `list_options` SET `list_options`.`notes` = 'sun' WHERE `list_options`.`option_id` = 'sundanese';
#EndIf

#IfNotRow2D list_options list_id language option_id swahili
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'swahili', 'Swahili', 156, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id swahili notes swa
UPDATE `list_options` SET `list_options`.`notes` = 'swa' WHERE `list_options`.`option_id` = 'swahili';
#EndIf

#IfNotRow2D list_options list_id language option_id swedish
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'swedish', 'Swedish', 157, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id swedish notes swe
UPDATE `list_options` SET `list_options`.`notes` = 'swe' WHERE `list_options`.`option_id` = 'swedish';
#EndIf

#IfNotRow2D list_options list_id language option_id tahitian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'tahitian', 'Tahitian', 158, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id tahitian notes tah
UPDATE `list_options` SET `list_options`.`notes` = 'tah' WHERE `list_options`.`option_id` = 'tahitian';
#EndIf

#IfNotRow2D list_options list_id language option_id tamil
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'tamil', 'Tamil', 159, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id tamil notes tam
UPDATE `list_options` SET `list_options`.`notes` = 'tam' WHERE `list_options`.`option_id` = 'tamil';
#EndIf

#IfNotRow2D list_options list_id language option_id tatar
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'tatar', 'Tatar', 160, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id tatar notes tat
UPDATE `list_options` SET `list_options`.`notes` = 'tat' WHERE `list_options`.`option_id` = 'tatar';
#EndIf

#IfNotRow2D list_options list_id language option_id telugu
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'telugu', 'Telugu', 161, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id telugu notes tel
UPDATE `list_options` SET `list_options`.`notes` = 'tel' WHERE `list_options`.`option_id` = 'telugu';
#EndIf

#IfNotRow2D list_options list_id language option_id tajik
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'tajik', 'Tajik', 162, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id tajik notes tgk
UPDATE `list_options` SET `list_options`.`notes` = 'tgk' WHERE `list_options`.`option_id` = 'tajik';
#EndIf

#IfNotRow2D list_options list_id language option_id tagalog
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'tagalog', 'Tagalog', 163, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id tagalog notes tgl
UPDATE `list_options` SET `list_options`.`notes` = 'tgl' WHERE `list_options`.`option_id` = 'tagalog';
#EndIf

#IfNotRow2D list_options list_id language option_id thai
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'thai', 'Thai', 164, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id thai notes tha
UPDATE `list_options` SET `list_options`.`notes` = 'tha' WHERE `list_options`.`option_id` = 'thai';
#EndIf

#IfNotRow2D list_options list_id language option_id tigrinya
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'tigrinya', 'Tigrinya', 165, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id tigrinya notes tir
UPDATE `list_options` SET `list_options`.`notes` = 'tir' WHERE `list_options`.`option_id` = 'tigrinya';
#EndIf

#IfNotRow2D list_options list_id language option_id tonga (tonga islands)
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'tonga (tonga islands)', 'Tonga (Tonga Islands)', 166, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id tonga (tonga islands) notes ton
UPDATE `list_options` SET `list_options`.`notes` = 'ton' WHERE `list_options`.`option_id` = 'tonga (tonga islands)';
#EndIf

#IfNotRow2D list_options list_id language option_id tswana
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'tswana', 'Tswana', 167, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id tswana notes tsn
UPDATE `list_options` SET `list_options`.`notes` = 'tsn' WHERE `list_options`.`option_id` = 'tswana';
#EndIf

#IfNotRow2D list_options list_id language option_id tsonga
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'tsonga', 'Tsonga', 168, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id tsonga notes tso
UPDATE `list_options` SET `list_options`.`notes` = 'tso' WHERE `list_options`.`option_id` = 'tsonga';
#EndIf

#IfNotRow2D list_options list_id language option_id turkmen
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'turkmen', 'Turkmen', 169, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id turkmen notes tuk
UPDATE `list_options` SET `list_options`.`notes` = 'tuk' WHERE `list_options`.`option_id` = 'turkmen';
#EndIf

#IfNotRow2D list_options list_id language option_id turkish
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'turkish', 'Turkish', 170, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id turkish notes tur
UPDATE `list_options` SET `list_options`.`notes` = 'tur' WHERE `list_options`.`option_id` = 'turkish';
#EndIf

#IfNotRow2D list_options list_id language option_id twi
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'twi', 'Twi', 171, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id twi notes twi
UPDATE `list_options` SET `list_options`.`notes` = 'twi' WHERE `list_options`.`option_id` = 'twi';
#EndIf

#IfNotRow2D list_options list_id language option_id uighur; uyghur
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'uighur; uyghur', 'Uighur; Uyghur', 172, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id uighur; uyghur notes uig
UPDATE `list_options` SET `list_options`.`notes` = 'uig' WHERE `list_options`.`option_id` = 'uighur; uyghur';
#EndIf

#IfNotRow2D list_options list_id language option_id ukrainian
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'ukrainian', 'Ukrainian', 173, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id ukrainian notes ukr
UPDATE `list_options` SET `list_options`.`notes` = 'ukr' WHERE `list_options`.`option_id` = 'ukrainian';
#EndIf

#IfNotRow2D list_options list_id language option_id urdu
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'urdu', 'Urdu', 174, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id urdu notes urd
UPDATE `list_options` SET `list_options`.`notes` = 'urd' WHERE `list_options`.`option_id` = 'urdu';
#EndIf

#IfNotRow2D list_options list_id language option_id uzbek
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'uzbek', 'Uzbek', 175, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id uzbek notes uzb
UPDATE `list_options` SET `list_options`.`notes` = 'uzb' WHERE `list_options`.`option_id` = 'uzbek';
#EndIf

#IfNotRow2D list_options list_id language option_id venda
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'venda', 'Venda', 176, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id venda notes ven
UPDATE `list_options` SET `list_options`.`notes` = 'ven' WHERE `list_options`.`option_id` = 'venda';
#EndIf

#IfNotRow2D list_options list_id language option_id vietnamese
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'vietnamese', 'Vietnamese', 177, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id vietnamese notes vie
UPDATE `list_options` SET `list_options`.`notes` = 'vie' WHERE `list_options`.`option_id` = 'vietnamese';
#EndIf

#IfNotRow2D list_options list_id language option_id volapük
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'volapük', 'Volapük', 178, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id volapük notes vol
UPDATE `list_options` SET `list_options`.`notes` = 'vol' WHERE `list_options`.`option_id` = 'volapük';
#EndIf

#IfNotRow2D list_options list_id language option_id walloon
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'walloon', 'Walloon', 179, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id walloon notes wln
UPDATE `list_options` SET `list_options`.`notes` = 'wln' WHERE `list_options`.`option_id` = 'walloon';
#EndIf

#IfNotRow2D list_options list_id language option_id wolof
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'wolof', 'Wolof', 180, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id wolof notes wol
UPDATE `list_options` SET `list_options`.`notes` = 'wol' WHERE `list_options`.`option_id` = 'wolof';
#EndIf

#IfNotRow2D list_options list_id language option_id xhosa
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'xhosa', 'Xhosa', 181, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id xhosa notes xho
UPDATE `list_options` SET `list_options`.`notes` = 'xho' WHERE `list_options`.`option_id` = 'xhosa';
#EndIf

#IfNotRow2D list_options list_id language option_id yiddish
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'yiddish', 'Yiddish', 182, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id yiddish notes yid
UPDATE `list_options` SET `list_options`.`notes` = 'yid' WHERE `list_options`.`option_id` = 'yiddish';
#EndIf

#IfNotRow2D list_options list_id language option_id yoruba
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'yoruba', 'Yoruba', 183, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id yoruba notes yor
UPDATE `list_options` SET `list_options`.`notes` = 'yor' WHERE `list_options`.`option_id` = 'yoruba';
#EndIf

#IfNotRow2D list_options list_id language option_id zhuang; chuang
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'zhuang; chuang', 'Zhuang; Chuang', 184, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id zhuang; chuang notes zha
UPDATE `list_options` SET `list_options`.`notes` = 'zha' WHERE `list_options`.`option_id` = 'zhuang; chuang';
#EndIf

#IfNotRow2D list_options list_id language option_id zulu
INSERT INTO `list_options` ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('language', 'zulu', 'Zulu', 185, 0, 0);
#EndIf

#IfNotRow3D list_options list_id language option_id zulu notes zul
UPDATE `list_options` SET `list_options`.`notes` = 'zul' WHERE `list_options`.`option_id` = 'zulu';
#EndIf