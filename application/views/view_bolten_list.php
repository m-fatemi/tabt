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
          <div class="box-body">
            <table class="table table-bordered">
              <tr>
                <th style="width: 10px">#</th>
                <th>عنوان</th>
                <th>تاریخ ارسال</th>
                <th style="width: 50px">حذف</th>
              </tr>
            <?php foreach ($bolten as $i => $item): ?>
              <tr>
                <td><?php echo $i+1; ?></td>
                <td><?php echo $item['title']; ?></td>
                <td><?php echo $item['created_at']; ?></td>

                <td>
                  <a href="?submitted=1&action=remove&itemId=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs" title="فعال سازی حساب"><i class="fa fa fa-trash"></i></a>
                </td>
              </tr>
            <?php endforeach ?>
            </table>
          </div>
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