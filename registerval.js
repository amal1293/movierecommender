function validate(){
	var firstname = document.getElementById('firstname').value.trim();
	var lastname = document.getElementById('lastname').value.trim();
	var username = document.getElementById('username').value.trim();
	var password = document.getElementById('password').value.trim();
	var repassword = document.getElementById('repassword').value.trim();
	var email = document.getElementById('email').value.trim();
	var captchatext = document.getElementById('captchatext').value.trim();
	
	var errorval = document.getElementById('validation');
	errorval.innerHTML = "";
	var returnvalue = true;
	
	if(firstname == "" || lastname == "" || username == "" || password == "" || repassword == "" || email == ""){
		errorval.innerHTML += "All fields are mandatory.<br/>";
		returnvalue = false;
	}
	
	if(firstname != "" && !(firstname[0] >= 'A' && firstname[0] <= 'Z')){
		errorval.innerHTML += "First Name must start with a capital letter.<br/>";
		returnvalue = false;
		firstname = "";
	}
	
	if(lastname != "" && !(lastname[0] >= 'A' && lastname[0] <= 'Z')){
		errorval.innerHTML += "Last Name must start with a capital letter.<br/>";
		returnvalue = false;
		lastname = "";
	}

	if(username!= "" && !(/^[a-zA-Z0-9\_]+$/.test(username))){
		errorval.innerHTML += "Username must contain only letters,numbers and underscore.<br/>";
		returnvalue = false;
		username = "";
	}
	
	if(username != "" &&(username.length < 4 || username.length > 10)){
		errorval.innerHTML += "Username must be between 4 and 10 characters long.<br/>";
		returnvalue = false;
		username = "";
	}

	if(password!= "" && password.length < 6){
		errordiv.innerHTML += "Password must be atleast 6 characters long.<br/>";
		returnvalue = false;
	}

	if(!(password== "" && repassword== "") && password != repassword){
		errorval.innerHTML += "Passwords do not match.<br/>";
		returnvalue = false;
	}

	if(email != "" && !(/^[a-zA-Z0-9]+[-\._]?[a-zAA-Z0-9]?@[a-zA-Z0-9]+\.[a-z]+\.?[a-z]{1,3}$/.test(email))){
		errorval.innerHTML += "Enter a valid email.<br/>";
		returnvalue = false;
		email = "";
	}

	if(returnvalue == false){
		password = "";
		repassword = "";
		captchatext = "";
	}

	return returnvalue;
}


function usernamecheck(){
	var xmlhttp;
	if(window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest();
	else
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	var username = document.getElementById('username').value;
	var validation = document.getElementById('validation');
	xmlhttp.open('GET','usernamecheck.php?username='+username,true);
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			validation.innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.send();
}
