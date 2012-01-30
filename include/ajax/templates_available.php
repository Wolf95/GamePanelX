<?php
// Get available templates for this Network ID

// Make sure user is an admin
if(!isset($_SESSION['gpx_isadmin']))
{
    die('<b>Error:</b> You are not authorized to view this page.');
}

####################################################################

// Use 'game' from the URL
$url_networkid  = $_GET['id'];
$url_type       = $_GET['type'];

// Make sure network id isn't empty
if(empty($url_networkid) || empty($url_type))
{
    die('<b>Ajax:</b> Empty networkid or type');
}

//
// Get all available templates for this Network ID
//

// Check if this Network ID is a parent or child IP
$result_parent  = @mysql_query("SELECT parentid,physical FROM network WHERE id = '$url_networkid'") or exit;

while($row_parent = mysql_fetch_array($result_parent))
{
    $this_parentid    = $row_parent['parentid'];
    $this_is_physical = $row_parent['physical'];
}

// Already physical.  Use it.
if($this_is_physical == 'Y')
{
    $this_networkid = $url_networkid;
}
// Non-physical.  Use the parent ID.
else
{
    $this_networkid = $this_parentid;
}

####################################################################

//
// Print HTML
//

// Include Language info
require_once('../languages/english.php'); // Language variables

// Start Select
echo '<select name="game" id="game" style="width:220px" onchange="showUser(this.value);document.server_details.create.style.display=\'\'">';

// Show empty option
echo '<option value="">Select a Server</option>';

####################################################################

// Get all available Game/Voice templates for this Network ID
$tpl_query = "SELECT 
                 cfg.long_name,
                 cfg.short_name 
               FROM cfg 
               LEFT JOIN templates ON 
                  cfg.short_name = templates.server 
               WHERE templates.networkid = '$this_networkid' 
                  AND templates.available = 'Y' 
                  AND templates.is_default = 'Y' 
                  AND templates.type = '$url_type' 
               ORDER BY cfg.long_name,cfg.short_name ASC 
               LIMIT 0,500";

$result_tpl = @mysql_query($tpl_query) or exit;
$num_tpl    = mysql_num_rows($result_tpl);

if($num_tpl >= 1)
{
    while($row_tpl = mysql_fetch_array($result_tpl))
    {
        // DB Values
        $cfg_long_name  = $row_tpl['long_name'];
        $cfg_short_name = $row_tpl['short_name'];
        
        // Show games
        echo '<option value="' . $cfg_short_name . '">' . $cfg_long_name . '</option>';
    }
}
else
{
    echo '<option value="">No templates found</option>';
}

// End everything
//echo '</select></td></tr></table>';

// End select
echo '</select>';

?>
