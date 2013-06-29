<?php
/**
 * Functions to create and manage temp user authentication tokens.
 *
 * Copyright (C) 2013 Brady Miller <brady@sparmy.com>
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
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://www.open-emr.org
 */

require_once("$srcdir/authentication/password_hashing.php"); 

/**
 * Remove all temp user authentication tokens that are more than 30 seconds old.
 */
function cleanup_all_user_tokens() {
  sqlStatement("DELETE FROM `users_secure_tokens_temp` WHERE timestampdiff(second,created,now()) > '30'");
}

/**
 * Remove all user authentication tokens.
 *
 * @param  string  $user     User ID (number id)
 */
function cleanup_user_tokens($user_id) {
  sqlStatement("DELETE FROM `users_secure_tokens_temp` WHERE `id` = ?",array($user_id));
}

/**
 * Create a user authentication token. This will create and return a token.
 * A salt/hash of the token will be stored in users_secure_tokens_temp.
 *
 * @param  string  $user     User ID (number id)
 * @return string            returns token id
 */
function create_user_token($user_id) {

  // Get username
  $sql_ret = sqlQuery("SELECT `username` FROM `users` where `id` = ?",array($user_id));
  $username = $sql_ret['username'];

  // Create a random token
  $token = create_random_token();

  // Create a salt/hash of the token and insert into the the database
  $salt = password_salt();
  $hash = password_hash($token,$salt); 
  sqlStatement("INSERT INTO `users_secure_tokens_temp` (`id`,`username`,`salt`,`password`) VALUES (?,?,?,?)",array($user_id,$username,$salt,$hash));

  // Return the token
  return $token;
}

/**
 * Create and return a random token id.
 *
 * @return string            returns random token id
 */
function create_random_token() {

  // Set Allowed characters and token length
  $Allowed_Chars ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  $Chars_Len = 61;
  $Token_Length = 50;

  // Build the random token
  $random_token = "";
  for($i=0; $i<$Token_Length; $i++) {
    $random_token .= $Allowed_Chars[mt_rand(0,$Chars_Len)];
  }

  // Return the random token
  return $random_token;
}
?>
