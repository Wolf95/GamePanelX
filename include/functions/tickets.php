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
 *                     Support Ticket functions
 *
 *********************************************************************** 
*/

//
// Update a Support Ticket
//
function gpx_ticket_update($ticket_id,$priority,$category,$status,$userid,$user_type,$response,$notes)
{
    // Safeify everything
    $safe_id        = mysql_real_escape_string(htmlspecialchars($ticket_id));
    $safe_priority  = mysql_real_escape_string(htmlspecialchars($priority));
    $safe_category  = mysql_real_escape_string(htmlspecialchars($category));
    $safe_status    = mysql_real_escape_string(htmlspecialchars($status));
    $safe_userid    = mysql_real_escape_string(htmlspecialchars($userid));
    $safe_user_type = mysql_real_escape_string(htmlspecialchars($user_type));
    $safe_response  = mysql_real_escape_string(htmlspecialchars($response));
    $safe_notes     = mysql_real_escape_string(htmlspecialchars($notes));
    
    // Update the original ticket
    if(!mysql_query("UPDATE tickets SET last_updated = NOW(),priority = '$safe_priority',category = '$safe_category',status = '$safe_status',internal_notes = '$safe_notes' WHERE id = '$safe_id'"))
    {
        return false;
    }
    
    // Add to the ticket if there's a response
    if(!empty($safe_response))
    {
        if(!mysql_query("INSERT INTO tickets (threadid,userid,response_userid,date_added,response_type,ticket_text) VALUES('$safe_id','$safe_userid','$safe_userid',NOW(),'$safe_user_type','$safe_response')"))
        {
            return false;
        }
    }
    
    return true;
}





//
// Update a Support Ticket (regular user)
//
function gpx_ticket_update_user($ticket_id,$category,$status,$userid,$response)
{
    // Safeify everything
    $safe_id        = mysql_real_escape_string(htmlspecialchars($ticket_id));
    $safe_priority  = mysql_real_escape_string(htmlspecialchars($priority));
    $safe_category  = mysql_real_escape_string(htmlspecialchars($category));
    $safe_status    = mysql_real_escape_string(htmlspecialchars($status));
    $safe_userid    = mysql_real_escape_string(htmlspecialchars($userid));
    $safe_response  = mysql_real_escape_string(htmlspecialchars($response));
    
    // Regular user
    $safe_user_type = 'user';
    
    // Update the original ticket
    if(!mysql_query("UPDATE tickets SET last_updated = NOW(),status = '$safe_status' WHERE id = '$safe_id'"))
    {
        return false;
    }
    
    // Add to the ticket if there's a response
    if(!empty($safe_response))
    {
        @mysql_query("INSERT INTO tickets (threadid,userid,response_userid,date_added,response_type,ticket_text) VALUES('$safe_id','$safe_userid','$safe_userid',NOW(),'$safe_user_type','$safe_response')");
        
        // Get the ticket ID just created
        $result_id  = @mysql_query("SELECT id FROM tickets WHERE threadid = '$safe_id' AND userid = '$safe_userid' AND response_userid = '$safe_userid' AND response_type = '$safe_user_type' ORDER BY id DESC LIMIT 0,1");
        
        while($row_id = mysql_fetch_array($result_id))
        {
            $this_ticketid  = $row_id['id'];
        }
        
        // Send email if needed
        require(GPX_DOCROOT . '/include/functions/email.php');
        gpx_email_support_ticket_updated($safe_id);
        
        // Add notification
        require(GPX_DOCROOT . '/include/functions/notifications.php');
        gpx_notify_add(6,$safe_id);
    }
    

    return true;
}

########################################################################



//
// Create a Support Ticket
//
function gpx_tickets_create($userid,$priority,$category,$subject,$ticket_text,$notes)
{
    // Safeify everything
    $safe_userid        = mysql_real_escape_string(htmlspecialchars($userid));
    $safe_priority      = mysql_real_escape_string(htmlspecialchars($priority));
    $safe_category      = mysql_real_escape_string(htmlspecialchars($category));
    $safe_subject       = mysql_real_escape_string(htmlspecialchars($subject));
    $safe_ticket_text   = mysql_real_escape_string(htmlspecialchars($ticket_text));
    $safe_notes         = mysql_real_escape_string(htmlspecialchars($notes));
    
    // Defaults
    $ticket_status        = 'open';
    $ticket_opened_by     = 'admin';
    $ticket_response_type = 'admin';
    
    if(!mysql_query("INSERT INTO tickets (userid,priority,category,date_added,last_updated,status,opened_by,response_type,subject,ticket_text,internal_notes) VALUES('$safe_userid','$safe_priority','$safe_category',NOW(),NOW(),'$ticket_status','$ticket_opened_by','$ticket_response_type','$safe_subject','$safe_ticket_text','$safe_notes')"))
    {
        return false;
    }
    else
    {
        return true;
    }
}




//
// Create a Support Ticket (regular user)
//
function gpx_tickets_create_user($userid,$priority,$category,$subject,$ticket_text)
{
    // Safeify everything
    $safe_userid        = mysql_real_escape_string(htmlspecialchars($userid));
    $safe_priority      = mysql_real_escape_string(htmlspecialchars($priority));
    $safe_category      = mysql_real_escape_string(htmlspecialchars($category));
    $safe_subject       = mysql_real_escape_string(htmlspecialchars($subject));
    $safe_ticket_text   = mysql_real_escape_string(htmlspecialchars($ticket_text));
    
    // Defaults
    $ticket_status        = 'open';
    $ticket_opened_by     = 'user';
    $ticket_response_type = 'user';
    
    ####################################################################
    
    //
    // Add the ticket
    //
    @mysql_query("INSERT INTO tickets (userid,priority,category,date_added,last_updated,status,opened_by,response_type,subject,ticket_text) VALUES('$safe_userid','$safe_priority','$safe_category',NOW(),NOW(),'$ticket_status','$ticket_opened_by','$ticket_response_type','$safe_subject','$safe_ticket_text')");
    
    // Get the ticket ID just created
    $result_id  = @mysql_query("SELECT id FROM tickets WHERE userid = '$safe_userid' AND category = '$safe_category' AND subject = '$safe_subject' AND ticket_text = '$safe_ticket_text' ORDER BY id DESC LIMIT 0,1");
    
    while($row_id = mysql_fetch_array($result_id))
    {
        $this_ticketid  = $row_id['id'];
    }
    
    ####################################################################
    
    // Send email if needed
    require(GPX_DOCROOT . '/include/functions/email.php');
    gpx_email_support_ticket_opened($this_ticketid);
    
    // Add notification
    require(GPX_DOCROOT . '/include/functions/notifications.php');
    gpx_notify_add(5,$this_ticketid);
        
    // Finish
    return true;
}


########################################################################



//
// Delete a Support Ticket
//
function gpx_tickets_delete($ticket_id)
{
    // Safeify everything
    $safe_ticketid = mysql_real_escape_string($ticket_id);
    
    // Delete the main Ticket and all associated tickets
    @mysql_query("DELETE FROM tickets WHERE id = '$safe_ticketid' OR threadid = '$safe_ticketid'") or die('Failed to delete the ticket');
    
    // Delete notifications for this ticket
    @mysql_query("DELETE FROM notify WHERE (typeid = '5' OR typeid = '6') AND relid = '$safe_ticketid'") or die('Failed to delete notifications');
    
    return true;
}
?>
