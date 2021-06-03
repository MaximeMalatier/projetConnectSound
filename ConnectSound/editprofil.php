<?php
session_start();

require_once ('configPDO.php');
/*$bdd = new PDO('mysql:host=127.0.0.1;dbname=connectsound; charset=utf8' ,'root','');
/*$bdd = new PDO('mysql:host=0y34u.myd.infomaniak.com ;dbname=0y34u_connectsound; charset=utf8' ,'0y34u_temp_1','jhtbvoRZ1nKg');*/


if (isset($_SESSION['id_profile_Profiles']))
{
    $requser = $bdd->prepare("SELECT * FROM profiles WHERE id_profile_Profiles=?");
    $requser-> execute(array($_SESSION['id_profile_Profiles']));
    $user = $requser->fetch();

    if (isset($_POST['newpseudo']) AND !empty($_POST['newpseudo'] AND $_POST['newpseudo'] != $user['pseudonyme_Profiles']))
    {
        $newpseudo = htmlspecialchars($_POST['newpseudo']);
        $insertpseudo = $bdd->prepare("UPDATE profiles SET pseudonyme_Profiles =? WHERE id_profile_Profiles=? ");
        $insertpseudo->execute(array($newpseudo, $_SESSION['id_profile_Profiles']));
        header('Location: profil.php?id='.$_SESSION['id_profile_Profiles']);
    }

    if (isset($_POST['newmail']) AND !empty($_POST['newmail'] AND $_POST['newmail'] != $user['mail_Profiles']))
    {
        $newmail = htmlspecialchars($_POST['newmail']);
        $insertmail = $bdd->prepare("UPDATE profiles SET mail_Profiles =? WHERE id_profile_Profiles=? ");
        $insertmail->execute(array($newmail, $_SESSION['id_profile_Profiles']));
        header('Location: profil.php?id='.$_SESSION['id_profile_Profiles']);
    }

    if (isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2']))
    {
        $mdp1 = sha1($_POST['newmdp1']);
        $mdp2 = sha1($_POST['newmdp2']);

        if ($mdp1 == $mdp2)
        {
          $insertmdp = $bdd->prepare("UPDATE profiles SET password_Profiles = ? WHERE id_profile_Profiles=?");
          $insertmdp->execute(array($mdp1, $_SESSION['id_profile_Profiles']));

            header('Location: profil.php?id_profile_Profiles='.$_SESSION['id_profile_Profiles']);
        }
        else
        {
            $msg = "Vos deux mots de passe ne correspondent pas!";
        }
    }

    if (isset($_FILES['photo_profil']) AND !empty($_FILES['photo_profil']['name']))
    {
        $taillemax = 2097152;
        $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
        if ($_FILES['photo_profil']['size'] <= $taillemax)
        {
            $extensionsUpload = strtolower(substr(strrchr($_FILES['photo_profil']['name'], '.'), 1));
            if (in_array($extensionsUpload, $extensionsValides))
            {
                $chemin = "img/profile/profile_picture/".$_SESSION['id_profile_Profiles'].".".$extensionsUpload;
                $resultat = move_uploaded_file($_FILES['photo_profil']['tmp_name'], $chemin);
                if ($resultat)
                {
                    $updatePicture = $bdd->prepare("UPDATE profiles SET picture_Profiles = :picture_Profiles WHERE id_profile_Profiles = :id_profile_Profiles ");
                    $updatePicture->execute(array(
                        'id_profile_Profiles' => $_SESSION['id_profile_Profiles'],
                        'picture_Profiles' => $_SESSION['id_profile_Profiles'].".".$extensionsUpload

                    ));
                    header('Location: profil.php?id_profile_Profiles='.$_SESSION['id_profile_Profiles']);
                }
                else
                {
                    $msg = "Erreur durant l'importation de votre photo";
                }
            }
            else
            {
                $msg = "La photo doit être au format jpg, jpeg, gif ou png.";
            }
        }
        else
        {
            $msg = "Votre photo de profil est trop grande !";
        }
    }
?>

<html><head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body cz-shortcut-listen="true">
<div>
    <h2>Edition de mon profil</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label>Pseudo :</label>
        <input type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $user['pseudonyme_Profiles']; ?>"/>
        <br/>

        <label>Mail :</label>
        <input type="email" name="newmail" placeholder="Mail" value="<?php echo $user['mail_Profiles']; ?>"/>
        <br/>

        <label>Mot de passe :</label>
        <input type="password" name="newmdp1" placeholder="Mot de passe" "/>
        <br/>

        <label>Confirmation du mot de passe :</label>
        <input type="password" name="newmdp2" placeholder="Confirmation du mot de passe" "/>
        <br/>

        <label>Photo de profil :</label>
        <input type="file" name="photo_profil"/>

        <input type="submit" value="Mettre à jour mon profil !">


    </form>

    <?php
    if (isset($msg)) { echo $msg;}
    ?>



</div>

</body></html>
<?php
}
else
{
    header("Location: connexion.php");
}
?>