<?php
/**
* Sql functions/classes for OpenEMR.
*
* Includes classes and functions that OpenEMR uses
* to interact with SQL.
*
* LICENSE: This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://opensource.org/licenses/gpl-license.php>.
* 
* @package   OpenEMR
* @link      http://www.open-emr.org
*/

require_once(dirname(__FILE__) . "/sqlconf.php");
require_once(dirname(__FILE__) . "/adodb/adodb.inc.php");
require_once(dirname(__FILE__) . "/adodb/drivers/adodb-mysql.inc.php");
require_once(dirname(__FILE__) . "/log.inc");

// Below arrays are for allowing both upper and lower case of several table
// names. The Primary is what is should be. However, because of differences
// in casing standards in windows/linux, some databases will be using the
// Secondary name, which can be fixed on the fly if an error is thrown
// in mysql. Note the PRIMARY_FORMAT is to allow use with the preg_replace
// function.
// This feature works by checking the error message if a sql command fails
// using the checkSqlErrorForCase() function. If it is true, then it will
// convert the statement to use the SECONDARY table titles and try the
// query again. (note it will also screen the errors for SECONDARY strings
// and then try the PRIMARY strings in this case)
// Note in the array need to place tables with common names such as form_CAMOS
// first, to prevent breaking things.
$TABLE_NAME_CASE_PRIMARY = array(
  'form_CAMOS',
  'form_CAMOS_category',
  'form_CAMOS_item',
  'form_CAMOS_subcategory',
  'RXNCONSO',
  'RXNREL',
  'RXNSAT',
  'RXNSTY'
);
$TABLE_NAME_CASE_PRIMARY_FORMAT = array(
  '/form_CAMOS/',
  '/form_CAMOS_category/',
  '/form_CAMOS_item/',
  '/form_CAMOS_subcategory/',
  '/RXNCONSO/',
  '/RXNREL/',
  '/RXNSAT/',
  '/RXNSTY/'
);
$TABLE_NAME_CASE_SECONDARY = array(
  'form_camos',
  'form_camos_category',
  'form_camos_item',
  'form_camos_subcategory',
  'rxnconso',
  'rxnrel',
  'rxnsat',
  'rxnsty'
);
$TABLE_NAME_CASE_SECONDARY_FORMAT = array(
  '/form_camos/',
  '/form_camos_category/',
  '/form_camos_item/',
  '/form_camos_subcategory/',
  '/rxnconso/',
  '/rxnrel/',
  '/rxnsat/',
  '/rxnsty/'
);


/**
* ADODB_mysql class wrapper to ensure proper auditing in OpenEMR.
*
* @author  Kevin Yeh <kevin.y@integralemr.com>
*/
class ADODB_mysql_log extends ADODB_mysql
{
        /**
        * ADODB Execute function wrapper to ensure proper auditing in OpenEMR.
        *
        * @param  string  $sql         query
        * @param  array   $inputarr    binded variables array (optional)
        * @return boolean              returns false if error
        */
    	function Execute($sql,$inputarr=false)
        {
            $retval= parent::Execute($sql,$inputarr);
            if ($retval === false) {
              $outcome = false;
              // Stash the error into last_mysql_error so it doesn't get clobbered when
              // we insert into the audit log.
              $GLOBALS['last_mysql_error']=$this->ErrorMsg();
            }
            else {
              $outcome = true;
            }
            // Stash the insert ID into lastidado so it doesn't get clobbered when
            // we insert into the audit log.
            $GLOBALS['lastidado']=$this->Insert_ID();
            auditSQLEvent($sql,$outcome,$inputarr);
            return $retval;
        }

        /**
        *  ADODB _insertid function wrapper to ensure proper auditing in OpenEMR.
        *
        *  Need to override this method to prevent infinite recursion with execute
        *  when trying to retrieve the last insert id.
        *
        * @return boolean    returns false if error
        */
        function _insertid()
        {
            $rs=$this->ExecuteNoLog("SELECT LAST_INSERT_ID()");
            $ret=reset($rs->fields);
            $rs->close();
            return $ret;
        }

