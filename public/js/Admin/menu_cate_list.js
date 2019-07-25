$(function(){

	//获取要修改的导航分类信息并展示
	$('.edit').on('click',function(){
        $('#error_message').hide();
	    $('#myModalLabel').html('修改导航分类');
		var menu_cate_id=$.trim($(this).data('id'));
        var _token=$.trim($('#_token').val());
		var data={
            menu_cate_id:menu_cate_id,
            _token:_token,
		};
		$.ajax({
			url:'/get_menu_cate',
			data:data,
			dataType:'json',
			type:'post',
			success:function(msg)
			{
                $('#edit_project').data('menu_cate_id',menu_cate_id);
                $('#edit_project').data('type','edit');

                $('#modal_menu_cate_name').val(msg.name);
                $('#modal_menu_cate_describe').val(msg.describe);

                $('.modal_menu_cate_status').each(function(){
                    if($.trim($(this).val())==msg.status)
                    {
                        $(this).prop('checked',true);
                    }
                });

                $('#modal_menu_cate_name,#modal_menu_cate_describe').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
                $('#error_message').hide();
                $('#myModal').modal('toggle');
			}
		})
	});

    //修改导航分类
    $('#edit_project').on('click',function(){
        var menu_cate_id=$(this).data('menu_cate_id');
        var type=$(this).data('type');
        var modal_menu_cate_name=$('#modal_menu_cate_name').val();
        var modal_menu_cate_describe=$('#modal_menu_cate_describe').val();
        var status=1;
        var _token=$.trim($('#_token').val());

        $('.modal_menu_cate_status').each(function(){
            if($(this).prop('checked'))
            {
                status=$.trim($(this).val());
            }
        });

        var data={
            menu_cate_id:menu_cate_id,
            name:modal_menu_cate_name,
            describe:modal_menu_cate_describe,
            status:status,
            _token:_token,
        };
        var url='/add_menu_cate';
        if(type=='edit')
        {
            url='/update_menu_cate';
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

    //添加导航分类
    $('#add_project').on('click',function(){
        $('#edit_project').data('type','add');

        $('#myModalLabel').html('添加导航分类');
        $('#modal_menu_cate_name,#modal_menu_cate_describe').val('').parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_message').hide();
        $('#myModal').modal('toggle');
    });

    //初始化提示信息
    $('#modal_menu_cate_name,#modal_menu_cate_describe').on('focus',function(){
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


