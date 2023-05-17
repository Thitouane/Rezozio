window.addEventListener('load',initRegister);

function initRegister(){
    document.forms.form_register.addEventListener('submit', sendFormRegister);
    document.forms.form_register.addEventListener('input',function(){this.message.value='';}); 
}

function sendFormRegister(ev){
    ev.preventDefault();
    let url = 'services/createUser.php?'+formDataToQueryString(new FormData(this));
    fetchFromJson(url)
    .then(processAnswer)
    .then(register, displayError);
}

function processAnswer(answer){
    if(answer.status=='ok')
      return answer.result;
    else  
      throw new Error(answer.message);
}

function register(answer){
    let cible  = document.getElementById('form_register');
    cible.message.value = answer.pseudo + ' est bien enregistré, veuillez vous connecter.';
}

function displayError(error){
    document.forms.form_register.message.value = 'échec : ' + error.message;
  }