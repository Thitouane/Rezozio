<?php
    require_once(__DIR__.'/lib/fonctionsHTML.php');

    $dataPersonne="";
    if (isset($personne))
        $dataPersonne = 'data-personne="'.htmlentities(json_encode($personne)).'"'; 
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Rézozio</title>
    <link rel="stylesheet" href="style/style.css" />
    <script src="js/fetchUtils.js"></script>
    <script src="js/gestionLog.js"></script>
    <script src="js/action_register.js"></script>
    <script src="js/action_findUsers.js"></script>
    <script src="js/action_findMessages.js"></script>
    <script src="js/action_findFollowedMessages.js"></script>
    <script src="js/action_getSubs.js"></script>
    <script src="js/action_getFollowers.js"></script>
    <script>
        function show_hide(obj){
            item = document.getElementById(obj);
            if(item.style.display=="block")
                item.style.display="none";
            else
                item.style.display="block";
            }
    </script>
</head>
                    <!-- FAIRE AVATAR // PRO -->
<?php
    echo "<body $dataPersonne>";
?>
    <img src="images/rézozio.png" alt="titre">
        <section class="deconnecte"> <!-- MODE DECONNECTE -->
            <section class="section_connexion">
                <form method="POST" action="services/login.php"  id="form_login">
                    <fieldset>
                        <legend>Connexion</legend> 
                        <label for="login">Login :</label>
                        <input type="text" name="login" id="login" max="25" min="3" required autofocus/></br>
                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" id="password" max="25" min="3" required/></br>
                        <button type="submit" name="valid">OK</button></br>
                        <output  for="login password" name="message"></output>
                    </fieldset>
                </form>

                <button name='show_hide' type="submit" onClick="show_hide('form_register')">Enregistrez-vous</button></br>
                <form method="POST" action="services/createUser.php"  id="form_register">
                    <fieldset>
                        <legend>Créer un compte</legend>
                        <label for="userId">Login :</label>
                        <input type="text" name="userId" id="userId" max="25" min="3" required autofocus/></br>
                        <label for="pseudo">Pseudo :</label>
                        <input type="text" name="pseudo" id="pseudo" required></br>
                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" id="password" max="25" min="3" required/></br>
                        <button type="submit" name="valid">OK</button></br>
                        <output  for="userId pseudo password" name="message"></output>
                    </fieldset>
                </form>  
            </section>
            <section class="section_messages">

                <button name='show_hide' type="submit" onClick="show_hide('inline')">Filtrer les messages</button></br>
                <div id="inline">
                    <form id="form_findUsers" action="services/findUsers.php" method="post">
                        <label for="searchedString">Find author :</label>
                        <input type="text" class="searchedString" name="searchedString" max="25" min="3" value="Rechercher..." onfocus="if(this.value=='Rechercher...')this.value=''" onblur="if(this.value=='')this.value='Rechercher...'" autocomplete="off" required/>
                    </form>
                    <form id="form_findMessages" action="services/findMessages.php" method="post">
                            <select id="findUsers" name="author"></select></br>
                            <label for="before">les messages en dessous de l'identifiant numéro:</label>
                            <input type="text" name="before"/></br>
                            <label for="count">nombre maximal de messages:</label>
                            <input type="text" name="count" value="15"/></br>
                            <button type="submit" name="valid">OK</button></br>
                    </form>
                </div>
                <ul id="messages"><?php  echo messagesToHTML($messages);?></ul>
            </section>
        </section>

        <section class="connecte"> <!-- MODE CONNECTE -->
            <h2 id="titre_connecte"></h2>

            <button name='show_hide' type="submit" onClick="show_hide('form_profile')">Modifier votre profil</button></br>
            <form method="POST" action="services/setProfile.php"  id="form_profile">
                <fieldset>
                    <legend>Modifier votre profil</legend> <!-- contraintes -->
                    <label for="pseudo">Pseudo :</label>
                    <input type="text" name="pseudo" id="pseudo" required="" autofocus=""/></br>

                    <label for="description">Description :</label>
                    <input type="text" name="description" id="description" required="" autofocus=""/></br>
                    
                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" id="password" required="required" /></br>
                    
                    <button type="submit" name="valid">OK</button></br>
                    <output  for="login password" name="message"></output>
                </fieldset>
            </form>

            <button id="logout">Déconnexion</button></br>

            <button name='show_hide' type="submit" id="getSubs">Voir Subs</button></br>
            <ul id="displaySubs"></ul>
           
            <button name='show_hide' type="submit" id="getFollowers">Voir Followers</button></br>
            <ul id="displayFollowers"></ul>

            <section class="section_messages">  <!-- a faire -->
                <form id="form_postMessage" action="services/postMessage.php" method="post">
                    <label for="postMessage">Postez votre message:</label>
                    <input type="text" name="postMessage" id="postMessage" required="" autofocus=""/></br>
                </form>

                <form id="form_findFollowedMessages" action="services/findFollowedMessages.php" method="post">
                    <label for="before">les messages en dessous de l'identifiant numéro:</label>
                    <input type="text" name="before"/></br>
                    <label for="count">nombre maximal de messages:</label>
                    <input type="text" name="count" value="15"/></br>
                    <button type="submit" name="valid">OK</button></br>
                </form>
                <ul id="messages2"><?php  echo messagesToHTML($messages);?></ul>
            </section>
        </section>
        
</body>
</html>