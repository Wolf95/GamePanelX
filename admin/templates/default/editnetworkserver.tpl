{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {section name=db loop=$server_details}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="network.php">{$lang.network}</a> / <a href="managenetworkserver.php?id={$server_details[db].id}">{$lang.managenetsrv_title}</a> / {$lang.editnetsrv_title}
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
      
    <br /><br />



    {if $infobox}
    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="infobox">
      <tr>
        <td align="center"><br />{$infobox}<br /><br /></td>
      </tr>
    </table><br />
    {/if}


    <form action="editnetworkserver.php?id={$server_details[db].id}" method="post">
    <table border="0" cellpadding="1" cellspacing="0" width="400" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">
          {if $server_details[db].physical eq "Y"}
            {$server_details[db].description|stripslashes}
          {else}
            {$lang.editnetsrv_edit_ip}
          {/if}
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      {if $server_details[db].physical eq "Y"}
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/os/medium/{$server_details[db].os}.png" border="0" width="64" height="64" /><br />{$server_details[db].os|ucwords} Server</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      {else}
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/icons/network.png" border="0" width="64" height="64" /></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      
      {/if}
      
      <tr>
        <td align="right" class="description">{$lang.date_added}:&nbsp;</td>
        <td>{$server_details[db].date_added}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.available}:&nbsp;</td>
        <td>
          <select name="available" class="dropdown" style="width:170px">
            {if $server_details[db].available eq "Y"}
                <option value="Y" selected>{$lang.yes}</option>
                <option value="N">{$lang.no}</option>
            {else}
                <option value="Y">{$lang.yes}</option>
                <option value="N" selected>{$lang.no}</option>
            {/if}
          </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.ip_address}:&nbsp;</td>
        <td><input type="text" name="ip" class="textbox_important" style="width:170px" value="{$server_details[db].ip}"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      {if $server_details[db].physical eq "Y"}
      <tr>
        <td align="right" class="description">{$lang.description}:&nbsp;</td>
        <td><input type="text" name="description" class="textbox_normal" style="width:170px" value="{$server_details[db].description|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.location}:&nbsp;</td>
        <td><input type="text" name="location" class="textbox_normal" style="width:170px" value="{$server_details[db].location|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.datacenter}:&nbsp;</td>
        <td><input type="text" name="datacenter" class="textbox_normal" style="width:170px" value="{$server_details[db].datacenter|stripslashes}"></td>
      </tr>
      
      {if $server_details[db].os eq "linux"}
      <tr>
        <td align="right" class="description">{$lang.version}:&nbsp;</td>
        <td>
          <select name="os_flavor" class="dropdown" style="width:170px">
            {if $server_details[db].linux_flavor eq "generic"}
                <option value="generic" selected>Generic Linux</option>
                <option value="arch">Arch</option>
                <option value="centos">CentOS</option>
                <option value="debian">Debian</option>
                <option value="fedora">Fedora</option>
                <option value="gentoo">Gentoo</option>
                <option value="ubuntu">Ubuntu</option>
            {elseif $server_details[db].linux_flavor eq "arch"}
                <option value="generic">Generic Linux</option>
                <option value="arch" selected>Arch</option>
                <option value="centos">CentOS</option>
                <option value="debian">Debian</option>
                <option value="fedora">Fedora</option>
                <option value="gentoo">Gentoo</option>
                <option value="ubuntu">Ubuntu</option>
            {elseif $server_details[db].linux_flavor eq "centos"}
                <option value="generic">Generic Linux</option>
                <option value="arch">Arch</option>
                <option value="centos" selected>CentOS</option>
                <option value="debian">Debian</option>
                <option value="fedora">Fedora</option>
                <option value="gentoo">Gentoo</option>
                <option value="ubuntu">Ubuntu</option>
            {elseif $server_details[db].linux_flavor eq "debian"}
                <option value="generic">Generic Linux</option>
                <option value="arch">Arch</option>
                <option value="centos">CentOS</option>
                <option value="debian" selected>Debian</option>
                <option value="fedora">Fedora</option>
                <option value="gentoo">Gentoo</option>
                <option value="ubuntu">Ubuntu</option>
            {elseif $server_details[db].linux_flavor eq "fedora"}
                <option value="generic">Generic Linux</option>
                <option value="arch">Arch</option>
                <option value="centos">CentOS</option>
                <option value="debian">Debian</option>
                <option value="fedora" selected>Fedora</option>
                <option value="gentoo">Gentoo</option>
                <option value="ubuntu">Ubuntu</option>
            {elseif $server_details[db].linux_flavor eq "gentoo"}
                <option value="generic">Generic Linux</option>
                <option value="arch">Arch</option>
                <option value="centos">CentOS</option>
                <option value="debian">Debian</option>
                <option value="fedora">Fedora</option>
                <option value="gentoo" selected>Gentoo</option>
                <option value="ubuntu">Ubuntu</option>
            {elseif $server_details[db].linux_flavor eq "ubuntu"}
                <option value="generic">Generic Linux</option>
                <option value="arch">Arch</option>
                <option value="centos">CentOS</option>
                <option value="debian">Debian</option>
                <option value="fedora">Fedora</option>
                <option value="gentoo">Gentoo</option>
                <option value="ubuntu" selected>Ubuntu</option>
            {else}
                <option value="generic" selected>Generic Linux</option>
                <option value="arch">Arch</option>
                <option value="centos">CentOS</option>
                <option value="debian">Debian</option>
                <option value="fedora">Fedora</option>
                <option value="gentoo">Gentoo</option>
                <option value="ubuntu">Ubuntu</option>
            {/if}
          </select>
        </td>
      </tr>
      {/if}

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.editnetsrv_conn_settings}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/icons/secure.png" border="0" width="64" height="64" /></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.addnetsrv_conn_user}:&nbsp;</td>
        <td><input type="text" name="conn_user" class="textbox_normal" style="width:170px" value="{$server_details[db].conn_user|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addnetsrv_conn_pass}:&nbsp;</td>
        <td><input type="text" name="conn_pass" class="textbox_normal" style="width:170px" value="{$server_details[db].conn_pass|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addnetsrv_conn_port}:&nbsp;</td>
        <td><input type="text" name="conn_port" class="textbox_normal" style="width:170px" value="{$server_details[db].conn_port|stripslashes}"></td>
      </tr>
      {/if}
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="update" value=" " class="button_save" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    <input type="hidden" name="physical" value="{$server_details[db].physical}">
    </form>
    
    {if $server_details[db].physical eq "Y"}
    
    <br /><br /><br />
    
    <center>
    <b>{$lang.editnetsrv_avail_ips}:</b>
    </center>
    
    <br />

    <form action="editnetworkserver.php?id={$server_details[db].id}" method="post">
    <table border="0" cellpadding="2" cellspacing="0" width="300" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" width="20">&nbsp;</td>
        <td align="center" width="30">&nbsp;</td>
        <td align="center" width="120">{$lang.ip_address}</td>
        <td align="center" width="100">{$lang.available}</td>
      </tr>
      
    {if $ips}
        {section name=ipaddr loop=$ips}
          <tr class="normal" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
            <td align="center" width="20"><input type="checkbox" name="del_srv[]" value="{$ips[ipaddr].id}" /></td>
            <td align="center" width="30" onClick="window.location='editnetworkserver.php?id={$ips[ipaddr].id}'"><img src="templates/{$template}/img/icons/network-20px.png" border="0" height="20" width="20" /></td>
            <td align="center" onClick="window.location='editnetworkserver.php?id={$ips[ipaddr].id}'">{$ips[ipaddr].ip|default:'&nbsp;'}</td>
            <td align="center" onClick="window.location='editnetworkserver.php?id={$ips[ipaddr].id}'">
              {if $ips[ipaddr].available eq "Y"}
                <font color="green">{$lang.yes}</font>
              {else}
                <font color="red">{$lang.no}</font>
              {/if}
            </td>
          </tr>
        {/section}
    {else}
      <tr>
        <td colspan="5" align="center">{$lang.none}</td>
      </tr>
    {/if}
    </table>
    
    <br />
    
    {if $ips}
    <center>
    <input type="submit" name="delete" value=" " class="button_delete" />
    </center>
    </form>
    {/if}
    
    
    <br /><br />
    
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
      <tr align="center">
        <td align="center"><a href="addnetworkserver.php?id={$server_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/network.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.editnetsrv_add_ip}</span></a></td>
      </tr>
    </table>
    
    <br /><br />
    {/if}
    
    {* All icons for this page and theme *}
    <table border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
        <td>
            {section name=page loop=$icons}
                <table border="0" cellpadding="12" cellspacing="5" align="left">
                  <tr>
                    <td align="center">
                        <a href="{$icons[page].href}?id={$server_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/{$icons[page].image}" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$icons[page].image_text}</a></span>
                    </td>
                  </tr>
                </table>
            {/section}
        </td>
      </tr>
    </table>
    {/section}
      


    {include file="$template/footer.tpl"}

{/if}
