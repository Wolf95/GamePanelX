{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {section name=db loop=$user_details}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="clients.php">{$lang.editclient_nav_client_accounts}</a> / <a href="manageclient.php?id={$user_details[db].id}">{$lang.editclient_nav_manage_client}</a> / {$lang.editclient_nav_edit_account}</span>
    
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



    <form action="editclient.php?id={$user_details[db].id}" method="post">
    <table border="0" cellpadding="1" cellspacing="0" width="400" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.editclient_nav_edit_account}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.status}:&nbsp;</td>
        <td>
          <select name="status" class="dropdown" style="width:170px">
            {if $user_details[db].status eq "active"}
                <option value="active" selected>{$lang.status_active}</option>
                <option value="suspended">{$lang.status_suspended}</option>
                <option value="closed">{$lang.status_closed}</option>
            {elseif $user_details[db].status eq "suspended"}
                <option value="active">{$lang.status_active}</option>
                <option value="suspended" selected>{$lang.status_suspended}</option>
                <option value="closed">{$lang.status_closed}</option>
            {elseif $user_details[db].status eq "closed"}
                <option value="active">{$lang.status_active}</option>
                <option value="suspended">{$lang.status_suspended}</option>
                <option value="closed" selected>{$lang.status_closed}</option>
            {else}
                <option value=""></option>
                <option value="active" selected>{$lang.status_active}</option>
                <option value="suspended">{$lang.status_suspended}</option>
                <option value="closed">{$lang.status_closed}</option>
            {/if}
          </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.language}:&nbsp;</td>
        <td>
          <select name="language" style="width:170px">
            {section name=langz loop=$languages}
                {if $user_details[db].language eq $languages[langz]}
                    <option value="{$languages[langz]}" selected>{$languages[langz]|ucwords}</option>
                {else}
                    <option value="{$languages[langz]}">{$languages[langz]|ucwords}</option>
                {/if}
            {/section}
          </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.username}:&nbsp;</td>
        <td><input type="text" name="username" class="textbox_normal" style="width:170px" value="{$user_details[db].username|stripslashes}"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.password_new}:&nbsp;</td>
        <td><input type="password" name="new_password" class="textbox_normal" style="width:170px" value=""></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.password_confirm}:&nbsp;</td>
        <td><input type="password" name="new_password_confirm" class="textbox_normal" style="width:170px" value=""></td>
      </tr>
      <tr>
        <td colspan="2" align="center">{$lang.editclient_no_pass_change}</td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.first_name}:&nbsp;</td>
        <td><input type="text" name="first_name" class="textbox_normal" style="width:170px" value="{$user_details[db].first_name|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.middle_name}:&nbsp;</td>
        <td><input type="text" name="middle_name" class="textbox_normal" style="width:170px" value="{$user_details[db].middle_name|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.last_name}:&nbsp;</td>
        <td><input type="text" name="last_name" class="textbox_normal" style="width:170px" value="{$user_details[db].last_name|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.company}:&nbsp;</td>
        <td><input type="text" name="company" class="textbox_normal" style="width:170px" value="{$user_details[db].company|stripslashes}"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.email}:&nbsp;</td>
        <td><input type="text" name="email_address" class="textbox_normal" style="width:170px" value="{$user_details[db].email_address|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.phone}:&nbsp;</td>
        <td><input type="text" name="phone_number" class="textbox_normal" style="width:170px" value="{$user_details[db].phone_number|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.address}:&nbsp;</td>
        <td><input type="text" name="street_address1" class="textbox_normal" style="width:170px" value="{$user_details[db].street_address1|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.address2}:&nbsp;</td>
        <td><input type="text" name="street_address2" class="textbox_normal" style="width:170px" value="{$user_details[db].street_address2|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.city}:&nbsp;</td>
        <td><input type="text" name="city" class="textbox_normal" style="width:170px" value="{$user_details[db].city|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.state}:&nbsp;</td>
        <td><input type="text" name="state" class="textbox_normal" style="width:170px" value="{$user_details[db].state|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.country}:&nbsp;</td>
        <td><input type="text" name="country" class="textbox_normal" style="width:170px" value="{$user_details[db].country|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.zip}:&nbsp;</td>
        <td><input type="text" name="zip_code" class="textbox_normal" style="width:170px" value="{$user_details[db].zip_code|stripslashes}"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" colspan="2" class="description">
          Private Notes:<br />
          <textarea name="notes" style="width:95%;height:100px">{$user_details[db].notes|stripslashes}</textarea>
        </td>
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
