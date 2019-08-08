<div class="content-wrapper" ng-app="translatorApp" ng-controller="translatorCtrl">
  <section class="content-header">
    <h1>
      اصلاح ترجمه
      <small>بازبینی ترجمه های دیگران</small>
    </h1>
  </section>
  <section class="content">
    <div ng-show="show_edit_box" class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">اصلاح ترجمه</h3>
      </div>
      <div class="box-body">
        <form role="form" ng-init="new_sentence()">
          <div class="form-group">
            <label>جمله اصلی</label>
			<div class="form-control" style="direction: ltr; background-color: #eee; height: auto; min-height: 80px">{{sentence_src}}</div>
          </div>

          <div class="form-group">
            <a href="" ng-click="copy_text(1)"><i class="fa  fa-files-o" aria-hidden="true"></i></a>
            <label>ترجمه</label>
            
            
            <textarea class="form-control" rows="3" ng-model="translate" disabled></textarea>
            امتیاز ترجمه: &nbsp;&nbsp;
            <input type="radio" ng-model="score" value="5" class="flat-red"> 5 &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" ng-model="score" value="4" class="flat-red"> 4 &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" ng-model="score" value="3" class="flat-red"> 3 &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" ng-model="score" value="2" class="flat-red"> 2 &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" ng-model="score" value="1" class="flat-red"> 1 &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" ng-model="score" value="0" class="flat-red"> 0 &nbsp;&nbsp;&nbsp;&nbsp;
          </div>

          <div class="form-group">
            <label>ترجمه شما (ایرادات ترجمه و ایرادات نگارشی را اصلاح کنید)</label>
            <textarea class="form-control" rows="3" ng-disabled="score == '5'" ng-model="translate_edited" placeholder="لطفا ترجمه صحیح را وارد نمایید..."></textarea>
          </div>

          <input type="hidden" ng-model="sentence_id">
          <div class="row">
            <div class="col-xs-9">
              <button class="btn btn-block btn-primary btn-flat" ng-click="submit_edit()">ثبت اطلاعات</button>
            </div>
            <div class="col-xs-3">
              <button class="btn btn-block btn-danger btn-flat" ng-click="new_sentence()">Skip</button>
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
      $scope.show_edit_box = 1;
    $scope.submit_edit = function() {
      // if(!$scope.aligned_score || !$scope.machine_score || !$scope.translate_edited){
      //   Notification.error('لطفا تمامی فیلدها را تکمیل کنید.');
      //   return;
      // }

      if(!$scope.score){
        Notification.error('لطفا امتیاز ترجمه را مشخص کنید!');
        return;
      }
      
      if($scope.score != '5' && !$scope.translate_edited){
        Notification.error('لطفا ترجمه صحیح و نهایی را وارد کنید!');
        return;
      }

      var final_translate = $scope.translate_edited;
      if($scope.score == '5')
        final_translate = null;
      var postData  = $.param({
                  sentence_id     : $scope.sentence_id,
                  trans_edited    : $scope.preProcess($scope.translate_edited),
                  score           : $scope.score,
                  // aligned_score : $scope.aligned_score,
                  csrf_token    : '<?php echo $this->security->get_csrf_hash(); ?>'
                });
      $http.post('<?php echo base_url(); ?>api/submit_edition', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
        .then(function successCallback(response) {
          if(response.data.result == 'OK'){
              Notification.success(response.data.message);
              $scope.new_sentence();
              $scope.translate_edited = "";
              $scope.score = "";
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
          $scope.translate_edited = $scope.translate;
        else
          $scope.translate_edited = $scope.trans_machine;
    }

    $scope.new_sentence = function(){
      var postData  = $.param({
                  csrf_token : '<?php echo $this->security->get_csrf_hash(); ?>'
                });
      $http.post('<?php echo base_url(); ?>api/new_sentence_edit', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
        .then(function successCallback(response) {
              // console.log(response.data);
              if(!response.data.sentence_orig){
                $scope.show_edit_box = 0;
                Notification.info("در حال حاضر برای گروه شما هیچ جمله ای جهت بازبینی وجود ندارد!");
              }
              else {
                $scope.sentence_src = response.data.sentence_orig;
                $scope.translate    = response.data.trans_user;
                $scope.sentence_id  = response.data.id;
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