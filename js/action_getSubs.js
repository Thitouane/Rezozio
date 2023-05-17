window.addEventListener('load',initGetSubs);

function initGetSubs(){
  var cible = document.getElementById("displaySubs");
  cible.textContent="";
  document.getElementById("getSubs").addEventListener("click", sendGetSubs);
}
  
function sendGetSubs(ev){ // form event listener
    ev.preventDefault();
    let url = 'services/getSubscriptions.php?';
    fetchFromJson(url)
    .then(processAnswer)
    .then(displaySubs, displayError);
}

function displaySubs(subs){
    var cible = document.getElementById("displaySubs");
    cible.textContent="";
    if(subs.length>0){
        for (var i=0; i<subs.length; i++){
            let node;
            node = document.createElement('li');
            node.value = subs[i].userId;
            node.setAttribute("id", "sub");
            node.innerHTML = subs[i].pseudo;
            cible.appendChild(node);
        }
    cible.style.display ="block";
  }
}

function displayError(error){
    window.alert(error);
}