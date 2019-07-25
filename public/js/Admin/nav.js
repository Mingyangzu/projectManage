$(function(){
    $('.show_active_class ul li').each(function(){
        var show_class_name=$.trim($(this).prop("className"));
        if(show_class_name=='active')
        {
            $(this).parent().parent().addClass('active');
        }
    })
});