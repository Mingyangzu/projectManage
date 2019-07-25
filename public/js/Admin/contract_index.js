$(function(){

	//获取要修改的合同信息并展示
	$('.edit').on('click',function(){
	    $('#myModalLabel').html('修改合同');
		var contract_id=$.trim($(this).data('id'));
        var _token=$.trim($('#_token').val());
		var data={
            contract_id:contract_id,
            _token:_token,
		};
		$.ajax({
			url:'/get_contract',
			data:data,
			dataType:'json',
			type:'post',
			success:function(msg)
			{
                $('#edit_project').data('contract_id',contract_id);
                $('#edit_project').data('type','edit');

                $('#modal_contract_time').val(msg.contract_time);
                $('#modal_contract_money').val(msg.money);
                $('#modal_project_name').val(msg.project_name);
                $('#modal_contract_title').val(msg.title);
                $('#modal_contract_describe').val(msg.describe);
                $('#modal_contract_take_effect_time').val(msg.take_effect_time);
                $('#modal_contract_end_time').val(msg.end_time);
                $('#modal_contract_upload').val(msg.url);
                $('#show_pic_url').html("<a target='_blank' href="+msg.contract_show_url+">下载</a>");
                $('.contract_status').each(function(){
                    if($.trim($(this).val())==msg.status)
                    {
                        $(this).prop('checked',true);
                    }
                });

                $('#modal_project_name,#modal_contract_title,#modal_contract_describe,#modal_contract_take_effect_time,#modal_contract_end_time,#modal_contract_upload,#modal_contract_time,#modal_contract_money').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
                $('#error_message').hide();
                $('#myModal').modal('toggle');
			}
		})
	});

    //修改合同信息
    $('#edit_project').on('click',function(){
        var contract_id=$(this).data('contract_id');
        var type=$(this).data('type');
        var modal_project_name=$('#modal_project_name').val();
        var modal_contract_title=$('#modal_contract_title').val();
        var modal_contract_describe=$('#modal_contract_describe').val();
        var modal_contract_take_effect_time=$('#modal_contract_take_effect_time').val();
        var modal_contract_end_time=$('#modal_contract_end_time').val();
        var modal_contract_upload=$('#modal_contract_upload').val();
        var modal_contract_time=$('#modal_contract_time').val();
        var modal_contract_money=$('#modal_contract_money').val();
        var status=1;
        var _token=$.trim($('#_token').val());

        $('.contract_status').each(function(){
            if($(this).prop('checked'))
            {
                status=$.trim($(this).val());
            }
        });

        var data={
            contract_id:contract_id,
            project_name:modal_project_name,
            title:modal_contract_title,
            describe:modal_contract_describe,
            status:status,
            contract_time:modal_contract_time,
            money:modal_contract_money,
            take_effect_time:modal_contract_take_effect_time,
            end_time:modal_contract_end_time,
            url:modal_contract_upload,
            _token:_token,
        };
        var url='/add_contract';
        if(type=='edit')
        {
            url='/update_contract';
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

    //添加合同
    $('#add_project').on('click',function(){
        $('#edit_project').data('type','add');
        $('#show_pic_url').html('');

        $('#myModalLabel').html('添加合同');
        $('#modal_project_name,#modal_contract_title,#modal_contract_describe,#modal_contract_take_effect_time,#modal_contract_end_time,#modal_contract_upload,#modal_contract_money,#modal_contract_time').val('').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
        $('#myModal').modal('toggle');
    });

    //初始化提示信息
    $('#modal_project_name,#modal_contract_title,#modal_contract_describe,#modal_contract_take_effect_time,#modal_contract_end_time,#modal_contract_upload,#show_pic_url,#modal_contract_money,#modal_contract_time').on('focus',function(){
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
                url:'/contract_url',
                type:'post',
                data:data,
                dataType:'json',
                success:function(msg)
                {
                    if(msg.status==1)
                    {
                        $('#show_pic_url').html(msg.file_name);
                        $('#modal_contract_upload').val(msg.file_address);
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


