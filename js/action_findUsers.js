window.addEventListener('load',initFindUsers);

function initFindUsers(){
  var cible = document.getElementById("findUsers");
  cible.textContent="";
  document.forms.form_findUsers.addEventListener("keyup", sendFormFindUsers);
}
  
function sendFormFindUsers(ev){ // form event listener
    ev.preventDefault();
    let url = 'services/findUsers.php?'+formDataToQueryString(new FormData(this));
    fetchFromJson(url)
    .then(processAnswer)
    .then(displayUsers, hide);
}

function displayUsers(users){
  var cible = document.getElementById("findUsers");
  cible.textContent="";
  if(users.length>0){
    cible.setAttribute("multiple","multiple");
    for (var i=0; i<users.length; i++){
      let node;
      node = document.createElement('option');
      node.value = users[i].userId;
      node.setAttribute("id", "findMessages");
      node.innerHTML = users[i].pseudo;
      cible.appendChild(node);
    }
  }
}

function hide(){
  var cible = document.getElementById("findUsers");
  cible.textContent="";
}