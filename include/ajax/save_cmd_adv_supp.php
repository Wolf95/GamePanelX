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

// Check logged-in
if(!isset($_SESSION['gpx_username']) || !isset($_SESSION['gpx_isadmin']) || $_SESSION['gpx_isadmin'] != 1)
{
    die('<center><b>Error:</b> You must be logged-in as an administrator to view this page.</center>');
}
$this_userid  = $_SESSION['gpx_userid'];

// URL ID
if(empty($_GET['id']))
{
    die('No server ID provided!');
}

$url_id = mysql_real_escape_string($_GET['id']);

########################################################################

//
// Advanced Config Items (cfg_x id's)
//


// Run through cfg_x items
foreach($_GET as $cfg => $cfg_val)
{
    // Current cfg_x items only
    if(preg_match("/^cfg_\d+$/", $cfg))
    {
        // Remove "cfg_", to get a valid ItemID
        $cfg        = str_replace('cfg_', '', $cfg);
        $cfg_cl_ed  = $_GET['cfgcled_'.$cfg];
        
        // Client-Editable
        if($cfg_cl_ed == '1')
        {
            $cfg_cl_ed  = 'Y';
        }
        else $cfg_cl_ed = 'N';
        
        // Update this ID
        if(is_numeric($cfg))
        {
            @mysql_query("UPDATE cfg_items SET client_edit = '$cfg_cl_ed',default_value = '$cfg_val' WHERE id = '$cfg'") or die('Failed to update config id '.$cfg);
        }
    }   
    
    
    // Add new config items
    //
    // - Key id: addcfg_x
    // - Value id: addcfgval_x
    elseif(preg_match("/^addcfg_\d+$/", $cfg))
    {
        // Remove "addcfg_", to get a valid ItemID
        $cfg  = str_replace('addcfg_', '', $cfg);
        
        if(is_numeric($cfg))
        {
            $this_key   = $_GET['addcfg_'.$cfg];
            $this_val   = $_GET['addcfgval_'.$cfg];
            $this_type  = $_GET['addcfgtype_'.$cfg];
            
            // Make sure IP is empty; it's value will never be used
            if($this_type == 1)
            {
                $this_val = '';
            }
            
            // Only normal simpleid's
            if($this_type > 4)
            {
                die('Invalid type entered; please try again');
            }
            
            // Must have a name
            if(!empty($this_key))
            {
                // Must have a value, unless it's simpleid 1 (IP Address)
                #if(!empty($this_val) || $this_type == 1)
                #{
                    $this_key   = mysql_real_escape_string($this_key);
                    $this_val   = mysql_real_escape_string($this_val);
                    $this_type  = mysql_real_escape_string($this_type);
                    
                    // Insert global row for this new item
                    #echo "INSERT INTO cfg_items (srvid,usr_def,simpleid,cmd_line,name,default_value) VALUES('$url_id','1','$this_type','1','$this_key','$this_val')";
                    @mysql_query("INSERT INTO cfg_items (srvid,usr_def,simpleid,cmd_line,name,default_value) VALUES('$url_id','1','$this_type','1','$this_key','$this_val')") or die('Failed to insert cfg item: '.$this_key);
                #}
            }
        }
    }
}

########################################################################

// Output
echo 'success';

?>
