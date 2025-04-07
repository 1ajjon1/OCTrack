//Will try to validate and login the user based on their input, will use email/username and their password
async function login(){
    //Will activate once the login button is pressed
    const username = document.getElementById("loginEmail").value;
    const password = document.getElementById("loginPassword").value;

    if (!username){
        //Using .textContent instead of innerHtml to prevent js execution
        document.getElementById("loginMessage").textContent = "Please enter an email address"
        return;
    }
    else if(!password){
        document.getElementById("loginMessage").textContent = "Please enter a password"
        return;
    }
    try {
        //API post request to db to try and login in user based on their input
        const response = await fetch("http://localhost:3001/login", {
            method: "POST",
            headers: { "Content-Type": "application/json"},
            body: JSON.stringify({username,password})
        });

        
        const result = await response.json();
        if(response.ok){//If the request is succesful
            sessionStorage.setItem('status', 'loggedIn');
            sessionStorage.setItem('username', result.username || username);
            window.location.href = "./Favourites_A2.php";
        }
        else{//If the request is insuccesful
            document.getElementById("loginMessage").textContent = result.error || "Login Failed";
        }
    }
    catch{
        document.getElementById("loginMessage").textContent = "Login error";
    }
    document.getElementById("loginEmail").value = "";
    document.getElementById("loginPassword").value = "";
}

//Will create a new user if all fields are filled in and pass the minimum requirements
async function registration(){
    //Outputs for if any of the fields are filled in
    const regFields = {
        emailRegistration: "Please enter an email address",
        passwordRegistration: "Please enter a Password",
        nameRegistration: "Please enter a name",
        userRegistration: "Please enter a username",
        passwordConfirmation: "Please re-enter a password"
    };
    //Once the registration button is pressed
    const regButton = document.getElementById("registrationButton");
    regButton.disabled = true;

    const username = document.getElementById("userRegistration").value;
    const name = document.getElementById("nameRegistration").value;
    const email = document.getElementById("emailRegistration").value;
    const password = document.getElementById("passwordRegistration").value;
    const passConfirmation = document.getElementById("passwordConfirmation").value;

    //Checks and makes sure that all of the fields are not empty
    for (const field in regFields){
        if (!document.getElementById(field).value.trim()){
            document.getElementById("regMsg").textContent = regFields[field];
            return;
        }
    }
    //Makes sure that the fields passed the minimum requirements
    if (!validation(username, email, password, name)){
        return;
    }

    if(password != passConfirmation){
        document.getElementById("regMsg").textContent = "Passwords Do not Match"
        return;
    }
    //API request to the DB 
    try {
        const response = await fetch("http://localhost:3001/createUser", {
            method: "POST",
            headers: { "Content-Type": "application/json"},
            body: JSON.stringify({username,password,email,name})
        });

        let result;
        try{
            result = await response.json();
        }
        catch{
            document.getElementById("regMsg").textContent = "Server Issue please again letter";
        }

        if(response.ok){
            // Clear input fields after registration
            document.getElementById("emailRegistration").value = "";
            document.getElementById("nameRegistration").value = "";
            document.getElementById("userRegistration").value = "";
            document.getElementById("passwordRegistration").value = "";
            document.getElementById("passwordConfirmation").value = "";
            document.getElementById("regMsg").textContent = "Registration Successful!";
        }
        else{
            document.getElementById("regMsg").textContent = result.error || "Registration Failed";
        }
    }
    catch{
        document.getElementById("regMsg").textContent = "Registration Failed, please try again later";
    }
    finally{
        regButton.disabled = false;
    }
}

//Used to ensure that the user's input meets the minimum standards
function validation(username, email, password, name){
    const emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    //Checks and makes sure that the user's name is not empty and is min 3 characters
    if (name==="" || name.length<3){
        document.getElementById("regMsg").textContent = "A Name is Required"
        return false;
    }
    //Checks and makes sure that the user email matches the emailRegex defined above
    if (!email.match(emailRegex)){
        document.getElementById("regMsg").textContent = "Invalid Email Address"
        return false;
    }
    //Checks and makes sure that the password is min 4 characters and max 15
    if (password.length < 4 || password.length > 15){
        document.getElementById("regMsg").textContent = "Password must be greater than 4 and less than 15 characters"
        return false;
    }
    //Checks and makes sure that the username is min 4 characters and max 10
    if (username.length < 4 || username.length > 10){
        document.getElementById("regMsg").textContent = "Username must be greater than 4 and less than 10 characters"
        return false;
    }
    return true;
}