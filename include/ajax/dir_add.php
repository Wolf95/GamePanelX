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

if(empty($_GET['id']))
{
    die('No server ID given');
}
if(!isset($_SESSION['file_prev']))
{
    $prev_dir = '';
}
else $prev_dir = $_SESSION['file_prev'];

$url_id       = mysql_real_escape_string($_GET['id']);
$url_dir_name = $_GET['d'];

if(empty($url_dir_name))
{
    die('No directory name provided');
}

########################################################################

require(GPX_DOCROOT.'/include/functions/filemanager.php');

$result_create  = gpx_file_create_dir($url_id,$prev_dir,$url_dir_name);

// Success
if($result_create == 'success')
{
    echo 'success';
}
// Error
else
{
    echo $result_create;
}


?>