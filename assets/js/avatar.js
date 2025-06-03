import { info, controlPseudo, successBorder, alertBorder, checkAvatarForm, validateImage } from './dom.js';

window.onload = () => {
    const avatar_form = document.body.querySelector('#avatar_form');
    if (avatar_form) {
        const avatar_form_pseudo = avatar_form.querySelector('#avatar_form_pseudo');
        const avatar_form_image = avatar_form.querySelector('#avatar_form_image');
        const avatar_form_submit = avatar_form.querySelector('#avatar_form_submit');
        let information = "Indiquez votre pseudonyme";
        info(message, information);

        avatar_form_pseudo.addEventListener('focus', function () {
            information = "Max 30  exemple :  Belphegor#175  ";
            info(message, information);
        });

        avatar_form_pseudo.addEventListener('input', function () {
            controlPseudo(this);
            checkAvatarForm(avatar_form_pseudo, avatar_form_image, avatar_form_submit);
        });

        avatar_form_pseudo.addEventListener('blur', function () {
            controlPseudo(this);
            checkAvatarForm(avatar_form_pseudo, avatar_form_image, avatar_form_submit);
        });

        avatar_form_image.addEventListener('focus', function () {
            information = "Télécharger votre avatar";
            info(message, information);

        });

        avatar_form_image.addEventListener('input', function () {
            if (validateImage(this) == true) {
                successBorder(this);
            }
            else {
                alertBorder(this);
            }
            checkAvatarForm(avatar_form_pseudo, avatar_form_image, avatar_form_submit);

        });
        avatar_form_image.addEventListener('blur', function () {
            if (validateImage(this) == true) {
                successBorder(this);
            }
            else {
                alertBorder(this);
            }
            checkAvatarForm(avatar_form_pseudo, avatar_form_image, avatar_form_submit);
        });

        avatar_form_submit.addEventListener('click', function (e) {  //avatar_form_submit
            let inputs =checkAvatarForm.getElementsByTagName('input');
            let cpt =0; let nbBorder =0; let fieldSuccess=[];
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        });
    }
}

