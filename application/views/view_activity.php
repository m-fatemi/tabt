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
        آمار فعالیت <?php echo $this->input->get('name'); ?>
      </h1>
    </section>
    <section class="content">
		<div class="row">
        <!-- AREA CHART -->
        <div class="col-lg-12 col-xs-12">
		  <div class="box box-primary">
			<div class="box-header with-border">
			  <h3 class="box-title">فعالیت در ۳۰ روز گذشته</h3>
			</div>
			<div class="box-body chart-responsive">
			  <div class="chart" id="line-chart" style="height: 300px;"></div>
			</div><!-- /.box-body -->
		  </div><!-- /.box -->
		</div>
      </div>
	  <?php $trans_count = $this->db->query("SELECT count(*) AS trans_count 
                                        FROM `users`,sentence_bank 
                                        WHERE sentence_bank.translator = users.id
                                        AND users.id = ?
										AND temporary = 0
                                        GROUP BY users.id", $this->input->get("user_id"))->row_array()['trans_count'];
          $edit_count = $this->db->query("SELECT count(*) AS edit_count 
                                        FROM `users`,sentence_bank 
                                        WHERE sentence_bank.editor = users.id
                                        AND users.id = ?
                                        GROUP BY users.id", $this->input->get("user_id"))->row_array()['edit_count'];
			
    ?>
	<div class="row">
	<div class="col-lg-12 col-xs-12">
		<div class="box box-primary">
			<div class="box-header with-border">
			  <h3 class="box-title">آمار کل</h3>
			</div>
			<div class="box-body">
			<table class="table table-bordered">
				<thead>
					<th>تعداد کل ترجمه‌ها</th>
					<th>تعداد کل بازبینی‌ها</th>
				</thead>
				<tbody>
					<td><?php echo $trans_count; ?> </td>
					<td><?php echo $edit_count; ?> </td>
				</tbody>
			</table>
			</div><!-- /.box-body -->
		  </div><!-- /.box -->
        </div>
	</div>
    </section>
  </div>
<div class="control-sidebar-bg"></div>
</div>
    <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/morris/morris.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/fastclick/fastclick.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
	<script>
      $(function () {
        "use strict";
		
		
		
		
		 <?php 
		 $user_id = $this->input->get('user_id');
		 $user_stats = $this->db->query("SELECT IFNULL(num_edit, 0) AS num_edits, IFNULL(num_trans, 0) AS num_translates, GREATEST(IFNULL(edit_date, 0), IFNULL(trans_date, 0)) AS date FROM (SELECT * FROM (SELECT * FROM (SELECT COUNT(*) AS num_edit, DATE_FORMAT(edited_at, '%Y-%m-%d') AS edit_date FROM sentence_bank WHERE removed = 0 AND temporary = 0 AND editor = ? GROUP BY DATE_FORMAT(edited_at, '%Y-%m-%d')) AS tbl1 WHERE DATE(edit_date) >= DATE_SUB(CURDATE(), INTERVAL 30 Day) AND DATE(edit_date) <= NOW()) AS t1 LEFT JOIN (SELECT * FROM (SELECT COUNT(*) AS num_trans, DATE_FORMAT(translated_at, '%Y-%m-%d') AS trans_date FROM sentence_bank WHERE removed = 0 AND temporary = 0 AND translator = ? GROUP BY DATE_FORMAT(translated_at, '%Y-%m-%d')) AS tbl1 WHERE DATE(trans_date) >= DATE_SUB(CURDATE(), INTERVAL 30 Day) AND DATE(trans_date) <= NOW()) AS t2 ON t1.edit_date = t2.trans_date UNION	SELECT * FROM (SELECT * FROM (SELECT COUNT(*) AS num_edit, DATE_FORMAT(edited_at, '%Y-%m-%d') AS edit_date FROM sentence_bank WHERE removed = 0 AND temporary = 0 AND editor = ? GROUP BY DATE_FORMAT(edited_at, '%Y-%m-%d')) AS tbl1 WHERE DATE(edit_date) >= DATE_SUB(CURDATE(), INTERVAL 30 Day) AND DATE(edit_date) <= NOW()) AS t1 RIGHT JOIN (SELECT * FROM (SELECT COUNT(*) AS num_trans, DATE_FORMAT(translated_at, '%Y-%m-%d') AS trans_date FROM sentence_bank WHERE removed = 0 AND temporary = 0 AND translator = ? GROUP BY DATE_FORMAT(translated_at, '%Y-%m-%d')) AS tbl1 WHERE DATE(trans_date) >= DATE_SUB(CURDATE(), INTERVAL 30 Day) AND DATE(trans_date) <= NOW()) AS t2 ON t1.edit_date = t2.trans_date) AS tbl3 ORDER BY date DESC", array($user_id, $user_id, $user_id, $user_id))->result_array(); 
		//print_r($user_stats);
		?>
		var line = new Morris.Line({
          element: 'line-chart',
          resize: true,
          data: [
			<?php
				if(count($user_stats)){

					$j = count($user_stats) - 1;;
					for($i = 30; $i >= 0; $i--){
						$d=strtotime("-" . $i ." days");
						if(date_format(date_create($user_stats[$j]['date']),"Y-m-d") ==  date("Y-m-d", $d)){
							echo "{y: '".date("Y-m-d", $d)."', item1:".$user_stats[$j]['num_translates'].", item2: ".$user_stats[$j]['num_edits']."},";
						if($j > 0)
							$j--;
						}
						else
							echo "{y: '".date("Y-m-d", $d)."', item1:0, item2: 0},";
					}
				} else {
					for($i = 30; $i >= 0; $i--){
						$d=strtotime("-" . $i ." days");
						echo "{y: '".date("Y-m-d", $d)."', item1:0, item2: 0},";
					}
				}
			?>
          ],
          xkey: 'y',
          xLabels: 'day',
		  smooth: true,
          ykeys: ['item1', 'item2'],
          labels: ['ترجمه', 'بازبینی'],
          lineColors: ['#f39c12', '#00a65a'],
          hideHover: 'auto'
        });
		
		
      });
    </script>
</body>
</html>