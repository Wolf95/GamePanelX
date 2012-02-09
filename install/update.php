<?php
/*
This file is part of GamePanelX.

GamePanelX is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

GamePanelX is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GamePanelX.  If not, see <http://www.gnu.org/licenses/>.
*/
error_reporting(E_ERROR);

// Get version
require('version.php');
require('../include/config.php');

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> Failed to select the database!</center>');

$result_version =  @mysql_query("SELECT value FROM configuration WHERE setting = 'Version' LIMIT 0,1");
$row_version    = mysql_fetch_row($result_version);
$usr_version    = $row_version[0];
$gpx_version    = GPX_VERSION;

########################################################################

// Already up to date
if(GPX_VERSION == $usr_version && !isset($_GET['force']))
{
    die("You are already up to date with version $usr_version!<br /><br />To force an update, <a href=\"update.php?force=1\">click here</a>.  Otherwise, <a href=\"../admin/login.php\">click here to login.</a>");
}

//
// Update through the versions
//

// Update to 1.0.9
if($usr_version == '1.0.8' || $usr_version == '1.0.7' || isset($_GET['force']))
{
    // Changed in 1.0.9
    @mysql_query("ALTER TABLE configuration MODIFY `value` VARCHAR(255) NOT NULL") or die('Failed to update configuration: '.mysql_error());
    
    ########
    
    // Get srvid of TF2 (Changed in 1.0.9)
    $result_tfid  = @mysql_query("SELECT id FROM cfg WHERE short_name = 'tf2' ORDER BY id LIMIT 1");
    $row_tfid     = mysql_fetch_row($result_tfid);
    $tf_id        = $row_tfid[0];
    
    if($tf_id)
    {
        // Update default Team Fortress 2 game type (Changed in 1.0.9)
        @mysql_query("UPDATE cfg_items SET default_value = 'tf',description = 'Specify the Steam game type' WHERE srvid = '$tf_id' AND name = '-game' AND deleted = '0'");
    }
    
    // Output
    echo "<font color=red><b>Please make sure your \"Web Root\" is set correctly on the Configuration page as there was a recent bug!</b></font><br /><br />";
    
    // Set new version
    $usr_version  = '1.0.9';
}

// Update to 1.0.12
if($usr_version == '1.0.9' || $usr_version == '1.0.11')
{
    // Add HL2:DM Support
    @mysql_query("INSERT INTO `cfg` (`max_slots`, `port`, `date_added`, `last_updated`, `type`, `available`, `based_on`, `is_steam`, `is_punkbuster`, `notes`, `description`, `cmd_line`, `automagical`, `size`, `short_name`, `query_name`, `steam_name`, `long_name`, `mod_name`, `nickname`, `style`, `log_file`, `reserved_ports`, `tcp_ports`, `udp_ports`, `executable`, `map`, `setup_cmd`, `working_dir`, `setup_dir`, `config_file`, `pid_file`, `cfg_default`, `cfg_ip`, `cfg_port`, `cfg_max_slots`, `cfg_map`, `cfg_password`, `cfg_internet`) VALUES(64, 27015, '2012-01-19 18:58:04', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', 'Multiplayer Half-Life 2', './%executable% -game %mod_name% +map %map% +maxplayers %max_slots% -ip %ip% +port %port%', '', '', 'hl2_dm', 'halflife2', 'hl2mp', 'Half-Life 2: Deathmatch', 'hl2mp', 'HL2', 'FPS', '%working_dir%/', '27000,27020,27019', '1200,27000_27039', '1200,27000_27039', 'srcds_run', 'dm_lockdown', '', 'orangebox', '', 'server.cfg', '', '', '-ip', '-port', '+maxplayers', '+map', '+sv_password', '+sv_lan')") or die('Failed to add HL2:DM Support (1)');
    $new_id = mysql_insert_id();
    
    @mysql_query("INSERT INTO `cfg_items` (`srvid`, `deleted`, `required`, `client_edit`, `usr_def`, `simpleid`, `cmd_line`, `cfg_file`, `name`, `default_value`, `description`) VALUES($new_id, 0, 0, 0, 1, 0, 1, 0, '-ip', '', ''),($new_id, 0, 0, 0, 1, 2, 1, 0, '-port', '', ''),($new_id, 0, 0, 0, 1, 4, 1, 0, '+map', '', ''),($new_id, 0, 0, 0, 1, 3, 1, 0, '+maxplayers', '', '')") or die('Failed to add HL2:DM Support (2)');
    
    // Set new version
    $usr_version  = '1.0.12';
}

// Update to 1.0.14
if($usr_version == '1.0.12')
{
    // Remove 'admin/action.php' and 'admin/serverinfo.php' files; they are unused
    if(file_exists('../admin/action.php'))
    {
        if(!unlink('../admin/action.php')) die('Failed to remove the "admin/action.php" file.  Please remove it manually and try again.');
    }
    if(file_exists('../admin/serverinfo.php'))
    {
        if(!unlink('../admin/serverinfo.php')) die('Failed to remove the "admin/serverinfo.php" file.  Please remove it manually and try again.');
    }
    
    // Set new version
    $usr_version  = '1.0.14';
}

########################################################################

// Save new version
@mysql_query("UPDATE configuration SET value = '$gpx_version' WHERE setting = 'Version'");

// Output
echo "Update Successful!<br /><br /><a href=\"../admin/login.php\">Click here to login.</a>";

?>
