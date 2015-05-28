(function() {

    var app = angular.module("unmarket", ["ngRoute", "ui.materialize"]);

    app.config(function($routeProvider, $locationProvider) {

        var componentsUrl = "app/components/"
        
        $routeProvider
            .when("/", {
                templateUrl: componentsUrl + "main/MainView.html",
                controller: "MainController"
            })
            .when("/product/:ASIN", {
                templateUrl: componentsUrl + "product/ProductView.html",
                controller: "ProductController"
            })
            .otherwise({redirectTo: "/"});

        //$locationProvider.html5Mode(true);
    });

}());