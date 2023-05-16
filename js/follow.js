
let fol = document.querySelectorAll(".follow");

for (let i = 0; i < fol.length; i++) 
{
    fol[i].addEventListener("click", function (e) {
        e.preventDefault();
        //console.log("geklikt😅");

        // Get the clicked <a> element
        let link = e.target;

        //krijg de id voor de prompt
        let userId = this.getAttribute("data-id");

        //post naar database AJAX
        let formData = new FormData();
        formData.append("usersId", userId);

        fetch("ajax/follow.php", {
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