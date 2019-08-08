<div class="content-wrapper" ng-app="translatorApp" ng-controller="translatorCtrl">
  <section class="content-header">
    <h1>
      ترجمه
      <small>جمله های لیست موقت</small>
    </h1>
  </section>
  <section class="content">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">لیست ترجمه های موقت</h3>
      </div>
      <div class="box-body" ng-init="get_temporary_sentences()">
		<table class="table table-bordered table-hover">
			<tr ng-repeat="item in temp_sentences">
				<td>
					<form role="form">
					  <div class="form-group">
						<label>جمله اصلی</label>
						<div class="form-control" style="direction: ltr; background-color: white; height: auto;">{{item.sentence_orig}}</div>
					  </div>
					  <div class="form-group">
						<label>ترجمه شما</label>
						<textarea class="form-control" ng-model="item.trans_user" rows="2">{{item.trans_user}}</textarea>
					  </div>
					  <div class="form-group">
						  <button class="btn btn-block btn-primary btn-flat" ng-click="submit_translate(item)">ثبت نهایی این ترجمه</button>
					  </div>
					</form>
				</td>
			</tr>
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
	  $scope.submit_translate = function(sentence) {
        if(!sentence.trans_user){
          Notification.error('لطفا ترجمه جمله را وارد کنید!');
          return;
        }
        var postData  = $.param({
                    sentence_id   : sentence.id,
                    trans_user    : $scope.preProcess(sentence.trans_user),
                    temporary     : 0,
                    csrf_token    : '<?php echo $this->security->get_csrf_hash(); ?>'
                  });
        $http.post('<?php echo base_url(); ?>api/submit_translate', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
          .then(function successCallback(response) {
            if(response.data.result == 'OK'){
                Notification.success(response.data.message);
                $scope.get_temporary_sentences();
            } 
            else if(response.data.result == 'INFO'){
              Notification.info(response.data.message);

            }
            else {
              Notification.error(response.data.message);
            }
          }, function errorCallback(response) {
              Notification.error({message: 'متاسفانه خطالی در پردازش اطلاعات رخ داد!'});
          });
      }

    $scope.get_temporary_sentences = function(){
      var postData  = $.param({
                  csrf_token : '<?php echo $this->security->get_csrf_hash(); ?>'
                });
      $http.post('<?php echo base_url(); ?>api/get_temporary_sentences', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
        .then(function successCallback(response) {
              console.log(response.data);
              if(response.data.length == 0){
			    $scope.temp_sentences = [];
                Notification.info("هیچ جمله ای در لیست موقت ندارید!");
              }
              else {
                $scope.temp_sentences = response.data;
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
      $scope.eraser = function() {
        $scope.trans_result = null;
        // console.log(document.getElementsByClassName('editable ltr src'));
        // document.getElementById('src').outerText = "";
        // document.getElementById('tgt').outerText = "";
        // console.log(document.getElementsByClassName('editable ltr src'));
      }

      $scope.index_max = function(numArray) {
        return numArray.indexOf(Math.max(...numArray));
      }

      $scope.preProcess = function(text){
        if(text == null)
          return null;
        var str = $scope.stripTags(text);
        str = str.replace("\t", "");
        str = str.replace("\r", "");
        str = str.replace("\n", "");
        str = str.replace("\\n", "");
        str = str.replace(".", " . ");
        str = str.replace(",", " , ");
        str = str.replace("?", " ? ");
        str = str.replace("!", " ! ");
        str = str.replace("'", " ' ");
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