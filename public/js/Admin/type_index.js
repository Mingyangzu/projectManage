$(function(){

	//获取要修改的类型信息并展示
	$('.edit').on('click',function(){
        $('#error_message').hide();
	    $('#myModalLabel').html('修改类型');
		var type_id=$.trim($(this).data('id'));
		// alert(role_id);
        var _token=$.trim($('#_token').val());
		var data={
            type_id:type_id,
            _token:_token,
		};
		$.ajax({
			url:'/get_type',
			data:data,
			dataType:'json',
			type:'post',
			success:function(msg)
			{
                $('#edit_project').data('type_id',type_id);
                $('#edit_project').data('type','edit');

                $('#modal_type_name').val(msg.name);

                $('.modal_type_status').each(function(){
                    if($.trim($(this).val())==msg.status)
                    {
                        $(this).prop('checked',true);
                    }
                });

                $('#modal_type_name').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
                $('#error_message').hide();
                $('#myModal').modal('toggle');
			}
		})
	});

    //修改类型
    $('#edit_project').on('click',function(){
        var type_id=$(this).data('type_id');
        var type=$(this).data('type');
        var modal_type_name=$('#modal_type_name').val();
        var status=1;
        var _token=$.trim($('#_token').val());

        $('.modal_type_status').each(function(){
            if($(this).prop('checked'))
            {
                status=$.trim($(this).val());
            }
        });

        var data={
            type_id:type_id,
            name:modal_type_name,
            status:status,
            _token:_token,
        };
        var url='/add_type';
        if(type=='edit')
        {
            url='/update_type';
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

    //添加进度
    $('#add_project').on('click',function(){
        $('#edit_project').data('type','add');

        $('#myModalLabel').html('添加类型');
        $('#modal_type_name').val('').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
        $('#myModal').modal('toggle');
    });

    //初始化提示信息
    $('#modal_type_name').on('focus',function(){
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
    });

    //获取权限信息并展示
    $('.give').on('click',function(){
        var role_id=$.trim($(this).data('id'));
        // alert(role_id);
        var _token=$.trim($('#_token').val());
        var data={
            role_id:role_id,
            _token:_token,
        };
        $.ajax({
            url:'/get_role_power_list',
            data:data,
            dataType:'json',
            type:'post',
            success:function(msg)
            {
                $('.select_power').each(function(){
                    $(this).prop('checked',false);
                    if($.inArray(parseInt($(this).val()),msg)>=0)
                    {
                        $(this).prop('checked',true);
                    }
                });
                $('#give_power').data('role_id',role_id);
                $('#myModalGive').modal('toggle');
            }
        })
    });

    //分配权限提交
    $('#give_power').on('click',function(){
        var role_id=$(this).data('role_id');
        var _token=$.trim($('#_token').val());
        var arr_power=[];
        $('.select_power').each(function(){
            if($(this).prop('checked'))
            {
                arr_power.push(parseInt($(this).val()));
            }
        });

        var data={
            role_id:role_id,
            _token:_token,
            arr_power:arr_power
        };
        $.ajax({
            url:'/submit_role_power',
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
                alert(msg.message);
            }
        })
    })
});


