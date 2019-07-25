$(function(){

	//获取要修改的权限信息并展示
	$('.edit').on('click',function(){
        $('#error_message').hide();
	    $('#myModalLabel').html('修改权限');
		var power_id=$.trim($(this).data('id'));
		// alert(role_id);
        var _token=$.trim($('#_token').val());
		var data={
            power_id:power_id,
            _token:_token,
		};
		$.ajax({
			url:'/get_power',
			data:data,
			dataType:'json',
			type:'post',
			success:function(msg)
			{
                $('#edit_project').data('power_id',power_id);
                $('#edit_project').data('type','edit');

                $('#modal_power_name').val(msg.name);
                $('#modal_power_link').val(msg.link);
                $('#modal_power_parent').val(msg.pid);

                $('.modal_power_status').each(function(){
                    if($.trim($(this).val())==msg.status)
                    {
                        $(this).prop('checked',true);
                    }
                });

                $('#modal_power_name,#modal_power_link,#modal_power_parent').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
                $('#error_message').hide();
                $('#myModal').modal('toggle');
			}
		})
	});

    //修改权限
    $('#edit_project').on('click',function(){
        var power_id=$(this).data('power_id');
        var type=$(this).data('type');
        var modal_power_name=$('#modal_power_name').val();
        var modal_power_link=$('#modal_power_link').val();
        var modal_power_parent=$('#modal_power_parent').val();
        var status=1;
        var _token=$.trim($('#_token').val());

        $('.modal_power_status').each(function(){
            if($(this).prop('checked'))
            {
                status=$.trim($(this).val());
            }
        });

        var data={
            power_id:power_id,
            name:modal_power_name,
            link:modal_power_link,
            status:status,
            pid:modal_power_parent,
            _token:_token,
        };
        var url='/add_power';
        if(type=='edit')
        {
            url='/update_power';
        }
        $.ajax({
            url:url,
            data:data,
            dataType:'json',
            type:'post',
            success:function(msg)
            {
                if(msg.status==1)
                {
                    alert(msg.message);
                    location.reload();
                    return false;
                }

                $('#'+msg.error_input).parent().removeClass('has-success').addClass('has-error').append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                $('#error_message').html(msg.message).show();
                return false;
            },
            error: function(msg){
                var json=JSON.parse(msg.responseText);
                // console.log(json);
                $.each(json.errors, function(idx, obj) {
                    $('#'+obj[0][1]).parent().removeClass('has-success').addClass('has-error').append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                    $('#error_message').html(obj[0][0]).show();
                    return false;
                })
            }
        })

    });

    //添加权限
    $('#add_project').on('click',function(){
        $('#edit_project').data('type','add');

        $('#myModalLabel').html('添加权限');

        $('#modal_power_parent').val(0);
        $('#modal_power_name,#modal_power_link').val('').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
        $('#myModal').modal('toggle');
    });

    //初始化提示信息
    $('#modal_power_name,#modal_power_link,#modal_power_parent').on('focus',function(){
        $(this).parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
    });

    //时间插件
    $('.datetimepicker').datetimepicker({
        format:'yyyy-mm-dd',
        weekStart:0,
        autoclose:true,
        startView:2,
        minView:2,
        maxView:3,
        todayBtn:true,
        todayHighlight:true,
        keyboardNavigation:true,
        language:'zh_cn',
        forceParse:true,
        minuteStep:3,

    });

    $('.datetimepicker').datetimepicker('hide');
    $('.datetimepicker').on('focus',function(){
        $('.datetimepicker').datetimepicker('hide');
        $(this).datetimepicker('show');
    })
});


