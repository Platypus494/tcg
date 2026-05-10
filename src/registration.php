<?php
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=utilisateurs', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('Erreur de connexion à la base de données :' . $e->getMessage());
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //On récupère les données du formulaire
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $firstname = trim($_POST['firstname'] ?? '');

    if (empty($email) || empty($password) || empty($firstname)) {
        echo "Veuillez remplir tous les champs.";
        return;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide";
        return;
    }

    //hashage du mdp
    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare('INSERT INTO utilisateurs (firstname,email,password) VALUES (:firstname,:email,:password)');
    $stmt->execute([
        'firstname' => $firstname,
        'email' => $email,
        'password' => $hash
    ]);

    $_SESSION['user_id'] = $pdo->lastInsertId();//on récupère le dernier id inséré dans la bdd
    $_SESSION['firstname'] = $firstname;
    $_SESSION['email'] = $email;

    header('Location:profil.php');

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="style/style.css">

    <!-- Police roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Source+Code+Pro:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">
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
    <section class="login dark-mode">
        <div>
            <form action="registration.php" method="POST">
                <label for="email">Prenom : </label>
                <input type="text" id="firstname" name="firstname" required>
                <br><br>
                <label for="email">Email : </label>
                <input type="email" id="email" name="email" required>
                <br><br>
                <label for="password"> Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                <br><br>
                <button type="submit">Creer un compte</button>
            </form><br>
            <a href="login.php">Vous avez un compte, connectez-vous</a>
        </div>
    </section>
</body>

</html>