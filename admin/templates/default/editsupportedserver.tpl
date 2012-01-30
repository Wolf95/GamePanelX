{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {section name=db loop=$cfg_details}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="defaultservers.php">{$lang.suppsrv_title}</a> / <a href="managesupportedserver.php?id={$cfg_details[db].id}">{$lang.managesuppsrv_title}</a> / {$lang.editsuppsrv_title}</span>
    
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
    <script language="JavaScript" type="text/javascript">
    $(document).ready(function() {
        // Ports
        $('#portButton').click(function() {
             if ($('#portSection').is(":hidden"))
             {
                  $('#portSection').fadeIn('slow');
             } else {
                  $('#portSection').fadeOut('slow');
             }
             return false;
        });
        
        // Specifics
        $('#specificButton').click(function() {
             if ($('#specificSection').is(":hidden"))
             {
                  $('#specificSection').fadeIn('slow');
             } else {
                  $('#specificSection').fadeOut('slow');
             }
             return false;
        });
        
        // CFG Items
        $('#cfgButton').click(function() {
             if ($('#cfgSection').is(":hidden"))
             {
                  $('#cfgSection').fadeIn('slow');
             } else {
                  $('#cfgSection').fadeOut('slow');
             }
             return false;
        });
    });
    </script>
    {/literal}

    <center>
    {$lang.editsuppsrv_change_warning}
    </center>
    
    <br /><br />
    
    <form action="editsupportedserver.php?id={$cfg_details[db].id}" method="post">
    <table border="0" cellpadding="1" cellspacing="0" width="550" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.editsuppsrv_title}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/servers/medium/{$cfg_details[db].short_name|default:'default'}.png" width="64" height="64" border="0" /><br /><b>{$cfg_details[db].long_name|stripslashes}</b></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.editsuppsrv_basic_setup}</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td width="150" align="right" class="description">{$lang.available}:&nbsp;</td>
        <td>
          <select name="available" style="width:170px">
            {if $cfg_details[db].available eq "Y"}
              <option value="Y" selected>{$lang.yes}</option>
              <option value="N">{$lang.no}</option>
            {else}
              <option value="Y">{$lang.yes}</option>
              <option value="N" selected>{$lang.no}</option>
            {/if}
          </select></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.type}:&nbsp;</td>
        <td>
          <select name="type" style="width:170px">
            {if $cfg_details[db].type eq "game"}
              <option value="game" selected>{$lang.game_server}</option>
              <option value="voip">{$lang.voip_server}</option>
              <option value="other">{$lang.other}</option>
            {elseif $cfg_details[db].type eq "voip"}
              <option value="game">{$lang.game_server}</option>
              <option value="voip" selected>{$lang.voip_server}</option>
              <option value="other">{$lang.other}</option>
            {else}
              <option value="game">{$lang.game_server}</option>
              <option value="voip">{$lang.voip_server}</option>
              <option value="other" selected>{$lang.other}</option>
            {/if}
          </select>
        </td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.editsuppsrv_based_on}:&nbsp;</td>
        <td>
          <select name="based_on" style="width:170px">
            {if $cfg_details[db].based_on eq "cmd"}
              <option value="cmd" selected>{$lang.cmd_line}</option>
              <option value="cfg">{$lang.config_file}</option>
            {else}
              <option value="cmd">{$lang.cmd_line}</option>
              <option value="cfg" selected>{$lang.config_file}</option>
            {/if}
          </select>
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_steam}:&nbsp;</td>
        <td>
          <select name="is_steam" style="width:170px">
            {if $cfg_details[db].is_steam eq "Y"}
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
        <td align="right" class="description">{$lang.addsupsrv_pb}:&nbsp;</td>
        <td>
          <select name="is_punkbuster" style="width:170px">
            {if $cfg_details[db].is_punkbuster eq "Y"}
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
      
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_query_engine}:&nbsp;</td>
        <td>
          <select name="query_engine" style="width:280px">
              <option value="">{$lang.addsupsrv_select_engine}</option>
              
              {if $query_engines}
                {section name=eng loop=$query_engines}
                  {if $cfg_details[db].query_name eq $query_engines[eng].query_name}
                    <option value="{$query_engines[eng].query_name}" selected>{$query_engines[eng].long_name|stripslashes}</option>
                  {else}
                    <option value="{$query_engines[eng].query_name}">{$query_engines[eng].long_name|stripslashes}</option>
                  {/if}
                {/section}
              {else}
                <option value="">{$lang.none}</option>
              {/if}
              
          </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>{$lang.addsupsrv_select_desc}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addsupsrv_naming}</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_full_name}:&nbsp;</td>
        <td><input type="text" name="long_name" class="textbox_normal" style="width:280px" value="{$cfg_details[db].long_name|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_short_name}:&nbsp;</td>
        <td><input type="text" name="short_name" class="textbox_normal" style="width:280px" value="{$cfg_details[db].short_name|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_mod_name}:&nbsp;</td>
        <td><input type="text" name="mod_name" class="textbox_normal" style="width:280px" value="{$cfg_details[db].mod_name|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_steam_name}:&nbsp;</td>
        <td><input type="text" name="steam_name" class="textbox_normal" style="width:280px" value="{$cfg_details[db].steam_name|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_nickname}:&nbsp;</td>
        <td><input type="text" name="nickname" class="textbox_normal" style="width:280px" value="{$cfg_details[db].nickname|stripslashes}"></td>
      </tr>

      <tr>
        <td align="right" class="description">{$lang.description}:&nbsp;</td>
        <td><input type="text" name="description" class="textbox_normal" style="width:280px" value="{$cfg_details[db].description|stripslashes}"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      </div>
      
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addsupsrv_srv_spec}</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><br /><a href="#" id="specificButton">{$lang.editsuppsrv_show_opts}</a>
      
          <div id="specificSection" style="display:none;width:100%" align="center" width="100%">
            <table width="100%">
              <tr>
                <td align="right" class="description">{$lang.executable}:&nbsp;</td>
                <td><input type="text" name="executable" class="textbox_normal" style="width:280px" value="{$cfg_details[db].executable|stripslashes}"></td>
              </tr>
              <tr>
                <td align="right" class="description">{$lang.max_slots}:&nbsp;</td>
                <td><input type="text" name="max_slots" class="textbox_normal" style="width:280px" value="{$cfg_details[db].max_slots|stripslashes}"></td>
              </tr>
              <tr>
                <td align="right" class="description">{$lang.map}:&nbsp;</td>
                <td><input type="text" name="map" class="textbox_normal" style="width:280px" value="{$cfg_details[db].map|stripslashes}"></td>
              </tr>
              <tr>
                <td align="right" class="description">{$lang.style}:&nbsp;</td>
                <td><input type="text" name="style" class="textbox_normal" style="width:280px" value="{$cfg_details[db].style|stripslashes}"></td>
              </tr>
              <tr>
                <td align="right" class="description">{$lang.log_file}:&nbsp;</td>
                <td><input type="text" name="log_file" class="textbox_normal" style="width:280px" value="{$cfg_details[db].log_file|stripslashes}"></td>
              </tr>
              
              <tr>
                <td align="right" class="description">{$lang.working_dir}:&nbsp;</td>
                <td><input type="text" name="working_dir" class="textbox_normal" style="width:280px" value="{$cfg_details[db].working_dir|stripslashes}"></td>
              </tr>
              <tr>
                <td align="right" class="description">{$lang.config_file}:&nbsp;</td>
                <td><input type="text" name="config_file" class="textbox_normal" style="width:280px" value="{$cfg_details[db].config_file|stripslashes}"></td>
              </tr>
              <tr>
                <td align="right" class="description">{$lang.editsuppsrv_pid_file}:&nbsp;</td>
                <td><input type="text" name="pid_file" class="textbox_normal" style="width:280px" value="{$cfg_details[db].pid_file|stripslashes}"></td>
              </tr>
              <tr>
                <td align="right" class="description" valign="top">{$lang.setup_cmd}:&nbsp;</td>
                <td><textarea name="setup_cmd" style="width:95%;height:100px">{$cfg_details[db].setup_cmd|stripslashes}</textarea></td>
              </tr>
              <tr>
                <td align="right" class="description" valign="top">{$lang.edit_srv_cmd_line}:&nbsp;</td>
                <td><textarea name="cmd_line" style="width:95%;height:100px">{$cfg_details[db].cmd_line|stripslashes}</textarea></td>
              </tr>
            </table>
          </div>
      
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.ports}</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><br /><a href="#" id="portButton">{$lang.editsuppsrv_show_ports}</a>
        
        <div id="portSection" style="display:none;width:100%" align="center" width="100%">
          <table width="100%">
            <tr>
              <td width="150" align="right" class="description">{$lang.port}:&nbsp;</td>
              <td><input type="text" name="port" class="textbox_normal" style="width:280px" value="{$cfg_details[db].port|stripslashes}"></td>
            </tr>
            <tr>
              <td align="right" class="description">{$lang.reserved_ports}:&nbsp;</td>
              <td><input type="text" name="reserved_ports" class="textbox_normal" style="width:280px" value="{$cfg_details[db].reserved_ports|stripslashes}"></td>
            </tr>
            <tr>
              <td align="right" class="description">{$lang.tcp_ports}:&nbsp;</td>
              <td><input type="text" name="tcp_ports" class="textbox_normal" style="width:280px" value="{$cfg_details[db].tcp_ports|stripslashes}"></td>
            </tr>
            <tr>
              <td align="right" class="description">{$lang.udp_ports}:&nbsp;</td>
              <td><input type="text" name="udp_ports" class="textbox_normal" style="width:280px" value="{$cfg_details[db].udp_ports|stripslashes}"></td>
            </tr>
          </table>
        </div>
      
        </td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addsupsrv_config_opts}</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><br /><a href="#" id="cfgButton">{$lang.editsuppsrv_show_conf}</a>
      
          <div id="cfgSection" style="display:none;width:100%" align="center" width="100%">
            <table width="100%">
              <tr>
                <td align="right" class="description">{$lang.ip_address}:&nbsp;</td>
                <td><input type="text" name="cfg_ip" class="textbox_normal" style="width:280px" value="{$cfg_details[db].cfg_ip|stripslashes}"></td>
              </tr>
              <tr>
                <td align="right" class="description">{$lang.port}:&nbsp;</td>
                <td><input type="text" name="cfg_port" class="textbox_normal" style="width:280px" value="{$cfg_details[db].cfg_port|stripslashes}"></td>
              </tr>
              <tr>
                <td align="right" class="description">{$lang.max_slots}:&nbsp;</td>
                <td><input type="text" name="cfg_max_slots" class="textbox_normal" style="width:280px" value="{$cfg_details[db].cfg_max_slots|stripslashes}"></td>
              </tr>
              <tr>
                <td align="right" class="description">{$lang.map}:&nbsp;</td>
                <td><input type="text" name="cfg_map" class="textbox_normal" style="width:280px" value="{$cfg_details[db].cfg_map|stripslashes}"></td>
              </tr>
              <tr>
                <td align="right" class="description">{$lang.password}:&nbsp;</td>
                <td><input type="text" name="cfg_password" class="textbox_normal" style="width:280px" value="{$cfg_details[db].cfg_password|stripslashes}"></td>
              </tr>
              <tr>
                <td align="right" class="description">{$lang.internet}:&nbsp;</td>
                <td><input type="text" name="cfg_internet" class="textbox_normal" style="width:280px" value="{$cfg_details[db].cfg_internet|stripslashes}"></td>
              </tr>
            </table>
          </div>
      
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" colspan="2" class="description">
          {$lang.private_notes}:<br />
          <textarea name="notes" style="width:95%;height:100px">{$cfg_details[db].notes|stripslashes}</textarea>
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
