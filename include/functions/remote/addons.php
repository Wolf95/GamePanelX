<?php
//
// Install Addon
//
function gpx_remote_addon_install($server_id,$addonid)
{
    require(GPX_DOCROOT . '/include/db.php');
    require_once(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id        = mysql_real_escape_string($server_id);
    $safe_addonid   = mysql_real_escape_string($addonid);
    
    ####################################################################

    // Get information for this Game/Voice Server
    $query_gameinfo = "SELECT 
                          servers.ip,
                          servers.port,
                          servers.type,
                          clients.username 
                        FROM servers 
                        LEFT JOIN clients ON 
                          servers.userid = clients.id 
                        WHERE servers.id = '$safe_id'";
    $result_gameinfo = @mysql_query($query_gameinfo) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Game/Voice Server info!</center>');
    
    while($row_gameinfo = mysql_fetch_array($result_gameinfo))
    {
        $server_ip        = $row_gameinfo['ip'];
        $server_port      = $row_gameinfo['port'];
        $server_type      = $row_gameinfo['type'];
        $server_username  = $row_gameinfo['username'];
    }

    ####################################################################


    // Find out if this server's IP is a Physical Server or not
    $result_phys = @mysql_query("SELECT physical,parentid FROM network WHERE ip = '$server_ip'") or die('<center><b>Error:</b> <i>remote.php</i>: Failed to check if server is physical!</center>');
    
    while($row_phys = mysql_fetch_array($result_phys))
    {
        $is_physical    = $row_phys['physical'];
        $phys_parentid  = $row_phys['parentid'];
    }
    
    ###################################################################
    
    
    // IP is Physical, use this IP to get network info
    if($is_physical == 'Y')
    {
        $sql_where = "WHERE ip = '$server_ip'";
    }
    // IP is regular, use this IP's Parent ID for network info
    else
    {
        $sql_where = "WHERE id = '$phys_parentid'";
    }
    

    // Get server info
    $query_server =  "SELECT 
                        ip,
                        AES_DECRYPT(conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(conn_port, '$enc_key') AS conn_port 
                      FROM network 
                      $sql_where";
    
    // Get info for this server
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Network Server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip    = $row_server['ip'];
        $conn_user  = $row_server['conn_user'];
        $conn_pass  = $row_server['conn_pass'];
        $conn_port  = $row_server['conn_port'];
    }
    
    ####################################################################

    //
    // Get Addon info
    //
    $result_addon = @mysql_query("SELECT target,addon_hash FROM cfg_addons WHERE id = '$safe_addonid'");
    
    while($row_addon = mysql_fetch_array($result_addon))
    {
        $addon_target     = $row_addon['target'];
        $addon_hash       = $row_addon['addon_hash'];
    }

    ####################################################################

    //
    // Filename/Target cannot contain "..", no funny business
    //
    if(preg_match("/\.\./", $addon_filename))
    {
        return 'FAILURE: Invalid filename supplied.';
        exit;
    }
    elseif(preg_match("/\.\./", $addon_target))
    {
        return 'FAILURE: Invalid target supplied.';
        exit;
    }
    
    ####
    
    // Default to /
    if(empty($addon_target))
    {
        $addon_target = '/';
    }
    
    ####################################################################

    // Install addon
    $ssh_cmd = '$HOME/scripts/InstallAddon -u ' . $server_username . ' -t ' . $server_type . ' -i ' . $server_ip . ' -p ' . $server_port . ' -x ' . $addon_hash . ' -a ' . $addon_target;
    
    // Run on remote server
    if(!gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd))
    {
        return 'FAILURE: Failed to install the addon on the Remote Server';
    }
    else
    {
        return true;
    }
}















########################################################################










