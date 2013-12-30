angular.module('SpotifyFilters', [])
.filter('spotifyUri', function() {
    return function(input) {
        return input ? '\u2713' : '\u2718';
    };
})
.filter('spotifyPlaylist', function() {
    return function(input) {
        return input.map(function(track) {
            if(track.uri) return track.uri + "\n";
        }).join('');
    };
})
;
