</body>
    @foreach($arr_static['js'] as $v)
        <script type='text/javascript' src="{{URL::asset('js/'.$v)}}"></script>
    @endforeach
</html>