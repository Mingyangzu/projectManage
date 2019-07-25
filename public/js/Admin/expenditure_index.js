$(function(){

	//获取要修改的支出信息并展示
	$('.edit').on('click',function(){
	    $('#myModalLabel').html('修改支出记录');
		var expenditure_id=$.trim($(this).data('id'));
        var _token=$.trim($('#_token').val());
		var data={
            expenditure_id:expenditure_id,
            _token:_token,
		};
		$.ajax({
			url:'/get_expenditure',
			data:data,
			dataType:'json',
			type:'post',
			success:function(msg)
			{
                $('#edit_project').data('expenditure_id',expenditure_id);
                $('#edit_project').data('type','edit');
                $('#modal_expenditure_name').val(msg.name);
                $('#modal_consumer').val(msg.consumer);
                $('#modal_expenditure_money').val(msg.pay_money);
                $('#modal_expenditure_method').val(msg.pay_method);
                $('#modal_expenditure_remarks').val(msg.remarks);
                $('#modal_expenditure_upload').val(msg.pay_voucher);
                var url='http://'+msg.pay_voucher;
                $('#show_pic_url').html("<a target='_blank' href="+url+">查看</a>");

                $('.modal_expenditure_status').each(function(){
                    if($.trim($(this).val())==msg.status)
                    {
                        $(this).prop('checked',true);
                    }
                });

                $('#modal_expenditure_money,#modal_expenditure_remarks,#modal_expenditure_upload').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
                $('#error_message').hide();
                $('#myModal').modal('toggle');
			}
		})
	});

    //修改支出信息
    $('#edit_project').on('click',function(){
        var expenditure_id=$(this).data('expenditure_id');
        var type=$(this).data('type');
        var modal_expenditure_name=$('#modal_expenditure_name').val();
        var modal_expenditure_money=$('#modal_expenditure_money').val();
        var pay_status=1;
        var pay_voucher=$('#modal_expenditure_upload').val();
        var pay_method=$('#modal_expenditure_method').val();
        var remarks=$('#modal_expenditure_remarks').val();
        var consumer=$('#modal_consumer').val();
        var _token=$.trim($('#_token').val());

        $('.modal_expenditure_status').each(function(){
            if($(this).prop('checked'))
            {
                pay_status=$.trim($(this).val());
            }
        });

        var data={
            expenditure_id:expenditure_id,
            name:modal_expenditure_name,
            pay_money:modal_expenditure_money,
            status:pay_status,
            pay_method:pay_method,
            remarks:remarks,
            pay_voucher:pay_voucher,
            consumer:consumer,
            _token:_token,
        };
        var url='/add_expenditure';
        if(type=='edit')
        {
            url='/update_expenditure';
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

    //添加支出
    $('#add_project').on('click',function(){
        $('#edit_project').data('type','add');
        $('#myModalLabel').html('添加支出记录');
        $('#show_pic_url').html('');
        $('#modal_expenditure_money,#modal_expenditure_remarks,#modal_expenditure_upload').val('').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
        $('#myModal').modal('toggle');
    });

    //初始化提示信息
    $('#modal_expenditure_money,#modal_expenditure_remarks,#show_pic_url,#modal_expenditure_upload').on('focus',function(){
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

    //ajax异步上传文件
    $('#upload_button').on('click',function(){
        var _token=$.trim($('#_token').val());
        var data={
            _token:_token,
        };
        $('#hide_upload_files').val('');
        $('#hide_upload_files').click();
        $('#hide_upload_files').off('change').on('change',function(){
            $('#ajax_upload_files').ajaxSubmit({
                url:'/expenditure_url',
                type:'post',
                data:data,
                dataType:'json',
                success:function(msg)
                {
                    if(msg.status==1)
                    {
                        $('#show_pic_url').html(msg.file_name);
                        $('#modal_expenditure_upload').val(msg.file_address);
                    }
                    else
                    {
                        $('#error_message').html(msg.message).show();
                    }
                }
            })
        })
    });
});


