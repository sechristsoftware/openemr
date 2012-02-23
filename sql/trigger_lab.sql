--
-- Triggers `form_labs`
--
DROP TRIGGER IF EXISTS `update_outbox`;
DELIMITER //
CREATE TRIGGER `update_outbox` AFTER INSERT ON `form_labs`
 FOR EACH ROW BEGIN
INSERT INTO e_outbox set e_type='CCD',e_block='lab_result',e_block_id=NEW.id,e_date=NOW(),e_sender='4815',e_env_code='P',e_method='HL7',e_status='1',e_pid=NEW.PID,e_provider_id='123456',e_attending_id='4815';
END
//
DELIMITER ;