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
        مدیریت کاربران
      </h1>
    </section>
    <section class="content">
      <div class="row">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">لیست کاربران</h3>
          </div><!-- /.box-header -->

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


          <div class="box-body">
            
          <form action="" method="post" accept-charset="utf-8" class="form-horizontal">
            <input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_hash(); ?>" />
            <div class="form-group">
              <label for="title" class="col-sm-2 control-label">عنوان: </label>
              <div class="col-sm-10">
                <input type="text" name="formData[title]" id="mobile" required="required" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label for="body" class="col-sm-2 control-label">متن اطلاعیه: </label>
              <div class="col-sm-10">
                <textarea id="editor1" name="formData[body]" rows="10" cols="80"></textarea>
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
          </form>

          </div>
        </div>
      </div>
    </section>
  </div>
<div class="control-sidebar-bg"></div>
</div>
    <script src="<?php echo base_url(); ?>assets/js/ckeditor/ckeditor.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/app.js"></script>
    <script>
      $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('editor1');
        //bootstrap WYSIHTML5 - text editor
        $(".textarea").wysihtml5();
      });
    </script>
</body>
</html>