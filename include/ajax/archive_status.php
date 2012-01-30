<?php
$template_id  = mysql_real_escape_string($_GET['id']);

// Check where we're at - Template status, Supported Server Install status
$result_st  = @mysql_query("SELECT status,installation_status FROM archives WHERE id = '$template_id'");
$row_st     = mysql_fetch_row($result_st);
$archive_status = $row_st[0];
$install_status = $row_st[1];


// Installation Status
if($install_status == 'running')
{
    require(GPX_DOCROOT.'/include/functions/supported.php');
    $result_check = trim(gpx_status_install_supported_server($template_id));
    
    // Steam percentages
    if(preg_match("/\d+\.\d+\%/", $result_check))
    {
        echo $result_check;
    }
    else
    {
        echo 'install_' . $result_check;
    }
    
    exit;
}

// Check Archive status
if($archive_status == 'running')
{
    // Get status of archive creation - running, complete, error
    require(GPX_DOCROOT.'/include/functions/templates.php');
    echo 'archive_' . gpx_template_status($template_id);
    exit;
}
else
{
    die('Unknown status');
}
?>
