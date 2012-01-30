{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {section name=db loop=$server_templates}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="templates.php">{$lang.templates_title}</a> / <a href="managetemplate.php?id={$server_templates[db].id}">{$lang.managetpl_title}</a> / {$lang.edittpl_title}
    
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



    <form action="edittemplate.php?id={$server_templates[db].id}" method="post">
    <table border="0" cellpadding="1" cellspacing="0" width="500" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.edittpl_details}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.description}:&nbsp;</td>
        <td><input type="text" name="description" class="textbox_normal" style="width:170px" value="{$server_templates[db].description|stripslashes}"></td>
      </tr>

      <tr>
        <td align="right" class="description">{$lang.server}:&nbsp;</td>
        <td>
          <select name="server">
            {if $servers}
            {section name=sv loop=$servers}
              {if $server_templates[db].server eq $servers[sv].short_name}
                <option value="{$servers[sv].short_name}" selected>{$servers[sv].long_name}</option>
              {else}
                <option value="{$servers[sv].short_name}">{$servers[sv].long_name}</option>
              {/if}
            {/section}
            {else}
              <option value="">{$lang.none}</option>
            {/if}
          </select>
        </td>
      </tr>

      <tr>
        <td align="right" class="description">{$lang.available}:&nbsp;</td>
        <td>
          <select name="available">
              {if $server_templates[db].available eq "Y"}
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
        <td align="right" class="description">{$lang.edittpl_default_srv}:&nbsp;</td>
        <td>
          <select name="is_default">
              {if $server_templates[db].is_default eq "Y"}
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
        <td>{$server_templates[db].ip}</td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.edittpl_created}:&nbsp;</td>
        <td>{$server_templates[db].file_path|stripslashes}</td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.edittpl_tpl_hash}:&nbsp;</td>
        <td>{$server_templates[db].template_hash|stripslashes}</td>
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
    <input type="hidden" value="{$server_templates[db].ip}" />
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
    {/section}
      


    {include file="$template/footer.tpl"}

{/if}
