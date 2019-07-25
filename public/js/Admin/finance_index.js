$(function(){

	//获取要修改的收款信息并展示
	$('.edit').on('click',function(){
	    $('#myModalLabel').html('修改收款记录');
		var collection_id=$.trim($(this).data('id'));
        var _token=$.trim($('#_token').val());
		var data={
            collection_id:collection_id,
            _token:_token,
		};
		$.ajax({
			url:'/get_collection_records',
			data:data,
			dataType:'json',
			type:'post',
			success:function(msg)
			{
                $('#edit_project').data('collection_id',collection_id);
                $('#edit_project').data('type','edit');
                $('#modal_contract_name').val(msg.contract_name);
                $('#modal_collection_name').val(msg.name);
                $('#modal_collection_money').val(msg.pay_money);
                $('#modal_pay_method').val(msg.pay_method);

                $('.project_collection_status').each(function(){
                    if($.trim($(this).val())==msg.status)
                    {
                        $(this).prop('checked',true);
                    }
                });

                $('#modal_contract_name,#modal_collecton_money').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
                $('#error_message').hide();
                $('#myModal').modal('toggle');
			}
		})
	});

    //修改收款记录信息
    $('#edit_project').on('click',function(){
        var collection_id=$(this).data('collection_id');
        var type=$(this).data('type');
        var modal_contract_name=$('#modal_contract_name').val();
        var modal_collection_name=$('#modal_collection_name').val();
        var modal_collection_money=$('#modal_collection_money').val();
        var pay_status=1;
        var pay_method=$('#modal_pay_method').val();
        var _token=$.trim($('#_token').val());

        $('.project_collection_status').each(function(){
            if($(this).prop('checked'))
            {
                pay_status=$.trim($(this).val());
            }
        });

        var data={
            collection_id:collection_id,
            contract_name:modal_contract_name,
            name:modal_collection_name,
            pay_money:modal_collection_money,
            status:pay_status,
            pay_method:pay_method,
            _token:_token,
        };
        var url='/add_collection';
        if(type=='edit')
        {
            url='/update_collection';
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

    //添加收款记录
    $('#add_project').on('click',function(){
        $('#edit_project').data('type','add');
        $('#myModalLabel').html('添加收款记录');
        $('#modal_contract_name,#modal_collection_money').val('').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
        $('#myModal').modal('toggle');
    });

    //初始化提示信息
    $('#modal_contract_name,#modal_collection_money').on('focus',function(){
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


