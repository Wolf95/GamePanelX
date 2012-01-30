<?php
//
// Create a Server Template
//
function gpx_remote_create_template($id)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');

    // Escape all given values
    $safe_id = mysql_real_escape_string($id);
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    if(!is_numeric($safe_id))
    {
        return 'Archive ID is not numeric; what happened?';
    }
    
    ####################################################################
    
    // Get server info
    $query_server =  "SELECT 
                        archives.file_path,
                        network.ip,
                        AES_DECRYPT(network.conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(network.conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(network.conn_port, '$enc_key') AS conn_port 
                      FROM archives 
                      LEFT JOIN network ON 
                        archives.networkid = network.id 
                      WHERE 
                        archives.id = '$safe_id'";
    
    $result_server = @mysql_query($query_server) or die('Failed to get archive info');
    
    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip              = $row_server['ip'];
        $conn_user            = $row_server['conn_user'];
        $conn_pass            = $row_server['conn_pass'];
        $conn_port            = $row_server['conn_port'];
        $archive_file_path    = $row_server['file_path'];
    }
    
    ####################################################################
    
    // Check required
    if(empty($archive_file_path))
    {
        return 'Required values were empty (path or hash)';
    }
    
    ####################################################################
    
    // Add $HOME if necessary
    if(!preg_match('/^\$HOME/', $archive_file_path))
    {
        $archive_file_path  = '$HOME/' . $archive_file_path;
    }
    
    //
    // Run the command through SSH2
    //
    $ssh_cmd = '$HOME/scripts/CreateTemplate -p ' . $archive_file_path . ' -i ' . $safe_id;
    
    if(!gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd))
    {
        return 'Failed to create the archive on the Remote Server';
    }
    else
    {
        return 'success';
    }
}













########################################################################











//
// Delete a Server Template
//
function gpx_remote_delete_template($safe_id)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Encryption Key
    $enc_key  = $config['encrypt_key'];
    
    if(empty($safe_id) || !is_numeric($safe_id))
    {
        return 'Archive ID is empty or not numeric; what happened?';
    }
    
    ####################################################################
    
    // Get server info
    $query_server =  "SELECT 
                        network.ip,
                        AES_DECRYPT(network.conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(network.conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(network.conn_port, '$enc_key') AS conn_port 
                      FROM network 
                      LEFT JOIN archives ON 
                        network.id = archives.networkid 
                      WHERE 
                        archives.id = '$safe_id'";
    
    // Get info for this server
    $result_server = @mysql_query($query_server) or die('Failed to get template info');

    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip              = $row_server['ip'];
        $conn_user            = $row_server['conn_user'];
        $conn_pass            = $row_server['conn_pass'];
        $conn_port            = $row_server['conn_port'];
    }
    
    ####################################################################
    
    //
    // Run the command through SSH2
    //
    $ssh_cmd = '$HOME' . "/scripts/DeleteTemplate -i $safe_id";
    $ssh_result = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true);
    
    if($ssh_result != 'success')
    {
        return 'Failed to delete on the Remote Server: <i>' . $ssh_result . '</i>';
    }
    else
    {
        return 'success';
    }
}

########################################################################


//
// Check Template Status
//
function gpx_remote_status_template($id)
{
    require(GPX_DOCROOT . '/include/db.php');
    require(GPX_DOCROOT . '/include/functions/ssh.php');
    
    // Escape all given values
    $safe_id = mysql_real_escape_string($id);
    
    // Encryption Key
    $enc_key = $config['encrypt_key'];
    
    if(empty($safe_id) || !is_numeric($safe_id))
    {
        return 'The archive ID is empty or not numeric; what happened?';
    }
    
    ####################################################################
    
    // Get server info
    $query_server =  "SELECT 
                        network.ip,
                        AES_DECRYPT(network.conn_user, '$enc_key') AS conn_user,
                        AES_DECRYPT(network.conn_pass, '$enc_key') AS conn_pass,
                        AES_DECRYPT(network.conn_port, '$enc_key') AS conn_port 
                      FROM network 
                      LEFT JOIN archives ON 
                        network.id = archives.networkid 
                      WHERE 
                        archives.id = '$safe_id'";
    
    // Get info for this server
    $result_server = @mysql_query($query_server) or die('<center><b>Error:</b> <i>remote.php</i>: Failed to get template info!</center>');

    while($row_server = mysql_fetch_array($result_server))
    {
        $conn_ip              = $row_server['ip'];
        $conn_user            = $row_server['conn_user'];
        $conn_pass            = $row_server['conn_pass'];
        $conn_port            = $row_server['conn_port'];
    }
        
    ####################################################################
    
    //
    // Run the command through SSH2
    //
    $ssh_cmd = '$HOME' . "/scripts/CheckTemplateStatus -i $safe_id";
    
    if(!$ssh_return = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true))
    {
        die('Failed to check the remote archive status');
    }
    else
    {
        return $ssh_return;
    }
}

?>
