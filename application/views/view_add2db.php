<div class="content-wrapper" ng-app="translatorApp" ng-controller="translatorCtrl">
    <section class="content-header">
      <h1>
        مدیریت جملات
      </h1>
    </section>
    <section class="content">
		<div class="row">
			<div class="box box-primary">
            <div class="box-header with-border">
				<h3 class="box-title">جملات جدید</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form">
				<div class="box-body">
					<div class="form-group">
					  <label for="exampleInputFile">جملات انگلیسی</label>
					  <input type="file" id="src_sentences" on-read-file="showContent($fileContent, 'sentences_src')">
					  <p class="help-block">در هر سطر یک جمله</p>
					</div>
					<div class="form-group">
					  <label for="trans_user">ترجمه جملات</label>
					  <input type="file" id="trans_user" on-read-file="showContent($fileContent, 'trans_user')">
					  <p class="help-block">هر سطر ترجمه متناظر با جملات انگلیسی</p>
					</div>
					<div class="form-group">
					  <?php $groups = $this->db->query("SELECT * FROM groups")->result_array(); ?>
						<label for="user_type" class="control-label">گروه بندی</label>
						  <select class="form-control" style="padding-top: 0px" ng-model="group" required="required" name="formData[user_group]" id="user_type">
							<?php foreach($groups as $group): ?>
								<option value="<?php echo $group['id']; ?>"><?php echo $group['name']; ?></option>
							<?php endforeach ?>
						  </select>
					</div>
					
					
					<div class="form-group">
						<label for="title" class="control-label">عنوان</label>
						<input class="form-control" type="text" id="title" ng-model="title" name="formData[title]"/>
					</div>
					
					
					<div class="form-group">
					  <?php $users = $this->db->query("SELECT id, name, level FROM users")->result_array(); ?>
						<label for="user_type" class="control-label">مترجم</label>
						  <select class="form-control" style="padding-top: 0px" ng-model="translator" required="required" name="formData[translator]" id="user_type">
							<?php foreach($users as $user): ?>
								<option value='{"id":"<?php echo $user['id']; ?>","level":"<?php echo $user["level"]; ?>"}'><?php echo $user['name']; ?></option>
							<?php endforeach ?>
						  </select>
					</div>
					<div class="form-group">
					  <label>
						  <input type="checkbox" checked="checked" ng-model="review_need">
						  نیاز به بازبینی
						</label>
					</div>
				</div><!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" ng-model="byn" ng-click="upload_sentences()" class="btn btn-primary">ارسال </button>
                <p ng-show="uploading" ng-model="ytg" class="help-block"><i class="fa fa-spinner fa-pulse fa-lg fa-fw"></i> در حال ارسال، لطفا صبر کنید...</p>
              </div>
            </form>
          </div><!-- /.box -->
      </div>
      <div class="row" ng-show="sentences.src">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">نمایش جملات</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered table-hover">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>جمله انگلیسی</th>
                  <th ng-show="sentences.trans_user">ترجمه تراز شده</th>
                </tr>
                <tr ng-repeat="x in range(num_rows)">
                  <td>{{$index+1}}</td>
                  <td style="direction: ltr;">{{sentences.src[$index]}}</td>
                  <td ng-show="sentences.trans_user.length">{{sentences.trans_user[$index]}}</td>
                </tr>
              </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
      </div><!-- /.row -->
    </section>
</div>
<div class="control-sidebar-bg"></div>
</div>
    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script>
    $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/app.js"></script>

	
<script type="text/javascript">
  var app = angular.module('translatorApp', ['angular-loading-bar', 'ui-notification'])
	.config(function(NotificationProvider) {
        NotificationProvider.setOptions({
            delay: 5000,
            startTop: 20,
            startRight: 80,
            verticalSpacing: 20,
            horizontalSpacing: 20,
            positionX: 'right',
            positionY: 'bottom',
            replaceMessage: false
        });   
    });

    app.controller('translatorCtrl', function($scope, $http, Notification) {
		$scope.sentences = {};
		$scope.uploading = false;
		$scope.sentences.trans_user = [];
	$scope.showContent = function(content, name){
        switch(name){
			case 'sentences_src':
				$scope.sentences.src = content.split('\n');
				break;
			case 'trans_user':
				$scope.sentences.trans_user = content.split('\n');
				break;
        }
		$scope.num_rows = Math.max($scope.sentences.src.length, $scope.sentences.trans_user.length);
    }
	

	
    $scope.upload_sentences = function(){
		$scope.uploading = true;
		if(!$scope.sentences.src){
			Notification.error('لطفا فایل جملا انگلیسی را وارد کنید!');
			$scope.uploading = false;
			return;
		}
		if(!$scope.group){
			Notification.error('لطفاْ گروه بندی جملات را مشخص کنید!');
			$scope.uploading = false;
			return;
		}
		if(!$scope.translator){
			Notification.error('مترجم را مشخص کنید!');
			$scope.uploading = false;
			return;
		}
		var num_sentences = $scope.sentences.src.length;
		$scope.output_final = new Array();

		if($scope.sentences.trans_user.length > 0){
			if($scope.sentences.trans_user.length != num_sentences){
				Notification.error("تعداد سطرها در فایل‌ها برابر نیستند!");
				$scope.uploading = false;
				return;
			}
		}

		var user_id = JSON.parse($scope.translator).id;
		var user_level = JSON.parse($scope.translator).level;

		for(var i = 0; i < $scope.sentences.src.length; i++){
			$scope.output_final[i] = {};
			$scope.output_final[i].sentence_orig = $scope.sentences.src[i];
			$scope.output_final[i].upload_by = <?php echo $this->session->userdata('user_id'); ?>;
			$scope.output_final[i].sentence_group = $scope.group;
			$scope.output_final[i].title = $scope.title;

			if($scope.sentences.trans_user.length > 0)
				$scope.output_final[i].trans_user = $scope.sentences.trans_user[i];
				
			$scope.output_final[i].translator = user_id;
			if($scope.review_need){
				$scope.output_final[i].prob_edit = Math.max(0, Math.random() - (0.5 - (user_level / 10)));
			}
		}

		var postData  = $.param({
            sentences     : $scope.output_final,
            csrf_token    : '<?php echo $this->security->get_csrf_hash(); ?>'
        });
		$http.post('<?php echo base_url(); ?>api/add_sentences', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
        .then(function successCallback(response) {
			if(response.data.result == 'OK'){
				Notification.success(response.data.message);
			}
			else if(response.data.result == 'ERROR'){
				Notification.error(response.data.message);
			}
			$scope.uploading = false;
			// Notification.success({message: 'جملات با موفقیت در پایگاه داده ثبت شدند.'});
        }, function errorCallback(response) {
            Notification.error({message: 'متاسفانه خطالی در پردازش اطلاعات رخ داد!'});
			$scope.uploading = false;
        });
    }

		$scope.range = function(num) {
			var ratings = []; 
			for (var i = 0; i < num; i++) {
				ratings.push(i) 
			}
			return ratings;
		}
	});

	app.directive('onReadFile', function ($parse) {
		return {
			restrict: 'A',
			scope: false,
			link: function(scope, element, attrs) {
				var fn = $parse(attrs.onReadFile);
				element.on('change', function(onChangeEvent) {
				  var reader = new FileReader();
				  reader.onload = function(onLoadEvent) {
					scope.$apply(function() {
					  fn(scope, {$fileContent:onLoadEvent.target.result});
					});
				  };
				  reader.readAsText((onChangeEvent.srcElement || onChangeEvent.target).files[0]);
				});
			}
		};
	});
	
	
</script>
</body>
</html>