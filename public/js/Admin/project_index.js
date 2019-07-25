$(function(){

	//获取要修改的项目信息并展示
	$('.edit').on('click',function(){
	    $('#myModalLabel').html('修改项目');
		var project_id=$.trim($(this).data('id'));
        var _token=$.trim($('#_token').val());
		var data={
            project_id:project_id,
            _token:_token,
		};
		$.ajax({
			url:'/get_project',
			data:data,
			dataType:'json',
			type:'post',
			success:function(msg)
			{
                $('#edit_project').data('project_id',project_id);
                $('#edit_project').data('type','edit');
                $('#modal_customer_company').val(msg.cus_company);
                $('#modal_project_name').val(msg.name);
                $('#modal_project_step').val(msg.step_id);
                $('#modal_project_admin').val(msg.admin_id);
                $('#modal_project_type').val(msg.type_id);
                $('#modal_project_cycle').val(msg.cycle);
                $('#modal_project_remarks').val(msg.remarks);

                $('.modal_project_status').each(function(){
                    if($.trim($(this).val())==msg.status)
                    {
                        $(this).prop('checked',true);
                    }
                });

                $('.modal_project_pay_status').each(function(){
                    if($.trim($(this).val())==msg.payment_status)
                    {
                        $(this).prop('checked',true);
                    }
                });

                $('.modal_project_bid').each(function(){
                    if($.trim($(this).val())==msg.is_bid)
                    {
                        $(this).prop('checked',true);
                    }
                });
                $('#modal_customer_company,#modal_project_name,#modal_project_cycle,#modal_project_remarks,#modal_project_admin').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
                $('#error_message').hide();
                $('#myModal').modal('toggle');
			}
		})
	});

    //修改项目信息
    $('#edit_project').on('click',function(){
        var project_id=$(this).data('project_id');
        var type=$(this).data('type');
        var customer_company=$('#modal_customer_company').val();
        var admin_id=$('#modal_project_admin').val();
        var name=$('#modal_project_name').val();
        var step_id=$('#modal_project_step').val();
        var type_id=$('#modal_project_type').val();
        var cycle=$('#modal_project_cycle').val();
        var remarks=$('#modal_project_remarks').val();
        var status=1;
        var payment_status=0;
        var is_bid=0;
        var _token=$.trim($('#_token').val());

        $('.modal_project_status').each(function(){
            if($(this).prop('checked'))
            {
                status=$.trim($(this).val());
            }
        });

        $('.modal_project_pay_status').each(function(){
            if($(this).prop('checked'))
            {
                payment_status=$.trim($(this).val());
            }
        });

        $('.modal_project_bid').each(function(){
            if($(this).prop('checked'))
            {
                is_bid=$.trim($(this).val());
            }
        });

        var data={
            project_id:project_id,
            company:customer_company,
            name:name,
            admin_id:admin_id,
            step_id:step_id,
            type_id:type_id,
            cycle:cycle,
            status:status,
            remarks:remarks,
            payment_status:payment_status,
            is_bid:is_bid,
            _token:_token,
        };
        var url='/update_project';
        if(type=='add')
        {
            url='/add_project';
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
                $.each(json.errors, function(idx, obj) {
                    $('#'+obj[0][1]).parent().removeClass('has-success').addClass('has-error').append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                    $('#error_message').html(obj[0][0]).show();
                    return false;
                })
            }
        })

    });

    //添加项目
    $('#add_project').on('click',function(){
        $('#edit_project').data('type','add');

        $('#myModalLabel').html('添加项目');
        $('#modal_customer_company,#modal_project_name,#modal_project_cycle,#modal_project_remarks').val('').parent().removeClass('has-error').removeClass('has-success').children('span').remove();

        // $("#modal_project_admin option:first").prop("selected", 'selected');

        $('#error_message').hide();
        $('#myModal').modal('toggle');
    });

    //初始化提示信息
    $('#modal_customer_company,#modal_project_name,#modal_project_cycle,#modal_project_remarks').on('focus',function(){
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


