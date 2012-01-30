function serverStatus(serverID)
{
    $.ajax({
        type: "GET",
        url: "include/ajdb.php",
        data: "a=refresh_gamestatus&id="+serverID,
        success: function(html){
          $("#checkStatusResponse").html(html);
        }
    });
}
