<?php
$url_archiveid    = mysql_real_escape_string($_GET['id']);
$url_networkid    = mysql_real_escape_string($_GET['networkid']);
$url_cfgid        = mysql_real_escape_string($_GET['cfgid']);
$url_description  = mysql_real_escape_string($_GET['description']);
$url_is_default   = mysql_real_escape_string($_GET['is_default']);

if(empty($url_archiveid) || empty($url_networkid) || empty($url_cfgid))
{
    die('Required values were left empty');
}

########################################################################

@mysql_query("UPDATE archives SET networkid = '$url_networkid',cfgid = '$url_cfgid',is_default = '$url_is_default',description = '$url_description' WHERE id = '$url_archiveid'") or die('Failed to update the archive');

echo 'success';

?>
