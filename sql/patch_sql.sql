ALTER TABLE `patient_data` ADD `primaryProviderID` INT NOT NULL ;
ALTER TABLE `lists` ADD `severity_al` VARCHAR( 100 ) NOT NULL ,
ADD `sig` VARCHAR( 300 ) NOT NULL ;

INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`) VALUES
('lists', 'Severity', 'Severity', 62, 1, 0, '', '');
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`) VALUES
('Severity', 'Moderate', 'Moderate', 4, 0, 0, '', ''),
('Severity', 'Mild to Moderate', 'Mild to Moderate', 3, 0, 0, '', ''),
('Severity', 'Mild', 'Mild', 2, 0, 0, '', ''),
('Severity', 'Unknown', 'Unknown', 1, 0, 0, '', ''),
('Severity', 'Moderate to Severe', 'Moderate to Severe', 5, 0, 0, '', ''),
('Severity', 'Severe', 'Severe', 6, 0, 0, '', ''),
('Severity', 'Fatal', 'Fatal', 7, 0, 0, '', '');

INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`) VALUES
('Smoking_status_packs', '1', '1', 10, 0, 0, '', ''),
('Smoking_status_packs', '10', '10', 100, 0, 0, '', ''),
('Smoking_status_packs', '2', '2', 20, 0, 0, '', ''),
('Smoking_status_packs', '3', '3', 30, 0, 0, '', ''),
('Smoking_status_packs', '4', '4', 40, 0, 0, '', ''),
('Smoking_status_packs', '5', '5', 50, 0, 0, '', ''),
('Smoking_status_packs', '6', '6', 60, 0, 0, '', ''),
('Smoking_status_packs', '7', '7', 70, 0, 0, '', ''),
('Smoking_status_packs', '8', '8', 80, 0, 0, '', ''),
('Smoking_status_packs', '9', '9', 90, 0, 0, '', '');


INSERT INTO `layout_options` (`form_id`, `field_id`, `group_name`, `title`, `seq`, `data_type`, `uor`, `fld_length`, `max_length`, `list_id`, `titlecols`, `datacols`, `default_value`, `edit_options`, `description`) VALUES
('DEM', 'primaryProviderID', '3Choices', 'Primary Provider', 1, 10, 2, 0, 255, '', 1, 1, '', '', '');

ALTER TABLE `immunizations` ADD `code_type` TINYINT( 2 ) NOT NULL ,
ADD `reaction` VARCHAR( 255 ) NOT NULL ,
ADD `medication_series_number` VARCHAR( 100 ) NOT NULL ;


CREATE TABLE IF NOT EXISTS `facility_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lists_id` int(11) DEFAULT NULL,
  `facility_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ids` (`lists_id`,`facility_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;


INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`) VALUES
('lists', 'Allergy_Reaction', 'Allergy Reaction', 61, 1, 0, '', '');
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`) VALUES
('Allergy_Reaction', 'Anemia/Blood Disorders', 'Anemia/Blood Disorders', 13, 0, 0, '', ''),
('Allergy_Reaction', 'Breathing Problems', 'Breathing Problems', 1, 0, 0, '', ''),
('Allergy_Reaction', 'Diarrhea', 'Diarrhea', 2, 0, 0, '', ''),
('Allergy_Reaction', 'Changes in mental processes', 'Changes in mental processes', 3, 0, 0, '', ''),
('Allergy_Reaction', 'Abdominal Pain', 'Abdominal Pain', 4, 0, 0, '', ''),
('Allergy_Reaction', 'Nasal/Sinus Congestion', 'Nasal/Sinus Congestion', 5, 0, 0, '', ''),
('Allergy_Reaction', 'Rash/Hives', 'Rash/Hives', 6, 0, 0, '', ''),
('Allergy_Reaction', 'Nausea/Vomiting', 'Nausea/Vomiting', 7, 0, 0, '', ''),
('Allergy_Reaction', 'Itching', 'Itching', 8, 0, 0, '', ''),
('Allergy_Reaction', 'Fever/Chills', 'Fever/Chills', 12, 0, 0, '', ''),
('Allergy_Reaction', 'Dizziness', 'Dizziness', 9, 0, 0, '', ''),
('Allergy_Reaction', 'Shock/Unconsciousness', 'Shock/Unconsciousness', 10, 0, 0, '', ''),
('Allergy_Reaction', 'Fatigue/Lethargy', 'Fatigue/Lethargy', 11, 0, 0, '', ''),
('Allergy_Reaction', 'Other Reaction', 'Other Reaction', 14, 0, 0, '', '');




