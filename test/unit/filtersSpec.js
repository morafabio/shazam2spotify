describe('filter', function() {

    beforeEach(module('SpotifyFilters'));

    describe('spotifyUri', function() {
        it('converts boolean values to unicode checkmark or cross',
            inject(function(spotifyUriFilter) {
                expect(spotifyUriFilter(true)).toBe('\u2713');
                expect(spotifyUriFilter(false)).toBe('\u2718');
            }));
    });

    describe('spotifyPlaylist', function() {
        it('get string of uri separed by a newline character from a playlist',
            inject(function(spotifyPlaylistFilter) {
                var playlist = [
                    { track : 'a', album: 'b', uri: 'c'},
                    { track : 'a', album: 'b', uri: 'd'},
                    { track : 'a', album: 'b', uri: null},
                    { track : 'a', album: 'b', uri: 'e'}
                ];
                expect(spotifyPlaylistFilter(playlist)).toBe('c\nd\ne\n');
            }));
    });
});