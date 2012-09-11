<?php
/**
 * edih_view.php
 * 
 * Copyright 2012 Kevin McCormick Longview, Texas
 * 
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 or later.  You should have 
 * received a copy of the GNU General Public License along with this program; 
 * if not, write to the Free Software Foundation, Inc., 
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *  <http://opensource.org/licenses/gpl-license.php>
 * 
 * 
 * @author Kevin McCormick
 * @link: http://www.open-emr.org
 * @package OpenEMR
 * @subpackage ediHistory
 */
 
$sanitize_all_escapes=true; 
$fake_register_globals=false; 
require_once(dirname(__FILE__) . "/../globals.php");
//

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>edi history</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="<?php echo $web_root?>/library/css/jquery-ui-1.8.21.custom.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $web_root?>/library/css/jquery.dataTables.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $web_root?>/library/css/edi_history.css" type="text/css" />
</head>
<body>

<!-- Begin tabs section -->
<div id="tabs">
  <ul class="Clear">
   <li><a href="#newfiles" id="btn-newfiles">New Files</a></li>
   <li><a href="#csvdatatables" id="btn-csvdatatables">CSV Tables</a></li>
   <li><a href="#erafiles" id="btn-erafiles">ERA Files</a></li>
   <li><a href="#x12text" id="btn-x12text">x12 Text</a></li>
   <li><a href="#edinotes" id="btn-edinotes">Notes</a></li>
  </ul> 	


    <div id="newfiles">
        <table cols=2> 
        <tr vertical-align="middle">
         <td align="center">       
            <form id='upload_new' action="edi_history_main.php" method="POST" enctype="multipart/form-data">
                <fieldset>
                <legend>Select one or more files to upload</legend> 
                <input id="upload_file" type="file" name="fileUplMulti[]" multiple /> 
                <input type="submit" name="uplsubmt" value="Submit" />
                </fieldset>
            </form>
         </td>
         <td align="center">
            <form id="process_new" action="edi_history_main.php" method="post">
                <fieldset>
                <legend>Process new files for CSV records:</legend>
                <input type="checkbox" name="htmlout" checked /> HTML Output? 
                <input type="checkbox" name="erronly" checked /> Show Errors Only? &nbsp;&nbsp;<br />
                <input type="hidden" name="NewFiles" value="ProcessNew">
                <label for="New-Files">Process New Files:</label>
                <input id="processfiles"  name="Process" type="button" value="Process" />
                </fieldset>
            </form>
         </td>
        </tr>
        </table>
    
        <div id='srvvals'></div>
        <div id='pfresult'></div>
        <div id='clmstat' title='Status of Claim'></div>
        <div id='batchclm' title='Segments Batch Claim'></div>

    </div> 
    
    <div id="csvdatatables">
		<table cols='2'>
		<tr>
		<td colspan='4'>
		
		<form id="formcsvtables" name="view_csv" action="edi_history_main.php" target="_blank" method="post">
			<fieldset style='float:left'>
				<legend>View CSV tables:</legend>
				<table cols='4'>
					<tr>
						<td colspan='4'>
							Select a percentage of the rows or or select dates
						</td>
					</tr>
					<tr>
						<td align='center'>
							Select CSV table:
						</td>
						<td align='center'>
							Pct (%) of rows
						</td>
						<td align='left'>
							Start Date: &nbsp; End Date:
						</td>
						<td align='center'>
							Submit
						</td>
					</tr>
					<tr height='1.5em'>
						<td align='center'>					
							<select id='csvselect' name="csvtables"> 
							<!--
								<option value="" selected="selected">Choose from list</option>
								<option value="batch files">batch files</option>
								<option value="batch claims">batch claims</option>
								<option value="f997 files">997 files</option>
								<option value="f997 claims">997 claims</option>
								<option value="f277 files">277 files</option>
								<option value="f277 claims">277 claims</option>
                                <option value="era files">ERA files</option>
								<option value="era claims">ERA claims</option>
								<option value="ibr files">ibr files</option>
								<option value="ibr claims">ibr claims</option>
								<option value="ebr files">ebr files</option>
								<option value="ebr claims">ebr claims</option>
								<option value="dpr claims">dpr claims</option>
							-->
							</select>				
						</td>						
							
						<td align='center'>
							<select id="csvpctrows" name="csvpctrows">
								<option value="5" selected="selected">5%</option>
								<option value="10">10%</option>
								<option value="25">25%</option>
								<option value="50">50%</option>
								<option value="75">75%</option>
								<option value="100">100%</option>	
							</select>
						</td>
						
						<td align='left'>
							<input id="dte1" type="text" size=10 name="csv_date_start" value="" />
							<input id="dte2" type="text" size=10 name="csv_date_end" value="" /> 
						</td>
						<td align='center'>
							<input type="hidden" name="csvshowtable" value="gettable">
							<input id="showtable" type="button" value="Submit" />
							<!-- <input id="submit-csv" type="submit" name="CSV-table" value="Submit-csv" /> -->
						</td>
					</tr>
                </table>
           </fieldset>
        </form> 
        
        </td>
        <td colspan='2'>
			
        <form id="formcsvhist" name="csv_ch" action="edi_history_main.php" target="_blank" method="get">
           <fieldset style='float:left'>
			  <legend>Per Encounter</legend>
			  <table cols='2'> 
			        <tr>
						<td colspan='2'>
							Enter Encounter Number
						</td>
					</tr>
					<tr>
						<td>
							Encounter
						</td>
						<td>
							Submit
						</td>	
					</tr>
					<tr>
						<td>
							<input id="csvenctr" type="text" size=7 name="chenctr" value="" />
						</td>
						<td>
							<input id="showhistory" type="button" value="Submit" />
						</td>
					</tr>
			  </table>
			</fieldset>
		</form>
		        
		</td></tr> 
		</table>
		
        <div id='tblshow'></div>
        <div id='tbclmstat'></div>
        <div id='tbbatchclm'></div> 
        <div id='tbcodetxt'></div> 
        <div id='tbcsvhist'></div> 
     
    </div>
    
    <div id='erafiles'>
		<table cols=2>
		<tr>
		<td>
	 		<form name="view_835" action="edi_history_main.php" target="_blank" enctype="multipart/form-data" method="post">
			<fieldset style='float:left'>
				<legend>View an x12-835 ERA file:</legend>
				<label for="era_file">Filename:</label>
				<input id="era_file" type="file" size=20 name="fileUplEra"  />
				<input type="submit" name="fileERA" value="Submit-file" />	
			</fieldset>
			</form>	
		</td>
		<td>
		<form name="view_ra" action="edi_history_main.php" target="_blank" method="post">
		<fieldset style='float:left'>
		  <legend>RA for Patient, Encounter, or Trace:</legend>
			<label for="pid835">Patient ID:</label>
			<input type="text" size=10 name="pid835" value="" />	
			<input type="submit" name="subpid835" value="Submit-pid" /> <br />
			<label for="enctr835">Encounter:</label>
			<input type="text" size=10 name="enctr835" value="" />
			<input type="submit" name="subenctr835" value="Submit-enctr" /> <br />
			<label for="trace835">Check No:</label>
			<input type="text" size=10 name="trace835" value="" />
			<input type="submit" name="subtrace835" value="Submit-trace" />
		</fieldset>
		</form> 
		</td>
		</tr>
		</table>  
    </div>
    
	<div id="x12text" >
		<table cols='2'>
			<tr>
			  <td align='center'>
				<form name="view_claim" action="edi_history_main.php" target="_blank" method="post">
					<fieldset>
						<legend>View Batch Claim x12 text:</legend>
						<label for="enctr">Enter Encounter:</label>
						<input type="text" name="enctrbatch" size=10 value="" /> 
						<input type="submit" name="Batch-enctr" value="Submit-Batch-enctr" />
					</fieldset>
				</form>
			  </td>
			  <td align='center'>
				<form name="view_ansi" action="edi_history_main.php" target="_blank" method="post">
				<fieldset>
					<legend>View ERA x12 text</legend>
					<label for="enctrERA">Enter Encounter:</label>
					<input type="text" name="enctrEra" size=10 value="" />
					<input type="submit" name="eraText" value="Submit-enctr" />
				</fieldset>
				</form>
			  </td>
			</tr> 
			<tr>
			  <td align='center' colspan='2'>
				<form name="view_x12" action="edi_history_main.php" target="_blank" enctype="multipart/form-data" method="post">
				<fieldset>
					<legend>View local x12 file:</legend>
					<label for="x12file">Choose File:</label>
					<input id="x12file" type="file" name="fileUplx12" />
					<input type="submit" name="fileX12" value="Submit-file" />	
				</fieldset>
				</form>
			</td> 
		</table>
		<div id='txtclmstat'></div>
        <div id='txtbatchclm'></div> 
        <div id='txtera'></div> 
    
	</div> 
        
    <div id="edinotes">
		<table cols='2'>
			<tr>
				<td colspan='2'>View the <a href="<?php echo $web_root?>/Documentation/Readme_edihistory.html" target="_blank">README</a> file</td>
			</tr>
			<tr>
				<td>
					<form name="viewlog" action="edi_history_main.php" enctype="multipart/form-data" method="post">
					<fieldset><legend>Inspect the log</legend>
					<label for="logfile">View Log:</label>
			        <input id="logfile" type="button" value="Open" />
					<input id="logClear" type="button" value="Close" />
					<input id="logArchive" type="button" value="Archive" />
					</fieldset>
					</form>
				</td>
				<td><form name="viewnotes" action="edi_history_main.php" enctype="multipart/form-data" method="post">
					<fieldset><legend>Notes</legend>
					<label for="getnotes">Notes:</label>
					<input id="getnotes" type="button" value="Open" />
					<label for="savenotes">Save:</label>
					<input id="savenotes" type="button" value="Save" />
					<label for="closenotes">Close:</label>
					<input id="closenotes" type="button" value="Close" />
					</fieldset>
					</form>
				</td>
			</tr>
		</table>
        
		<div id='logshow'></div> 
		<div id='mynotes'></div>   

    </div>
   
