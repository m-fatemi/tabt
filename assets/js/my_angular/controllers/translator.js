
angular.module('vahidapp')

.controller('translator',  function($scope, $timeout, $sce, $http, httpService, nazerService, base, Notification) {

 	$scope.sayHello1 = function() {
		var postData = $.param({csrf_token: $("input:hidden[name='csrf_token']").val()});
		$http.post('http://localhost/welcome/api', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}}).success(function(data){
					console.log(data);
		});
		 // Notification.success({message: 'اطلاعات با موفقیت ثبت شد. با سپاس از همکاری شما...', delay: null});
	}
	
	
	$scope.sayHello = function() {
			$http.get("http://ejrano.com/api?action=cities&id=1").then(function(response) {
				console.log(response.data);
			});
		}




});
