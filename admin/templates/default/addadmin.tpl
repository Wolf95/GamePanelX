{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="admins.php">{$lang.admins_nav_admin_accounts}</a> / {$lang.addadmin_nav_add_admin_account}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br /><br />
    
    
    {literal}
    <script language="JavaScript">
    document.adduser.username.style.display = 'none';
    </script>
    {/literal}
    
    <form action="addadmin.php" method="post" name="addadmin">
    <table border="0" cellpadding="1" cellspacing="0" width="400" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addadmin_title}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/icons/admin_add-64px.png" border="0" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.username}:&nbsp;</td>
        <td><input type="text" name="username" id="username" class="textbox_important" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.password}:&nbsp;</td>
        <td><input type="password" name="password" class="textbox_important" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.password_confirm}:&nbsp;</td>
        <td><input type="password" name="password_confirm" class="textbox_important" style="width:170px"></td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.first_name}:&nbsp;</td>
        <td><input type="text" name="first_name" class="textbox_normal" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.middle_name}:&nbsp;</td>
        <td><input type="text" name="middle_name" class="textbox_normal" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.last_name}:&nbsp;</td>
        <td><input type="text" name="last_name" class="textbox_normal" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.email}:&nbsp;</td>
        <td><input type="text" name="email_address" class="textbox_normal" style="width:170px"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td align="right" class="description">{$lang.language}:&nbsp;</td>
        <td>
          <select name="language" style="width:170px">
            {section name=langz loop=$languages}
                {if $languages[langz] == $default_language}
                    <option value="{$languages[langz]}" selected>{$languages[langz]|ucwords}</option>
                {else}
                    <option value="{$languages[langz]}">{$languages[langz]|ucwords}</option>
                {/if}
            {/section}
          </select>
        </td>
      </tr>

      <tr>
        <td align="right" class="description">{$lang.status}:&nbsp;</td>
        <td>
          <select name="status" class="dropdown" style="width:170px">
            <option value="active" selected>{$lang.status_active}</option>
            <option value="suspended">{$lang.status_suspended}</option>
          </select>
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" colspan="2" class="description">{$lang.private_notes}<br /><textarea name="notes" style="width:95%;height:100px"></textarea></td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="create" value=" " class="button_create" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    </form>

    {include file="$template/footer.tpl"}

{/if}
