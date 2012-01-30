{if $logged_in}
    
    <div id="cmd_adv" style="margin-top:10px;">
        <div align="center">
            
            <span style="font-size:8pt;color:#666;">Click on the items in the Command-Line to edit them, or click their values in the Setting/Value section</span>
            
            <!-- <div id="trash"><img src="templates/default/img/icons/trash-64.png" border="0" /></div> -->
            
            <!-- <div class="items_title">Current Startup Items</div> -->
            
            <!-- <span style="font-size:9pt;color:#777;text-align:left;"><u>Current Startup Items</u></span> -->
            
            <div id="cfg_cur_items" style="margin-top:20px;">            
                <div class="cfg_row" id="curcfg_{$cfg_adv[adv].id}" style="border-bottom:1px solid #E0E0E0;">
                    <div class="cfg_name" style="color:#333;">Setting</div>
                    <div class="cfg_val" style="color:#333;">Value</div>
                    <div class="cfg_cled" style="color:#333;"></div>
                    <div class="cfg_rm"></div>
                </div>
                
                {section name=adv loop=$cfg_adv}
                            
                {if $cfg_adv[adv].simpleid eq "4"}
                <div class="cfg_row" id="curcfg_{$cfg_adv[adv].id}">
                    <div class="cfg_name">{$cfg_adv[adv].name}:</div>
                    <div class="cfg_val">
                        <input type="text" id="adv_map" value="{$srv_map}" class="input_txt" style="display:none;" />
                        <span class="spantxt_val" id="spanval_map" onClick="javascript:cmdToggleMap();">{$srv_map}</span>
                    </div>
                    
                    <div class="cfg_cled"></div>                
                    <div class="cfg_rm"></div>
                </div>
                
                {elseif $cfg_adv[adv].simpleid ne "1" && $cfg_adv[adv].simpleid ne "2" && $cfg_adv[adv].simpleid ne "3"}
                <div class="cfg_row" id="curcfg_{$cfg_adv[adv].id}">
                    <div class="cfg_name">{$cfg_adv[adv].name}:</div>
                    <div class="cfg_val">
                        <input type="text" id="cfg_{$cfg_adv[adv].id}" value="{$cfg_adv[adv].item_value}" class="input_txt" style="display:none;" /> 
                          <span class="spantxt_val" id="spanval_{$cfg_adv[adv].id}" onClick="javascript:cmdToggleItem({$cfg_adv[adv].id});">{$cfg_adv[adv].item_value}</span>
                    </div>
                    
                    <div class="cfg_cled"></div>                
                    <div class="cfg_rm"></div>
                </div>
                {/if}
                
                {/section}
            </div>
            
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
