1) save dated_reminders.php to \openemr\interface\main\calendar
2) save dated_reminders_add.php to \openemr\interface\main\calendar  
3) save dated_reminders_log.php to \openemr\interface\main\calendar

4) edit \openemr\interface\main\calendar\index.php 
  - add this in line 157 :
        // TajEmo Work by CB 2012/01/11 02:51:25 PM adding dated reminders
        // I am asuming that at this point security checks have been performed
        require_once 'dated_reminders.php';


        it should look like this :
                    $output = new pnHTML();
                    //$output->StartPage(); 
                    
                    // TajEmo Work by CB 2012/01/11 02:51:25 PM adding dated reminders
                    // I am asuming that at this point security checks have been performed
                    require_once 'dated_reminders.php';
                    
                    
                    $output->SetInputMode(_PNH_VERBATIMINPUT);
        
5) Run The following SQL TO CREATE THE TABLES REQUIRED :
 
          DROP TABLE IF EXISTS `dated_reminders`; 

          CREATE TABLE IF NOT EXISTS `dated_reminders` (
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
          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
          
          DROP TABLE IF EXISTS `dated_reminders_link`;
          
          CREATE TABLE IF NOT EXISTS `dated_reminders_link` (
            `dr_link_id` int(11) NOT NULL AUTO_INCREMENT,
            `dr_id` int(11) NOT NULL,
            `to_id` int(11) NOT NULL,
            PRIMARY KEY (`dr_link_id`),
            KEY `to_id` (`to_id`),
            KEY `dr_id` (`dr_id`)
          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
   
    