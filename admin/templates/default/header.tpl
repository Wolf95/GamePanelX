<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>{$company} | {$pagetitle}</title>
<link rel="stylesheet" type="text/css" href="templates/{$template}/style.css" />
<script type="text/javascript" src="templates/{$template}/scripts/jquery.js"></script>
<script type="text/javascript" src="templates/{$template}/scripts/dd.js"></script>
<script type="text/javascript" src="templates/{$template}/scripts/gpx.js"></script>
</head>

<body>

{if $logged_in}
{literal}
<script type="text/javascript">
$(document).ready(function() {
    // InstantNotifications
    checkNotif();
    $('#divCheckNotify').click(function(event) {
        checkNotif();
        if ($('#boxNotify').is(":visible")) {
            $('#divCheckNotify').css("background-image", "url(templates/{/literal}{$template}{literal}/img/grad_30px-new.png)");
        }
        else
        {
            getNotif();
            $('#divCheckNotify').css("background-image", "url(templates/{/literal}{$template}{literal}/img/grad_30px-sel.png)");
        }
        $('#boxNotify').toggle();
    });
    setInterval("checkNotif()", 7000);
    $('#hideNotifyDiv').click(function(event) {
        $('#divCheckNotify').css("background-image", "url(templates/{/literal}{$template}{literal}/img/grad_30px-new.png)");
        $('#boxNotify').hide();
    });
    // OnlineClients
    onlineClients();
    $('#clientsButton').click(function(event) {
        onlineClients();
        if ($('#boxClients').is(":visible")) {
            $('#clientsButton').css("background-image", "url(templates/{/literal}{$template}{literal}/img/grad_30px-new.png)");
        }
        else
        {
            getClients()
            $('#clientsButton').css("background-image", "url(templates/{/literal}{$template}{literal}/img/grad_30px-sel.png)");
        }
        $('#boxClients').toggle();
    });
    setInterval("onlineClients()", 10000);
    $('#hideClientsDiv').click(function(event) {
        $('#clientsButton').css("background-image", "url(templates/{/literal}{$template}{literal}/img/grad_30px-new.png)");
        $('#boxClients').hide();
    });
});
</script>
{/literal}

{* Recent Notifications Popup Box *}
<table border="0" cellpadding="0" cellspacing="0" align="center" width="344" height="270" id="boxNotify" style="border:1px solid #666;position:fixed;bottom: 0px;right:191px;display:none">
<tr>
  <td style="font-size:8pt;font-weight:bold;color:#444;background-image:url(templates/{$template}/img/nav_grad_40px.png)" valign="middle" height="20" valign="top" id="hideNotifyDiv" align="center">{$lang.notify_recent}</td>
</tr>
<tr>
  <td colspan="3" height="230" style="background-color:#F1F1F1;font-size:10pt" valign="top" id="boxNotifyTd"> </td>
</tr>
</table>

{* Online Clients Popup Box *}
<table border="0" cellpadding="0" cellspacing="0" align="center" width="162" height="270" id="boxClients" style="border:1px solid #666;position:fixed;bottom: 0px;right:30px;display:none">
<tr>
  <td style="font-size:8pt;font-weight:bold;color:#444;background-image:url(templates/{$template}/img/nav_grad_40px.png)" valign="middle" height="20" valign="top" id="hideClientsDiv" align="center">{$lang.notify_clients}</td>
</tr>
<tr>
  <td colspan="3" height="230" style="background-color:#F1F1F1;font-size:10pt" valign="top" id="boxClientsTd"> </td>
</tr>
</table>

{* Bottom Bar with Notifications and Online Clients *}
{if $browser ne "Internet Explorer" or $browser eq "Internet Explorer" and $browser_ver gte "9"}
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="30" style="border-top:1px solid #888;position:fixed;bottom:0px;left:0px;padding:0px;">
<tr>
  <td height="30" style="background-image:url('templates/{$template}/img/grad_30px-new.png')"><a href="main.php"><img src="templates/{$template}/img/bar_logo.png" border="0" height="26" /></a></td>
  <td height="30" width="342" align="center" style="background-image:url('templates/{$template}/img/grad_30px-new.png');cursor:pointer" id="divCheckNotify">&nbsp;</td>
  <td height="30" width="160" align="center" style="background-image:url('templates/{$template}/img/grad_30px-new.png');cursor:pointer" id="clientsButton">&nbsp;</td>
  <td height="30" width="30" style="background-image:url('templates/{$template}/img/grad_30px-new.png')">&nbsp;</td>
