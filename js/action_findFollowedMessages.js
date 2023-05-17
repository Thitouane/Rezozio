window.addEventListener('load',initFindFollowedMessages);

function initFindFollowedMessages(){
  document.forms.form_findFollowedMessages.addEventListener('submit',sendFormFindFollowedMessages);
}

function sendFormFindFollowedMessages(ev){ // form event listener
  ev.preventDefault();
  let url = 'services/findFollowedMessages.php?'+formDataToQueryString(new FormData(this));
  fetchFromJson(url)
  .then(processAnswer)
  .then(displayFindFollowedMessages, displayError);
  
}

function displayFindFollowedMessages(messages){ //verif
  let cible  = document.getElementById('messages2');
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