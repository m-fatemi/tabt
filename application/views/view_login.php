<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Log in</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a><b>University of Tabriz</b></a>
      </div>
      <div class="login-box-body">
      <br>
      <?php if($login_error): ?>
        <p class="login-box-msg">Incorrect username/password!</p>
      <?php endif ?>
        <form action="" method="post" accept-charset="utf-8">
          <input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_hash(); ?>" />
          <div class="form-group has-feedback">
            <input type="text" name="username" class="form-control" placeholder="Username" value="demo">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="Password" value="demo">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <input type="hidden" name="submitted" value="1">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </form>
        <br>
      </div>
    </div>
    <script src="assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>