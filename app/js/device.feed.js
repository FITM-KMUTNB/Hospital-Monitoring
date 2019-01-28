var time_lastupdate = 0;
var loop_lost = 0;
var device_min = 0;
var device_max = 0;
var device_od;
var myChart;
var limit = 40;
var disconnect_time = 240;

Chart.defaults.global.defaultFontColor = '#999999';
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

    $('#loading-bar').toggleClass('-action');

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
            $('#disconnect-bar').addClass('-active');
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

        deviceDisconnect(tstemp[0],data.data.update);
        graphRender(dataTemp.reverse(),dataTime.reverse());
        historyRender(dataItems);

        renderCurrent(data.data.device_log);

        setTimeout(function(){
            myChart.destroy();
            init();
        },10000);

    });
}

function deviceDisconnect(timestamp, now) {
    $disconnect = $('#disconnect-bar');
    var diff = now - timestamp;

    if (diff > 300) {
        $disconnect.addClass('-active');
    } else {
        $disconnect.removeClass('-active');
    }
}

function renderCurrent(data){
    var site_title  = $('#site_title').val();
    var device_name = $('#device_name').val();

    document.title =  data.current.temp + '° | ' + device_name;

    $('#tempcurrent').html(data.current.temp+'°');
    $('#timecurrent').html(data.current.time);
    $('#templowest').html(data.min.temp+'°');
    $('#timelowest').html(data.min.time);
    $('#temphighest').html(data.max.temp+'°');
    $('#timehighest').html(data.max.time);

    if (data.max.temp >= device_max) $('#temphighest').addClass('over');
    else $('#temphighest').removeClass('over');

    if (data.min.temp <= device_min) $('#templowest').addClass('over');
    else $('#templowest').removeClass('over');

    if (data.current.temp >= device_max || data.current.temp <= device_min) {
        $('#tempcurrent').addClass('over')
    } else {
        $('#tempcurrent').removeClass('over')
    }
}

function limitChecking(temp){

    var current = temp[(temp.length) - 1];

    if (current >= device_max || current <= device_min) {
        var color = ['#e74c3c','#451612'];
        return color;
    } else {
        var color = ['#2962FF','#ebf4f9'];
        return color;
    }
}

function graphRender(dataTemp,dataTime){

    $('#graph').html('');

    var borderColor = limitChecking(dataTemp);

    var ctx = document.getElementById("graph").getContext('2d');
    myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dataTime,
            datasets: [{
                data: dataTemp,
                backgroundColor: borderColor[0],
                borderColor: borderColor[0],
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
        html +='<div class="status"><i class="fas fa-circle"></i></div>';
        html +='<div class="time">' + v.log_time_fb + '</div>';
        html +='<div class="temp">' + v.log_temp + ' °C</div>';
        html +='<div class="icon">' + icon + '</div>';
        html +='</div>';
    });

    $('#historylog').html(html);
}