        /**
        * ADODB Execute function wrapper to skip auditing in OpenEMR.
        *
        * Bypasses the OpenEMR auditing engine.
        *
        * @param  string  $sql         query
        * @param  array   $inputarr    binded variables array (optional)
        * @return boolean              returns false if error
        */
        function ExecuteNoLog($sql,$inputarr=false)
        {
            return parent::Execute($sql,$inputarr);
        }

        /*
        * ADODB GenID function wrapper to work with OpenEMR.
        *
        * Need to override to fix a bug where call to GenID was updating
        * sequences table but always returning a zero with the OpenEMR audit
        * engine both on and off. Note this bug only appears to occur in recent
        * php versions on windows. The fix is to use the ExecuteNoLog() function
        * rather than the Execute() functions within this function (otherwise,
        * there are no other changes from the original ADODB GenID function).
        *
        * @param  string  $seqname     table name containing sequence (default is adodbseq)
        * @param  integer $startID     id to start with for a new sequence (default is 1)
        * @return integer              returns the sequence integer
        */
        function GenID($seqname='adodbseq',$startID=1)
        {
                // post-nuke sets hasGenID to false
                if (!$this->hasGenID) return false;

                $savelog = $this->_logsql;
                $this->_logsql = false;
                $getnext = sprintf($this->_genIDSQL,$seqname);
                $holdtransOK = $this->_transOK; // save the current status
                $rs = @$this->ExecuteNoLog($getnext);
                if (!$rs) {
                        if ($holdtransOK) $this->_transOK = true; //if the status was ok before reset
                        $u = strtoupper($seqname);
                        $this->ExecuteNoLog(sprintf($this->_genSeqSQL,$seqname));
                        $cnt = $this->GetOne(sprintf($this->_genSeqCountSQL,$seqname));
                        if (!$cnt) $this->ExecuteNoLog(sprintf($this->_genSeq2SQL,$seqname,$startID-1));
                        $rs = $this->ExecuteNoLog($getnext);
                }

                if ($rs) {
                        $this->genID = mysql_insert_id($this->_connectionID);
                        $rs->Close();
                } else
                        $this->genID = 0;

                $this->_logsql = $savelog;
                return $this->genID;
        }
}
if (!defined('ADODB_FETCH_ASSOC')) define('ADODB_FETCH_ASSOC', 2);
$database = NewADOConnection("mysql_log"); // Use the subclassed driver which logs execute events
// Below clientFlags flag is telling the mysql connection to allow local_infile setting,
// which is needed to import data in the Administration->Other->External Data Loads feature.
// Note this is a specific bug to work in Ubuntu 12.04, of which the Data Load feature does not
// work and is suspicious for a bug in PHP of that OS; Setting this clientFlags fixes this bug
// and appears to not cause problems in other operating systems.
$database->clientFlags = 128;
$database->PConnect($host, $login, $pass, $dbase);
$GLOBALS['adodb']['db'] = $database;
$GLOBALS['dbh'] = $database->_connectionID;

// Modified 5/2009 by BM for UTF-8 project ---------
if (!$disable_utf8_flag) {
 $success_flag = $database->Execute("SET NAMES 'utf8'");
  if (!$success_flag) {
   error_log("PHP custom error: from openemr library/sql.inc  - Unable to set up UTF8 encoding with mysql database: ".getSqlLastError(), 0);
  }
}

// set up associations in adodb calls (not sure why above define
//  command does not work)
$GLOBALS['adodb']['db']->SetFetchMode(ADODB_FETCH_ASSOC);

//fmg: This makes the login screen informative when no connection can be made
if (!$GLOBALS['dbh']) {
  //try to be more helpful
  if ($host == "localhost") {
    echo "Check that mysqld is running.<p>";
  } else {
    echo "Check that you can ping the server '$host'.<p>";
  }//if local
  HelpfulDie("Could not connect to server!", getSqlLastError("native_mysql"));
  exit;
}//if no connection

