$(document).ready(function(){

	var sign = $('#sign').val();

	$('#name , #description , #line_token').focus(function(){
		$('#btn-save').removeClass('completed');
		$('#btn-save').html('บันทึก');
		$('#btn-nav').removeClass('-show');
	});

	$('.btn-amin-delete').click(function(){
		var user_id 	= $(this).parent().attr('data-user');
		var project_id 	= $('#project_id').val();

		if(!confirm('คุณต้องการลบผู้ดูแล ใช่หรือไม่ ?')){ return false; }

		console.log('UserID '+ user_id);

		$.ajax({
	        url         :'api.project.php',
	        cache       :false,
	        dataType    :"json",
	        type        :"POST",
	        data:{
	            calling     :'project',
	            action      :'remove_admin',
	            user_id 	:user_id,
	            project_id 	:project_id,
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

	$('#btn-admin-add').click(function(){
		var email 		= $('#email_admin').val();
		var project_id 	= $('#project_id').val();
		$.ajax({
	        url         :'api.project.php',
	        cache       :false,
	        dataType    :"json",
	        type        :"POST",
	        data:{
	            calling     :'project',
	            action      :'add_admin',
	            email 		:email,
	            project_id 	:project_id,
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
		var project_id 	= $('#project_id').val();

		if (name == '') {
			alert('คุณจำเป็นต้องใส่ชื่อโปรเจค!');
			$('#name').focus();
			return false;
		}

		if (project_id == '') {
			$('#btn-save').html('กำลังสร้างโปรเจคใหม่...');
		}

		$.ajax({
	        url         :'api.project.php',
	        cache       :false,
	        dataType    :"json",
	        type        :"POST",
	        data:{
	            calling     :'project',
	            action      :'submit',
	            name 		:name,
	            description :description,
	            line_token 	:line_token,
	            project_id 	:project_id,
	            sign 		:sign
	        },
	        error: function (request, status, error) {
	            console.log(request, status, error);
	        }
	    }).done(function(data){
	        console.log(data);
			var return_device = $('#return_device').val();
			$('#btn-save').addClass('completed');

	        if (data.return != 0) {
	        	setTimeout(function(){ window.location = 'index.php'; }, 2000);
	        } else {
	        	$('#btn-save').html('บันทึกแล้ว<i class="fas fa-check"></i>');

	        	if (return_device != '') {
	        		setTimeout(function() {
						window.location = 'project-editor.php?id=' + return_device; }, 1000);
	        	}
	        }

	    });
	});
});