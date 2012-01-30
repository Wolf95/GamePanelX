<?php
$url_networkid    = mysql_real_escape_string($_GET['networkid']);
$url_cfgid        = mysql_real_escape_string($_GET['cfgid']);
$url_userid       = mysql_real_escape_string($_GET['userid']);
$url_description  = mysql_real_escape_string($_GET['description']);

if(empty($url_networkid))
{
    die('Empty network ID!  Please try again.');
}

########################################################################
# port,max_slots,map,

// Get server defaults
$result_df  = @mysql_query("SELECT port,type,short_name,log_file,executable,working_dir,setup_dir FROM cfg WHERE id = '$url_cfgid'");

while($row_df = mysql_fetch_array($result_df))
{
    $def_port     = $row_df['port'];
    $def_type     = $row_df['type'];
    $def_short_nm = $row_df['short_name'];
    $def_log_file = $row_df['log_file'];
    $def_exe      = $row_df['executable'];
    $def_work_dir = $row_df['working_dir'];
    $def_set_dir  = $row_df['setup_dir'];
    $def_map      = $row_df['map'];
}


/*
* DEPRECATED for the below query
*
// Get IP simpleID
$result_ip  = @mysql_query("SELECT id FROM cfg_items WHERE srvid = '$url_cfgid' AND simpleid = '1' ORDER BY id DESC LIMIT 0,1");
$row_ip     = mysql_fetch_row($result_ip);
$cfgid_ip   = $row_ip[0];
*/

// Get cfgid's for each of the 4 simple id's
$result_spid  = @mysql_query("SELECT id,simpleid FROM cfg_items WHERE srvid = '$url_cfgid' AND simpleid IN (1,2,3,4) ORDER BY simpleid ASC");

while($row_spid = mysql_fetch_array($result_spid))
{
    $this_cfgid = $row_spid['id'];
    $this_smpid = $row_spid['simpleid'];
    
    // IP Address
    if($this_smpid == 1)
    {
        $cfgid_ip = $this_cfgid;
    }
    // Port
    elseif($this_smpid == 2)
    {
        $cfgid_port = $this_cfgid;
    }
    // Max Slots
    elseif($this_smpid == 3)
    {
        $cfgid_maxslots = $this_cfgid;
    }
    // Map
    elseif($this_smpid == 4)
    {
        $cfgid_map  = $this_cfgid;
    }
}

########################################################################


// Array for config inserts
$config_arr = array();

// Run through cfg_x items
foreach($_GET as $cfg => $cfg_val)
{
    // Simple ID's
    if(preg_match("/^smptxt_\d+$/", $cfg))
    {
        // Remove "cfg_", to get a valid ItemID
        $cfg  = str_replace('smptxt_', '', $cfg);
        
        // Port
        if($cfg == '2')
        {
            $server_port = $cfg_val;
            
            // Add to insert array (There is no value in `servers_cfg` for these, use respective `servers` values for SimpleID's
            $config_arr[] = "INSERT INTO servers_cfg (srvid,itemid) VALUES('%SRVID%','$cfgid_port')";
        }
        // Max Slots
        elseif($cfg == '3')
        {
            $server_max_slots = $cfg_val;
            
            // Add to insert array
            $config_arr[] = "INSERT INTO servers_cfg (srvid,itemid) VALUES('%SRVID%','$cfgid_maxslots')";
        }
        // Map
        elseif($cfg == '4')
        {
            $server_map = $cfg_val;
            
            // Add to insert array
            $config_arr[] = "INSERT INTO servers_cfg (srvid,itemid) VALUES('%SRVID%','$cfgid_map')";
        }
    }
    
    
    // Generic cfg_x items only
    elseif(preg_match("/^cfg_\d+$/", $cfg))
    {
        // Remove "cfg_", to get a valid ItemID
        $cfg  = str_replace('cfg_', '', $cfg);
        
        // Only if there's both an ID and a value
        if(!empty($cfg) && !empty($cfg_val))
        {
            // Add to insert array; later, foreach through it and mysql_query it
            $config_arr[] = "INSERT INTO servers_cfg (srvid,itemid,item_value) VALUES('%SRVID%','$cfg','$cfg_val')";
        }
    }
}

// Add IP into the mix
$config_arr[] = "INSERT INTO servers_cfg (srvid,itemid) VALUES('%SRVID%','$cfgid_ip')";

########################################################################

