<?php
/**
 * This is a library of commonly used functions for managing data for authentication
 * 
 * Copyright (C) 2013 Kevin Yeh <kevin.y@integralemr.com> and OEMR <www.oemr.org>
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
 * @author  Kevin Yeh <kevin.y@integralemr.com>
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://www.open-emr.org
 */

require_once("$srcdir/authentication/common_operations.php");

/**
 *
 * Returns the authentication method. Note this function is only used when the rsa method
 * is not being used.
 *
 * @param   string  $username  Username
 * @param   int     $portal    0 for no portal, 1 for onsite portal
 * @return  string             Method of client side authentication (sha1,sha1upgrade or sha1)
 */
function client_authentication_method($username,$portal=0)
{
    // Set default client side authentication when rsa is not supported
    $default_client_side_method = "sha1";

    // if $username is empty, then simply return sha1 (normal behavior when rsa is not supported)
    if (!isset($username) || empty($username)) {
        return $default_client_side_method;
    }

    // if forcing the new password security mechanism, then simply return sha1 (normal behavior when rsa is not supported)
    if((isset($GLOBALS['password_compatibility'])&&!$GLOBALS['password_compatibility'])) {
        return $default_client_side_method;
    }

    if ($portal==1) {
        // Look in the onsite portal credentials
        $query_ret = sqlQuery("SELECT CHAR_LENGTH(portal_pwd) as `length_hash` FROM `patient_access_onsite` WHERE `portal_username` = ?", array($username));
    }
    else {
        // Look in the main credentials
        $query_ret = sqlQuery("SELECT CHAR_LENGTH(".COL_PWD.") as `length_hash` FROM `".TBL_USERS."` WHERE `".COL_UNM."` = ?", array($username));
    }

    if (!empty($query_ret['length_hash']) && strlen($query_ret['length_hash']) == 32) {
        return "md5";
    }
    else if (!empty($query_ret['length_hash']) && strlen($query_ret['length_hash']) == 40) {
        return "sha1upgrade";
    }
    else {
        // This is normal behavior when rsa is not supported
        return $default_client_side_method;
    }
}

/**
 *
 * Returns the client side salt of a user. If user does not exist or the user is migrating
 * from an old password mechanism to new password mechanism then a random salt will be returned.
 * To not allow brute force user checking, the random salt is stored in a dummy table and that is
 * then used for the user in the future (read more details within the function below).  Note this
 * function is only used when the rsa method is not being used.
 *
 * @param   string  $username  Username
 * @param   int     $portal    0 for no portal, 1 for onsite portal
 * @return  string             Salt of client side authentication for user, if applicable.
 */
function client_authentication_salt($username,$portal=0)
{
    // if $username is empty, then simply return a random salt
    if (!isset($username) || empty($username)) {
        return password_salt(true);
    }

    if ($portal==1) {
        // Look in the onsite portal credentials
        $query_ret = sqlQuery("SELECT `portal_salt_client_side` as `".COL_SALT_CLIENT."` FROM `patient_access_onsite` WHERE `portal_username` = ?", array($username));
    }
    else {
        // Look in the main credentials
        $query_ret = sqlQuery("SELECT `".COL_SALT_CLIENT."` FROM `".TBL_USERS_SECURE."` WHERE `".COL_UNM."` = ?", array($username));
    }

    if (isset($query_ret[COL_SALT_CLIENT]) && !empty($query_ret[COL_SALT_CLIENT])) {
        return $query_ret[COL_SALT_CLIENT];
    }
    else {
        // No user exists or the user needs to be transitioned, return a random salt.
        // To not allow guessing of active users, will place the entry in a dummy
        // salt storage table, which will use when a user that does not exist or needs
        // to be transitioned is requested more than once. (note that this is not meant
        // to store users client side salts; since this salt and the users client salt may
        // may differ; it is only meant to not allow brute force user checking and the table
        // will need to be emptied whenever the client side hashing mechanism is upgraded)
        $query_ret_dummy = sqlQuery("SELECT `".COL_SALT_CLIENT."` FROM `".TBL_USERS_DUMMY."` WHERE `".COL_UNM."` = ?", array($username));
        if (isset($query_ret[COL_SALT_CLIENT]) && !empty($query_ret[COL_SALT_CLIENT])) {
            return $query_ret[COL_SALT_CLIENT];
        }
        else {
            $new_dummy_salt = password_salt(true);
            sqlInsert("INSERT INTO ".TBL_USERS_DUMMY." (".COL_UNM.",".COL_SALT_CLIENT.") VALUES (?,?)", array($username,$new_dummy_salt));
            return $new_dummy_salt;
        }
    }
}

