<?php
$cfg_id = mysql_real_escape_string($_GET['id']);

if(empty($cfg_id))
{
    die('No game specified; unable to show options');
}

########################################################################

/*
* DEPRECATED for now; these are redundant considering all these are defined in the startup settings.
*
// Get default `cfg` settings for this game
$query_cfg  = "SELECT 
                  max_slots,
                  port,
                  executable,
                  map,
                  working_dir 
               FROM cfg 
               WHERE 
                  id = '$cfg_id'";

$result_cfg = @mysql_query($query_cfg);
$row_cfg    = mysql_fetch_row($result_cfg);

// Default values
$cfg_max_slots    = $row_cfg[0];
$cfg_port         = $row_cfg[1];
$cfg_exe          = $row_cfg[2];
$cfg_map          = $row_cfg[3];
$cfg_working_dir  = $row_cfg[4];

echo '<table border="0" cellpadding="2" cellspacing="0" align="center" width="500" style="margin-top:30px;border-top:1px solid #CCC;">
      <tr>
        <td colspan="2"><b>Basic Settings</b></td>
      </tr>
      
      <tr>
        <td width="150">Max Slots:</td>
        <td>
            <select id="max_slots" class="dropdown">';
              
              // Loop through slots
              for($i=1; $i <= $cfg_max_slots; $i++)
              {
                  echo '<option value="' . $i . '">' . $i . ' players</option>';
              }
              
            echo '</select>
        </td>
      </tr>
      
      <tr>
        <td>Port:</td>
        <td><input type="text" id="port" value="' . $cfg_port . '" class="textbox_normal" /></td>
      </tr>
      
      <tr>
        <td>Map:</td>
        <td><input type="text" id="map" value="' . $cfg_map . '" class="textbox_normal" /></td>
      </tr>
      
      <tr>
        <td>Executable:</td>
        <td><input type="text" id="exe" value="' . $cfg_exe . '" class="textbox_normal" /></td>
      </tr>
      
      <tr>
        <td>Working Directory:</td>
        <td><input type="text" id="working_dir" value="' . $cfg_working_dir . '" class="textbox_normal" /></td>
      </tr>
      </table>';
*/

########################################################################

// Check if cfg-based or cmd-based
$result_based = @mysql_query("SELECT based_on FROM cfg WHERE id = '$cfg_id'");
$row_based    = mysql_fetch_row($result_based);
$based_on     = $row_based[0];

// Based on cmd-line.  Loop through command-line startup options
if($based_on == 'cmd')
{
    // Get this all server's available
    $result = @mysql_query("SELECT 
                            id,
                            simpleid,
                            required,
                            name,
                            default_value,
                            description 
                         FROM cfg_items 
                         WHERE 
                            srvid = '$cfg_id' 
                            AND deleted = '0' 
                         ORDER BY 
                            name ASC") or die('Failed to get config items');
    
    echo '<div class="cfg_row" id="curcfg_{$cfg_adv[adv].id}" style="border-bottom:1px solid #E0E0E0;margin-top:10px;">
              <div class="cfg_name" style="color:#333;">Startup Setting</div>
              <div class="cfg_val" style="color:#333;">Startup Value</div>
              <div class="cfg_rm"></div>
          </div>';

    while($row  = mysql_fetch_array($result))
    {
        $cfg_id           = $row['id'];
        $cfg_simpleid     = $row['simpleid'];
        $cfg_required     = $row['required'];
        $cfg_name         = $row['name'];
        $cfg_value        = $row['default_value'];
        $cfg_description  = $row['description'];
        
        // Skip IP
        if($cfg_simpleid == 1)
        {
            continue;
        }
        
        // Store port for js use
        elseif($cfg_simpleid == 2)
        {
            /*
            echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#port").val(' . $cfg_value . ');
            });
            </script>';
            */
            
            echo '<div class="cfg_row" id="smpdiv_2">
                      <div class="cfg_name">' . $cfg_name . ':</div>
                      <div class="cfg_val"><input type="text" value="' . $cfg_value . '" class="textbox_normal" id="smptxt_2" /></div>
                  </div>';
        }
        // Max Slots
        elseif($cfg_simpleid == 3)
        {
            /*
            echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#port").val(' . $cfg_value . ');
            });
            </script>';
            */
            
            echo '<div class="cfg_row" id="smpdiv_3">
                      <div class="cfg_name">' . $cfg_name . ':</div>
                      <div class="cfg_val"><input type="text" value="' . $cfg_value . '" class="textbox_normal" id="smptxt_3" /></div>
                  </div>';
        }
        // Map
        elseif($cfg_simpleid == 4)
        {
            /*
            echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#port").val(' . $cfg_value . ');
            });
            </script>';
            */
            
            echo '<div class="cfg_row" id="smpdiv_4">
                      <div class="cfg_name">' . $cfg_name . ':</div>
                      <div class="cfg_val"><input type="text" value="' . $cfg_value . '" class="textbox_normal" id="smptxt_4" /></div>
                  </div>';
        }
        
        ###########################
        
        // All generic values
        else
        {
            echo '<div class="cfg_row">
                      <div class="cfg_name">' . $cfg_name . ':</div>
                      <div class="cfg_val"><input type="text" value="' . $cfg_value . '" class="textbox_normal" id="cfg_' . $cfg_id . '" /></div>
                  </div>';
        }
    }
}

// Config based; no startup options.  Get defaults, display them.
else
{
    // Get defaults for this server
    $result_def = @mysql_query("SELECT port,max_slots,map FROM cfg WHERE id = '$cfg_id'");
    $row_def    = mysql_fetch_row($result_def);
    
    $default_port     = $row_def[0];
    $default_maxslots = $row_def[1];
    $default_map      = $row_def[2];
    
    
    echo '<div class="cfg_row" id="curcfg_{$cfg_adv[adv].id}" style="border-bottom:1px solid #E0E0E0;margin-top:10px;">
              <div class="cfg_name" style="color:#333;">Default Setting</div>
              <div class="cfg_val" style="color:#333;">Default Value</div>
              <div class="cfg_rm"></div>
          </div>';
    
    // Port
    echo '<div class="cfg_row" id="smpdiv_2">
              <div class="cfg_name">Port:</div>
              <div class="cfg_val"><input type="text" value="' . $default_port . '" class="textbox_normal" id="smptxt_2" /></div>
          </div>';
    
    // Max Slots
    echo '<div class="cfg_row" id="smpdiv_3">
              <div class="cfg_name">Max Slots:</div>
              <div class="cfg_val"><input type="text" value="' . $default_maxslots . '" class="textbox_normal" id="smptxt_3" /></div>
          </div>';
    
    // Map
    echo '<div class="cfg_row" id="smpdiv_4">
              <div class="cfg_name">Map:</div>
              <div class="cfg_val"><input type="text" value="' . $default_map . '" class="textbox_normal" id="smptxt_4" /></div>
          </div>';
}

?>
