<?php
//
// Add a current item from `cfg_items` to `servers_cfg`
//
$url_serverid = mysql_real_escape_string($_GET['id']);
$url_itemid   = mysql_real_escape_string($_GET['itemid']);

if(!is_numeric($url_serverid) || !is_numeric($url_itemid))
{
    die('Required values were left empty');
}

########################################################################

// Get default value for this item
$result_def = @mysql_query("SELECT name,default_value FROM cfg_items WHERE id = '$url_itemid'");
$row_def    = mysql_fetch_row($result_def);
$def_item_name  = $row_def[0];
$def_item_val   = $row_def[1];

// Add to `servers_cfg`
@mysql_query("INSERT INTO servers_cfg (srvid,itemid,item_value) VALUES('$url_serverid','$url_itemid','$def_item_val')");
#$this_new_id  = mysql_insert_id();


// Output new ID
#echo $this_new_id;

// Output complete row for display
echo '<div class="cfg_row" id="curcfg_' . $url_itemid . '">
                <div class="cfg_name">' . $def_item_name . ':</div>
                <div class="cfg_val">
                    <input type="text" id="cfg_' . $url_itemid . '" value="' . $def_item_val . '" class="input_txt" style="display:none;" /> 
                      <span style="font-size:9pt;color:#777;cursor:pointer;" onClick="$(this).hide();$(\'#cfg_' . $url_itemid . '\').show();">' . $def_item_val . '</span>
                </div>
                <div class="cfg_rm"></div>
            </div>';

?>