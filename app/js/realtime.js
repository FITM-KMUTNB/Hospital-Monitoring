$(document).ready(function(){
    getlastlog();
});
function getlastlog(){
    $('#loading-bar').toggleClass('-action');
    $.ajax({
        url         :'api.php',
        cache       :false,
        dataType    :"json",
        type        :"GET",
        data:{
            calling     :'log',
            action      :'getupdated',
        },
        error: function (request, status, error) {
            console.log("Request Error");
        }
    }).done(function(data){
        console.log(data);
        var currentdate = new Date();
        var datetime    = currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds();
        $('#extime').html(data.data.execute);
        $('#updatetime').html(datetime);

        $.each(data.data.items,function(k,v) {
            $('#device-'+v.device_id).removeClass('lostconnect , alret , active');
            if (v.update_timestemp > 3600) {
                $('#device-'+v.device_id).addClass('lostconnect');
                $('#device-'+v.device_id+' .icon').html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>');
            } else if (v.device_alert) {
                $('#device-'+v.device_id).addClass('alret');
                $('#device-'+v.device_id+' .icon').html('<i class="fa fa-exclamation-circle" aria-hidden="true"></i>');
            } else {
                $('#device-'+v.device_id).addClass('active');
                $('#device-'+v.device_id+' .icon').html('<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>');
            }
            $('#device-'+v.device_id+' .detail .time').html(v.update_time);
            $('#device-'+v.device_id+' .temp').html(v.device_temp+'°');
        });
        setTimeout(getlastlog,10000);
    }).error();
}