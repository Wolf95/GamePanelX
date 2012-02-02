<?php
/*
 * GamePanelX Pro
 * Complete Game and Voice server management tool
 * 
 * Copyright(C) 2009-2010 GamePanelX Pro.  All Rights Reserved. 
 * 
 * Email: support@gamepanelx.com
 * Website: http://www.gamepanelx.com
 * 
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.                                                         
 *                                                                      
 * You may not reverse  engineer, decompile, defeat  license  encryption
 * mechanisms, or  disassemble this software product or software product
 * license.  GamePanelX Pro may terminate this license if you don't
 * comply with any of the terms and conditions set forth in our end user
 * license agreement (EULA).  In such event,  licensee  agrees to return
 * licensor  or destroy  all copies of software  upon termination of the
 * license.                                                             
 *                                                                      
 * Please see the EULA file for the full End User License Agreement.    
*/

// Check logged-in
if(!isset($_SESSION['gpx_username']))
{
    die('<center><b>Error:</b> You must be logged-in to view this page.</center>');
}

if(empty($_GET['id']))
{
    die('<center><b>Error:</b> No server ID given!</center>');
}

$url_id = mysql_real_escape_string($_GET['id']);

// Check if admin
if($_SESSION['gpx_isadmin'] == 1) $is_admin = true;
else $is_admin  = false;

// Make sure this user has access to this server
if(!$is_admin)
{
    $this_userid  = $_SESSION['gpx_userid'];
    $result_ac  = @mysql_query("SELECT id FROM servers WHERE id = '$url_id' AND userid = '$this_userid'");
    $row_ac     = mysql_fetch_row($result_ac);
    
    if(!$row_ac[0]) die('You do not have access to this server.  Please contact your host.');
}

########################################################################

//
// Get Server Info
//
$server_query = "SELECT 
                    servers.id,
                    DATE_FORMAT(servers.date_created, '%c/%e/%Y %H:%i') AS date_created,
                    servers.type,
                    servers.status,
                    servers.server,
                    servers.port,
                    servers.description,
                    servers.creation_status,
                    servers.update_status,
                    servers.subdomain,
                    servers.notes,
                    clients.username,
                    cfg.short_name,
                    cfg.long_name,
                    cfg.is_steam,
                    domains.domain,
                    network.ip,
                    network.datacenter,
                    network.location 
                 FROM servers 
                 LEFT JOIN clients ON 
                    servers.userid = clients.id 
                 LEFT JOIN cfg ON 
                    servers.server = cfg.short_name 
                 LEFT JOIN domains ON 
                    servers.domainid = domains.id 
                 LEFT JOIN network ON 
                    servers.networkid = network.id 
                 WHERE 
                    servers.id = '$url_id'";

$result = @mysql_query($server_query) or die('<center><b>Error:</b> Failed to get game server information!</center>');

// Get the values
while($row = mysql_fetch_array($result))
{
    $srv_id         = $row['id'];
    $srv_type       = $row['type'];
    $srv_status     = $row['status'];
    $srv_crea_sts   = $row['creation_status'];
    $srv_upd_sts    = $row['update_status'];
    $srv_ip         = $row['ip'];
    $srv_port       = $row['port'];
    $srv_descr      = $row['description'];
    $srv_subdom     = $row['subdomain'];
    $srv_notes      = $row['notes'];
    $srv_username   = $row['username'];
    $srv_short_name = $row['short_name'];
    $srv_long_name  = $row['long_name'];
    $srv_is_steam   = $row['is_steam'];
    $srv_domain     = $row['domain'];
    $srv_datacenter = $row['datacenter'];
    $srv_location   = $row['location'];
}

########################################################################

// Check if still running the initial install
if($srv_crea_sts == 'running')
{
    require(GPX_DOCROOT.'/include/functions/remote.php');
    gpx_remote_status_create_server($srv_id);
}
    
?>

<style type="text/css">
#srv_actions
{
    width: 130px;
    height: 85px;
}
#srv_action_result
{
    width: 600px;
    height: 30px;
    line-height: 30px;
    font-family: Arial;
    font-size: 12pt;
}
.infotbl
{
    padding: 4px;
}
.infotbl td,tr,tbody
{
    font-family: Arial;
    font-size: 14pt;
    color: #666;
}
</style>

<div style="width:160px;height:68px;margin-left:5px;">
    <div style="width:100%;height:48px;"><img src="templates/default/img/servers/medium/<?php echo $srv_short_name; ?>.png" style="display:block;" border="0" width="48" height="48" title="<?php echo $srv_long_name; ?> server" alt="<?php echo $srv_long_name; ?>" /></div>
    <div style="width:100%;height:20px;font-size:8pt;color:#777;"><?php echo $srv_long_name; ?></div>
</div>

<div align="center">
    <div style="width:400px;height:30px;text-align:center;font-size:14pt;color:#666;"><?php echo $srv_descr; ?></div>
</div>

