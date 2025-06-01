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
    const passwordRegex = new RegExp('^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{12}$');
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

const checkFields = function(champ1,champ2,champ3,bouton){
    if(champ1.classList.contains('border-green-300') && champ2.classList.contains('border-green-300') && champ3.checked){
        message.innerHTML="";
        bouton.classList.remove('btn-confirmation');
        bouton.classList.add('btn-validation');
        bouton.textContent="Validez votre saisie";
    }
    else{
        bouton.classList.remove('btn-validation');
        bouton.classList.add('btn-confirmation');
        bouton.textContent="Saisir";
    }
}

const checkEmail = function(champ,bouton){
    if(champ.classList.contains('border-green-300')){
        message.innerHTML="";
        bouton.classList.remove('btn-confirmation');
        bouton.classList.add('btn-validation');
        bouton.textContent='Validez votre saisie';
    }else{
        bouton.classList.remove('btn-validation');
        bouton.classList.add('btn-confirmation');
        bouton.textContent="Saisir";
    }
}
const checkPasswords = function(champ1,champ2,bouton){  //localeCompare
    if(champ1.value===champ2.value){
    if(champ1.classList.contains('border-green-300') && champ2.classList.contains('border-green-300')){
    message.innerHTML="";
    bouton.classList.remove('btn-confirmation');
    bouton.classList.add('btn-validation');
    bouton.textContent='Validez votre saisie';
        }
    }else{
    bouton.classList.remove('btn-validation');
    bouton.classList.add('btn-confirmation');
    bouton.textContent="Nouveau mot de passe";
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

const yellowfield = function(champ,text){
    champ.style.display = 'block';
    champ.innerHTML = text;
    champ.classList.remove('text-green-300, text-red-300');
    champ.classList.add('text-yellow-400');
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


export {clearBorder,alertBorder,successBorder,clearField,controlEmail,info,greenField,controlPassword,checkPasswords,
    redField,initialEmail,controlTerms,checkFields,controlRemember,clearRemember,agreeTermsControl,yellowfield,checkEmail}; // a list of exported variables
