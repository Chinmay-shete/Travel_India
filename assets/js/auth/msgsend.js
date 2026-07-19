function sendMail(){
    let params = {
        name : document.getElementById("name").value ,
        email : document.getElementById("email").value ,
        massage : document.getElementById("massage").value 
    };

    const serviceId = "service_r87w76a";
    const templeteId = "template_sfuy85w";
    
    emailjs.send(serviceId, templeteId, params)
    .then(
        res => {
            document.getElementById("name").value = "";
            document.getElementById("email").value = "";
            document.getElementById("massage").value = "";
            console.log(res);
            alert("Your massage sent Successfully..!");            
        }
    )
    .catch((err) => console.log(err));

}
//document.getElementById('');
