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
 *                   Instant Notifications functions
 *
 *********************************************************************** 
*/


// Add new Notification
function gpx_notify_add($typeid,$relid)
{
    $typeid = mysql_real_escape_string($typeid);
    $relid  = mysql_real_escape_string($relid);
    
    ####################################################################
    
    // Add this notification
    @mysql_query("INSERT INTO notify (typeid,relid,seen,date_added) VALUES('$typeid','$relid','N',NOW())");

    // Finish
    return true;
}

?>
