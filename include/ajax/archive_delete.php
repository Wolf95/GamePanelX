<?php
$url_archiveid    = mysql_real_escape_string($_GET['id']);

########################################################################

require(GPX_DOCROOT.'/include/functions/templates.php');

$result_delete  = gpx_template_delete($url_archiveid);

if($result_delete == 'success')
{
    echo 'success';
}
else
{
    echo $result_delete;
}

?>
