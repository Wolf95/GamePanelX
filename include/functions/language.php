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



/***********************************************************************
 * 
 *                     Language functions
 *
 *********************************************************************** 
*/

//
// Use logged-in user's set language
//
function gpx_language_get()
{ 
    $this_userid  = $_SESSION['gpx_userid'];
    $is_admin     = $_SESSION['gpx_isadmin'];
    
    // Administrators
    if($is_admin) $table = 'admins';
    else $table = 'clients';
    
    $result_lang  = @mysql_query("SELECT language FROM $table WHERE id = '$this_userid'") or die('<center><b>Error:</b> Failed to get your preferred language!</center>');
    
    while($row_lang = mysql_fetch_array($result_lang))
    {
        // Make sure language is lowercase
        $user_lang  = strtolower($row_lang['language']);
    }
    
    // If no language set, default to English
    if(empty($user_lang))
    {
        $user_lang = 'english';
    }

    // Require this specific language file
    $language_file = GPX_DOCROOT . '/languages/' . $user_lang . '.php';

    // Make sure file exists
    if(file_exists($language_file))
    {
        require_once($language_file);
    }
    else
    {
        die('<center><b>Error:</b> Failed to find the \'' . $user_lang . '\' language file!</center>');
    }


    // Return array of language variables
    return $lang;
}

?>
