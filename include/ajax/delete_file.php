<?php
if(!isset($_SESSION['gpx_username']))
{
    die('<center><b>Error:</b> You must be logged-in to view this page.</center>');
}

// Check if admin
if($_SESSION['gpx_isadmin'] == 1) $is_admin = true;
else $is_admin  = false;

########################################################################

$url_serverid   = mysql_real_escape_string($_GET['id']);
$url_prev_dir   = mysql_real_escape_string($_GET['prev_dir']);
$url_file       = base64_decode(mysql_real_escape_string($_GET['file']));

if($url_prev_dir) $url_prev_dir .= '/';
if(empty($url_serverid) || empty($url_file))
{
    die('Error: Required fields were left empty');
}

// No weirdness
if(preg_match("/\.\./", $url_prev_dir))
{
    die('Invalid directory specified!');
}

########################################################################

// Make sure this user has access to this server
if(!$is_admin)
{
    $this_userid  = $_SESSION['gpx_userid'];
    $result_ac  = @mysql_query("SELECT id,client_file_man FROM servers WHERE id = '$url_id' AND userid = '$this_userid'");
    $row_ac     = mysql_fetch_row($result_ac);
    $has_fileman  = $row_ac[1];
    
    if(!$row_ac[0]) die('You do not have access to this server.  Please contact your host.');
    elseif($has_fileman == 'N')
    {
        die('You do not have File Manager access to this server.  Please contact your host.');
    }
}

########################################################################

// Get basic server info
$result_info    = @mysql_query("SELECT 
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
                                  servers.id = '$url_serverid'");

while($row_info = mysql_fetch_array($result_info))
{
    $srv_username   = $row_info['username'];
    $srv_ip         = $row_info['ip'];
    $srv_port       = $row_info['port'];
    $srv_type       = $row_info['type'];
    $is_physical    = $row_info['physical'];
    $phys_parentid  = $row_info['parentid'];
}

########################################################################

require(GPX_DOCROOT . '/include/functions/ssh.php');
$enc_key = $config['encrypt_key'];

// IP is Physical, use this IP to get network info
if($is_physical == 'Y')
{
    $sql_where = "WHERE ip = '$srv_ip'";
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

########################################################################

$full_path      = '$HOME/accounts/' . $srv_username . '/' . $srv_type . '/' . $srv_ip . '\:' . $srv_port . '/' . $url_prev_dir . $url_file;
$ssh_cmd        = 'if [ -f ' . $full_path . ' ]; then rm -f ' . $full_path . '; fi; echo success';

// Run the file delete
echo gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true);

?>
