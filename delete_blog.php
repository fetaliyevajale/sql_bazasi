<?php
$server = "localhost";
$username = "root";
$password = "";
$databasename = "cinema";
$dsn = "mysql:host=$server;dbname=$databasename";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);

   
        $sql = 'SELECT image FROM blogs WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($blog['image'] && file_exists($blog['image'])) {
            unlink($blog['image']);
        }

        $sql = 'DELETE FROM blogs WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $check = $stmt->execute([':id' => $id]);
        if($check){
            header('Location: blogs.php');
        }    
    }
} catch (PDOException $e) {
    echo 'Xəta: ' . htmlspecialchars($e->getMessage());
}
?>
