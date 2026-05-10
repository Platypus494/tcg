<?php
session_start();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=utilisateurs', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('Erreur de connexion à la base de données :' . $e->getMessage());
}
$stmt = $pdo->prepare('DELETE FROM utilisateurs WHERE id=:id');
$stmt->execute(['id' => $_SESSION['user_id']]);
session_destroy();
header('Location: index.php');
exit;
?>