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

require_once("$srcdir/authentication/privDB.php");
require_once("$srcdir/authentication/password_hashing.php");
define("TBL_USERS_SECURE","users_secure");
define("TBL_USERS","users");

define("COL_PWD","password");
define("COL_UNM","username");
define("COL_ID","id");
define("COL_SALT","salt");
define("COL_LU","last_update");
define("COL_PWD_H1","password_history1");
define("COL_SALT_H1","salt_history1");

define("COL_PWD_H2","password_history2");
define("COL_SALT_H2","salt_history2");

define("COL_SALT_CLIENT","salt_client_side");

define("TBL_USERS_DUMMY","client_salt_dummy");


/**
 * create a new password entry in the users_secure table
 * 
 * @param type         $username
 * @param string/array $password  Passing by reference so additional copy is not created in memory
 *                                Note a string is for clear text.
 *                                Note an array is used when client side hash has already happened
 */
function initializePassword($username,$userid,&$password)
{
    
    $salt=password_salt();
    if (is_array($password)) {
        // No cleartext password is available. Client side salt has already been set and clear text has already been hashed on client side
        $client_side_salt = $password['client_salt'];
        $hash=password_hash($password['phash_client'],$salt);
    }
    else {
        // Using cleartext password, so need to create a client side salt/hash and mimick the client side hash
        $client_side_salt = password_salt(true);
        $hash_client=password_hash($password,$client_side_salt);
        $hash=password_hash($hash_client,$salt);
    }

    $hash=password_hash($password,$salt);
    $passwordSQL= "INSERT INTO ".TBL_USERS_SECURE.
                  " (".implode(",",array(COL_ID,COL_UNM,COL_PWD,COL_SALT,COL_LU,COL_SALT_CLIENT)).")".
                  " VALUES (?,?,?,?,NOW(),?) ";
                  
    $params=array(
                    $userid,
                    $username,
                    $hash,
                    $salt,
                    $client_side_salt
    );
    privStatement($passwordSQL,$params); 
}


/**
 * After a user's password has been updated to use the new hashing strategy wipe out the old hash value.
 * 
 * 
 * @param type $username
 * @param type $userid
 */
function purgeCompatabilityPassword($username,$userid)
{
    $purgeSQL = " UPDATE " . TBL_USERS 
                ." SET ". COL_PWD . "='NoLongerUsed' "
                ." WHERE ".COL_UNM. "=? "
                ." AND ".COL_ID. "=?";
    privStatement($purgeSQL,array($username,$userid));
}
?>
