<?php
//
// Save a config file in the file manager, after editing
//
if(isset($_POST['submit']))
{
    ####################################################################
    
    require('../config.php');
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Couldnt connect to the db');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Couldnt select the db');
    
    // Check if admin
    if($_SESSION['gpx_isadmin'] == 1) $is_admin = true;
    else $is_admin  = false;
    
    ####################################################################
    
    if(empty($_POST['id']))
    {
        die('<center><b>Error:</b> No server ID given!</center>');
    }

    $url_id     = mysql_real_escape_string($_POST['id']);
    $file_path  = $_SESSION['file_prev'];
    $url_text   = $_POST['text'];
    
    // Make sure this user has access to this server
    if(!$is_admin)
    {
        $this_userid  = $_SESSION['gpx_userid'];
        $result_ac  = @mysql_query("SELECT id FROM servers WHERE id = '$url_id' AND userid = '$this_userid'");
        $row_ac     = mysql_fetch_row($result_ac);
        
        if(!$row_ac[0]) die('You do not have access to this server.  Please contact your host.');
    }

    ########################################################################

    require(GPX_DOCROOT.'/include/functions/remote.php');

    if(gpx_remote_file_edit($url_id,$file_path,$url_text))
    {
        echo 'success';
    }
    else
    {
        die('failed');
    }
}
else
{
    die("Not set");
}

?>