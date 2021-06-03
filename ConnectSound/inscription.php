<?php


require_once ('configPDO.php');;
/*$bdd = new PDO('mysql:host=127.0.0.1;dbname=connectsound; charset=utf8' ,'root','');
/*$bdd = new PDO('mysql:host=0y34u.myd.infomaniak.com ;dbname=0y34u_connectsound; charset=utf8' ,'0y34u_temp_1','jhtbvoRZ1nKg');
*/

if (isset($_POST['submit']))
{
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $mail = htmlspecialchars($_POST['mail']);
    $mail2 = htmlspecialchars($_POST['mail2']);
    $mdp = sha1($_POST['mdp']);
    $mdp2 = sha1($_POST['mdp2']);

    if(!empty($_POST['pseudo']) AND !empty($_POST['mail'])  AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2']))
    {
        $pseudolength = strlen($pseudo);
        if ($pseudolength<= 255)
        {
            if ($mail == $mail2)
            {
                if (filter_var($mail, FILTER_VALIDATE_EMAIL))
                {
                    $reqmail= $bdd ->prepare("SELECT * FROM profiles WHERE mail_Profiles = ?");
                    $reqmail-> execute(array($mail));
                    $mailexist = $reqmail -> rowCount();
                    if ($mailexist == 0) {
                        if ($mdp == $mdp2)
                        {
                            $insertmbr = $bdd->prepare("INSERT INTO profiles(pseudonyme_Profiles, mail_Profiles, password_Profiles) VALUES (?, ?, ?)");
                            $insertmbr->execute(array($pseudo, $mail, $mdp));
                            header("Location:connexion.php");
                        } else {
                            $erreur = "Les mots de passes ne correspondent pas !";
                        }
                    }
                    else
                    {
                        $erreur = "Un compte avec cette adresse email existe déjà!";
                    }
                }
                else{
                    $erreur = "L'email n'est pas valide";
                }
            }
            else
            {
                $erreur = "Les adresses email ne correspondent pas !";
            }
        }
        else
        {
            $erreur = "Votre pseudo est trop long !";
        }
        }
    else
        {
        $erreur = "Tout les champs doivent être complétés !";
    }
}


?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/style_inscription.css">
    <link rel="stylesheet" href="">

    <link rel="icon" type="image/png" sizes="32x32" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/logo.png">
</head>
<body cz-shortcut-listen="true">

<div class="inscription">
    <img src="img/inscription/img_inscription.png" alt="">
    <h3 id="title_inscription">Rejoignez les musiciens de Connect<span>Sound.</span></h3>

    <form method="POST" action="">


        <input type="text" placeholder="Votre pseudo" name="pseudo" id="pseudo" value="<?php if (isset($pseudo)) { echo $pseudo; }?>">

        <input type="email" placeholder="Confirmez votre mail" name="mail" id="mail" value="<?php if (isset($mail)) { echo $mail; }?>">

        <input type="email" placeholder="Votre mail" name="mail2" id="mail2" value="<?php if (isset($mail)) { echo $mail; }?>">

        <input type="password" placeholder="Votre mot de passe" name="mdp" id="mdp">

        <input type="password" placeholder="Confirmation du mot de passe" name="mdp2" id="mdp2">

        <div id="erreur">
            <?php
            if (isset($erreur))
            {
                echo $erreur;
            }
            ?>
        </div>

        <input type="submit" name="submit" value="Je m'inscris" id="bouton">

    </form>

    <p id="no_account">Vous avez déjà un compte ? <a href="connexion.php">Connectez-vous</a> !</p>

</div>

</body></html>
