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
// GamePanelX Pro Installer
//

// SQL Install File
$version_sql_file = 'versions/sql/' . GPX_VERSION . '.sql';
$default_sql_file = 'versions/sql/default.sql';

// If no table changes in this version, use default
if(file_exists($version_sql_file))
{
    $sql_file = $version_sql_file;
}
elseif(file_exists($default_sql_file))
{
    $sql_file = $default_sql_file;
}
else
{
    die('Failed to find the SQL install files!');
}

########################################################################

// Begin the SQL query
$sql_query = "";

// Open the file
$file = fopen($sql_file, "r");

// Loop line-by-line
while(!feof($file))
{
    $sql_query .= fgets($file);
}
fclose($file);

########################################################################

// Split each table by semicolon
$arr_file = explode(';', $sql_query);

foreach($arr_file as $single_query)
{
    $single_query = trim($single_query);
    
    if(!empty($single_query))
    {
        // Add back the semicolon
        $single_query .= ';';

        // Install the table
        @mysql_query($single_query) or die(mysql_error());
    }
}

?>