/**
* Standard sql query in OpenEMR.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. Will continue to
* be compatible with previous function calls that do
* not use binding. It also has flexible
* casing for some table names.
* If use adodb binding, then will return a recordset object.
* If do not use binding, then will return a resource object.
* The sqlFetchArray() function should be used to
* utilize the return object (it will accept both recordset
* and resource objects).
*
* @param  string  $statement  query
* @param  array   $binds      binded variables array (optional)
* @return recordset/resource
*/
function sqlStatement($statement, $binds=NULL )
{
  if (is_array($binds)) {
    // Use adodb Execute with binding and return a recordset.
    //   Note that the auditSQLEvent function is embedded
    //    in the Execute command.
    $recordset = $GLOBALS['adodb']['db']->Execute( $statement, $binds );
    if ($recordset === FALSE) {
      $mode = checkSqlErrorForCase($statement);
      if ($mode) {
        return sqlStatement(convertSqlCase($statement, $mode), $binds);
      }
      else {
        HelpfulDie("query failed: $statement", getSqlLastError());
      }
    }
    return $recordset;
  }
  else {
    // Use mysql_query and return a resource.
    $resource = mysql_query($statement, $GLOBALS['dbh']);
    if ($resource === FALSE) {
      $mode = checkSqlErrorForCase($statement,"native_mysql");
      if ($mode) {
        return sqlStatement(convertSqlCase($statement, $mode), $binds);
      }
      else {
        auditSQLEvent($statement, FALSE, $binds);
        HelpfulDie("query failed: $statement", getSqlLastError("native_mysql"));
      }
    }
    auditSQLEvent($statement, TRUE, $binds);
    return $resource;
  }
}

/**
* Specialized sql query in OpenEMR that skips auditing.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. Will continue to
* be compatible with previous function calls that do
* not use binding. It is equivalent to the 
* sqlStatement() function, EXCEPT it skips the
* audit engine. This function should only be used
* in very special situations. It also has flexible
* casing for some table names.
* If use adodb binding, then will return a recordset object.
* If do not use binding, then will return a resource object.
* The sqlFetchArray() function should be used to
* utilize the return object (it will accept both recordset
* and resource objects).
*
* @param  string  $statement  query
* @param  array   $binds      binded variables array (optional)
* @return recordset/resource
*/
function sqlStatementNoLog($statement, $binds=NULL )
{
  if (is_array($binds)) {
    // Use adodb ExecuteNoLog with binding and return a recordset.
    $recordset = $GLOBALS['adodb']['db']->ExecuteNoLog( $statement, $binds );
    if ($recordset === FALSE) {
      $mode = checkSqlErrorForCase($statement);
      if ($mode) {
        return sqlStatementNoLog(convertSqlCase($statement, $mode), $binds);
      }
      else {
        HelpfulDie("query failed: $statement", getSqlLastError());
      }
    }
    return $recordset;
  }
  else {
    // Use mysql_query and return a resource.
    $resource = mysql_query($statement, $GLOBALS['dbh']);
    if ($resource === FALSE) {
      $mode = checkSqlErrorForCase($statement,"native_mysql");
      if ($mode) {
        return sqlStatementNoLog(convertSqlCase($statement, $mode), $binds);
      }
      else {
        HelpfulDie("query failed: $statement", getSqlLastError("native_mysql"));
      }
    }
    return $resource;
  }
}

/**
* sqlStatement() function wrapper for CDR engine in OpenEMR.
* Allows option to turn on/off auditing specifically for the
* CDR engine.
* 
* @param  string  $statement  query
* @param  array   $binds      binded variables array (optional)
* @return recordset/resource
*/
function sqlStatementCdrEngine($statement, $binds=NULL )
{
  if ($GLOBALS['audit_events_cdr']) {
    return sqlStatement($statement,$binds);
  }
  else {
    return sqlStatementNoLog($statement,$binds);
  }
}

/**
* Returns a row (as an array) from a sql recordset or resource object.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection.
* It will act upon the object returned from the
* sqlStatement() function (and sqlQ() function).
* It will automatically figure out if the input
* object is a recordset or a resource.
*
* @param recordset/resource $r
* @return array
*/
function sqlFetchArray($r)
{
  if (!is_resource($r)) {
    //treat as an adodb recordset
    if ($r === FALSE)
      return false;
    if ($r->EOF)
      return false;
    //ensure it's an object (ie. is set)
    if (!is_object($r))
      return false;
    return $r->FetchRow();
  }
  else {
    //treat as a mysql_query resource
    if ($r == FALSE)
      return false;
    return mysql_fetch_array($r, MYSQL_ASSOC);
  }
}

