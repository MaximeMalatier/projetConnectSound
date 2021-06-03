<?php
session_start();

require_once ('configPDO.php');
/*$bdd = new PDO('mysql:host=127.0.0.1;dbname=connectsound; charset=utf8' ,'root','');*/

/*$bdd = new PDO('mysql:host=0y34u.myd.infomaniak.com ;dbname=0y34u_connectsound; charset=utf8' ,'0y34u_temp_1','jhtbvoRZ1nKg');*/

if (isset($_GET['id_profile_Profiles']) AND $_GET['id_profile_Profiles'] > 0)
{

$getid = intval($_GET['id_profile_Profiles']);
$requser = $bdd->prepare('SELECT * FROM profiles WHERE id_profile_Profiles= ?');
$requser->execute(array($getid));
$userinfo = $requser->fetch();


?>

    <?php

    $articles = $bdd->query('SELECT * FROM articles ORDER BY date_time_publication DESC LIMIT 0,25');

    ?>



<html>
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="css/style_header.css">
    <link rel="stylesheet" href="css/style_typo.css">
    <link rel="stylesheet" href="css/style_index.css">


    <link rel="icon" type="image/png" sizes="32x32" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/logo.png">
</head>
<body>

<header>


    <!-- RECHERCHE -->
    <div class="find">
        <a href="recherche.php">Recherchez de nouveaux collaborateurs !</a>
        <img src="img/loupeblanche.png">
    </div>

    <h1><img src="img/logo.png" alt="logo connectsound">Connect<span>Sound.</span></h1>

    <!-- MENU -->
    <div class="menu">
    <ul>
        <li><img src="img/accueil.png" alt="accueil pictogramme"><a href="">Accueil</a></li>
        <li><img src="img/profil.png" alt="profil pictrogramme"><a href="profil.php?id_profile_Profiles=<?php echo $_SESSION['id_profile_Profiles']?>">Profil</a></li>
        <li><img src="img/handshake.png" alt="handshake pictogramme"><a href="collaborateurs.php?id_profile_Profiles=<?php echo $_SESSION['id_profile_Profiles']?>">Collaborateurs</a></li>
    </ul>
    </div>

</header>


<div class="content">

    <aside class="suggest">


        <h2>Vous aimerez ces profils</h2>
        <hr>

        <?php
        $recupUser = $bdd ->query('SELECT * FROM profiles');
        while ($userinfo = $recupUser ->fetch()){
        ?>

        <div class="profile_suggest">
            <?php
            if (!empty($userinfo['picture_Profiles']))
            {
                ?>
                <img src="img/profile/profile_picture/<?php echo $userinfo['picture_Profiles']; ?>" alt="" width="150"/>
                <?php
            }
            ?>
            <div class="profile_information">
                <h3><?php echo $userinfo['pseudonyme_Profiles']; ?></h3>



            <div class="bouton">
                <a href="profil.php?id_profile_Profiles=<?php echo $userinfo['id_profile_Profiles'] ?>">Voir le profil</a>

            </div>
            </div>
        </div>
            <?php
        }
        ?>
    </aside>


<main class="fil">

    <?php while ($a = $articles->fetch()) { ?>
    <div class="publication">
        <img src="img/profile/profile_picture/<?php echo $a['id_publisher'];?>" alt="">
        <div class="profile_information">
        <h2>

            <?php
            $publisher = $a['id_publisher'];
            $profilpublish = $bdd->query("SELECT pseudonyme_Profiles FROM profiles WHERE id_profile_Profiles ='$publisher'");
            $name = $profilpublish->fetch();
            echo $name[0];



            ?>

        </h2>
        <p>Chanteur / Guitariste</p>
        <p><?= $a['date_time_publication']?></p>
        </div>
        <hr>
        <p><?= $a['contenu'] ?></p>
        <div class="img_publication">
            <img id="img_publi" src="img/publication/img_publication/<?php echo $a['img_article'];?>" </p>
        </div>
        <a href="#"><?= $a['link'] ?></a>
        <hr>


        <div id="react">
        <img src="img/publication/like.png" alt="">
        <img src="img/publication/partager.png" alt="">
        <img src="img/publication/enregistrer.png" alt="">
        </div>

    </div>
        <?php
        }
        ?>




</main>


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
</div>

</body>
</html>

<?php
}

    ?>

