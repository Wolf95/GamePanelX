<?php
/*
 * GamePanelX Pro
 * Complete Game and Voice server management tool
 * 
 * Copyright(C) 2009-2010 GamePanelX Pro.  All Rights Reserved. 
 * 
 * Email: support@gamepanelx.com
 * Website: http://www.gamepanelx.com
 * 
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.                                                         
 *                                                                      
 * You may not reverse  engineer, decompile, defeat  license  encryption
 * mechanisms, or  disassemble this software product or software product
 * license.  GamePanelX Pro may terminate this license if you don't
 * comply with any of the terms and conditions set forth in our end user
 * license agreement (EULA).  In such event,  licensee  agrees to return
 * licensor  or destroy  all copies of software  upon termination of the
 * license.                                                             
 *                                                                      
 * Please see the EULA file for the full End User License Agreement.    
*/

//
// Smarty
//
require '../libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->compile_dir  = '../admin/templates_c/';

// Required Files
require('../include/auth.php');
require('../include/config.php');

// Page Title
$smarty->assign('pagetitle', 'Export to XML');

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>xmlimport.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>xmlimport.php</i>: Failed to select the database!</center>');


########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

$this_date	=	date('r');

########################################################################

$url_id = mysql_real_escape_string($_GET['id']);

// Get server info for XML export
$result_info  = @mysql_query("SELECT 
                                * 
                              FROM cfg 
                              WHERE 
                                id = '$url_id'");

while($row_info = mysql_fetch_array($result_info))
{
    $srv_max_slots				=	$row_info['max_slots'];
    $srv_port							=	$row_info['port'];
    $srv_type							=	$row_info['type'];
    $srv_based_on					=	$row_info['based_on'];
    $srv_is_steam					=	$row_info['is_steam'];
    $srv_is_pb						=	$row_info['is_punkbuster'];
    $srv_description			=	stripslashes($row_info['description']);
    $srv_short_name				=	stripslashes($row_info['short_name']);
    $srv_query_name				=	stripslashes($row_info['query_name']);
    $srv_steam_name				=	stripslashes($row_info['steam_name']);
    $srv_long_name				=	stripslashes($row_info['long_name']);
    $srv_mod_name					=	stripslashes($row_info['mod_name']);
    $srv_nickname					= stripslashes($row_info['nickname']);
    $srv_style						=	stripslashes($row_info['style']);
    $srv_log_file					=	stripslashes($row_info['log_file']);
    $srv_reserved_ports		=	$row_info['reserved_ports'];
    $srv_tcp_ports				=	$row_info['tcp_ports'];
    $srv_udp_ports				=	$row_info['udp_ports'];
    $srv_executable				=	stripslashes($row_info['executable']);
    $srv_map							=	stripslashes($row_info['map']);
    $srv_setup_cmd				=	stripslashes($row_info['setup_cmd']);
    $srv_cmd_line 				=	stripslashes($row_info['cmd_line']);
    $srv_working_dir			=	stripslashes($row_info['working_dir']);
    $srv_setup_dir				=	stripslashes($row_info['setup_dir']);
    $srv_config_file			=	stripslashes($row_info['config_file']);
    $srv_pid_file					=	$row_info['pid_file'];
    $srv_cfg_def_text			=	stripslashes($row_info['cfg_default_text']);
    $srv_cfg_ip						=	stripslashes($row_info['cfg_ip']);
    $srv_cfg_port					=	stripslashes($row_info['cfg_port']);
    $srv_cfg_max_slots		=	stripslashes($row_info['cfg_max_slots']);
    $srv_cfg_map					= stripslashes($row_info['cfg_map']);
    $srv_cfg_password			=	stripslashes($row_info['cfg_password']);
    $srv_cfg_internet			=	stripslashes($row_info['cfg_internet']);
}


// Open XML file for writing
$fh = fopen(GPX_DOCROOT.'/tmp/'.$url_id.'.xml', 'w') or die("Unable to open 'tmp/$url_id.xml'.  Check that the webserver has write permissions on ".GPX_DOCROOT."/tmp/ and try again.");

$xml_file	=	"<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<server>
  <short_name>$srv_short_name</short_name>
  <long_name>$srv_long_name</long_name>
  <steam_name>$srv_steam_name</steam_name>
  <query_name>$srv_query_name</query_name>
  <mod_name>$srv_mod_name</mod_name>
  <style>$srv_style</style>
  <nickname>$srv_nickname</nickname>
  <description>$srv_description</description>
  <is_steam>$srv_is_steam</is_steam>
  <is_punkbuster>$srv_is_pb</is_punkbuster>
  <map>$srv_map</map>
  <setup_cmd>$srv_setup_cmd</setup_cmd>
  <cmd_line>$srv_cmd_line</cmd_line>
  <port>$srv_port</port>
  <max_slots>$srv_max_slots</max_slots>
  <type>$srv_type</type>
  <based_on>$srv_based_on</based_on>
  <log_file>$srv_log_file</log_file>
  <working_dir>$srv_working_dir</working_dir>
  <executable>$srv_executable</executable>  
  <setup_dir>$srv_setup_dir</setup_dir>
  <config_file>$srv_config_file</config_file>
  <pid_file>$srv_pid_file</pid_file>
  <cfg_ip>$srv_cfg_ip</cfg_ip>
  <cfg_port>$srv_cfg_port</cfg_port>
  <cfg_max_slots>$srv_cfg_max_slots</cfg_max_slots>
  <cfg_map>$srv_cfg_map</cfg_map>
  <cfg_password>$srv_cfg_password</cfg_password>
  <cfg_internet>$srv_cfg_internet</cfg_internet>  
  <reserved_ports>$srv_reserved_ports</reserved_ports>
  <tcp_ports>$srv_tcp_ports</tcp_ports>
  <udp_ports>$srv_udp_ports</udp_ports>
  <notes>Exported to XML via GamePanelX on $this_date</notes>
  
  <cmditems>";

// Write the beginning
fwrite($fh, $xml_file);


// Loop through command-line items for this server
$cmd_items  = '';
$result_cmd	=	@mysql_query("SELECT 
                            required,
                            client_edit,
                            simpleid,
                            name,
                            default_value,
                            description 
                          FROM cfg_items 
                          WHERE 
                            srvid = '$url_id'");

while($row_cmd  = mysql_fetch_array($result_cmd))
{
  $cmd_req      = $row_cmd['required'];
  $cmd_cl_ed    = $row_cmd['client_edit'];
  $cmd_simpleid = $row_cmd['simpleid'];
  $cmd_name     = stripslashes($row_cmd['name']);
  $cmd_value    = stripslashes($row_cmd['default_value']);
  $cmd_desc     = stripslashes($row_cmd['description']);
  
  $cmd_items .=	"
    <item>
      <required>$cmd_req</required>
      <client_edit>$cmd_cl_ed</client_edit>
      <simpleid>$cmd_simpleid</simpleid>
      <name>$cmd_name</name>
      <value>$cmd_value</value>
      <description>$cmd_desc</description>
    </item>";
}

// Write the Commands
fwrite($fh, $cmd_items);

// Add the ending tags
$finish_xml	=	"
  </cmditems>
</server>";
fwrite($fh, $finish_xml);


// Finish up
fclose($fh);

#echo "XML file: <a href=\"../tmp/$url_id.xml\">Download this XML file</a>";

// Set headers
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=$srv_short_name.xml");
header("Content-Type: application/zip");
header("Content-Transfer-Encoding: binary");

// Read the file from disk
readfile("../tmp/$url_id.xml");

// Delete the file
unlink("../tmp/$url_id.xml");

?>
