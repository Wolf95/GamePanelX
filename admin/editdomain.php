<?php
/*
 * GamePanelX Pro
 * Complete Game and Voice server management tool
 * 
 * Copyright(C) 2009-2010 GamePanelX Pro.  All Rights Reserved. 
 * 
 * Email: support@gamepanelx.com
 * Website: http://www.gamepanelx.com
 * 
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.                                                         
 *                                                                      
 * You may not reverse  engineer, decompile, defeat  license  encryption
 * mechanisms, or  disassemble this software product or software product
 * license.  GamePanelX Pro may terminate this license if you don't
 * comply with any of the terms and conditions set forth in our end user
 * license agreement (EULA).  In such event,  licensee  agrees to return
 * licensor  or destroy  all copies of software  upon termination of the
 * license.                                                             
 *                                                                      
 * Please see the EULA file for the full End User License Agreement.    
*/

//
// Smarty
//
require '../libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->compile_dir  = '../admin/templates_c/';

// Required Files
require('../include/auth.php');
require('../include/config.php');

// Page Title
$smarty->assign('pagetitle', 'Edit Domain');


########################################################################


// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editdomain.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editdomain.php</i>: Failed to select the database!</center>');

########################################################################

// ID from the URL
$url_id = mysql_real_escape_string($_GET['id']);

// Check malformed ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}

// Assign this ID to smarty
$smarty->assign('domainid', $url_id);

########################################################################


// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('updated');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Update Account
        if($url_info == 'updated')
        {
            $info_msg = 'Domain successfully updated!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}



########################################################################

// Actions from the URL
$url_action = mysql_real_escape_string($_GET['action']);

// List of allowed actions
$allowed_actions = array('delete');

// Correct action
if(!empty($url_action) && !in_array($url_action, $allowed_actions))
{
    die('<center><b>Error:</b> <i>editdomain.php:</i> Invalid URL Parameters!</center>');
}

// Delete the domain
if(!empty($url_action) && !empty($url_id))
{
    // Delete domain
    if($url_action == 'delete')
    {
        if($url_id > 0)
        {
            require(GPX_DOCROOT . '/include/functions/domains.php');
            if(!gpx_dns_delete($url_id))
            {
                die('<center><b>Error:</b> <i>editdomain.php:</i> Failed to delete the domain!</center>');
            }
        }
        
        // Show box on domains page
        header("Location: domains.php?info=deleted");
        exit;
    }
}

########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################


if(!isset($_POST['update']))
{
    //
    // Get domain
    //
    $result = @mysql_query("SELECT id,domain,ip,mx FROM domains WHERE id = '$url_id'") or die('<center><b>Error:</b> <i>editdomain.php:</i> Failed to list domains!</center>');

    // Smarty loop
    while ($line = mysql_fetch_assoc($result))
    {
        $value[] = $line;
    }

    // Smarty mysql loop
    $smarty->assign('domain_details', $value);
    
    ####################################################################
    
    //
    // Get list of servers with subdomains using this domain
    //
    $result_sub = @mysql_query("SELECT id,subdomain,ip,port FROM servers WHERE domainid = '$url_id' ORDER BY id DESC LIMIT 0,50");
    
    // Smarty loop
    while ($line_sub = mysql_fetch_assoc($result_sub))
    {
        $value_sub[] = $line_sub;
    }
    
    $smarty->assign('subdomains', $value_sub);
    
    ####################################################################
    
    // Get list of available languages
    require('languages.php');
    
    
    // Display HTML Page
    $smarty->display($config['Template'] . '/editdomain.tpl'); 
}

########################################################################


elseif(isset($_POST['update']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editdomain.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editdomain.php</i>: Failed to select the database!</center>');

    // Re-escape URL id
    $url_id = mysql_real_escape_string($url_id);
    
    // POST Values (escape all of them)
    $post_domain  = mysql_real_escape_string($_POST['domain']);
    $post_ip      = mysql_real_escape_string($_POST['ip']);
    $post_mx      = mysql_real_escape_string($_POST['mx']);
    
    ######################################################################
    
    // Check for a valid domain name
    if(!preg_match("/^([a-z0-9]([-a-z0-9]*[a-z0-9])?\\.)+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]|travel)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])$/i", $post_domain))
    {
        die('Invalid domain');
    }
    
    ######################################################################
    
    //
    // Update domain name
    //
    $this_userid  = $_SESSION['gpx_userid'];
    
    @mysql_query("UPDATE domains SET last_updated_by = '$this_userid',domain = '$post_domain',ip='$post_ip',mx='$post_mx',last_updated = NOW() WHERE id = '$url_id'");
    
    ######################################################################
    
    // Recreate the zone file
    require(GPX_DOCROOT . '/include/functions/domains.php');
    $result_rebuild  = gpx_dns_rebuild_zone($url_id);
    
    if($result_rebuild != 'success')
    {
        die($result_rebuild);
    }
    
    // Attempt to reload the domain in BIND/Named
    $reload_result = gpx_dns_reload_zone($url_id);
    
    if($reload_result != 'success')
    {
        die($reload_result);
    }
    
    ######################################################################
    
    // Redirect to editdomain.php
    header("Location: editdomain.php?id=$url_id&info=updated");
    exit;
}
