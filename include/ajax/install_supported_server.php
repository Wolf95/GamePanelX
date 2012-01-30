<?php
// Install a Supported Game/Voice Server (escaped in 'gpx_supported_install' function)
$url_networkid    = $_GET['networkid'];
$url_cfgid        = $_GET['cfgid'];
$url_description  = mysql_real_escape_string($_GET['description']);
$url_default      = $_GET['is_default'];

// For now, steam only
$filename         = 'hldsupdatetool.bin';

########################################################################

require(GPX_DOCROOT.'/include/functions/supported.php');

$result_install = gpx_supported_install($url_networkid,$url_cfgid,$filename,$url_description,$url_default);

if($result_install == 'success')
{
    echo 'success';
}
else
{
    echo $result_install;
}

?>
