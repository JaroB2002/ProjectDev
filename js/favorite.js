
let fav = document.querySelectorAll(".favorite");

    for (let i = 0; i < fav.length; i++) 
    {
        fav[i].addEventListener("click", function (e) {
            e.preventDefault();
            //console.log("geklikt😅");

            // Get the clicked <a> element
            let link = e.target;

           /* // Find the parent <a> element if the clicked element is the <span> element
            if (link.tagName !== "A") {
                link = link.closest("a");
            }*/

            //krijg de id voor de prompt
            let promptId = this.getAttribute("data-id");

            //post naar database AJAX
            let formData = new FormData();
            formData.append("promptId", promptId);

            fetch("ajax/favorite.php", {
                method: "POST", // or 'PUT'
                body: formData
            })

            //.then(response => response.json())
            .then(function (response) {
                return response.json();
            })

            .then(function (json) {
                link.innerHTML = json.status;
            })

            .catch(function (error) {
                console.log(error);
            });
        });
    }