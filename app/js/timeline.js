$(document).ready(function(){
    getlastlog()
    tippy('[title]', {
        arrow: true
    })
});

function getlastlog(){
    var space_id = $('#space_id').val();
    $('#loading-bar').toggleClass('-action');

    $.ajax({
        url         :'api.php',
        cache       :false,
        dataType    :"json",
        type        :"GET",
        data:{
            calling     :'log',
            action      :'getupdated',
            space_id    :space_id,
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

        $.each(data.data.items,function(k,v){
            $('#device-'+v.device_id).removeClass('lostconnect , alret , active , disable');
            if (!v.device_status) {
                $('#device-'+v.device_id).addClass('disable');
                $('#device-'+v.device_id+' .info .status-icon').html('<i class="fa fa-lock-alt"></i>');
            } else if (v.update_timestemp > 240) {
                $('#device-'+v.device_id).addClass('lostconnect');
                $('#device-'+v.device_id+' .info .status-icon').html('<i class="fa fa-sync fa-spin"></i>');
            } else if (v.device_alert) {
                $('#device-'+v.device_id).addClass('alret');
                $('#device-'+v.device_id+' .info .status-icon').html('<i class="fa fa-exclamation-circle"></i>');
            } else {
                $('#device-'+v.device_id).addClass('active');
                $('#device-'+v.device_id+' .info .status-icon').html('<i class="fa fa-thermometer-full"></i>');
            }

            $('#device-'+v.device_id+' .desc').html(v.update_time);
            $('#device-'+v.device_id+' .temp').html(v.device_temp+'°');
        });

        setTimeout(getlastlog,10000)
    });
}