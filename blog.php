<?php
$server = "localhost";
$username = "root";
$password = "";
$databasename = "cinema";
$dsn = "mysql:host=$server;dbname=$databasename";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = htmlspecialchars($_POST['title']);
        $body = htmlspecialchars($_POST['body']);
        $imagePath = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024;

            $fileType = $_FILES['image']['type'];
            $fileSize = $_FILES['image']['size'];
            $imageName = basename($_FILES['image']['name']);
            $imageTmpName = $_FILES['image']['tmp_name'];

            if (in_array($fileType, $allowedTypes) && $fileSize <= $maxSize) {
                $imagePath = 'uploads/' . $imageName;
                move_uploaded_file($imageTmpName, $imagePath);
            }
        }

        $sql = 'INSERT INTO blogs (title, body, image) VALUES (:title, :body, :image)';
        $stmt = $pdo->prepare($sql);
        $check = $stmt->execute([
            ':title' => $title,
            ':body' => $body,
            ':image' => $imagePath
        ]);

        if ($check) {
            header('Location: blogs.php');
        }
    }
} catch (PDOException $e) {
    echo 'Xəta: ' . htmlspecialchars($e->getMessage());
}
