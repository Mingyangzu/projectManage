@include('Admin.header')
<div class="container">
    <h2>后台管理系统</h2>
    <form class="form-signin">
        <div class="form-group has-success has-feedback">
            <input type="text" id='username' class="form-control" placeholder="用户名">
        </div>
        <div class="form-group has-success has-feedback">
            <input type="password" id='password' class="form-control" placeholder="密码">
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="button" id='login'>登 陆</button>
    </form>
</div>
@include('Admin.footer')