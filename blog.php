<?php

$server = "localhost";
$username = "root";
$password = "";
$databasename = "cinema";


$dsn = "mysql:host=$server;dbname=$databasename";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $title = $_POST['title'];
    $body = $_POST['body'];
    $imagePath = '';


    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imagePath = 'uploads/' . $imageName;

       
        move_uploaded_file($imageTmpName, $imagePath);
    }

    $sql = 'INSERT INTO blogs (title, body, image) VALUES (:title, :body, :image)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':body' => $body,
        ':image' => $imagePath
    ]);

    echo 'Blog yazısı uğurla əlavə edildi!';
} catch (PDOException $e) {
    echo 'Xəta: ' . $e->getMessage();
}
?>