</div> 
<!-- End tabs section -->
<!-- the jquery.dataTables.min.js possibly should be moved to library/js ? -->
<script src="<?php echo $web_root?>/library/js/jquery-1.7.2.min.js" type="text/javascript"></script> 
<script src="<?php echo $web_root?>/library/js/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></script> 
<script src="<?php echo $web_root?>/library/js/datatables/media/js/jquery.dataTables.min.js" type="text/javascript"></script> 

<script type="text/javascript">
    $(document).ready(function() {
        // activate tab interface
        $("#tabs").tabs();
        $("#tabs").tabs({
            select: function() {	
                //Reset all these text fields to their default
                $("input:text, input:file").val(function() { 
                    return this.defaultValue;
                    });
                }
        });
        // datepicker options
        $(function() {
            $( "#dte1" ).datepicker({ 
                dateFormat: "mm/dd/yy",
                onSelect: function(selected) {
					$("#dte2").datepicker("option","minDate", selected)
				}  
            });
            $( "#dte2" ).datepicker({
                dateFormat: "mm/dd/yy", 
                onSelect: function(selected) {
					$("#dte1").datepicker("option","maxDate", selected);
				}
            });
        });

        /*
         * functions for ajax and popups
         */
        $('#srvvals').ajaxError(function() {
            $(this).html( "Error retrieving values." );
        }); 
               
        $(function() {
            $.ajax({
                url: 'edi_history_main.php', 
                data: { srvinfo: 'yes' }, 
                dataType: 'json',
                success: function(rsp){ 
					$('#srvvals').data("mf", rsp.mfuploads); 
					$('#srvvals').html('');
				}
            }); 
        });
        $(function() {
            $.ajax({
                url: 'edi_history_main.php',
                data: { csvtbllist: 'yes' },
                dataType: 'json',
                success: function(data) {
                  var options = $('#csvselect').attr('options');
                  var optct = data.length;
                  if (optct) {
                    var options = [];
                    options.push("<option value='' selected='selected'>Choose from list</option>");
                    for (var i=0; i<optct; i++) {
                      options.push("<option value=" + data[i].fname + ">" + data[i].desc + "</option>");
                    }
                    $("#csvselect").html(options.join(''));
                  }
                }
            });
        });       
        // the process files script html output is requested and displayed, 
        // replacing the tab panel contents 
        // also, the 'success' event calls an array of functions
        $('#processfiles').click(function() {
            $.ajax({
                type: "POST",
                url: "edi_history_main.php", 
                data:  $('#process_new').serialize(),
                dataType: "html",
                success: [ 
                    function(data){ $("#pfresult").html(data); },
                    bindlinks('#pfresult', 'click', '.clmstatus', 'click', '#clmstat', 'Claim Status'),
                    bindlinks('#pfresult', 'click', '.btclm', 'click', '#batchclm', 'Batch Claim')
                ]
            });
        });         
   
        // list files selected in the multifile upload input      
        $('#upload_file').change(function(){
            $('#srvvar').html('');
            $('#pfresult').html('');
            var fmax = $('#srvvals').data("mf");
            var far = this.files;
            var fct = far.length;
            for(var i = 0; i < fct; i++) {
                if (i == fmax) $('#pfresult').append("<p>max file count reached - reload names below </p>");
                $('#pfresult').append('file: ' + far[i].name +'<br />');
            }            
        });      
        // submit files for sorting and storage -- accepted/rejected, already uploaded, etc
        // shows a popup window (idea is to allow comparison with upload files display)
        $('#upload_new').submit(function() {
            if (! window.focus) return true;
            window.open('', 'Uploads', 'height=400,width=600,left=300,top=100,menubar=yes,resizable=yes,scrollbars=yes');
            this.target='Uploads';
            return true;
        });
            
        $('#csvClear').click(function() {
			$("#tblshow").html('');
		});
		
        $('#logClear').click(function() {
			$("#logshow").html('');
		});	
		
		$('#logArchive').click(function() {
			$.ajax({
                type: "GET",
                url: "edi_history_main.php", 
                data: { archivelog: "yes" },
                dataType: "html",
                success: function(data){ 
					$("#logshow").html(''), 
					$("#logshow").html($.trim(data)); 
				}
			});
		}); 
			
        $('#logfile').click(function() { 
			$.ajax({
                type: "GET",
                url: "edi_history_main.php", 
                data: { showlog: "yes" },
                dataType: "html",
                success: function(data){ $("#logshow").html($.trim(data)); }
			});
		}); 
		      
		$('#getnotes').click(function() {
			$('#logshow').html('');
			$('#mynotes').html('');
			$.ajax({
                type:'GET',
                url: "edi_history_main.php",
                data: { getnotes: "yes"},
                dataType: "text",
                success: function(data){ 
					$('#mynotes').html("<H4>Notes:</H4>"
							+ "<textarea id='txtnotes',name='txtnotes',rows='10',cols='600',auotfocus='autofocus'></textarea>"
							+ "<p></p>"
							); 
					// necessary to trim the data since php from script has leading newlines (UTF-8 issue)
					$('#txtnotes').val($.trim(data));
				}
			});
		});	
		
		$('#savenotes').click(function() {
			var notetxt = $("#txtnotes").val();
			$.post("edi_history_main.php", { putnotes: "yes", tnotes: notetxt },
				function(data){ $('#mynotes').append(data); });
		});
		
		$('#closenotes').click(function() { 
			$('#mynotes').html('');
		});
			     
     }); 
