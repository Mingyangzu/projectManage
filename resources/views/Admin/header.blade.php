<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
            {{$title}}
		</title>
        @foreach($arr_static['css'] as $v)
            <link href="{{URL::asset('css/'.$v)}}" rel='stylesheet'>
        @endforeach
	</head>
	<body>
	<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
    @if($is_nav==1)
        @include('Admin.nav')
        @include('Admin.password_modal')
    @endif