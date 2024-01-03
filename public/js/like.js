DocumentFragment.addEventListener('DOMContentLoaded',()=>{
    const likeElements = [].slice.call(document.querySelectorAll('a[data-action="like"]'));
    if(likeElements){
        new Like(likeElements);
    }
})
export default class Like {
    constructor(likeElements){
        this.likeElements = likeElements;

        if(this.likeElements){
            console.log(this.likeElements);
        }
    }
}