/**
* Standard sql insert query in OpenEMR.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. This function
* is specialized for insert function and will return
* the last id generated from the insert. It also has flexible
* casing for some table names.
*
* @param  string   $statement  query
* @param  array    $binds      binded variables array (optional)
* @return integer  Last id generated from the sql insert command
*/
function sqlInsert($statement, $binds=array())
{
  //Run a adodb execute
  // Note the auditSQLEvent function is embedded in the
  //   Execute function.
  $recordset = $GLOBALS['adodb']['db']->Execute($statement, $binds);
  if ($recordset === FALSE) {
    $mode = checkSqlErrorForCase($statement);
    if ($mode) {
      return sqlInsert(convertSqlCase($statement, $mode), $binds);
    }
    else {
      HelpfulDie("query failed: $statement", getSqlLastError());
    }
  }
  // Return the correct last id generated using function
  //   that is safe with the audit engine.
  return getSqlLastID();
}

/**
* Specialized sql query in OpenEMR that only returns
* the first row of query results as an associative array.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. It also has flexible
* casing for some table names.
*
* @param  string  $statement  query
* @param  array   $binds      binded variables array (optional)
* @return array
*/
function sqlQuery($statement, $binds=NULL)
{
  if (is_array($binds)) {
    $recordset = $GLOBALS['adodb']['db']->Execute( $statement, $binds );
  }
  else {
    $recordset = $GLOBALS['adodb']['db']->Execute( $statement );
  }
  if ($recordset === FALSE) {
    $mode = checkSqlErrorForCase($statement);
    if ($mode) {
      return sqlQuery(convertSqlCase($statement, $mode), $binds);
    }
    else {
      HelpfulDie("query failed: $statement", getSqlLastError());
    }
  }
  if ($recordset->EOF)
   return FALSE;
  $rez = $recordset->FetchRow();
  if ($rez == FALSE)
    return FALSE;
  return $rez;
}

/**
* Specialized sql query in OpenEMR that bypasses the auditing engine
* and only returns the first row of query results as an associative array.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. It is equivalent to the
* sqlQuery() function, EXCEPT it skips the
* audit engine. This function should only be used
* in very special situations.  It also has flexible
* casing for some table names.
*
* @param  string  $statement  query
* @param  array   $binds      binded variables array (optional)
* @return array
*/
function sqlQueryNoLog($statement, $binds=NULL)
{
  if (is_array($binds)) {
    $recordset = $GLOBALS['adodb']['db']->ExecuteNoLog( $statement, $binds );
  }
  else {
    $recordset = $GLOBALS['adodb']['db']->ExecuteNoLog( $statement );
  }
  if ($recordset === FALSE) {
    $mode = checkSqlErrorForCase($statement);
    if ($mode) {
      return sqlQueryNoLog(convertSqlCase($statement, $mode), $binds);
    }
    else {
      HelpfulDie("query failed: $statement", getSqlLastError());
    }
  }
  if ($recordset->EOF)
   return FALSE;
  $rez = $recordset->FetchRow();
  if ($rez == FALSE)
    return FALSE;
  return $rez;
}

/**
* Specialized sql query in OpenEMR that ignores sql errors, bypasses the
* auditing engine and only returns the first row of query results as an
* associative array.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. It is equivalent to the
* sqlQuery() function, EXCEPT it skips the
* audit engine and ignores erros. This function should only be used
* in very special situations.  It also has flexible
* casing for some table names.
*
* @param  string  $statement  query
* @param  array   $binds      binded variables array (optional)
* @return array
*/
function sqlQueryNoLogIgnoreError($statement, $binds=NULL)
{
  if (is_array($binds)) {
    $recordset = $GLOBALS['adodb']['db']->ExecuteNoLog( $statement, $binds );
  }
  else {
    $recordset = $GLOBALS['adodb']['db']->ExecuteNoLog( $statement );
  }
  if ($recordset === FALSE) {
    $mode = checkSqlErrorForCase($statement);
    if ($mode) {
      return sqlQueryNoLogIgnoreError(convertSqlCase($statement, $mode), $binds);
    }
    else {
      // ignore the error and return FALSE
      return FALSE;
    }
  }
  if ($recordset->EOF)
   return FALSE;
  $rez = $recordset->FetchRow();
  if ($rez == FALSE)
    return FALSE;
  return $rez;
}

