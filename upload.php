<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Read query parameters safely
  $tailNumber = isset($_GET['tailNumber']) ? htmlspecialchars($_GET['tailNumber']) : '';
  $location = isset($_GET['location']) ? htmlspecialchars($_GET['location']) : '';
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['photo'])) {
    // Define directories
    $uploadDir = __DIR__ . '/images/';
    $metaDir = __DIR__ . '/metadata/';

    // Ensure folders exist
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    if (!is_dir($metaDir)) mkdir($metaDir, 0755, true);

    // Sanitize input
    $title = htmlspecialchars(trim($_POST['tailNumber']));
    $author = htmlspecialchars(trim($_POST['location']));
    $caption = htmlspecialchars(trim($_POST['caption']));

    // Create a unique filename
    $fileExt = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $safeName = uniqid() . '.' . $fileExt;
    $filePath = $uploadDir . $safeName;
    $metaPath = $metaDir . pathinfo($safeName, PATHINFO_FILENAME) . '.meta';

    // Move uploaded file & save metadata
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
        $metadata = [
            'tailNumber' => $title,
            'location' => $author,
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sky-Themed Upload Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="sky-theme">

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="form-container">
            <h2 class="text-center text-primary"><i class="bi bi-cloud"></i> Upload Image</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <!-- Tail Number (Optional) -->
                <div class="mb-3">
                    <label for="tailNumber" class="form-label">Tail Number</label>
                    <input type="text" class="form-control" id="tailNumber" value="<?= $tailNumber ?>"
                        name="tailNumber" placeholder="Enter tail number">
                </div>

                <!-- Location (Optional) -->
                <div class="mb-3">
                    <label for="location" class="form-label">Location (Airport "Code)
                    <input type="text" class="form-control" id="location" value="<?= $location ?>"
                         name="location" placeholder="Enter location">
                </div>

                <!-- Caption (Optional) -->
                <div class="mb-3">
                    <label for="caption" class="form-label">Caption</label>
                    <textarea class="form-control" id="caption" name="caption" rows="2" placeholder="Write a caption"></textarea>
                </div>

                <!-- Image Upload (Required) -->
                <div class="mb-3">
                    <label for="photo" class="form-label">Upload Image (Required)</label>
                    <input type="file" class="form-control" id="photo" name="photo" required>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

