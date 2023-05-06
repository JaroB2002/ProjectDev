console.log("link");

let links = document.querySelectorAll(".like");
for(let i = 0; i < links.length; i++){
    links[i].addEventListener("click", function(e){
        e.preventDefault();
        console.log("geklikt😅");

        //krijg de id voor de prompt
        let id = this.getAttribute("data-id");

        //de spam met de id likes[id]
        let span = document.querySelector("#likes" + id);
        console.log(id);
    });
}
