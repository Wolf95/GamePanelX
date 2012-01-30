{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    
    <div align="center">
        <div id="tabs_full">
            <div class="tabs" id="tab_info" style="background:#E0E0E0;" onClick="javascript:loadServerTab('tab_serverinfo',{$server_details[db].id});">Server Defaults</div>
                <div class="tabspc"></div>
            <div class="tabs" id="tab_status" onClick="javascript:loadServerTab('tab_serverstatus',{$server_details[db].id});">Add Server Defaults</div>
                <div class="tabspc"></div>
            <div class="tabs" id="tab_editsrv" onClick="javascript:loadServerTab('tab_serveredit',{$server_details[db].id});">Edit Server</div>
                <div class="tabspc"></div>
            <div class="tabs" id="tab_files" onClick="javascript:loadServerTab('tab_serverfiles',{$server_details[db].id});">Files</div>
                <div class="tabspc"></div>
            <div class="tabs" id="tab_startup" onClick="javascript:loadServerTab('tab_serverstartup',{$server_details[db].id});">Startup</div>
        </div>
    </div>
    
        
    <div align="center">
        <div id="info"></div>
    </div>
    
    <div id="content"></div>
    
    
    {include file="$template/footer.tpl"}

{/if}
