<?php
$uploadDir = 'images/';
$metaDir = 'metadata/';
$images = array_diff(scandir($uploadDir), ['.', '..']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/style.css">
    <title>Photo Gallery</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Your Bootstrap Carousel or Other Content Goes Here -->

<!-- Bootstrap Carousel -->
<div id="photoCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php $first = true; ?>
        <?php foreach ($images as $img): ?>
            <?php
            $metaFile = $metaDir . pathinfo($img, PATHINFO_FILENAME) . '.meta';
            $meta = file_exists($metaFile) ? json_decode(file_get_contents($metaFile), true) : [];
            ?>
            <div class="carousel-item <?= $first ? 'active' : '' ?>">
                <img width="960" src="<?= $uploadDir . $img ?>" class="d-block w-100">
                <div class="carousel-caption">
                    <h5><?= $meta['title'] ?? 'Untitled' ?></h5>
                    <p><?= $meta['caption'] ?? '' ?> - <strong><?= $meta['author'] ?? 'Unknown' ?></strong></p>
                </div>
            </div>
            <?php $first = false; ?>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Link to Upload Page -->
<a href="upload.php">Upload a New Photo</a>

<!-- AJAX Auto-Refresh -->
<script>
function refreshCarousel() {
    fetch('ajax_refresh.php')
        .then(response => response.json())
        .then(data => {
            let carouselInner = document.querySelector('.carousel-inner');
            carouselInner.innerHTML = '';
            let isFirst = true;

            data.forEach(item => {
                let newSlide = document.createElement('div');
                newSlide.className = `carousel-item ${isFirst ? 'active' : ''}`;
                newSlide.innerHTML = `
                    <img src="${item.image}" class="d-block w-100">
                    <div class="carousel-caption">
                        <h5>${item.title}</h5>
                        <p>${item.caption} - <strong>${item.author}</strong></p>
                    </div>
                `;
                carouselInner.appendChild(newSlide);
                isFirst = false;
            });
        });
}

// Refresh every 30 seconds
setInterval(refreshCarousel, 30000);
</script>

    <!-- Bootstrap JavaScript (Including Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
