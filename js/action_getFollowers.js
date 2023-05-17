window.addEventListener('load',initGetFollowers);

function initGetFollowers(){
  var cible = document.getElementById("displayFollowers");
  cible.textContent="";
  document.getElementById("getFollowers").addEventListener("click", sendGetFollowers);
}
  
function sendGetFollowers(ev){ // form event listener
    ev.preventDefault();
    let url = 'services/getFollowers.php?';
    fetchFromJson(url)
    .then(processAnswer)
    .then(displayFollowers, displayError);
}

function displayFollowers(followers){
    var cible = document.getElementById("displayFollowers");
    cible.textContent="";
    if(followers.length>0){
        for (var i=0; i<followers.length; i++){
            let node;
            node = document.createElement('li');
            node.value = followers[i].userId;
            node.setAttribute("id", "follower");
            if(node.mutual)
                node.innerHTML =  followers[i].pseudo+' vous suit' ;
            else 
                node.innerHTML = followers[i].pseudo;
            cible.appendChild(node);
        }
        cible.style.display ="block";
  }
}

function displayError(error){
    window.alert(error);
}