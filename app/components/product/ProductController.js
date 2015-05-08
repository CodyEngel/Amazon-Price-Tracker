(function() {
  
	var app = angular.module("unmarket");

	var ProductController = function($scope, $routeParams, $http, $filter) {

		var ASIN = $routeParams.ASIN;

		var getProduct = function() {
			return $http.get("http://192.168.1.30:52990/api/get.php?type=product&id=" + ASIN)
					.then(function(response) {
						return response.data;
					});
		};

		$scope.addPriceWatchItem = function(desiredPrice, email)
		{
			$http.get("http://192.168.1.30:52990/api/put.php?type=priceWatch&desiredPrice=" + desiredPrice + "&email=" + email + "&asin=" + ASIN + "&currentPrice=" + $scope.product.Price);
		};

		$scope.formatNumber = function(desiredPrice)
		{
			$scope.desiredPrice = $filter('number')(desiredPrice, 2)
		}

		var onResult = function(response) {
			console.log(ASIN);
			console.dir(response);
			$scope.product = response.data;
		};

		var onError = function(reason) {
			$scope.error = reason;
		};

		getProduct().then(onResult, onError);

	};
  
	app.controller("ProductController", ["$scope", "$routeParams", "$http", "$filter", ProductController]); // array allows for minification
  
}());