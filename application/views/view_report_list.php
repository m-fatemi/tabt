<div class="content-wrapper" ng-app="translatorApp" ng-controller="translatorCtrl">
  <section class="content-header">
    <h1>
      ویرایش 
      <small>جمله های گزارش شده</small>
    </h1>
  </section>
  <section class="content">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">جملات گزارش شده گروه <?php echo $this->session->userdata('group_name'); ?></h3>
      </div>
      <div class="box-body" ng-init="get_reported_sentences()">
		<table class="table table-bordered table-striped">
			<thead>
				<th>توسط</th>
				<th>جمله</th>
				<th>عملیات</th>
			</thead>
			<tbody>
				<tr ng-repeat="item in reported_sentences">
					<td style="width: 50px;">{{item.reporter}}</td>
					<td>
						<textarea class="form-control" style="direction: ltr" ng-model="item.sentence_orig" rows="2">{{item.sentence_orig}}</textarea>
					</td>
					<td style="width: 100px;">
						<button class="btn btn-block btn-success btn-flat" ng-click="submit_sentence_edit(item)">ثبت </button>
						<button class="btn btn-block btn-danger btn-flat" ng-click="remove_sentence(item)">حذف </button>
					</td>
				</tr>
			</tbody>
			
		</table>
      </div>
    </div>
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
	  $scope.temp_sentences = [];
	  
	  $scope.remove_sentence = function(sentence) {
        var postData  = $.param({
                    sentence_id   	 : sentence.id,
                    csrf_token    	 : '<?php echo $this->security->get_csrf_hash(); ?>'
                  });
        $http.post('<?php echo base_url(); ?>api/remove_sentence', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
          .then(function successCallback(response) {
            if(response.data.result == 'OK'){
                Notification.success(response.data.message);
                $scope.get_reported_sentences();
            } 
            else if(response.data.result == 'INFO'){
              Notification.info(response.data.message);

            }
            else {
              Notification.error(response.data.message);
            }
          }, function errorCallback(response) {
              Notification.error({message: 'متاسفانه خطایی در پردازش اطلاعات رخ داد!'});
          });
      }
	  
	  $scope.submit_sentence_edit = function(sentence) {
        var postData  = $.param({
                    sentence_id   	 : sentence.id,
                    sentence_orig    : $scope.preProcess(sentence.sentence_orig),
                    csrf_token    	 : '<?php echo $this->security->get_csrf_hash(); ?>'
                  });
        $http.post('<?php echo base_url(); ?>api/submit_sentence_edit', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
          .then(function successCallback(response) {
            if(response.data.result == 'OK'){
                Notification.success(response.data.message);
                $scope.get_reported_sentences();
            } 
            else if(response.data.result == 'INFO'){
              Notification.info(response.data.message);

            }
            else {
              Notification.error(response.data.message);
            }
          }, function errorCallback(response) {
              Notification.error({message: 'متاسفانه خطایی در پردازش اطلاعات رخ داد!'});
          });
      }

    $scope.get_reported_sentences = function(){
      var postData  = $.param({
                  csrf_token : '<?php echo $this->security->get_csrf_hash(); ?>'
                });
      $http.post('<?php echo base_url(); ?>api/get_reported_sentences', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
        .then(function successCallback(response) {
              console.log(response.data);
              if(response.data.length == 0){
			    $scope.reported_sentences = [];
                Notification.info("در حال حاضر هیچ جمله در لیست گزارش شده ها وجود ندارد!");
              }
              else {
                $scope.reported_sentences = response.data;
              }
        }, function errorCallback(response) {
            Notification.error({message: 'متاسفانه خطایی در پردازش اطلاعات رخ داد!'});
        });
    }
    
    //*****************************************************************************//
    //////////////////////////////////// Utils //////////////////////////////////////
    //*****************************************************************************//

      $scope.stripTags = function(text) {
          return text ? String(text).replace(/<[^>]+>/gm, '') : '';
      }
      $scope.allTrim = function(text) {
          return text.replace(/\s+/g,' ').replace(/^\s+|\s+$/,'');
      }

      $scope.index_max = function(numArray) {
        return numArray.indexOf(Math.max(...numArray));
      }

      $scope.preProcess = function(text){
        if(text == null)
          return null;
        //var str = $scope.stripTags(text);
        var str = text;
        str = str.replace("\t", "");
        str = str.replace("\r", "");
        str = str.replace("\n", "");
        str = str.replace("\\n", "");
        //str = str.replace(".", " . ");
        str = str.replace(",", " , ");
        str = str.replace("?", " ? ");
        str = str.replace("!", " ! ");
        //str = str.replace("'", " ' ");
        str = str.replace("&nbsp;", "");
        str = $scope.allTrim(str);
        return str;
      }
    //*****************************************************************************//
    //////////////////////////////////// Utils //////////////////////////////////////
    //*****************************************************************************//
  });
</script>

</body>
</html>