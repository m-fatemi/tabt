<div class="content-wrapper" ng-app="translatorApp" ng-controller="translatorCtrl">
  <section class="content-header">
    <h1>
      ترجمه
      <small>جمله جدید</small>
    </h1>
  </section>
  <section class="content">
    <div ng-show="show_translate_box" class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">ترجمه جدید</h3>
      </div>
      <div class="box-body">
        <form role="form" ng-init="new_sentence()">
          <div class="form-group">
            <label>جمله انگلیسی</label>
            
			<div class="form-control" style="direction: ltr; background-color: #eee; height: auto; min-height:80px">{{sentence_src}}</div>
          </div>

          <div class="form-group" ng-show="trans_aligned">
            <a href="" ng-click="copy_text(1)"><i class="fa  fa-files-o" aria-hidden="true"></i></a>
            <label>ترجمه راهنما</label>
            <!--
            <input type="radio" ng-model="aligned_score" value="4" class="flat-red"> عالی 
            <input type="radio" ng-model="aligned_score" value="3" class="flat-red"> خوب 
            <input type="radio" ng-model="aligned_score" value="2" class="flat-red"> متوسط 
            <input type="radio" ng-model="aligned_score" value="1" class="flat-red"> ضعیف 
            -->
            <textarea class="form-control" rows="3" ng-model="trans_aligned" disabled></textarea>
          </div>

          <div class="form-group" ng-show="trans_machine">
            <a href="" ng-click="copy_text(2)"><i class="fa  fa-files-o" aria-hidden="true"></i></a>
            <label>ترجمه راهنمای 2</label>
            <!--
            <input type="radio" ng-model="machine_score" value="4" class="flat-red"> عالی 
            <input type="radio" ng-model="machine_score" value="3" class="flat-red"> خوب 
            <input type="radio" ng-model="machine_score" value="2" class="flat-red"> متوسط 
            <input type="radio" ng-model="machine_score" value="1" class="flat-red"> ضعیف 
            -->
            <textarea class="form-control" rows="3" ng-model="trans_machine" disabled></textarea>
          </div>

          <div class="form-group">
            <label>ترجمه شما</label>
            <textarea class="form-control" rows="3" ng-model="trans_user" placeholder="لطفا ترجمه صحیح را وارد نمایید..."></textarea>
          </div>

          <input type="hidden" ng-model="sentence_id">
          <div class="row">
            <div class="col-xs-3">
              <button class="btn btn-block btn-success btn-flat" ng-click="submit_translate()">ثبت نهایی</button>
            </div>
            <div class="col-xs-3">
              <button class="btn btn-block btn-primary btn-flat" ng-click="submit_translate(1)">ثبت موقت</button>
            </div>
            <div class="col-xs-3">
              <button class="btn btn-block btn-warning btn-flat" ng-click="new_sentence()">Skip</button>
            </div>
            <div class="col-xs-3">
              <button class="btn btn-block btn-danger btn-flat" ng-click="report(sentence_id)">گزارش جمله</button>
            </div>
          </div>
        </form>
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
      $scope.show_translate_box = 1;
	  
	  
	  
      $scope.submit_translate = function(temp = 0) {
        if(!$scope.trans_user && !temp){
          Notification.error('لطفا تمامی فیلدها را تکمیل کنید.');
          return;
        }
        var postData  = $.param({
                    sentence_id  : $scope.sentence_id,
                    trans_user   : $scope.preProcess($scope.trans_user),
                    temporary    : temp,
                    csrf_token   : '<?php echo $this->security->get_csrf_hash(); ?>'
                  });
        $http.post('<?php echo base_url(); ?>api/submit_translate', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
          .then(function successCallback(response) {
            if(response.data.result == 'OK'){
                Notification.success(response.data.message);
                $scope.new_sentence();
                $scope.trans_user = "";
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

      $scope.copy_text = function(src) {
          if(src == 1)
            $scope.trans_user = $scope.trans_aligned;
          else
            $scope.trans_user = $scope.trans_machine;
      }

      $scope.new_sentence = function(){
        var postData  = $.param({
                    csrf_token : '<?php echo $this->security->get_csrf_hash(); ?>'
                  });
        $http.post('<?php echo base_url(); ?>api/new_sentence', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
          .then(function successCallback(response) {
              if(!response.data.sentence_orig){
                $scope.show_translate_box = 0;
                Notification.info("در حال حاضر برای گروه شما هیچ جمله ای جهت ترجمه وجود ندارد!");
              } else {
                $scope.sentence_src = response.data.sentence_orig;
                $scope.trans_aligned = response.data.trans_aligned;
                $scope.trans_machine = response.data.trans_machine;
                $scope.sentence_id = response.data.id;
              }
          }, function errorCallback(response) {
              Notification.error({message: 'متاسفانه خطالی در پردازش اطلاعات رخ داد!'});
          });
      }
	  
      $scope.report = function(id){
        var postData  = $.param({
					sentence_id : id,
                    csrf_token  : '<?php echo $this->security->get_csrf_hash(); ?>'
                  });
        $http.post('<?php echo base_url(); ?>api/report_sentence', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
          .then(function successCallback(response) {
              if(response.data.result == "OK"){
			    $scope.new_sentence();
                Notification.success(response.data.message);
              } else {
                Notification.error(response.data.message);
              }
          }, function errorCallback(response) {
              Notification.error({message: 'متاسفانه خطالی در پردازش اطلاعات رخ داد!'});
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