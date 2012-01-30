<?php
//
// Steam Update
//
function gpx_remote_update_steam($serverid)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');

    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id = mysql_real_escape_string($serverid);
    
    ####################################################################
    
    // Get server info
    $query_server =  "SELECT 
                        servers.ip,
                        servers.port,
                        servers.type,
                        servers.server,
                        servers.executable,
                        clients.username,
                        cfg.steam_name,
                        cfg.setup_dir 
                      FROM servers 
                      LEFT JOIN clients ON 
                        servers.userid = clients.id 
                      LEFT JOIN cfg ON 
                        servers.server = cfg.short_name 
                      WHERE servers.id = '$safe_id'";

    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $server_ip        = $row_server['ip'];
        $server_port      = $row_server['port'];
        $server_type      = $row_server['type'];
        $server_name      = $row_server['server'];
        $server_exe       = $row_server['executable'];
        $server_username  = $row_server['username'];
        $cfg_steam_name   = $row_server['steam_name'];
        $cfg_setup_dir    = $row_server['setup_dir'];
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
    
    // Get steam update command
    require(GPX_DOCROOT . '/include/automagical/steam.php');
    $update_cmd = $setup['update_cmd'];

    // Convert any % variables into usable stuff
    $update_cmd = str_replace('%steam_name%', $cfg_steam_name, $update_cmd);
    $update_cmd = str_replace('%setup_dir%', $cfg_setup_dir, $update_cmd);


    // Run the SSH Test
    $ssh_cmd = '$HOME/scripts/UpdateServer -u ' . $server_username . ' -t ' . $server_type . ' -i ' . $server_ip . ' -p ' . $server_port . ' -o \'' . $update_cmd . '\'';
    if(!gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd))
    {
        die('<center><b>Error:</b> <i>remote.php:</i> Test connection failed!</center>');
    }
    else
    {
        return true;
    }
}

?>
