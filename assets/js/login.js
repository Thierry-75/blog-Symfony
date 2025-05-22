import { alertBorder,clearField,controlEmail,controlPassword} from './dom.js';
window.onload = () => {
    /*----form_login----*/
    const form_login = document.body.querySelector('#form_login');
    if(form_login){
        const inputEmail = form_login.querySelector('#inputEmail');
        inputEmail.addEventListener('focus',function(){
            clearField(this);            
        });
        inputEmail.addEventListener('input',function(){
            controlEmail(this);
        });
        const inputPassword = form_login.querySelector('#inputPassword');
        inputPassword.addEventListener('focus',function(){
            clearField(this);
        });
        inputPassword.addEventListener('input',function(){
            controlPassword(this);
        });
        const inputSubmit = form_login.querySelector('#inputSubmit')
            inputSubmit.addEventListener('click',function(event){
                    const inputs =form_login.getElementsByTagName('input');
                    let fieldSuccess = [];
                    let counter =0;
                    let nbBorder = 0;
                    for(var i =0; i < inputs.length; i++){
                        if(inputs[i].type =='email' || inputs[i].type == 'password'){
                            fieldSuccess[i]=inputs[i];
                            if(fieldSuccess[i].value ==''){
                                alertBorder(fieldSuccess[i]);
                                counter++;
                            }
                            if(fieldSuccess[i].classList.contains('border-green-300')){
                                nbBorder++;
                            }
                        }
                    }
                    if(!counter == 0 || !fieldSuccess.length == nbBorder){
                        event.preventDefault();
                        event.stopImmediatePropagation();
                        return false;
                    } 
        });

    }
}

