$(function(){

	$('#login').on('click',function(){
		var username=$.trim($('#username').val());
		var password=$.trim($('#password').val());
		var _token=$.trim($('#_token').val());

		var data={
			username:username,
			password:password,
            _token:_token
		};
		$.ajax({
			url:'/login_check',
			data:data,
			dataType:'json',
			type:'post',
			success:function(msg)
			{
				if(msg.status==1)
				{
					location.href= '/welcome';
					return false;
				}

				$('#'+msg.error_input).parent().removeClass('has-success').addClass('has-error').append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
			}
		})
	});

	$('#username,#password').on('focus',function(){
        $(this).parent().removeClass('has-error').removeClass('has-success').children('span').remove();
	});
});
