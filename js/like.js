//console.log("link");

let links = document.querySelectorAll(".like");
for(let i = 0; i < links.length; i++){
    links[i].addEventListener("click", function(e){
        e.preventDefault();
        //console.log("geklikt😅");

        //krijg de id voor de prompt
        let promptId = this.getAttribute("data-id");

        //de spam met de id likes[id]
        let span = document.querySelector("#likes" + promptId);
        console.log(promptId);

        //post naar database AJAX
        let formData = new  FormData();
        formData.append("promptId",promptId );

        fetch("ajax/like.php", {
                method: "POST", // or 'PUT'
                body: formData
        })

        .then(response => response.json())
        .then(result =>{
            console.log("succes", result);
        })
        .catch(error => {
            console.log("error", error);
        })
        
          
        //aantal likes tonen 
    });
}
