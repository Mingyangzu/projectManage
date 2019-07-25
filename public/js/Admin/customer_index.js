$(function(){

    //查看客户信息
    $('.see_customer').on('click',function(){
        var customer_id=$.trim($(this).data('id'));
        var _token=$.trim($('#_token').val());
        var data={
            customer_id:customer_id,
            _token:_token,
        };
        $.ajax({
            url:'/get_customer',
            data:data,
            dataType:'json',
            type:'post',
            success:function(msg)
            {
                $('#modal_customer_tel_see').html(msg.phone);
                $('#modal_customer_names_see').html(msg.username);
                $('#modal_company_names_see').html(msg.company);
                $('#modal_customer_landline_see').html(msg.landline);
                $('#modal_customer_position_see').html(msg.position);
                $('#modal_customer_turnover_see').html(msg.turnover+'万元');
                $('#modal_customer_address_see').html(msg.address);
                $('#modal_customer_main_business_see').val(msg.main_business);
                $('#modal_customer_scale_see').html(msg.scale+'人');
                $('#modal_customer_source_see').html(msg.show_source);
                $('#modal_customer_remarks_see').val(msg.remarks);
                $('#modal_customer_industry_see').html(msg.industry);
                $('#modal_customer_wechat_see').html(msg.wechat);
                $('#modal_customer_status_see').html(msg.show_status);
                $('#modal_customer_type_see').html(msg.show_type);

                $('#my_see_Modal').modal('toggle');
            }
        })
    });

	//获取要修改的客户信息并展示
	$('.edit').on('click',function(){
	    $('#myModalLabel').html('修改客户');
		var customer_id=$.trim($(this).data('id'));
        var _token=$.trim($('#_token').val());
		var data={
            customer_id:customer_id,
            _token:_token,
		};
		$.ajax({
			url:'/get_customer',
			data:data,
			dataType:'json',
			type:'post',
			success:function(msg)
			{
                $('#edit_project').data('customer_id',customer_id);
                $('#edit_project').data('type','edit');
                $('#modal_customer_tel').val(msg.phone);
                $('#modal_customer_names').val(msg.username);
                $('#modal_company_names').val(msg.company);
                $('#modal_customer_landline').val(msg.landline);
                $('#modal_customer_position').val(msg.position);
                $('#modal_customer_turnover').val(msg.turnover);
                $('#modal_customer_address').val(msg.address);
                $('#modal_customer_main_business').val(msg.main_business);
                $('#modal_customer_scale').val(msg.scale);
                $('#modal_customer_source').val(msg.source);
                $('#modal_customer_remarks').val(msg.remarks);
                $('#modal_customer_industry').val(msg.industry);
                $('#modal_customer_wechat').val(msg.wechat);

                $('.modal_customer_status').each(function(){
                    if($.trim($(this).val())==msg.status)
                    {
                        $(this).prop('checked',true);
                    }
                });
                $('.modal_customer_type').each(function(){
                    if($.trim($(this).val())==msg.type)
                    {
                        $(this).prop('checked',true);
                    }
                });

                $('#modal_customer_tel,#modal_customer_names,#modal_company_names,#modal_customer_position,#modal_customer_turnover,#modal_customer_address,#modal_customer_main_business,#modal_customer_scale,#modal_customer_remarks,#modal_customer_industry,#modal_customer_landline,#modal_customer_wechat').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
                $('#error_message').hide();
                $('#myModal').modal('toggle');
			}
		})
	});

    //修改客户信息
    $('#edit_project').on('click',function(){
        var customer_id=$(this).data('customer_id');
        var type=$(this).data('type');
        var customer_phone=$('#modal_customer_tel').val();
        var customer_name=$('#modal_customer_names').val();
        var company_name=$('#modal_company_names').val();
        var customer_position=$('#modal_customer_position').val();
        var customer_turnover=$('#modal_customer_turnover').val();
        var customer_address=$('#modal_customer_address').val();
        var customer_main_business=$('#modal_customer_main_business').val();
        var customer_scale=$('#modal_customer_scale').val();
        var customer_source=$('#modal_customer_source').val();
        var customer_remarks=$('#modal_customer_remarks').val();
        var customer_industry=$('#modal_customer_industry').val();
        var landline=$('#modal_customer_landline').val();
        var wechat=$('#modal_customer_wechat').val();
        var customer_type=1;

        var status=1;
        var _token=$.trim($('#_token').val());

        $('.modal_customer_status').each(function(){
            if($(this).prop('checked'))
            {
                status=$.trim($(this).val());
            }
        });
        $('.modal_customer_type').each(function(){
            if($(this).prop('checked'))
            {
                customer_type=$.trim($(this).val());
            }
        });

        var data={
            customer_id:customer_id,
            phone:customer_phone,
            username:customer_name,
            company:company_name,
            status:status,
            _token:_token,
            position:customer_position,
            turnover:customer_turnover,
            address:customer_address,
            main_business:customer_main_business,
            scale:customer_scale,
            source:customer_source,
            remarks:customer_remarks,
            industry:customer_industry,
            landline:landline,
            type:customer_type,
            wechat:wechat,
        };
        var url='/update_customer';
        if(type=='add')
        {
            url='/add_customer';
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

    //添加客户
    $('#add_project').on('click',function(){
        $('#edit_project').data('type','add');

        $('#myModalLabel').html('添加客户');
        $('#modal_customer_tel,#modal_customer_names,#modal_company_names,#modal_customer_position,#modal_customer_turnover,#modal_customer_address,#modal_customer_main_business,#modal_customer_scale,#modal_customer_remarks,#modal_customer_industry,#modal_customer_landline,#modal_customer_wechat').val('').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
        $('#myModal').modal('toggle');
    });

    //初始化提示信息
    $('#modal_customer_tel,#modal_customer_names,#modal_company_names,#modal_customer_position,#modal_customer_turnover,#modal_customer_address,#modal_customer_main_business,#modal_customer_scale,#modal_customer_remarks,#modal_customer_industry,#modal_customer_landline,#modal_customer_wechat').on('focus',function(){
        $(this).parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
    });


    //获取要分配的业务员并展示
    $('.appoint').on('click',function(){

        var admin_id=$.trim($(this).data('admin_id'));
        var cus_id=$.trim($(this).data('cus_id'));
        var is_new=$.trim($(this).data('is_new'));
        var _token=$.trim($('#_token').val());

        var data={
            cus_id:cus_id,
            is_new:is_new,
            _token:_token,
        };
        $.ajax({
            url:'/get_customer_admin',
            data:data,
            dataType:'json',
            type:'post',
            success:function(msg)
            {
                $('#modal_cus_appoint').html('');
                $('#appoint_cus_admin').data('cus_id',cus_id);
                var str='';
                $.each(msg,function(i,v){
                    var str_op="<option value="+v.id+">"+v.name+"</option>\n";
                    if(v.id==admin_id)
                    {
                        str_op="<option selected value="+v.id+">"+v.name+"</option>\n";
                    }
                    str+=str_op;
                });
// console.log(str);
                $('#modal_cus_appoint').append(str);
            }
        });
        $('#myModalAppoint').modal('toggle');

    });

    //分配业务员
    $('#appoint_cus_admin').on('click',function(){
        var _token=$.trim($('#_token').val());
        var cus_id=$(this).data('cus_id');
        var admin_id=$('#modal_cus_appoint').val();

        var data={
            _token:_token,
            cus_id:cus_id,
            admin_id:admin_id,
        };

        $.ajax({
            url: '/appoint_customer_admin',
            data: data,
            dataType: 'json',
            type: 'post',
            success: function (msg)
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


