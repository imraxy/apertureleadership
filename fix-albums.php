<?php
$file = __DIR__ . '/resources/views/albums/albums.blade.php';
$content = file_get_contents($file);

// 1. Remove the PHP block that calculates distorted dimensions
$phpBlock = '/@php.*?\ = 334\.64.*?@endphp/s';
$content = preg_replace($phpBlock, '', $content);

// 2. Remove inline style from img tag
$content = str_replace('style="width:{{}}px; height:{{}}px;"', '', $content);

// 3. Add album-img class to img
$content = str_replace('class="album-img"', 'class="album-img"', $content); // already in my previous edit
$content = str_replace('<img src=', '<img src=', $content); // placeholder

// 4. Make folder icon bigger - add folder-icon class
$content = str_replace('folder-plus1.png"', 'folder-plus1.png" class="folder-icon"', $content);
$content = str_replace('Add to Folder<', 'Add to Folder<', $content);

// 5. Add CSS for proper image display
$css = '
<style>
.album-img {
    max-width: 100%;
    height: auto;
    object-fit: contain;
    width: auto !important;
    max-height: 300px;
}
.album-box {
    margin: 15px;
    text-align: center;
}
.img-title {
    font-size: 12px !important;
    margin: 5px 0;
}
.folder-icon {
    width: 24px !important;
    height: 24px !important;
    vertical-align: middle;
}
.plus-icon {
    font-size: 14px;
    cursor: pointer;
}
.album-gallery {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}
</style>
';

// Insert CSS after @push('css')
$content = str_replace('@push(\'css\')', '@push(\'css\')' . $css, $content);

file_put_contents($file, $content);
echo Fixed albums.blade.phpn;
