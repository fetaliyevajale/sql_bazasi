<?php
$server = "localhost";
$username = "root";
$password = "";
$databasename = "cinema";
$dsn = "mysql:host=$server;dbname=$databasename";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT * FROM blogs';
    $stmt = $pdo->query($sql);
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Xəta: ' . htmlspecialchars($e->getMessage());
    $blogs = [];
}
?>
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Blogs</h1>
        <button>
            <a href="inter.php">Add new blog</a>
        </button>
        <div class="row">
            <?php foreach ($blogs as $blog): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if ($blog['image']): ?>
                            <img src="<?php echo htmlspecialchars($blog['image']); ?>" class="card-img-top" alt="Şəkil">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($blog['body']); ?></p>
                            <a href="edit_blog.php?id=<?php echo $blog['id']; ?>" class="btn btn-warning">Redaktə et</a>
                            <a href="delete_blog.php?id=<?php echo $blog['id']; ?>" class="btn btn-danger">Sil</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
