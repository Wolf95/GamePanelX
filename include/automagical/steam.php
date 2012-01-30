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
// Steam-based games
//

// Known Filename
$setup['filename']       = 'hldsupdatetool.bin';

// Known SHA1sum
$setup['checksum']       = '3b8e0fcdd7375511f88556c3f8b698a28c93110f';

// Setup Commands
$setup['cmd']            = 'chmod u+x hldsupdatetool.bin ; ';
$setup['cmd']           .= 'echo yes | ./hldsupdatetool.bin ; ';
$setup['cmd']           .= 'chmod u+x steam ; ';
$setup['cmd']           .= './steam ; ';
$setup['cmd']           .= 'sleep 2 ; ';
$setup['cmd']           .= './steam ; ';
$setup['cmd']           .= 'sleep 2 ; ';
$setup['cmd']           .= './steam -command update -game "%steam_name%" -dir . > %tmp_dir%/.gpxinstall.log';

########################################################################

//
// Update commands
//
$setup['update_cmd']           .= './steam ; ';
$setup['update_cmd']           .= 'sleep 2 ; ';
$setup['update_cmd']           .= './steam ; ';
$setup['update_cmd']           .= 'sleep 2 ; ';
$setup['update_cmd']           .= './steam -command update -game "%steam_name%" -dir . > %tmp_dir%/.gpxinstall.log';

?>
