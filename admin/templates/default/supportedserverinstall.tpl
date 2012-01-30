{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="supportedservers.php">{$lang.suppsrv_title}</a> / {$lang.suppsrvinstall_title}</span>
    
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
    
    <br /><br />
    
    <form action="supportedserverinstall.php" method="post">
    <table border="0" cellpadding="1" cellspacing="0" width="550" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.suppsrvinstall_info}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center">This server installer is currently for <b>Steam</b> games only.</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>      
      
      
      <tr>
        <td align="right" class="description">{$lang.network_server}:&nbsp;</td>
        <td>
          <select name="networkid" style="width:300px">
          
              {if $avail_ips}
                  {section name=ip loop=$avail_ips}
                      <option value="{$avail_ips[ip].id}">
                        {if $avail_ips[ip].description}
                            {$avail_ips[ip].description|stripslashes|substr:0:25} ({$avail_ips[ip].ip})
                        {else}
                            {$avail_ips[ip].ip}
                        {/if}
                      </option>
                  {/section}
              {else}
                  <option value="">{$lang.none}</option>
              {/if}

          </select>
        </td>
      </tr>
      
      
      <tr>
        <td align="right" class="description">{$lang.server}:&nbsp;</td>
        <td>
          <select name="server" style="width:300px">
          
              {if $servers}
                  {section name=db loop=$servers}
                      <option value="{$servers[db].id}">{$servers[db].long_name|stripslashes|default:'&nbsp;'}</option>
                  {/section}
              {else}
                  <option value="">{$lang.none}</option>
              {/if}

          </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.suppsrvinstall_filename}:&nbsp;</td>
        <td><b>hldsupdatetool.bin</b></td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.suppsrvinstall_uploaded}:&nbsp;</td>
        <td><input type="checkbox" name="uploaded" id="uploaded" value="1" /><label for="uploaded">&nbsp;{$lang.suppsrvinstall_agree} <i>~/uploads/</i></label></td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="install" value=" " class="button_create" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
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
                        <a href="{$icons[page].href}?id={$cfg_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/{$icons[page].image}" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$icons[page].image_text}</a></span>
                    </td>
                  </tr>
                </table>
            {/section}
        </td>
      </tr>
    </table>


    {include file="$template/footer.tpl"}

{/if}
