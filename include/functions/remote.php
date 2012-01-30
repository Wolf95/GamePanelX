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


/***********************************************************************
 * 
 *                     Remote Server Functions
 *
 *********************************************************************** 
*/

// For API Usage
if(!defined('GPX_DOCROOT'))
{
    define('GPX_DOCROOT', '../');
}

//
// Miscellaneous functions
//
require_once(GPX_DOCROOT . '/include/functions/remote/misc.php');

########################################################################


//
// Game/Voice Server functions
//
require_once(GPX_DOCROOT . '/include/functions/remote/servers.php');


########################################################################


//
// Game/Voice Template functions
//
require_once(GPX_DOCROOT . '/include/functions/remote/templates.php');


########################################################################


//
// Game/Voice Status functions (Restart/Stop)
//
require_once(GPX_DOCROOT . '/include/functions/remote/status.php');


########################################################################


//
// File Manager functions
//
require_once(GPX_DOCROOT . '/include/functions/remote/filemanager.php');


########################################################################


//
// Addon functions
//
require_once(GPX_DOCROOT . '/include/functions/remote/addons.php');


########################################################################

//
// Supported Server functions
//
require_once(GPX_DOCROOT . '/include/functions/remote/supported.php');

########################################################################


//
// Game/Voice Server Update functions
//
require_once(GPX_DOCROOT . '/include/functions/remote/updates.php');

?>
