(function() {
  
	var app = angular.module("unmarket");

	var ProductController = function($scope, $routeParams, $http) {

		var ASIN = $routeParams.ASIN;

		var getProduct = function() {
			return $http.get("http://192.168.1.30:52990/api/get.php?type=product&id=" + ASIN)
					.then(function(response) {
						return response.data;
					});
		};

		var onResult = function(response) {
			console.log(ASIN);
			console.dir(response);
			$scope.product = response.data;
		};

		var onError = function(reason) {
			$scope.error = reason;
		};

		getProduct().then(onResult, onError);

		/*
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
		*/


		/*
			var onRepo = function(data) {
	            $scope.repo = data;
	        };

	        var onError = function(reason) {
	            $scope.error = reason;
	        };

	        var reponame = $routeParams.reponame;
	        var username = $routeParams.username;

	        github.getRepoDetails(username, reponame)
	            .then(onRepo, onError);
		*/
	};
  
	app.controller("ProductController", ["$scope", "$routeParams", "$http", ProductController]); // array allows for minification
  
}());