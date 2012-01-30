{if $logged_in}

    {literal}
    <style type="text/css">
    #encfile
    {
        width: 600px;
        height: 500px;
        display: table;
    }
    #file_content
    {
        width: 600px;
        height: 500px;
        background: #FFF;
        font-family: Verdana;
        font-size: 10pt;
        color: #333;
        border: 1px solid #999;
        padding: 10px;
        
        border-radius: 8px;
        -moz-border-radius: 8px;
        -webkit-border-radius: 8px;        
    }
    </style>
    {/literal}
    
    
    <div align="center">
        <div id="encfile">
            <span style="cursor:pointer;" onClick="javascript:filesLoad('{$srvid}','','0');"><img src="templates/{$template}/img/fm/back-64.png" width="32" height="32" border="0" /><br />{$lang.back}</span><br /><br />
            
            <textarea id="file_content">{$file_contents}</textarea>
            
            <input type="button" id="srv_button_update" value=" " class="button_save" onClick="javascript:saveTxtFile({$srvid});" />
        </div>
    </div>


{/if}