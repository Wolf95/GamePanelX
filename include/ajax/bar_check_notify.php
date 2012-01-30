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

//
// Update pending server statuses
//
$result_upd = @mysql_query("SELECT COUNT(id) AS thecount FROM servers WHERE creation_status = 'running'");

while($row_upd  = mysql_fetch_array($result_upd))
{
    $count_creating = $row_upd['thecount'];
}

// Server needs updating; run check for completion
if($count_creating >=1)
{
    require(GPX_DOCROOT . '/include/functions/status.php');
    
    $result_run  = @mysql_query("SELECT id FROM servers WHERE creation_status = 'running' ORDER BY id DESC");
    
    while($row_run  = mysql_fetch_array($result_run))
    {
        $this_serverid  = $row_run['id'];
        gpx_getstatus_create_server($this_serverid);
    }
}

#####################################################

// Count new unseen notifications
$result_notify  = @mysql_query("SELECT COUNT(id) AS numnotify FROM notify WHERE seen = 'N'");

while($row_notify = mysql_fetch_array($result_notify))
{
    $num_notify = $row_notify['numnotify'];
}

echo '<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="100%"><tr>';

// Print number out
if($num_notify == 0)
{
    echo '<td width="50%" align="right" style="padding-right:5px"><img src="templates/default/img/icons/notify-none-32px.png" width="20" height="20" border="0" /></td><td>(0) ' . $lang[notify_notifications] . '</td>';
}
elseif($num_notify == 1)
{
    echo '<td width="50%" align="right" style="padding-right:5px"><img src="templates/default/img/icons/notify-32px.png" width="20" height="20" border="0" /></td><td><b>(1) ' . $lang[notify_notification] . '</b></td>';
}
elseif($num_notify > 1)
{
    echo '<td width="50%" align="right" style="padding-right:5px"><img src="templates/default/img/icons/notify-32px.png" width="20" height="20" border="0" /></td><td><b>(' . $num_notify . ') ' . $lang[notify_notifications] . '</b></td>';
}

exit;

?>