/**
 * 
 * @param type $username
 * @param string/array $password    password is passed by reference so that it can be "cleared out"
 *                                  as soon as we are done with it. Note that it is a string when using
 *                                  clear text and an array when has already been hashed on the client sider
 *                                  with second string in array for an additional hash if needed for migration.
 * @param type $provider
 */
function validate_user_password($username,&$password,$provider)
{
    $ip=$_SERVER['REMOTE_ADDR'];
    
    $valid=false;
    $getUserSecureSQL= " SELECT " . implode(",",array(COL_ID,COL_PWD,COL_SALT,COL_SALT_CLIENT))
                       ." FROM ".TBL_USERS_SECURE
                       ." WHERE ".COL_UNM."=?";
    $userSecure=privQuery($getUserSecureSQL,array($username));
    if(is_array($userSecure))
    {
        if (is_array($password)) {
            // No clear text password available and client hash has already happened, so just do the server hash.
            $phash=password_hash($password['phash_client'],$userSecure[COL_SALT]);
        }
        else {
            // Using the clear text passwored
            // 1. Need to simulate the client hash
            $phash_client=password_hash($password,$userSecure[COL_SALT_CLIENT]);
            // 2. Now do the server hash
            $phash=password_hash($phash_client,$userSecure[COL_SALT]);
        }
        if($phash!=$userSecure[COL_PWD])
        {
            
            return false;
        }
        $valid=true;
    }
    else
    {  
        if((!isset($GLOBALS['password_compatibility'])||$GLOBALS['password_compatibility']))           // use old password scheme if allowed.
        {
            $getUserSQL="select id, password from users where username = ?";
            $userInfo = privQuery($getUserSQL,array($username));            
            $dbPasswordLen=strlen($userInfo['password']);
            if($dbPasswordLen==32)
            {
                if (is_array($password)) {
                    // No clear text password available and hash of password already has been completed on the client side.
                    // (yes, pass the hash vulnerability happens here this one time)
                    $phash=$password['phash_client_extra'];
                }
                else {
                    // Hash the clear text password
                    $phash=md5($password);
                }
                $valid=$phash==$userInfo['password'];
            }
            else if($dbPasswordLen==40)
            {
                if (is_array($password)) {
                    // No clear text password available and hash of password already has been completed on the client side.
                    // (yes, pass the hash vulnerability happens here this one time)
                    $phash=$password['phash_client_extra'];
                }
                else {
                    // Hash the clear text password
                    $phash=sha1($password);
                }
                $valid=$phash==$userInfo['password'];
            }
            if($valid)
            {
                initializePassword($username,$userInfo['id'],$password);
                purgeCompatabilityPassword($username,$userInfo['id']);
                $_SESSION['relogin'] = 1;
            }
            else
            {
                return false;
            }
    }
        
    }
    $getUserSQL="select id, authorized, see_auth".
                        ", cal_ui, active ".
                        " from users where username = ?";
    $userInfo = privQuery($getUserSQL,array($username));
    
    if ($userInfo['active'] != 1) {
        newEvent( 'login', $username, $provider, 0, "failure: $ip. user not active or not found in users table");
        $password='';
        return false;
    }  
    // Done with the possible cleartext password at this point!
    $password='';
    if($valid)
    {
        if ($authGroup = privQuery("select * from groups where user=? and name=?",array($username,$provider)))
        {
            $_SESSION['authUser'] = $username;
            $_SESSION['authGroup'] = $authGroup['name'];
            $_SESSION['authUserID'] = $userInfo['id'];
            $_SESSION['authPass'] = $phash;
            $_SESSION['authProvider'] = $provider;
            $_SESSION['authId'] = $userInfo{'id'};
            $_SESSION['cal_ui'] = $userInfo['cal_ui'];
            $_SESSION['userauthorized'] = $userInfo['authorized'];
            // Some users may be able to authorize without being providers:
            if ($userInfo['see_auth'] > '2') $_SESSION['userauthorized'] = '1';
            newEvent( 'login', $username, $provider, 1, "success: $ip");
            $valid=true;
        } else {
            newEvent( 'login', $username, $provider, 0, "failure: $ip. user not in group: $provider");
            $valid=false;
        }
        
        
        
    }
    return $valid;
}

function verify_user_gacl_group($user)
{
    global $phpgacl_location;
    if (isset ($phpgacl_location)) {
      if (acl_get_group_titles($user) == 0) {
          newEvent( 'login', $user, $provider, 0, "failure: $ip. user not in any phpGACL groups. (bad username?)");
	  return false;
      }
    }
    return true;
}
?>
