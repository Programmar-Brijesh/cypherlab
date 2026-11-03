<?php

namespace App\Helpers;

use TCPDF;

class TcpdfFpdi extends TCPDF
{
    protected $fpdi = null;
    protected $sourceFile = null;
    protected $pageCount = 0;

    /**
     * Set source file and return page count
     */
    public function setSourceFile($file)
    {
        $this->sourceFile = $file;

        if (class_exists('\setasign\Fpdi\Fpdi')) {
            try {
                $this->fpdi = new \setasign\Fpdi\Fpdi();
                $this->pageCount = $this->fpdi->setSourceFile($file);
            } catch (\Exception $e) {
                $this->pageCount = $this->countPdfPages($file);
            }
        } else {
            $this->pageCount = $this->countPdfPages($file);
        }

        return $this->pageCount;
    }

    /**
     * Import page
     */
    public function importPage($pageNumber)
    {
        if ($pageNumber < 1 || $pageNumber > $this->pageCount) {
            throw new \Exception('Invalid page number: ' . $pageNumber);
        }
        return $pageNumber;
    }

    /**
     * Get template size
     */
    public function getTemplateSize($templateId)
    {
        // Try FPDI first
        if ($this->fpdi) {
            try {
                $size = $this->fpdi->getTemplateSize($templateId);
                if (is_array($size) && isset($size['width'])) {
                    return $size;
                }
            } catch (\Exception $e) {
                // Fall through
            }
        }

        // Try Imagick to get actual page size
        if ($this->sourceFile && extension_loaded('imagick')) {
            try {
                $im = new \Imagick();
                $im->readImage($this->sourceFile . '[' . ($templateId - 1) . ']');
                $w = $im->getImageWidth() * 0.352778;   // px to mm
                $h = $im->getImageHeight() * 0.352778;  // px to mm
                $im->clear();
                $im->destroy();

                return [
                    'width'       => round($w, 2),
                    'height'      => round($h, 2),
                    'orientation' => $w > $h ? 'L' : 'P',
                ];
            } catch (\Exception $e) {
                // Fall through
            }
        }

        // Default A4 size
        return [
            'width'       => 210,
            'height'      => 297,
            'orientation' => 'P',
        ];
    }

    /**
     * Use template (place page as background)
     */
    public function useTemplate($pageNumber, $x = 0, $y = 0, $width = 0, $height = 0)
    {
        if ($this->sourceFile) {
            $pageImage = $this->pdfPageToImage($this->sourceFile, $pageNumber);
            if ($pageImage && file_exists($pageImage) && filesize($pageImage) > 500) {
                $this->Image(
                    $pageImage,
                    $x,
                    $y,
                    $width ?: $this->getPageWidth(),
                    $height ?: $this->getPageHeight(),
                    '',
                    '',
                    '',
                    true,
                    300
                );
            } else {
                // Fallback: Try FPDI if available
                if ($this->fpdi) {
                    try {
                        $templateId = $this->fpdi->importPage($pageNumber);
                        $this->fpdi->useTemplate($templateId, $x, $y, $width, $height);
                    } catch (\Exception $e) {
                        // Silent fail
                    }
                }
            }
        }
    }

    /**
     * Convert PDF page to image
     */
    private function pdfPageToImage($pdfPath, $pageNumber)
    {
        $cacheDir = sys_get_temp_dir() . '/tcpdf_pgs_' . md5($pdfPath);
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }

        $imagePath = $cacheDir . '/p_' . $pageNumber . '.jpg';

        // Return cached if exists
        if (file_exists($imagePath) && filesize($imagePath) > 100) {
            return $imagePath;
        }

        // Method 1: Imagick
        if (extension_loaded('imagick')) {
            try {
                $im = new \Imagick();
                $im->setResolution(150, 150);
                $im->readImage($pdfPath . '[' . ($pageNumber - 1) . ']');
                $im->setImageFormat('jpg');
                $im->setImageCompressionQuality(90);
                $im->setImageBackgroundColor('white');
                $im->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
                $im->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
                $im->writeImage($imagePath);
                $im->clear();
                $im->destroy();

                if (file_exists($imagePath) && filesize($imagePath) > 100) {
                    return $imagePath;
                }
            } catch (\Exception $e) {
                // Continue to next method
            }
        }

        // Method 2: Ghostscript
        $gs = trim(shell_exec('command -v gs 2>/dev/null'));
        if (!empty($gs)) {
            $cmd = sprintf(
                'gs -dNOPAUSE -dBATCH -dQUIET -sDEVICE=jpeg -r150 -dJPEGQ=90 ' .
                    '-dFirstPage=%d -dLastPage=%d ' .
                    '-sOutputFile=%s %s 2>/dev/null',
                $pageNumber,
                $pageNumber,
                escapeshellarg($imagePath),
                escapeshellarg($pdfPath)
            );
            shell_exec($cmd);

            if (file_exists($imagePath) && filesize($imagePath) > 100) {
                return $imagePath;
            }
        }

        // Method 3: Spatie package
        if (class_exists('\Spatie\PdfToImage\Pdf')) {
            try {
                $pdf = new \Spatie\PdfToImage\Pdf($pdfPath);
                $pdf->setPage($pageNumber)
                    ->setResolution(150)
                    ->setOutputFormat('jpg')
                    ->saveImage($imagePath);

                if (file_exists($imagePath) && filesize($imagePath) > 100) {
                    return $imagePath;
                }
            } catch (\Exception $e) {
                // Continue
            }
        }

        return null;
    }

    /**
     * Count pages in PDF file
     */
    private function countPdfPages($file)
    {
        // Method 1: Try Imagick
        if (extension_loaded('imagick')) {
            try {
                $im = new \Imagick();
                $im->pingImage($file);
                $count = $im->getNumberImages();
                $im->clear();
                if ($count > 0) return $count;
            } catch (\Exception $e) {
                // Fall through
            }
        }

        // Method 2: Parse PDF manually
        $content = @file_get_contents($file);
        if ($content === false) return 1;

        // Count /Type /Page entries
        $matches = [];
        preg_match_all('/\/Type\s*\/Page[^s]/i', $content, $matches);
        $count = count($matches[0]);

        // If no matches, try alternative pattern
        if ($count === 0) {
            preg_match_all('/\/Type\/Page\b/i', $content, $matches);
            $count = count($matches[0]);
        }

        return $count > 0 ? $count : 1;
    }
}
