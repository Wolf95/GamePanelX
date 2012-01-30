{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="supportedservers.php">{$lang.suppsrv_title}</a> / <a href="managesupportedserver.php?id={$cfg_details[db].srvid}">{$lang.managesuppsrv_title}</a> / <a href="supportedserverconfigs.php?id={$cfg_details[db].srvid}">{$lang.suppsrvconf_title}</a> / {$lang.addsrvconf_title}
    
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
    
    
    <center>
    {$lang.addsrvconf_title}
    </center>
    
    <br /><br />
    
    <form action="addserverconfig.php?id={$serverid}" method="post">
    <table border="0" cellpadding="1" cellspacing="0" width="550" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addsrvconf_conf_details}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_filename}:&nbsp;</td>
        <td><input type="text" name="file" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_dir_path}:&nbsp;</td>
        <td><input type="text" name="dir" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.description}:&nbsp;</td>
        <td><input type="text" name="description" class="textbox_normal" style="width:280px"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 1:&nbsp;</td>
        <td><input type="text" name="rmcmd1" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 2:&nbsp;</td>
        <td><input type="text" name="rmcmd2" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 3:&nbsp;</td>
        <td><input type="text" name="rmcmd3" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 4:&nbsp;</td>
        <td><input type="text" name="rmcmd4" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 5:&nbsp;</td>
        <td><input type="text" name="rmcmd5" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 6:&nbsp;</td>
        <td><input type="text" name="rmcmd6" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 7:&nbsp;</td>
        <td><input type="text" name="rmcmd7" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 8:&nbsp;</td>
        <td><input type="text" name="rmcmd8" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 9:&nbsp;</td>
        <td><input type="text" name="rmcmd9" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 10:&nbsp;</td>
        <td><input type="text" name="rmcmd10" class="textbox_normal" style="width:280px"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="update" value=" " class="button_create" /></td>
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
