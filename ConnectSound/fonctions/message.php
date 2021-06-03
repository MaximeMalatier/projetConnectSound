<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=connectsound; charset=utf8', 'root', '');




if (isset($getid)){
    $getid = intval($_GET['id_profile_Profiles']);
    var_dump($getid);
    $requser = $bdd->prepare('SELECT * FROM Profiles WHERE id_profile_Profiles= ?');
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();


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
<?php
}
    ?>