// Swap for defaults if there are no cmd-line options
if(empty($server_port))
{
    // Check if this default port is used
    $result_used  = @mysql_query("SELECT id FROM servers WHERE port = '$def_port' AND networkid = '$url_networkid' LIMIT 0,1");
    $row_used     = mysql_fetch_row($result_used);
    $port_used    = $row_used[0];
    
    // Use this port
    if(empty($port_used))
    {
        $server_port = $def_port;
    }
    // Try incrementing
    else
    {
        $server_port  = $def_port + 2;
        
        // Check if used
        $result_used  = @mysql_query("SELECT id FROM servers WHERE port = '$server_port' AND networkid = '$url_networkid' LIMIT 0,1");
        $row_used     = mysql_fetch_row($result_used);
        $port_used    = $row_used[0];
        
        if(!empty($port_used)) die('Unable to find a usable port, since default port was taken.');
    }
}

// Max Slots
if(empty($server_max_slots)) $server_max_slots = '8';

########################################################################

// Check required
if($def_type == 'game' && empty($server_map)) die('The Map field was empty.  Please double-check and try again');
#elseif(empty($server_port)) die('The Port field was empty.  Please double-check and try again');
#elseif(empty($server_max_slots)) die('The Max Slots field was empty.  Please double-check and try again');


$server_port      = mysql_real_escape_string($server_port);
$server_max_slots = mysql_real_escape_string($server_max_slots);
$server_map       = mysql_real_escape_string($server_map);

// Insert server
@mysql_query("INSERT INTO servers 
                (userid,networkid,port,max_slots,date_created,type,server,description,map,log_file,executable,working_dir,setup_dir) 
              VALUES('$url_userid','$url_networkid','$server_port','$server_max_slots',NOW(),'$def_type','$def_short_nm','$url_description','$server_map','$def_log_file','$def_exe','$def_work_dir','$def_set_dir')") or die('Failed to insert the server!');

$this_serverid  = mysql_insert_id();

if(empty($this_serverid))
{
    die('Didnt find a server ID!  Bailing out!');
}

########################################################################

// Insert each config value as a new row
foreach($config_arr as $query)
{
    $query  = str_replace('%SRVID%', $this_serverid, $query);
    
    // Run the insert query
    @mysql_query($query) or die('Failed to insert the config item: '.mysql_error());
}

########################################################################

// Get IP from this Network ID (for cmd line)
$result_p   = @mysql_query("SELECT ip FROM network WHERE id = '$url_networkid'");
$row_p      = mysql_fetch_row($result_p);
$server_ip  = $row_p[0];

// Begin CMD Line
$cmd_line = './' . $def_exe;

// Get this all server's current config items
$server_query = "SELECT 
                    servers_cfg.item_value,
                    cfg_items.simpleid,
                    cfg_items.name 
                 FROM servers_cfg 
                 LEFT JOIN cfg_items ON 
                    servers_cfg.itemid = cfg_items.id 
                 WHERE 
                    servers_cfg.srvid = '$this_serverid' 
                    AND servers_cfg.deleted = '0' 
                 ORDER BY 
                    cfg_items.simpleid ASC,
                    servers_cfg.item_order ASC";

$result_srv = @mysql_query($server_query) or die('Failed to get current adv config items');

while($row_srv  = mysql_fetch_array($result_srv))
{
    $cfg_name     = $row_srv['name'];
    $cfg_simpleid = $row_srv['simpleid'];
    $cfg_val      = $row_srv['item_value'];
    
    // IP Address
    if($cfg_simpleid == 1)
    {
        $cfg_val  = $server_ip;
    }
    // Port
    elseif($cfg_simpleid == 2)
    {
        $cfg_val  = $server_port;
    }
    // Max Slots
    elseif($cfg_simpleid == 3)
    {
        $cfg_val  = $server_max_slots;
    }
    // Map
    elseif($cfg_simpleid == 4)
    {
        $cfg_val  = $server_map;
    }
    
    
    // Add to command-line
    if(!empty($cfg_name) && !empty($cfg_val))
    {
        $cmd_line .= ' ' . $cfg_name . ' ' . $cfg_val;
    }
}

// Save new cmd line
@mysql_query("UPDATE servers SET cmd_line = '$cmd_line' WHERE id = '$this_serverid'");

########################################################################

//
// Create on Remote Server
//


// Probably coming from the API
if(!defined('GPX_DOCROOT'))
{
    require('../include/functions/remote.php');
}
// Normal pages
else
{
    require(GPX_DOCROOT . '/include/functions/remote.php');
}

/*
* Dont care for now...fix startup later
*
// Check if we should start the server after creation
if(!defined('GPX_CFG_START_SRV_AFTER_CREATE'))
{
    $start_server = true;
}
else
{
    if(GPX_CFG_START_SRV_AFTER_CREATE == 'Y')
    {
        $start_server = true;
    }
    else
    {
        $start_server = false;
    }
}
*/

// Create server
$result_create  = gpx_remote_create_server($this_serverid,false);

// Print result
if($result_create == 'success')
{
    if($def_type == 'game')
    {
        echo 'success_game';
    }
    else
    {
        echo 'success_voip';
    }
}
else echo $result_create;

?>
