myApp.controller('ContentCtrl', ['$scope', '$http', function ($scope, $http) {
    $http.get('./drivers_json.php')
    .success(function(data) {
        $scope.contents = data;
    });
}]);