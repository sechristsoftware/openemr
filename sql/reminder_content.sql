CREATE TABLE IF NOT EXISTS `reminder_content` (
  `id` int(2) NOT NULL,
  `appt_message` text,
  `confirm_message` text NOT NULL,
  `cancel_message` text NOT NULL
) ENGINE=InnoDB  COMMENT='OMP Appt reminder Message';

--
-- Dumping data for table `reminder_content`
--

INSERT INTO `reminder_content` (`id`, `appt_message`, `confirm_message`, `cancel_message`) VALUES
(1, 'Hi {PATIENT_NAME},We are calling to confirm you have a doctors appointment tomorrow at  {APPT_DATETIME} on {APPT_FACILITY} with {APPT_PROVIDER}  Please Press 1  to confirm appointment and not be billed for missed appointment,Press 2 to cancel appointment at {APPT_FACILITY_PHONE}\r\n\r\nThanks\r\n{APPT_FACILITY}', 'Thank you. We will see you tomorrow at {APPT_FACILITY}', 'Thank you. You will receive a call to reschedule your appointment.Goodbye.');

