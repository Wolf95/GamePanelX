{if $logged_in}
           
    <div style="margin-left:10px;width:600px;height:50px;">
        <span class="editor_txt" onClick="javascript:cmdEditSmp();">Basic Editor</span><br />
        <span class="editor_txt" onClick="javascript:cmdEditAdv();">Advanced Editor</span>
    </div>
    
    <div id="settings_desc" align="center" style="margin-bottom:10px;">
    These settings will change how the server restarts.
    </div>
    
    {literal}
    <script type="text/javascript">
    // Press Enter: Save Simple Startup
    $(document).ready(function(){
        $('#map').bind('keypress', function(e) {
            if(e.keyCode == 13){
                cmdSaveSimple({/literal}{$srvid}{literal});
            }
        });
    });
    </script>
    {/literal}
    
    
    
    <div id="cmd_smp">
        <table border="0" cellpadding="0" cellspacing="0" width="520" align="center">
        
        {section name=smp loop=$cfg_simple}
        {if $cfg_simple[smp].simpleid eq "3"}
        <tr>
          <td height="40" style="font-family:Arial;font-size:10pt;font-weight:bold;color:#666;">Max Players:</td>
          <td height="40" style="font-family:Arial;font-size:12pt;color:#666;">
              
              <select id="maxslots" class="input_select">
                {if $maxslots eq 32}
                    {section name=maxp loop=32} 
                        {if $srv_maxslots eq $smarty.section.maxp.iteration}
                          <option value="{$smarty.section.maxp.iteration}" selected>{$smarty.section.maxp.iteration}</option>
                        {else}
                          <option value="{$smarty.section.maxp.iteration}">{$smarty.section.maxp.iteration}</option>
                        {/if}
                    {/section}
                {elseif $maxslots gt 64}
                    {section name=maxp loop=128} 
                        {if $srv_maxslots eq $smarty.section.maxp.iteration}
                          <option value="{$smarty.section.maxp.iteration}" selected>{$smarty.section.maxp.iteration}</option>
                        {else}
                          <option value="{$smarty.section.maxp.iteration}">{$smarty.section.maxp.iteration}</option>
                        {/if}
                    {/section}
                {else}
                    {section name=maxp loop=64} 
                        {if $srv_maxslots eq $smarty.section.maxp.iteration}
                          <option value="{$smarty.section.maxp.iteration}" selected>{$smarty.section.maxp.iteration}</option>
                        {else}
                          <option value="{$smarty.section.maxp.iteration}">{$smarty.section.maxp.iteration}</option>
                        {/if}
                    {/section}
                {/if}
              </select>
              
          </td>
        </tr>
        {/if}
        {/section}
        
        {section name=smp loop=$cfg_simple}
        {if $cfg_simple[smp].simpleid eq "4"}
        <tr>
          <td width="160" height="30" style="font-family:Arial;font-size:10pt;font-weight:bold;color:#666;">Map:</td>
          <td height="30" style="font-family:Arial;font-size:12pt;color:#666;"><input type="text" id="map" value="{$srv_map}" class="input_txt" /></td>
        </tr>
        {/if}
        {/section}
        
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        
        <tr>
          <td colspan="2" align="center"><input type="button" id="savecmd_smp" value="Save" class="input_btn" onClick="javascript:cmdSaveSimple({$srvid});" /></td>
        </tr>
        </table>
    </div>
    
    
    <div id="cmd_adv" style="display:none;">
        <div align="center">
            
            <span style="font-size:8pt;color:#666;">Click on the items in the Command-Line to edit them, or click their values in the Setting/Value section</span>
                        
            <div id="cmd_line_cur">
                {section name=advz loop=$cfg_adv}
                    
                    {if $cfg_adv[advz].simpleid eq "1"}
                        <div class="cmdcl_item" id="curcmdbox_ip" onClick="javascript:cmdToggleIP();">{$cfg_adv[advz].name} {$srv_ip}</div>
                    {elseif $cfg_adv[advz].simpleid eq "2"}
                        <div class="cmdcl_item" id="curcmdbox_port" onClick="javascript:cmdTogglePort();">{$cfg_adv[advz].name} {$srv_port}</div>
                    {elseif $cfg_adv[advz].simpleid eq "3"}
                        <div class="cmdcl_item" id="curcmdbox_maxslots" onClick="javascript:cmdToggleMaxSlots();">{$cfg_adv[advz].name} {$srv_maxslots}</div>
                    {elseif $cfg_adv[advz].simpleid eq "4"}
                        <div class="cmdcl_item" id="curcmdbox_map" onClick="javascript:cmdToggleMap();">{$cfg_adv[advz].name} {$srv_map}</div>
                    {else}
                        <div class="cmdcl_item" id="curcmdbox_{$cfg_adv[advz].id}" onClick="javascript:cmdToggleItem({$cfg_adv[advz].id});">{$cfg_adv[advz].name} {$cfg_adv[advz].item_value}</div>
                    {/if}
                    
                {/section}
            </div>
            
            
            <div id="cfg_cur_items">
            
            
            <div class="cfg_row" id="curcfg_{$srvid}" style="border-bottom:1px solid #E0E0E0;">
                <div class="cfg_name" style="color:#333;">Setting</div>
                <div class="cfg_val" style="color:#333;">Value</div>
                <div class="cfg_cled" style="color:#333;">Client-Editable</div>
                <div class="cfg_rm"></div>
            </div>
            
            {section name=adv loop=$cfg_adv}
            
            {if $cfg_adv[adv].simpleid eq "1"}
            <div class="cfg_row" id="curcfg_{$cfg_adv[adv].id}">
                <div class="cfg_name">{$cfg_adv[adv].name}:</div>
                <div class="cfg_val">
                    <select id="adv_netid" class="input_select" style="display:none;">
                      {section name=ips loop=$network_ips}
                        {if $network_ips[ips].id eq $srv_networkid}
                          <option value="{$network_ips[ips].id}" selected>{$network_ips[ips].ip} - {$network_ips[ips].description} ({$network_ips[ips].location})</option>
                        {else}
                          <option value="{$network_ips[ips].id}">{$network_ips[ips].ip} - {$network_ips[ips].description} ({$network_ips[ips].location})</option>
                        {/if}
                      {/section}
                    </select>
                    <span class="spantxt_val" id="spanval_ip" onClick="javascript:cmdToggleIP();">{$srv_ip}</span>
                </div>
                
                <div class="cfg_cled"></div>                
                <div class="cfg_rm"></div>
            </div>
            
            {elseif $cfg_adv[adv].simpleid eq "2"}
            <div class="cfg_row" id="curcfg_{$cfg_adv[adv].id}">
                <div class="cfg_name">{$cfg_adv[adv].name}:</div>
                <div class="cfg_val">
                    <input type="text" id="adv_port" value="{$srv_port}" class="input_txt" style="display:none;" />
                    <span class="spantxt_val" id="spanval_port" onClick="javascript:cmdTogglePort();">{$srv_port}</span>
                </div>
                
                <div class="cfg_cled"></div>                
                <div class="cfg_rm"></div>
            </div>
            
            {elseif $cfg_adv[adv].simpleid eq "3"}
            <div class="cfg_row" id="curcfg_{$cfg_adv[adv].id}">
                <div class="cfg_name">{$cfg_adv[adv].name}:</div>
                <div class="cfg_val">
                    <input type="text" id="adv_maxslots" value="{$srv_maxslots}" class="input_txt" style="display:none;" />
                    <span class="spantxt_val" id="spanval_maxslots" onClick="javascript:cmdToggleMaxSlots();">{$srv_maxslots}</span>
                </div>
                
                <div class="cfg_cled"></div>                
                <div class="cfg_rm"></div>
            </div>
            
            {elseif $cfg_adv[adv].simpleid eq "4"}
            <div class="cfg_row" id="curcfg_{$cfg_adv[adv].id}">
                <div class="cfg_name">{$cfg_adv[adv].name}:</div>
                <div class="cfg_val">
                    <input type="text" id="adv_map" value="{$srv_map}" class="input_txt" style="display:none;" />
                    <span class="spantxt_val" id="spanval_map" onClick="javascript:cmdToggleMap();">{$srv_map}</span>
                </div>
                
                <div class="cfg_cled">
                    {if $cfg_adv[adv].client_edit}
                        <input type="checkbox" id="cfgcled_map" class="cl_ed" value="1" checked /> <label for="cfgcled_map">Editable</label>
                    {else}
                        <input type="checkbox" id="cfgcled_map" class="cl_ed" value="1" /> <label for="cfgcled_map">Editable</label>
                    {/if}
                </div>
                
                <div class="cfg_rm"></div>
            </div>
            
            {else}
            <div class="cfg_row" id="curcfg_{$cfg_adv[adv].id}">
                <div class="cfg_name">{$cfg_adv[adv].name}:</div>
                <div class="cfg_val">
                    <input type="text" id="cfg_{$cfg_adv[adv].id}" value="{$cfg_adv[adv].item_value}" class="input_txt" style="display:none;" /> 
                      <span class="spantxt_val" id="spanval_{$cfg_adv[adv].id}" onClick="javascript:cmdToggleItem({$cfg_adv[adv].id});">{$cfg_adv[adv].item_value}</span>
                </div>
                
                <div class="cfg_cled">
                    {if $cfg_adv[adv].client_edit}
                        <input type="checkbox" id="cfgcled_{$cfg_adv[adv].id}" class="cl_ed" value="1" checked /> <label for="cfgcled_{$cfg_adv[adv].id}">Editable</label>
                    {else}
                        <input type="checkbox" id="cfgcled_{$cfg_adv[adv].id}" class="cl_ed" value="1" /> <label for="cfgcled_{$cfg_adv[adv].id}">Editable</label>
                    {/if}
                </div>
                
                <div class="cfg_rm">
                    {if !$cfg_adv[adv].required}
                        <span style="font-size:8pt;color:#999;cursor:pointer;" onClick="javascript:cmdConfirmDelItem({$cfg_adv[adv].id},{$srvid});">(Remove)</span>
                      {else}
                        &nbsp;
                      {/if}
                </div>
            </div>
            {/if}
            
            {/section}
            </div>
            
            
            {* Available, unused items *}
            <div id="avail_items">
                
                <span style="font-size:9pt;font-weight:bold;color:#444;cursor:pointer;" onClick="javascript:$(this).hide();$('#avail_items_div').fadeIn();"><img src="templates/default/img/icons/list-64.png" border="0" width="24" height="24" />Show available Startup Items</span> &nbsp;&nbsp;&nbsp;<span style="font-size:9pt;font-weight:bold;color:#444;cursor:pointer;" onClick="javascript:cmdAddItem();"><img src="templates/default/img/icons/add-64.png" width="24" height="24" border="0" /> Click to add new startup item</span>
                
                <div id="avail_items_div" style="display:none;margin-top:10px;">
                    <div class="items_title">Available Startup Items</div>
                    
                    {section name=avl loop=$cfg_avail}
                    <div class="cfg_row" id="curcfgrow_{$cfg_avail[avl].id}">
                        <div class="cfg_name">{$cfg_avail[avl].name}:</div>
                        <div class="cfg_val">
                            <span style="font-size:9pt;color:#777;cursor:default;">{$cfg_avail[avl].default_value}</span>
                        </div>
                        <div class="cfg_rm">
                            {* Add to current server *}
                             <span style="font-size:8pt;color:#999;cursor:pointer;" onClick="javascript:cmdAddtoCur({$cfg_avail[avl].id},{$srvid});">(Add)</span> 
                            
                            {if $cfg_avail[avl].usr_def}
                                <span style="font-size:8pt;color:#999;cursor:pointer;">(Delete)</span>
                            {else}
                                &nbsp;
                            {/if}
                        </div>
                    </div>
                    {/section}
                </div>
            </div>
            {* /Available, unused items *}
            
            
            <table border="0" cellpadding="0" cellspacing="0" width="520" align="center" style="margin-top:10px;">
                <tr>
                  <td colspan="2">
                      
                      
                      <div id="addcfg_div" style="width:100%;height:40px;line-height:40px;display:none;"></div>
                  </td>
                </tr>
                
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
                
                <tr>
                  <td colspan="2" align="center"><input type="button" id="savecmd_adv" value="Save" class="input_btn" onClick="javascript:cmdSaveAdv({$srvid});" /></td>
                </tr>
            </table>
            <input type="hidden" id="addcfg_id" value="1" />
        </div>
    </div>
    
{/if}