</tr>
</table>
{/if}
{/if}

<div align="center">
  <div id="hdr_logo" style="background-image:url('templates/{$template}/img/logo_grad.png');"><img src="templates/{$template}/img/logo.png" border="0" /></div>
</div>

{if $nonav != 1}
<table border="0" cellpadding="0" cellspacing="0" align="center" width="850" id="hdr_nav">
  <tr align="center" height="43" style="vertical-align:middle" valign="middle">
      <td style="vertical-align:middle" valign="middle" colspan="6" height="43" align="center" background="templates/{$template}/img/nav/bg.png" bgcolor="#053a68">
          <ul id="jsddm" style="vertical-align:middle" width="200">
              <li><a href="main.php" style="text-align:center"><img src="templates/{$template}/img/nav/transparent/main.png" border="0" style="vertical-align:middle"><span class="nav_titles">&nbsp;&nbsp;{$lang.nav_main}</span></a></li>
              <li><a href="accounts.php" style="text-align:center"><img src="templates/{$template}/img/nav/transparent/clients.png" border="0" style="vertical-align:middle"><span class="nav_titles">&nbsp;&nbsp;{$lang.nav_accounts}</span></a>
                  <ul>
                      
                      <li><a href="#" style="background-color:#333333;color:white"><img src="templates/{$template}/img/nav/transparent/clients.png" border="0" style="vertical-align:middle">&nbsp;<b>{$lang.nav_accounts_clients}</b></a></li>
                      <li><a href="clients.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/list.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_accounts_list_client_accounts}</a></li>
                      <li><a href="addclient.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/add.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_accounts_create_client_account}</a></li>
                      
                      
                      
                      <li><a href="#" style="background-color:#333333;color:white"><img src="templates/{$template}/img/nav/transparent/admins.png" border="0" style="vertical-align:middle">&nbsp;<b>{$lang.nav_accounts_admins}</b></a></li>
                      <li><a href="admins.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/list.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_accounts_list_admin_accounts}</a></li>
                      <li><a href="addadmin.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/add.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_accounts_create_admin_account}</a></li>
                      
                      
                      <li><a href="#" style="background-color:#333333;color:white"><img src="templates/{$template}/img/nav/transparent/setup.png" border="0" style="vertical-align:middle">&nbsp;<b>{$lang.nav_accounts_my_account}</b></a></li>
                      <li><a href="editadmin.php?id={$userid}" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/setup.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_accounts_edit_details}</a></li>
                  </ul>
              </li>
              <li style="vertical-align:middle"><a href="servertypes.php" style="text-align:center;width:100px"><img src="templates/{$template}/img/nav/transparent/servers.png" border="0" style="vertical-align:middle"><span class="nav_titles">&nbsp;&nbsp;{$lang.nav_client_servers}</span></a>
                  <ul>
                      <li><a href="#" style="background-color:#333333;color:white"><img src="templates/{$template}/img/nav/transparent/games.png" border="0" style="vertical-align:middle">&nbsp;<b>{$lang.nav_client_servers_game_servers}</b></a></li>
                      <li><a href="servers.php?type=game" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/list.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_client_servers_list_game_servers}</a></li>
                      <li><a href="createserver.php?type=game" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/add.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_client_servers_create_game_server}</a></li>
                      
                      
                      
                      <li><a href="#" style="background-color:#333333;color:white"><img src="templates/{$template}/img/nav/transparent/voip.png" border="0" style="vertical-align:middle">&nbsp;<b>{$lang.nav_client_servers_voice_servers}</b></a></li>
                      <li><a href="servers.php?type=voip" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/list.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_client_servers_list_voice_servers}</a></li>
                      <li><a href="createserver.php?type=voip" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/add.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_client_servers_create_voice_server}</a></li>
                  </ul>
              </li>
              <li><a href="network.php" style="text-align:center;width:75px"><img src="templates/{$template}/img/nav/transparent/network.png" border="0" style="vertical-align:middle"><span class="nav_titles">&nbsp;&nbsp;{$lang.nav_network}</span></a>
                  <ul>
                      <li><a href="#" style="background-color:#333333;color:white"><img src="templates/{$template}/img/nav/transparent/network.png" border="0" style="vertical-align:middle">&nbsp;<b>{$lang.nav_network_servers}</b></a></li>
                      <li><a href="network.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/list.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_network_list_servers}</a></li>
                      <li><a href="addnetworkserver.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/add.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_network_create}</a></li>
                      <li><a href="testconnection.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/cmd.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_network_test}</a></li>
                  </ul>
              </li>
              <li><a href="tickets.php" style="text-align:center;width:110px"><img src="templates/{$template}/img/nav/transparent/support.png" border="0" style="vertical-align:middle"><span class="nav_titles">&nbsp;&nbsp;{$lang.nav_tickets}</span></a>
                  <ul>
                      <li><a href="#" style="background-color:#333333;color:white"><img src="templates/{$template}/img/nav/transparent/support.png" border="0" style="vertical-align:middle">&nbsp;<b>{$lang.nav_tickets}</b></a></li>
                      <li><a href="tickets.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/list.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_tickets_list}</a></li>
                      <li><a href="addticket.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/add.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_tickets_create}</a></li>
                  </ul>
              </li>
              <li><a href="configuration.php" style="text-align:center;width:100px"><img src="templates/{$template}/img/nav/transparent/setup.png" border="0" style="vertical-align:middle"><span class="nav_titles">&nbsp;&nbsp;{$lang.nav_system}</span></a>
                  <ul>
                      <li><a href="#" style="background-color:#333333;color:white"><img src="templates/{$template}/img/nav/transparent/setup.png" border="0" style="vertical-align:middle">&nbsp;<b>{$lang.nav_system_settings}</b></a></li>
                      <li><a href="configuration.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/setup.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_configuration_settings}</a></li>

                      <li><a href="#" style="background-color:#333333;color:white"><img src="templates/{$template}/img/nav/transparent/template.png" border="0" style="vertical-align:middle">&nbsp;<b>{$lang.nav_templates}</b></a></li>
                      <li><a href="archives.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/list.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_configuration_game_templates}</a></li>
                      <li><a href="createarchive.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/add.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_templates_add}</a></li>
                  
                      <li><a href="#" style="background-color:#333333;color:white"><img src="templates/{$template}/img/nav/transparent/supported.png" border="0" style="vertical-align:middle">&nbsp;<b>{$lang.nav_suppsrv}</b></a></li>
                      <li><a href="defaultservers.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/list.png" border="0" style="vertical-align:middle">&nbsp;{$lang.suppsrv_icon_default_srv}</a></li>
                      <li><a href="addsupportedserver.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/add.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_suppsrv_add}</a></li>
                      <li><a href="#" style="background-color:#333333;color:white"><img src="templates/{$template}/img/nav/transparent/supported.png" border="0" style="vertical-align:middle">&nbsp;<b>{$lang.nav_domains_dns}</b></a></li>
                      <li><a href="domains.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/list.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_domains_manage}</a></li>
                  </ul>
              </li>
          </ul>
      </td>
  </tr>
</table>
{/if}

<table border="0" cellpadding="0" cellspacing="0" align="center" width="847" style="border-bottom:1px solid #999999;border-bottom-left-radius:8px;border-bottom-right-radius:8px;box-shadow:-1px 0px 10px #6D7B8D;-moz-box-shadow:-1px 5px 10px #6D7B8D;-webkit-box-shadow:-1px 5px 10px #6D7B8D;">
  <tr>
    <td valign="top" class="center_table" colspan="6" style="border-top:1px solid black">