//
// Remove Addon
//
function gpx_remote_addon_remove($server_id,$addonid)
{
    require(GPX_DOCROOT . '/include/db.php');
    require_once(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id        = mysql_real_escape_string($server_id);
    $safe_addonid   = mysql_real_escape_string($addonid);   // (this is the CFG addon id)
    
    ####################################################################

    // Get information for this Game/Voice Server
    $query_gameinfo = "SELECT 
                          servers.ip,
                          servers.port,
                          servers.type,
                          clients.username 
                        FROM servers 
                        LEFT JOIN clients ON 
                          servers.userid = clients.id 
                        WHERE servers.id = '$safe_id'";
    $result_gameinfo = @mysql_query($query_gameinfo) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Game/Voice Server info!</center>');
    
    while($row_gameinfo = mysql_fetch_array($result_gameinfo))
    {
        $server_ip        = $row_gameinfo['ip'];
        $server_port      = $row_gameinfo['port'];
        $server_type      = $row_gameinfo['type'];
        $server_username  = $row_gameinfo['username'];
    }

    ####################################################################


    // Find out if this server's IP is a Physical Server or not
    $result_phys = @mysql_query("SELECT physical,parentid FROM network WHERE ip = '$server_ip'") or die('<center><b>Error:</b> <i>remote.php</i>: Failed to check if server is physical!</center>');
    
    while($row_phys = mysql_fetch_array($result_phys))
    {
        $is_physical    = $row_phys['physical'];
        $phys_parentid  = $row_phys['parentid'];
    }
    
    ###################################################################
    
    
    // IP is Physical, use this IP to get network info
    if($is_physical == 'Y')
    {
        $sql_where = "WHERE ip = '$server_ip'";
    }
    // IP is regular, use this IP's Parent ID for network info
    else
    {
        $sql_where = "WHERE id = '$phys_parentid'";
    }
    

    // Get server info
    $query_server =  "SELECT 
                        ip,
                        AES_DECRYPT(conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(conn_port, '$enc_key') AS conn_port 
                      FROM network 
                      $sql_where";
    
    // Get info for this server
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Network Server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip    = $row_server['ip'];
        $conn_user  = $row_server['conn_user'];
        $conn_pass  = $row_server['conn_pass'];
        $conn_port  = $row_server['conn_port'];
    }

    ####################################################################
    
    // Get CFG addon ID from the server addon ID
    $result_cfgid = @mysql_query("SELECT addonid FROM servers_addons WHERE id = '$addonid'");
    
    while($row_cfgid = mysql_fetch_array($result_cfgid))
    {
        $cfg_addonid = $row_cfgid['addonid'];
    }
    
    ####################################################################
    
    // Get list of directories to remove after all files have been removed
    $result_dirs = @mysql_query("SELECT addon_hash,target,remove_dirs FROM cfg_addons WHERE id = '$cfg_addonid'");
    
    while($row_dirs = mysql_fetch_array($result_dirs))
    {
        $addon_hash         = $row_dirs['addon_hash'];
        $addon_target       = $row_dirs['target'];
        $addon_remove_dirs  = $row_dirs['remove_dirs'];
    }
    
    // Make dirs separated by a space
    $arr_dirs = explode(",", $addon_remove_dirs);
    $new_dirs = "";
    
    foreach($arr_dirs as $single_dir)
    {
        $new_dirs .= $single_dir . ' ';
    }
    
    ####################################################################

    // Remove addon
    $ssh_cmd = '$HOME/scripts/RemoveAddon -u ' . $server_username . ' -t ' . $server_type . ' -i ' . $server_ip . ' -p ' . $server_port . ' -x ' . $addon_hash . ' -a ' . $addon_target . ' -d "' . $new_dirs . '"';

    // Run on remote server
    if(!gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd))
    {
        return 'FAILURE: Failed to remove the addon on the Remote Server';
    }
    else
    {
        return true;
    }
}












