const clearBorder = function(champ){
    champ.classList.remove('border-solide','border-2','border-green-300');
    champ.classList.remove('border-solide','border-2','border-red-300');
    champ.classList.add('border-none');
}

const alertBorder = function(champ){
    champ.classList.remove('border-none','border-solid','border-2','border-green-300');
    champ.classList.add("border-solid",  "border-2", "border-red-300");
}

const successBorder = function(champ){
    champ.classList.remove('border-none','border-solid','border-2','border-red-300');
    champ.classList.add("border-solid","border-2", "border-green-300");
}

const clearField = function(champ){
    champ.value ="";
    clearBorder(champ);
}

const clearRemember = function(champ){
    champ.style.outline = "none";
}

const info = function (vue, text) {
    vue.textContent= text;
  };

const controlEmail = function(champ){
    const emailRegex = new RegExp("^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$");
    if(champ.value.match(emailRegex)){
        successBorder(champ);
        return true;
    }else{
        alertBorder(champ);
        return false;
    }
}

/**pattern: '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{12,12}$/i',
htmlPattern: '^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{12,12}$' */
const controlPassword = function(champ){
    const passwordRegex = new RegExp('^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{12,12}$');
    if(champ.value.match(passwordRegex)){
        successBorder(champ);
    }else{
        alertBorder(champ);
    }
}

const controlRemember = function(champ){
    if(champ.checked){
        champ.style.outline = "2px solid #0cfa40";
    }
    if(!champ.checked){
        champ.style.outline = "2px solid #fca5a5";
    }
}

const controlTerms = function(champ,slogan){
    if(champ.checked){
        slogan ="";
        info(message,slogan);
        let text = 'Conditions générales OK!';
        greenField(allowAgreeTerms, text);
        champ.classList.remove('border', 'border-gray-50');
        champ.style.outline='2px solid lightGreen';
    }
    if(!champ.checked){
        slogan ="";
        info(message,slogan);
        let text = 'Accepter les conditions générales !';
        redField(allowAgreeTerms, text);
        champ.classList.remove('border', 'border-gray-50');
        champ.style.outline='2px solid #fca5a5';
    }
}

const checkFields = function(){
    if(registration_form_email.classList.contains('border-green-300') && registration_form_plainPassword.classList.contains('border-green-300') && registration_form_agreeTerms.checked){
        message.innerHTML="";
        registration_form_submit.classList.remove('text-white')
        registration_form_submit.classList.add('text-yellow-200');
        registration_form_submit.textContent="Validez votre saisie";
    }
    else{
        registration_form_submit.classList.remove('text-yellow-200');
        registration_form_submit.classList.add('text-white')
        registration_form_submit.textContent="Créer votre compte";
    }
}

const initialEmail = function(){
    allowEmail.style.display ='none';
    allowEmail.innerHTML ='';
    allowEmail.classList.remove('text-red-300','text-green-300');
}

const greenField = function(champ,text){
    champ.style.display = 'block'; 
    champ.innerHTML=text;
    champ.classList.remove('text-red-300');
    champ.classList.add('text-green-300');
}

const redField = function(champ,text){
    champ.style.display = 'block'; 
    champ.innerHTML=text;
    champ.classList.remove('text-green-300');
    champ.classList.add('text-red-300');
}

const agreeTermsControl = function (champ, label) {
    if (champ.checked) {
      var information = "";
      info(message, information);
      var mention = "Conditions générales acceptées  OK !";
      greenAllow(allowAgreeTerms, mention);
      champ.style.outline = "2px solid lightgreen";
      label.classList.remove("font-semibold");
    } else if (!champ.checked) {
      var information = "Acceptez les conditions pour continuer...";
      info(message, information);
      var mention = "";
      redAllow(allowAgreeTerms, mention);
      champ.style.outline = "2px solid red";
      label.classList.add("font-semibold");
    }
  };


export {clearBorder,alertBorder,successBorder,clearField,controlEmail,info,greenField,controlPassword,
    redField,initialEmail,controlTerms,checkFields,controlRemember,clearRemember,agreeTermsControl}; // a list of exported variables