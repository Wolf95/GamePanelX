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

class Clients
{
    function create($arr_details)
    {
        // POST Values
        $post_first_name    = mysql_real_escape_string($arr_details['first_name']);
        $post_middle_name   = mysql_real_escape_string($arr_details['middle_name']);
        $post_last_name     = mysql_real_escape_string($arr_details['last_name']);
        $post_company       = mysql_real_escape_string($arr_details['company']);
        $post_phone         = mysql_real_escape_string($arr_details['phone']);
        $post_email         = mysql_real_escape_string($arr_details['email']);
        $post_address1      = mysql_real_escape_string($arr_details['address1']);
        $post_address2      = mysql_real_escape_string($arr_details['address2']);
        $post_city          = mysql_real_escape_string($arr_details['city']);
        $post_state         = mysql_real_escape_string($arr_details['state']);
        $post_zip           = mysql_real_escape_string($arr_details['zip']);
        $post_country       = mysql_real_escape_string($arr_details['country']);
        $post_external_pid  = mysql_real_escape_string($arr_details['external_pid']);
        $post_cp_username   = mysql_real_escape_string($arr_details['cp_username']);
        $post_cp_password   = mysql_real_escape_string($arr_details['cp_password']);
        $post_server        = mysql_real_escape_string($arr_details['server']);
        $post_rcon_pass     = mysql_real_escape_string($arr_details['rcon_password']);
        $post_private_pass  = mysql_real_escape_string($arr_details['private_password']);
        $post_total_slots   = mysql_real_escape_string($arr_details['player_slots']);
        $post_game_private  = mysql_real_escape_string($arr_details['game_private']);
        $post_ext_serverid  = mysql_real_escape_string($arr_details['ext_serverid']);
        $post_ext_clientid  = mysql_real_escape_string($arr_details['ext_clientid']);
        
        // Defaults
        $status   = 'active';
        $language = 'english';
        $notes    = '';
        
        ####################################################################
        
        //
        // Create Client Account
        //
        require('../include/functions/accounting.php');
        if(!gpx_acct_create_client($post_cp_username,$post_cp_password,$status,$post_first_name,$post_middle_name,$post_last_name,$post_company,$post_phone,$post_email,$post_address1,$post_address2,$post_city,$post_state,$post_zip,$post_country,$language,$notes))
        {
            return 'ERROR: GamePanelX Pro API: Failed to create the client account.';
        }

        // Get the user ID just created
        $result_userid = @mysql_query("SELECT id FROM clients WHERE first_name = '$post_first_name' AND last_name = '$post_last_name' AND city = '$post_city' AND state = '$post_state' ORDER BY id DESC LIMIT 0,1");
        
        while($row_userid = mysql_fetch_array($result_userid))
        {
            $this_userid = $row_userid['id'];
        }
        
        
        
        ####################################################################
        
        //
        // If there's an external PID, create a Game/Voice server for it
        //
        if(!empty($post_ext_serverid))
        {
            require('servers.php');
            $Servers  = new Servers;
            
            // Create new server
            $create_result  = $Servers->create($this_userid,$post_server,$post_total_slots,$post_ext_serverid);

            if($create_result == 'success')
            {
                return 'success';
            }
            else
            {
                return $create_result;
            }
        }
        else
        {
            return 'ERROR: GamePanelX Pro API: No external billing product ID provided.';
        }
    }
    
    
    
    
    
    
    
    
    //
    // Suspend Client Account ($servers is a comma-separated list of servers to suspend for the client)
    //
    function suspend($clientid,$suspend_servers)
    {
        $clientid = mysql_real_escape_string($clientid);
        
        ####################################################################
        
        require(CTR_DOCROOT . '/include/functions/accounting.php');
        
        if(!gpx_acct_suspend($clientid))
        {
            die('ERROR: GamePanelX Pro API: Failed to suspend the client account.');
        }
        
        ####################################################################
        
        // Optionally suspend all game/voice servers for this client
        if($suspend_servers)
        {
            echo "Would suspend servers.";
            /*
            require(CTR_DOCROOT . '/include/functions/servers.php');
            
            if(!gpx_servers_suspend($clientid))
            {
                die('ERROR: Failed to suspend the client servers.');
            }
            */
        }
        
        
        return true;
    }
}
