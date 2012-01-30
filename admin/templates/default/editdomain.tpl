{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {section name=db loop=$domain_details}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.editdom_title}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
      
    <br /><br />


    {literal}
    <script type="text/javascript">
    <!--
    function confirmDelete()
    {
        var answer = confirm("{/literal}{$lang.editdom_confirm_delete}{literal}")
        if (answer)
        {
            {/literal}
            window.location = "editdomain.php?action=delete&id={$domainid}"
            {literal}
        }
    }
    //-->
    </script>
    {/literal}
    
    
    {if $infobox}
    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="infobox">
      <tr>
        <td align="center"><br />{$infobox}<br /><br /></td>
      </tr>
    </table><br />
    {/if}



    <form action="editdomain.php?id={$domain_details[db].id|stripslashes}" method="post">
    <table border="0" cellpadding="1" cellspacing="0" width="400" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.editdom_title}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td align="right" class="description">{$lang.domain}:&nbsp;</td>
        <td><input type="text" name="domain" class="textbox_normal" style="width:170px" value="{$domain_details[db].domain|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.ip_address}:&nbsp;</td>
        <td><input type="text" name="ip" class="textbox_normal" style="width:170px" value="{$domain_details[db].ip|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.adddom_mx_rec}:&nbsp;</td>
        <td><input type="text" name="mx" class="textbox_normal" style="width:170px" value="{$domain_details[db].mx|stripslashes}"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="update" value=" " class="button_save" /></td>
      </tr>
      
      <tr>
        <td colspan="2" align="right" style="padding-right:4px"><a href="javascript:void(0)" onClick="return confirmDelete()"><b>{$lang.delete}</b></a></td>
      </tr>
    </table>
    </form>
    
    <br /><br />
    
    
    <table border="0" cellpadding="1" cellspacing="0" width="500" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.editdom_latest_servers}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      {if $subdomains}
        {section name=sub loop=$subdomains}
        <tr>
          <td style="padding-left:5px">{$subdomains[sub].subdomain|stripslashes}.{$domain_details[db].domain|stripslashes} ({$subdomains[sub].ip|stripslashes}:{$subdomains[sub].port|stripslashes})</td>
          <td><a href="manageserver.php?id={$subdomains[sub].id|stripslashes}">{$lang.view}</a></td>
        </tr>
        {/section}
      {else}
        <tr>
          <td colspan="2" align="center">{$lang.none}</td>
        </tr>
      {/if}
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    
    
    {* All icons for this page and theme *}
    <table border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
        <td>
            {section name=page loop=$icons}
                <table border="0" cellpadding="12" cellspacing="5" align="left">
                  <tr>
                    <td align="center">
                        <a href="{$icons[page].href}?id={$user_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/{$icons[page].image}" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$icons[page].image_text}</a></span>
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
