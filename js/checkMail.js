function checkMail(){
    console.log("checkEmail");
    let email = document.getElementById("email").value;
    let feedback = document.querySelector("#feedback"); 
    console.log(email);
    let formData = new FormData();
    formData.append("email", email);

    fetch("ajax/checkMail.php", {
        method: "POST",
        body: formData
    })
    .then(function(response){
        return response.json();
    })
    .then(function(result){
        if(result.available === 'false'){
            feedback.innerHTML = "Email is unavailable";
            feedback.classList.add("text-red-500");
        } else {
            feedback.innerHTML = "Email is available";
            feedback.classList.add("text-green-500");
        }
        console.log(result);
    })
}