<html>
<head>
<meta http-equiv="content-type" content="text/html; charset={$charset}" />
<title>{$company} | {$pagetitle}</title>
<link rel="stylesheet" type="text/css" href="templates/{$template}/style.css" />
<script type="text/javascript" src="templates/{$template}/scripts/jquery.js"></script>
<script type="text/javascript" src="templates/{$template}/scripts/dd.js"></script>
</head>


<body>

          
<table border="0" cellpadding="0" cellspacing="0" align="center" width="750" style="border-left:1px solid #999999;border-right:1px solid #999999;border-bottom:1px solid black">
  <tr>
    <td align="center" height="30" colspan="6" background="templates/{$template}/img/logo_grad.png"><img src="templates/{$template}/img/logo.png"></td>
  </tr>
</table>

{if $nonav != 1}
<table border="0" cellpadding="0" cellspacing="0" align="center" width="750" style="border-left:1px solid #999999;border-right:1px solid #999999">
  <tr align="center" height="43" style="vertical-align:middle" valign="middle">
      <td style="vertical-align:middle" valign="middle" colspan="6" height="43" align="center" background="templates/{$template}/img/nav/bg.png" bgcolor="#053a68">
          <ul id="jsddm" style="vertical-align:middle">
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
                      <li><a href="templates.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/list.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_configuration_game_templates}</a></li>
                      <li><a href="addtemplate.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/add.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_templates_add}</a></li>
                  
                      <li><a href="#" style="background-color:#333333;color:white"><img src="templates/{$template}/img/nav/transparent/supported.png" border="0" style="vertical-align:middle">&nbsp;<b>{$lang.nav_suppsrv}</b></a></li>
                      <li><a href="supportedservers.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/list.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_suppsrv}</a></li>
                      <li><a href="addsupportedserver.php" style="text-align:left">&nbsp;&nbsp;&nbsp;<img src="templates/{$template}/img/nav/transparent/add.png" border="0" style="vertical-align:middle">&nbsp;{$lang.nav_suppsrv_add}</a></li>
                  </ul>
              </li>
          </ul>
      </td>
  </tr>
</table>
{/if}

<table border="0" cellpadding="0" cellspacing="0" align="center" width="750" style="border-left:1px solid #999999;border-right:1px solid #999999;border-bottom:1px solid #999999">
  <tr>
    <td valign="top" class="center_table" colspan="6" style="border-top:1px solid black">
