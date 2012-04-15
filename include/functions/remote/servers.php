<?php
//
// Create a Remote Server
//
function gpx_remote_create_server($id,$start_server)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');

    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id = mysql_real_escape_string($id);
    
    
    // Get server info
    $query_server =  "SELECT 
                        servers.port,
                        servers.type,
                        servers.server,
                        servers.executable,
                        servers.working_dir,
                        clients.username,
                        network.ip,
                        network.physical,
                        network.parentid,
                        cfg.id AS cfgid,
                        cfg.pid_file 
                      FROM servers 
                      LEFT JOIN cfg ON 
                        servers.server = cfg.short_name 
                      LEFT JOIN clients ON 
                        servers.userid = clients.id 
                      LEFT JOIN network ON 
                        servers.networkid = network.id 
                      WHERE 
                        servers.id = '$safe_id'";

    $result_server = @mysql_query($query_server) or die('Remote: Failed to get server info');

    while($row_server = mysql_fetch_array($result_server))
    {
        $server_ip        = $row_server['ip'];
        $server_port      = $row_server['port'];
        $server_type      = $row_server['type'];
        $server_name      = $row_server['server'];
        $server_exe       = $row_server['executable'];
        $server_work_dir  = $row_server['working_dir'];
        $server_username  = $row_server['username'];
        $cfg_pid_file     = $row_server['pid_file'];
        $cfg_id           = $row_server['cfgid'];
        $is_physical      = $row_server['physical'];
        $phys_parentid    = $row_server['parentid'];
    }
    
    ####################################################################
    
    /*
    // Find out if this server's IP is a Physical Server or not
    $result_phys = @mysql_query("SELECT physical,parentid FROM network WHERE ip = '$server_ip'") or die('<center><b>Error:</b> <i>remote.php</i>: Failed to check if server is physical!</center>');
    
    while($row_phys = mysql_fetch_array($result_phys))
    {
        $is_physical    = $row_phys['physical'];
        $phys_parentid  = $row_phys['parentid'];
    }
    */
    
    ####################################################################
    
    
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
    

    // Get Network Server info
    $query_server =  "SELECT 
                        id AS networkid,
                        ip,
                        AES_DECRYPT(conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(conn_port, '$enc_key') AS conn_port 
                      FROM network 
                      $sql_where";
                      
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Network Server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $networkid  = $row_server['networkid'];
        $conn_ip    = $row_server['ip'];
        $conn_user  = $row_server['conn_user'];
        $conn_pass  = $row_server['conn_pass'];
        $conn_port  = $row_server['conn_port'];
    }
    
    ####################################################################
    
    //
    // Get default template for this game/voice server
    //
    $result_def   = @mysql_query("SELECT id FROM archives WHERE networkid = '$networkid' AND cfgid = '$cfg_id' AND is_default = '1' ORDER BY id DESC LIMIT 0,1") or die('Remote: Failed to get the default archive');
    $row_def      = mysql_fetch_row($result_def);
    $archive_id   = $row_def[0];
    
    if(empty($archive_id))
    {
        return 'Remote: There is no default archive for this server!';
    }
  
    ####################################################################

    
    //
    // Run the command via SSH2
    //
    
    
    // If true, start server
    if($start_server)
    {
        // Get parsed command-line
        require(GPX_DOCROOT . '/include/functions/cmd.php');
        $parsed_cmd_line = gpx_cmd_parse($safe_id);
        
        // PID File
        if(!empty($cfg_pid_file))
        {
            $pid_file_cmd = ' -P ' . $cfg_pid_file;
        }
        else
        {
            $pid_file_cmd = "";
        }
        
        // Working Directory
        if(!empty($server_work_dir))
        {
            $working_dir_cmd  = ' -w ' . $server_work_dir;
        }
        else
        {
            $working_dir_cmd  = "";
        }
        
        //
        // Full Command (send to /dev/null)
        //
        $ssh_cmd = '$HOME' . "/scripts/CreateServer -u $server_username -t $server_type -i $server_ip -p $server_port $pid_file_cmd $working_dir_cmd -x $archive_id -s yes -o '$parsed_cmd_line' >> /dev/null 2>&1 &";
        
        
        /*
         * OLD:
         * 
        // Use PID File for server restart
        if(!empty($cfg_pid_file))
        {
            $ssh_cmd = '$HOME' . "/scripts/CreateServer -u $server_username -t $server_type -i $server_ip -p $server_port -P $cfg_pid_file -x $template_hash -s yes -o '$parsed_cmd_line'";
        }
        else
        {
            $ssh_cmd = '$HOME' . "/scripts/CreateServer -u $server_username -t $server_type -i $server_ip -p $server_port -x $template_hash -s yes -o '$parsed_cmd_line'";
        }
        */
    }
    else
    {
        $ssh_cmd = '$HOME' . "/scripts/CreateServer -u $server_username -t $server_type -i $server_ip -p $server_port -x $archive_id";
    }
    
    ####################################################################
    
    // Run the command
    if(!gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd))
    {
        return 'Remote: Failed to create on the Remote Server';
    }
    else
    {
        return 'success';
    }
}







