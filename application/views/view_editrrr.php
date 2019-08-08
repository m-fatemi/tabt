  <div class="content-wrapper" ng-app="translatorApp" ng-controller="translatorCtrl">
    <section class="content-header">
      <h1>
        پنل کاربری
        <small>ویرایش جملات</small>
      </h1>
    </section>
    <section class="content">
        <div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title">ویرایش جملات فارسی</h3>
          </div>
          <div class="box-body">
            <form role="form" ng-init="new_sentence()">
              <div class="form-group">
                <label>در صورتی که جمله زیر نیاز به ویرایش(علائم دستوری و نگارشی، فواصل اضافی، نیم فاصله و...) دارد، تغییرات مورد نیاز را اعمال کرده و ثبت نهایی کنید.</label>
                <textarea class="form-control" rows="5" ng-model="sentence_edited"></textarea>
              </div>
              <input type="hidden" ng-model="sentence_id">
              <button class="btn btn-block btn-primary btn-flat" ng-click="submit_sentence()">ثبت اطلاعات</button>
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
  <script src="assets/dist/js/app.js"></script>

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

    $scope.submit_sentence = function() {
      if(!$scope.sentence_edited){
        Notification.error('لطفا تمامی فیلدها را تکمیل کنید.');
        return;
      }
      var postData  = $.param({
                  sentence_id        : $scope.sentence_id,
                  sentence_edited    : $scope.preProcess($scope.sentence_edited),
                  csrf_token         : '<?php echo $this->security->get_csrf_hash(); ?>'
                });
      $http.post('<?php echo base_url(); ?>api/submit_edition', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
        .then(function successCallback(response) {
          if(response.data.result == 'OK'){
              Notification.success(response.data.message);
              $scope.new_sentence();
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

    $scope.angular_test = function() {
      Notification.info($scope.machine_score);
    }

    $scope.new_sentence = function(){
      var postData  = $.param({
                  csrf_token : '<?php echo $this->security->get_csrf_hash(); ?>'
                });
      $http.post('<?php echo base_url(); ?>api/new_fa_sentence', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
        .then(function successCallback(response) {
              $scope.sentence_edited = response.data.sentence_orig;
              $scope.sentence_id = response.data.id;
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