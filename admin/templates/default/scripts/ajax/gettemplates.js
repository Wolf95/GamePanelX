function getTemplates(networkID,type)
{
    $.ajax({
      type: "GET",
      url: "../include/ajdb.php",
      data: "a=templates_available&id="+networkID+"&type="+type,
      success: function(html){
        $("#divShowServers").html(html);
      }
    });
}