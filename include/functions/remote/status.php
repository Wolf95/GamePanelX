<?php
//
// Restart a Game/Voice Server
//
function gpx_remote_server_restart($server_id,$unsuspend=false)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id = mysql_real_escape_string($server_id);
    
    ####################################################################

    // Get information for this Game/Voice Server
    $query_gameinfo = "SELECT 
                          servers.port,
                          servers.max_slots,
                          servers.logging,
                          servers.type,
                          servers.server,
                          servers.log_file,
                          servers.map,
                          servers.executable,
                          servers.working_dir,
                          servers.cmd_line,
                          clients.id AS userid,
                          clients.username,
                          cfg.pid_file,
                          network.ip 
                        FROM servers 
                        LEFT JOIN clients ON 
                          servers.userid = clients.id 
                        LEFT JOIN cfg ON 
                          servers.server = cfg.short_name 
                        LEFT JOIN network ON 
                          servers.networkid = network.id 
                        WHERE servers.id = '$safe_id'";
    
    $result_gameinfo = @mysql_query($query_gameinfo) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Game/Voice Server info!</center>');
    
    while($row_gameinfo = mysql_fetch_array($result_gameinfo))
    {
        $server_ip        = $row_gameinfo['ip'];
        $server_port      = $row_gameinfo['port'];
        $server_max_slots = $row_gameinfo['max_slots'];
        $server_logging   = $row_gameinfo['logging'];
        $server_type      = $row_gameinfo['type'];
        $server_name      = $row_gameinfo['server'];
        $server_log_file  = $row_gameinfo['log_file'];
        $server_map       = $row_gameinfo['map'];
        $server_exe       = $row_gameinfo['executable'];
        $server_pid_file  = $row_gameinfo['pid_file'];
        $server_work_dir  = $row_gameinfo['working_dir'];
        $server_cmd_line  = $row_gameinfo['cmd_line'];
        $server_username  = $row_gameinfo['username'];
        $server_userid    = $row_gameinfo['userid'];
    }
    
    /*
    * DEPRECATED - Not required anymore since cmd-line isnt raw anymore and it's saved as normal in the Startup Editor
    *
    // Get the full, parsed command-line for this server
    require(GPX_DOCROOT . '/include/functions/cmd.php');
    $full_cmd_line = gpx_cmd_parse($safe_id);
    */
    
    ###################################################################
    
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

    // Working directory
    if(!empty($server_work_dir))
    {
        $opt_working_dir  = "-w $server_work_dir";
    }
    else
    {
        $opt_working_dir  = "";
    }
    
    // PID File - Use if this server needs one
    if(!empty($server_pid_file))
    {
        $opt_pid_file     = "-P $server_pid_file";
    }
    else
    {
        $opt_pid_file     = "";
    }

    ####################################################################
    
    // Optionally UN-suspend the server
    if($unsuspend) $unsuspend_cmd  = '-s yes';
    else $unsuspend_cmd  = '';
    
    // Server Logging
    if($server_logging) $log_cmd  = '-l yes';
    else $log_cmd = '';
    
    ####################################################################
    
    // Log this action (1 restart, 2 stop)
    require(GPX_DOCROOT.'/include/class/log.php');
    $Log = new Log;
    $Log->addlog('1',$server_userid,$safe_id);
    
    ####################################################################
    
    //
    // Restart the server
    //
    $ssh_cmd  = '$HOME' . "/scripts/Restart -u $server_username -t $server_type -i $server_ip -p $server_port $opt_pid_file $opt_working_dir $unsuspend_cmd $log_cmd -o '$server_cmd_line'";
    $response = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true);
    $response = trim($response);
    
    // Response may be 'success' or 'success success' since it may have to stop THEN restart a running server
    if($response == 'success' || $response == 'success success' )
    {
        return 'success';
    }
    else
    {
        //die('<center><b>Error:</b> <i>remote.php:</i> Failed to restart the server!</center>');
        return $response;
    }
}