--
-- Table structure for table `e_docs`
--

DROP TABLE IF EXISTS `e_docs`;
CREATE TABLE IF NOT EXISTS `e_docs` (
  `ed_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ed_table` varchar(50) DEFAULT NULL,
  `ed_tid` bigint(20) NOT NULL,
  `ed_seq` smallint(10) NOT NULL,
  `ed_date` date DEFAULT NULL,
  `ed_name` varchar(50) DEFAULT NULL,
  `ed_type` varchar(5) DEFAULT NULL,
  `ed_status` varchar(50) DEFAULT NULL,
  `ed_file_path` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`ed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `e_inbox`
--

DROP TABLE IF EXISTS `e_inbox`;
CREATE TABLE IF NOT EXISTS `e_inbox` (
  `e_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `e_extid` bigint(20) DEFAULT NULL,
  `e_mtid` tinyint(4) DEFAULT NULL,
  `e_type` varchar(5) DEFAULT NULL,
  `e_date` datetime DEFAULT NULL,
  `e_recipientid` bigint(20) DEFAULT NULL,
  `e_senderid` bigint(20) DEFAULT NULL,
  `e_source` varchar(50) NOT NULL,
  `e_destination` varchar(50) DEFAULT NULL,
  `e_facility` varchar(50) DEFAULT NULL,
  `e_env_code` varchar(50) DEFAULT NULL,
  `e_method` varchar(4) DEFAULT NULL,
  `e_status` varchar(1) DEFAULT NULL,
  `e_pid` bigint(20) DEFAULT NULL,
  `e_mrn` varchar(16) DEFAULT NULL,
  `e_pv1` varchar(10) DEFAULT NULL,
  `e_attending_id` varchar(50) DEFAULT NULL,
  `e_referring_id` varchar(50) DEFAULT NULL,
  `e_inbox` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`e_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `e_notes`
--

DROP TABLE IF EXISTS `e_notes`;
CREATE TABLE IF NOT EXISTS `e_notes` (
  `en_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `en_table` varchar(50) DEFAULT NULL,
  `en_tid` bigint(20) DEFAULT NULL,
  `en_seq` smallint(10) DEFAULT NULL,
  `en_date` date DEFAULT NULL,
  `en_type` varchar(20) DEFAULT NULL,
  `en_status` varchar(50) DEFAULT NULL,
  `en_body` text,
  PRIMARY KEY (`en_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `e_outbox`
--

DROP TABLE IF EXISTS `e_outbox`;
CREATE TABLE IF NOT EXISTS `e_outbox` (
  `e_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `e_mtid` tinyint(4) DEFAULT NULL,
  `e_type` varchar(5) DEFAULT NULL,
  `e_block` varchar(50) NOT NULL,
  `e_block_id` int(11) NOT NULL,
  `e_date` datetime DEFAULT NULL,
  `e_recipient` bigint(20) DEFAULT NULL,
  `e_sender` bigint(20) DEFAULT NULL,
  `e_source` varchar(50) NOT NULL,
  `e_destination` varchar(50) DEFAULT NULL,
  `e_facility` varchar(50) DEFAULT NULL,
  `e_env_code` varchar(50) DEFAULT NULL,
  `e_method` varchar(4) DEFAULT NULL,
  `e_status` varchar(1) DEFAULT NULL,
  `e_pid` bigint(20) DEFAULT NULL,
  `e_mrn` varchar(16) DEFAULT NULL,
  `e_provider_id` bigint(20) DEFAULT NULL,
  `e_attending_id` varchar(50) DEFAULT NULL,
  `e_referring_id` varchar(50) DEFAULT NULL,
  `e_inbox` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`e_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Table structure for table `e_types`
--

DROP TABLE IF EXISTS `e_types`;
CREATE TABLE IF NOT EXISTS `e_types` (
  `et_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `et_type` varchar(20) NOT NULL,
  `et_map` varchar(30) DEFAULT NULL,
  `et_source` varchar(20) DEFAULT NULL,
  `et_code_type` varchar(25) DEFAULT NULL,
  `et_code` varchar(25) DEFAULT NULL,
  `et_name` varchar(50) DEFAULT NULL,
  `et_description` varchar(100) DEFAULT NULL,
  `et_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`et_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `e_units`
--

DROP TABLE IF EXISTS `e_units`;
CREATE TABLE IF NOT EXISTS `e_units` (
  `eu_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eu_code` varchar(3) NOT NULL,
  `eu_description` varchar(30) NOT NULL,
  PRIMARY KEY (`eu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rxnatomarchive`
--

DROP TABLE IF EXISTS `rxnatomarchive`;
CREATE TABLE IF NOT EXISTS `rxnatomarchive` (
  `RXAUI` varchar(8) NOT NULL,
  `AUI` varchar(10) DEFAULT NULL,
  `STR` varchar(4000) NOT NULL,
  `ARCHIVE_TIMESTAMP` varchar(280) NOT NULL,
  `CREATED_TIMESTAMP` varchar(280) NOT NULL,
  `UPDATED_TIMESTAMP` varchar(280) NOT NULL,
  `CODE` varchar(50) DEFAULT NULL,
  `IS_BRAND` varchar(1) DEFAULT NULL,
  `LAT` varchar(3) DEFAULT NULL,
  `LAST_RELEASED` varchar(30) DEFAULT NULL,
  `SAUI` varchar(50) DEFAULT NULL,
  `VSAB` varchar(40) DEFAULT NULL,
  `RXCUI` varchar(8) DEFAULT NULL,
  `SAB` varchar(20) DEFAULT NULL,
  `TTY` varchar(20) DEFAULT NULL,
  `MERGED_TO_RXCUI` varchar(8) DEFAULT NULL,
  KEY `X_RXNATOMARCHIVE_RXAUI` (`RXAUI`),
  KEY `X_RXNATOMARCHIVE_RXCUI` (`RXCUI`),
  KEY `X_RXNATOMARCHIVE_MERGED_TO` (`MERGED_TO_RXCUI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rxnconso`
--

DROP TABLE IF EXISTS `rxnconso`;
CREATE TABLE IF NOT EXISTS `rxnconso` (
  `RXCUI` varchar(8) NOT NULL,
  `LAT` varchar(3) NOT NULL DEFAULT 'ENG',
  `TS` varchar(1) DEFAULT NULL,
  `LUI` varchar(8) DEFAULT NULL,
  `STT` varchar(3) DEFAULT NULL,
  `SUI` varchar(8) DEFAULT NULL,
  `ISPREF` varchar(1) DEFAULT NULL,
  `RXAUI` varchar(8) NOT NULL,
  `SAUI` varchar(50) DEFAULT NULL,
  `SCUI` varchar(50) DEFAULT NULL,
  `SDUI` varchar(50) DEFAULT NULL,
  `SAB` varchar(20) NOT NULL,
  `TTY` varchar(20) NOT NULL,
  `CODE` varchar(50) NOT NULL,
  `STR` varchar(3000) NOT NULL,
  `SRL` varchar(10) DEFAULT NULL,
  `SUPPRESS` varchar(1) DEFAULT NULL,
  `CVF` varchar(50) DEFAULT NULL,
  KEY `X_RXNCONSO_STR` (`STR`(767)),
  KEY `X_RXNCONSO_RXCUI` (`RXCUI`),
  KEY `X_RXNCONSO_TTY` (`TTY`),
  KEY `X_RXNCONSO_CODE` (`CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rxncui`
--

DROP TABLE IF EXISTS `rxncui`;
CREATE TABLE IF NOT EXISTS `rxncui` (
  `cui1` varchar(8) DEFAULT NULL,
  `ver_start` varchar(20) DEFAULT NULL,
  `ver_end` varchar(20) DEFAULT NULL,
  `cardinality` varchar(8) DEFAULT NULL,
  `cui2` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rxncuichanges`
--

DROP TABLE IF EXISTS `rxncuichanges`;
CREATE TABLE IF NOT EXISTS `rxncuichanges` (
  `RXAUI` varchar(8) DEFAULT NULL,
  `CODE` varchar(50) DEFAULT NULL,
  `SAB` varchar(20) DEFAULT NULL,
  `TTY` varchar(20) DEFAULT NULL,
  `STR` varchar(3000) DEFAULT NULL,
  `OLD_RXCUI` varchar(8) NOT NULL,
  `NEW_RXCUI` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rxndoc`
--

DROP TABLE IF EXISTS `rxndoc`;
CREATE TABLE IF NOT EXISTS `rxndoc` (
  `DOCKEY` varchar(50) NOT NULL,
  `VALUE` varchar(1000) DEFAULT NULL,
  `TYPE` varchar(50) NOT NULL,
  `EXPL` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rxnrel`
--

DROP TABLE IF EXISTS `rxnrel`;
CREATE TABLE IF NOT EXISTS `rxnrel` (
  `RXCUI1` varchar(8) DEFAULT NULL,
  `RXAUI1` varchar(8) DEFAULT NULL,
  `STYPE1` varchar(50) DEFAULT NULL,
  `REL` varchar(4) DEFAULT NULL,
  `RXCUI2` varchar(8) DEFAULT NULL,
  `RXAUI2` varchar(8) DEFAULT NULL,
  `STYPE2` varchar(50) DEFAULT NULL,
  `RELA` varchar(100) DEFAULT NULL,
  `RUI` varchar(10) DEFAULT NULL,
  `SRUI` varchar(50) DEFAULT NULL,
  `SAB` varchar(20) NOT NULL,
  `SL` varchar(1000) DEFAULT NULL,
  `DIR` varchar(1) DEFAULT NULL,
  `RG` varchar(10) DEFAULT NULL,
  `SUPPRESS` varchar(1) DEFAULT NULL,
  `CVF` varchar(50) DEFAULT NULL,
  KEY `X_RXNREL_RXCUI1` (`RXCUI1`),
  KEY `X_RXNREL_RXCUI2` (`RXCUI2`),
  KEY `X_RXNREL_RELA` (`RELA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rxnsab`
--

DROP TABLE IF EXISTS `rxnsab`;
CREATE TABLE IF NOT EXISTS `rxnsab` (
  `VCUI` varchar(8) DEFAULT NULL,
  `RCUI` varchar(8) DEFAULT NULL,
  `VSAB` varchar(20) DEFAULT NULL,
  `RSAB` varchar(20) NOT NULL,
  `SON` varchar(3000) DEFAULT NULL,
  `SF` varchar(20) DEFAULT NULL,
  `SVER` varchar(20) DEFAULT NULL,
  `VSTART` varchar(10) DEFAULT NULL,
  `VEND` varchar(10) DEFAULT NULL,
  `IMETA` varchar(10) DEFAULT NULL,
  `RMETA` varchar(10) DEFAULT NULL,
  `SLC` varchar(1000) DEFAULT NULL,
  `SCC` varchar(1000) DEFAULT NULL,
  `SRL` int(11) DEFAULT NULL,
  `TFR` int(11) DEFAULT NULL,
  `CFR` int(11) DEFAULT NULL,
  `CXTY` varchar(50) DEFAULT NULL,
  `TTYL` varchar(300) DEFAULT NULL,
  `ATNL` varchar(1000) DEFAULT NULL,
  `LAT` varchar(3) DEFAULT NULL,
  `CENC` varchar(20) DEFAULT NULL,
  `CURVER` varchar(1) DEFAULT NULL,
  `SABIN` varchar(1) DEFAULT NULL,
  `SSN` varchar(3000) DEFAULT NULL,
  `SCIT` varchar(4000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rxnsat`
--

DROP TABLE IF EXISTS `rxnsat`;
CREATE TABLE IF NOT EXISTS `rxnsat` (
  `RXCUI` varchar(8) DEFAULT NULL,
  `LUI` varchar(8) DEFAULT NULL,
  `SUI` varchar(8) DEFAULT NULL,
  `RXAUI` varchar(8) DEFAULT NULL,
  `STYPE` varchar(50) DEFAULT NULL,
  `CODE` varchar(50) DEFAULT NULL,
  `ATUI` varchar(11) DEFAULT NULL,
  `SATUI` varchar(50) DEFAULT NULL,
  `ATN` varchar(50) NOT NULL,
  `SAB` varchar(20) NOT NULL,
  `ATV` varchar(4000) DEFAULT NULL,
  `SUPPRESS` varchar(1) DEFAULT NULL,
  `CVF` varchar(50) DEFAULT NULL,
  KEY `X_RXNSAT_RXCUI` (`RXCUI`),
  KEY `X_RXNSAT_ATV` (`ATV`(767)),
  KEY `X_RXNSAT_ATN` (`ATN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rxnsty`
--

DROP TABLE IF EXISTS `rxnsty`;
CREATE TABLE IF NOT EXISTS `rxnsty` (
  `RXCUI` varchar(8) NOT NULL,
  `TUI` varchar(4) DEFAULT NULL,
  `STN` varchar(100) DEFAULT NULL,
  `STY` varchar(50) DEFAULT NULL,
  `ATUI` varchar(11) DEFAULT NULL,
  `CVF` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

