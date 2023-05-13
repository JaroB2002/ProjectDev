document.querySelector('#btnAddComment').addEventListener('click', function() {
    //alert('Comment added');

    // postid?
    // comment text?
    let postId = this.dataset.postid;
    let text = document.querySelector("#commentText").value;
    //console.log(postid);
    //console.log(text);

    // post naar database (ajax)
    let formData = new FormData();
    formData.append('text', text);
    formData.append('postId', postId);

    fetch('ajax/Savecomment.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            let newComment = document.createElement('li');
            newComment.innerHTML = result.body;
            document
            .querySelector('.post__comments__list')
            .appendChild(newComment);
        })
        .catch(error => {
            console.error('Error:', error);
        });


    // antwoord ok? toon comment onderaan
});