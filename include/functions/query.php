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
 *                     Game/Voice Server Query
 *
 *********************************************************************** 
*/


//
// Use GameQ to get server info
//
// Take a full array of server info (must have server name,ip and port), query 
// the respective servers, and return the current status in a new array.
//
function gpx_query($info)
{
    // Require GameQ library
    require_once(GPX_DOCROOT . '/include/query.php');

########################################################################


    // Get the default server query timeout from the `configuration` table
    $result_timout = @mysql_query("SELECT value FROM configuration WHERE setting='ServerQueryTimeout'") or die('<center><b>Error:</b> <i>query.php:</i> Failed to query the configuration table!</center>');

    while($row_timeout = mysql_fetch_array($result_timout))
    {
        $query_timeout = $row_timeout['value'];
    }


########################################################################

    // Run GameQ specifics
    $gq = new GameQ();
    $gq->addServers($info);

        
    // You can optionally specify some settings
    $gq->setOption('timeout', $query_timeout);


    // You can optionally specify some output filters,
    // these will be applied to the results obtained.
    $gq->setFilter('normalise');
    $gq->setFilter('sortplayers', 'gq_ping');

    // Send requests, and parse the data
    $results = $gq->requestData();
    
    /*
    echo '<pre>';
    var_dump($results);
    echo '</pre>';
    exit;
    */
    
########################################################################


    // Give the array back to the other page with online status
    $count_array = count($info) - 1;
    
    
    // Create new '$current' array for just current status info
    $current = array();
    
    
    
    // If there's more than 1 array, loop through all of them
    if($count_array >= 1)
    {
        for($i = 0; $i <= $count_array; $i++)
        {
            // If online is true, set status to online            
            if($results[$i]['gq_online'])
            {
                $current_status = 'online';
            }
            else
            {
                $current_status = 'offline';
            }
            
            // Add these values to the new '$current' array
            $current[$i]['current_address']     = $results[$i]['gq_address'];
            $current[$i]['current_port']        = $results[$i]['gq_port'];
            $current[$i]['current_status']      = $current_status;
            $current[$i]['current_numplayers']  = $results[$i]['gq_numplayers'];
            $current[$i]['current_maxplayers']  = $results[$i]['gq_maxplayers'];
        }
    }
    // Otherwise, just use the 1 array
    else
    {
        // If online is true, set status to online
        if($results[0]['gq_online'])
        {
            $current_status = 'online';
        }
        else
        {
            $current_status = 'offline';
        }
        
        // Operating System
        if($results[0]['os'] == 'l')
        {
            $current_os = 'Linux';
        }
        elseif($results[0]['os'] == 'w')
        {
            $current_os = 'Windows';
        }
        else
        {
            $current_os = 'Unknown';
        }
        
        // Add these values to the new '$current' array
        $current[0]['current_status']      = $current_status;
        $current[0]['current_address']     = $results[0]['gq_address'];
        $current[0]['current_port']        = $results[0]['gq_port'];
        $current[0]['current_hostname']    = $results[0]['gq_hostname'];
        $current[0]['current_mapname']     = $results[0]['gq_mapname'];
        $current[0]['current_maxplayers']  = $results[0]['gq_maxplayers'];
        $current[0]['current_numplayers']  = $results[0]['gq_numplayers'];
        $current[0]['current_mod']         = $results[0]['gq_mod'];
        
        // Extras
        $current[0]['current_os']          = $current_os;
        $current[0]['current_game_dir']    = $results[0]['game_dir'];
        $current[0]['current_mod']         = $results[0]['gq_mod'];
        $current[0]['current_has_pw']      = $results[0]['gq_password'];
        $current[0]['current_protocol']    = $results[0]['gq_prot'];
        $current[0]['current_num_bots']    = $results[0]['num_bots'];
        
        // Players
        $current[0]['current_players']     = $results[0]['players'];
    }


    // Return array of values
    return $current;

}








//
// Generic TCP query
// (For servers with no GameQ entry)
//
function gpx_query_generic($ip,$port)
{
    $gen_con = fsockopen($ip,$port,$errno,$errstr,12);
    
    // Failure
    if(!$gen_con)
    {
        return false;
    }
    // Success
    else
    {
        return true;
    }

    ####################################################################
}

?>