//
// Stop a Game/Voice Server
//
function gpx_remote_server_stop($server_id,$suspend=false)
{
    // For API Usage
    if(!defined('GPX_DOCROOT'))
    {
        define('GPX_DOCROOT', '../');
    }

    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id = mysql_real_escape_string($server_id);
    
    ####################################################################

    // Get information for this Game/Voice Server
    $query_gameinfo = "SELECT 
                          servers.port,
                          servers.type,
                          clients.id AS userid,
                          clients.username,
                          network.ip 
                        FROM servers 
                        LEFT JOIN clients ON 
                          servers.userid = clients.id 
                        LEFT JOIN network ON 
                          servers.networkid = network.id 
                        WHERE servers.id = '$safe_id'";
    
    $result_gameinfo = @mysql_query($query_gameinfo) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Game/Voice Server info!</center>');
    
    while($row_gameinfo = mysql_fetch_array($result_gameinfo))
    {
        $server_ip        = $row_gameinfo['ip'];
        $server_port      = $row_gameinfo['port'];
        $server_type      = $row_gameinfo['type'];
        $server_username  = $row_gameinfo['username'];
        $server_userid    = $row_gameinfo['userid'];
    }

    ###################################################################
    
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
    
    // Optionally suspend the server
    if($suspend)
    {
        $suspend_cmd  = ' -s yes ';
    }
    else
    {
        $suspend_cmd  = "";
    }
    
    // Log this action (1 restart, 2 stop)
    require(GPX_DOCROOT.'/include/class/log.php');
    $Log = new Log;
    $Log->addlog('2',$server_userid,$safe_id);
    
    
    // Stop the server
    $ssh_cmd  = '$HOME' . "/scripts/Stop -u $server_username -t $server_type -i $server_ip -p $server_port $suspend_cmd";
    $response = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true);
    $response = trim($response);
    
    if($response == 'success')
    {
        return 'success';
    }
    else
    {
        // die('<center><b>Error:</b> <i>remote.php:</i> Failed to stop the server!</center>');
        return $response;
    }
}








//
// Update a Game/Voice Server
//
function gpx_remote_server_steamupdate($server_id)
{
    // For API Usage
    if(!defined('GPX_DOCROOT'))
    {
        define('GPX_DOCROOT', '../');
    }
    
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id = mysql_real_escape_string($server_id);
    
    ####################################################################

    // Get information for this Game/Voice Server
    $query_gameinfo = "SELECT 
                          servers.port,
                          servers.type,
                          clients.id AS userid,
                          clients.username,
                          network.ip,
                          cfg.steam_name 
                        FROM servers 
                        LEFT JOIN clients ON 
                          servers.userid = clients.id 
                        LEFT JOIN network ON 
                          servers.networkid = network.id 
                        LEFT JOIN cfg ON 
                          servers.server = cfg.short_name 
                        WHERE servers.id = '$safe_id'";
    
    $result_gameinfo = @mysql_query($query_gameinfo) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Game/Voice Server info!</center>');
    
    while($row_gameinfo = mysql_fetch_array($result_gameinfo))
    {
        $server_ip        = $row_gameinfo['ip'];
        $server_port      = $row_gameinfo['port'];
        $server_type      = $row_gameinfo['type'];
        $server_username  = $row_gameinfo['username'];
        $server_userid    = $row_gameinfo['userid'];
        $server_steam_mod = $row_gameinfo['steam_name'];
    }
    
    // Get primary admin email
    $result_em    = @mysql_query("SELECT value FROM configuration WHERE setting = 'PrimaryEmail'");
    $row_em       = mysql_fetch_row($result_em);
    $admin_email  = $row_em[0];
    
    ###################################################################
    
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
    
    if(empty($server_steam_mod))
    {
        die('No steam name found!  Is this a steam game?  Exiting.');
    }
    
    /*
    // Log this action (1 restart, 2 stop)
    require(GPX_DOCROOT.'/include/class/log.php');
    $Log = new Log;
    $Log->addlog('2',$server_userid,$safe_id);
    */
    #$server_dir = '$HOME/accounts/' . $server_username . '/game/' . $server_ip . '\:' . $server_port;
    
    // Update the server
    $install_cmd  = '$HOME' . "/scripts/SteamInstall -m '$server_steam_mod' -e '$admin_email'";
    $ssh_cmd      = '$HOME/scripts/UpdateServer  ' . "-u $server_username -t game -i $server_ip -p $server_port -o \"$install_cmd\"";
    
    $response     = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true);
    
    if($response == 'success')
    {
        return 'success';
    }
    else
    {
        return $response;
    }
}

?>
