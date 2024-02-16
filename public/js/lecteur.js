
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
                //si aucun noeud n'est passé en paramètre, on prend le document
                if (!nodeOnWhichBindLinks) {
                    nodeOnWhichBindLinks = document;
                }
            
                nodeOnWhichBindLinks.querySelectorAll("a:not(.exclude-from-interception)").forEach(aElt => aElt.addEventListener('click', handleLinkClick ))
                  
                    
            
                console.log("bindPageLinks() - end");
            }
            
            async function handleLinkClick(e) {
                // Use e.currentTarget instead of $(this)
                const href = e.currentTarget.getAttribute('href');
                console.log("document.on.click - start");
                if (doLoadInternally(href)) {
                    e.preventDefault();
                    await loadPage(href);
                }
                console.log("document.on.click - end");
            }
            

            
                function showToast(message) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: 'success',
                    title: message
                });
                }
    
            setTimeout(function() {
                var errorMessages = document.querySelectorAll('.msg_error');
                for (var i = 0; i < errorMessages.length; i++) {
                    errorMessages[i].style.display = 'none';
                }
            }, 6000);
        
            setTimeout(function() {
                var successMessages = document.querySelectorAll('.msg_success');
                for (var i = 0; i < successMessages.length; i++) {
                    successMessages[i].style.display = 'none';
                }
            }, 6000);
            
            
            async function loadPage(url) {
                await $('#content').load(url + ' #content', function() {
                    const savedColor = localStorage.getItem('themeColor');
                    // on reaplique le dark mode si il est actif
                    applyDarkMode(); 
                    // on réapplique la couleur du thème personnalisé si elle est sauvegardée
                    if (savedColor) {
                        applyThemeColor(savedColor);
                    }
                    // on reinitalise les positions ce certains boutons controler par du js
                    resetButtonPositions();
                    // On applique l'ecouteur d'evenement sur les liens des pages chargées
                    bindPageLinks(document.getElementById('content'));
                });
            
                // changer l'url dans la barre d'adresse sans recharger la page
                history.pushState({}, '', url);
            }
        
            function doLoadInternally(url) {
                return !url.match(/^http[s]?:\/\//);
            }

            
            // verifie si le lecteur est en pause ou en lecture, si il est en pause, il le lance, si il est en lecture, il le met en pause et inverse l'icone du bouton play/pause
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
                tracks = JSON.parse(trackButton.getAttribute('data-tracks'));
                currentTrackIndex = 0;
                playTrack();
            }
            
            
            function playTrack() {
                // On récupère les information de la musique qui correspond à l'index courant
                const track = tracks[currentTrackIndex];
                console.log("playTrack() - track = ", track);
                // On met à jour l'audio avec la musique a jouer, on change le bouton play en pause, et on appel la fonction addToHistory
                if (track.track) {
                    console.log("play() - audio = ", audio);
                    audio.src = track.track;
                    audio.load();
                    audio.play();
                    playPauseBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
                    addToHistory(track.musiqueId); // Appeler la fonction addToHistory pour ajouter la musique à l'historique et ajouter +1 au nombre de lecture de la musique
                }
                // On met à jour l'image de l'album, le nom de la musique et le nom de l'artiste
                document.getElementById('albumCover').src = track.albumCover;

                let songNameElement = document.getElementById('songName');
                songNameElement.href = `/album/${track.albumId}`;
                songNameElement.innerText = track.songName;

                let artistNameElement = document.getElementById('artistName');
                artistNameElement.href = `/groupe/${track.groupeId}`;
                artistNameElement.innerText = track.artistName;

            }

            //ajout +1 a l'index de lecture pour passé à la musique suivante, boucle sur la liste des musiques si on est à la fin
            function playNextTrack() {
                currentTrackIndex = (currentTrackIndex + 1) % tracks.length;
                playTrack();
            }
            //enelve -1 a l'index de lecture pour passé à la musique suivante, boucle sur la liste des musiques si on est au debut
            function playPreviousTrack() {
                currentTrackIndex = (currentTrackIndex - 1 + tracks.length) % tracks.length;
                playTrack();
            }
        
            function seekAudio(e) {
                // Récupère la position de la progressBar par rapport à la fenêtre
                const rect = progressBar.getBoundingClientRect();
                // On calcule la position du click par rapport à la progressBar
                const clickPosition = (e.pageX - rect.left) / progressBar.offsetWidth;
                // On met à jour la position de la musique
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
            document.addEventListener('turbo:load', function() {
                
                console.log("document.on.DOMContentLoaded - start");
                bindPageLinks();
                console.log("document.on.DOMContentLoaded - end");
                let body = document.querySelector('body');
                body.classList.remove('preload');
                resetButtonPositions();
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

            // quand la musique est terminée, si la répétition n'est pas activée, on passe à la musique suivante, sinon on relance la musique
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
        
    