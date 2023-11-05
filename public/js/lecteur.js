// recuperer tout les elements du lecteur
document.addEventListener('DOMContentLoaded', function() {
const audio = document.querySelector("#audio");
const track = document.querySelector("#track");
const elapsed = document.querySelector("#elapsed");
const trackTime = document.querySelector("#track-time");
const playButton = document.querySelector("#play-button");
const pauseButton = document.querySelector("#pause-button");
const stopButton = document.querySelector("#stop-button");
const volume = document.querySelector("#volume");
const volumeValue = document.querySelector("#volume-value");

//on laisse les donnée de l'audio charger avant d'utiliser la fonction buildDuration qu'on a definie plus bas 
audio.addEventListener('loadedmetadata', function() {
    let duration = audio.duration;
    trackTime.textContent = buildDuration(duration);
});

//on fait en sorte que que l'input range qui represente l'avancement de l'audio avance en meme temps que l'audio avance et on affiche l'avancement 
// minutes et en secondes, cela se fait a chaque fois que le l'audio avance grace a l'ecouteur d'evenement timeupdate
audio.addEventListener("timeupdate",function(){
    track.value = (this.currentTime / audio.duration)*100;
    elapsed.textContent = buildDuration(this.currentTime)
});

track.addEventListener("input",function(){
    elapsed.textContent = buildDuration(this.value);
    audio.currentTime = (audio.duration/100)*this.value;
    
});
volume.addEventListener("input",function(){
    audio.volume = this.value;
    volumeValue.textContent = Math.round(this.value*100) + "%";
})

// fonction qui determine la durée de l'audio a partir du fichier mp3 et qui formate correctement pour l'afficher en minutes:secondes 
function buildDuration(duration) {
    let minutes = Math.floor(duration / 60);
    let reste = duration % 60;
    let seconds = Math.floor(reste);
    seconds = String(seconds).padStart(2, "0");
    return minutes + ":" + seconds;
}
    //gerer le button play 
    playButton.addEventListener("click",function(){
        audio.play();
        audio.volume = volume.value;
        pauseButton.style.display ="initial";
        this.style.display="none";
    })

    pauseButton.addEventListener("click",function(){
        audio.pause();
        playButton.style.display ="initial";
        this.style.display="none";
    })
});