/**
* sqlQuery() function wrapper for CDR engine in OpenEMR.
* Allows option to turn on/off auditing specifically for the
* CDR engine.
*
* @param  string  $statement  query
* @param  array   $binds      binded variables array (optional)
* @return array
*/
function sqlQueryCdrEngine($statement, $binds=NULL )
{
  if ($GLOBALS['audit_events_cdr']) {
    return sqlQuery($statement,$binds);
  }
  else {
    return sqlQueryNoLog($statement,$binds);
  }
}

/**
* Specialized sql query in OpenEMR that skips auditing.
*
* This function should only be used in very special situations.
* It also has flexible
* casing for some table names.
*
* @param  string  $statement  query
*/
function sqlInsertClean_audit($statement)
{
  $ret = $GLOBALS['adodb']['db']->ExecuteNoLog($statement);
  if ($ret === FALSE) {
    $mode = checkSqlErrorForCase($statement);
    if ($mode) {
      return sqlInsertClean_audit(convertSqlCase($statement, $mode));
    }
    else {
      HelpfulDie("query failed: $statement", getSqlLastError());
    }
  }
}

/**
* Function that will safely return the last ID inserted,
* and accounts for the audit engine.
*
* @return  integer Last ID that was inserted into sql
*/
function getSqlLastID() {
  if ($GLOBALS['lastidado'] >0) {
    return $GLOBALS['lastidado'];
  }
  else {
    return $GLOBALS['adodb']['db']->Insert_ID();
  }
}

/**
* Function that will safely return the last error,
* and accounts for the audit engine.
*
* @param   string  $mode either adodb(default) or native_mysql
* @return  string        last mysql error
*/
function getSqlLastError($mode="adodb") {
  if (!empty($GLOBALS['last_mysql_error'])) {
    return $GLOBALS['last_mysql_error'];
  }
  else {
    if ($mode == "adodb") {
      return $GLOBALS['adodb']['db']->ErrorMsg();
    }
    else { // $mode = "native_mysql"
      return mysql_error($GLOBALS['dbh']);
    }
  }
}

