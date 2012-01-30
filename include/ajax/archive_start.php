<?php
// Begin Template/Archive creation using session prev_dir

if(!isset($_SESSION['file_prev']) || empty($_SESSION['file_prev']))
{
    die('No directory found; please check the directory and try again.');
}

$file_path  = $_SESSION['file_prev'];

// Strip /// extra slashes off path
$file_path  = preg_replace("/\/+/", '/', $file_path);

// URL Values
$networkid    = $_GET['id'];
$cfgid        = $_GET['server'];
$is_default   = $_GET['default'];
$description  = $_GET['description'];

if($is_default == '1')
{
    $is_default = 'Y';
}
else $is_default = 'N';

########################################################################

// Create template
require(GPX_DOCROOT.'/include/functions/templates.php');

$result_create  = gpx_template_create($networkid,$cfgid,$is_default,$description,$file_path);

// Echo success or failure msg
echo $result_create;


?>
