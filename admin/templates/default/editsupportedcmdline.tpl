{if $logged_in}
    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
        
    <br /><br />
    
    <div align="center">
        <div id="info" style="display:none;"></div>
    </div>
    
    <center>
    <img style="padding-bottom:8px" src="templates/{$template}/img/icons/edit_cmdline.png" name="icon_image" width="64" height="64" border="0"><br />
    {$lang.edit_cmdline_image_txt}
    </center>
    
    <br /><br />
    
    <div id="cmd_adv">
        <div align="center">
            
            <div id="cfg_cur_items">
            
            
            <div class="cfg_row" id="curcfg_{$cfg_avail[adv].id}" style="border-bottom:1px solid #E0E0E0;">
                <div class="cfg_name" style="color:#333;">Setting</div>
                <div class="cfg_val" style="color:#333;">Value</div>
                <div class="cfg_cled" style="color:#333;">Client-Editable</div>
                <div class="cfg_rm"></div>
            </div>
            
            {section name=adv loop=$cfg_avail}
            
            {* {if $cfg_avail[adv].simpleid ne "1" && $cfg_avail[adv].simpleid ne "2" && $cfg_avail[adv].simpleid ne "3" && $cfg_avail[adv].simpleid ne "4"} *}
            <div class="cfg_row" id="curcfg_{$cfg_avail[adv].id}">
                <div class="cfg_name">
                    {if $cfg_avail[adv].simpleid eq "1" || $cfg_avail[adv].simpleid eq "2" || $cfg_avail[adv].simpleid eq "3" || $cfg_avail[adv].simpleid eq "4"}
                        <font color="red">{$cfg_avail[adv].name}:</font>
                    {else}
                        {$cfg_avail[adv].name}:
                    {/if}
                </div>
                <div class="cfg_val">
                    <input type="text" id="cfg_{$cfg_avail[adv].id}" value="{$cfg_avail[adv].item_value}" class="input_txt" style="display:none;" /> 
                      <span class="spantxt_val" id="spanval_{$cfg_avail[adv].id}" onClick="javascript:cmdToggleItem({$cfg_avail[adv].id});">{$cfg_avail[adv].item_value}</span>
                </div>
                
                <div class="cfg_cled">
                    {if $cfg_avail[adv].client_edit}
                        <input type="checkbox" id="cfgcled_{$cfg_avail[adv].id}" value="1" checked /> <label for="cfgcled_{$cfg_avail[adv].id}">Editable</label>
                    {else}
                        <input type="checkbox" id="cfgcled_{$cfg_avail[adv].id}" value="1" /> <label for="cfgcled_{$cfg_avail[adv].id}">Editable</label>
                    {/if}
                </div>
                
                <div class="cfg_rm">
                    <span style="font-size:8pt;color:#999;cursor:pointer;" onClick="javascript:cmdConfirmDelItemSupp({$cfg_avail[adv].id},{$srvid});"> (Remove)</span>
                </div>
            </div>
            {* {/if} *}
            
            {/section}
            </div>
            
            <span style="font-size:9pt;font-weight:bold;color:#444;cursor:pointer;" onClick="javascript:cmdAddItem();"><img src="templates/default/img/icons/add-64.png" width="24" height="24" border="0" /> Click to add new startup item</span>
            
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
                  <td colspan="2" align="center"><input type="button" id="savecmd_adv" value="Save" class="input_btn" onClick="javascript:suppCmdSaveAdv({$srvid});" /></td>
                </tr>
            </table>
            <input type="hidden" id="addcfg_id" value="1" />
        </div>
    </div>
    
    {include file="$template/footer.tpl"}

{/if}
