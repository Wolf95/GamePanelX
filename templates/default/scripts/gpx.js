function checkNotif()
{
    $.ajax({
      type: "GET",
      url: "include/ajdb.php",
      data: "a=bar_check_notify",
      success: function(html){
        $('#divCheckNotify').html(html);
      }
    });
}
function getNotif()
{
    $.ajax({
      type: "GET",
      url: "include/ajdb.php",
      data: "a=bar_get_notify",
      success: function(html){
        $('#boxNotifyTd').html(html);
      }
    });
}
function onlineClients()
{
    $.ajax({
      type: "GET",
      url: "include/ajdb.php",
      data: "a=bar_online_clients",
      success: function(html){
        $('#clientsButton').html(html);
      }
    });
}
function getClients()
{
    $.ajax({
      type: "GET",
      url: "include/ajdb.php",
      data: "a=bar_get_clients",
      success: function(html){
        $('#boxClientsTd').html(html);
      }
    });
}

/* ****************************************************************** */

function filesLoad(serverID,thisDir,resetFiles)
{
    $('#info').hide();
    
    if(serverID)
    {
        if(thisDir == "" || thisDir == "undefined")
        {
            var thisDir = "";
        }
        
        $.ajax({
            type: "GET",
            url: "include/ajdb.php",
            data: "a=file_list&id="+serverID+"&f="+thisDir+"&reset="+resetFiles,
            beforeSend: function(){
                $('#files').html('<i>Loading Server Files ...</i>');
            },
            success: function(html){
                $('#files').html(html);
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Ajax Error: "+errorThrown);
            }
        });
    }
    else
    {
        alert("Error: No server given!");
    }
}

// Same as above, but for network servers
function filesLoadNet(serverID,thisDir,resetFiles)
{
    $('#info').hide();
    $('#sel_net_srv').hide();
    
    if(serverID)
    {
        if(thisDir == "" || thisDir == "undefined")
        {
            var thisDir = "";
        }
        
        $.ajax({
            type: "GET",
            url: "include/ajdb.php",
            data: "a=net_file_list&id="+serverID+"&f="+thisDir+"&reset="+resetFiles,
            beforeSend: function(){
                $('#files').html('<i>Loading Server Files ...</i>');
            },
            success: function(html){
                $('#files').html(html);
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Ajax Error: "+errorThrown);
            }
        });
    }
    else
    {
        alert("Error: No server given!");
    }
}

function fileShowAddDir()
{
    $('#adddir').fadeToggle();
    $('#adddir_name').focus();
}

