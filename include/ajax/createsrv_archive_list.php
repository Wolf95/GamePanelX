<?php
// Create Server: Take network id, get list of games with working archives to use
$url_networkid  = mysql_real_escape_string($_GET['id']);
$url_type       = $_GET['type'];

if($url_type != 'game' && $url_type != 'voip' && $url_type != 'other')
{
    $url_type = 'game';
}

########################################################################

// Get the parent server of this Network ID
$result_p = @mysql_query("SELECT physical,parentid FROM network WHERE id = '$url_networkid'");
$row_p    = mysql_fetch_row($result_p);
$net_physical = $row_p[0];
$net_parentid = $row_p[1];

// Use this for archives, otherwise use it's parent for archives
if($net_physical == 'Y') $net_id = $url_networkid;
else $net_id = $net_parentid;

########################################################################

// When the user selects a game, show game options
echo '<script type="text/javascript">
$(document).ready(function(){
    $("#game").change(function(){
        createServerGetOptions();
    });
});
</script>';

// List games with archives
$query_list = "SELECT DISTINCT
                  cfg.id,
                  cfg.long_name 
               FROM archives 
               LEFT JOIN cfg ON 
                  archives.cfgid = cfg.id 
               WHERE 
                  archives.status = 'complete' 
                  AND archives.is_default = '1' 
                  AND archives.networkid = '$net_id' 
                  AND cfg.type = '$url_type' 
               ORDER BY 
                  cfg.long_name ASC,
                  archives.id DESC";

$result_list  = @mysql_query($query_list);

echo '<b>Game:</b> <select id="game" class="dropdown">';
echo '<option value="" selected>Choose a Server</option>';

while($row_list = mysql_fetch_array($result_list))
{
    $cfg_id   = $row_list['id'];
    $cfg_name = $row_list['long_name'];
    
    echo '<option value="' . $cfg_id . '">' . $cfg_name . '</option>';
}

echo '</select>';

?>
