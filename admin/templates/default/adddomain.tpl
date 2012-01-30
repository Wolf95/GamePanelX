{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.adddom_title}</span>
    
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


    {if $tpl_id}
      <form action="adddomain.php?id={$tpl_id}" method="post">
    {else}
      <form action="adddomain.php" method="post">
    {/if}
    <table border="0" cellpadding="1" cellspacing="0" width="500" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.adddom_add_domain}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td align="right" class="description">{$lang.domain}:&nbsp;</td>
        <td><input type="text" name="domain" class="textbox_normal" style="width:220px" value="" /></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.ip_address}:&nbsp;</td>
        <td><input type="text" name="ip" class="textbox_normal" style="width:220px" value="" /></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.adddom_mx_rec}:&nbsp;</td>
        <td><input type="text" name="mx" class="textbox_normal" style="width:220px" value="" /></td>
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
                        <a href="{$icons[page].href}?id={$server_templates[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/{$icons[page].image}" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$icons[page].image_text}</a></span>
                    </td>
                  </tr>
                </table>
            {/section}
        </td>
      </tr>
    </table>


    {include file="$template/footer.tpl"}

{/if}
