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
            <form action="" method="post">
              <input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <input type="hidden" name="submitted" value="1">
              <input type="hidden" name="action" value="update_levels">
              <input type="submit" value="برزو رسانی سطح کاربران" title="آخرین بروز رسانی: <?php echo $last_levels_update; ?>" class="btn btn-warning pull-left"/>
            </form>
          </div><!-- /.box-header -->
          <div class="box-body" style="overflow-x: scroll">
            <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th>نام</th>
                <th>گروه</th>
                <th>سطح</th>
                <th>تعداد ترجمه‌</th>
                <th>تعداد بازبینی</th>
                <th>میانگین ترجمه‌ روزانه</th>
                <th>میانگین‌ بازبینی روزانه</th>
                <th style="width: 100px">وضعیت</th>
                <th style="width: 100px">عملیات</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $i => $item): ?>
              <tr>
                <td><?php echo $i+1; ?></td>
                <td><a href="<?php echo base_url('admin/user_activity') . '?user_id=' . $item['id'] . '&name=' . $item['name']; ?>"><?php echo $item['name']; ?></a></td>
                <td><?php echo $item['group_name']; ?></td>
                <td><?php echo $this->utils->level_name($item['level']); ?></td>
                <!-- <td>
                  <div class="progress progress-xs">
                    <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                  </div>
                </td> -->
                <td><span><?php echo $item['num_translate']; ?></span></td>
                <td><span><?php echo $item['num_edit']; ?></span></td>
                <td><span class="badge bg-<?php if($item['avg_translate'] < 10) echo 'red'; else if($item['avg_translate'] >= 10 && $item['avg_translate'] < 50) echo 'yellow'; else echo 'green';
                      ?>"><?php echo $item['avg_translate']; ?></span></td>
                <td><span class="badge bg-<?php if($item['avg_edit'] < 10) echo 'red'; else if($item['avg_edit'] >= 10 && $item['avg_edit'] < 50) echo 'yellow'; else echo 'green'; ?>"><?php echo $item['avg_edit']; ?></span>
                </td>
                <td>
                <?php if($item['isActive']): ?>
                  <span class="badge bg-green">فعال</span>
                <?php else: ?>
                  <span class="badge bg-red">مسدود</span>
                <?php endif ?>
                </td>
                <td>
                  <a href="?submitted=1&action=unlock&user_id=<?php echo $item['id']; ?>" class="btn btn-success btn-xs" title="فعال سازی حساب"><i class="fa fa fa-unlock"></i></a>
                  <a href="?submitted=1&action=lock&user_id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs" title="مسدود کردن حساب"><i class="fa fa fa-lock"></i></a>
                </td>
              </tr>
            <?php endforeach ?>
            </tbody>
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