/**
* Process sql error and return information if
* the query needs to be recased and requeried
*
* @param   string         $statement  sql query statement
* @param   string         $mode       either adodb(default) or native_mysql
* @return  string/boolean             return PRIMARY/SECONDARY if should be recased and re-queried or return true
*/
function checkSqlErrorForCase($statement,$mode="adodb") {
  global $TABLE_NAME_CASE_PRIMARY, $TABLE_NAME_CASE_SECONDARY;

  $primary=FALSE; //flag
  $secondary=FALSE; //flag

  foreach ($TABLE_NAME_CASE_PRIMARY as $key => $value) {
    if (preg_match("/^Table '.+".$value."' doesn't exist$/",getSqlLastError($mode))) {
      //TODO - Check to ensure the value is in the statement

      //Ensure the Secondary table exist to avoid infinite recursion
      $check_table = sqlQueryCheckSqlErrorForCase("SHOW TABLES LIKE '$TABLE_NAME_CASE_SECONDARY[$key]'");
      if ($check_table) {
        // Ensure all table names in query are consistent(all primary) and the secondary exist
        foreach ($TABLE_NAME_CASE_PRIMARY as $key_sec => $value_sec) {
          if (preg_match("/".$TABLE_NAME_CASE_SECONDARY[$key_sec]."/",$statement)) {
            //secondary is in query, so exit
            return FALSE;
          }
          if (preg_match("/".$value_sec."/",$statement)) {
            //primary is in query, so ensure the secondary table exists
            //sort of a duplicate of previous check, but in another way
            $check_table_sec = sqlQueryCheckSqlErrorForCase("SHOW TABLES LIKE '$TABLE_NAME_CASE_SECONDARY[$key_sec]'");
            if ($check_table_sec) {
              $primary=TRUE;
            }
            else {
              return FALSE;
            }
          }
        }
      }
      break; //break out of the foreach after get one hit
    }
  }

  foreach ($TABLE_NAME_CASE_SECONDARY as $key => $value) {
    if (preg_match("/^Table '.+".$value."' doesn't exist$/",getSqlLastError($mode))) {
      //TODO - Check to ensure the value is in the statement

      //Ensure the primary table exist to avoid infinite recursion
      $check_table = sqlQueryCheckSqlErrorForCase("SHOW TABLES LIKE '$TABLE_NAME_CASE_PRIMARY[$key]'");
      if ($check_table) {
        // Ensure all table names in query are consistent(all secondary) and the primary exist
        foreach ($TABLE_NAME_CASE_SECONDARY as $key_sec => $value_sec) {
          if (preg_match("/".$TABLE_NAME_CASE_PRIMARY[$key_sec]."/",$statement)) {
            //primary is in query, so exit
            return FALSE;
          }
          if (preg_match("/".$value_sec."/",$statement)) {
            //secondary is in query, so ensure the primary table exists
            //sort of a duplicate of previous check, but in another way
            $check_table_sec = sqlQueryCheckSqlErrorForCase("SHOW TABLES LIKE '$TABLE_NAME_CASE_PRIMARY[$key_sec]'");
            if ($check_table_sec) {
              $secondary=TRUE;
            }
            else {
              return FALSE;
            }
          }
        }
      }
      break; //break out of the foreach after get one hit
    }
  }

  // return correct boolean/string
  if ($primary && $secondary) return FALSE;
  if ($primary) return "PRIMARY";
  if ($secondary) return "SECONDARY";
  return FALSE;
}

/**
* Highly Specialized sql query in OpenEMR only to be called by the
*  checkSqlErrorForCase function to avoid infinite recursion.
*
* @param  string  $statement  query
* @return array
*/
function sqlQueryCheckSqlErrorForCase($statement)
{
  $recordset = $GLOBALS['adodb']['db']->ExecuteNoLog( $statement );
  if ($recordset === FALSE) {
    return FALSE;
  }
  if ($recordset->EOF)
   return FALSE;
  $rez = $recordset->FetchRow();
  if ($rez == FALSE)
    return FALSE;
  return $rez;
}

/**
* Convert casing of pertinent tables in a sql queary
*
* @param   string $statement  sql query statement
* @param   string $mode       either PRIMARY or SECONDARY
* @return  string             return the converted sql query statement
*/
function convertSqlCase($statement, $mode) {
  global $TABLE_NAME_CASE_PRIMARY, $TABLE_NAME_CASE_PRIMARY_FORMAT, $TABLE_NAME_CASE_SECONDARY, $TABLE_NAME_CASE_SECONDARY_FORMAT;

  if ($mode == "PRIMARY") {
    //Convert from the primary to secondary
    return  preg_replace($TABLE_NAME_CASE_PRIMARY_FORMAT,$TABLE_NAME_CASE_SECONDARY,$statement);
  }
  else { //$mode == "SECONDARY"
    //Convert from the secondary to primary
    return  preg_replace($TABLE_NAME_CASE_SECONDARY_FORMAT,$TABLE_NAME_CASE_PRIMARY,$statement);
  }
}

/**
* Function that will return an array listing
* of columns that exist in a table.
*
* @param   string  $table sql table
* @return  array
*/
function sqlListFields($table) {
  $sql = "SHOW COLUMNS FROM ". mysql_real_escape_string($table);
  $resource = sqlQ($sql);
  $field_list = array();
  while($row = mysql_fetch_array($resource)) {
    $field_list[] = $row['Field'];
  }
  return $field_list;
}

/**
* Returns the number of sql rows
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection.
* It will act upon the object returned from the
* sqlStatement() function (and sqlQ() function).
* It will automatically figure out if the input
* object is a recordset or a resource.
*
* @param recordset/resource $r
* @return integer Number of rows
*/
function sqlNumRows($r)
{
  if (!is_resource($r)) {
    //treat as an adodb recordset
    return $r->RecordCount();
  }
  else {
    //treat as a mysql_query resource
    return mysql_num_rows($r);
  }
}

