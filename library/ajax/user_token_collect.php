<?php
/**
 * Ajax script to create and return a temp user authentication token.
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

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once(dirname(__FILE__) . "/../../interface/globals.php");
require_once(dirname(__FILE__) . "/../authentication/user_tokens.php");

if (isset($_SESSION['authId']) && !empty($_SESSION['authId'])) {

  // Remove all outdated user authentication tokens
  // This is a safeguard
  cleanup_all_user_tokens();

  // Remove all current user authentication tokens
  // There should not be any but this is a safeguard
  cleanup_user_tokens($_SESSION['authId']);

  // Create a new user token
  $token = create_user_token($_SESSION['authId']);

  // Return the token
  echo $token;
}
else {
  // No user is set in the Session, so return a dummy token that will not work.
  echo "dummy";
}
?>
