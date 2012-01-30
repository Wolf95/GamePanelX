<?php
$url_networkid  = mysql_real_escape_string($_GET['networkid']);
$url_port       = mysql_real_escape_string($_GET['port']);

if(empty($url_networkid) || empty($url_port))
{
    die('Empty network ID or port. Please try again.');
}

########################################################################

$query_ck = "SELECT 
                id 
             FROM servers 
             WHERE 
                networkid = '$url_networkid' 
                AND port = '$url_port'";
$result_ck  = @mysql_query($query_ck);
$row_ck     = mysql_fetch_row($result_ck);
$used_id    = $row_ck[0];

// Output
if(!empty($used_id) && is_numeric($used_id))
{
    echo 'used';
}
else
{
    echo 'ok';
}

?>