/* ************ 
 *   end of document ready() jquery 
 * ************
 */
/* ************ 
 * called to bind links in ajax retrieved content for dialog display
 * .on( events [, selector] [, data], handler(eventObject) )
 */
    function bindlinks(dElem, dEvt, cClass, cEvt, cElem, mytitle){ 
         $(dElem).on(dEvt, cClass, cEvt, function(e) {
            e.preventDefault();
            $.get($(this).attr('href'), function(data){ $(cElem).html(data); })
            var statDialog = $(cElem).dialog({
                autoOpen: false,
                position: "center",
                resizable: true,
                buttons: [{ text: "Close", click: function() { $(this).dialog("close"); } }], 
                modal: false,
                title: mytitle, //$(this).attr('title'),
                height: 400,
                width: 'auto'
            });
            statDialog.dialog('open'); 
        });
    } 
    
   // the csv tables are displayed using jquery dataTables plugin
   // here, the 'success' action is to execute an array of functions 
   // calls the helper function bindlinks() which applies jquery .on method
	$('#showtable').click(function() {
		// verify a csv file is selected
		if ($('#csvselect').val() == '') {
			$("#tblshow").html('No table selected -- select one first');
			return false;
		}
		$.ajax({
			type:'POST',
			url: "edi_history_main.php", 
			data: $('#formcsvtables').serialize(), 
			dataType: "html",
			success: [ 
				function(data){ $("#tblshow").html($.trim(data)); },
				function(){
					$('#csvTable').dataTable({
						DisplayLength: 10,    
						bJQueryUI: true, 
						bScrollInfinite: true,
						bScrollCollapse: true,
						sScrollY: '240px',
						sScrollX: '90%',
						sScrollXInner: '100%'
					});
				},
				bindlinks('#tblshow', 'click', '.clmstatus', 'click', '#tbclmstat', 'Claim Status'),
				bindlinks('#tblshow', 'click', '.btclm', 'click', '#tbbatchclm', 'Batch Claim'),
				bindlinks('#tblshow', 'click', '.codeval', 'click', '#tbcodetxt', 'Code Text')				
			]              
		});
	}); 
	
	// csv encounter history
	$('#showhistory').click(function() {
		$('#tbcsvhist').html('');
		var chenctr = $('#chenctr').value;
		var encrecord = $('#tbcsvhist').dialog({
					buttons: [{ text: "Close", click: function() { $(this).dialog("close"); } }], 
					modal: false,
					title: "Encounter Record",
					height: 416,
					width: 'auto'
				});
		$.ajax({
			type: "GET",
			url: "edi_history_main.php", 
			data: $('#formcsvhist').serialize(), //{ csvenctr: chenctr },
			dataType: "html",
			success: [
				function(data){ $('#tbcsvhist').html($.trim(data)); },
				bindlinks('#tbcsvhist', 'click', '.clmstatus', 'click', '#tbclmstat', 'Claim Status'),
				bindlinks('#tbcsvhist', 'click', '.btclm', 'click', '#tbbatchclm', 'Batch Claim'),
				bindlinks('#tbcsvhist', 'click', '.codeval', 'click', '#tbcodetxt', 'Code Text'),
				encrecord.dialog('open')
			]				
		});
    });		
	                    
/* ************ 
 * end of javascript block
 */             
</script>     

</body>

</html>
