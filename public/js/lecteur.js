
            $(document).ready(function() {
                var audio = new Audio('/uploads/files/Radiohead - Reckoner (By Clement Picon).mp3');
                audio.loop = true;
                audio.play();
            
                
                $(document).on('click', 'a:not(.exclude-from-interception)', function(e) {
                    var href = $(this).attr('href');
            
                  
                    if (doLoadInternally(href)) {
                        e.preventDefault();
                        loadPage(href);
                    }
                    
                });
            
                function loadPage(url) {
                    $('#content').load(url + ' #content');
                }
            
                function doLoadInternally(url) {
                   
                    return !url.match(/^http[s]?:\/\//);
                }
            });
       
        
            document.addEventListener('DOMContentLoaded', function () {
            var audio = document.getElementById('audioPlayer');
            var playPauseBtn = document.getElementById('playPauseBtn');
            var trackButtons = document.querySelectorAll('.track-btn');
            var progressBar = document.getElementById('progressBar');
            var progress = document.getElementById('progress');
            var volumeSlider = document.getElementById('volumeSlider');
            var volumeIcon = document.getElementById('volumeIcon');
            var timeInfo = document.getElementById('timeInfo');

            
        
            playPauseBtn.addEventListener('click', togglePlayPause);
            trackButtons.forEach(button => button.addEventListener('click', changeTrack));
            progressBar.addEventListener('click', seekAudio);
            volumeSlider.addEventListener('input', adjustVolume);
            audio.addEventListener('timeupdate', updateUI);
        
            function togglePlayPause() {
                if (audio.paused) {
                    audio.play();
                    playPauseBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
                } else {
                    audio.pause();
                    playPauseBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
                }
            }
        
            function changeTrack(event) {
                var trackSource = event.target.getAttribute('data-track');
                var albumCover = event.target.getAttribute('data-album-cover');
                var songName = event.target.getAttribute('data-song-name');
                var artistName = event.target.getAttribute('data-artist-name');
        
                if (trackSource) {
                    audio.src = trackSource;
                    audio.load();
                    audio.play();
                    playPauseBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
                }
        
                
                document.getElementById('albumCover').src = albumCover;
                document.getElementById('songName').textContent = songName;
                document.getElementById('artistName').textContent = artistName;
            }
        
            function seekAudio(e) {
                var rect = progressBar.getBoundingClientRect();
                var clickPosition = (e.pageX - rect.left) / progressBar.offsetWidth;
                audio.currentTime = clickPosition * audio.duration;
            }
        
            volumeSlider.addEventListener('input', function () {
                adjustVolume();
                updateVolumeSliderBackground(this.value);
                updateVolumeIcon(volumeSlider.value);
            });
        
            function adjustVolume() {
                audio.volume = volumeSlider.value;
            }
            function updateVolumeSliderBackground(volume) {
                const percentage = volume * 100;
                volumeSlider.style.background = 'linear-gradient(to right, #307AD0 ' + percentage + '%, #ffff ' + percentage + '%)';
            }
            function updateVolumeIcon(volume) {
                if (volume == 0) {
                    volumeIcon.className = 'fa fa-volume-xmark';
                } else if (volume <= 0.3) {
                    volumeIcon.className = 'fa fa-volume-off';
                } else if (volume <= 0.8) {
                    volumeIcon.className = 'fa fa-volume-low';
                } else {
                    volumeIcon.className = 'fa fa-volume-high';
                }
            }
        
            function updateUI() {
                updateProgressBar();
                updateTimeInfo();
            }
        
            function updateProgressBar() {
                var percentage = (audio.currentTime / audio.duration) * 100;
                progress.style.width = percentage + '%';
            }
        
            function updateTimeInfo() {
                timeInfo.textContent = formatTime(audio.currentTime) + ' / ' + formatTime(audio.duration);
            }
        
            function formatTime(time) {
                var minutes = Math.floor(time / 60);
                var seconds = Math.floor(time % 60);
                return minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
            }
            updateVolumeSliderBackground(volumeSlider.value);
        });
    