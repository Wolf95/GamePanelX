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
// Cronjobs
//

########################################################################

require('config.php');
require(GPX_DOCROOT . '/include/functions/remote.php');

########################################################################

// If logged in, make sure user is an admin
if(isset($_SESSION['gpx_userid']) && !isset($_SESSION['gpx_isadmin']))
{
    die('<b>Error:</b> You are not authorized to view this page.');
}

########################################################################

// Loop through all Linux network servers and check their load averages
$result_servers = @mysql_query("SELECT id FROM network WHERE available = 'Y' AND physical = 'Y'") or die(mysql_error());

while($row_servers = mysql_fetch_array($result_servers))
{
    // This network ID
    $this_networkid = $row_servers['id'];
    
    // Check this load avg.
    gpx_remote_loadinfo($this_networkid);
    
    /*
    * Dont die, log it
    if(!gpx_remote_loadinfo($this_networkid))
    {
        die('<b>Error:</b> Failed to get load info');
    }
    */
}

?>
