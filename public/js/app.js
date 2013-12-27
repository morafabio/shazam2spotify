var app = angular.module('Spotify2ShazamApp', ['SpotifyFilters']);

// Playlist Controller
function PlaylistCtrl($scope, $http) {

    $scope.playlist = [];
    
    $scope.loadPlaylist = function() {
        $http({method:"GET", url:"service/locator/"}).success(function(result){
            $scope.playlist = result;
        });
    };

    $scope.loadPlaylist();
}
