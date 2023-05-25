//console.log('link');

let com = document.querySelectorAll(".comments")

for (let i = 0; i < com.length; i++) 
{
    com[i].addEventListener("click", function (e){
        e.preventDefault();
        
        //postid
        let promptId = this.getAttribute("data-id");
        //let text = document.querySelector(".text").value;

        let form = this.closest('form');

        // Selecteer het tekstveld binnen de geselecteerde form
        let input = form.querySelector('input[type="text"]');

        // Doe iets met het geselecteerde invoerveld (bijv. toegang krijgen tot de waarde)
        let commentText = input.value;
        console.log(commentText);

        var ul = form.nextElementSibling;
        console.log(ul);

    
        console.log(promptId);
        //console.log(text);
    
        //comment naar databank
        let formData = new FormData();
        formData.append("promptId", promptId);
        formData.append("text", commentText);
    
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
            ul.appendChild(comment);
        })
        .catch(error => {
            console.log("error", error);
        });
    
    });
}

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
