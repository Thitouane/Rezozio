<?php
require_once("lib/db_parms.php");
   

  // /!\ postman /!\ 


Class DataLayer{
    private $connexion;
    public function __construct(){

            $this->connexion = new PDO(
                       DB_DSN, DB_USER, DB_PASSWORD,
                       [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                       ]
                     );
    }

    function authentifier($login, $password){
      $sql = <<<EOD
      select
      login as "userId", pseudo, password
      from rezozio.users
      where login = :login
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':login', $login);
      $stmt->execute();
      $info = $stmt->fetch();
      if ($info && crypt($password, $info['password']) == $info['password'])
        return new Identite($info['userId'], $info['pseudo']);
      else
        return NULL;
    }

    function getUsers(){
      $sql = 'select * from rezozio.users';
      $stmt = $this->connexion->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll();
    }

    function getSubs(){
      $sql = 'select * from rezozio.subscriptions';
      $stmt = $this->connexion->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll();
    }

    function getUser($userId){
      $sql = "select login, pseudo from rezozio.users where login = :login";
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':login', $userId);
      $stmt->execute();
      return $stmt->fetch();
    }

    function getProfile($userId){  //verif
      $sql = <<<EOD
      select
        users.login as "userId", users.pseudo, users.description,
        s1.target is not null as "followed",

        s2.target is not null as "isFollower"
     from rezozio.users
     left join rezozio.subscriptions as s1 on users.login = s1.target and s1.follower = :current

     left join rezozio.subscriptions as s2 on users.login = s2.follower and s2.target = :current
     where users.login = :userId
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':userId', $userId);
      $stmt->bindValue(':current', $_SESSION['ident']->login);
      $stmt->execute();
      return $stmt->fetch();
    }

    function getMessage($messageId){
      $sql =  
      'select messages.id as "messageId", messages.author, users.pseudo, messages.content, messages.datetime 
      from rezozio.messages, rezozio.users
      where id = :msgId and users.login = messages.author';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':msgId', $messageId);
      $stmt->execute();
      return $stmt->fetch();
    }

    function getAvatar($userId){  
      $sql = "select avatar_type, avatar_small, avatar_large from rezozio.users where login = :login"; //case
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':login', $userId);
      $stmt->execute();
      return $stmt->fetch();
    }

    function uploadAvatar($type,$small,$large){  //verif
      $sql = "update rezozio.users set avatar_type = :type, avatar_small = :small avatar_large = :large
      where id = :userId";
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':userId', $_SESSION['ident']->login);
      $stmt->bindValue(':type',);
      $stmt->bindValue(':small',);
      $stmt->bindValue(':large',);
      $stmt->execute();
      return True;
    }

    function createUser($userId, $password, $pseudo){
      $sql = "insert into rezozio.users (login, password, pseudo) values (:userId, :password, :pseudo)";
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':userId',$userId);
      $stmt->bindValue(':password',password_hash($password,CRYPT_BLOWFISH));
      $stmt->bindValue(':pseudo',$pseudo);
      $stmt->execute();
      return True;
    }

    function findUsers($searchedString){
      $sql = 'select login as "userId", pseudo from rezozio.users where login like :s or pseudo like :s order by pseudo';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':s', '%'.$searchedString.'%');
      $stmt->execute();
      return $stmt->fetchAll();
    }

    function getMessages($limit){
      $sql = 'select messages.id as "messageId", messages.author, users.pseudo, messages.content, messages.datetime 
      from rezozio.messages, rezozio.users
      where users.login = messages.author
      order by datetime 
      limit :limit';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':limit', $limit);
      $stmt->execute();
      return $stmt->fetchAll();
    }
    function getMessagesBefore($before, $limit){
      $sql = 'select messages.id as "messageId", messages.author, users.pseudo, messages.content, messages.datetime 
      from rezozio.messages, rezozio.users
      where users.login = messages.author and id < :before
      order by datetime 
      limit :limit';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':limit', $limit);
      $stmt->bindValue(':before', $before);
      $stmt->execute();
      return $stmt->fetchAll();
    }
    function findMessagesByAuthor($author, $limit){
      $sql = 'select messages.id as "messageId", messages.author, users.pseudo, messages.content, messages.datetime 
      from rezozio.messages, rezozio.users
      where users.login = :author and users.login = messages.author
      order by datetime 
      limit :limit';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':limit', $limit);
      $stmt->bindValue(':author', $author);
      $stmt->execute();
      return $stmt->fetchAll();
    }
    function findMessages($author, $before, $count){
      $sql = 'select messages.id as "messageId", messages.author, users.pseudo, messages.content, messages.datetime 
      from rezozio.messages, rezozio.users
      where users.login = :author and users.login = messages.author and id < :before
      limit :lim';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':author', $author);
      $stmt->bindValue(':before', $before);
      $stmt->bindValue(':lim', $count);
      $stmt->execute();
      return $stmt->fetchAll();
    }

    function findFollowedMessages($before, $count){  //verif mais normalement c bon
      $sql = 'select messages.id as "messageId", messages.author, users.pseudo, messages.content, messages.datetime 
      from rezozio.messages, rezozio.users, rezozio.subscriptions
      where id < :before and :author = subscriptions.follower and users.login = subscriptions.target and author = subscriptions.target
      limit :count';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':author', $_SESSION['ident']->login);
      $stmt->bindValue(':before', $before);
      $stmt->bindValue(':count', $count);
      $stmt->execute();
      return $stmt->fetchAll();
    }
    function findFollowedMessagesBeforeless($count){  //verif mais normalement c bon
      $sql = 'select messages.id as "messageId", messages.author, users.pseudo, messages.content, messages.datetime 
      from rezozio.messages, rezozio.users, rezozio.subscriptions
      where :author = subscriptions.follower and users.login = subscriptions.target and author = subscriptions.target
      limit :count';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':author', $_SESSION['ident']->login);
      $stmt->bindValue(':count', $count);
      $stmt->execute();
      return $stmt->fetchAll();
    }

    function postMessage($source){
      $sql = 'insert into rezozio.messages (author, content) values ((select users.login from rezozio.users where users.login = :author), :content)';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':author', $_SESSION['ident']->login);
      $stmt->bindValue(':content', $source);
      $stmt->execute();
      return True;
    }
    function findNewestMessage(){
      $sql = 'select max(id) as "messageId" from rezozio.messages where author = :author';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':author', $_SESSION['ident']->login);
      $stmt->execute();
      return $stmt->fetch();
    }

    function setDesc($description){  //verif
      $sql = 'update rezozio.users set description = :description where login = :current';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':current', $_SESSION['ident']->login);
      $stmt->bindValue(':description', $description);
      $stmt->execute();
      return True;
    }
    function setPseudo($pseudo){
      $sql = 'update rezozio.users set pseudo = :pseudo where login = :current';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':current', $_SESSION['ident']->login);
      $stmt->bindValue(':pseudo', $pseudo);
      $stmt->execute();
      return True;
    }
    function setPassword($password){
      $sql = 'update rezozio.users set password = :password where login = :current';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':current', $_SESSION['ident']->login);
      $stmt->bindValue(':password', password_hash($password,CRYPT_BLOWFISH));
      $stmt->execute();
      return True;
    }
  
    function follow($target){
      $sql = 'insert into rezozio.subscriptions (follower, target) values ((select login from rezozio.users where login = :current),(select login from rezozio.users where login = :target))';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':target', $target);
      $stmt->bindValue(':current', $_SESSION['ident']->login);
      $stmt->execute();
      return True;
    }

    function unfollow($target){
      $sql='delete from rezozio.subscriptions where target = :target and follower = :current';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':target', $target);
      $stmt->bindValue(':current', $_SESSION['ident']->login);
      $stmt->execute();
      return True;
    }

    function getFollowers(){
      $sql = 'select users.login as "userId", users.pseudo, t2.follower is not null as "mutual"
      from rezozio.subscriptions as t1
      left join rezozio.subscriptions as t2 on t1.follower = t2.target and t2.follower = :target
      join rezozio.users on login = t1.follower
      where t1.target = :target';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':target', $_SESSION['ident']->login);
      $stmt->execute();
      return $stmt->fetchAll();
    }

    function getSubscriptions(){
      $sql = 'select users.login as "userId", users.pseudo
      from rezozio.subscriptions as t1
      left join rezozio.subscriptions as t2 on t1.target = t2.follower and t2.target = :target
      join rezozio.users on login = t1.target
      where t1.follower = :target';
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':target', $_SESSION['ident']->login);
      $stmt->execute();
      return $stmt->fetchAll();
    }

}
?>