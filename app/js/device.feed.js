var time_lastupdate = 0;
var loop_lost = 0;
var device_min = 0;
var device_max = 0;
var device_od;
var myChart;
var limit = 40;
var disconnect_time = 300;

Chart.defaults.global.defaultFontColor = '#DDDDDD';
Chart.defaults.global.defaultFontSize = '10';

$(document).ready(function() {
    device_min  = parseInt($('#device_min').val())
    device_max  = parseInt($('#device_max').val())
    device_id   = $('#device_id').val();
    tippy('[title]', {
        arrow: true
    })
    init()
});

function init(){

    $('#loading-bar').toggleClass('action');

    $.ajax({
        url         :'api.php',
        cache       :false,
        dataType    :"json",
        type        :"GET",
        data:{
            calling     :'log',
            action      :'history_log',
            device_id   :device_id,
            time_stamp  :time_lastupdate,
            limit: limit,
        },
        error: function (request, status, error) {
            $('#disconnect-bar').addClass('active');
            setTimeout(function(){
                myChart.destroy();
                init();
            },10000);
        }
    }).done(function(data){

        var dataset     = [];
        var dataItems   = data.data.items;

        var dataTemp    = [];
        var dataTime    = [];
        var tstemp      = [];

        $.each(dataItems,function(k,v){
            dataTemp.push(v.log_temp);
            dataTime.push(v.log_time);
            tstemp.push(v.log_timestamp);
        });

        graphRender(dataTemp.reverse(),dataTime.reverse());
        historyRender(dataItems);

        // Device disconnect checking
        $disconnect = $('#disconnect-bar')
        var deviceLog = data.data.device_log
        $('body').removeClass('alert active disconnect disable')

        if (data.data.status === 'disable') {
            $('body').addClass('disable')
        } else if ((data.data.update - tstemp[0]) > disconnect_time) {
            $('body').addClass('disconnect')
        } else if (deviceLog.current.temp < device_min || deviceLog.current.temp > device_max) {
            $('body').removeClass('active')
            $('body').addClass('alert')
        } else {
            $('body').removeClass('alert')
            $('body').addClass('active')
        }

        // if ((data.data.update - tstemp[0]) > 300) {
        //     $disconnect.addClass('active');
        //     $('body').addClass('disconnect')
        // } else {
        //     $disconnect.removeClass('active');
        // }

        renderCurrent(data.data.device_log);

        setTimeout(function(){
            myChart.destroy();
            init();
        },10000);

    });
}

function renderCurrent(data){
    var site_title  = $('#site_title').val();
    var device_name = $('#device_name').val();

    document.title =  data.current.temp + '° | ' + device_name;

    $('#tempcurrent').html(data.current.temp + '°');
    $('#timecurrent').html(data.current.time);
    $('#templowest').html(data.min.temp + '°');
    $('#timelowest').html(data.min.time);
    $('#temphighest').html(data.max.temp + '°');
    $('#timehighest').html(data.max.time);
}

function graphRender(dataTemp,dataTime){
    $('#graph').html('');
    var ctx = document.getElementById("graph").getContext('2d');
    myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dataTime,
            datasets: [{
                data: dataTemp,
                backgroundColor: '#FFFFFF',
                borderColor: '#FFFFFF',
                borderWidth: 4,
                pointBorderWidth: 0,
                fill: false,
                pointRadius: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            tooltips: {
                callbacks: {
                    label: function(tooltipItem){
                        return tooltipItem.yLabel;
                    }
                }
            },
            title:{
                display:false
            },
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        drawBorder: false,
                        display: false
                    },
                    ticks: {
                        stepSize: 2,
                    },
                    position: 'right',
                }],
                xAxes: [{
                    display: false
                }],
            },
            animation: {
                duration: 0,
            },
            hover: {
                animationDuration: 0,
            },
            responsiveAnimationDuration: 0,
        }
    });
}

function historyRender(dataset){
    var html = '';
    var gtemp = new Array();
    if(dataset.length == 0){
        $('#historylog').html('<div class="empty">ไม่มีข้อมูลอุปกรณ์นี้</div>');
        return false;
    }
    $.each(dataset,function(k,v){
        gtemp.push(parseFloat(v.log_temp));
        var alert = '';
        var icon = '';

        if (v.alert) {
            alert = 'alert'
        }

        switch (v.log_state) {
            case 'up':
                icon = '<i class="fa fa-arrow-up"></i>';
                break;
            case 'down':
                icon = '<i class="fa fa-arrow-down"></i>';
                break;
            default: 
                ;
        }
        html +='<div class="logitems ' + alert + '">';
        html +='<div class="time">' + v.log_time_fb + '</div>';
        html +='<div class="status"><i class="fas fa-circle"></i></div>';
        html +='<div class="icon">' + icon + '</div>';
        html +='<div class="temp">' + v.log_temp + ' °C</div>';
        html +='</div>';
    });

    $('#historylog').html(html);
}