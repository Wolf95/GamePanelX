<?php

// Make sure user is an admin
if(!isset($_SESSION['gpx_isadmin']))
{
    die('<b>Error:</b> You are not authorized to view this page.');
}

####################################################################

// Require language support
require(GPX_DOCROOT . '/languages/' . $config['Language'] . '.php');

####################################################################

$result_num = @mysql_query("SELECT COUNT(id) AS numclients FROM clients WHERE logged_in = 'Y' AND last_response > NOW() - INTERVAL 15 MINUTE");

while($row_num  = mysql_fetch_array($result_num))
{
    $count_clients  = $row_num['numclients'];
}

// Output number
if($count_clients == 0)
{
    echo '(0) ' . $lang[notify_onl_clients];
}
elseif($count_clients == 1)
{
    echo '<b>(1) ' . $lang[notify_onl_client] . '</b>';
}
elseif($count_clients > 1)
{
    echo '<b>(' . $count_clients . ') ' . $lang[notify_onl_clients] . '</b>';
}


exit;

?>
