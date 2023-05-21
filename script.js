document.getElementById("btnAddComment").addEventListener("click", addComment);

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
}
