(function() {
  
	var app = angular.module("unmarket");

	var MainController = function($scope, $http) {

		$scope.searchText = "Search";
		$scope.searchIndex = "All";

		var getSearchResults = function(keyword, searchIndex) {
			return $http.get("http://192.168.1.30:52990/api/get.php?type=search&keyword=" + keyword + "&searchIndex=" + searchIndex)
					.then(function(response) {
						return response.data;
					});
		};

		var onSearchResults = function(data) {
			$scope.searchResults = data;
			$scope.searchText = "Search";
			console.dir($scope.searchResults);
		};

		var onError = function(reason) {
			$scope.error = reason;
		};

		$scope.search = function(keyword, searchIndex) {
			$scope.searchText = "Searching...";
			getSearchResults(keyword, searchIndex).then(onSearchResults, onError);
		};

	};
  
	app.controller("MainController", ["$scope", "$http", MainController]); // array allows for minification
  
}());