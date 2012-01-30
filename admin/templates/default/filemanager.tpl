{if $logged_in}

    {literal}
    <style type="text/css">
    #files
    {
        width: 550px;
        height: 60px;
        display: table;
        font-family: Arial;
        font-size: 9pt;
        color: #444;
        padding: 8px;
        padding-top: 20px;
    }
    </style>
    
    
    <script type="text/javascript">
    $(document).ready(function(){
        filesLoad({/literal}{$srvid}{literal},'','1');
    });
    </script>
    {/literal}
    
    <div align="center">
        <div id="files"></div>
    </div>

{/if}
