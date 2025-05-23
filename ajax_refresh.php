<?php
$uploadDir = 'images/';
$metaDir = 'metadata/';
$images = array_diff(scandir($uploadDir), ['.', '..']);
$data = [];

foreach ($images as $img) {
    $metaFile = $metaDir . pathinfo($img, PATHINFO_FILENAME) . '.meta';
    $meta = file_exists($metaFile) ? json_decode(file_get_contents($metaFile), true) : [];
    
    $data[] = [
        'image' => $uploadDir . $img,
        'tailNumber' => $meta['tailNumber'] ?? '',
        'location' => $meta['location'] ?? 'Unknown',
        'caption' => $meta['caption'] ?? '',
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>