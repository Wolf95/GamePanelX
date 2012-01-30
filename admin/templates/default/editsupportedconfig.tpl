{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {section name=db loop=$cfg_details}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="supportedservers.php">{$lang.suppsrv_title}</a> / <a href="managesupportedserver.php?id={$cfg_details[db].srvid}">{$lang.managesuppsrv_title}</a> / <a href="supportedserverconfigs.php?id={$cfg_details[db].srvid}">{$lang.suppsrvconf_title}</a> / {$lang.editsuppconfig_title}
    
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


    {literal}
    <script type="text/javascript">
    <!--
    function confirmation()
    {
        var answer = confirm("{/literal}{$lang.editsuppconfig_confirm_delete}{literal}")
        if (answer)
        {
            {/literal}
            window.location = "editsupportedconfig.php?id={$cfg_details[db].id}&srvid={$srvid}&action=delete"
            {literal}
        }
    }
    //-->
    </script>
    {/literal}
    
    
    <center>
    {$lang.editsuppconfig_config_details}
    </center>
    
    <br /><br />
    
    <form action="editsupportedconfig.php?id={$cfg_details[db].id}" method="post">
    <table border="0" cellpadding="1" cellspacing="0" width="550" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$cfg_details[db].name}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/icons/info.png" width="64" height="64" border="0" /><br /><b>{$cfg_details[db].long_name|stripslashes}</b></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.editsuppconfig_filename}:&nbsp;</td>
        <td><input type="text" name="file" class="textbox_normal" style="width:280px" value="{$cfg_details[db].name|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.editsuppconfig_dir_path}:&nbsp;</td>
        <td><input type="text" name="dir" class="textbox_normal" style="width:280px" value="{$cfg_details[db].dir|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.description}:&nbsp;</td>
        <td><input type="text" name="description" class="textbox_normal" style="width:280px" value="{$cfg_details[db].description|stripslashes}"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 1:&nbsp;</td>
        <td><input type="text" name="rmcmd1" class="textbox_normal" style="width:280px" value="{$cfg_details[db].rmcmd1|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 2:&nbsp;</td>
        <td><input type="text" name="rmcmd2" class="textbox_normal" style="width:280px" value="{$cfg_details[db].rmcmd2|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 3:&nbsp;</td>
        <td><input type="text" name="rmcmd3" class="textbox_normal" style="width:280px" value="{$cfg_details[db].rmcmd3|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 4:&nbsp;</td>
        <td><input type="text" name="rmcmd4" class="textbox_normal" style="width:280px" value="{$cfg_details[db].rmcmd4|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 5:&nbsp;</td>
        <td><input type="text" name="rmcmd5" class="textbox_normal" style="width:280px" value="{$cfg_details[db].rmcmd5|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 6:&nbsp;</td>
        <td><input type="text" name="rmcmd6" class="textbox_normal" style="width:280px" value="{$cfg_details[db].rmcmd6|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 7:&nbsp;</td>
        <td><input type="text" name="rmcmd7" class="textbox_normal" style="width:280px" value="{$cfg_details[db].rmcmd7|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 8:&nbsp;</td>
        <td><input type="text" name="rmcmd8" class="textbox_normal" style="width:280px" value="{$cfg_details[db].rmcmd8|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 9:&nbsp;</td>
        <td><input type="text" name="rmcmd9" class="textbox_normal" style="width:280px" value="{$cfg_details[db].rmcmd9|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsrvconf_forbidden_cmd} 10:&nbsp;</td>
        <td><input type="text" name="rmcmd10" class="textbox_normal" style="width:280px" value="{$cfg_details[db].rmcmd10|stripslashes}"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="update" value=" " class="button_save" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="right" style="padding:4px"><a href="#" onClick="javascript:confirmation()">{$lang.editsuppconfig_delete_config}</a></td>
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
    {/section}
      


    {include file="$template/footer.tpl"}

{/if}
