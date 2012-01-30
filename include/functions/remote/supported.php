<?php
//
// Install a Supported Server
//
function gpx_remote_supported_install($templateid,$networkid,$filename,$install_cmd,$steam_mod)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    if(empty($templateid) || empty($networkid))
    {
        die('Remote: Required values were not given');
    }
    
    // Safe ID
    $safe_id  = mysql_real_escape_string($templateid);    
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    $tpl_networkid  = $networkid;
    
    if(!is_numeric($safe_id))
    {
        die('Remote: Archive ID is not numeric; what happened?');
    }
    
    ####################################################################
    
    // Get server info
    $query_server =  "SELECT 
                        ip,
                        AES_DECRYPT(conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(conn_port, '$enc_key') AS conn_port 
                      FROM network 
                      WHERE 
                        id = '$tpl_networkid'";
    
    // Get info for this server
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get server info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip              = $row_server['ip'];
        $conn_user            = $row_server['conn_user'];
        $conn_pass            = $row_server['conn_pass'];
        $conn_port            = $row_server['conn_port'];
    }
    
    // Required
    if(empty($steam_mod))
    {
        if(empty($filename) || empty($install_cmd))
        {
            die('Remote: Required values were not given');
        }
    }
    
    ####################################################################
    
    // Get primary admin email
    $result_em    = @mysql_query("SELECT value FROM configuration WHERE setting = 'PrimaryEmail'");
    $row_em       = mysql_fetch_row($result_em);
    $admin_email  = $row_em[0];
    
    ####################################################################
    
    //
    // Run the command through SSH2
    //
    
    // Use steam installer
    if(!empty($steam_mod))
    {
        $ssh_cmd = '$HOME' . "/scripts/InstallSupportedServer -i $safe_id -s '$steam_mod'";
        
        if(!empty($admin_email)) $ssh_cmd .= " -e '$admin_email'";
    }
    // All others...
    else $ssh_cmd = '$HOME' . "/scripts/InstallSupportedServer -i $safe_id -f $filename -c '$install_cmd'";
    
    $result_install = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true);
    
    if($result_install == 'success')
    {
        return 'success';
    }
    else
    {
        return 'Remote Error: ' . $result_install;
    }
}









//
// Check Supported Server Installation Status
//
function gpx_remote_status_install_supported_server($templateid)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Escape all given values
    $safe_id = mysql_real_escape_string($templateid);
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    if(!is_numeric($safe_id))
    {
        die('The Archive ID was not numeric; what happened?');
    }
    
    ####################################################################
    
    $result_server  = @mysql_query("SELECT networkid FROM archives WHERE id = '$safe_id'") or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get supported server info!</center>');
    $row_server     = mysql_fetch_row($result_server);
    $networkid      = $row_server[0];
    
    ####################################################################
    
    // Get Network Server info
    $query_server =  "SELECT 
                        ip,
                        AES_DECRYPT(conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(conn_port, '$enc_key') AS conn_port 
                      FROM network 
                      WHERE 
                        id = '$networkid'";
    
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get Network Server info!</center>');
    
    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip    = $row_server['ip'];
        $conn_user  = $row_server['conn_user'];
        $conn_pass  = $row_server['conn_pass'];
        $conn_port  = $row_server['conn_port'];
    }
    
    ####################################################################
    
    // Setup the SSH command
    $ssh_cmd = '$HOME/scripts/CheckSupportedInstallStatus -i ' . $safe_id;
    
    ####################################################################
    
    //
    // Run the command through SSH2
    //
    if(!$ssh_return = @gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true))
    {
        return 'FAILURE: <i>remote.php:</i> Failed to check the Server Creation Status!</center>';
    }
    else
    {
        return trim($ssh_return);
    }
}

?>
