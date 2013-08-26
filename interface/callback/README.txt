

 The folder location is /interface/callback.

File list and descriptions


  7/31/2013
 
pull_appt.php

     This file accesses  the fetchAppointment function from the appointment.inc.php.
     The returned appointments are in an array and filtered by the foreach loop into 
     the callBacks array. This file creates the callbacks.inc.php that is the script
     file to upload the contact information to the dailing system.

upload_header.php
 
     This file is contains the header information for the callbacks.inc.php file

upload_footer.php 

     this file contains the bottom portions of the callbacks.inc.php file and it contains
     the reset of the code to create a broadcast and assign the call list to the broadcast.

callbacks.inc.php

     this file is dynamically generated and the combination of the upload_header.php, 
     pull_appt.php and upload_footer.php. It contains all the code to build and deploy the 
     IVR on CallFire.com.

fetchLogin.inc.php

     this is an include file that is used as part of the registration validation


timeFunction.inc.php

     this is another include file that is used to keep track of all the time functions since
     time is very important in this system. 


parse_results.php
 
     the purpose of this file it to post results to the practice they can see who has confirmed 
     appointments and who did not answer the phone.

System File Change:

     Made an addition to the appointment.inc.php file to fileter out appointments that have been
     canceled. Added functions fetchEventsMaster and fetchAppToCall 



High level how it works:

    In the EMR, there is a button in the Patient/Client menu. Once this button is clicked, it calls 
 the pull_appt.php file. This file has a few validation checks in it. The biggest thing it does is
 it goes and retrieves the appointment data minus canceled appointments. The data is then passed in 
 an array to build the callbacks.inc.php file on the fly. 

 The upload header and the upload footer are as the name suggest the upper and lower portions of the 
 callbacks.inc.php file. The pull_appt.php file creates the middle part of the file. Once the file is
 built then it is called by the pull_appt.php file seconds after it is built. Once the code in the 
 callbacks.inc.php file is executed, it uploads a list of patient to call, it also sets the time for
 the calls to be made. It compensates for the weekend. The callbacks.inc.php writes the ivr batch id
 to the database along with the current date. These will be used later. The callbacks.inc.php file then
 redirects back to the pull_appt.php file and that file checks that the batch has been recorded and calls
 the stats.php file. If the results are not ready the file displays the appropriate message.
 
 This is what we have for now. More features to come soon...


Future - Building Configuration Options...

  The current system is built with a singular purpose in mind. However, in building the system. There
  is a need to make the system more versital. There needs to be a configuration section done to 
  accomplish this goal. The configuration section will allow for changing of the message that the 
  patient hears. Clients will be able to select number of days in advance that the patient is to be
  contacted.  

