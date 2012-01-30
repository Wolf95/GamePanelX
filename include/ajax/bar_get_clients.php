<?php

// Make sure user is an admin
if(!isset($_SESSION['gpx_isadmin']))
{
    die('<b>Error:</b> You are not authorized to view this page.');
}

####################################################################

// Require language support
require(GPX_DOCROOT . '/languages/' . $config['Language'] . '.php');

####################################################################

$result_cl  = @mysql_query("SELECT id,username,first_name,last_name FROM clients WHERE logged_in = 'Y' AND last_response > NOW() - INTERVAL 15 MINUTE");
$count_cl   = mysql_num_rows($result_cl);

// At least 1 online
if($count_cl >= 1)
{
    while($row_cl = mysql_fetch_array($result_cl))
    {
        $cl_id          = $row_cl['id'];
        $cl_username    = $row_cl['username'];
        $cl_first_name  = $row_cl['first_name'];
        $cl_last_lame   = $row_cl['last_name'];
        
        echo '<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="20" style="border-bottom:1px solid lightgrey">
                  <tr>
                    <td>';
        
        // Empty first/last name
        if(empty($cl_first_name))
        {
            $cl_first_name  = '<i>' . $lang[none] . '</i>';
        }
        else
        {
            // Trim it
            if(strlen($cl_first_name) > 10)
            {
                $cl_first_name  = substr($cl_first_name, 0, 10) . '..';
            }
        }
        if(empty($cl_last_lame))
        {
            $cl_last_lame  = '<i>' . $lang[none] . '</i>';
        }
        else
        {
            // Trim it
            if(strlen($cl_last_lame) > 10)
            {
                $cl_last_lame  = substr($cl_last_lame, 0, 10) . '..';
            }
        }

        
        // Output user info
        echo '<a href="manageclient.php?id=' . $cl_id . '">' . $cl_first_name . ' ' . $cl_last_lame . ' (' . $cl_username . ')</a>';
        echo '</td></tr></table>';
    }
}
// None online
else
{
    echo '<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="20" style="border-bottom:1px solid lightgrey">
          <tr>
            <td align="center">' . $lang[notify_none_online] . '</td>
          </tr>
          </table>';
}


?>
