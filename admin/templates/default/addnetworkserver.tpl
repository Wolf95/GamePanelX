{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="network.php">{$lang.network}</a> / {$lang.addnetsrv_title}
    
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


    {if $parentid}
      <form action="addnetworkserver.php?id={$parentid}" method="post">
    {else}
      <form action="addnetworkserver.php" method="post">
    {/if}
    
    <table border="0" cellpadding="1" cellspacing="0" width="400" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">
        
        {if $is_physical}
          {$lang.addnetsrv_title}
        {else}
          {$lang.addnetsrv_title}
        {/if}
        
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/icons/network.png" border="0" width="64" height="64" /></td>
      </tr>
      
     {if $is_physical}
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.os}:&nbsp;</td>
        <td>
          <select name="os" class="dropdown" style="width:170px">
            <option value="linux" selected>Linux</option>
            <option value="windows">Windows</option>
            <option value="other">Other</option>
          </select>
        </td>
      </tr>
      {/if}
      
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.available}:&nbsp;</td>
        <td>
          <select name="available" class="dropdown" style="width:170px">
            <option value="Y" selected>{$lang.yes}</option>
            <option value="N">{$lang.no}</option>
          </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.ip_address}:&nbsp;</td>
        <td><input type="text" name="ip" class="textbox_important" style="width:170px" value=""></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      {if $is_physical}
      <tr>
        <td align="right" class="description">{$lang.description}:&nbsp;</td>
        <td><input type="text" name="description" class="textbox_normal" style="width:170px" value=""></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.location}:&nbsp;</td>
        <td><input type="text" name="location" class="textbox_normal" style="width:170px" value=""></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.datacenter}:&nbsp;</td>
        <td><input type="text" name="datacenter" class="textbox_normal" style="width:170px" value=""></td>
      </tr>


      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addnetsrv_conn_settings}</td>
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
        <td><input type="text" name="conn_user" class="textbox_important" style="width:170px" value=""></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addnetsrv_conn_pass}:&nbsp;</td>
        <td><input type="password" name="conn_pass" class="textbox_important" style="width:170px" value=""></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addnetsrv_conn_port}:&nbsp;</td>
        <td><input type="text" name="conn_port" class="textbox_important" style="width:170px" value="22"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      {/if}
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="create" value=" " class="button_create" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    <input type="hidden" name="parentid" value="{$parentid}">
    <input type="hidden" name="physical" value="{$is_physical}">
    </form>
    
    <br /><br />
    
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

    {include file="$template/footer.tpl"}

{/if}