########################################################################







//
// Delete a Remote Server
//
function gpx_remote_delete_server($id)
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
    $safe_id = mysql_real_escape_string($id);
    
    
    // Get server info
    $query_server =  "SELECT 
                          servers.port,
                          servers.type,
                          clients.username,
                          network.ip,
                          network.physical,
                          network.parentid 
                      FROM servers 
                      LEFT JOIN clients ON 
                          servers.userid = clients.id 
                      LEFT JOIN network ON 
                          servers.networkid = network.id 
                      WHERE 
                          servers.id = '$safe_id'";

    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $server_ip        = $row_server['ip'];
        $server_port      = $row_server['port'];
        $server_type      = $row_server['type'];
        $server_username  = $row_server['username'];
        $is_physical      = $row_server['physical'];
        $phys_parentid    = $row_server['parentid'];
    }
    
    ####################################################################
    
    /*
    // Find out if this server's IP is a Physical Server or not
    $result_phys = @mysql_query("SELECT physical,parentid FROM network WHERE ip = '$server_ip'") or die('<center><b>Error:</b> <i>remote.php</i>: Failed to check if server is physical!</center>');
    
    while($row_phys = mysql_fetch_array($result_phys))
    {
        $is_physical    = $row_phys['physical'];
        $phys_parentid  = $row_phys['parentid'];
    }
    */
    
    ####################################################################
    
    
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
    

    // Get Network Server info
    $query_server =  "SELECT 
                        id AS networkid,
                        ip,
                        AES_DECRYPT(conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(conn_port, '$enc_key') AS conn_port 
                      FROM network 
                      $sql_where";
                      
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Network Server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $networkid  = $row_server['networkid'];
        $conn_ip    = $row_server['ip'];
        $conn_user  = $row_server['conn_user'];
        $conn_pass  = $row_server['conn_pass'];
        $conn_port  = $row_server['conn_port'];
    }
    
    ####################################################################
    
    
    //
    // Run the command via SSH2
    //
    $ssh_cmd = '$HOME' . "/scripts/DeleteServer -u $server_username -t $server_type -i $server_ip -p $server_port";

    if(!gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd))
    {
        die('<center><b>Error:</b> <i>remote.php:</i> Failed to delete on the Remote Server!</center>');
    }
    else
    {
        return true;
    }
}




########################################################################




