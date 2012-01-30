<?php
//
// GamePanelX Pro Logging
//
class Log
{
    // Log server actions
    public function addlog($typeid,$userid,$serverid)
    {
        $typeid   = mysql_real_escape_string($typeid);
        $userid   = mysql_real_escape_string($userid);
        $serverid = mysql_real_escape_string($serverid);
        
        // Get user type
        if(isset($_SESSION['gpx_isadmin']))
        {
            $usrtype  = 'admin';
        }
        else $usrtype  = 'user';
        
        // Insert activity
        if(mysql_query("INSERT INTO activity_log (userid,relid,typeid,user_type,date_added) VALUES('$userid','$serverid','$typeid','$usrtype',NOW())"))
        {
            return 'success';
        }
        else
        {
            return 'failed';
        }
    }
}
?>
