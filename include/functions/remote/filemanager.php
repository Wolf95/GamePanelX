<?php
//
// File Manager: Get remote file list
//
function gpx_remote_file_list($server_id,$server_dir)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id = mysql_real_escape_string($server_id);
    
    // Remove ending slashes from dir
    $server_dir = preg_replace("/\/+$/", '/', $server_dir);
    
    // Strip ".." from this
    $server_dir = preg_replace("/\.\./", '', $server_dir);
    
    ####################################################################

    // Get information for this Game/Voice Server
    $query_gameinfo = "SELECT 
                          servers.port,
                          servers.type,
                          clients.username,
                          network.physical,
                          network.parentid,
                          network.ip
                        FROM servers 
                        LEFT JOIN clients ON 
                          servers.userid = clients.id 
                        LEFT JOIN network ON 
                          servers.networkid = network.id 
                        WHERE 
                          servers.id = '$safe_id'";
    
    $result_gameinfo = @mysql_query($query_gameinfo) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Game/Voice Server info!</center>');
    
    while($row_gameinfo = mysql_fetch_array($result_gameinfo))
    {
        $server_ip        = $row_gameinfo['ip'];
        $server_port      = $row_gameinfo['port'];
        $server_type      = $row_gameinfo['type'];
        $server_username  = $row_gameinfo['username'];
        $is_physical      = $row_gameinfo['physical'];
        $phys_parentid    = $row_gameinfo['parentid'];
    }

    ####################################################################

    /*
    * Deprecated in favor of using the above query
    *
    // Find out if this server's IP is a Physical Server or not
    $result_phys = @mysql_query("SELECT physical,parentid FROM network WHERE ip = '$server_ip'") or die('<center><b>Error:</b> <i>remote.php</i>: Failed to check if server is physical!</center>');
    
    while($row_phys = mysql_fetch_array($result_phys))
    {
        $is_physical    = $row_phys['physical'];
        $phys_parentid  = $row_phys['parentid'];
    }
    */
    
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
    
    // User's game directory
    $game_dir = "accounts/$server_username/$server_type/$server_ip:$server_port/";
    
    if(!empty($server_dir))
    {
        $list_dir = $game_dir . $server_dir;
    }
    else
    {
        $list_dir = $game_dir;
    }
    
    // Get directory contents via SFTP
    $file_list  = gpx_sftp($conn_ip,$conn_port,$conn_user,$conn_pass,$list_dir);
    
    return $file_list;
    
    /*
    // User's game directory
    $game_dir = '$HOME' . "/accounts/$server_username/$server_type/$server_ip\:$server_port/";
    
    if(!empty($server_dir))
    {
        $list_dir = $game_dir . $server_dir;
    }
    else
    {
        $list_dir = $game_dir;
    }
    
    
    // Get remote file list
    $ssh_cmd = 'ls -lpX ' . $list_dir . ' | awk \'{print $1,$5,$6,$7,$8,$9}\' | grep -v total ; echo success';
    
    #echo "SSH CMD: $ssh_cmd<br>";
    
    if(!$file_list = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true))
    {
        return false;
    }
    else
    {
        return $file_list;
    }
    */
}






// Same as above, but specific for Network Servers, listing a home dir and not a gameserver root
function gpx_remote_file_list_net($network_id,$server_dir)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id = mysql_real_escape_string($network_id);
    
    // Remove ending slashes from dir
    $server_dir = preg_replace("/\/+$/", '/', $server_dir);
    
    // Strip ".." from this
    $server_dir = preg_replace("/\.\./", '', $server_dir);
    
    ####################################################################
    
    // Get information for this Network Server
    $query_netinfo = "SELECT 
                          physical,
                          parentid,
                          ip 
                        FROM network 
                        WHERE 
                          id = '$safe_id'";
    
    $result_netinfo = @mysql_query($query_netinfo) or die('Failed to get Network Server info');
    
    while($row_netinfo = mysql_fetch_array($result_netinfo))
    {
        $server_ip        = $row_netinfo['ip'];
        $is_physical      = $row_netinfo['physical'];
        $phys_parentid    = $row_netinfo['parentid'];
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
    
    // Home dir listing
    if(!empty($server_dir))
    {
        $list_dir = './' . $server_dir;
    }
    else
    {
        $list_dir = './';
    }
    
    // Get directory contents via SFTP
    $file_list  = gpx_sftp($conn_ip,$conn_port,$conn_user,$conn_pass,$list_dir);
    
    return $file_list;
    
    /*
    // Get remote file list
    $ssh_cmd = 'ls -lpX ' . $list_dir . ' | awk \'{print $1,$5,$6,$7,$8,$9}\' | grep -v total ; echo success';
    
    #echo "SSH CMD: $ssh_cmd<br>";
    
    if(!$file_list = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true))
    {
        return false;
    }
    else
    {
        return $file_list;
    }
    */
}