//
// Check Server Creation Status
//
function gpx_remote_status_create_server($id)
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
                        servers.port,
                        servers.type,
                        clients.username,
                        network.ip,
                        network.physical,
                        network.parentid 
                      FROM servers 
                      LEFT JOIN clients ON 
                        servers.userid = clients.id 
                      LEFT JOIN network ON 
                        servers.networkid = network.id 
                      WHERE 
                        servers.id = '$safe_id'";

    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $server_ip        = $row_server['ip'];
        $server_port      = $row_server['port'];
        $server_type      = $row_server['type'];
        $server_username  = $row_server['username'];
        $is_physical      = $row_server['physical'];
        $phys_parentid    = $row_server['parentid'];
    }
    
    ####################################################################
    
    /*
    // Find out if this server's IP is a Physical Server or not
    $result_phys = @mysql_query("SELECT physical,parentid FROM network WHERE ip = '$server_ip'") or die('<center><b>Error:</b> <i>remote.php</i>: Failed to check if server is physical!</center>');
    
    while($row_phys = mysql_fetch_array($result_phys))
    {
        $is_physical    = $row_phys['physical'];
        $phys_parentid  = $row_phys['parentid'];
    }
    */
    
    ####################################################################
    
    
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
    

    // Get Network Server info
    $query_server =  "SELECT 
                        ip,
                        AES_DECRYPT(conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(conn_port, '$enc_key') AS conn_port 
                      FROM network 
                      $sql_where";
                      
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Network Server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip    = $row_server['ip'];
        $conn_user  = $row_server['conn_user'];
        $conn_pass  = $row_server['conn_pass'];
        $conn_port  = $row_server['conn_port'];
    }

    ####################################################################
    
    // Check empty
    if(empty($server_ip) || empty($server_port) || empty($server_type) || empty($server_username))
    {
        die('<center><b>Error:</b> <i>remote.php:</i> Required field(s) were left empty!</center>');
    }
    
    ####################################################################
    
    //
    // Run the command through SSH2
    //
    $ssh_cmd = '$HOME' . "/scripts/CheckCreateServerStatus -u $server_username -t $server_type -i $server_ip -p $server_port";
    
    if(!$ssh_return = @gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true))
    {
        return 'FAILURE: <i>remote.php:</i> Failed to check the Server Creation Status!</center>';
    }
    else
    {
        return trim($ssh_return);
    }
}













//
// Check Server Update Status
//
function gpx_remote_status_update_server($serverid)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Escape all given values
    $safe_id = mysql_real_escape_string($serverid);
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];

    ####################################################################
    
    // Get server info
    $query_server =  "SELECT 
                        servers.ip,
                        servers.port,
                        servers.type,
                        clients.username 
                      FROM servers 
                      LEFT JOIN clients ON 
                        servers.userid = clients.id 
                      WHERE servers.id = '$safe_id'";

    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $server_ip        = $row_server['ip'];
        $server_port      = $row_server['port'];
        $server_type      = $row_server['type'];
        $server_username  = $row_server['username'];
    }
    
    ####################################################################
    
    // Find out if this server's IP is a Physical Server or not
    $result_phys = @mysql_query("SELECT physical,parentid FROM network WHERE ip = '$server_ip'") or die('<center><b>Error:</b> <i>remote.php</i>: Failed to check if server is physical!</center>');
    
    while($row_phys = mysql_fetch_array($result_phys))
    {
        $is_physical    = $row_phys['physical'];
        $phys_parentid  = $row_phys['parentid'];
    }
    
    ####################################################################
    
    
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
    

    // Get Network Server info
    $query_server =  "SELECT 
                        ip,
                        AES_DECRYPT(conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(conn_port, '$enc_key') AS conn_port 
                      FROM network 
                      $sql_where";
                      
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Network Server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip    = $row_server['ip'];
        $conn_user  = $row_server['conn_user'];
        $conn_pass  = $row_server['conn_pass'];
        $conn_port  = $row_server['conn_port'];
    }

    ####################################################################
    
    // Check empty
    if(empty($server_ip) || empty($server_port) || empty($server_type) || empty($server_username))
    {
        die('<center><b>Error:</b> <i>remote.php:</i> Required field(s) were left empty!</center>');
    }
    
    ####################################################################
    
    //
    // Run the command through SSH2
    //
    $ssh_cmd = '$HOME' . "/scripts/CheckUpdateStatus -u $server_username -t $server_type -i $server_ip -p $server_port";
    
    if(!$ssh_return = @gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true))
    {
        return 'FAILURE: <i>remote.php:</i> Failed to check the Server Update Status!</center>';
    }
    else
    {
        return trim($ssh_return);
    }
}