//
// Create a Supported Server Addon
//
function gpx_remote_supported_addon_create($id)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');

    // Escape all given values
    $safe_id      = mysql_real_escape_string($id); // `cfg_addons` id
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];

    ####################################################################
    
    // Function to generate a random addon hash
    function gpx_random_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
    {
        // Length of character list
        $chars_length = (strlen($chars) - 1);

        // Start our string
        $string = $chars{rand(0, $chars_length)};
       
        // Generate random string
        for ($i = 1; $i < $length; $i = strlen($string))
        {
            // Grab a random character from our list
            $r = $chars{rand(0, $chars_length)};
           
            // Make sure the same two characters don't appear next to each other
            if ($r != $string{$i - 1}) $string .=  $r;
        }
       
        // Return the string
        return $string;
    }
    
    ####################################################################
    
    // Create random filename hash for this addon
    $random_hash  = gpx_random_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890');

    // Update the database with the addon hash
    @mysql_query("UPDATE cfg_addons SET addon_hash = '$random_hash' WHERE id = '$safe_id'") or die('<center><b>Error:</b> <i>remote.php:</i> Failed to update the addon hash!</center>');
    
    ####################################################################
    
    // Get server info
    $query_server =  "SELECT 
                        cfg_addons.file_path,
                        network.ip,
                        AES_DECRYPT(network.conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(network.conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(network.conn_port, '$enc_key') AS conn_port 
                      FROM cfg_addons 
                      LEFT JOIN network ON 
                        cfg_addons.networkid = network.id 
                      WHERE cfg_addons.id = '$safe_id'";
    
    // Get info for this server
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get template info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip              = $row_server['ip'];
        $conn_user            = $row_server['conn_user'];
        $conn_pass            = $row_server['conn_pass'];
        $conn_port            = $row_server['conn_port'];
        $addon_file_path      = $row_server['file_path'];
    }

    ####################################################################
    
    // Check required
    if(empty($addon_file_path) || empty($random_hash))
    {
        die('<center><b>Error:</b> <i>remote.php</i>: Required values were left out.</center>');
    }
    
    ####################################################################
    
    //
    // Run the command through SSH2
    //
    $ssh_cmd = '$HOME' . "/scripts/CreateAddon -p $addon_file_path -i $random_hash";

    if(!gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd))
    {
        die('<center><b>Error:</b> <i>remote.php:</i> Failed to create on the Remote Server!</center>');
    }
    else
    {
        return true;
    }
}









//
// Check Addon creation status
//
function gpx_remote_status_addon_create($id)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Escape all given values
    $safe_id = mysql_real_escape_string($id);
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];

    ####################################################################
    
    // Get server info
    $query_server =  "SELECT 
                        network.ip,
                        AES_DECRYPT(network.conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(network.conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(network.conn_port, '$enc_key') AS conn_port,
                        cfg_addons.addon_hash 
                      FROM network 
                      LEFT JOIN cfg_addons ON 
                        network.id = cfg_addons.networkid 
                      WHERE cfg_addons.id = '$safe_id'";
    
    // Get info for this server
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get addon info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip              = $row_server['ip'];
        $conn_user            = $row_server['conn_user'];
        $conn_pass            = $row_server['conn_pass'];
        $conn_port            = $row_server['conn_port'];
        $addon_hash           = $row_server['addon_hash'];
    }

    ####################################################################
    
    // Check empty
    if(empty($addon_hash))
    {
        die('<center><b>Error:</b> <i>remote.php:</i> There is no hash for this addon!</center>');
    }
    
    ####################################################################
    
    //
    // Run the command through SSH2
    //
    $ssh_cmd = '$HOME' . "/scripts/CheckAddonCreationStatus -i $addon_hash";
    
    if(!$ssh_return = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true))
    {
        die('<center><b>Error:</b> <i>remote.php:</i> Failed to check the Addon Creation Status!</center>');
    }
    else
    {
        return trim($ssh_return);
    }
}










//
// Remove a Supported Server Addon
//
function gpx_remote_supported_addon_remove($id)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');

    // Escape all given values
    $safe_id  = mysql_real_escape_string($id); // `cfg_addons` id
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];

    ####################################################################
    
    // Get server info
    $query_server =  "SELECT 
                        cfg_addons.addon_hash,
                        network.ip,
                        AES_DECRYPT(network.conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(network.conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(network.conn_port, '$enc_key') AS conn_port 
                      FROM cfg_addons 
                      LEFT JOIN network ON 
                        cfg_addons.networkid = network.id 
                      WHERE cfg_addons.id = '$safe_id'";
    
    // Get info for this server
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get template info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip              = $row_server['ip'];
        $conn_user            = $row_server['conn_user'];
        $conn_pass            = $row_server['conn_pass'];
        $conn_port            = $row_server['conn_port'];
        $addon_hash           = $row_server['addon_hash'];
    }

    ####################################################################
    
    // Check required
    if(empty($addon_hash))
    {
        die('<center><b>Error:</b> <i>remote.php</i>: There is no hash set for this addon.</center>');
    }
    
    ####################################################################
    
    //
    // Run the command through SSH2
    //
    $ssh_cmd = '$HOME' . "/scripts/DeleteAddon -i $addon_hash";

    if(!gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd))
    {
        die('<center><b>Error:</b> <i>remote.php:</i> Failed to create on the Remote Server!</center>');
    }
    else
    {
        return true;
    }
}

?>
