{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.accounts_nav_choose_type}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br /><br />
    
    <table border="0" cellpadding="2" cellspacing="0" width="600" align="center" class="tablez">
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td align="center"><a href="clients.php"><img src="templates/{$template}/img/icons/clients-64px.png" border="0" /><br />{$lang.accounts_client_acct}</a></td>
        <td align="center"><a href="admins.php"><img src="templates/{$template}/img/icons/admin-64px.png" border="0" /><br />{$lang.accounts_admin_acct}</a></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    
    <br /><br />

    {* All icons for this page and theme *}
    <table border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
        <td>
            {section name=page loop=$icons}
                <table border="0" cellpadding="12" cellspacing="5" align="left">
                  <tr>
                    <td align="center">
                        <a href="{$icons[page].href}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/{$icons[page].image}" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$icons[page].image_text}</a></span>
                    </td>
                  </tr>
                </table>
            {/section}
        </td>
      </tr>
    </table>

    {include file="$template/footer.tpl"}

{/if}
