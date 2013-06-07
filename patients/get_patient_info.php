<?php
/**
 * Entry script for onsite patient portal.
 *
 * Copyright (C) 2011 Cassian LUP <cassi.lup@gmail.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Cassian LUP <cassi.lup@gmail.com>
 * @author  Brady Miller <brady@sparmy.com>
 * @author  Kevin Yeh <kevin.y@integralemr.com> 
 * @link    http://www.open-emr.org
 */

    //starting the PHP session (also regenerating the session id to avoid session fixation attacks)
        session_start();
        session_regenerate_id(true);
    //

    //landing page definition -- where to go if something goes wrong
	$landingpage = "index.php?site=".$_SESSION['site_id'];
    //
    
    //checking whether the request comes from index.php
        if (!isset($_SESSION['itsme'])) {
                session_destroy();
		header('Location: '.$landingpage.'&w');
		exit;
	}
    //

    //some validation
        if (!isset($_POST['uname']) || empty($_POST['uname'])) {
                session_destroy();
		header('Location: '.$landingpage.'&w&c');
		exit;
	}
        if (!isset($_POST['code']) || empty($_POST['code'])) {
                session_destroy();
                header('Location: '.$landingpage.'&w&c');
		exit;
        }
    //

    //SANITIZE ALL ESCAPES
    $fake_register_globals=false;

    //STOP FAKE REGISTER GLOBALS
    $sanitize_all_escapes=true;

    //Settings that will override globals.php
        $ignoreAuth = 1;
    //

    //Authentication (and language setting)
	require_once('../interface/globals.php');
        require_once("$srcdir/authentication/rsa.php");
        require_once("$srcdir/authentication/common_operations.php");        
        $password_update=isset($_SESSION['password_update']);
        unset($_SESSION['password_update']);

        if (isset($_REQUEST['login_pk']) && !empty($_REQUEST['login_pk'])) {
            // rsa is working, so get the clear text password
            $pubKey=$_REQUEST['login_pk'];
            $rsa=new rsa_key_manager();
            $rsa->load_from_db($pubKey);
            $plain_code= $rsa->decrypt($_POST['code']);
            $Pass=$plain_code;
        }
        else {
            // In this case, the value has already been hashed on the client side,
            // so place in an array. Also will include the pertinent migration hash
            // and client salt, if applicable.
            $Pass=array('code' => $_POST['code']);
            if (isset($_POST['code_salt']) && !empty($_POST['code_salt'])) {
                $Pass['code_salt']=$_POST['code_salt'];
            }
            if (isset($_POST['code_extra']) && !empty($_POST['code_extra'])) {
                $Pass['code_extra']=$_POST['code_extra'];
            }
        }

        // set the language
        if (!empty($_POST['languageChoice'])) {
                $_SESSION['language_choice'] = $_POST['languageChoice'];
        }
        else if (empty($_SESSION['language_choice'])) {
                // just in case both are empty, then use english
                $_SESSION['language_choice'] = 1;
        }
        else {
                // keep the current session language token
        }

        $authorizedPortal=false; //flag
        DEFINE("TBL_PAT_ACC_ON","patient_access_onsite");
        DEFINE("COL_PID","pid");
        DEFINE("COL_POR_PWD","portal_pwd");
        DEFINE("COL_POR_USER","portal_username");
        DEFINE("COL_POR_SALT","portal_salt");
        DEFINE("COL_POR_PWD_STAT","portal_pwd_status");
        DEFINE("COL_POR_CLIENT_SALT","portal_salt_client_side");
        $sql= "SELECT ".implode(",",array(COL_ID,COL_PID,COL_POR_PWD,COL_POR_SALT,COL_POR_PWD_STAT,COL_POR_CLIENT_SALT))
              ." FROM ".TBL_PAT_ACC_ON
              ." WHERE ".COL_POR_USER."=?";
                $auth = privQuery($sql, array($_POST['uname']));
                if($auth===false)
                {
                    session_destroy();
                    header('Location: '.$landingpage.'&w');
                    exit;
                }
                if(empty($auth[COL_POR_SALT]))
                {
                    // Check the old sha1 method hash and migrate if it checks to salted method
                    // Exit if only accepting salted hashes
                    if ((isset($GLOBALS['password_compatibility']) && !$GLOBALS['password_compatibility'])) {
                        session_destroy();
                        header('Location: '.$landingpage.'&w');
                        exit;
                    }
                    if(is_array($Pass)) {
                        // RSA not available, so password has already been hashed on client side with a new salt
                        $client_side_salt=$Pass['code_salt'];
                        $client_side_hash=$Pass['code'];
                        $old_sha1_hash=$Pass['code_extra'];
                    }
                    else {
                        // RSA is working, so have the clear text password in $Pass
                        // Need to create a client side hash, mimick the client side hash and create the old sha1 hash
                        $client_side_salt=password_salt(true);
                        $client_side_hash=password_hash($Pass,$client_side_salt);
                        $old_sha1_hash=SHA1($Pass);
                    }

                    if($old_sha1_hash!=$auth[COL_POR_PWD])
                    {
                        session_destroy();
                        header('Location: '.$landingpage.'&w');
                        exit;                        
                    }

                    // Passed authentication, so now migrate to salted hash (server side; client side hash/salt already completed above)
                    $new_salt=password_salt();
                    $new_hash=password_hash($client_side_hash,$new_salt);
                    $sqlUpdatePwd= " UPDATE " . TBL_PAT_ACC_ON
                                  ." SET " .COL_POR_PWD."=?, "
                                  . COL_POR_SALT . "=?, "
                                  . COL_POR_CLIENT_SALT . "=? "
                                  ." WHERE ".COL_ID."=?";
                    privStatement($sqlUpdatePwd,array($new_hash,$new_salt,$client_side_salt,$auth[COL_ID]));   
                }
                else {
                    // Using new salt method
                    if(is_array($Pass)) {
                        // RSA not available, so password has already been hashed/salted on client side
                        $client_side_hash=$Pass['code'];
                    }
                    else {
                        // RSA is working, so have the clear text password in $Pass
                        // Need to mimick the client side hash/salt before proceeding
                        $client_side_salt=$auth[COL_POR_CLIENT_SALT];
                        $client_side_hash=password_hash($Pass,$client_side_salt);
                    }

                    if(password_hash($Pass,$auth[COL_POR_SALT])!=$auth[COL_POR_PWD])
                    {
                        session_destroy();
                        header('Location: '.$landingpage.'&w');
                        exit;                        
                        
                    }
     
                }
                $_SESSION['portal_username']=$_POST['uname'];
		$sql = "SELECT * FROM `patient_data` WHERE `pid` = ?";

		if ($userData = sqlQuery($sql, array($auth['pid']) )) { // if query gets executed

			if (empty($userData)) {
                                // no records for this pid, so escape
				session_destroy();
                                header('Location: '.$landingpage.'&w');
				exit;
                        }

			if ($userData['allow_patient_portal'] != "YES") {
				// Patient has not authorized portal, so escape
				session_destroy();
                                header('Location: '.$landingpage.'&w');
				exit;
                        }

			if ($auth['pid'] != $userData['pid']) {
				// Not sure if this is even possible, but should escape if this happens
				session_destroy();
				header('Location: '.$landingpage.'&w');
				exit;
			}

                        if ( $password_update)
                        {
                            if (!(empty($_POST['code_new'])) && !(empty($_POST['code_new_confirm']))) {
                                if (is_array($Pass)) {
                                    // client side salt/hash already completed
                                    $code_new_client_hash = $_POST['code_new'];
                                    $code_new_confirm_client_hash = $POST['code_new_confirm'];
                                }
                                else {
                                    // need to mimick the client side salt/hash
                                    $code_new=$rsa->decrypt($_POST['code_new']);
                                    $code_new_client_hash = password_hash($code_new,$client_side_salt);
                                    $code_new_confirm=$rsa->decrypt($_POST['code_new_confirm']);
                                    $code_new_confirm_client_hash = password_hash($code_new_confirm,$client_side_salt);
                                }

                                if ($code_new_client_hash == $code_new_confirm_client_hash) {
                                    // now need to create new server side salt/hash
                                    $new_salt=password_salt();
                                    $new_hash=password_hash($code_new_client_hash,$new_salt);

                                    // Update the password and continue (patient is authorized)
                                    privStatement("UPDATE ".TBL_PAT_ACC_ON
                                                 ."  SET ".COL_POR_PWD."=?,".COL_POR_SALT."=?,".COL_POR_PWD_STAT."=1 WHERE id=?", array($new_hash,$new_salt,$auth['id']) );
                                    $authorizedPortal = true;
                                }
                            }
                        }
			if ($auth['portal_pwd_status'] == 0) {
				if(!$authorizedPortal) {
					// Need to enter a new password in the index.php script
					$_SESSION['password_update'] = 1;
                                	header('Location: '.$landingpage);
					exit;
				}
			}

			if ($auth['portal_pwd_status'] == 1) {
				// continue (patient is authorized)
				$authorizedPortal = true;
			}

			if ($authorizedPortal) {
                        	// patient is authorized (prepare the session variables)
				unset($_SESSION['password_update']); // just being safe
				unset($_SESSION['itsme']); // just being safe
				$_SESSION['pid'] = $auth['pid'];
				$_SESSION['patient_portal_onsite'] = 1;
			}
			else {
				session_destroy();
				header('Location: '.$landingpage.'&w');
				exit;
			}

		}
		else { //problem with query
			session_destroy();
			header('Location: '.$landingpage.'&w');
			exit;
		}		
    //

    require_once('summary_pat_portal.php');

?>
