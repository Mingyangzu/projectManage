$(function(){
    $('#modal_new_password,#modal_new_password_ok').on('focus',function(){
        $(this).parent().removeClass('has-error').removeClass('has-success').children('span').remove();
        $('#error_pwd_message').hide();
    });
    //修改密码
    $('#modify_password').on('click',function(){
        $('#modal_new_password').val('');
        $('#modal_new_password_ok').val('');
        $('#error_pwd_message').hide();
        $('#m_p_Modal').modal('toggle');
    });

    //提交修改密码
    $('#submit_modify_password').on('click',function(){
        var _token=$.trim($('#_token').val());
        var admin_id=$.trim($(this).data('admin_id'));
        var new_password=$('#modal_new_password').val();
        var new_password_ok=$('#modal_new_password_ok').val();
        var data={
            _token:_token,
            admin_id:admin_id,
            new_password:new_password,
            new_password_ok:new_password_ok
        };
        $.ajax({
            url:'/modify_password',
            data:data,
            dataType:'json',
            type:'post',
            success:function(msg)
            {
                if(msg.status==1)
                {
                    alert(msg.message);
                    location.href='/';
                    return false;
                }

                $('#'+msg.error_input).parent().removeClass('has-success').addClass('has-error').append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                $('#error_pwd_message').html(msg.message).show();
                return false;
            },
            error: function(msg){
                var json=JSON.parse(msg.responseText);
                // console.log(json);
                $.each(json.errors, function(idx, obj) {
                    $('#'+obj[0][1]).parent().removeClass('has-success').addClass('has-error').append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                    $('#error_pwd_message').html(obj[0][0]).show();
                    return false;
                })
            }
        })
    });
});