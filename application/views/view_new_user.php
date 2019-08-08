<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>پنل مدیریت</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/fonts/fonts-fa.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/bootstrap-rtl.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/morris/morris.css">
  
  </head>

  <?php 
    $this->load->view('header'); 
    $this->load->view('view_sidemenu'); 
  ?>
  <div class="content-wrapper" >
    <section class="content-header">
      <h1>
        پنل کاربری
      </h1>
    </section>
    <section class="content">
      <div class="row">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">ایجاد حساب کاربری جدید</h3>
          </div>

      <?php if($success): ?>
        <div class="pad margin no-print">
          <div class="callout callout-success" style="margin-bottom: 0!important;">
            اطلاعات با موفقیت ثبت شد.
          </div>
        </div>
      <?php endif ?>

      <?php if($error): ?>
        <div class="pad margin no-print">
          <div class="callout callout-warning" style="margin-bottom: 0!important;">
            متاسفانه خطلایی در ثبت اطلعات رخ داد!
          </div>
        </div>
      <?php endif ?>
          <form action="" method="post" accept-charset="utf-8" class="form-horizontal">
          <input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_hash(); ?>" />
            <div class="box-body">
              <div class="form-group">
                <label for="name" class="col-sm-2 control-label">نام و نام خانوادگی</label>
                <div class="col-sm-10">
                  <input type="text" name="formData[name]" id="name" required="required" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label for="mobile" class="col-sm-2 control-label">شماره تماس</label>
                <div class="col-sm-10">
                  <input type="text" name="formData[mobile]" id="mobile" class="form-control">
                </div>
              </div>
              <div class="form-group">
			  <?php $groups = $this->db->query("SELECT * FROM groups")->result_array(); ?>
                <label for="user_type" class="col-sm-2 control-label">گروه کاربری </label>
                <div class="col-sm-10">
                  <select class="form-control" style="padding-top: 0px" required="required" ng-model="user_groupp" name="formData[user_group]" id="user_type">
				<?php foreach($groups as $group): ?>
                    <option value="<?php echo $group['id']; ?>"><?php echo $group['name']; ?></option>
				<?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="user_type" class="col-sm-2 control-label">سطح دسترسی</label>
                <div class="col-sm-10">
                  <select class="form-control" style="padding-top: 0px" required="required" name="formData[user_type]" id="user_type">
                    <option value="user">کاربر عادی</option>
                    <option value="admin">مدیر</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="user_type" class="col-sm-2 control-label">سطح اولیه</label>
                <div class="col-sm-10">
                  <select class="form-control" style="padding-top: 0px" required="required" name="formData[level]" id="user_type">
                    <option value="5">E</option>
                    <option value="4">D</option>
                    <option value="3">C</option>
                    <option value="2">B</option>
                    <option value="1">A</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="username" class="col-sm-2 control-label">نام کاربری</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="username" required="required" name="formData[username]">
                </div>
              </div>
              <div class="form-group">
                <label for="password" class="col-sm-2 control-label">کلمه عبور</label>
                <div class="col-sm-10">
                  <input type="password" name="formData[password]" id="password" required="required" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox">
                    <input type="hidden" name="submitted" value="1">
                    <button type="submit" class="btn btn-info pull-right">ثبت اطلاعات</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

    </section>
   </div>
    <div class="control-sidebar-bg"></div>
</div>
    <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/app.js"></script>
</body>
</html>