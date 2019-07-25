$(function(){

	//获取要修改的管理员信息并展示
	$('.edit').on('click',function(){
        $('#error_message').hide();
	    $('#myModalLabel').html('修改管理员');
		var admin_id=$.trim($(this).data('id'));
        var _token=$.trim($('#_token').val());
		var data={
            admin_id:admin_id,
            _token:_token,
		};
		$.ajax({
			url:'/get_admin',
			data:data,
			dataType:'json',
			type:'post',
			success:function(msg)
			{
                $('#edit_project').data('admin_id',admin_id);
                $('#edit_project').data('type','edit');

                $('#modal_admin_name').val(msg.name);
                $('#modal_admin_email').val(msg.email);
                $('#modal_admin_phone').val(msg.phone);
                $('#modal_admin_bank_card').val(msg.bank_card);
                $('#modal_admin_id_card').val(msg.id_card);

                $('#modal_admin_role').val(msg.role);

                $('.modal_admin_status').each(function(){
                    if($.trim($(this).val())==msg.status)
                    {
                        $(this).prop('checked',true);
                    }
                });

                $('.modal_admin_sex').each(function(){
                    if($.trim($(this).val())==msg.sex)
                    {
                        $(this).prop('checked',true);
                    }
                });

                $('#modal_admin_name,#modal_admin_email,#modal_admin_phone,#modal_admin_bank_card,#modal_admin_id_card').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
                $('#error_message').hide();
                $('#myModal').modal('toggle');
			}
		})
	});

    //修改管理员
    $('#edit_project').on('click',function(){
        var admin_id=$(this).data('admin_id');
        var type=$(this).data('type');
        var modal_admin_name=$('#modal_admin_name').val();
        var modal_admin_email=$('#modal_admin_email').val();
        var modal_admin_role=$('#modal_admin_role').val();

        var modal_admin_phone=$('#modal_admin_phone').val();
        var modal_admin_bank_card=$('#modal_admin_bank_card').val();
        var modal_admin_id_card=$('#modal_admin_id_card').val();

        var status=1;
        var sex=1;
        var role=modal_admin_role;
        var _token=$.trim($('#_token').val());

        $('.modal_admin_status').each(function(){
            if($(this).prop('checked'))
            {
                status=$.trim($(this).val());
            }
        });
        $('.modal_admin_sex').each(function(){
            if($(this).prop('checked'))
            {
                sex=$.trim($(this).val());
            }
        });

        var data={
            admin_id:admin_id,
            name:modal_admin_name,
            email:modal_admin_email,
            sex:sex,
            role:role,
            phone:modal_admin_phone,
            bank_card:modal_admin_bank_card,
            id_card:modal_admin_id_card,
            status:status,
            _token:_token,
        };
        var url='/add_admin';
        if(type=='edit')
        {
            url='/update_admin';
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

    //添加管理员
    $('#add_project').on('click',function(){
        $('#edit_project').data('type','add');

        $('#myModalLabel').html('添加管理员');
        $('#modal_admin_name,#modal_admin_email,#modal_admin_role,#modal_admin_phone,#modal_admin_bank_card,#modal_admin_id_card').val('').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
        $('#myModal').modal('toggle');
    });

    //初始化提示信息
    $('#modal_admin_name,#modal_admin_email,#modal_admin_phone,#modal_admin_bank_card,#modal_admin_id_card').on('focus',function(){
        $(this).parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
    });
    $('#modal_admin_role').on('change',function(){
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


