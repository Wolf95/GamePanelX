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
require 'libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->compile_dir  = 'templates_c/';

// Required Files
require('include/authuser.php');
require('include/config.php');

########################################################################

// Page Title
$smarty->assign('pagetitle', 'Welcome');

//
// Redirect logged-in users
//
if(isset($_SESSION['gpx_username']) && !empty($_SESSION['gpx_username']))
{
    header("Location: main.php");
    exit;
}

//
// Force login for others
//
else
{
    header("Location: login.php");
    exit;
}

?>