/**
* Error function for OpenEMR sql functions
*
* @param string $statement
* @param string $sqlerr
*/
function HelpfulDie ($statement, $sqlerr='')
{
  echo "<p><p><font color='red'>ERROR:</font> $statement<p>";
  if ($sqlerr) {
    echo "Error: <font color='red'>$sqlerr</font><p>";
  }//if error
  exit;
}

/**
* @todo document use of the generate_id function
*/
function generate_id () {
  $database = $GLOBALS['adodb']['db'];
  return $database->GenID("sequences");
}

/**
* Specialized sql query in OpenEMR with limited functionality
*
* Does not fully incorporate the audit engine, so
* recommend not using this function (if bind is set,
* then will get logged, however if bind is not set,
* then will not get logged).  
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. Will continue to
* be compatible with previous function calls that do
* not use binding. It also has flexible
* casing for some table names.
* If use adodb binding, then will return a recordset object.
* If do not use binding, then will return a resource object.
* The sqlFetchArray() function should be used to
* utilize the return object (it will accept both recordset
* and resource objects).
*
* @deprecated
* @param  string  $statement  query
* @param  array   $binds      binded variables array (optional)
* @return recordset/resource
*/
function sqlQ($statement, $binds=NULL )
{
  if (is_array($binds)) {
    $recordset = $GLOBALS['adodb']['db']->Execute( $statement, $binds );
    if ($recordset === FALSE) {
      $mode = checkSqlErrorForCase($statement);
      if ($mode) {
        return sqlQ(convertSqlCase($statement, $mode), $binds);
      }
      else {
        HelpfulDie("query failed: $statement", getSqlLastError());
      }
    }
    return $recordset;
  }
  else {
    $resource = mysql_query($statement, $GLOBALS['dbh']);
    if ($resource === FALSE) {
      $mode = checkSqlErrorForCase($statement,"native_mysql");
      if ($mode) {
        return sqlQ(convertSqlCase($statement, $mode), $binds);
      }
      else {
        HelpfulDie("query failed: $statement", getSqlLastError("native_mysql"));
      }
    }
    return $resource;
  }
}

/**
* Simple wrapper for sqlInsert() function (deprecated).
*
* Function that will allow use of the adodb binding feature
* to prevent sql-injection.
*
* @deprecated
* @param  string   $statement  query
* @param  array    $binds      binded variables array (optional)
* @return integer  Last id generated from the sql insert command
*/
function idSqlStatement($statement , $binds=NULL )
{
  return sqlInsert($statement, $binds);
}

/**
* Simple wrapper for sqlInsert() function (deprecated).
*
* Function that will allow use of the adodb binding feature
* to prevent sql-injection.
*
* @deprecated
* @param  string   $statement  query
* @param  array    $binds      binded variables array (optional)
* @return integer  Last id generated from the sql insert command
*/
function sqlInsertClean($statement, $binds=NULL )
{
  return sqlInsert($statement, $binds);
}

/**
* Sql connection function (deprecated)
*
* No longer needed
*
* @deprecated
* @param string $login
* @param string $pass
* @param string $dbase
* @param string $host
* @param string $port
* @return connection
*/
function sqlConnect($login,$pass,$dbase,$host,$port = '3306')
{
  $GLOBALS['dbh'] = $database->_connectionID;
  return $GLOBALS['dbh'];
}

/**
* Sql close connection function (deprecated)
*
* No longer needed since PHP does this automatically.
*
* @deprecated
* @return boolean
*/
function sqlClose()
{
  //----------Close our mysql connection
  $closed = $GLOBALS['adodb']['db']->close or
    HelpfulDie("could not disconnect from mysql server link", getSqlLastError());
  return $closed;
}

/**
* Very simple wrapper function and not necessary (deprecated)
*
* Do not use.
*
* @deprecated
* @return connection
*/
function get_db() {
  return $GLOBALS['adodb']['db'];
}



?>
