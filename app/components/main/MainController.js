(function() {
  
	var app = angular.module("unmarket");

	var MainController = function($scope, $http) {

		var getSearchResults = function(keyword) {
			return $http.get("http://192.168.1.30:52990/api/get.php?type=search&keyword=" + keyword)
					.then(function(response) {
						return response.data;
					});
		};

		var onSearchResults = function(data) {
			$scope.searchResults = data;
			console.dir($scope.searchResults);
		};

		var onError = function(reason) {
			$scope.error = reason;
		};

		$scope.search = function(keyword) {
			getSearchResults(keyword).then(onSearchResults, onError);
		};

	};
  
	app.controller("MainController", ["$scope", "$http", MainController]); // array allows for minification
  
}());