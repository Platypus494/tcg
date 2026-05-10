<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=utilisateurs','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    exit('Erreur de connexion à la base de données :'.$e->getMessage());
}
if ($_SERVER['REQUEST_METHOD'] ==='POST'){
    //On récupère les données du formulaire
    $firstname= trim($_POST['firstname'] ?? '');
    $email= trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $id =$_SESSION['user_id'];

    if(empty($email) || empty($firstname)){
        echo "Veuillez remplir tous les champs.";
        return;
    }
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
        echo "Adresse email invalide";
        return;
    }
    //test si l'utilisateur a modifié le mdp
    if (empty($password)){
        $stmt = $pdo->prepare('UPDATE utilisateurs SET email=:email,firstname=:firstname WHERE id = :id');
        $stmt->execute([
            'email'=>$email,
            'id'=>$id,
            'firstname'=>$firstname
        ]);
    }else {
        $hash = password_hash($password, PASSWORD_BCRYPT);//hashage du mdp
        $stmt = $pdo->prepare('UPDATE utilisateurs SET email=:email,firstname=:firstname,password=:password WHERE id = :id');
        $stmt->execute([
            'email'=>$email,
            'id'=>$id,
            'firstname'=>$firstname,
            'password' => $hash
        ]);
    }
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['user_id'] = $id;
    $_SESSION['firstname'] = $firstname;
    $_SESSION['email'] = $email;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">

    <!-- Police roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Source+Code+Pro:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">

    <title>Profil</title>
</head>

<body>
  <header>
        <nav class="menu_header">
            <a href="index.php">
                <img class="logo" src="img/gamer.png" alt="logo">
            </a>
            <div class="link">
                <ul>
                    <li><a class="element" href="learn.html">Pour débuter</a></li>
                    <li><a class="element" href="booster.html">Boosters</a></li>
                    <li><a class="element" href="trade.html">Echanger</a></li>
                    <li><a class="element" href="faq.html">FAQ</a></li>
                </ul>
            </div>
            <div class="other">
                <img class="switch-image" src="img/dark.png" alt="switch dark mode">
                <select class="language" name="language" id="language">
                    <option value="fr">fr &#x1F1EB;&#x1F1F7;</option>
                    <option value="en">en &#127468;&#127463; </option>
                    <option value="es">es &#127466;&#127480;</option>
                    <option value="jp">jp &#x1f1ef;&#x1f1f5;</option>
                </select>
                <a href="profil.php"><img class='logo' src="img/profil.png" alt="profil"></a>
            </div>
        </nav>
        <nav class="burger-menu">
            <img class="logo" src="img/gamer.png" alt="logo">
            <img class="burger-logo logo" src="img/burger-menu.png" alt="burger-logo">
        </nav>
    </header>
    <div class="burger-list hidden">
        <div class="list">
            <img class="close-logo logo" src="img/close.png" alt="close-logo">
            <ul>
                <li><a class="element" href="learn.html">Pour débuter</a></li>
                <li><a class="element" href="booster.html">Boosters</a></li>
                <li><a class="element" href="trade.html">Echanger</a></li>
                <li><a class="element" href="faq.html">FAQ</a></li>
                <li><a class="element" href="profil.php">Profil</a></li>
                <li>
                    <div class="flex">
                        <img class="switch-image2" src="img/dark.png" alt="switch dark mode">
                        <select class="language" name="language" id="language">
                            <option value="fr">fr &#x1F1EB;&#x1F1F7;</option>
                            <option value="en">en &#127468;&#127463; </option>
                            <option value="es">es &#127466;&#127480;</option>
                            <option value="jp">jp &#x1f1ef;&#x1f1f5;</option>
                        </select>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="image-txt-center dark-mode">
        <img class="main-image main" src="../img/re_main.jpg" alt="main image of the booster">
        <h1 class="txt">Profil</h1>
    </div>
    <section class="account dark-mode">
        <h1>Bienvenue, <?= htmlspecialchars($_SESSION['firstname']); ?> !</h1>
        <br>
    <form action="profil.php" method="POST">
    <label for="firstname">Prenom : </label>
    <input type="text" id="firstname" name="firstname" value="<?=$_SESSION['firstname']?>">
    <br>

    
    <label for="email">Email : </label>
    <input type="email" id="email" name="email" value="<?=$_SESSION['email']?>">
    <br>


     <label for="password"> Mot de passe :</label>
    <input type="password" id="password" name="password">
    <br><br>
    <button type="submit">Modifier</button>
</form>
<br>

        <form method="POST" action="logout.php">
        <button type="submit">Se déconnecter</button>
    </form>
    <br>
    <form method="POST" action="delete.php">
        <button type="submit">Supprimer le compte</button>
    </form>
    <br>
    </section>
    <footer>
        <div class="footer-container">
            <div class="item">
                <h2>Notes importantes</h2>
                <p>Toutes les images, textes et données de ce site web ne peuvent être reproduits sans autorisation.
                    Veuillez noter que les images utilisées sur ce site peuvent différer du produit final, car celui-ci
                    est
                    encore en cours de développement. </p>
            </div>

            <div class="item">
                <h2>Communauté</h2>
                <div class="list-type">
                    <img class="logo scale" src="../img/discord.png" alt="discord">
                    <img class='logo scale' src="../img/youtube.png" alt="youtube">
                    <img class='logo scale' src="../img/insta.png" alt="instagram">
                </div>
            </div>
        </div>
        <div class="nav-center">
            <nav>
                <ul>
                    <li><a class="element" href="#">À propos</a></li>
                    <li><a class="element" href="#">Mentions légales</a></li>
                    <li><a class="element" href="#">Contact</a></li>
                </ul>
            </nav>
        </div>
    </footer>
</body>
<script src="../script/main.js"></script>

</html>