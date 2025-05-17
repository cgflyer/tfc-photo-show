<?php
$uploadDir = 'images/';
$metaDir = 'metadata/';
$gallery_event_title = 'TFC 2025 Poker Run and Picnic';
$default_image = 'Logo2d.jpg';
$images = array_diff(scandir($uploadDir), ['.', '..']);
$ltg_tile_columns = 3;
$ltg_tile_images = 3;
$ltg_tile_replace_pct = 0.11;
// still todo: when image is clicked display modal-popup with data for that image
// but we have a problem that the underlying grid will try to refresh the image
// so we have to suspend the refresh when the modal is displayed.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <!-- disabling button so only local users with qr code can submit -->
        <div class="text-center mt-3">
          <!--<button type="button" class="btn btn-primary" onclick="openUploadPage()">
            <i class="bi bi-plus-lg"></i>
          </button> -->
        </div>

<script>
function openUploadPage() {
    window.open("upload.php", "_blank", "width=600,height=400");
}
</script>

  <div class="row py-5 px-lg-5 px-sm-0">
  <?php
      for ($column_item = 0; $column_item < 3; $column_item++) { ?>
        <div id="<?= 'ltg-c' . $column_item ?>" class="column">
          <?php for ($image_item = 0; $image_item < 3; $image_item++) { 
            $image_id_tag = 'ltg-c'. $column_item . '-i' . $image_item; 
            $image_class = 'flip-transition' . 
               ($image_item + $column_item == 0 ? ' h:scale-1' : '');
             ?>
            <div class="flip-container">
              <img id="<?= $image_id_tag ?>" src="<?= $uploadDir . $default_image ?>" 
                 class="<?= $image_class ?> img-fluid clickable-image"
                 data-id="<?= $image_id_tag ?>"
                 alt="" srcset=""/>
            </div>
          <?php
          }
        ?>
        </div>
      <?php
      }
      ?>
  </div>
</div>   
<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Metadata</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" class="img-fluid mb-2" alt="">
                <p id="modalMetadata"></p>
            </div>
        </div>
    </div>
</div>



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

function flipImage(imageId, newSrc) {
    let img = document.getElementById(imageId);
    img.classList.add("flip"); 

    setTimeout(() => {
        img.src = newSrc;
        img.classList.remove("flip");
    }, 800);
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// Resume the method when modal closes
document.getElementById('imageModal').addEventListener('hidden.bs.modal', () => {
    start_carousel();
});



let metadata = {};

// Event listener for image clicks
document.querySelectorAll('.clickable-image').forEach(image => {
    image.addEventListener('click', () => {
        stop_carousel();
        const imageId = image.getAttribute('data-id');
        const modalImage = document.getElementById('modalImage');
        const modalMetadata = document.getElementById('modalMetadata');
        const modelReference = document.getElementById('imageModalLabel');

        modalImage.src = image.src;
        modelReference.innerHTML = `<strong>${metadata[imageId].tailNumber} at ${metadata[imageId].location}</strong>`;
        modalMetadata.innerHTML = `<strong>${metadata[imageId].caption}</strong>`;

        // Show Bootstrap modal
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    });
});

function formatMetadata(input = {}) {
    return { "caption" : input.caption ?? "",
        "tailNumber" : input.tailNumber ?? "",
        "location": input.location ?? ""
     };
}
let ajax_data;
function refreshImages() {
    fetch('ajax_refresh.php')
        .then(response => response.json())
        .then(data => {
            ajax_data = Array.from(data);
        });
}

function refreshCarousel(grid_rows, grid_cols, replace_pct) {
    let images = shuffleArray(ajax_data);
    let new_images = Math.ceil(grid_rows * grid_cols * replace_pct);
    const image_selection = shuffleArray([...Array(images.length)].map((_, i) => i));
    console.log(`will pick ${new_images} images from array`); // Output: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]            let index = 0;
    console.log(`array selection index is ${image_selection}`); // Output: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]            let index = 0;
    for (let an_image=0; an_image < new_images; an_image++) {
        /* determine which row and col to replace in row-major decode order */
        let r = Math.floor(image_selection[an_image] / grid_rows);
        let c = image_selection[an_image] % grid_cols;
        let image_target = 'ltg-c' + c + '-i' + r;
        let image_path = images.length > 0 ? images[image_selection[an_image]].image : "<?=$uploadDir . $default_image?>";
        metadata[image_target] = formatMetadata(images[image_selection[an_image]]);
        flipImage(image_target, image_path);
    }
}
setInterval(() => refreshImages(), 30000);

let carousel_timer;
// Refresh every 30 seconds
function start_carousel() {
carousel_timer = setInterval(() => refreshCarousel(<?=$ltg_tile_images?>, 
    <?=$ltg_tile_columns?>,
    <?=$ltg_tile_replace_pct?>), 5000);
}

function stop_carousel() {
    clearInterval(carousel_timer);
}

window.onload = function() {
    refreshCarousel(<?=$ltg_tile_images?>,<?=$ltg_tile_columns?>,
        0.88
    );
    start_carousel();
 };

</script>
<!-- Include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
