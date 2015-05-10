(function() {
  
	var app = angular.module("unmarket");

	var ProductController = function($scope, $routeParams, $http, $filter) {

		var ASIN = $routeParams.ASIN;
		$scope.priceWatchSubmitted = false;

		var getProduct = function() {
			return $http.get("/api/get.php?type=product&id=" + ASIN)
					.then(function(response) {
						return response.data;
					});
		};

		var getProductPriceHistory = function() {
			return $http.get("/api/get.php?type=productPriceHistory&id=" + ASIN)
					.then(function(response) {
						return response.data;
					});
		};

		$scope.addPriceWatchItem = function(desiredPrice, email)
		{
			$http.get("/api/put.php?type=priceWatch&desiredPrice=" + desiredPrice + "&email=" + email + "&asin=" + ASIN + "&currentPrice=" + $scope.product.Price);
			$scope.priceWatchSubmitted = true;
		};

		$scope.formatNumber = function(desiredPrice)
		{
			$scope.desiredPrice = $filter('number')(desiredPrice, 2)
		}

		var onProductResult = function(response) {
			console.log(ASIN);
			console.dir(response);
			$scope.product = response.data;
		};

		var onProductError = function(reason) {
			$scope.error = reason;
		};

		var onPriceHistoryResult = function(response) {
			console.dir(response);
			$scope.priceHistory = response.data;
		}

		getProduct().then(onProductResult, onProductError);
		getProductPriceHistory().then(onPriceHistoryResult);
	};
  
	app.controller("ProductController", ["$scope", "$routeParams", "$http", "$filter", ProductController]); // array allows for minification

	app.directive("linearChart", function($window, $routeParams)
	{
		return {
			restrict: 'EA',
			template: "<svg width='90%' height='500'></svg>",
			link: function(scope, elem, attrs) {
				var ASIN = $routeParams.ASIN;
				     
				var d3 = $window.d3;
				var rawSvg = elem.find("svg")[0];

				var margin = {top: 20, right: 20, bottom: 75, left: 50},
				    height = 500 - margin.top - margin.bottom;

				var parseDate = d3.time.format("%Y-%m-%d %H:%M:%S").parse;

				var x = d3.time.scale()
				    .range([0, rawSvg.clientWidth - margin.right]);

				var y = d3.scale.linear()
				    .range([height, 0]);

				var xAxis = d3.svg.axis()
				    .scale(x)
				    .orient("bottom");

				var yAxis = d3.svg.axis()
				    .scale(y)
				    .orient("left");

				var line = d3.svg.line()
					.x(function(d) { return x(d.date); })
					.y(function(d) { return y(d.close); });

				var svg = d3.select(rawSvg)
				    .attr("width", rawSvg.clientWidth + margin.left + margin.right)
				    .attr("height", height + margin.top + margin.bottom)
				  .append("g")
				    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

				d3.tsv("/api/get.php?type=productPriceHistory&id=" + ASIN, function(error, data) {
				  data.forEach(function(d) {
				    d.date = parseDate(d.date);
				    d.close = +d.close;
				  });

				  x.domain(d3.extent(data, function(d) { return d.date; }));
				  y.domain([0, d3.max(data, function(d) { return d.close; }) * 1.5]);

				  svg.append("path")
				      .datum(data)
				      .attr("class", "line")
				      .attr("d", line);

				  svg.append("g")
				      .attr("class", "x axis")
				      .attr("transform", "translate(0," + height + ")")
				      .call(xAxis)
				      .selectAll("text")
				      .style("text-anchor", "end")
				      .attr("dx", "-.8em")
				      .attr("dy", ".15em")
				      .attr("transform", function(d) {
				      		return "rotate(-50)"
				      	});

				  svg.append("g")
				      .attr("class", "y axis")
				      .call(yAxis)
				    .append("text")
				      .attr("transform", "rotate(-90)")
				      .attr("y", 6)
				      .attr("dy", ".71em")
				      .style("text-anchor", "end")
				      .text("Price ($)");
				});
			}
		};
	});
  
}());