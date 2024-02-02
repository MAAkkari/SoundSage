
            // variables globales

            // let trackButtons = null;
            
            let audio = null;
            let playPauseBtn = null;
            let progressBar = null;
            let progress = null;
            let volumeSlider = null;
            let volumeIcon = null;
            let timeInfo = null;
            
            
            // définir/redéfinir les liens des <a> du contenu de #content qui est rechargé en temps réel au changement de page
        function bindPageLinks(isFirstCall) {
                console.log("bindPageLinks() - start");
                let nodeOnWhichBindLinks = null;

                if (isFirstCall) {
                    nodeOnWhichBindLinks = document;
                } else {
                    nodeOnWhichBindLinks = document.getElementById("content");
                }

                // pour chacun des <a> concernées, définir/redéfinir son comportement au déclenchament de l'évènement
                // $(document).on('click', 'a:not(.exclude-from-interception)', function(e) {
                // document.getElementById("#content").querySelectorAll("a:not(.exclude-from-interception)").forEach(aElt => aElt.addEventListener('click', function(e) {
                nodeOnWhichBindLinks.querySelectorAll("a:not(.exclude-from-interception)").forEach(aElt => aElt.addEventListener('click', async function(e) {
                    console.log("document.on.click - start");
                    const href = $(this).attr('href');
                
                    if (doLoadInternally(href)) {
                        e.preventDefault();
                        await loadPage(href);
                        
                        // if (isFirstCall) {
                            bindPageLinks(false);
                        // }
                    }
                    console.log("document.on.click - end");
                    
                    
                }));

                // trackButtons = document.querySelectorAll('.track-btn');
                // trackButtons.forEach(button => button.addEventListener('click', changeTrack));
                console.log("bindPageLinks() - end");
                
                
            }
            
          
            
            
            async function loadPage(url) {
                await $('#content').load(url + ' #content', function() {
                    // This callback function is executed after the content is loaded successfully.
                    // Here you should call the darkmode() function to apply dark mode to new elements.
                    applyDarkMode(); // Re-apply dark mode to newly loaded content.
                });
            }
        
            function doLoadInternally(url) {
               
                return !url.match(/^http[s]?:\/\//);
            }

            
        
            function togglePlayPause() {
                if (audio.paused) {
                    audio.play();
                    playPauseBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
                } else {
                    audio.pause();
                    playPauseBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
                }
            }
        
            // function changeTrack(event) {
            //     console.log("changeTrack() - start");
            //     console.log("changeTrack() - event = ", event);
            //     const trackSource = event.target.getAttribute('data-track');
            //     const albumCover = event.target.getAttribute('data-album-cover');
            //     const songName = event.target.getAttribute('data-song-name');
            //     const artistName = event.target.getAttribute('data-artist-name');
        
            function changeTrack(trackButton) {
                
                console.log("changeTrack() - event = ", trackButton);
                const trackSource = trackButton.getAttribute('data-track');
                const albumCover = trackButton.getAttribute('data-album-cover');
                const songName = trackButton.getAttribute('data-song-name');
                const artistName = trackButton.getAttribute('data-artist-name');

                console.log("changeTrack() - trackSource = ", trackSource);
                if (trackSource) {
                    console.log("changeTrack() - audio = ", audio);
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
                const rect = progressBar.getBoundingClientRect();
                const clickPosition = (e.pageX - rect.left) / progressBar.offsetWidth;
                audio.currentTime = clickPosition * audio.duration;
            }
        
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
                const percentage = (audio.currentTime / audio.duration) * 100;
                progress.style.width = percentage + '%';
            }
        
            function updateTimeInfo() {
                timeInfo.textContent = formatTime(audio.currentTime) + ' / ' + formatTime(audio.duration);
            }
        
            function formatTime(time) {
                const minutes = Math.floor(time / 60);
                const seconds = Math.floor(time % 60);
                return minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
            }


            
            // au premier chargement de la page
            // $(document).ready(function() {
        document.addEventListener('DOMContentLoaded', function() {
                // l'import de ce fichier js est possible dans le <head> (et pas forcément juste avant la fin du <body>) car il ne contient que des déclarations de fonctions (et non des appels) (sauf le premier bloc mais qui lui n'est déclenché que lorsque le document / le DOM est prêt)
                
                console.log("CALL bindPageLinks() - avant");
                bindPageLinks(true);
                console.log("CALL bindPageLinks() - après");
            
            audio = document.getElementById('audioPlayer');
            playPauseBtn = document.getElementById('playPauseBtn');
            progressBar = document.getElementById('progressBar');
            progress = document.getElementById('progress');
            volumeSlider = document.getElementById('volumeSlider');
            volumeIcon = document.getElementById('volumeIcon');
            timeInfo = document.getElementById('timeInfo');

            // trackButtons = document.querySelectorAll('.track-btn');
            
        
            playPauseBtn.addEventListener('click', togglePlayPause);
            // trackButtons.forEach(button => button.addEventListener('click', changeTrack));
            progressBar.addEventListener('click', seekAudio);
            volumeSlider.addEventListener('input', adjustVolume);
            audio.addEventListener('timeupdate', updateUI);
            
        
            volumeSlider.addEventListener('input', function () {
                adjustVolume();
                updateVolumeSliderBackground(this.value);
                updateVolumeIcon(volumeSlider.value);
            });


            updateVolumeSliderBackground(volumeSlider.value);
        });
        
    