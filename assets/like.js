function like(event){

    event.preventDefault()

    fetch(this.href)
        .then(response=>response.json())
        .then((data)=>{
            this.querySelector('.nbrLikes').innerHTML = data.count
            const thumb = this.querySelector('.thumb')
            if(data.liked)
            {
                thumb.classList.remove('bi-hand-thumbs-up')
                thumb.classList.add('bi-hand-thumbs-up-fill')
            }else {
                thumb.classList.add('bi-hand-thumbs-up')
                thumb.classList.remove('bi-hand-thumbs-up-fill')
            }
        })
}


document.addEventListener('DOMContentLoaded', ()=>{
    const boutonsLike = document.querySelectorAll('.like')

    boutonsLike.forEach((bouton)=>{
        bouton.addEventListener('click', like)
    })
})

