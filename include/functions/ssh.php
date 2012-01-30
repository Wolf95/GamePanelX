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
// Functions for SSH Connections
//

function gpx_ssh_exec($ssh_ip,$ssh_port,$ssh_user,$ssh_pass,$ssh_cmd,$ssh_output=false)
{
    // Server Connection Timeout
    $conn_timeout = 6;
    
    // Set script timeout
    set_time_limit($conn_timeout);
    
    ####################################################################
    
    // Check empty
    if(empty($ssh_ip))
    {
        $err  = 'Connection Address';
    }
    elseif(empty($ssh_port))
    {
        $err  = 'Connection Port';
    }
    elseif(empty($ssh_user))
    {
        $err  = 'Connection Username';
    }
    elseif(empty($ssh_pass))
    {
        $err  = 'Connection Password';
    }
    elseif(empty($ssh_cmd))
    {
        $err  = 'Connection Command';
    }
    
    // Failure
    if(!empty($err))
    {
        return 'FAILURE: <i>ssh.php:</i> Important Remote Server values were left out: <b>' . $err . '</b>';
    }

    
    // Old school...
    $gpxseckey_T2V1lmkWLli04Z7q3FT = 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3';
    
    ####################################################################
    
    //
    // PHPSecLib (Pure-PHP SSH Implementation)
    //
    require_once(GPX_DOCROOT . '/include/remote/Net/SSH2.php');


    // Check that the host/port is up and working
    $test_host = fsockopen($ssh_ip,$ssh_port,$errno,$errstr,$conn_timeout);
    if(!$test_host)
    {
        //return 'FAILURE: <i>ssh.php:</i> Unable to connect to the Remote IP Address and Port.  The host may be down, the SSHd service not running, or the port is closed.  Check your configuration and try again.</center>';
        return 'Unable to connect to the Remote IP Address and Port';
    }


    // Connect to the server
    $ssh = new Net_SSH2($ssh_ip, $ssh_port, $conn_timeout);
    if (!$ssh->login($ssh_user, $ssh_pass))
    {
        //return 'FAILURE: Login to the Remote Server failed';
        return 'FAILURE: Login to the Remote Server failed';
    }
    
    
    // Check if the function wants output back
    if($ssh_output)
    {
        return trim($ssh->exec($ssh_cmd));
    }
    else
    {
        $ssh->exec($ssh_cmd);
        return true;
    }

    
    ####################################################################
    
    /*
    
    //
    // PECL SSH2 Module
    //
    
    
    // Make sure the ssh2 function exists
    if (!function_exists('ssh2_connect'))
    {
        die('<center><b>Error:</b> <i>ssh.php:</i> The SSH2 Module doesn\'t exist!  Check the module installation and try again.</center>');
    }
    
    
    ####################################################################
    
    
    // Check that the host/port is up and working
    $test_host = fsockopen($ssh_ip,$ssh_port,$errno,$errstr,$conn_timeout);
    if(!$test_host)
    {
        die('<center><b>Error:</b> <i>ssh.php:</i> Unable to connect to the SSH IP Address and Port.  The host may be down, the SSHd service not running, or the port is closed.  Check your configuration and try again.</center>');
    }
    
    
    ####################################################################


    // Connect to the server
    $ssh2 = ssh2_connect($ssh_ip,$ssh_port);
    
    // Set connection timeout
    stream_set_timeout($ssh2, $conn_timeout);
    
    // Authenticate
    ssh2_auth_password($ssh2, $ssh_user, $ssh_pass);
    
    // Execute command
    if(!$stream = ssh2_exec($ssh2, $ssh_cmd))
    {
        return false;
    }
    // Success.  Check if we want output from the command
    else
    {
        // Check if the functions wants output back
        if($ssh_output)
        {
            stream_set_blocking($stream, true);
            $data = '';
            
            while($buf = fread($stream,4096))
            {
                $data .= $buf;
            }

            // Close the stream
            fclose($stream);
            
            return $data;
        }
        else
        {
            return true;
        }
    }
    */
}




