$(function(){

    //查看工资信息
    $('.see_wages').on('click',function(){
        var wages_id=$.trim($(this).data('id'));
        var _token=$.trim($('#_token').val());
        var data={
            wages_id:wages_id,
            _token:_token,
        };
        $.ajax({
            url:'/get_wages',
            data:data,
            dataType:'json',
            type:'post',
            success:function(msg)
            {
                $.each(msg,function(i,v){
                    // console.log(i);
                    // console.log(v);
                    $('#modal_see_wages_'+i).html(v);
                });
                $('#my_see_Modal').modal('toggle');
            }
        })
    });
	//获取要修改的薪资信息并展示
	$('.edit').on('click',function(){
        $('#error_message').hide();
	    $('#myModalLabel').html('修改薪资');
		var wages_id=$.trim($(this).data('id'));
		// alert(role_id);
        var _token=$.trim($('#_token').val());
		var data={
            wages_id:wages_id,
            _token:_token,
		};
		$.ajax({
			url:'/get_wages',
			data:data,
			dataType:'json',
			type:'post',
			success:function(msg)
			{
                $('#edit_project').data('wages_id',wages_id);
                $('#edit_project').data('type','edit');

                $('#modal_wages_user_name').val(msg.admin_id);
                $('#modal_wages_total').val(msg.total);
                $('#modal_wages_basic').val(msg.basic);
                $('#modal_wages_post').val(msg.post);
                $('#modal_wages_full').val(msg.full);
                $('#modal_wages_education').val(msg.education);
                $('#modal_wages_years').val(msg.years);
                $('#modal_wages_oper_allowance').val(msg.oper_allowance);
                $('#modal_wages_skill').val(msg.skill);
                $('#modal_wages_social_security').val(msg.social_security);
                $('#modal_wages_deduct_merit').val(msg.deduct_merit);
                $('#modal_wages_payable_merit').val(msg.payable_merit);
                $('#modal_wages_royalty').val(msg.royalty);
                $('#modal_wages_leave').val(msg.leave);
                $('#modal_wages_late').val(msg.late);
                $('#modal_wages_perquisites').val(msg.perquisites);
                $('#modal_wages_bonus').val(msg.bonus);
                $('#modal_wages_remarks').val(msg.remarks);
                $('#modal_wages_time').val(msg.time);

                $('.modal_wages_status').each(function(){
                    if($.trim($(this).val())==msg.status)
                    {
                        $(this).prop('checked',true);
                    }
                });

                $('#modal_wages_time,#modal_wages_total,#modal_wages_basic,#modal_wages_post,#modal_wages_post,#modal_wages_full,#modal_wages_education,#modal_wages_years,#modal_wages_oper_allowance,#modal_wages_skill,#modal_wages_social_security,#modal_wages_deduct_merit,#modal_wages_payable_merit,#modal_wages_royalty,#modal_wages_leave,#modal_wages_late,#modal_wages_perquisites,#modal_wages_bonus,#modal_wages_remarks').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
                $('#error_message').hide();
                $('#myModal').modal('toggle');
			}
		})
	});

    //修改薪资
    $('#edit_project').on('click',function(){
        var type=$(this).data('type');
        var status=1;
        var _token=$.trim($('#_token').val());

        $('.modal_wages_status').each(function(){
            if($(this).prop('checked'))
            {
                status=$.trim($(this).val());
            }
        });

        var data={
            status:status,
            _token:_token,
            wages_id:$(this).data('wages_id'),
            admin_id:$('#modal_wages_user_name').val(),
            total:$('#modal_wages_total').val(),
            basic:$('#modal_wages_basic').val(),
            post:$('#modal_wages_post').val(),
            full:$('#modal_wages_full').val(),
            education:$('#modal_wages_education').val(),
            years:$('#modal_wages_years').val(),
            oper_allowance:$('#modal_wages_oper_allowance').val(),
            skill:$('#modal_wages_skill').val(),
            social_security:$('#modal_wages_social_security').val(),
            deduct_merit:$('#modal_wages_deduct_merit').val(),
            payable_merit:$('#modal_wages_payable_merit').val(),
            royalty:$('#modal_wages_royalty').val(),
            leave:$('#modal_wages_leave').val(),
            late:$('#modal_wages_late').val(),
            perquisites:$('#modal_wages_perquisites').val(),
            bonus:$('#modal_wages_bonus').val(),
            remarks:$('#modal_wages_remarks').val(),
            time:$('#modal_wages_time').val(),
        };
        var url='/add_wages';
        if(type=='edit')
        {
            url='/update_wages';
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
        $('#modal_wages_time,#modal_wages_total,#modal_wages_basic,#modal_wages_post,#modal_wages_post,#modal_wages_full,#modal_wages_education,#modal_wages_years,#modal_wages_oper_allowance,#modal_wages_skill,#modal_wages_social_security,#modal_wages_deduct_merit,#modal_wages_payable_merit,#modal_wages_royalty,#modal_wages_leave,#modal_wages_late,#modal_wages_perquisites,#modal_wages_bonus,#modal_wages_remarks').val('').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
        $('#myModal').modal('toggle');
    });

    //初始化提示信息
    $('#modal_wages_time,#modal_wages_total,#modal_wages_basic,#modal_wages_post,#modal_wages_post,#modal_wages_full,#modal_wages_education,#modal_wages_years,#modal_wages_oper_allowance,#modal_wages_skill,#modal_wages_social_security,#modal_wages_deduct_merit,#modal_wages_payable_merit,#modal_wages_royalty,#modal_wages_leave,#modal_wages_late,#modal_wages_perquisites,#modal_wages_bonus,#modal_wages_remarks').on('focus',function(){
        $(this).parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
    });

    //时间插件
    $('.datetimepicker').datetimepicker({
        format:'yyyy-mm',
        weekStart:0,
        autoclose:true,
        startView:3,
        minView:3,
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
});


