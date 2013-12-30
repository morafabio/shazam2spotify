var app = angular.module('Spotify2ShazamApp', ['SpotifyFilters']);

function LocatorCtrl($scope) {

    $scope.playlist = [];
    $scope.statusText = "Upload your file.";
    $scope.status = "info";

    $scope.uploadFile = function(element) {
        $scope.statusText = "Processing, please wait...";
        $scope.playlist = [];
        $scope.$apply();

        var fd = new FormData()
        fd.append("uploadedFile", element.files[0])

        var xhr = new XMLHttpRequest()
        xhr.addEventListener("load", uploadComplete, false)
        xhr.addEventListener("error", uploadFailed, false)
        xhr.addEventListener("abort", uploadCanceled, false)
        xhr.open("POST", "service/locator/")
        xhr.send(fd)
        element.clean();
    }

    function uploadComplete(evt) {
        var response = angular.fromJson(evt.target.responseText);
        if(response.error) {
            $scope.playlist = [];
            $scope.statusText = response.error;
        } else {
            $scope.playlist = response;
            $scope.statusText = "Finished.";
        }
        $scope.$apply();
    }

    function uploadFailed(evt) {
        $scope.statusText = "Upload failed.";
        $scope.$apply();
    }

    function uploadCanceled(evt) {
        $scope.statusText = "Upload canceled.";
        $scope.$apply();
    }
}

function TextareaController($scope, $http) {
    $scope.selectable = function(){
         var textBox = document.getElementById("copy");
         textBox.onfocus = function() {
             textBox.select();
             textBox.onmouseup = function() {
                textBox.onmouseup = null;
                return false;
             };
         };
    };
    $scope.selectable();
}