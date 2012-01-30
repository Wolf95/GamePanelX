<?php
// Get info on a template -- SSH in, get ls -l status
$url_id = mysql_real_escape_string($_GET['id']);

require(GPX_DOCROOT.'/include/functions/ssh.php');
$enc_key = $config['encrypt_key'];

if(empty($enc_key))die('No enc key found');

########################################################################
#archives.file_path,
// Get template info
$result_info  = @mysql_query("SELECT 
                                network.ip,
                                AES_DECRYPT(network.conn_user, '$enc_key') AS conn_user,
                                AES_DECRYPT(network.conn_pass, '$enc_key') AS conn_pass,
                                AES_DECRYPT(network.conn_port, '$enc_key') AS conn_port 
                              FROM archives 
                              LEFT JOIN network ON 
                                archives.networkid = network.id 
                              WHERE 
                                archives.id = '$url_id'") or die('Failed to query for archive info');

while($row_server = mysql_fetch_array($result_info))
{
    $conn_ip    = $row_server['ip'];
    $conn_user  = $row_server['conn_user'];
    $conn_pass  = $row_server['conn_pass'];
    $conn_port  = $row_server['conn_port'];
    #$file_path  = $row_server['file_path'];
}

// Run an `ls`
$ssh_cmd    = 'ls -lh $HOME/templates/' . $url_id . '.tar.gz | awk \'{print $5}\'';
$result_ls  = gpx_ssh_exec($conn_ip,$conn_port,$conn_user,$conn_pass,$ssh_cmd,true);

// Not found
if(preg_match("/No\ such\ file\ or\ directory/i", $result_ls)) echo 'no';

// Found
elseif(preg_match("/^\d+/", $result_ls)) echo $result_ls;

// Unknown
else echo 'unknown';

?>