//
// Check Steam Update Status
//
function gpx_remote_status_steam_update($serverid)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Escape all given values
    $safe_id = mysql_real_escape_string($serverid);
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];

    ####################################################################
    
    // Get server info
    $query_server =  "SELECT 
                        servers.port,
                        servers.type,
                        network.ip,
                        clients.username 
                      FROM servers 
                      LEFT JOIN clients ON 
                        servers.userid = clients.id 
                      LEFT JOIN network ON 
                        servers.networkid = network.id 
                      WHERE servers.id = '$safe_id'";

    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $server_ip        = $row_server['ip'];
        $server_port      = $row_server['port'];
        $server_type      = $row_server['type'];
        $server_username  = $row_server['username'];
    }
    
    ####################################################################
    
    // Find out if this server's IP is a Physical Server or not
    $result_phys = @mysql_query("SELECT physical,parentid FROM network WHERE ip = '$server_ip'") or die('<center><b>Error:</b> <i>remote.php</i>: Failed to check if server is physical!</center>');
    
    while($row_phys = mysql_fetch_array($result_phys))
    {
        $is_physical    = $row_phys['physical'];
        $phys_parentid  = $row_phys['parentid'];
    }
    
    ####################################################################
    
    
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
    

    // Get Network Server info
    $query_server =  "SELECT 
                        ip,
                        AES_DECRYPT(conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(conn_port, '$enc_key') AS conn_port 
                      FROM network 
                      $sql_where";
                      
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Network Server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip    = $row_server['ip'];
        $conn_user  = $row_server['conn_user'];
        $conn_pass  = $row_server['conn_pass'];
        $conn_port  = $row_server['conn_port'];
    }

    ####################################################################
    
    // Check empty
    if(empty($server_ip) || empty($server_port) || empty($server_type) || empty($server_username))
    {
        die('<center><b>Error:</b> <i>remote.php:</i> Required field(s) were left empty!</center>');
    }
    
    ####################################################################
    
    //
    // Run the command through SSH2
    //
    $srv_path = '$HOME/accounts/' . "$server_username/$server_type/$server_ip\\:$server_port/.gpxinstall.log";
    $ssh_cmd  = 'tail -n1 ' . $srv_path . ' | awk \'{print $1}\' | grep -vi semaphore';
    
    if(!$ssh_return = @gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true))
    {
        return 'FAILURE: <i>remote.php:</i> Failed to check the Server Update Status!</center>';
    }
    else
    {
        return trim($ssh_return);
    }
}



















//
// Move a Game/Voice Server locally
//
function gpx_remote_server_move_local($server_id,$new_userid,$new_ip,$new_port)
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

    // Get new userid's username
    $result_newuser = @mysql_query("SELECT username FROM clients WHERE id = '$new_userid'") or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get new username!</center>');
    
    while($row_newuser = mysql_fetch_array($result_newuser))
    {
        $new_username = $row_newuser['username'];
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
    
    // Move the server
    $ssh_cmd = '$HOME' . "/scripts/MoveServerLocal -u $server_username -t $server_type -i $server_ip -p $server_port -U $new_username -I $new_ip -P $new_port";
    if(!gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd))
    {
        die('<center><b>Error:</b> <i>remote.php:</i> Failed to stop the server!</center>');
    }
    else
    {
        return true;
    }
}















//
// View a server log
//
function gpx_remote_server_viewlog($server_id)
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
                          servers.type,
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
    }

    ####################################################################

    // Get new userid's username
    $result_newuser = @mysql_query("SELECT username FROM clients WHERE id = '$new_userid'") or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get new username!</center>');
    
    while($row_newuser = mysql_fetch_array($result_newuser))
    {
        $new_username = $row_newuser['username'];
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
    
    // Move the server
    $ssh_cmd = 'tail $HOME' . "/accounts/$server_username/$server_type/$server_ip\:$server_port/.gpxsrvlog -n50";
    
    $result_cmd = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true);
    
    return $result_cmd;
}


?>
