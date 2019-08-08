  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        مدیریت
        <small>پنل مدیریت کافه مشاور</small>
      </h1>
    </section>
    <section class="content">
      <!-- BAR CHART -->
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title pull-right">نمودار فعالیت شما</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body chart-responsive">
          <div class="chart" id="bar-chart" style="height: 300px;"></div>
        </div>
      </div>
    </section>
  </div>
<div class="control-sidebar-bg"></div>
</div>
    <script src="assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="assets/plugins/morris/morris.min.js"></script>
    <script src="assets/plugins/fastclick/fastclick.min.js"></script>
    <script src="assets/dist/js/app.js"></script>
    <script src="assets/dist/js/demo.js"></script>
    <script>
      $(function () {
        "use strict";
        //BAR CHART
        var bar = new Morris.Bar({
          element: 'bar-chart',
          resize: true,
          data: [
            {y: 'هفته 1', a: 100, b:120},
            {y: 'هفته 2', a: 75, b:60},
            {y: 'هفته 3', a: 50, b:42},
            {y: 'هفته 4', a: 75, b:200, },
            {y: 'هفته 5', a: 50, b:142},
            {y: 'هفته 6', a: 75, b:100},
            {y: 'هفته 7', a: 85, b:90},
            {y: 'هفته 8', a: 57, b:68},
            {y: 'هفته 9', a: 75, b:50},
            {y: 'هفته 10', a: 175, b:135},
            {y: 'هفته 12', a: 100, b:40}
          ],
          barColors: ['#00a65a', '#00c0ef'],
          xkey: 'y',
          ykeys: ['a', 'b'],
          labels: ['ترجمه', 'ویرایش'],
          hideHover: 'auto'
        });
      });
    </script>
  </body>
</html>