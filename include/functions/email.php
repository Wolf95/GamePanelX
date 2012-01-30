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
 *                     Email Notify Functions
 *
 *********************************************************************** 
*/


//
// New Support Ticket Opened
//
function gpx_email_support_ticket_opened($ticketid)
{
    $ticketid = mysql_real_escape_string($ticketid);
    
    ####################################################################

    // Get info from ticket
    $query_tkt    = "SELECT 
                        clients.id AS userid,
                        clients.username,
                        clients.first_name,
                        clients.last_name,
                        tickets.subject,
                        tickets.ticket_text 
                     FROM tickets 
                     LEFT JOIN clients ON 
                        tickets.userid = clients.id 
                     WHERE tickets.id = '$ticketid'";
    
    $result_ticket  = @mysql_query($query_tkt);

    while($row_info = mysql_fetch_array($result_ticket))
    {
        $client_id          = $row_info['userid'];
        $client_username    = $row_info['username'];
        $client_first_name  = $row_info['first_name'];
        $client_last_name   = $row_info['last_name'];
        $ticket_subject     = $row_info['subject'];
        $ticket_text        = $row_info['ticket_text'];
    }
    
    ####################################################################
    
    // Get system email address
    $result_se  = @mysql_query("SELECT value FROM configuration WHERE setting='PrimaryEmail'");
    
    while($row_se = mysql_fetch_array($result_se))
    {
        $system_email = $row_se['value'];
    }

    ####################################################################
    
    // Loop through each admin and send an email to each
    $result_adm = @mysql_query("SELECT receive_email,email_address FROM admins WHERE status = 'active'");
    
    while($row_adm = mysql_fetch_array($result_adm))
    {
        $admin_receive  = $row_adm['receive_email'];
        $admin_email    = $row_adm['email_address'];
        
        // Only email if wanted
        if($admin_receive == 'Y')
        {
            $to       = $admin_email;
            $from     = $system_email;
            $subject  = 'GamePanelX: New Support Ticket opened';

            $headers  = "From: $from\r\n"; 
            $headers .= "Content-type: text/html\r\n"; 
$html_body = <<<EOF
This is an email notification from <b>GamePanelX</b><br /><br />

<b>$client_username</b> (ID #$client_id) has opened a new Support Ticket.<br /><br />
<b>Client Name:</b> $client_first_name $client_last_name<br />
<b>Subject:</b> $ticket_subject<br />
<b>Ticket Text:</b> <span style="font-family:Arial;font-size:10pt;color:#444">$ticket_text</span><br /><br />
EOF;
            // Send it
            @mail($to, $subject, $html_body, $headers);
        }
    }
    
    
    // Finish
    return true;
}























//
// Support Ticket Updated
//
function gpx_email_support_ticket_updated($ticketid)
{
    $ticketid = mysql_real_escape_string($ticketid);
    
    ####################################################################
    
    // Get info from ticket
    $query_tkt    = "SELECT 
                        clients.id AS userid,
                        clients.first_name,
                        clients.last_name,
                        tickets.ticket_text 
                     FROM tickets 
                     LEFT JOIN clients ON 
                        tickets.userid = clients.id 
                     WHERE tickets.id = '$ticketid'";
    
    $result_ticket  = @mysql_query($query_tkt);
    
    while($row_info = mysql_fetch_array($result_ticket))
    {
        $client_id          = $row_info['userid'];
        $client_first_name  = stripslashes($row_info['first_name']);
        $client_last_name   = stripslashes($row_info['last_name']);
        $ticket_text        = stripslashes($row_info['ticket_text']);
    }
    
    // Get latest reply
    $result_ltst  = @mysql_query("SELECT tickets.ticket_text FROM tickets WHERE threadid = '$ticketid' AND response_type = 'user'");
    $row_ltst     = mysql_fetch_row($result_ltst);
    $latest_reply = stripslashes($row_ltst[0]);
    
    ####################################################################
    
    // Get system email address
    $result_se  = @mysql_query("SELECT value FROM configuration WHERE setting='PrimaryEmail'");
    
    while($row_se = mysql_fetch_array($result_se))
    {
        $system_email = $row_se['value'];
    }

    ####################################################################
    
    // Loop through each admin and send an email to each
    $result_adm = @mysql_query("SELECT receive_email,email_address FROM admins WHERE status = 'active'");
    
    while($row_adm = mysql_fetch_array($result_adm))
    {
        $admin_receive  = $row_adm['receive_email'];
        $admin_email    = $row_adm['email_address'];
        
        // Only email if wanted
        if($admin_receive == 'Y')
        {
            $to       = $admin_email;
            $from     = $system_email;
            $subject  = 'GamePanelX: Support Ticket updated';

            $headers  = "From: $from\r\n"; 
            $headers .= "Content-type: text/html\r\n"; 
$html_body = <<<EOF
This is an email notification from <b>GamePanelX</b><br /><br />

<b>$client_username</b> (ID #$client_id) has updated Support Ticket ID #$ticketid<br /><br />
<b>Client Name:</b> $client_first_name $client_last_name<br />

<b>New Text:</b> <span style="font-family:Arial;font-size:10pt;color:#444">$latest_reply</span><br /><br />
<b>Original Text:</b> <span style="font-family:Arial;font-size:10pt;color:#444">$ticket_text</span><br /><br />
EOF;
            // Send it
            @mail($to, $subject, $html_body, $headers);
        }
    }
    
    
    // Finish
    return true;
}
