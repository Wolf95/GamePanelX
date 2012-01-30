{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {literal}
    <script type="text/javascript">
    $(document).ready(function(){
        // IP selection - show archives for that
        $('#ip').change(function(){
            var thisType = "{/literal}{$type}{literal}";
            createsrv_gamelist(thisType);
            
        });
        
        // Check/Uncheck option set
        $('#use_defaults').click(function(){
            $('#srv_options').fadeToggle();
        });
        
    });
    </script> 
    
    <style type="text/css">
    .dropdown
    {
        width: 200px;
        height: 30px;
        line-height: 30px;
        background: #FFF;
        border: 1px solid #999;
        
        border-radius: 6px;
        -moz-border-radius: 6px;
        -webkit-border-radius: 6px;
    }
    </style>
    {/literal}

    {* Location Links *}
        <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="servers.php?type={$type}">

    {if $type == 'game'}
      {$lang.managesrv_nav_game_srv}
    {elseif $type == 'voip'}
      {$lang.managesrv_nav_voice_srv}
    {elseif $type == 'other'}
      {$lang.managesrv_nav_other_srv}
    {else}
      {$lang.managesrv_nav_all_srv}
    {/if}
    
    </a> / {$lang.createsrv_title}
    
    </span>
    
    <br />

    <center>
    <img style="padding-bottom:8px" src="templates/{$template}/img/icons/add_{$type}server.png" name="icon_image" width="64" height="64" border="0"><br />
    {if $type eq "game"}
      {$lang.createsrv_gs}
    {elseif $type eq "game"}
      {$lang.createsrv_vs}
    {else}  
      {$lang.createsrv_title}
    {/if}
    </center>
    
    <br /><br />
    
    <form action="createserver.php" name="server_details" id="server_details" method="post">
    <table border="0" cellpadding="2" cellspacing="0" width="500" align="center">
      <tr>
        <td align="right" class="description" width="160">{$lang.edit_srv_owner}:&nbsp;</td>
        <td>
        
          <select id="userid" class="dropdown">
            {section name=users loop=$user_list}
              
              {if $user_list[users].id eq $client_id}
                <option value="{$user_list[users].id}" selected>{$user_list[users].username}</option>
              {else}
                <option value="{$user_list[users].id}">{$user_list[users].username}</option>
              {/if}
            
            {/section}
          </select>
        
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description" class="required">{$lang.edit_srv_ip}:&nbsp;</td>
        <td>
          <select id="ip" class="dropdown">
            <option value="">{$lang.createsrv_select_ip}</option>
              {if $avail_ips}
              {section name=ips loop=$avail_ips}
                <option value="{$avail_ips[ips].id}">{$avail_ips[ips].ip}</option>
              {/section}
              {else}
                <option value="">{$lang.createsrv_no_ips}</option>
              {/if}
          </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.edit_srv_description|stripslashes}:&nbsp;</td>
        <td><input type="text" id="description" class="textbox_normal" style="width:170px" value=""></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center">
            <div id="results"></div>
            <div id="srv_option" style="display:none;"><input type="checkbox" id="use_defaults" checked> <label for="use_defaults">Use default settings for this server</label></div>
            <div id="srv_options" style="display:none;"></div>
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="button" id="button_createsrv" value="Create Server" class="input_btn" onClick="javascript:createServer();" style="display:none;" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    <input type="hidden" id="port">
    <input type="hidden" name="id" value="{$server_details[db].id}">
    <input type="hidden" name="type" value="{$type}">
    <input type="text" name="text" value="{$type}" style="display:none" />
    </form>
    
    
    {include file="$template/footer.tpl"}

{/if}
