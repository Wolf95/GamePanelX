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
$smarty->assign('pagetitle', 'Add Template');

########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>adddomain.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>adddomain.php</i>: Failed to select the database!</center>');

########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);


########################################################################


//
// First Page
//
if(!isset($_POST['update']))
{
    // Display HTML Page
    $smarty->display($config['Template'] . '/adddomain.tpl'); 
}

########################################################################


elseif(isset($_POST['update']))
{
    // POST Values (escape all of them)
    $post_domain  = $_POST['domain'];
    $post_ip      = $_POST['ip'];
    $post_mx      = $_POST['mx'];
    
    ####################################################################
    
    // Check for a valid domain name
    if(!preg_match("/^([a-z0-9]([-a-z0-9]*[a-z0-9])?\\.)+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]|travel)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])$/i", $post_domain))
    {
        die('Invalid domain');
    }
    
    ####################################################################
    
    //
    // Add the domain name to the database
    //
    require(GPX_DOCROOT . '/include/functions/domains.php');
    if(!gpx_dns_add($post_domain,$post_ip,$post_mx))
    {
        die('<b>Error:</b> Failed to add the domain!');
    }
    
    ####################################################################
    
    // Get the ID of the domain just added
    $result_id  = @mysql_query("SELECT id FROM domains WHERE domain = '$post_domain' AND ip = '$post_ip' AND mx = '$post_mx' ORDER BY id DESC LIMIT 0,1");
    
    while($row_id = mysql_fetch_array($result_id))
    {
        $this_domainid  = $row_id['id'];
    }
    
    ####
    
    // Build the DNS Zone File
    $result_rebuild = gpx_dns_rebuild_zone($this_domainid);
    
    if($result_rebuild != 'success')
    {
        // Build failed; delete from database
        @mysql_query("DELETE from domains WHERE id = '$this_domainid'");
        
        die($result_rebuild);
    }
    
    ####################################################################
    
    // Redirect to templates.php
    header("Location: domains.php?info=created");
    exit;
}
