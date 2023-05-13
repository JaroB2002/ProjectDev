let report = document.querySelectorAll("#reportButton");

report.forEach(function(button){
    button.addEventListener("click", reportPrompt);
});

function reportPrompt(event){
console.log(event);
event.preventDefault();
console.log("reportPrompt");
let promptid = event.target.dataset.promptid;
console.log(promptid);
let formData = new FormData();
formData.append("promptid", promptid);

let item = this;
fetch("ajax/reportPrompt.php", {
    method: "POST",
    body: formData
})
.then(function(response){
    return response.json();
})
.then(function(result){
    if(result.status == "success"){
        item.innerHTML = result.message;
    }
});
}