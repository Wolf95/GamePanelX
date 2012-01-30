<?php
// Get cmd-line when creating server

// Make sure user is an admin
if(!isset($_SESSION['gpx_isadmin']))
{
    die('<b>Error:</b> You are not authorized to view this page.');
}

####################################################################

// Use 'game' from the URL
$url_game = $_GET['game'];

// Make sure game isn't empty
if(empty($url_game))
{
    exit;
}


// Query for 'cmd_line' and all 10 config options
$game_query = "SELECT 
                 cfg.port,
                 cfg.log_file,
                 cfg.max_slots,
                 cfg.map,
                 cfg.executable,
                 cfg.working_dir,
                 cfg.setup_dir,
                 cfg.config_file,
                 cfg.pid_file,
                 cfg.cmd_line,
                 cfg_options.* 
               FROM cfg 
               LEFT JOIN cfg_options ON 
                 cfg.id = cfg_options.srvid 
               WHERE cfg.short_name = '$url_game'";

$result_game = @mysql_query($game_query) or exit;


while($row_game = mysql_fetch_array($result_game))
{
    // DB Values
    $game_port        = $row_game['port'];
    $game_log_file    = $row_game['log_file'];
    $game_max_slots   = $row_game['max_slots'];
    $game_map         = $row_game['map'];
    $game_exe         = $row_game['executable'];
    $game_work_dir    = $row_game['working_dir'];
    $game_set_dir     = $row_game['setup_dir'];
    $game_config_file = $row_game['config_file'];
    $game_pid_file    = $row_game['pid_file'];
    
    // Start table
    echo '<table border="0" cellpadding="1" cellspacing="0" width="400" align="center" class="tablez" style="border:none">';

    
    //
    // Game-related options
    //        
    require_once('../languages/english.php'); // Language variables
    
    echo '<tr>
      <td align="right" class="description">' . $lang[edit_srv_port] . ':&nbsp;</td>
      <td><input type="text" name="port" id="port" class="textbox_important" style="width:170px" value="' . $game_port . '"></td>
    </tr>
    <tr>
      <td align="right" class="description">' . $lang[edit_srv_log_file] . ':&nbsp;</td>
      <td align="left"><input type="text" name="log_file" class="textbox_normal" style="width:170px" value="' . $game_log_file . '"></td>
    </tr>
    <tr>
      <td align="right" class="description">' . $lang[edit_srv_max_slots] . ':&nbsp;</td>
      <td><input type="text" name="max_slots" id="max_slots" class="textbox_important" style="width:170px" value="' . $game_max_slots . '"></td>
    </tr>
    <tr>
      <td align="right" class="description">' . $lang[edit_srv_map] . ':&nbsp;</td>
      <td><input type="text" name="map" id="map" class="textbox_normal" style="width:170px" value="' . $game_map . '"></td>
    </tr>
    <tr>
      <td align="right" class="description">' . $lang[edit_srv_exe] . ':&nbsp;</td>
      <td><input type="text" name="executable" id="executable" class="textbox_important" style="width:170px" value="' . $game_exe . '"></td>
    </tr>

    <tr>
      <td align="right" class="description">' . $lang[edit_srv_working_dir] . ':&nbsp;</td>
      <td><input type="text" name="working_dir" class="textbox_normal" style="width:170px" value="' . $game_work_dir . '"></td>
    </tr>
    <tr>
      <td align="right" class="description">' . $lang[edit_srv_setup_dir] . ':&nbsp;</td>
      <td><input type="text" name="setup_dir" class="textbox_normal" style="width:170px" value="' . $game_set_dir . '"></td>
    </tr>
    
    <tr>
      <td align="right" class="description">Config File:&nbsp;</td>
      <td><input type="text" name="config_file" class="textbox_normal" style="width:170px" value="' . $game_config_file . '"></td>
    </tr>';
    echo '</table>';
    

    echo '<table border="0" cellpadding="1" cellspacing="0" width="400" align="center" class="tablez" style="border:none">';
    // SPACER
    echo '<tr><td colspan="4">&nbsp;</td></tr>';
    


    // Add the Command-Line
    $game_cmd_line = $row_game['cmd_line'];        
    echo '<tr><td colspan="4" align="center" class="description"><b>' . $lang[edit_srv_cmd_line] . ':</b><br /><textarea name="cmd_line" style="width:350px;height:100px">' . $game_cmd_line . '</textarea></td></tr>';

    // SPACER
    echo '<tr><td colspan="4">&nbsp;</td></tr>';
    
    // Row Titles
    echo '<tr>
            <td style="border-bottom:1px solid lightgrey;color:#555"><b>' . $lang[edit_srv_option] . '</b></td>
            <td style="border-bottom:1px solid lightgrey;color:#555"><b>' . $lang[description] . '</b></td>
            <td style="border-bottom:1px solid lightgrey;color:#555"><b>' . $lang[edit_srv_option_value] . '</b></td>
            <td style="border-bottom:1px solid lightgrey;color:#555" align="center"><b>' . $lang[edit_srv_option_cl_edit] . '</b></td>
          </tr>';
    
    // Add the options
    for($i = 1; $i <= 10; $i++)
    {
        // Names
        $this_name  = 'opt' . $i . '_name';
        $this_edit  = 'opt' . $i . '_edit';
        $this_value = 'opt' . $i . '_value';
        
        // Values
        $res_name   = $row_game[$this_name];
        $res_edit   = $row_game[$this_edit];
        $res_value  = $row_game[$this_value];
        
        // Output
        echo '<tr align="left">';
        echo '<td class="description">%opt' . $i . '%&nbsp;&nbsp;</td>';
        echo '<td><input type="text" name="opt' . $i . '_name" value="' . $res_name . '" style="width:120px"></td>';
        echo '<td><input type="text" name="opt' . $i . '_value" value="' . $res_value . '" style="width:120px"></td>';
        
        echo '<td class="description" align="center">';
        if($res_edit == 'Y')
        {
            echo '<input type="checkbox" name="opt' . $i . '_edit" id="opt' . $i . '_edit" checked><label for="opt' . $i . '_edit"> ' . $lang[yes] . '</label>';
        }
        else
        {
            echo '<input type="checkbox" name="opt' . $i . '_edit" id="opt' . $i . '_edit"><label for="opt' . $i . '_edit"> ' . $lang[yes] . '</label><br />';
        }
        echo '</td></tr>';
    }
    
    
    // End table
    echo '</table>';
}

?>
