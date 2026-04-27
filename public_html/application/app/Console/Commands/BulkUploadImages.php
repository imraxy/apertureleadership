<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BulkUploadImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:bulk-upload {--source=/home/u285921350/apertureleadership/wetransfer_new-aperture-photos_2026-04-08_1837/Small/} {--csv=/home/u285921350/apertureleadership/photo-category-mapping.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk upload images to the gallery without watermarks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sourceDir = $this->option('source');
        $csvFile = $this->option('csv');
        $uploadBase = public_path('uploads/albums/');

        $this->info("Source directory: $sourceDir");
        $this->info("CSV file: $csvFile");
        $this->info("Upload base: $uploadBase");
        $this->newLine();

        // Read CSV mapping
        $images = [];
        if (!file_exists($csvFile)) {
            $this->error("CSV file not found: $csvFile");
            return 1;
        }

        $handle = fopen($csvFile, 'r');
        $header = fgetcsv($handle);
        while (($row = fgetcsv($handle)) !== false) {
            $images[] = [
                'filename' => $row[1],
                'category_id' => $row[2],
            ];
        }
        fclose($handle);

        $this->info("Found " . count($images) . " images to upload");
        $this->newLine();

        $successCount = 0;
        $errorCount = 0;
        $skippedCount = 0;

        $bar = $this->output->createProgressBar(count($images));
        $bar->start();

        foreach ($images as $index => $imageData) {
            $filename = $imageData['filename'];
            $categoryId = (int)$imageData['category_id'];
            $sourcePath = $sourceDir . $filename;

            // Check if source file exists
            if (!file_exists($sourcePath)) {
                $errorCount++;
                $bar->advance();
                continue;
            }

            try {
                // Extract title from filename
                $title = pathinfo($filename, PATHINFO_FILENAME);
                $slug = Str::slug($title, '-');

                // Check if image already exists
                $existing = DB::table('session_images')->where('title', $title)->first();
                if ($existing) {
                    $skippedCount++;
                    $bar->advance();
                    continue;
                }

                // Get image info
                $imageInfo = getimagesize($sourcePath);
                if (!$imageInfo) {
                    $errorCount++;
                    $bar->advance();
                    continue;
                }
                $width = $imageInfo[0];
                $height = $imageInfo[1];

                // Determine shape
                $maxWidth = $height * 1.5;
                $shape = ($maxWidth <= $width) ? 'rectangle' : '';

                // Create database record
                $now = now();
                $recordId = DB::table('session_images')->insertGetId([
                    'album_category_id' => $categoryId,
                    'title' => $title,
                    'description' => null,
                    'slug' => $slug,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Create upload directory
                $uploadPath = $uploadBase . $recordId . '/';
                if (!File::isDirectory($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true, true);
                }

                // Generate unique filename
                $salt = bin2hex(openssl_random_pseudo_bytes(22));
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $newFilename = $salt . '.' . $extension;

                // Copy original image as-is (NO watermark)
                $mainPath = $uploadPath . $newFilename;
                File::copy($sourcePath, $mainPath);

                // Create small thumbnail
                $smallPath = $uploadPath . 'small_' . $newFilename;
                $srcImage = null;
                if ($extension == 'jpg' || $extension == 'jpeg') {
                    $srcImage = imagecreatefromjpeg($sourcePath);
                } elseif ($extension == 'png') {
                    $srcImage = imagecreatefrompng($sourcePath);
                }

                if ($srcImage) {
                    imagejpeg($srcImage, $smallPath, 30);
                    imagedestroy($srcImage);
                } else {
                    File::copy($sourcePath, $smallPath);
                }

                // Update database
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
                $this->error("Error processing $filename: " . $e->getMessage());
                $errorCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Upload complete!");
        $this->info("Success: $successCount");
        $this->info("Skipped: $skippedCount");
        $this->info("Errors: $errorCount");

        return 0;
    }
}
