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

#IfNotTable report_results
CREATE TABLE `report_results` (
  `report_id` bigint(20) NOT NULL,
  `field_id` varchar(31) NOT NULL default '',
  `field_value` text,
  PRIMARY KEY (`report_id`,`field_id`)
) ENGINE=MyISAM;
#EndIf

#IfMissingColumn version v_acl
ALTER TABLE `version` ADD COLUMN `v_acl` int(11) NOT NULL DEFAULT 0;
#EndIf

#IfMissingColumn form_encounter pos_code
	ALTER TABLE `form_encounter` ADD `pos_code` INT( 11 ) NOT NULL; 
#EndIf

#IfMissingColumn facility pos_code_multiple
	ALTER TABLE `facility` ADD `pos_code_multiple` TEXT NOT NULL;
#EndIf

#IfNotRow2D list_options list_id lists option_id POS
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `seq`, `is_default` ) VALUES ('lists' ,'POS','POS', 3, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 1, 'Unassigned', 'N/A', 1, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 3, 'School', 'A facility whose primary purpose is education.', 3, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 4, 'Homeless Shelter', 'A facility or location whose primary purpose is to provide temporary housing to homeless individuals (e.g., emergency shelters, individual or family shelters).', 4, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 5, 'Indian Health Service Free-standing Facility', 'A facility or location, ownedAnd operated by the Indian Health Service, which provides diagnostic, therapeutic (surgicalAnd non-surgical),And rehabilitation services toAmerican IndiansAndAlaska Natives who do not require hospitalization.', 5, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 6, 'Indian Health Service Provider-based Facility', 'A facility or location, ownedAnd operated by the Indian Health Service, which provides diagnostic, therapeutic (surgicalAnd non-surgical),And rehabilitation services rendered by, or under the supervision of, physicians toAmerican IndiansAndAlaska NativesAdmittedAs inpatients or outpatients. ', 6, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 7, 'Tribal 6 Free-standing Facility', 'A facility or location ownedAnd operated byA federally recognizedAmerican Indian orAlaska Native tribe or tribal organization underA 6Agreement, which provides diagnostic, therapeutic (surgicalAnd non-surgical),And rehabilitation services to tribal members who do not require hospitalization.', 7, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 8, 'Tribal 6 Provider-based Facility', 'A facility or location ownedAnd operated byA federally recognizedAmerican Indian orAlaska Native tribe or tribal organization underA 6Agreement, which provides diagnostic, therapeutic (surgicalAnd non-surgical),And rehabilitation services to tribal membersAdmittedAs inpatients or outpatients.', 8, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 11, 'Office ', 'Location, other than a hospital, skilled nursing facility (SNF), military treatment facility, community health center, State orLocal public health clinic, or intermediate care facility (ICF), where the health professional routinely provides health examinations, diagnosis, and treatment of illness or injury on an ambulatory basis.', 11, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 12, 'Home', 'Location, other than a hospital or other facility, where the patient receives care in a private residence.', 12, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 13, 'Assisted Living Facility', 'Congregate residential facility with self-contained living units providing assessment of each resident’s needs and on-site support 24 hours a day, 7 days a week, with the capacity to deliver or arrange for services including some health care and other services.  (effective 10/1/03)', 13, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 14, 'Group Home *', 'A residence, with shared living areas, where clients receive supervision and other services such as social and/or behavioral services, custodial service, and minimal services (e.g., medication administration).', 14, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 15, 'Mobile Unit', 'A facility/unit that moves from place-to-place equipped to provide preventive, screening, diagnostic,And/or treatment services.', 15, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 20, 'Urgent Care Facility', 'Location, distinct from a hospital emergency room, an office, or a clinic, whose purpose is to diagnose and treat illness or injury for unscheduled, ambulatory patients seeking immediate medical attention.', 20, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 21, 'Inpatient Hospital', 'A facility, other than psychiatric, which primarily provides diagnostic, therapeutic (both surgicalAnd nonsurgical),And rehabilitation services by, or under, the supervision of physicians to patientsAdmitted forA variety of medical conditions.', 21, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 22, 'Outpatient Hospital', 'A portion of a hospital which provides diagnostic, therapeutic (both surgical and nonsurgical), and rehabilitation services to sick or injured persons who do not require hospitalization or institutionalization.', 22, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 23, 'Emergency Room - Hospital', 'A portion of a hospital where emergency diagnosis and treatment of illness or injury is provided.', 23, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 24, 'Ambulatory Surgical Center', 'A freestanding facility, other than a physician office, where surgical and diagnostic services are provided on an ambulatory basis.', 24, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 25, 'Birthing Center ', 'A facility, other thanA hospital maternity facilities orA physician office, which providesA setting for labor, delivery,And immediate post-partum careAs wellAs immediate care of new born infants.', 25, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 26, 'Military Treatment Facility', 'A medical facility operated by one or more of the Uniformed Services. Military Treatment Facility (MTF) also refers to certain former U.S. Public Health Service (USPHS) facilities now designated as Uniformed Service Treatment Facilities (USTF).', 26, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 31, 'Skilled Nursing Facility', 'A facility which primarily provides inpatient skilled nursing careAnd related services to patients who require medical, nursing, or rehabilitative services but does not provide the level of care or treatmentAvailable inA hospital.', 31, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 32, 'Nursing Facility', 'A facility which primarily provides to residents skilled nursing careAnd related services for the rehabilitation of injured, disabled, or sick persons, or, onA regular basis, health-related care servicesAbove the level of custodial care to other than mentally retarded individuals.', 32, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 33, 'Custodial Care Facility', 'A facility which provides room, boardAnd other personalAssistance services, generally onA long-term basis,And which does not includeA medical component.', 33, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 34, 'Hospice', 'A facility, other thanA patient home, in which palliativeAnd supportive care for terminally ill patientsAnd their familiesAre provided.', 34, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 41, 'Ambulance - Land', 'A land vehicle specifically designed, equipped and staffed for lifesaving and transporting the sick or injured.', 41, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 42, 'Ambulance - Air or Water', 'An air or water vehicle specifically designed, equipped and staffed for lifesaving and transporting the sick or injured.', 42, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 49, 'Independent Clinic', 'A location, not part of a hospital and not described by any other Place of Service code, that is organized and operated to provide preventive, diagnostic, therapeutic, rehabilitative, or palliative services to outpatients only.  (effective 10/1/03)', 49, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 50, 'Federally Qualified Health Center', 'A facility located inA medically underservedArea that provides Medicare beneficiaries preventive primary medical care under the general direction ofA physician.', 50, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 51, 'Inpatient Psychiatric Facility', 'A facility that provides inpatient psychiatric services for the diagnosisAnd treatment of mental illness onA 24-hour basis, by or under the supervision ofA physician.', 51, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 52, 'Psychiatric Facility-Partial Hospitalization', 'A facility for the diagnosisAnd treatment of mental illness that providesA planned therapeutic program for patients who do not require full time hospitalization, but who need broader programs thanAre possible from outpatient visits toA hospital-based or hospital-affiliated facility.', 52, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 53, 'Community Mental Health Center', 'A facility that provides the following services: outpatient services, including specialized outpatient services for children, the elderly, individuals whoAre chronically ill,And residents of the CMHC mental health servicesArea who have been discharged from inpatient treatmentAtA mental health facility 24 hourA day emergency care services day treatment, other partial hospitalization services, or psychosocial rehabilitation services screening for patients being considered forAdmission to State mental health facilities to determine theAppropriateness of suchAdmissionAnd consultationAnd education services.', 53, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 54, 'Intermediate Care Facility/Mentally Retarded', 'A facility which primarily provides health-related careAnd servicesAbove the level of custodial care to mentally retarded individuals but does not provide the level of care or treatmentAvailable inA hospital or SNF.', 54, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 55, 'Residential Substance Abuse Treatment Facility', 'A facility which provides treatment for substance (alcoholAnd drug)Abuse to live-in residents who do not requireAcute medical care. Services include individualAnd group therapyAnd counseling, family counseling, laboratory tests, drugsAnd supplies, psychological testing,And roomAnd board.', 55, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 56, 'Psychiatric Residential Treatment Center', 'A facility or distinct part ofA facility for psychiatric care which providesA total 24-hour therapeutically plannedAnd professionally staffed group livingAnd learning environment.', 56, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 57, 'Non-residential Substance Abuse Treatment Facility', 'A location which provides treatment for substance (alcoholAnd drug)Abuse onAnAmbulatory basis.  Services include individualAnd group therapyAnd counseling, family counseling, laboratory tests, drugsAnd supplies,And psychological testing.  (effective 10/1/03)', 57, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 60, 'Mass Immunization Center', 'A location where providersAdminister pneumococcal pneumoniaAnd influenza virus vaccinationsAnd submit these servicesAs electronic media claims, paper claims, or using the roster billing method. This generally takes place inA mass immunization setting, suchAs,A public health center, pharmacy, or mall but may includeA physician office setting.', 60, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 61, 'Comprehensive Inpatient Rehabilitation Facility', 'A facility that provides comprehensive rehabilitation services under the supervision ofA physician to inpatients with physical disabilities. Services include physical therapy, occupational therapy, speech pathology, social or psychological services,And orthoticsAnd prosthetics services.', 61, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 62, 'Comprehensive Outpatient Rehabilitation Facility', 'A facility that provides comprehensive rehabilitation services under the supervision ofA physician to outpatients with physical disabilities. Services include physical therapy, occupational therapy,And speech pathology services.', 62, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 65, 'End-Stage Renal Disease Treatment Facility', 'A facility other thanA hospital, which provides dialysis treatment, maintenance,And/or training to patients or caregivers onAnAmbulatory or home-care basis.', 65, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 71, 'Public Health Clinic', 'A facility maintained by either State or local health departments that providesAmbulatory primary medical care under the general direction ofA physician.  (effective 10/1/03)', 71, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 72, 'Rural Health Clinic', 'A certified facility which is located in a rural medically underserved area that provides ambulatory primary medical care under the general direction of a physician.', 72, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 81, 'Independent Laboratory', 'A laboratory certified to perform diagnosticAnd/or clinical tests independent ofAn institution orA physician office.', 81, 0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `notes`, `seq`, `is_default` ) VALUES ('POS', 99, 'Other Place of Service', 'Other place of service not identified above. ', 99, 0);
#EndIf

