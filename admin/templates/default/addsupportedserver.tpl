{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.addsupsrv_title}</span>
    
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

    <center>
    {$lang.addsupsrv_topinfo}
    </center>
    
    <br /><br />
    
    <form action="addsupportedserver.php?id={$cfg_details[db].id}" method="post">
    <table border="0" cellpadding="1" cellspacing="0" width="550" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addsupsrv_basic_setup}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td width="150" align="right" class="description">{$lang.available}:&nbsp;</td>
        <td>
          <select name="available" style="width:170px">
              <option value="Y" selected>{$lang.yes}</option>
              <option value="N">{$lang.no}</option>
          </select></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.type}:&nbsp;</td>
        <td>
          <select name="type" style="width:170px">
              <option value="game" selected>{$lang.addsupsrv_gamesrv}</option>
              <option value="voip">{$lang.addsupsrv_voipsrv}</option>
              <option value="other">{$lang.addsupsrv_other}</option>
          </select>
        </td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_basedon}:&nbsp;</td>
        <td>
          <select name="based_on" style="width:170px">
              <option value="cmd" selected>{$lang.addsupsrv_cmd_line}</option>
              <option value="cfg">{$lang.addsupsrv_config_file}</option>
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
              <option value="Y">{$lang.yes}</option>
              <option value="N" selected>{$lang.no}</option>
          </select>
        </td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_pb}:&nbsp;</td>
        <td>
          <select name="is_punkbuster" style="width:170px">
              <option value="Y">{$lang.yes}</option>
              <option value="N" selected>{$lang.no}</option>
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
                  <option value="{$query_engines[eng].query_name}">{$query_engines[eng].long_name|stripslashes}</option>
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
        <td><input type="text" name="long_name" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_short_name}:&nbsp;</td>
        <td><input type="text" name="short_name" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_mod_name}:&nbsp;</td>
        <td><input type="text" name="mod_name" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_steam_name}:&nbsp;</td>
        <td><input type="text" name="steam_name" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsupsrv_nickname}:&nbsp;</td>
        <td><input type="text" name="nickname" class="textbox_normal" style="width:280px"></td>
      </tr>

      <tr>
        <td align="right" class="description">{$lang.description}:&nbsp;</td>
        <td><input type="text" name="description" class="textbox_normal" style="width:280px"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addsupsrv_srv_spec}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td align="right" class="description">{$lang.executable}:&nbsp;</td>
        <td><input type="text" name="executable" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.map}:&nbsp;</td>
        <td><input type="text" name="map" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.style}:&nbsp;</td>
        <td><input type="text" name="style" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.config_file}:&nbsp;</td>
        <td><input type="text" name="config_file" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.log_file}:&nbsp;</td>
        <td><input type="text" name="log_file" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.working_dir}:&nbsp;</td>
        <td><input type="text" name="working_dir" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.setup_dir}:&nbsp;</td>
        <td><input type="text" name="setup_dir" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description" valign="top">{$lang.setup_cmd}:&nbsp;</td>
        <td><textarea name="setup_cmd" style="width:95%;height:100px"></textarea></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addsupsrv_ports}</td>
      </tr>
     
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td width="150" align="right" class="description">{$lang.port}:&nbsp;</td>
        <td><input type="text" name="port" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.reserved_ports}:&nbsp;</td>
        <td><input type="text" name="reserved_ports" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.tcp_ports}:&nbsp;</td>
        <td><input type="text" name="tcp_ports" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.tcp_ports}:&nbsp;</td>
        <td><input type="text" name="udp_ports" class="textbox_normal" style="width:280px"></td>
      </tr>


      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addsupsrv_config_opts}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.ip_address}:&nbsp;</td>
        <td><input type="text" name="cfg_ip" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.port}:&nbsp;</td>
        <td><input type="text" name="cfg_port" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.max_slots}:&nbsp;</td>
        <td><input type="text" name="cfg_max_slots" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.map}:&nbsp;</td>
        <td><input type="text" name="cfg_map" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.password}:&nbsp;</td>
        <td><input type="text" name="cfg_password" class="textbox_normal" style="width:280px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.internet}:&nbsp;</td>
        <td><input type="text" name="cfg_internet" class="textbox_normal" style="width:280px"></td>
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
          <textarea name="notes" style="width:95%;height:100px"></textarea>
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
                        <a href="{$icons[page].href}?id={$cfg_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/{$icons[page].image}" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$icons[page].image_text}</a></span>
                    </td>
                  </tr>
                </table>
            {/section}
        </td>
      </tr>
    </table>


    {include file="$template/footer.tpl"}

{/if}
