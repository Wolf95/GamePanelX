{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="templates.php">{$lang.templates_title}</a> / {$lang.addtemplate_title}
    
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
      <form action="addtemplate.php?id={$tpl_id}" method="post">
    {else}
      <form action="addtemplate.php" method="post">
    {/if}
    <table border="0" cellpadding="1" cellspacing="0" width="500" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addtemplate_details}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      
      <tr>
        <td align="right" class="description">{$lang.server}:&nbsp;</td>
        <td>
          {if $tpl_server}
              {$tpl_long_name}
              <input type="hidden" name="server" value="{$tpl_server}" />
          {else}
              <select name="server" style="width:300px">
                {if $servers}
                {section name=sv loop=$servers}
                    <option value="{$servers[sv].short_name}">{$servers[sv].long_name}</option>
                {/section}
                {else}
                  <option value="">{$lang.none}</option>
                {/if}
              </select>
          {/if}
        </td>
      </tr>
      
            <tr>
        <td align="right" class="description">{$lang.network_server}:&nbsp;</td>
        <td>
          {if $tpl_networkid and $tpl_ip}
              {$tpl_description|stripslashes|default:'&nbsp;'} ({$tpl_ip})
              <input type="hidden" name="networkid" value="{$tpl_networkid}" />
          {else}
            <select name="networkid" style="width:300px">
              {if $network_servers}
              {section name=ns loop=$network_servers}
                  <option value="{$network_servers[ns].id}">
                    {if $network_servers[ns].description}
                        {$network_servers[ns].description|stripslashes|substr:0:25} ({$network_servers[ns].ip})
                    {else}
                        {$network_servers[ns].ip}
                    {/if}
                  </option>
              {/section}
              {else}
                  <option value="">{$lang.none}</option>
              {/if}
            </select>
          {/if}
        </td>
      </tr>
      
      
      <tr>
        <td align="right" class="description">{$lang.description}:&nbsp;</td>
        <td><input type="text" name="description" class="textbox_normal" style="width:220px" value="" /></td>
      </tr>
      
      {if $tpl_file_path}
          <input type="hidden" name="file_path" value="{$tpl_file_path}" />
      {else}
          <tr>
            <td align="right" class="description">{$lang.addtemplate_path_dir}:&nbsp;</td>
            <td><input type="text" name="file_path" class="textbox_normal" style="width:220px" value="" /></td>
          </tr>
      {/if}


      {if $tpl_type}
          <input type="hidden" name="template_type" value="{$tpl_type}" />
      {else}
          <tr>
            <td align="right" class="description">{$lang.addtemplate_tpl_type}:&nbsp;</td>
            <td>
              <select name="template_type">
                <option value="game" selected>{$lang.addtemplate_game}</option>
                <option value="voip">{$lang.addtemplate_voip}</option>
                <option value="other">{$lang.addtemplate_other}</option>
              </select>
            </td>
          </tr>
      {/if}
      
      <tr>
        <td align="right" class="description">{$lang.available}:&nbsp;</td>
        <td>
          <select name="available">
            <option value="Y" selected>{$lang.yes}</option>
            <option value="N">{$lang.no}</option>
          </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.addtemplate_default}:&nbsp;</td>
        <td>
          <select name="is_default">
            <option value="Y" selected>{$lang.yes}</option>
            <option value="N">{$lang.no}</option>
          </select>
        </td>
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
