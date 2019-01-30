$(document).ready(function(){

	var sign = $('#sign').val();

	$('#name , #description , #line_token').focus(function(){
		$('#btn-save').removeClass('-completed');
		$('#btn-save').html('บันทึก');
		$('#btn-nav').removeClass('-show');
	});

	$('.btn-zone-delete').click(function(){
		var zone_id = $(this).parent().attr('data-id');
		var space_id 	= $('#space_id').val();

		if(!confirm('คุณต้องการลบสถานที่นี้ ใช่หรือไม่ ?')){ return false; }

		$.ajax({
	        url         :'api.space.php',
	        cache       :false,
	        dataType    :"json",
	        type        :"POST",
	        data:{
	            calling     :'space',
	            action      :'delete_zone',
	            zone_id 	:zone_id,
	            space_id 	:space_id,
	            sign 		:sign
	        },
	        error: function (request, status, error) {
	            console.log("Request Error");
	        }
	    }).done(function(data){

	        console.log(data);

	        $('#zone-'+zone_id).fadeOut(100);

	    });

	});

	$('.btn-amin-delete').click(function(){
		var user_id 	= $(this).parent().attr('data-user');
		var space_id 	= $('#space_id').val();

		if(!confirm('คุณต้องการลบผู้ดูแล ใช่หรือไม่ ?')){ return false; }

		console.log('UserID '+ user_id);

		$.ajax({
	        url         :'api.space.php',
	        cache       :false,
	        dataType    :"json",
	        type        :"POST",
	        data:{
	            calling     :'space',
	            action      :'remove_admin',
	            user_id 	:user_id,
	            space_id 	:space_id,
	            sign 		:sign
	        },
	        error: function (request, status, error) {
	            console.log("Request Error");
	        }
	    }).done(function(data){

	        console.log(data);

	        $('#admin-'+user_id).fadeOut(100);

	    });

	});

	$('#btn-zone-save').click(function(){

		var title 		= $('#zone_title').val();
		var space_id 	= $('#space_id').val();

		$.ajax({
	        url         :'api.space.php',
	        cache       :false,
	        dataType    :"json",
	        type        :"POST",
	        data:{
	            calling     :'space',
	            action      :'create_zone',
	            title 		:title,
	            space_id 	:space_id,
	            sign 		:sign
	        },
	        error: function (request, status, error) {
	            console.log("Request Error");
	        }
	    }).done(function(data){

	        console.log(data);

	        location.reload();

	    });
	});

	$('#btn-admin-add').click(function(){

		var email 		= $('#email_admin').val();
		var space_id 	= $('#space_id').val();

		$.ajax({
	        url         :'api.space.php',
	        cache       :false,
	        dataType    :"json",
	        type        :"POST",
	        data:{
	            calling     :'space',
	            action      :'add_admin',
	            email 		:email,
	            space_id 	:space_id,
	            sign 		:sign
	        },
	        error: function (request, status, error) {
	            console.log("Request Error");
	        }
	    }).done(function(data){

	        console.log(data);

	        location.reload();

	    });
	});

	$('#btn-save').click(function(){

		var name 		= $('#name').val();
		var description = $('#description').val();
		var line_token 	= $('#line_token').val();
		var space_id 	= $('#space_id').val();

		if(name == ''){
			alert('คุณจำเป็นต้องใส่ชื่อกลุ่ม!');
			$('#name').focus();
			return false;
		}

		if(space_id == ''){
			$('#btn-save').html('กำลังสร้างกลุ่มใหม่...');
		}

		$.ajax({
	        url         :'api.space.php',
	        cache       :false,
	        dataType    :"json",
	        type        :"POST",
	        data:{
	            calling     :'space',
	            action      :'submit',
	            name 		:name,
	            description :description,
	            line_token 	:line_token,
	            space_id 	:space_id,
	            sign 		:sign
	        },
	        error: function (request, status, error) {
	            console.log(request, status, error);
	        }
	    }).done(function(data){
	        console.log(data);
	        var return_device = $('#return_device').val();

	        if (data.return != 0) {
	        	setTimeout(function(){ window.location = 'index.php'; }, 2000);
	        } else {
	        	$('#btn-save').addClass('completed');
	        	$('#btn-save').html('บันทึกแล้ว<i class="fas fa-check"></i>');

	        	if (return_device != '') {
	        		setTimeout(function() {
						window.location = 'space-editor.php?id=' + return_device; }, 1000);
	        	}
	        }

	    });
	});
});