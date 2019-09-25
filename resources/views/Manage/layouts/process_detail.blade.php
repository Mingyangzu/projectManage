<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title> 拓美管理系统 - @yield('title')</title>
        <link rel="stylesheet" href="/layui/css/layui.css" media="all">
        <link rel="stylesheet" href="/layui/css/formSelects-v4.css" />
        <script src="/layui/layui.js"></script>
        <script src="/layui/formSelects-v4.js" type="text/javascript" charset="utf-8"></script>
        <style>
            .layui-container{width: 99%;}
            .table-title{text-align: center; height: 39px; line-height: 39px;font-size: 18px;margin-bottom: 15px;}
            .layui-row-top{height: 38px; line-height: 38px;}
            .layui-row .layui-input{border:0; border-bottom: 1px solid #333; height: 25px;}

            .process-detail tr{height: 30px;}
            .process-detail tr td{border-color: #000; padding: 3px 10px;}
            .process-detail tr td .layui-input{border:0; border-bottom: 1px solid #333; }
            .tb-left{text-align: center; color: #000; font-size:16px;}
            .tb-right{color:#000;}
            .tb-right .layui-input{height: 30px; line-height: 30px;}
            .smheight{height: 30px;}
            /*.process-detail textarea{border: 0; resize: none; }*/
        </style>
    </head>
    <body class="">
        <!--startprint1-->
        <div id='detailbox' style='margin: 10px auto;width: 980px;'>

            <div class="layui-container"> 
                <div class="layui-row table-title" style=''> 
                    <div class="layui-col-xs12" > 托美科技服务项目下单表 </div>
                </div>


                <div class="layui-row layui-row-top">
                    <div class="layui-col-xs3">签单人: 
                        <input class="layui-input layui-input-inline" style='width: 75%;' value="{{$data['process']['salesman_str']}}" />
                    </div>
                    <div class="layui-col-xs4">下单时间:
                        <input class="layui-input layui-input-inline" style='width: 78%;' value="{{$data['process']['develop_date']}}" />
                    </div>
                    <div class="layui-col-xs5">合同签订完结时间:
                        <input class="layui-input layui-input-inline" style='width: 68%;' value="{{$data['process']['deliver_date']}}" />
                    </div>
                </div>

                <div class="layui-row layui-row-top">
                    <div class="layui-col-xs6">项目技术负责人:
                        <input class="layui-input layui-input-inline" style='width: 75%;' value="{{$data['process']['technical_str']}}" />
                    </div>
                    <div class="layui-col-xs6">项目监督负责人:
                        <input class="layui-input layui-input-inline" style='width: 75%;' value="{{$data['process']['admin_str']}}" />
                    </div>
                </div>

                <div class="layui-row layui-row-top">
                    <div class="layui-col-xs6">客户姓名及电话:
                        <input class="layui-input layui-input-inline" style='width: 75%;' value="{{$data['process']['customer_str']}}" />
                    </div>
                    <div class="layui-col-xs6">公司名称及座机:
                        <input class="layui-input layui-input-inline" style='width: 75%;' value="{{$data['process']['company_str']}}" />
                    </div>
                </div>


                <table class="layui-table process-detail" width='98%'>
                    <colgroup>
                        <col width="12%">
                        <col width="86%">
                    </colgroup>
                    <tbody>
                        <tr>
                            <td class='tb-left'>项目开发内容及需求</td>
                            <td class='tb-right'>
                                <textarea name="remarks" class="layui-textarea">开发内容: {{$data['process']['note']}}</textarea>
                            </td>
                        </tr>
                        
                        @foreach($data['note'] as $v)
                        @if($v['step'] < 10 && $v['type'] == 1)
                        <tr>
                            <td class='tb-left'  rowspan="3">{{ $develop_status[$v['step']] }} <br /><br /> (收款) <br /> 财务确认签字 <br /> <input class="layui-input layui-input-inline"  value="{{$data['finance'][$v['step']]}}" /> </td>
                            <td class='tb-right'>
                                指定完成日期: <input class="layui-input layui-input-inline" style='width: 190px;' value="{{$v['over_date']}}" />
                                实际完成日期: <input class="layui-input layui-input-inline" style='width: 190px;' value="{{$v['end_date']}}" />
                                开发人员签字: <input class="layui-input layui-input-inline" style='width: 115px;' value="{{$v['admin_name']}}" />
                            </td>
                        </tr>
                        <tr>
                            <td class='tb-right'>
                                <textarea name="remarks" class="layui-textarea">开发内容: {{$v['remarks']}} </textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class='tb-right'>
                                <textarea name="remarks" class="layui-textarea">开发人员总结: {{$v['note']}} </textarea>
                            </td>
                        </tr>
                        @else
                            <?php $note[$v['step']] = $v; ?>
                        @endif
                        @endforeach
                        

                        <tr>
                            <td class='tb-left' rowspan="2">项目技术负责人总结</td>
                            <td class='tb-right' style="border-bottom:0;" >
                                <textarea name="remarks" class="layui-textarea"> {{@$note[10]['note']}} </textarea>
                            </td>
                        </tr>
                        <tr class='smheight'>
                            <td class='tb-right' style="border-top:0; text-align: right;" >签字: <input class="layui-input layui-input-inline" style='width: 130px;' value="{{@$note[10]['admin_name']}}" /> </td>
                        </tr>

                        <tr>
                            <td class='tb-left' rowspan="2">项目监督负责人总结</td>
                            <td class='tb-right' style="border-bottom:0;" >
                                <textarea name="remarks" class="layui-textarea"> {{@$note[11]['note']}} </textarea>
                            </td>
                        </tr>
                        <tr class='smheight'>
                            <td class='tb-right' style="border-top:0; text-align: right;" >签字: <input class="layui-input layui-input-inline" style='width: 130px;' value="{{@$note[11]['admin_name']}}" /> </td>
                        </tr>

                        <tr>
                            <td class='tb-left' rowspan="2">绩效考核结果</td>
                            <td class='tb-right' style="border-bottom:0;" >
                                <textarea name="remarks" class="layui-textarea"> {{@$note[12]['note']}} </textarea>
                            </td>
                        </tr>
                        <tr class='smheight'>
                            <td class='tb-right' style="border-top:0; text-align: right;" >
                                项目技术负责人签字:<input class="layui-input layui-input-inline" style='width: 130px;' value="" />
                                项目监督负责人签字:<input class="layui-input layui-input-inline" style='width: 130px;' value="" />
                                总经理签字: <input class="layui-input layui-input-inline" style='width: 130px;' value="" /> 
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" style='border: 0;'>
                                备注：本项目下单表由业务联系人填写，交由财务与合同内容核对无误签名、登记后，由项目监督人签名下单。本单完结后由项目监督人连同设计效果图确认书|UI设计确认书、项目完结确认书一同收集交行政存档。
                            </td>
                        </tr>

                    </tbody>
                </table>
                <!--endprint1-->

            </div>
        </div>
    </body>
    <script>
        var process_print = function (oper) {
            if (oper < 10) {
                
                var textareaTag = document.getElementsByTagName('textarea');
                var d=textareaTag.length;
                for(i=0; i<d; i++){
                    textareaTag[i].style.border = 0;
                    textareaTag[i].style.resize = 'none';
                }
                
                bdhtml = window.document.body.innerHTML; //获取当前页的html代码
                console.log(bdhtml);
                sprnstr = "<!--startprint"+oper+"-->"; //设置打印开始区域
                eprnstr = "<!--endprint"+oper+"-->"; //设置打印结束区域
                prnhtml = bdhtml.substring(bdhtml.indexOf(sprnstr) + 18); //从开始代码向后取html
                prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr)); //从结束代码向前取html
                window.document.body.innerHTML = prnhtml;
                window.print();
                window.document.body.innerHTML = bdhtml;
            } else {
                window.print();
            }
        }
    </script>
</html>