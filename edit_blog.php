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

        $sql = 'SELECT * FROM blogs WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = htmlspecialchars($_POST['title']);
        $body = htmlspecialchars($_POST['body']);
        $imagePath = $blog['image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024; // 2 MB

            $fileType = $_FILES['image']['type'];
            $fileSize = $_FILES['image']['size'];
            $imageName = basename($_FILES['image']['name']);
            $imageTmpName = $_FILES['image']['tmp_name'];

            if (in_array($fileType, $allowedTypes) && $fileSize <= $maxSize) {
                $imagePath = 'uploads/' . $imageName;
                move_uploaded_file($imageTmpName, $imagePath);
            }
        }

        $sql = 'UPDATE blogs SET title = :title, body = :body, image = :image WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':body' => $body,
            ':image' => $imagePath,
            ':id' => $id
        ]);

        echo 'Blog yazısı uğurla yeniləndi!';
    }
} catch (PDOException $e) {
    echo 'Xəta: ' . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Redaktə</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Blog Redaktə</h1>
        <form action="edit_blog.php?id=<?php echo $blog['id']; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Başlıq:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="body">Məzmun:</label>
                <textarea class="form-control" id="body" name="body" rows="5" required><?php echo htmlspecialchars($blog['body']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Yeni Şəkil:</label>
                <input type="file" class="form-control-file" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Yenilə</button>
        </form>
    </div>
</body>
</html>

