<?php
session_start();

require_once ('configPDO.php');
/*$bdd = new PDO('mysql:host=127.0.0.1;dbname=connectsound; charset=utf8' ,'root','');
/*$bdd = new PDO('mysql:host=0y34u.myd.infomaniak.com ;dbname=0y34u_connectsound; charset=utf8' ,'0y34u_temp_1','jhtbvoRZ1nKg');*/


if (isset($_GET['id_profile_Profiles']) AND $_GET['id_profile_Profiles'] > 0)
{

$getid = intval($_GET['id_profile_Profiles']);
$requser = $bdd->prepare('SELECT * FROM profiles WHERE id_profile_Profiles= ?');
$requser->execute(array($getid));
$userinfo = $requser->fetch();

$pseudo = $userinfo['pseudonyme_Profiles'];


        if(isset($_POST['send'])) {
            $message = htmlspecialchars(($_POST['message']));
            $insererMessage = $bdd->prepare('INSERT INTO messages(message_content, id_receiver, id_author) VALUES(?, ?, ?)');
            $insererMessage->execute(array($message, $getid, $_SESSION['id_profile_Profiles']));
        }




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collaborateurs</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="css/style_header.css">
    <link rel="stylesheet" href="css/style_collaborateurs">
    <link rel="stylesheet" href="css/style_typo.css">
</head>
<body>


<header>



    <h1><img src="img/logo.png" alt="logo connectsound">Connect<span>Sound.</span></h1>

    <div class="menu">
        <ul>
            <li><img src="img/accueil.png" alt="accueil pictogramme"><a href="accueil.php?id_profile_Profiles=<?php echo $_SESSION['id_profile_Profiles']?>">Accueil</a></li>
            <li><img src="img/profil.png" alt="profil pictrogramme"><a href="profil.php?id_profile_Profiles=<?php echo $_SESSION['id_profile_Profiles']?>">Profil</a></li>
            <li><img src="img/handshake.png" alt="handshake pictogramme"><a href="collaborateurs.php">Collaborateurs</a></li>
        </ul>
    </div>


</header>



    <div class="content">
    <aside class="collaborateurs">


        <h2 id="title_collab">Collaborateurs</h2>

        <?php
        $recupUser = $bdd ->query('SELECT * FROM profiles');
        while ($userinfo = $recupUser ->fetch()){
            ?>
            <div class="collab">

                <?php
                if (!empty($userinfo['picture_Profiles']))
                {
                    ?>
                    <img id="profile-img" src="img/profile/profile_picture/<?php echo $userinfo['picture_Profiles']; ?>" alt="" width="150"/>
                    <?php
                }
                ?>
                <h2><a href="collaborateurs.php?id_profile_Profiles=<?php echo $userinfo['id_profile_Profiles'] ?>"><?php echo $userinfo['pseudonyme_Profiles']; ?></a></h2>


            </div>

            <?php
        }
        ?>


    </aside>

    <main class="messagerie">
        <div class="header-msg">

            <img src="img/profile/profile_picture/<?php echo $getid?>"  alt="">
            <h2><?php echo $pseudo; ?></h2>
        </div>


    <div class="message_area">
    <?php

    $recupMessages = $bdd ->prepare('SELECT * FROM messages WHERE id_author = ? AND id_receiver = ? OR id_author = ? AND id_receiver= ?');
    $recupMessages->execute(array($_SESSION['id_profile_Profiles'], $getid, $getid , $_SESSION['id_profile_Profiles']));
    while($message = $recupMessages->fetch()){
        if($message['id_receiver'] == $_SESSION['id_profile_Profiles']){
            ?>
        <div class="message_receive">
        <div class="message">
            <p><?= $message['message_content']; ?></p>
        </div>
        </div>
        <div class="my_messages">
            <?php
        }elseif ($message['id_receiver'] == $getid){
            ?>
                    <div class="message_send">
                    <div class="your_message">
                        <p><?= $message['message_content']; ?></p>
                    </div>
                    </div>


            <?php
        }

        ?>
        <?php
    }
    ?>
        </div>
        <div class="type_msg">
            <form method="post">
                <input type="text" placeholder="Envoyer un message ..." name="message" id="message">
                <input type="submit" placeholder="Envoyer" name="send">
            </form>
        </div>
    </main>


</div>

<!--<script>
    setInterval('load_messages()', 1500);

    function load_messages(){
        $(".message_area").load('message.php');


    }
</script>-->
</body>
</html>

<?php
}
    ?>