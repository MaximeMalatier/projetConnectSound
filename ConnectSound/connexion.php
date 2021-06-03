<?php
session_start();





require_once ('configPDO.php');
/*$bdd = new PDO('mysql:host=0y34u.myd.infomaniak.com;dbname=0y34u_projets2; charset=utf8' ,'0y34u_projets2','Connectsound66613');*/
/*$bdd = new PDO('mysql:host=127.0.0.1;dbname=connectsound; charset=utf8' ,'root','');
/*$bdd = new PDO('mysql:host=0y34u.myd.infomaniak.com ;dbname=0y34u_connectsound; charset=utf8' ,'0y34u_temp_1','jhtbvoRZ1nKg');*/


if(isset($_POST['formconnexion']))
{
    $mailconnect = htmlspecialchars($_POST['mailconnect']);
    $mdpconnect = sha1($_POST['mdpconnect']);
    if (!empty($mailconnect) AND !empty($mdpconnect))
    {
        $requser = $bdd->prepare("SELECT * FROM profiles WHERE mail_Profiles = ? AND password_Profiles=?");
        $requser -> execute(array($mailconnect, $mdpconnect));
        $userexist = $requser ->rowCount();
        if($userexist == 1)
        {
            $userinfo = $requser-> fetch();
            $_SESSION['id_profile_Profiles'] = $userinfo['id_profile_Profiles'];
            $_SESSION['pseudo'] = $userinfo['pseudonyme_Profiles'];
            $_SESSION['mail'] = $userinfo['mail_Profiles'];
            header("Location:profil.php?id_profile_Profiles=".$_SESSION['id_profile_Profiles']);
        }
        else
        {
            $erreur = "Mauvais mail ou mot de passe !";
        }
    }
    else
    {
        $erreur = " Tout les champs doivent être complété !";
    }
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style_inscription.css">

    <link rel="icon" type="image/png" sizes="32x32" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/logo.png">

</head>
<body cz-shortcut-listen="true">
<div class="inscription">
    <img src="img/inscription/img_inscription.png" alt="">
    <h3 id="title_inscription">Connectez vous sur Connect<span>Sound.</span></h3>

    <form method="POST" action="">
        <input type="email" name="mailconnect" placeholder="Mail"/>
        <input type="password" name="mdpconnect" placeholder="Mot de passe"/>

        <div id="erreur">
        <?php
        if (isset($erreur))
        {
            echo $erreur;
        }
        ?>
        </div>

        <input type="submit" name="formconnexion" value="Se connecter !" id="bouton"/>

    </form>

    <p id="no_account">Vous n'avez pas de compte ? <a href="inscription.php">Inscrivez-vous</a> !</p>



</div>

</body></html>
