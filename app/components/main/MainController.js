(function() {
  
	var app = angular.module("unmarket");

	var MainController = function($scope, $http, $location) {

		$scope.searchText = "Search";
		$scope.searchIndex = "All";
		$scope.itemPage = 1;

		$scope.searchResults = []

		var getSearchResults = function(keyword, searchIndex) {
			return $http.get("/api/get.php?type=search&keyword=" + keyword + "&searchIndex=" + searchIndex)
					.then(function(response) {
						return response.data;
					});
		};

		var getMoreSearchResults = function(keyword, searchIndex) {
			return $http.get("/api/get.php?type=search&keyword=" + keyword + "&searchIndex=" + searchIndex + "&itemPage=" + $scope.itemPage)
					.then(function(response) {
						return response.data;
					});
		};

		var onSearchResults = function(data) {
			$scope.searchResults = data;
			$scope.searchText = "Search";
			$scope.itemPage++;
		};

		var onMoreSearchResults = function(data) {
			$scope.searchResults.push(data);
			$scope.itemPage++;
		};

		var onError = function(reason) {
			$scope.error = reason;
		};

		$scope.search = function(keyword, searchIndex) {
			var regex = RegExp("http://www.amazon.com/([\\w-]+/)?(dp|gp/product)/(\\w+/)?(\\w{10})");
			match = keyword.match(regex);
			if (match) {
				$location.path("/product/" + match[4]);
			}
			else {
				$scope.itemPage = 1;
				$scope.searchText = "Searching...";
				getSearchResults(keyword, searchIndex).then(onSearchResults, onError);
			}
		};

		$scope.moreResults = function(keyword, searchIndex) {
			getMoreSearchResults(keyword, searchIndex).then(onMoreSearchResults);
		}

	};
  
	app.controller("MainController", ["$scope", "$http", "$location", MainController]); // array allows for minification
  
}());