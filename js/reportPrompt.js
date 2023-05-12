function reportPrompt(event){
    event.preventDefault();
    console.log("reportPrompt");
    /*hoe juiste promptid vinden?*/
    let promptid = event.target.dataset.promptid;
    console.log(promptid);
    let formData = new FormData();
    formData.append("promptid", promptid);

    fetch("ajax/reportPrompt.php", {
        method: "POST",
        body: formData
    })
    .then(function(response){
        return response.json();
    })
    .then(function(result){
        console.log(result);
        document.querySelector("#reportButton").innerHTML = "Reported prompt";
    });
}