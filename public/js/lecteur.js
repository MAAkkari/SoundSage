
            // variables globales

            // let trackButtons = null;
            let repeatBtn = null;
            let audio = null;
            let playPauseBtn = null;
            let progressBar = null;
            let progress = null;
            let volumeSlider = null;
            let volumeIcon = null;
            let timeInfo = null;
            let currentTrackIndex = 0;
            let tracks = [];    
            let isRepeating = false;
            
            
            // définir/redéfinir les liens des <a> du contenu de #content qui est rechargé en temps réel au changement de page
            function bindPageLinks(nodeOnWhichBindLinks) {
                console.log("bindPageLinks() - start");
            
                // If no node is provided, default to the entire document
                if (!nodeOnWhichBindLinks) {
                    nodeOnWhichBindLinks = document;
                }
            
                nodeOnWhichBindLinks.querySelectorAll("a:not(.exclude-from-interception)").forEach(aElt => aElt.addEventListener('click', async function(e) {
                    console.log("document.on.click - start");
                    const href = $(this).attr('href');
            
                    if (doLoadInternally(href)) {
                        e.preventDefault();
                        await loadPage(href);
                    }
                    console.log("document.on.click - end");
                }));
            
                console.log("bindPageLinks() - end");
            }
            
          
            
            
            async function loadPage(url) {
                await $('#content').load(url + ' #content', function() {
                    // This callback function is executed after the content is loaded successfully.
                    applyDarkMode(); 
                    const savedColor = localStorage.getItem('themeColor');
                    if (savedColor) {
                        applyThemeColor(savedColor); // Re-apply the theme color to newly loaded content
                    }
                    resetButtonPositions();
            
                    // Call bindPageLinks to bind the click event listener to the new links
                    bindPageLinks(document.getElementById('content'));
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
        
            
        
            function changeTrack(trackButton) {
                console.log("changeTrack() - event = ", trackButton);
                tracks = JSON.parse(trackButton.getAttribute('data-tracks'));
                currentTrackIndex = 0;
                playTrack();
            }
            
            function playTrack() {
                const track = tracks[currentTrackIndex];
                console.log("playTrack() - track = ", track);
            
                if (track.track) {
                    console.log("play() - audio = ", audio);
                    audio.src = track.track;
                    audio.load();
                    audio.play();
                    playPauseBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
                    addToHistory(track.musiqueId); // Call addToHistory with the track's id
                }
            
                document.getElementById('albumCover').src = track.albumCover;
                document.getElementById('songName').innerHTML = `<a href="/album/${track.albumId}">${track.songName}</a>`;
                document.getElementById('artistName').innerHTML = `<a href="/groupe/${track.groupeId}">${track.artistName}</a>`;
            }
            function playNextTrack() {
                currentTrackIndex = (currentTrackIndex + 1) % tracks.length;
                playTrack();
            }
            
            function playPreviousTrack() {
                currentTrackIndex = (currentTrackIndex - 1 + tracks.length) % tracks.length;
                playTrack();
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
                // Update the linear-gradient to start with red and transition to white at the current volume percentage
                volumeSlider.style.background = `linear-gradient(to right, white ${percentage}%, white ${percentage}%)`;
                
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
                bindPageLinks();
                console.log("CALL bindPageLinks() - après");
              
            repeatBtn = document.getElementById('repeatBtn');
            audio = document.getElementById('audioPlayer');
            playPauseBtn = document.getElementById('playPauseBtn');
            progressBar = document.getElementById('progressBar');
            progress = document.getElementById('progress');
            volumeSlider = document.getElementById('volumeSlider');
            volumeIcon = document.getElementById('volumeIcon');
            timeInfo = document.getElementById('timeInfo');

            repeatBtn.addEventListener('click', function() {
                isRepeating = !isRepeating;
                repeatBtn.classList.toggle('active-repeat', isRepeating);
                audio.loop = isRepeating;
            });

            // trackButtons = document.querySelectorAll('.track-btn');
            audio.addEventListener('ended', function() {
                if (!isRepeating) {
                    playNextTrack();
                } else {
                    audio.play();
                }
            });
            
        
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
        
    