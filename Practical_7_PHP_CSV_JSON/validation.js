const form=document.getElementById("registrationForm");
const error=(id,msg)=>document.getElementById(id).textContent=msg;
form.addEventListener("submit",e=>{
 e.preventDefault(); document.querySelectorAll("small").forEach(x=>x.textContent="");
 let ok=true;
 const name=document.getElementById("name").value.trim(), email=document.getElementById("email").value.trim();
 const mobile=document.getElementById("mobile").value.trim(), pass=document.getElementById("password").value;
 if(!/^[A-Za-z ]{2,50}$/.test(name)){error("nameError","Enter a valid name.");ok=false}
 if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){error("emailError","Enter a valid email.");ok=false}
 if(!/^[6-9]\d{9}$/.test(mobile)){error("mobileError","Enter a valid 10-digit mobile number.");ok=false}
 if(!/^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(pass)){error("passwordError","Use at least 8 characters with a letter and number.");ok=false}
 if(pass!==document.getElementById("confirmPassword").value){error("confirmError","Passwords do not match.");ok=false}
 if(!document.getElementById("course").value || !document.getElementById("year").value){alert("Please select course and year.");ok=false}
 if(!document.querySelector('input[name="gender"]:checked')){alert("Please select gender.");ok=false}
 if(!document.getElementById("terms").checked){error("termsError","You must accept the terms.");ok=false}
 if(ok) document.getElementById("success").textContent="Registration successful! Form is valid.";
});
