
angular.module('vahidapp')
.factory('httpService', function($http, $timeout) {




  
  function validateForm(inputArray, formClass) {

    /* inputArray :
    *  get data from ng-model and push them into an array like :
    *  var inputValueArray  = new Array(name, subj, email, content);
    *  formClass : class of form that form className0 till (number of inputs)-1 */
    var err = 0;
    for (var i = 0; i < inputArray.length; i++) {
      if(!inputArray[i] || inputArray[i] == "") {
        angular.element(document.querySelector('.'+formClass+i)).addClass('border_red');
        var err = 1;
      } else { angular.element(document.querySelector('.'+formClass+i)).removeClass('border_red'); }
    }
    return err;

  };




  
  var handleRequest = function(inputArray, url, onSuccess, onErr, onHold, postName) {

    /* inputArray is the same as mentioned in validateForm
     onSuccess, onErr, onHold, these are functions. */
    onHold();
    var request = $http({
          url: url,
          method: "POST",
          data: $.param(postName),
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      })
    .then(function(response) {
      // at first "return request" works!! But in controller we have .then() , so the promise waits for response.
      return handleSuccessPromise(response, onSuccess, onErr);
    }, function(error) {
        console.log(error);
        onErr();
    });
    return request;

  };




  function handleSuccessPromise(response, onSuccess, onErr) {

    // "res" is an index that retrieved from json_encode in php
    // it's whether TRUE or FALSE
    if (response.data.res) {
        onSuccess();
        return response;
    } 
    else {
      onErr();
    }
    return response;
  }




  return {
  	validateForm : validateForm,
    handleRequest : handleRequest,
  };


})




.factory('nazerService', function($http, $timeout, base, httpService, Notification) {


  function getCity (state_id) {
    var cities = new Array();
    var url = base.url + 'state/get_city_by_state_api';
    var inputValueArray  = new Array(state_id);
    var sent = httpService.handleRequest(inputValueArray, url, function() {}, function() {}, function() {}, { retrieveState:inputValueArray , csrf_token: $("input:hidden[name='csrf_token']").val()})
      .then(function(result) {
        var res = result.data.queryResult;
        for (var i = 0; i < res.length; i++) {
          cities[i] = result.data.queryResult[i];
        }
        return cities;
      });
    return sent;
  }
  
  function getCity2 (state_id) {
    var cities = new Array();
    var url = base.url + 'state/get_city_by_state_api';
    var inputValueArray  = new Array(state_id);
    var sent = httpService.handleRequest(inputValueArray, url, function() {}, function() {}, function() {}, { retrieveState:inputValueArray , csrf_token: $("input:hidden[name='csrf_token']").val()})
      .then(function(result) {
        var res = result.data.queryResult;
        for (var i = 1; i < res.length+1; i++) {
          cities[i] = result.data.queryResult[i-1];
        }
        return cities;
      });
    return sent;
  }



  function getHafarName(vezarat_code, className) {
    var url = base.url + 'hafar/get_hafar_by_id_api';
    var inputValueArray  = new Array(vezarat_code);
    var sent = httpService.handleRequest(inputValueArray, url, function() {}, function() {}, function() {}, { retrieveHafar:inputValueArray , csrf_token: $("input:hidden[name='csrf_token']").val()})
      .then(function(result) {
        var db = result.data.queryResult[0];
        angular.element(document.querySelector("."+className)).addClass('display_none');
        return db.name;
      });
    return sent;
  }


  function getNazerName(nazer_code, className) {
    var url = base.url + 'nazer/get_nazer_by_id_api';
    var inputValueArray  = new Array(nazer_code);
    var sent = httpService.handleRequest(inputValueArray, url, function() {}, function() {}, function() {}, { retrieveNazer:inputValueArray , csrf_token: $("input:hidden[name='csrf_token']").val()})
      .then(function(result) {
        var db = result.data.queryResult[0];
        angular.element(document.querySelector("."+className)).addClass('display_none');
        return db.nazer_firstname + " " + db.nazer_lastname;
      });
    return sent;
  }


  function insertContract (dataForValidation, dataForServer, paramUrl) {

    var err = httpService.validateForm(dataForValidation, "firstTabValidate");
    if (err == 1) return $.when(null);
    
    var url = base.url + paramUrl;
    var onErr = function() {Notification.error('خطا در برقراری ارتباط')};
    var sent = httpService.handleRequest(dataForServer, url, function() {}, onErr, function() {}, { new_contract:dataForServer , csrf_token: $("input:hidden[name='csrf_token']").val()})

    .then(function(result) {
      console.log(result);
      if (!result.data.loginResult) {
        window.location.assign("login");
        return $.when(null);
      }
      if(result.data.queryResult) {
        Notification.success('با موفقیت ثبت شد.');
        return result.data.queryResult;
      }
      else {
        Notification.error('ثبت با مشکل روبرو است. شماره قرارداد تکراری می باشد.');
        return $.when(null);
      }

    });

    return sent;
  }


  function retrieveDataByID (UniqeKey, fakeTextClass, paramUrl) {
    var url = base.url + paramUrl;
    var inputValueArray  = new Array(UniqeKey);
    var sent = httpService.handleRequest(inputValueArray, url, function() {}, function() {}, function() {}, { postName:inputValueArray , csrf_token: $("input:hidden[name='csrf_token']").val()})
    .then(function(result) {
      angular.element(document.querySelectorAll("."+fakeTextClass)).addClass('display_none');
      return result.data.queryResult[0];
    });
    return sent;

  }


  return {
    getCity : getCity,
    getCity2 : getCity2,
    getHafarName : getHafarName,
    getNazerName : getNazerName,
    insertContract : insertContract,
    retrieveDataByID : retrieveDataByID
  }


});