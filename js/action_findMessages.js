window.addEventListener('load',initFindMessages);

function initFindMessages(){
  document.forms.form_findMessages.addEventListener('submit',sendFormFindMessages);
}

function sendFormFindMessages(ev){ // form event listener
  ev.preventDefault();
  let url = 'services/findMessages.php?'+formDataToQueryString(new FormData(this));
  fetchFromJson(url)
  .then(processAnswer)
  .then(displayFindMessages, displayError);
}

function displayFindMessages(messages){ //verif
  let cible  = document.getElementById('messages');
  while (cible.firstChild) {
    cible.removeChild(cible.firstChild);
  }
  if (messages.length>0) {
    node = listToTable(messages);
    for (var i=0; i<messages.length; i++){
      let node;
      node = document.createElement('li');
      node.setAttribute("date", messages[i].datetime);
      node.innerHTML = "<div><p><span>From : </span>"+messages[i].pseudo+"</p><p>"+messages[i].content+"</p></div>";
      cible.appendChild(node);
    }
  }
}

function displayError(error){
  window.alert(error);
}