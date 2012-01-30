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
// Add Domain Record
//
function gpx_dns_add($domain,$ip,$mx)
{
    $domain = mysql_real_escape_string($domain);
    $ip     = mysql_real_escape_string($ip);
    $mx     = mysql_real_escape_string($mx);
    
    ####################################################################
    
    // This user ID
    $this_userid  = $_SESSION['gpx_userid'];
    
    // Insert domain
    if(mysql_query("INSERT INTO domains (last_updated_by,date_added,last_updated,is_enabled,ip,mx,domain) VALUES('$this_userid',NOW(),NOW(),'Y','$ip','$mx','$domain')"))
    {
        return true;
    }
    else
    {
        return false;
    }
}










//
// Delete Domain Record
//
function gpx_dns_delete($domainid)
{
    $domainid = mysql_real_escape_string($domainid);
    
    ####################################################################
    
    // Get the domain name we're deleting
    $result_dm  = @mysql_query("SELECT domain FROM domains WHERE id = '$domainid'");
    
    while($row_dm = mysql_fetch_array($result_dm))
    {
        $this_domain = $row_dm['domain'];
    }

    ####################################################################
    
    //
    // First, make sure the file lines have been removed from /etc/named.conf, otherwise fail
    //
    $file = fopen('/etc/named.conf', "r") or die('<b>Error:</b> Failed to open the /etc/named.conf for checking');
    $regex_domain = preg_quote($this_domain);
    
    while(!feof($file))
    {
        $cur_line = fgets($file);
        
        // Check for the domain in the named.conf file
        if(preg_match("/$regex_domain/", $cur_line))
        {
            die('<b>Error:</b> The domain entry must be deleted out of /etc/named.conf before you can delete it from the control panel.');
        }
    }
    fclose($file);

    ####################################################################

    // Delete this domain
    @mysql_query("DELETE FROM domains WHERE id = '$domainid'");
    
    // Delete the zone file
    unlink(GPX_DOCROOT . '/domains/' . $this_domain . '.db');
    
    
    // Finish
    return true;
}














//
// Rebuild DNS Zone file
//
function gpx_dns_rebuild_zone($domainid)
{
    $domainid = mysql_real_escape_string($domainid);
    
    #################################################################
    
    // Get domain info
    $result_dns  = @mysql_query("SELECT ip,mx,domain FROM domains WHERE id = '$domainid'");
    
    while($row_dns  = mysql_fetch_array($result_dns))
    {
        $this_domain_ip   = $row_dns['ip'];
        $this_domain_mx   = $row_dns['mx'];
        $this_domain      = $row_dns['domain'];
    }
    
    #################################################################
    
    // Create serial number
    
    $serial_num = date("Ymds");
    
    
    // $serial_num = '2010092701';
    
    //
    // Begin Zone File
    //
    $zone_file  = '@       IN      SOA     ' . $this_domain . '. root.' . $this_domain . '. (' . " \n";
    $zone_file .= '  ' . $serial_num . " \n"; // Serial
    $zone_file .= '  10800 ' . " \n";          // Refresh
    $zone_file .= '  3600 ' . " \n";           // Retry
    $zone_file .= '  604800 ' . " \n";         // Expire
    $zone_file .= '  3600 )' . " \n\n";        // Minimum
    
    // Main A Record
    $zone_file .= $this_domain . '.   IN A   ' . $this_domain_ip . " \n";
    
    // NS Record
    $zone_file .= $this_domain . '.   IN NS   ns.' . $this_domain . '.' . " \n";

    // MX Record
    $zone_file .= $this_domain . '.   IN MX  10 ' . $this_domain_mx . ". \n\n";

    #################################################################
    
    //
    // Add all subdomains using this domain
    //
    $result_subs  = @mysql_query("SELECT subdomain,ip FROM servers WHERE domainid = '$domainid' AND subdomain != '' ORDER BY subdomain ASC");
    
    while($row_subs = mysql_fetch_array($result_subs))
    {
        $subdomain    = $row_subs['subdomain'];
        $subdomain_ip = $row_subs['ip'];
        
        // Add this subdomain
        $zone_file .= $subdomain . '.' . $this_domain . '.   IN A   ' . $subdomain_ip . " \n";
    }
    
    #################################################################
    
    //
    // Write the data to the zone file
    //
    $zone_filename  = GPX_DOCROOT . '/domains/' . $this_domain . '.db';
    
    if(!$fh = fopen($zone_filename, 'w'))
    {
        return '<b>Error:</b> Failed to open the domain file.  Check the write permissions on the "domains" directory.';
    }
    
    if(!fwrite($fh, $zone_file . "\n"))
    {
        return '<b>Error:</b> Failed to write to the domain file.  Check the write permissions on the "domains" directory.';
    }
    
    fclose($fh);
    
    #################################################################
    
    
    // Finish
    return 'success';
}











//
// Attempt to reload the domain zone
//
function gpx_dns_reload_zone($domainid)
{
    $domainid = mysql_real_escape_string($domainid);
    
    #################################################################
    
    // Get domain info
    $result_dns  = @mysql_query("SELECT domain FROM domains WHERE id = '$domainid'");
    
    while($row_dns  = mysql_fetch_array($result_dns))
    {
        $this_domain      = $row_dns['domain'];
    }
    
    #################################################################
    
    // Only if 'exec' function is available
    if(function_exists('exec'))
    {
        // Try and reload the zone
        exec("/usr/sbin/rndc reload $this_domain", $output_arr);
        $rndc_output  = $output_arr[0];

        // Success
        if(preg_match("/success/i", $rndc_output) || preg_match("/queued/i", $rndc_output))
        {
            return 'success';
        }
        // Check for failure
        elseif(preg_match("/fail/i", $rndc_output))
        {
            return '<center><b>Error:</b> Domain reload failed.  Check the syntax and try again.</center>';
        }
        elseif(empty($rndc_output))
        {
            return '<center><b>Error:</b> No output from Named.  Check that the webserver user has group access to the named group and that there is a domain entry for this in named.conf.</center>';
        }
        else
        {
            return '<center><b>Error:</b> Unknown error: ' . $rndc_output . '</center>';
        }
    }
    // Try the 'system' call
    elseif(function_exists('system'))
    {
        // Try and reload the zone
        system("/usr/sbin/rndc reload $this_domain", $output_arr);
        $rndc_output  = $output_arr[0];

        // Success
        if(preg_match("/success/i", $rndc_output) || preg_match("/queued/i", $rndc_output))
        {
            return 'success';
        }
        // Check for failure
        elseif(preg_match("/fail/i", $rndc_output))
        {
            return '<center><b>Error:</b> Domain reload failed.  Check the syntax and try again.</center>';
        }
        elseif(empty($rndc_output))
        {
            return '<center><b>Error:</b> No output from Named.  Check that the webserver user has group access to the named group and that there is a domain entry for this in named.conf.</center>';
        }
        else
        {
            return '<center><b>Error:</b> Unknown error.</center>';
        }
    }
    else
    {
        die('The exec/system functions are unavailable; unable to reload the DNS zone.');
    }
}






//
// All-in-one DNS reload
//
function gpx_dns_complete($domainid)
{
    $domainid = mysql_real_escape_string($domainid);
    
    #################################################################
    
    // Rebuild zone file
    if(!gpx_dns_rebuild_zone($domainid))
    {
        return false;
    }
    
    // Reload zone
    if(!gpx_dns_reload_zone($domainid))
    {
        return false;
    }
    
    
    // Finish
    return true;    
}
