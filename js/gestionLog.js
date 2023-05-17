window.addEventListener('load',initState);
window.addEventListener('load',initLog);

var currentUser = null;

function initState(){ // initialise l'état de la page
   let personne = document.body.dataset.personne;
   if(personne == null)
      etatDeconnecte();
   else{
      personne = JSON.parse(personne);
      alert(personne.pseudo);
      if(personne.login == null || personne.pseudo == null)
         etatDeconnecte();
      else
         etatConnecte(personne);
   }
}

function initLog(){ // mise en place des gestionnaires sur le formulaire de login et le bouton logout
   document.forms.form_login.addEventListener('submit',sendLogin); // envoi
   document.forms.form_login.addEventListener('input',function(){this.message.value='';}); // effacement auto du message
   document.getElementById('logout').addEventListener('click',sendLogout);
}

function sendLogin(ev){ // gestionnaire de l'évènement submit sur le formulaire de login
   ev.preventDefault();
   let url="services/login.php";
   fetchFromJson(url, {method: "post",body:new FormData(this),credentials:'same-origin'})
   .then(processAnswer)
   .then(etatConnecte, errorLogin)
}

function sendLogout(ev){ // gestionnaire de l'évènement click sur le bouton logout
   ev.preventDefault();
   let url = 'services/logout.php';
   fetchFromJson(url)
   .then(processAnswer)
   .then(etatDeconnecte, errorLogin)
}

function etatDeconnecte() { // passe dans l'état 'déconnecté'
    // cache ou montre les éléments
    for (let elt of document.querySelectorAll('.connecte'))
       elt.hidden=true;
    for (let elt of document.querySelectorAll('.deconnecte'))
       elt.hidden=false;
    // nettoie la partie personnalisée :
    currentUser = null; 
    delete(document.body.dataset.personne);
    document.querySelector('#titre_connecte').textContent='';
    //document.querySelector('#liste_favoris').textContent='';
    //document.querySelector('#avatar').src='';
}

function etatConnecte(personne) { // passe dans l'état 'connecté'
    currentUser = personne;
    // cache ou montre les éléments
    for (let elt of document.querySelectorAll('.deconnecte'))
       elt.hidden=true;
    for (let elt of document.querySelectorAll('.connecte'))
       elt.hidden=false;
       
    // personnalise le contenu
    document.querySelector('#titre_connecte').innerHTML = `${currentUser.prenom} ${currentUser.nom}`;
    
    //updateAvatar();
    
}


function errorLogin(error) { 
   // affiche error.message dans l'élément OUTPUT. 
   document.forms.form_login.message.value = 'échec : ' + error.message;
}

/**
function updateAvatar() {
    let changeAvatar = function(blob) {
      if (blob.type.startsWith('image/')){ // le mimetype est celui d'une image
        let img = document.getElementById('avatar');
        img.src = URL.createObjectURL(blob);
      }
    };
  fetchBlob('services/getAvatar.php?login='+currentUser.login)
    .then(changeAvatar);
}
 */