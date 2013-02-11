<?php
/**
 * Manage background operations that should be executed at intervals.
 *
 * This script may be executed by a suitable Ajax request, by a cron job, or both.
 *
 * For both calling methods, this script guarantees that each active
 * background service call will not be called again before it has completed,
 * and will not be called any more frequently than at the specified interval.
 * 
 * Notes:
 * 1. If the Ajax method is used, services will only be checked while
 * Ajax requests are being received, which is currently only when users are
 * logged in. 
 * 2. All services are checked and called sequentially in the order specified
 * by the array below. Service calls that are "slow" should be put at the end
 * of the list.
 * 3. The actual interval between two calls to a given background service may be
 * as long as the time to complete that service plus the interval between
 * n+1 calls to this script where n is the number of other services preceding it
 * in the array, even if the specified minimum interval is shorter, so plan
 * accordingly. Example: with a 5 min cron interval, the 4th service on the list
 * may not be started again for up to 20 minutes after it has completed if 
 * services 1, 2, and 3 take more than 15, 10, and 5 minutes to complete,
 * respectively.
 *
 * Copyright (C) 2013 EMR Direct <http://www.emrdirect.com/>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  EMR Direct <http://www.emrdirect.com/>
 * @link    http://www.open-emr.org
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

//while the number of services is small, dependencies can be loaded here
//when some critical threshold is reached, this may need to be revisited.
require_once(dirname(__FILE__) . "/../../interface/globals.php");
require_once(dirname(__FILE__) . "/../sql.inc");
require_once(dirname(__FILE__) . "/../direct_message_check.inc");

//Remove time limit so script doesn't time out
set_time_limit(0);

//Release session lock to prevent freezing of other scripts
session_write_close();

//Safety in case one of the background functions tries to output data
ignore_user_abort(1);

//ajax=true should be set by calling ajax scripts
//if false, we may assume this is a cron job
$isAjaxCall = isset($_POST['ajax']);

/**
 * @var timed_functions: array of timed services
 * Each element is an array and desribes one service, following this format:
 *   'service_name' => array(
 *      is_active,  //expression that evaluates to true only if the service is active,
 *	interval,   //the interval at which the service task should be run in seconds,
 *	func_name,  //the function_name to call to perform the task,
 *	func_param  //an optional parameter to pass to the function (or NULL)
 *   )
 *
 * Each service must do its own logging, as appropriate, and should disable itself
 * to prevent continued service calls if an error condition occurs which requires 
 * administrator intervention. The function return values and output are ignored.
 *
 */

$timed_functions = array(
    'phimail' => array(
	$GLOBALS['phimail_enable'],	// true when service activated
	300,				// 5 minute minimum interval between calls (300 seconds)
	'phimail_check',		// name of service function to be called
	NULL				// optional parameter not used for this service
    )
);

//In case a service function calls die() or exit()
register_shutdown_function(background_shutdown);

function execute_background_service_calls() {
  global $service_name;
  global $timed_functions;

  foreach($timed_functions as $service_name => $service_param) {
    $adodb = $GLOBALS['adodb']['db'];

    if(!$service_param[0]) continue;
    $interval=(int)$service_param[1];

    //leverage locking built-in to UPDATE to prevent race conditions
    //will need to assess performance in high concurrency setting at some point
    $sql="UPDATE background_services SET is_running = 1, next_run = NOW()+ INTERVAL " .
	$interval. " SECOND WHERE is_running = 0 AND NOW() > next_run AND name=" . 
	$adodb->qstr($service_name) ;

    if(@sqlStatementNoLog($sql)===FALSE) continue;
    $acquiredLock =  @mysql_affected_rows($GLOBALS['dbh']);
    if($acquiredLock<1) continue;

    //use try/catch in case service functions throw an unexpected Exception
    try {
	if(isset($service_param[3]))
	    @$service_param[2]($service_param[3]);
	else
	    @$service_param[2]();
    } catch (Exception $e) {
	//do nothing
    }

    $sql="UPDATE background_services SET is_running=0 WHERE name=" . $adodb->qstr($service_name);
    $res = @sqlStatementNoLog($sql);
  }
}

execute_background_service_calls();
unset($service_name);

/**
 * Catch unexpected failures.
 * 
 * if the global $service_name is still set, then a die() or exit() occurred during the execution
 * of that service's function call, and we did not complete the foreach loop properly,
 * so we need to reset the is_running flag for that service before quitting
 */


function background_shutdown() {
  global $service_name;
  $adodb = $GLOBALS['adodb']['db'];
  
  if (isset($service_name)) {
    
    $sql="UPDATE background_services SET is_running=0 WHERE name=" . $adodb->qstr($service_name);
    $res = @sqlStatementNoLog($sql);

  }
}

?>
