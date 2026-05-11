<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ReplaceGalleryImages extends Command
{
    protected $signature = 'images:replace-all 
                            {--source=/home/u285921350/domains/apertureleadership.com/public_html/stg/source-images/} 
                            {--csv=/home/u285921350/domains/apertureleadership.com/public_html/stg/mapping.csv}';

    protected $description = 'Delete existing gallery images and re-upload without watermarks, with correct categories';

    public function handle()
    {
        $sourceDir = rtrim($this->option('source'), '/') . '/';
        $csvFile = $this->option('csv');
        $uploadBase = public_path('uploads/albums/');

        $this->info("Gallery Image Replacement (No Watermark)");
        $this->info("=========================================");

        if (!file_exists($csvFile)) {
            $this->error("CSV file not found: $csvFile");
            return 1;
        }

        $images = $this->readCsv($csvFile);
        $this->info("Found " . count($images) . " images in CSV");

        if (count($images) === 0) {
            $this->error("No images to process!");
            return 1;
        }

        $this->info("Step 1: Deleting existing images...");
        $deletedCount = $this->deleteExistingImages($images);
        $this->info("Deleted: $deletedCount");

        $this->info("Step 2: Re-uploading images (no watermark)...");
        $result = $this->uploadImages($images, $sourceDir);
        
        $this->info("=========================================");
        $this->info("Complete!");
        $this->info("Deleted: $deletedCount");
        $this->info("Uploaded: {$result['success']}");
        $this->info("Errors: {$result['errors']}");

        return 0;
    }

    private function readCsv($csvFile)
    {
        $images = [];
        $handle = fopen($csvFile, 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) >= 3) {
                $images[] = [
                    'filename' => $row[0],
                    'category_id' => (int)$row[1],
                    'title' => isset($row[2]) ? $row[2] : pathinfo($row[0], PATHINFO_FILENAME),
                ];
            }
        }

        fclose($handle);
        return $images;
    }

    private function deleteExistingImages($images)
    {
        $deletedCount = 0;

        foreach ($images as $imageData) {
            $title = $imageData['title'];
            $existing = DB::table('session_images')
                ->where('title', $title)
                ->first();

            if ($existing) {
                $folderPath = public_path('uploads/albums/' . $existing->id);
                if (File::exists($folderPath)) {
                    File::deleteDirectory($folderPath);
                }
                DB::table('session_images')->where('id', $existing->id)->delete();
                $deletedCount++;
            }
        }

        return $deletedCount;
    }

    private function uploadImages($images, $sourceDir)
    {
        $successCount = 0;
        $errorCount = 0;

        $bar = $this->output->createProgressBar(count($images));
        $bar->start();

        foreach ($images as $imageData) {
            $filename = $imageData['filename'];
            $categoryId = $imageData['category_id'];
            $title = $imageData['title'];
            $sourcePath = $sourceDir . $filename;

            if (!file_exists($sourcePath)) {
                $errorCount++;
                $bar->advance();
                continue;
            }

            try {
                $imageInfo = getimagesize($sourcePath);
                if (!$imageInfo) {
                    $errorCount++;
                    $bar->advance();
                    continue;
                }

                $width = $imageInfo[0];
                $height = $imageInfo[1];
                $maxWidth = $height * 1.5;
                $shape = ($maxWidth <= $width) ? 'rectangle' : '';

                $slug = Str::slug($title, '-');
                $recordId = DB::table('session_images')->insertGetId([
                    'album_category_id' => $categoryId,
                    'title' => $title,
                    'description' => null,
                    'slug' => $slug,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $uploadPath = public_path('uploads/albums/' . $recordId . '/');
                if (!File::isDirectory($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true, true);
                }

                $salt = bin2hex(openssl_random_pseudo_bytes(22));
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $newFilename = $salt . '.' . $extension;

                File::copy($sourcePath, $uploadPath . $newFilename);

                $smallPath = $uploadPath . 'small_' . $newFilename;
                $this->createThumbnail($sourcePath, $smallPath, $extension);

                DB::table('session_images')
                    ->where('id', $recordId)
                    ->update([
                        'session_image' => $newFilename,
                        'thumbnail_image' => $newFilename,
                        'small_image' => 'small_' . $newFilename,
                        'width' => $width,
                        'height' => $height,
                        'shape' => $shape,
                    ]);

                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        return ['success' => $successCount, 'errors' => $errorCount];
    }

    private function createThumbnail($sourcePath, $destPath, $extension)
    {
        $srcImage = null;

        if (in_array($extension, ['jpg', 'jpeg'])) {
            $srcImage = imagecreatefromjpeg($sourcePath);
        } elseif ($extension == 'png') {
            $srcImage = imagecreatefrompng($sourcePath);
        }

        if ($srcImage) {
            imagejpeg($srcImage, $destPath, 30);
            imagedestroy($srcImage);
        } else {
            File::copy($sourcePath, $destPath);
        }
    }
}
