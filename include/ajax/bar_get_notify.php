<?php
  
// Make sure user is an admin
if(!isset($_SESSION['gpx_isadmin']))
{
    die('<b>Error:</b> You are not authorized to view this page.');
}

####################################################################

// Count new unseen notifications
$query_notify   = "SELECT 
                      id,
                      typeid,
                      relid,
                      seen,
                      DATE_FORMAT(date_added, '%c/%e/%Y %h:%i%p') AS date_added 
                   FROM notify 
                   ORDER BY id DESC 
                   LIMIT 0,8";

$result_notify  = @mysql_query($query_notify);
$count_notify   = mysql_num_rows($result_notify);

#####################################################

// Require language support
require(GPX_DOCROOT . '/languages/' . $config['Language'] . '.php');

#####################################################

// List of these to set to seen later
$list_seen  = "";

// Notifications to show
if($count_notify > 0)
{
    while($row_notify = mysql_fetch_array($result_notify))
    {
        $notif_id     = $row_notify['id'];
        $notif_typeid = $row_notify['typeid'];
        $notif_relid  = $row_notify['relid'];
        $notif_seen   = $row_notify['seen'];
        $notif_date   = strtolower($row_notify['date_added']);
        
        // Add to unseen list
        if($notif_seen == 'N')
        {
            $list_seen   .= $notif_id . ',';
        }
        
        #################################################
        
        // Setup links to correct spots
        switch ($notif_typeid)
        {
            case 1:
                $notif_txt  = $lang['notify_1'];
                $img_notif  = 'clients_add-64px.png';
                $link_page  = 'manageclient.php';
                break;
            case 2:
                $notif_txt  = $lang['notify_2'];
                $img_notif  = 'add_gameserver.png';
                $link_page  = 'manageserver.php';
                break;
            case 3:
                $notif_txt  = $lang['notify_3'];
                $img_notif  = 'template_add_64.png';
                $link_page  = 'managetemplate.php';
                break;
            case 4:
                $notif_txt  = $lang['notify_4'];
                $img_notif  = 'template_64.png';
                $link_page  = 'managetemplate.php';
                break;
            case 5:
                $notif_txt  = $lang['notify_5'];
                $img_notif  = 'tickets_add.png';
                $link_page  = 'viewticket.php';
                break;
            case 6:
                $notif_txt  = $lang['notify_6'];
                $img_notif  = 'tickets_64.png';
                $link_page  = 'viewticket.php';
                break;
        }
        
        #################################################

        // Show notifications
        echo '<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="25">
              <tr>
                <td width="32" height="25" align="center"><img src="templates/default/img/icons/' . $img_notif . '" width="20" height="20" border="0" /></td>
                <td><a href="' . $link_page . '?id=' . $notif_relid . '">';
                
                // Bold new notifications
                if($notif_seen == 'N')
                {
                    echo '<b>' . $notif_txt . '</b></a></td>';
                }
                else
                {
                    echo $notif_txt . '</a></td>';
                }
              
              echo '<td width="100" align="center"><span style="font-size:8pt;color:#777">' . $notif_date . '</span></td>';
              echo '</tr>
              </table>' . "\n";
        
    }
}
else
{
    echo '<span style="font-size:8pt">' . $lang['notify_no_notifications'] . '</span>';
}

#####################################################

/*
//
// Mark current unseen list as seen
//
if(!empty($list_seen))
{
    // Remove last comma
    $list_seen  = substr($list_seen, 0, -1);

    // Set all these to seen
    @mysql_query("UPDATE notify SET seen = 'Y' WHERE id IN ($list_seen)");
}
*/

// List was opened; update everything as seen
@mysql_query("UPDATE notify SET seen = 'Y' WHERE seen != 'Y'") or die('Failed to update seen items');


?>
