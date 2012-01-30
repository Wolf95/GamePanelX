{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
        
    
    {literal}
    <script type="text/javascript">
    $(document).ready(function(){
        // Load info tab
        {/literal}
        {section name=db loop=$server_details}
        loadServerTab('tab_serverinfo',{$server_details[db].id});
        {literal}
        // Change clicked tabs color
        $('.tabs').click(function(){
            $('.tabs').css('background','#F1F1F1');
            $('#'+this.id).css('background','#E0E0E0');
        });
        
        
        $('#srv_status_txt').html('<span style="font-size:9pt;font-weight:normal;color:#777;">Checking ...</span>');
        serverQuery({/literal}{$server_details[db].id}{literal});
        setInterval("serverQuery({/literal}{$server_details[db].id}{literal});", 5000);
    });
    </script>
    {/literal}
        
    <div align="center">
        <div id="tabs_full">
            <div class="tabs" id="tab_info" style="background:#E0E0E0;" onClick="javascript:loadServerTab('tab_serverinfo',{$server_details[db].id});">Info</div>
                <div class="tabspc"></div>
            <div class="tabs" id="tab_status" onClick="javascript:loadServerTab('tab_serverstatus',{$server_details[db].id});">Status</div>
                <div class="tabspc"></div>
            <div class="tabs" id="tab_editsrv" onClick="javascript:loadServerTab('tab_serveredit',{$server_details[db].id});">Edit Server</div>
                <div class="tabspc"></div>
            <div class="tabs" id="tab_files" onClick="javascript:loadServerTab('tab_serverfiles',{$server_details[db].id});">Files</div>
                <div class="tabspc"></div>
            <div class="tabs" id="tab_startup" onClick="javascript:loadServerTab('tab_serverstartup',{$server_details[db].id});">Startup</div>
        </div>
    </div>
    

    <div id="srv_toprow">
        <div style="width:50%;height:30px;float:left;">
            <div id="srv_mng"><b>Managing</b> <i>{$server_details[db].ip}:{$server_details[db].port}</i></div>
        </div>
        <div style="width:50%;height:30px;float:left;">
            <div id="srv_status"><b>Server Status:</b> <span id="srv_status_txt"></span></div>
        </div>
    </div>
    
    <div align="center">
        <div id="info" style="display:none;"></div>
    </div>
    
    <div id="content"></div>
    {/section}    
    
    
    {include file="$template/footer.tpl"}

{/if}
