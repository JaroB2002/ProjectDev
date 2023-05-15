//console.log("link");

let links = document.querySelectorAll(".like");
for (let i = 0; i < links.length; i++) {
    links[i].addEventListener("click", function (e) {
        e.preventDefault();
        //console.log("geklikt😅");

        // Get the clicked <a> element
        let link = e.target;

        // Find the parent <a> element if the clicked element is the <span> element
        if (link.tagName !== "A") {
            link = link.closest("a");
        }

        // Get the <span> element inside the clicked <a> element
        let span = link.querySelector("span.likes");
        console.log(span);

        //krijg de id voor de prompt
        let promptId = this.getAttribute("data-id");

        //post naar database AJAX
        let formData = new FormData();
        formData.append("promptId", promptId);

        fetch("ajax/like.php", {
            method: "POST", // or 'PUT'
            body: formData
        })

            //.then(response => response.json())
            .then(function (response) {
                return response.json();
            })

            .then(function (json) {
                span.innerHTML = json.likes + " people like this";
            })

            .catch(function (error) {
                console.log(error);
            });


        //aantal likes tonen 
    });
}