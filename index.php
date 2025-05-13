<?php
$uploadDir = 'images/';
$metaDir = 'metadata/';
$gallery_event_title = 'TFC 2025 Poker Run and Picnic';
$default_image = 'Logo2d.jpg';
$images = array_diff(scandir($uploadDir), ['.', '..']);
$ltg_tile_columns = 3;
$ltg_tile_images = 3;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=.8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style.css">
    <title>Photo Gallery</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark">

<div class="live-tile-gallery container-fluid">
        <div class="heading pt-4 ">
            <h1 class="text-center text-white pt-4"> <?= $gallery_event_title ?> </h1>
        </div>

  <div class="row py-5 px-lg-5 px-sm-0">
  <?php
      for ($column_item = 0; $column_item < 3; $column_item++) { ?>
        <div id="<?= 'ltg-c' . $column_item ?>" class="column">
          <?php for ($image_item = 0; $image_item < 3; $image_item++) { 
            $image_id_tag = 'ltg-c'. $column_item . '-i' . $image_item; 
            $image_class = $image_item + $column_item == 0 ? 'class="h:scale-1"' : ''; ?>
            <img id="<?= $image_id_tag ?>" src="<?= $uploadDir . $default_image ?>" 
               <?= $image_class ?> alt="" srcset="">
          <?php
          }
        ?>
        </div>
      <?php
      }
      ?>
  </div>
</div>   

<!-- Link to Upload Page -->
<a href="upload.php">Upload a New Photo</a>

<!-- AJAX Auto-Refresh -->
<script>
function shuffleArray(array) {
    let shuffled = [...array]; // Copy the array
    for (let i = shuffled.length - 1; i > 0; i--) {
        let j = Math.floor(Math.random() * (i + 1)); // Random index
        [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]]; // Swap
    }
    return shuffled;
}


function refreshCarousel(grid_rows, grid_cols) {
    fetch('ajax_refresh.php')
        .then(response => response.json())
        .then(data => {
            let carouselInner = document.querySelector('.live-tile-gallery.row');
            let images = shuffleArray(data);
            let index = 0;
            for (r=0; r < grid_rows; r++) {
                for (c=0; c < grid_cols; c++) {
                    let image_target = 'ltg-c' + c + '-i' + r;
                    let image_path = images.length > 0 ? images[index % images.length].image : "<?=$uploadDir . $default_image?>";
                    document.getElementById(image_target).src = image_path;
                    index += 1;
                }
            }
        });
}

// Refresh every 30 seconds
setInterval(() => refreshCarousel(<?=$ltg_tile_images?>, <?=$ltg_tile_columns?>), 5000);
window.onload = function() {
    refreshCarousel(<?=$ltg_tile_images?>, <?=$ltg_tile_columns?>);
 };

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
