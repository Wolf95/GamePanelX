{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.cfg_title}
    
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
    
    
    <form action="configuration.php" method="post" name="testconnection">
    <table border="0" cellpadding="1" cellspacing="0" width="500" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.cfg_details}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/icons/network.png" width="64" height="64" border="0" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.version}:&nbsp;</td>
        <td><b>{$config_version}</b></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.language}:&nbsp;</td>
        <td>
          <select name="lang" style="width:225px">
            {section name=langz loop=$languages}
                {if $config_language eq $languages[langz]}
                    <option value="{$languages[langz]}" selected>{$languages[langz]|ucwords}</option>
                {else}
                    <option value="{$languages[langz]}">{$languages[langz]|ucwords}</option>
                {/if}
            {/section}
          </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.cfg_docroot}:&nbsp;</td>
        <td><input type="text" name="docroot" class="textbox_normal" style="width:220px" value="{$config_docroot}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.cfg_company}:&nbsp;</td>
        <td><input type="text" name="company" class="textbox_normal" style="width:220px" value="{$config_company}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.cfg_template}:&nbsp;</td>
        <td><input type="text" name="template" class="textbox_normal" style="width:220px" value="{$config_template}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.cfg_prim_email}:&nbsp;</td>
        <td><input type="text" name="prim_email" class="textbox_normal" style="width:220px" value="{$config_email_pri}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.cfg_sec_email}:&nbsp;</td>
        <td><input type="text" name="sec_email" class="textbox_normal" style="width:220px" value="{$config_email_sec}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.cfg_server_timeout}:&nbsp;</td>
        <td><input type="text" name="query_timeout" class="textbox_normal" style="width:220px" value="{$config_server_qt}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.cfg_remote_timeout}:&nbsp;</td>
        <td><input type="text" name="remote_timeout" class="textbox_normal" style="width:220px" value="{$config_rm_srv_timeout}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.cfg_start_after_create}:&nbsp;</td>
        <td>
          <select name="start_sv_after_create">
            {if $config_start_sv_after_create eq "Y"}
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
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.cfg_billing_sys}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.cfg_billing_srv_limit}:&nbsp;</td>
        <td><input type="text" name="server_limit" class="textbox_normal" style="width:220px" value="{$config_billing_serverlimit}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.cfg_billing_load_limit}:&nbsp;</td>
        <td><input type="text" name="load_limit" class="textbox_normal" style="width:220px" value="{$config_billing_loadlimit}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.cfg_billing_default_ports}:&nbsp;</td>
        <td>
          <select name="default_ports_only">
            {if $config_billing_defports eq "Y"}
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
        <td align="right" class="description">{$lang.cfg_api_key}:&nbsp;</td>
        <td><input type="text" name="api_key" class="textbox_important" style="width:220px" value="{$api_key}" readonly></td>
      </tr>
      
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="2" align="center"><input type="submit" name="submit" value=" " class="button_save" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    </form>

    {include file="$template/footer.tpl"}

{/if}
