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
$smarty->assign('pagetitle', 'Import XML File');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>xmlimport.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>xmlimport.php</i>: Failed to select the database!</center>');


########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################
    
    
    
//
// File Upload
//
if(!isset($_POST['create']))
{    
    // Display HTML Page
    $smarty->display($config['Template'] . '/xmlimport.tpl');
}



########################################################################



//
// Upload / Create
//
elseif(isset($_POST['create']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>xmlimport.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>xmlimport.php</i>: Failed to select the database!</center>');

    // POST Values
    $post_system    = $_POST['system_type'];
    $post_xml_file  = $_POST['xml_file'];

    // Check empty
    if(empty($post_system))
    {
        die('<center><b>Error:</b> <i>xmlimport.php</i>: Required fields were left blank!</center>');
    }
    
    ####################################################################

    // Upload location
    $upload_dir     = "../tmp/";
    
    // Check for the TMP directory
    if(!file_exists($upload_dir))
    {
        die('<center><b>Error:</b> <i>xmlimport.php</i>: No \'tmp\' directory found!</center>');
    }
    
    
    $xml_filename   = $upload_dir . basename( $_FILES['xml_file']['name']);
    $file_type      = $_FILES['xml_file']['type'];
    
    // Make sure this is an XML File
    if($file_type != "text/xml")
    {
        die('<center><b>Error:</b> <i>xmlimport.php</i>: Invalid file type!  You must upload a valid XML file.</center>');
    }
    
    // Move uploaded XML File
    if(!move_uploaded_file($_FILES['xml_file']['tmp_name'], $xml_filename))
    {
        die('<b>Error:</b> Failed to upload XML file!');
    }
    
    
    ####################################################################
    
    //
    // Import correct System Type
    //
    require('../include/functions/xml.php');
    
    // GamePanelX Pro
    if($post_system == 'gpx')
    {
        $result_import  = gpx_xml_import($xml_filename);
        
        // Success
        if(preg_match("/^success\ \d+$/", $result_import))
        {
            // Get ID
            $arr_id = explode(' ', $result_import);
            $imp_id = $arr_id[1];
            
            header("Location: defaultservers.php?info=created&id=$imp_id");
            exit;
        }
        // Existing Server
        if(preg_match("/^existing\ \d+$/", $result_import))
        {
            // Get ID
            $arr_id = explode(' ', $result_import);
            $imp_id = $arr_id[1];
            
            die('<center><b>Error:</b> <i>xmlimport.php:</i> That server already has default settings.<br /><br />Please try again with a different server type or <a href="managesupportedserver.php?id=' . $imp_id . '">click here to edit/delete the current one</a>.</center>');
        }
        // Failure
        else die('<center><b>Error:</b> <i>xmlimport.php:</i> Failed to import the XML Document!</center>');
    }
    else die('Unknown control panel specified.');
}