function fileAddDir(serverID)
{
    var dirName = $('#adddir_name').val();
    
    if(serverID && dirName)
    {
        $.ajax({
            type: "GET",
            url: "include/ajdb.php",
            data: "a=dir_add&id="+serverID+"&d="+dirName,
            success: function(html){
                if(html == 'success')
                {
                    $('#adddir_name').val('');
                    $('#adddir').fadeOut();
                    $('#info').html('Successfully created the directory!').fadeIn();
                }
                else
                {
                    alert("Creation Error: "+html);
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Ajax Error: "+errorThrown);
            }
        });
    }
    else
    {
        alert("Error: No directory or serverid specified");
    }
}

// Use the current dir for archiving
function archiveUseDir(networkID)
{
    var serverType  = $('#game').val();
    var description = $('#description').val();
    
    if(networkID == "" || serverType == "")
    {
        alert("A network server or game/voice server was not chosen.\n\nPlease try again!");
        return false;
    }
        
    $('#click_use_dir').hide();
    
    if($('#is_default').is(':checked'))
    {
        var isDefault = '1';
    }
    else
    {
        var isDefault = '0';
    }
    
    $.ajax({
        type: "GET",
        url: "include/ajdb.php",
        data: "a=archive_start&id="+networkID+"&server="+serverType+"&description="+description+"&default="+isDefault,
        beforeSend: function(){
            $('#files').html('<i>Starting process ...</i>');
        },
        success: function(html){
            if(html == "success")
            {
                window.location = 'archives.php?info=created';
                return false;
                //$('#files').hide().html('Process started.').fadeIn();
            }
            else
            {
                $('#files').hide().html('Failed: '+html).fadeIn();
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("Ajax Error: "+errorThrown);
        }
    });
}

/* ****************************************************************** */

/*
* Manage server functions
*/
function loadServerTab(page,serverID)
{
    $('#info').hide();
    if(page && serverID)
    {
        $.ajax({
          type: "GET",
          url: "include/ajdb.php",
          data: "a="+page+"&id="+serverID,
          beforeSend: function(){
              $('#content').html('<span style="font-size:9pt;color:#777;"><i>Loading ...</i></span>');
          },
          success: function(html){
              $('#content').html(html);
              //$('#content_full').fadeIn('medium');
          },
          error: function(jqXHR, textStatus, errorThrown){
              alert("Ajax Error: "+errorThrown);
          }
        });
    }
    else
    {
        alert("ERROR: No page or server id specified.");
    }
}
function serverQuery(serverID)
{
    if(serverID)
    {
        $.ajax({
            type: "GET",
            url: "include/ajdb.php",
            dataType: "json",
            data: "a=query_server&id="+serverID,
            success: function(html){
                if(html.status)
                {
                    var thisCurPly      = html.players;
                    var thisCurMaxSlots = html.maxslots;
                    var thisCurStatus   = html.status;
                    
                    if(thisCurStatus == "online")
                    {
                        if(thisCurPly >= 1)
                        {
                            thisCurPly = '<b>'+thisCurPly+'</b>';
                        }
                        
                        $('#srv_status_txt').html('<span style="font-size:11pt;font-weight:bold;color:green;">Online</span><span style="font-size:11pt;font-weight:normal;color:#777;"><i> ('+thisCurPly+'/'+thisCurMaxSlots+')</i></span>');
                    }
                    else if(thisCurStatus == "offline")
                    {
                        $('#srv_status_txt').html('<span style="font-size:11pt;font-weight:bold;color:red;">Offline</span>');
                    }
                    else
                    {
                        $('#srv_status_txt').html('<span style="font-size:11pt;font-weight:bold;color:orange;">Unknown</span>');
                    }
                }
                
                else
                {
                    $('#srv_status_txt').html('Unknown: '+html);
                }
                
                /*
                if(html == "online")
                {
                    $('#srv_status_txt').html('<span style="font-size:11pt;font-weight:bold;color:green;">Online</span>');
                }
                else if(html == "offline")
                {
                    $('#srv_status_txt').html('<span style="font-size:11pt;font-weight:bold;color:red;">Offline</span>');
                }
                else
                {
                    $('#srv_status_txt').html('<span style="font-size:11pt;font-weight:normal;color:#777;">'+html+'</span>');
                }
                */
            }
        });
    }
    else
    {
        die("ERROR: No server specified");
    }
}



function saveClientServerDetails()
{
    var srvID         = $('#serverid').val();
    var srvOwnerID    = $('#srv_userid').val();
    var srvStatus     = $('#srv_status').val();
    var srvDesc       = $('#srv_description').val();
    var srvSubDom     = $('#srv_subdomain').val();
    var srvDomain     = $('#srv_domainid').val();
    var srvIP         = $('#srv_ip').val();
    var srvPort       = $('#srv_port').val();
    var srvLogFile    = $('#srv_log_file').val();
    var srvMaxSlots   = $('#srv_max_slots').val();
    var srvMap        = $('#srv_map').val();
    var srvExe        = $('#srv_executable').val();
    var srvWorkDir    = $('#srv_working_dir').val();
    var srvSetupDir   = $('#srv_setup_dir').val();
    var srvRcon       = $('#srv_rcon').val();
    var srvClFMan     = $('#srv_client_file_man').val();
    var srvNotes      = $('#srv_notes').val();
    
    var addPost       = "&id="+srvID+"&ownerid="+srvOwnerID+"&status="+srvStatus+"&description="+srvDesc+"&subdomain="+srvSubDom+"&domain="+srvDomain+"&ip="+srvIP+"&port="+srvPort+"&logfile="+srvLogFile+"&maxslots="+srvMaxSlots+"&map="+srvMap+"&exe="+srvExe+"&workingdir="+srvWorkDir+"&setupdir="+srvSetupDir+"&rcon="+srvRcon+"&clfileman="+srvClFMan+"&notes="+srvNotes;
    
    $.ajax({
        type: "GET",
        url: "include/ajdb.php",
        data: "a=save_clientserverdetails"+addPost,
        success:function(html){
            // Scroll to top
            $('html, body').animate({scrollTop:0}, 'fast');
            
            // Response
            if(html == "success")
            {
                $('#info').hide().html('Successfully saved!').fadeIn('slow');
            }
            else
            {
                $('#info').hide().html('An unknown error occured: '+html).fadeIn('slow');
            }
        },
        error:function(jqXHR, textStatus, errorThrown){
            $('#info').hide().html('Failed to save because of a page error.').fadeIn('slow');
        }
    });
}

/* ****************************************************************** */

/*
 * Edit CMD-Line
 * 
*/
function cmdEditSmp()
{
    $('#cmd_adv').fadeOut('fast',function(){
        $('#cmd_smp').fadeIn('fast');
    });
}
function cmdEditAdv()
{
    $('#cmd_smp').fadeOut('fast',function(){
        $('#cmd_adv').fadeIn('fast');
    });
    $('#settings_desc').hide();
}
function cmdSaveSimple(srvID)
{
    var thisMap   = $('#map').val();
    var thisSlots = $('#maxslots').val();
    
    // Data
    var thisData="&map="+thisMap+"&maxslots="+thisSlots;
    
    $.ajax({
        type: "GET",
        url: "include/ajdb.php",
        data: "a=save_cmd_smp&id="+srvID+thisData,
        success: function(html){
            if(html == "success")
            {
                $('#info').hide().html('Successfully saved!').fadeIn();
            }
            else
            {
                $('#info').hide().html("Error: "+html).fadeIn();
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("Ajax Error: "+errorThrown);
        }
    });
}

// Edit Server's CMD-Line
function cmdSaveAdv(srvID)
{
    var dataStr = "";
    
    $('#cmd_adv :input').each(function(index,element){
        var thisID  = element.id;
        //var thisVal =$(this).val();
        var thisVal = encodeURIComponent($(this).val());
        
        // Client-Editable
        if($('#'+thisID).hasClass('cl_ed'))
        {
            if($('#'+thisID).is(':checked'))
            {
                var thisVal = '1';
            }
            else
            {
                var thisVal = '0';
            }
        }
        
        dataStr = dataStr + "&"+thisID+"="+thisVal;
    });
    
    // Run ajax
    $.ajax({
        type: "GET",
        url: "include/ajdb.php",
        data: "a=save_cmd_adv&id="+srvID+dataStr,
        success: function(html){
            if(html == "success")
            {
                loadServerTab('tab_serverstartup',srvID);
                $('#info').hide().html('Successfully saved!').fadeIn();
                
                /*
                // Hide txt, show vals
                $('.input_txt').hide();
                $('.input_select').hide();
                $('.spantxt_val').show();
                
                // Show basic stuff
                $('#map').show();
                $('#maxslots').show();
                */
            }
            else
            {
                $('#info').hide().html("Error: "+html).fadeIn();
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("Ajax Error: "+errorThrown);
        }
    });
}

// Edit Supported CMD-Line
function suppCmdSaveAdv(srvID)
{
    var dataStr = "";
    
    $('#cmd_adv :input').each(function(index,element){
        var thisID  = element.id;
        //var thisVal =$(this).val();
        var thisVal = encodeURIComponent($(this).val());
        
        // Client-Editable
        if($(this).is(':checked') && thisVal == "1")
        {
            thisVal = "1";
        }
        else if(thisVal == "1")
        {
            thisVal = "0";
        }
        
        // Strings
        if(thisVal != "value ...   " && thisVal != "name ...   ")
        {
            dataStr = dataStr + "&"+thisID+"="+thisVal;
        }
    });
    
    // Run ajax
    $.ajax({
        type: "GET",
        url: "include/ajdb.php",
        data: "a=save_cmd_adv_supp&id="+srvID+dataStr,
        success: function(html){
            if(html == "success")
            {
                window.location = 'editsupportedcmdline.php?info=success&id='+srvID;
                return false;
            }
            else
            {
                alert("Failed: "+html);
                //$('#info').hide().html("Error: "+html).fadeIn();
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("Ajax Error: "+errorThrown);
        }
    });
}

function cmdClrTxt()
{
    //var thisItem  = $('#addcfg_'+itemID).val();
    
    //var thisVal=this.value;
    //alerT("ITEM: "+thisVal);
    alert("Would clear");
}
function cmdAddTxt()
{
    if(this.value == "")
    {
        this.value = "name ...   ";
    }
}

function cmdAddItem()
{
    var curItemID = $('#addcfg_id').val();
    var newItemId = parseInt(curItemID) + parseInt(1);
    var addHTML = '<div style="margin-top:5px;width:180px;height:45px;float:left;"><input type="text" id="addcfg_'+newItemId+'" class="input_txt" style="width:160px;" value="name ...   " onFocus="javascript:if(this.value == \'name ...   \'){ this.value=\'\'; };" /></div><div style="margin-top:5px;float:left;width:200px;height:45px;font-size:14pt;font-weight:bold;color:#444;">:<input type="text" id="addcfgval_'+newItemId+'" class="input_txt" style="width:180px;" value="value ...   " onFocus="javascript:if(this.value == \'value ...   \'){ this.value=\'\'; };" /></div><div style="margin-top:5px;width:120px;height:45px;float:left;"><select style="height:45px;width:120px;border-radius:6px;border:1px solid #999;color:#999;" id="addcfgtype_'+newItemId+'"><option value="0">Normal Option</option><option value="1">IP Address</option><option value="2">Port</option><option value="3">Max Slots</option><option value="4">Map</option></select></div>';
    
    $('#addcfg_div').append(addHTML).fadeIn();
    
    // Focus on new box
    $('#addcfg_'+newItemId).focus();
    
    // Store new item ID
    $('#addcfg_id').val(newItemId);
}

// Delete cmdline item
function cmdDelItem(itemid,srvID)
{
    if(itemid)
    {
        $.ajax({
            type: "GET",
            url: "include/ajdb.php",
            data: "a=del_cmd_item&id="+srvID+"&itemid="+itemid,
            success: function(html){
                if(html == "success")
                {
                    $('#info').hide().html('Successfully removed!').fadeIn();
                    $('#curcfg_'+itemid).fadeOut();
                }
                else
                {
                    $('#info').hide().html("Error: "+html).fadeIn();
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Ajax Error: "+errorThrown);
            }
        });
    }
    else
    {
        alert("Error: No config item specified");
    }
}

// Delete cmdline item (Supported Servers)
function cmdDelItemSupp(itemid,srvID)
{
    if(itemid)
    {
        $.ajax({
            type: "GET",
            url: "include/ajdb.php",
            data: "a=del_cmd_item_supp&id="+srvID+"&itemid="+itemid,
            success: function(html){
                if(html == "success")
                {
                    $('#info').hide().html('Successfully removed!').fadeIn();
                    $('#curcfg_'+itemid).fadeOut();
                }
                else
                {
                    alert("ERROR: "+html);
                    //$('#info').hide().html("Error: "+html).fadeIn();
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Ajax Error: "+errorThrown);
            }
        });
    }
    else
    {
        alert("Error: No config item specified");
    }
}

// Confirm deletion (Supported Servers)
function cmdConfirmDelItemSupp(itemid,srvID)
{
    var answer = confirm("Are you sure?\n\nDelete this startup item?");
    
    if(answer)
    {
        cmdDelItemSupp(itemid,srvID);
    }
    return false;
}

// Confirm deletion
function cmdConfirmDelItem(itemid,srvID)
{
    var answer = confirm("Are you sure?\n\nDelete this startup item?");
    
    if(answer)
    {
        cmdDelItem(itemid,srvID);
    }
    return false;
}

function cmdAddtoCur(itemID,srvID)
{
    if(itemID)
    {
        $.ajax({
            type: "GET",
            url: "include/ajdb.php",
            data: "a=cmd_add_cur&id="+srvID+"&itemid="+itemID,
            success: function(html){
                if(html)
                {
                    // Success
                    $('#curcfgrow_'+itemID).fadeOut();
                    $('#cfg_cur_items').append(html);
                }
                else
                {
                    alert("An unknown error occured");
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Ajax Error: "+errorThrown);
            }
        });
    }
}

function cmdToggleItem(itemID)
{
    $('#cfg_cur_items').fadeIn();
    $('#spanval_'+itemID).hide();
    $('#cfg_'+itemID).show();
    $('#cfg_'+itemID).focus();
    $('#curcmdbox_'+itemID).css('background','#CCC');
}
function cmdToggleIP()
{
    $('#cfg_cur_items').fadeIn();
    $('#spanval_ip').hide();
    $('#adv_netid').show();
    $('#adv_netid').focus();
    $('#curcmdbox_ip').css('background','#CCC');
}
function cmdTogglePort()
{
    $('#cfg_cur_items').fadeIn();
    $('#spanval_port').hide();
    $('#adv_port').show();
    $('#adv_port').focus();
    $('#curcmdbox_port').css('background','#CCC');
}
function cmdToggleMaxSlots()
{
    $('#cfg_cur_items').fadeIn();
    $('#spanval_maxslots').hide();
    $('#adv_maxslots').show();
    $('#adv_maxslots').focus();
    $('#curcmdbox_maxslots').css('background','#CCC');
}
function cmdToggleMap()
{
    $('#cfg_cur_items').fadeIn();
    $('#spanval_map').hide();
    $('#adv_map').show();
    $('#adv_map').focus();
    $('#curcmdbox_map').css('background','#CCC');
}

/* ****************************************************************** */
    
/*
* Templates/Archives
*/
function installSupportedServer()
{
    var networkID   = $('#netsrv').val();
    var serverType  = $('#game').val();
    var description = $('#description').val();
    
    $.ajax({
        type: "GET",
        url: "include/ajdb.php",
        data: "a=install_supported_server&networkid="+networkID+"&cfgid="+serverType+"&description="+description,
        success: function(html){
            if(html == 'success')
            {
                window.location = 'archives.php?info=created';
                return false;
            }
            else
            {
                alert("An error occured: "+html);
                return false;
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("Ajax Error: "+errorThrown);
        }
    });
}

function createServerGetOptions()
{
    var cfgID = $("#game").val();
    $('#use_defaults').attr('checked', true);
    
    $.ajax({
        type: "GET",
        url: "include/ajdb.php",
        data: "a=createsrv_getoptions&id="+cfgID,
        success: function(html){
            $('#srv_option').show();
            $('#srv_options').html(html).hide();
            
            // Show create server button
            $('#button_createsrv').fadeIn();
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("Ajax Error: "+errorThrown);
        }
    });
}

/* ****************************************************************** */

/*
* Create Servers
*/
function createServer()
{
    // Make ajax check to see if IP/Port combo is used
    var networkID   = $('#ip').val();
    var cfgID       = $('#game').val();
    var userID      = $('#userid').val();
    var description = $('#description').val();
    
    // We have a port
    if($('#smpdiv_2').length)
    {
        var port  = $('#smptxt_2').val();
    }
    else
    {
        alert("No port found in configuration.  Check the game settings and try again.");
        return false;
    }
    
    // Check port is filled out
    if(port == "")
    {
        alert("You must enter a Port for this server.");
        return false;
    }
    
    // Returns false on used; otherwise should continue through this
    $.ajax({
        type: "GET",
        url: "include/ajdb.php",
        data: "a=createsrv_check_net&networkid="+networkID+"&port="+port,
        success: function(html){
            if(html == 'used')
            {
                // Taken
                alert("That IP/Port combination is already in use.  Please choose another.");
                $('#ip').focus();
                return false;
            }
            // Port not taken
            else if(html == 'ok')
            {
                // Use config values, create server
                var dataStr = "";
                
                $('#srv_options :input').each(function(index,element){
                    var thisID  = element.id;
                    var thisVal = $(this).val();
                    dataStr = dataStr + "&"+thisID+"="+thisVal;
                });
                
                // Run ajax server creation
                $.ajax({
                    type: "GET",
                    url: "include/ajdb.php",
                    data: "a=createsrv_start&networkid="+networkID+"&cfgid="+cfgID+"&userid="+userID+"&description="+description+dataStr,
                    success: function(html){
                        if(html == "success")
                        {
                            window.location="servers.php";
                            return false;
                        }
                        else
                        {
                            alert("Failed to create server: "+html);
                            return false;
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert("Ajax Error: "+errorThrown);
                    }
                });
            }
            else
            {
                alert("Unknown response: "+html);
                return false;
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("Ajax Error: "+errorThrown);
            return false;
        }
    });
}



/* ****************************************************************** */

/*
 * Server Stop/Restart
 * 
*/
function confirmDeleteServer(serverID)
{
    if(serverID)
    {
        var answer=confirm("Are you sure?\n\nDelete this Server?");
        
        if(answer)
        {
            window.location='manageserver.php?id='+serverID+'&action=delete';
            return false;
        }
        return false;
    }
    else
    {
        alert("ERROR: No server selected");
    }
}


function confirmRestartServer(serverID)
{
    if(serverID)
    {
        var answer=confirm("Are you sure?\n\nRestart this Server?");
        
        if(answer)
        {
            restartServer(serverID);
        }
        return false;
    }
    else
    {
        alert("ERROR: No server selected");
    }
}
function confirmStopServer(serverID)
{
    var answer=confirm("Are you sure?\n\nStop this Server?");
    
    if(answer)
    {
        stopServer(serverID);
    }
    return false;
}

function restartServer(serverID)
{
    if(serverID)
    {
        $.ajax({
            type: "GET",
            url: "include/ajdb.php",
            data: "a=server_restart&id="+serverID,
            beforeSend: function(){
                $('#srv_actions').hide();
                $('#srv_action_result').html('<i>Restarting ...</i>');
            },
            success: function(html){
                $('#srv_actions').show();
                
                if(html == 'success')
                {
                    $('#srv_action_result').hide().html('<span style="font-weight:bold;color:green;">Successfully restarted the server!</span>').fadeIn('slow');
                    setTimeout("serverQuery(serverID);", 1000);
                }
                else
                {
                    $('#srv_action_result').hide().html('<span style="font-weight:bold;color:red;">Server Restart Error:</span> '+html).fadeIn('slow');
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                $('#srv_action_result').hide().html('<span style="font-weight:bold;color:red;">Server Error:</span> '+errorThrown).fadeIn('slow');
            }
        });
    }
    else
    {
        die("ERROR: No server specified");
    }
}

function stopServer(serverID)
{
    if(serverID)
    {
        $.ajax({
            type: "GET",
            url: "include/ajdb.php",
            data: "a=server_stop&id="+serverID,
            beforeSend: function(){
                $('#srv_actions').hide();
                $('#srv_action_result').html('<i>Stopping ...</i>');
            },
            success: function(html){
                $('#srv_actions').show();
                
                if(html == 'success')
                {
                    $('#srv_action_result').hide().html('<span style="font-weight:bold;color:green;">Successfully stopped the server!</span>').fadeIn('slow');
                    setTimeout("serverQuery(serverID);", 1000);
                }
                else
                {
                    $('#srv_action_result').hide().html('<span style="font-weight:bold;color:red;">Server Stop Error:</span> '+html).fadeIn('slow');
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                $('#srv_action_result').hide().html('<span style="font-weight:bold;color:red;">Server Error:</span> '+errorThrown).fadeIn('slow');
            }
        });
    }
    else
    {
        die("ERROR: No server specified");
    }
}
function rconCMD(serverID)
{
    var rconcmd   = $('#rcon_cmd').val();
    var rconPass  = $('#rcon_pass').val();
    
    if(serverID)
    {
        $.ajax({
          type: "GET",
          url: "include/ajdb.php",
          data: "a=rcon_server&id="+serverID+"&rcon="+rconPass+"&cmd="+rconcmd,
          beforeSend:function(){
              $('#rcon_result').hide().html('<i>Running ...</i>').fadeIn();
          },
          success: function(html){
              $('#rcon_result').hide().html(html).fadeIn();
          },
          error: function(jqXHR, textStatus, errorThrown){
              alert("Rcon Error: "+errorThrown);
          }
      });
    }
    else
    {
        alert("No serverid provided");
    }
}


/* ****************************************************************** */

function createsrv_gamelist(thisType)
{
    var networkID = $('#ip').val();
    
    $.ajax({
        type: "GET",
        url: "include/ajdb.php",
        data: "a=createsrv_archive_list&id="+networkID+"&type="+thisType,
        beforeSend:function(){
            $('#results').html('<i>Loading ...</i>');
        },
        success: function(html){
            $('#results').hide().html(html).fadeIn();
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("Ajax Error: "+errorThrown);
        }
    });
}

function saveTxtFile(serverID)
{
    var thisTxt=$('#file_content').val();
    
    if(serverID)
    {
        $.ajax({
            type: "POST",
            url: "include/ajax/file_save.php",
            data: "submit=1&a=file_save&id="+serverID+"&text="+thisTxt,
            success: function(html){
                if(html == 'success')
                {
                    $('#info').hide().html('Saved!').fadeIn();
                    
                    //filesLoad(serverID,'','0');
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Ajax Errors: "+errorThrown);
            }
        });
    }
    else
    {
        alert("ERROR: No serverid provided");
    }
}

function confirmDeleteFile(srvID,prevDir,file,keyID)
{
    if(srvID == "" || file == "")
    {
        alert("Required values were left out!");
        return false;
    }
    if(prevDir == "") var prevDir = "";
    var answer = confirm("Are you sure?\n\nDelete this file?");
    
    if(answer)
    {
        deleteFile(srvID,prevDir,file,keyID);
    }
    else
    {
        return false;
    }
}

function deleteFile(srvID,prevDir,file,keyID)
{
    $.ajax({
        type: "GET",
        url: "../include/ajdb.php",
        data: "a=delete_file&id="+srvID+"&prev_dir="+prevDir+"&file="+file,
        success: function(html){
            if(html == 'success')
            {
                $('#del_'+keyID).fadeOut();
            }
            else
            {
                alert("Error: "+html);
            }
        },
        error:function(a,b,c){
            alert("Ajax Error: "+a+", "+b+", "+c);
            return false;
        }
    });
}
