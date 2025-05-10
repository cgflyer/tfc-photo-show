<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['photo'])) {
    // Define directories
    $uploadDir = __DIR__ . '/images/';
    $metaDir = __DIR__ . '/metadata/';

    // Ensure folders exist
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    if (!is_dir($metaDir)) mkdir($metaDir, 0755, true);

    // Sanitize input
    $title = htmlspecialchars(trim($_POST['title']));
    $author = htmlspecialchars(trim($_POST['author']));
    $caption = htmlspecialchars(trim($_POST['caption']));

    // Create a unique filename
    $fileExt = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $safeName = uniqid() . '.' . $fileExt;
    $filePath = $uploadDir . $safeName;
    $metaPath = $metaDir . pathinfo($safeName, PATHINFO_FILENAME) . '.meta';

    // Move uploaded file & save metadata
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
        $metadata = [
            'title' => $title,
            'author' => $author,
            'caption' => $caption,
            'filename' => $safeName,
            'upload_date' => date('Y-m-d H:i:s'),
        ];
        file_put_contents($metaPath, json_encode($metadata, JSON_PRETTY_PRINT));
        echo '<p>Upload successful!</p>';
    } else {
        echo '<p>Error uploading file.</p>';
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <label>Title: <input type="text" name="title" required></label><br>
    <label>Author: <input type="text" name="author" required></label><br>
    <label>Caption: <input type="text" name="caption"></label><br>
    <label>Photo: <input type="file" name="photo" required></label><br>
    <button type="submit">Upload</button>
</form>

<a href="index.php">View Gallery</a>