//console.log('link');

document.querySelector(".comments").addEventListener("click", function(e){
    e.preventDefault();

    //console.log('geklikt');

    //postid
    let promptId = this.dataset.promptid;
    let text = document.querySelector(".text").value;

    //console.log(promptId);
    //console.log(text);

    //comment naar databank
    let formData = new FormData();
    formData.append("promptId", promptId);
    formData.append("text", text);

    fetch("ajax/comment.php", {
        method: "POST",
        body: formData
    })

    .then(function (response) {
        return response.json();
    })
    .then(result => {
        let comment = document.createElement('li');
        comment.innerHTML = result.body;
        document.querySelector('.list').appendChild(comment);
    })
    .catch(error => {
        console.log("error", error);
    });

});

/*document.getElementById("btnAddComment").addEventListener("click", addComment);

function addComment(event) {
    event.preventDefault();

    let commentText = document.getElementById("commentText").value;
    let postId = this.getAttribute("data-postid");

    let formData = new FormData();
    formData.append("commentText", commentText);
    formData.append("postId", postId);

    fetch("ajax/addComment.php", {
        method: "POST",
        body: formData
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (result) {
            if (result.status === "success") {
                let comment = result.comment;
                let commentElement = document.createElement("div");
                commentElement.classList.add("comment");
                commentElement.textContent = comment.text;
                document.getElementById("postComments").appendChild(commentElement);
                document.getElementById("commentText").value = "";
            }
        });
}*/
