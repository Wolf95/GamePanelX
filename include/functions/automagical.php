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
 *             Automagical(c) Installer functions
 *
 *********************************************************************** 
*/

//
// Fetch game/voice supported server list
//
function gpx_automagical_list()
{
    require_once('../include/db.php');
    
    // Setup
    $postfields   = array();
    $gpx_url      = "http://automagical.gamepanelx.com";
    
    
    // Send info
    $postfields["action"]   = "serverlist";
    $postfields["license"]  = $config['license'];
    
    
    // Curl setup
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $gpx_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);

    // Get XML response from server
    $xml = simplexml_load_string($data);

    return $xml;
    
    $server = $xml->server;
}


// 
// Installation Commands
//
function gpx_automagical_cmd($gpxid)
{
    // Setup
    $postfields   = array();
    $gpx_url      = "http://automagical.gamepanelx.com";
    $gpx_license  = GPX_LICENSE;


    // Send info
    $postfields["action"]   = "getcmd";
    $postfields["license"]  = $gpx_license;
    $postfields["gpxid"]    = stripslashes($gpxid);
    
    
    // Curl setup
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $gpx_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);

    /*
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    */
}


?>