//
// File Manager: Create remote directory
//
function gpx_remote_file_create_dir($server_id,$server_dir,$dir_name)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id        = mysql_real_escape_string($server_id);
    $safe_prev_dir  = mysql_real_escape_string($server_dir);
    $safe_new_dir   = mysql_real_escape_string($dir_name);
    
    ####################################################################

    // Get information for this Game/Voice Server
    $query_gameinfo = "SELECT 
                          servers.port,
                          servers.type,
                          clients.username,
                          network.physical,
                          network.parentid,
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
        $is_physical      = $row_gameinfo['physical'];
        $phys_parentid    = $row_gameinfo['parentid'];
    }

    ####################################################################

    /*
    * Deprecated in favor of using above query with a join
    *
    // Find out if this server's IP is a Physical Server or not
    $result_phys = @mysql_query("SELECT physical,parentid FROM network WHERE ip = '$server_ip'") or die('<center><b>Error:</b> <i>remote.php</i>: Failed to check if server is physical!</center>');
    
    while($row_phys = mysql_fetch_array($result_phys))
    {
        $is_physical    = $row_phys['physical'];
        $phys_parentid  = $row_phys['parentid'];
    }
    */
    
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

    // New Directory
    $create_dir = $safe_prev_dir . '/' . $safe_new_dir;
    
    
    // Make sure NO ".." directories
    if(preg_match("/\.\./", $create_dir))
    {
        return 'FAILURE: Invalid directory path';
        exit;
    }
    
    
    // Create Remote Directory
    $ssh_cmd = 'mkdir -p $HOME/accounts/' . $server_username . '/' . $server_type . '/' . $server_ip . '\:' . $server_port . '/' . $create_dir;
    
    #echo "SSH CMD: $ssh_cmd";
    
    // $ssh_cmd = '$HOME' . "/scripts/CreateDir -u $server_username -t $server_type -i $server_ip -p $server_port -d $create_dir";
    
    // Get output
    $ssh_result = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true);
    
    // Empty result means successful creation
    if(empty($ssh_result))
    {
        return 'success';
    }
    else
    {
        return 'Remote Error: ' . $ssh_result;
    }
    
    /*
    if(!gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true))
    {
        return false;
    }
    else
    {
        return true;
    }
    */
}







########################################################################









//
// Get remote file type
//
function gpx_remote_file_type($server_id,$file_name,$dir_path)
{
    require(GPX_DOCROOT . '/include/db.php');
    require_once(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id        = mysql_real_escape_string($server_id);
    $safe_prev_dir  = mysql_real_escape_string($server_dir);
    $safe_new_dir   = mysql_real_escape_string($dir_name);
    
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

    // User's server directory
    $user_dir   = '$HOME' . "/accounts/$server_username/$server_type/$server_ip\:$server_port";
    
    // Local path to filename
    $file_path  = $dir_path . '/' . $file_name;
    
    // Get info
    $ssh_cmd = '$HOME' . "/scripts/FileType -d $user_dir -f $file_path";
    
    if(!$file_type = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true))
    {
        return false;
    }
    else
    {
        return $file_type;
    }
}








########################################################################





//
// Get file contents
//
function gpx_remote_file_contents($server_id,$file_path)
{
    require(GPX_DOCROOT . '/include/db.php');
    require_once(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id        = mysql_real_escape_string($server_id);
    
    ####################################################################

    // Get information for this Game/Voice Server
    $query_gameinfo = "SELECT 
                          servers.port,
                          servers.type,
                          clients.username,
                          network.physical,
                          network.parentid,
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
        $is_physical      = $row_gameinfo['physical'];
        $phys_parentid    = $row_gameinfo['parentid'];
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
    
    // User's server directory
    $user_dir   = '$HOME' . "/accounts/$server_username/$server_type/$server_ip\:$server_port";
    
    // Local path to filename
    $file_path  = $user_dir . '/' . $file_path;
    
    // Get file contents
    $ssh_cmd = "cat $file_path";

    if(!$file_contents = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true))
    {
        return 'FAILURE: Failed to get file from the Remote Server';
    }
    else
    {
        return $file_contents;
    }
}








########################################################################





//
// Overwrite file contents with new text
//
function gpx_remote_file_edit($server_id,$file_path,$text)
{
    require(GPX_DOCROOT . '/include/db.php');
    require_once(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id        = mysql_real_escape_string($server_id);
    
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
    
    // User's server directory
    $user_dir   = '$HOME' . "/accounts/$server_username/$server_type/$server_ip\:$server_port";
    
    // Local path to filename
    $file_path  = $user_dir . '/' . $file_path;

    ####################################################################
    
    //
    // Loop through every line, trim newlines, and re-add a \n
    //
    $text       = stripslashes($text);
    $arr_lines  = explode("\n", $text);
    
    // Create new text variable
    $clean_text = "";
    
    foreach($arr_lines as $single_line)
    {
        // Strip any newline chars
        $single_line = trim($single_line);
        
        // Add \n
        $single_line = $single_line . '\n';
        
        // Escape quotes
        $single_line = str_replace('"', '\"', $single_line);
        $single_line = str_replace("'", "\'", $single_line);
        
        // Trim the ending one last time
        $single_line = trim($single_line);
        
        // Add to clean text
        $clean_text .= $single_line;
    }

    // Write over text file
    $ssh_cmd = 'echo -e "' . $clean_text . '" > ' .  $file_path;


    // Run on remote server
    if(!gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd))
    {
        return 'FAILURE: Failed to get file from the Remote Server';
    }
    else
    {
        return true;
    }
}




########################################################################





//
// Delete remote file
//
function gpx_remote_file_delete($server_id,$file_name,$dir_path)
{
    require(GPX_DOCROOT . '/include/db.php');
    require_once(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    // Escape all given values
    $safe_id        = mysql_real_escape_string($server_id);
    
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
    
    // User's server directory
    $user_dir   = '$HOME' . "/accounts/$server_username/$server_type/$server_ip\:$server_port";
    
    // Local path to filename
    $file_path  = $dir_path . '/' . $file_name;

    ####################################################################

    // Delete File
    $ssh_cmd = 'echo y | rm -f ' .  $user_dir . '/' . $file_path;

    // Run on remote server
    if(!gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd))
    {
        return 'FAILURE: Failed to delete file from the Remote Server';
    }
    else
    {
        return true;
    }
}

?>
