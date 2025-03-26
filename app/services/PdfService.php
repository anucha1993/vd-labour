<?php

namespace App\Services;

use Imagick;
use Exception;

class PdfService
{
    public function createPdfThumbnail($pdfPath, $thumbnailPath)
    {
        try {
            $imagick = new Imagick();
            $imagick->setResolution(150, 150); // ตั้งค่าความละเอียดของ thumbnail
            $imagick->readImage($pdfPath . '[0]'); // อ่านเฉพาะหน้าแรก
            $imagick->setImageFormat('jpeg');
            $imagick->writeImage($thumbnailPath);
            $imagick->clear();
            $imagick->destroy();

            return true;
        } catch (Exception $e) {
            // จัดการข้อผิดพลาด
            return false;
        }
    }
}
