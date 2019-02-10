$(document).ready(function(){

	var sign 		= $('#sign').val();
	var device_id 	= $('#device_id').val();

	$('#name , #description , #min , #max').focus(function(){
		$('#btn-save').removeClass('completed');
		$('#btn-nav').removeClass('show');
		$('#btn-save').html('บันทึก');
	});

	$('#btn-status-toggle').click(function(){
		var device_id = $('#device_id').val();

		$.ajax({
	        url         :'api.device.php',
	        cache       :false,
	        dataType    :"json",
	        type        :"POST",
	        data:{
	            calling     :'device',
	            action      :'status_toggle',
	            device_id 	:device_id,
	            sign 		:sign
	        },
	        error: function (request, status, error) {
	            console.log("Request Error");
	        }
	    }).done(function(data){
	        // console.log(data);
	        location.reload();

	    });
	});

	$('#btn-notify-toggle').click(function(){
		var device_id = $('#device_id').val();

		$.ajax({
	        url         :'api.device.php',
	        cache       :false,
	        dataType    :"json",
	        type        :"POST",
	        data:{
	            calling     :'device',
	            action      :'notify_toggle',
	            device_id 	:device_id,
	            sign 		:sign
	        },
	        error: function (request, status, error) {
	            console.log("Request Error");
	        }
	    }).done(function(data) {
	        location.reload();

	    });
	});

	$('#btn-token-reset').click(function(){

		if (!confirm('คุณต้องการคีย์ใหม่ ใช่หรือไม่ ?')) { return false; }

		var device_id = $('#device_id').val();

		$.ajax({
	        url         :'api.device.php',
	        cache       :false,
	        dataType    :"json",
	        type        :"POST",
	        data:{
	            calling     :'device',
	            action      :'token_reset',
	            device_id 	:device_id,
	            sign 		:sign
	        },
	        error: function (request, status, error) {
	            console.log("Request Error");
	        }
	    }).done(function(data){
	        // console.log(data);
	        location.reload();

	    });
	});

	$('#btn-save').click(function(){
		var name 		= $('#name').val();
		var description = $('#description').val();
		var project_id 	= $('#project_id').val();
		var min 		= parseFloat($('#min').val());
		var max 		= parseFloat($('#max').val());
		var device_id 	= $('#device_id').val();
		
		if (name == '') {
			alert('คุณจำเป็นต้องระบุชื่ออุปกรณ์!');
			$('#name').focus();
			return false;
		} else if (isNaN(min)) {
			alert('ไม่มีค่าอุณหภูมิต่ำที่สุด!');
			$('#min').focus();
			return false;
		} else if (isNaN(max)) {
			alert('ไม่มีค่าอุณหภูมิสูงที่สุด!');
			$('#max').focus();
			return false;
		} else if (parseFloat(min) >= parseFloat(max)) {
			alert('อุณหภูมิต่ำสุดไม่ควรเกินจุดสูงสุด!');
			$('#min').focus();
			return false;
		}

		if(device_id == ''){
			$('#btn-save').html('กำลังสร้างอุปกรณ์ใหม่');
		}

		$.ajax({
	        url         :'api.device.php',
	        cache       :false,
	        dataType    :"json",
	        type        :"POST",
	        data:{
	            calling     :'device',
	            action      :'submit',
	            name		:name,
	            description	:description,
	            project_id	:project_id,
	            min			:min,
	            max			:max,
	            warning		:0,
	            device_id	:device_id,
	            sign 		:sign
	        },
	        error: function (request, status, error) {
	        	console.log(request.responseText)
	            console.log("Request Error");
	        }
	    }).done(function(data){
			console.log(data);
			$('#btn-save').addClass('completed')
			var deviceId = (data.return != 0 ? data.return : device_id)
			console.log('deviceId', deviceId)
	        if (data.return == 0) {
				$('#btn-save').html('บันทึกแล้ว<i class="fa fa-check"></i>')	
			}
			setTimeout(function() {
				window.location = 'device.php?id=' + deviceId;
			}, 1000)
	    });
	});
});