<div align="center">
    <div id="srv_actions">
        
        <div style="float:left;">
            <div style="width:100%;height:64px;"><img src="templates/default/img/icons/server_restart-64.png" border="0" title="Restart Server" style="display:block;cursor:pointer;float:left;" onClick="javascript:confirmRestartServer(<?php echo $url_id; ?>);" /></div>
            <div style="width:100%;height:20px;font-size:9pt;color:#777;cursor:pointer;" onClick="javascript:confirmRestartServer(<?php echo $url_id; ?>);">Restart</div>
        </div>
        
        <div style="float:left;">
            <div style="width:100%;height:64px;"><img src="templates/default/img/icons/server_stop-64.png" border="0" title="Stop Server" style="display:block;cursor:pointer;float:left;" onClick="javascript:confirmStopServer(<?php echo $url_id; ?>);" /></div>
            <div style="width:100%;height:20px;font-size:9pt;color:#777;cursor:pointer;" onClick="javascript:confirmStopServer(<?php echo $url_id; ?>);">Stop</div>
        </div>
    </div>
    
    <div id="srv_action_result"></div>
    
</div>


<table border="0" cellpadding="0" cellspacing="0" width="400" height="60" align="center" class="infotbl">
<tr height="30">
  <td width="170"><b>Connection Info:</b></td>
  <td><?php echo $srv_ip . ':' . $srv_port; ?></td>
</tr>

<tr height="30">
  <td><b>Datacenter:</b></td>
  <td><?php echo $srv_datacenter; ?></td>
</tr>
<tr height="30">
  <td><b>Location:</b></td>
  <td><?php echo $srv_location; ?></td>
</tr>

<tr height="30">
  <td><b>Private Notes:</b></td>
  <td style="font-size:8pt;color:#888;"><i><?php
  if(empty($srv_notes))
  {
      echo 'none';
  }
  else
  {
      echo substr($srv_notes, 0, 120);
  }
  ?></i></td>
</tr>

<?php
// Installation Status
if($srv_crea_sts == 'running')
{
    echo '<tr height="30">
      <td width="170"><b>Status:</b></td>
      <td><span style="color:orange;font-size:12pt;">Installing server ...</span></td>
    </tr>';
}
?>

</table>


<table border="0" cellpadding="4" cellspacing="0" width="500" height="120" align="center" class="infotbl" style="margin-top:20px;">
<tr height="20">
  <td colspan="2" style="font-size:9pt;font-weight:bold;color:#444;">Latest Activity:</td>
</tr>
<tr height="20">
  <td colspan="2" style="font-size:9pt;color:#444;">
  <?php
  // Get list of latest log activity
  $query_log  = "SELECT 
                    activity_log.userid,
                    activity_log.typeid,
                    activity_log.user_type,
                    DATE_FORMAT(activity_log.date_added, '%c/%e/%Y at %r') AS date_added,                    
                    ad.first_name AS ad_first_name,
                    ad.last_name AS ad_last_name,
                    cl.first_name AS cl_first_name,
                    cl.last_name AS cl_last_name 
                 FROM activity_log 
                 LEFT JOIN clients cl ON 
                    activity_log.userid = cl.id 
                 LEFT JOIN admins ad ON 
                    activity_log.userid = ad.id 
                 WHERE 
                    activity_log.relid = '$url_id' 
                    AND activity_log.deleted = '0' 
                 ORDER BY activity_log.id DESC 
                 LIMIT 0,10";
  
  $result_log = @mysql_query($query_log);
  $count_log  = mysql_num_rows($result_log);
  
  if($count_log >= 1)
  {
      while($row_log = mysql_fetch_array($result_log))
      {
          $log_userid     = $row_log['userid'];
          $log_user_type  = $row_log['user_type'];
          $log_type       = $row_log['typeid'];
          $log_date       = $row_log['date_added'];
          $client_full    = $row_log['cl_first_name'] . ' ' . $row_log['cl_last_name'];
          #$admin_full     = $row_log['ad_first_name'] . ' ' . $row_log['ad_last_name'];
          
          if($log_user_type == 'admin')
          {
              #$this_name  = '<a href="manageadmin.php?id=' . $log_userid . '">' . $admin_full . '</a>';;
              $this_name  = '<a href="manageadmin.php?id=' . $log_userid . '">(admin) ' . $admin_full . ' (#' . $log_userid . ')' . '</a>';
          }
          else
          {
              $this_name  = '<a href="manageclient.php?id=' . $log_userid . '">' . $client_full . ' (#' . $log_userid . ')' . '</a>';
          }
          
          
          // Actions
          if($log_type == 1)
          {
              $type_txt = ' restarted the server';
          }
          elseif($log_type == 2)
          {
              $type_txt = ' stopped the server';
          }
          elseif($log_type == 5)
          {
              $type_txt = ' suspended the server';
          }
          elseif($log_type == 6)
          {
              $type_txt = ' unsuspended the server';
          }
          elseif($log_type == 7)
          {
              $type_txt = ' reinstalled the server';
          }
          elseif($log_type == 8)
          {
              $type_txt = ' installed an addon';
          }
          elseif($log_type == 9)
          {
              $type_txt = ' updated server settings';
          }
          elseif($log_type == 10)
          {
              $type_txt = ' updated startup settings (simple)';
          }
          elseif($log_type == 11)
          {
              $type_txt = ' updated startup settings (advanced)';
          }
          
          // Output
          echo $this_name . $type_txt . ' on ' . $log_date . '<br />';
      }
  }
  // Nothing
  else
  {
      echo 'There is no activity yet';
  }
  ?>
  </td>
</tr>
</table>
