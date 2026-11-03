<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\IOFactory;
use Intervention\Image\ImageManager;
use Mpdf\Mpdf;
use setasign\Fpdi\Fpdi;

class PdfToolController extends Controller
{
    protected $tempPath = 'temp/pdfs/';

    public function landing()
    {
        return view('tools.pdf-tools.landing');
    }

    public function merge()
    {
        return view('tools.pdf-tools.merge');
    }

    // Upload multiple PDFs for merging
    public function mergeUpload(Request $request)
    {
        try {
            $request->validate([
                'files.*' => 'required|file|mimes:pdf|max:51200'
            ]);

            if (!Storage::exists($this->tempPath)) {
                Storage::makeDirectory($this->tempPath);
            }

            $uploaded = [];
            foreach ($request->file('files') as $file) {
                $tempId = (string) Str::uuid();
                $path = $file->storeAs($this->tempPath, $tempId . '.pdf');
                if (!$path || !Storage::exists($path)) {
                    throw new \Exception("Failed to store {$file->getClientOriginalName()}");
                }
                $uploaded[] = [
                    'id'   => $tempId,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }

            return response()->json(['success' => true, 'files' => $uploaded]);
        } catch (\Exception $e) {
            Log::error('PDF Merge Upload Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Process merge
    public function mergeProcess(Request $request)
    {
        try {
            $order = json_decode($request->input('order'), true);
            if (empty($order)) {
                return response()->json(['success' => false, 'error' => 'No files to merge'], 400);
            }

            $pdf = new Fpdi();
            $mergedFiles = 0;

            foreach ($order as $tempId) {
                $filePath = Storage::path($this->tempPath . $tempId . '.pdf');
                if (!file_exists($filePath)) {
                    continue;
                }
                try {
                    $pageCount = $pdf->setSourceFile($filePath);
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $tpl = $pdf->importPage($i);
                        $size = $pdf->getTemplateSize($tpl);
                        $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
                        $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                        $pdf->useTemplate($tpl);
                    }
                    $mergedFiles++;
                } catch (\Exception $e) {
                    Log::warning("Could not merge file {$tempId}: " . $e->getMessage());
                }
            }

            if ($mergedFiles === 0) {
                return response()->json(['success' => false, 'error' => 'No valid PDF files could be processed'], 400);
            }

            $outputFileName = 'merged_' . Str::uuid() . '.pdf';
            $outputPath = Storage::path($this->tempPath . $outputFileName);
            $outputDir = dirname($outputPath);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            $pdf->Output($outputPath, 'F');

            foreach ($order as $tempId) {
                Storage::delete($this->tempPath . $tempId . '.pdf');
            }

            return response()->download($outputPath, 'merged.pdf')->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('PDF Merge Process Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Merge failed: ' . $e->getMessage()], 500);
        }
    }

    // Show PDF Split page
    public function split()
    {
        return view('tools.pdf-tools.split');
    }

    // Upload single PDF and return page count
    public function splitUpload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:51200'
            ]);

            $file = $request->file('file');
            $tempId = (string) Str::uuid();
            $path = $file->storeAs($this->tempPath, $tempId . '.pdf');

            if (!$path || !Storage::exists($path)) {
                throw new \Exception("Failed to store file");
            }

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile(Storage::path($path));

            return response()->json([
                'success' => true,
                'id' => $tempId,
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'pages' => $pageCount
            ]);
        } catch (\Exception $e) {
            Log::error('PDF Split Upload Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Process split and return file list (no auto download)
    public function splitProcess(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string',
                'method' => 'required|in:range,extract,interval,every',
                'params' => 'required'
            ]);

            $tempId = $request->input('id');
            $method = $request->input('method');
            $params = json_decode($request->input('params'), true);
            if (!$params || !is_array($params)) {
                return response()->json(['success' => false, 'error' => 'Invalid params format'], 422);
            }

            $sourcePath = Storage::path($this->tempPath . $tempId . '.pdf');
            if (!file_exists($sourcePath)) {
                return response()->json(['success' => false, 'error' => 'Source file not found'], 400);
            }

            $pdf = new Fpdi();
            $totalPages = $pdf->setSourceFile($sourcePath);
            $pagesToExtract = [];

            switch ($method) {
                case 'range':
                    if (isset($params['ranges']) && is_array($params['ranges'])) {
                        foreach ($params['ranges'] as $range) {
                            $parts = explode('-', $range);
                            if (count($parts) == 2) {
                                $start = max(1, intval($parts[0]));
                                $end = min($totalPages, intval($parts[1]));
                                for ($i = $start; $i <= $end; $i++) $pagesToExtract[] = $i;
                            }
                        }
                    } elseif (isset($params['start']) && isset($params['end'])) {
                        $start = max(1, intval($params['start']));
                        $end = min($totalPages, intval($params['end']));
                        for ($i = $start; $i <= $end; $i++) $pagesToExtract[] = $i;
                    }
                    break;
                case 'extract':
                    $pages = $params['pages'] ?? [];
                    foreach ($pages as $p) {
                        $p = intval($p);
                        if ($p >= 1 && $p <= $totalPages) $pagesToExtract[] = $p;
                    }
                    break;
                case 'interval':
                    $interval = intval($params['interval'] ?? 1);
                    if ($interval < 1) $interval = 1;
                    for ($start = 1; $start <= $totalPages; $start += $interval) {
                        $end = min($start + $interval - 1, $totalPages);
                        for ($i = $start; $i <= $end; $i++) $pagesToExtract[] = $i;
                        $pagesToExtract[] = '---CHUNK---';
                    }
                    break;
                case 'every':
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $pagesToExtract[] = $i;
                        if ($i < $totalPages) $pagesToExtract[] = '---CHUNK---';
                    }
                    break;
            }

            if (end($pagesToExtract) === '---CHUNK---') array_pop($pagesToExtract);
            if (empty($pagesToExtract)) {
                return response()->json(['success' => false, 'error' => 'No valid pages to extract'], 400);
            }

            $chunks = [];
            $current = [];
            foreach ($pagesToExtract as $item) {
                if ($item === '---CHUNK---') {
                    if (!empty($current)) {
                        $chunks[] = $current;
                        $current = [];
                    }
                } else {
                    $current[] = $item;
                }
            }
            if (!empty($current)) $chunks[] = $current;

            $sessionId = (string) Str::uuid();
            $outputDir = storage_path('app/' . $this->tempPath . 'split_' . $sessionId);
            if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);

            $files = [];
            foreach ($chunks as $idx => $pageNumbers) {
                $newPdf = new Fpdi();
                foreach ($pageNumbers as $pageNum) {
                    $newPdf->setSourceFile($sourcePath);
                    $tpl = $newPdf->importPage($pageNum);
                    $size = $newPdf->getTemplateSize($tpl);
                    $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
                    $newPdf->AddPage($orientation, [$size['width'], $size['height']]);
                    $newPdf->useTemplate($tpl);
                }
                $defaultName = ($method === 'every') ? "page_{$pageNumbers[0]}.pdf" : "split_part_" . ($idx + 1) . ".pdf";
                $filePath = $outputDir . '/' . $defaultName;
                $newPdf->Output($filePath, 'F');
                $files[] = [
                    'originalName' => $defaultName,
                    'size' => filesize($filePath),
                    'path' => $filePath
                ];
            }

            session()->put("pdf_split_{$sessionId}", $files);
            Storage::delete($this->tempPath . $tempId . '.pdf');

            return response()->json([
                'success' => true,
                'sessionId' => $sessionId,
                'files' => array_map(function ($f) {
                    return ['name' => $f['originalName'], 'size' => $f['size']];
                }, $files)
            ]);
        } catch (\Exception $e) {
            Log::error('PDF Split Process Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Split failed: ' . $e->getMessage()], 500);
        }
    }

    // Download a single split file (with optional rename)
    public function splitDownloadSingle(Request $request)
    {
        $request->validate([
            'sessionId' => 'required|string',
            'filename' => 'required|string',
            'rename' => 'nullable|string'
        ]);

        $sessionId = $request->input('sessionId');
        $filename = $request->input('filename');
        $rename = $request->input('rename', $filename);
        if (!preg_match('/\.pdf$/i', $rename)) $rename .= '.pdf';

        $files = session()->get("pdf_split_{$sessionId}", []);
        $found = null;
        foreach ($files as $file) {
            if ($file['originalName'] === $filename) {
                $found = $file;
                break;
            }
        }
        if (!$found) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->download($found['path'], $rename)->deleteFileAfterSend(false);
    }

    // Download all split files as ZIP (cleans up the folder after download)
    public function splitDownloadZip(Request $request)
    {
        $request->validate([
            'sessionId' => 'required|string'
        ]);

        $sessionId = $request->input('sessionId');
        $files = session()->get("pdf_split_{$sessionId}", []);
        if (empty($files)) {
            return response()->json(['error' => 'No files found'], 404);
        }

        $outputDir = dirname($files[0]['path']);
        $zipPath = storage_path('app/' . $this->tempPath . 'split_zip_' . Str::uuid() . '.zip');
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== true) {
            return response()->json(['error' => 'Could not create ZIP'], 500);
        }
        foreach ($files as $file) {
            $zip->addFile($file['path'], $file['originalName']);
        }
        $zip->close();

        return response()->download($zipPath, 'split_files.zip')->deleteFileAfterSend(true);
    }

    /**
     * Show PDF Compressor page
     */
    public function compress()
    {
        return view('tools.pdf-tools.compress');
    }

    /**
     * Upload PDF and return file info
     */
    public function compressUpload(Request $request)
    {
        try {
            Log::info('PDF Compressor: Upload started');

            $request->validate([
                'file' => 'required|file|mimes:pdf|max:51200'
            ]);

            if (!Storage::exists($this->tempPath)) {
                Storage::makeDirectory($this->tempPath);
            }

            $file = $request->file('file');
            $tempId = (string) Str::uuid();
            $path = $file->storeAs($this->tempPath, $tempId . '.pdf');

            if (!$path || !Storage::exists($path)) {
                throw new \Exception("Failed to store file");
            }

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile(Storage::path($path));
            $gsAvailable = $this->isGhostscriptAvailable();

            Log::info('PDF Compressor: Upload successful', ['temp_id' => $tempId]);

            return response()->json([
                'success' => true,
                'id' => $tempId,
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'pages' => $pageCount,
                'gsAvailable' => $gsAvailable
            ]);
        } catch (\Exception $e) {
            Log::error('PDF Compressor: Upload failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }


    /**
     * Process compression using Ghostscript
     */
    public function compressProcess(Request $request)
    {
        try {
            $request->validate([
                'id'         => 'required|string',
                'method'     => 'required|in:preset,target',
                'preset'     => 'required_if:method,preset|in:low,medium,high',
                'quality'    => 'nullable|integer|min:10|max:100',
                'targetSize' => 'nullable|integer|min:10'
            ]);

            $tempId = $request->input('id');
            $method = $request->input('method');
            $sourcePath = Storage::path($this->tempPath . $tempId . '.pdf');

            if (!file_exists($sourcePath)) {
                return response()->json(['success' => false, 'error' => 'Source file not found'], 400);
            }

            $originalSize = filesize($sourcePath);
            $compressedPath = null;
            $compressedSize = null;

            if ($method === 'preset') {
                $preset = $request->input('preset');
                $customQuality = $request->input('quality');

                switch ($preset) {
                    case 'low':
                        $gsSetting = 'screen';
                        break;
                    case 'medium':
                        $gsSetting = 'ebook';
                        break;
                    case 'high':
                        $gsSetting = 'printer';
                        break;
                    default:
                        $gsSetting = 'ebook';
                }

                if ($customQuality) {
                    if ($customQuality <= 33) $gsSetting = 'screen';
                    elseif ($customQuality <= 66) $gsSetting = 'ebook';
                    else $gsSetting = 'printer';
                }

                $compressedPath = $this->compressWithGhostscript($sourcePath, $gsSetting);
                $compressedSize = filesize($compressedPath);
            } else { // target size method
                $targetKb = $request->input('targetSize');
                if ($targetKb >= $originalSize / 1024) {
                    return response()->json(['success' => false, 'error' => 'Target size must be smaller than original'], 400);
                }

                Log::info('PDF Compressor: Target size method started', ['target_kb' => $targetKb]);

                // Try to find best compression
                $best = $this->compressToTarget($sourcePath, $targetKb);
                $compressedPath = $best['path'];
                $compressedSize = $best['size'];

                if (!$compressedPath || !file_exists($compressedPath)) {
                    // Fallback to medium compression
                    Log::warning('Target size failed, falling back to medium preset');
                    $compressedPath = $this->compressWithGhostscript($sourcePath, 'ebook');
                    $compressedSize = filesize($compressedPath);
                }
            }

            if (!$compressedPath || !file_exists($compressedPath)) {
                throw new \Exception('Compression failed – no output file');
            }

            $savedPercent = round((1 - $compressedSize / $originalSize) * 100, 1);

            // Generate preview (optional)
            $previewUrl = null;
            if (extension_loaded('imagick')) {
                try {
                    $imagick = new \Imagick();
                    $imagick->readImage($compressedPath . '[0]');
                    $imagick->setImageFormat('png');
                    $imagick->resizeImage(300, 0, \Imagick::FILTER_LANCZOS, 1);
                    $previewPath = Storage::path($this->tempPath . 'preview_' . Str::uuid() . '.png');
                    $imagick->writeImage($previewPath);
                    $previewUrl = Storage::url($this->tempPath . basename($previewPath));
                    $imagick->clear();
                } catch (\Exception $e) {
                    Log::warning('Preview generation failed: ' . $e->getMessage());
                }
            }

            $sessionId = (string) Str::uuid();
            session()->put("pdf_compress_{$sessionId}", ['path' => $compressedPath]);

            // Clean up original uploaded file
            Storage::delete($this->tempPath . $tempId . '.pdf');

            return response()->json([
                'success'         => true,
                'sessionId'       => $sessionId,
                'originalSize'    => $originalSize,
                'compressedSize'  => $compressedSize,
                'savedPercent'    => $savedPercent,
                'previewUrl'      => $previewUrl
            ]);
        } catch (\Exception $e) {
            Log::error('PDF Compressor: Process failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'error' => 'Compression failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download compressed file with rename
     */
    public function compressDownload(Request $request)
    {
        $request->validate([
            'sessionId' => 'required|string',
            'rename' => 'nullable|string'
        ]);

        $sessionId = $request->input('sessionId');
        $rename = $request->input('rename', 'compressed.pdf');
        if (!preg_match('/\.pdf$/i', $rename)) $rename .= '.pdf';

        $data = session()->get("pdf_compress_{$sessionId}");
        if (!$data || !file_exists($data['path'])) {
            return response()->json(['error' => 'File not found'], 404);
        }

        session()->forget("pdf_compress_{$sessionId}");
        return response()->download($data['path'], $rename)->deleteFileAfterSend(true);
    }

    /**
     * Helper: Compress PDF using Ghostscript
     */
    private function compressWithGhostscript($inputPath, $setting)
    {
        $outputPath = Storage::path($this->tempPath . 'compressed_' . Str::uuid() . '.pdf');
        $cmd = sprintf(
            'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/%s -dNOPAUSE -dQUIET -dBATCH -sOutputFile=%s %s 2>&1',
            escapeshellarg($setting),
            escapeshellarg($outputPath),
            escapeshellarg($inputPath)
        );
        exec($cmd, $output, $returnCode);
        if ($returnCode !== 0 || !file_exists($outputPath) || filesize($outputPath) === 0) {
            throw new \Exception("Ghostscript compression failed: " . implode("\n", $output));
        }
        return $outputPath;
    }

    private function compressToTarget($inputPath, $targetKb)
    {
        $settings = ['screen', 'ebook', 'printer', 'prepress']; // smallest to largest
        $bestPath = null;
        $bestSize = PHP_INT_MAX;
        $bestDiff = PHP_INT_MAX;

        foreach ($settings as $setting) {
            try {
                $testPath = $this->compressWithGhostscript($inputPath, $setting);
                $testSizeKb = filesize($testPath) / 1024;
                $diff = abs($testSizeKb - $targetKb);

                Log::debug("Trying setting {$setting}: size {$testSizeKb} KB, diff {$diff}");

                if ($diff < $bestDiff) {
                    // New best found – delete previous best if exists
                    if ($bestPath && file_exists($bestPath)) {
                        unlink($bestPath);
                    }
                    $bestPath = $testPath;
                    $bestSize = filesize($testPath);
                    $bestDiff = $diff;
                } else {
                    // Not better – delete this test file
                    if (file_exists($testPath)) {
                        unlink($testPath);
                    }
                }

                // If we reached a size <= target, we can stop because lower settings produce smaller files
                if ($testSizeKb <= $targetKb) {
                    Log::info("Target reached with setting {$setting}: {$testSizeKb} KB <= {$targetKb} KB");
                    break;
                }
            } catch (\Exception $e) {
                Log::warning("Compression with setting {$setting} failed: " . $e->getMessage());
                continue;
            }
        }

        if (!$bestPath) {
            throw new \Exception('Could not compress to target size – no valid setting');
        }

        $finalSizeKb = $bestSize / 1024;
        Log::info("Best compression for target {$targetKb} KB: size {$finalSizeKb} KB");

        return ['path' => $bestPath, 'size' => $bestSize];
    }

    /**
     * Helper: Check Ghostscript availability
     */
    private function isGhostscriptAvailable()
    {
        $output = shell_exec('which gs 2>/dev/null || which gswin64c 2>/dev/null || echo ""');
        return !empty(trim($output));
    }


    public function pdfToWord()
    {
        return view('tools.pdf-tools.pdfToWord');
    }

    public function pdfToWordUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf|max:20480', // 20 MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error'   => $validator->errors()->first('file')
            ], 422);
        }

        $file     = $request->file('file');
        $id       = Str::uuid()->toString();
        $tempPath = "temp/pdfs/{$id}";
        $fileName = $file->getClientOriginalName();
        $file->storeAs($tempPath, $fileName, 'local'); // storage/app/temp/pdfs/{id}/...

        // Count pages (optional, if pdfinfo installed)
        $pages = 1;
        $fullPath = Storage::disk('local')->path("{$tempPath}/{$fileName}");
        if ($this->commandExists('pdfinfo')) {
            $output = shell_exec("pdfinfo " . escapeshellarg($fullPath) . " 2>/dev/null");
            if ($output && preg_match('/Pages:\s+(\d+)/i', $output, $m)) {
                $pages = (int) $m[1];
            }
        }

        session()->put("pdf_toword_{$id}", [
            'id'           => $id,
            'originalName' => $fileName,
            'storagePath'  => "{$tempPath}/{$fileName}",
            'size'         => $file->getSize(),
            'pages'        => $pages,
        ]);

        return response()->json([
            'success' => true,
            'id'      => $id,
            'name'    => $fileName,
            'size'    => $file->getSize(),
            'pages'   => $pages,
        ]);
    }

    /**
     * Convert uploaded PDF to DOCX
     */
    /**
     * Convert uploaded PDF to DOCX (ZipArchive method – guaranteed working)
     */
    public function pdfToWordProcess(Request $request)
    {
        $request->validate(['id' => 'required|string']);
        $id   = $request->input('id');
        $meta = session("pdf_toword_{$id}");

        if (!$meta || !Storage::disk('local')->exists($meta['storagePath'])) {
            return response()->json(['success' => false, 'error' => 'File not found.'], 404);
        }

        $inputPath = Storage::disk('local')->path($meta['storagePath']);
        $outDir    = Storage::disk('local')->path("temp/converted/{$id}");
        File::ensureDirectoryExists($outDir);

        try {
            // 1. Extract text from PDF
            $parser = new \Smalot\PdfParser\Parser();
            $pdf    = $parser->parseFile($inputPath);
            $pages  = $pdf->getPages();

            // 2. Create document.xml content
            $xmlParagraphs = '';
            foreach ($pages as $pageNum => $page) {
                $text = trim($page->getText());
                if (empty($text)) continue;

                $lines = explode("\n", $text);
                foreach ($lines as $line) {
                    $line = htmlspecialchars(trim($line), ENT_XML1, 'UTF-8');
                    if (!empty($line)) {
                        $xmlParagraphs .= <<<XML
    <w:p>
        <w:r>
            <w:rPr>
                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                <w:sz w:val="22"/>
            </w:rPr>
            <w:t xml:space="preserve">{$line}</w:t>
        </w:r>
    </w:p>

XML;
                    }
                }
                // Page break after each page
                if ($pageNum < count($pages) - 1) {
                    $xmlParagraphs .= '<w:p><w:r><w:br w:type="page"/></w:r></w:p>' . "\n";
                }
            }

            // If no text found
            if (empty($xmlParagraphs)) {
                $xmlParagraphs = <<<XML
    <w:p>
        <w:r>
            <w:rPr><w:b/><w:color w:val="FF0000"/></w:rPr>
            <w:t>No extractable text found. This PDF may be scanned or image-based.</w:t>
        </w:r>
    </w:p>
XML;
            }

            // 3. XML files for DOCX structure
            $documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
    <w:body>
' . $xmlParagraphs . '
    </w:body>
</w:document>';

            $contentTypesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
</Types>';

            $relsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>';

            // 4. Create DOCX (ZIP)
            $baseName   = pathinfo($meta['originalName'], PATHINFO_FILENAME);
            $outputFile = "{$outDir}/{$baseName}.docx";

            $zip = new \ZipArchive();
            if ($zip->open($outputFile, \ZipArchive::CREATE) !== true) {
                throw new \Exception('Cannot create ZIP file');
            }

            $zip->addFromString('[Content_Types].xml', $contentTypesXml);
            $zip->addFromString('_rels/.rels', $relsXml);
            $zip->addFromString('word/document.xml', $documentXml);
            $zip->close();

            // Verify
            if (!file_exists($outputFile) || filesize($outputFile) < 100) {
                throw new \Exception('DOCX file creation failed');
            }
        } catch (\Exception $e) {
            \Log::error('PDF to Word: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }

        $wordSize = filesize($outputFile);
        $meta['convertedPath'] = "temp/converted/{$id}/" . basename($outputFile);
        $meta['wordSize']      = $wordSize;
        session(["pdf_toword_{$id}" => $meta]);

        return response()->json([
            'success'      => true,
            'sessionId'    => $id,
            'originalSize' => $meta['size'],
            'wordSize'     => $wordSize,
        ]);
    }

    /**
     * Download the generated DOCX
     */
    public function pdfToWordDownload(Request $request)
    {
        $request->validate([
            'sessionId' => 'required|string',
            'rename'    => 'nullable|string',
        ]);

        $id   = $request->input('sessionId');
        $meta = session("pdf_toword_{$id}");

        if (!$meta || empty($meta['convertedPath']) || !Storage::disk('local')->exists($meta['convertedPath'])) {
            abort(404, 'File not found.');
        }

        $downloadName = $request->input('rename', 'converted.docx');
        if (!Str::endsWith(strtolower($downloadName), '.docx')) {
            $downloadName .= '.docx';
        }

        $fullPath = Storage::disk('local')->path($meta['convertedPath']);

        return response()->download($fullPath, $downloadName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    private function commandExists($cmd)
    {
        $check  = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'where' : 'command -v';
        $result = @shell_exec("{$check} {$cmd} 2>/dev/null");

        if ($result === null || $result === false) {
            return false;
        }

        return !empty(trim((string)$result));
    }

    public function wordToPdf()
    {
        return view('tools.pdf-tools.wordToPdf');
    }

    public function wordToPdfUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:doc,docx|max:20480', // 20MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error'   => $validator->errors()->first('file')
            ], 422);
        }

        $file     = $request->file('file');
        $id       = Str::uuid()->toString();
        $tempPath = "temp/word/{$id}";
        $fileName = $file->getClientOriginalName();
        $file->storeAs($tempPath, $fileName, 'local');

        session()->put("word_topdf_{$id}", [
            'id'           => $id,
            'originalName' => $fileName,
            'storagePath'  => "{$tempPath}/{$fileName}",
            'size'         => $file->getSize(),
        ]);

        return response()->json([
            'success' => true,
            'id'      => $id,
            'name'    => $fileName,
            'size'    => $file->getSize(),
        ]);
    }


    public function wordToPdfProcess(Request $request)
    {
        $request->validate(['id' => 'required|string']);
        $id   = $request->input('id');
        $meta = session("word_topdf_{$id}");

        if (!$meta || !Storage::disk('local')->exists($meta['storagePath'])) {
            return response()->json(['success' => false, 'error' => 'File not found.'], 404);
        }

        $inputPath = Storage::disk('local')->path($meta['storagePath']);
        $outDir    = Storage::disk('local')->path("temp/converted/{$id}");
        File::ensureDirectoryExists($outDir);
        $baseName   = pathinfo($meta['originalName'], PATHINFO_FILENAME);
        $outputFile = "{$outDir}/{$baseName}.pdf";

        try {
            // 1. Read DOCX content using PhpWord
            $phpWord = IOFactory::load($inputPath);
            $content = '';

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $content .= $element->getText() . "\n";
                    } elseif (method_exists($element, 'getElements')) {
                        // Handle text runs inside paragraphs
                        foreach ($element->getElements() as $run) {
                            if (method_exists($run, 'getText')) {
                                $content .= $run->getText();
                            }
                        }
                        $content .= "\n";
                    }
                }
                $content .= "\n--- Page Break ---\n\n";
            }

            if (empty(trim($content))) {
                $content = "No text content found in this document.\nThe file may contain only images or unsupported elements.";
            }

            // 2. Create PDF using TCPDF
            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // Set document info
            $pdf->SetCreator('CypherLabs');
            $pdf->SetAuthor('CypherLabs');
            $pdf->SetTitle($baseName);

            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Set margins
            $pdf->SetMargins(15, 15, 15);
            $pdf->SetAutoPageBreak(true, 15);

            // Add a page
            $pdf->AddPage();

            // Set font
            $pdf->SetFont('helvetica', '', 11);

            // Write content
            $lines = explode("\n", $content);
            foreach ($lines as $line) {
                if (trim($line) === '--- Page Break ---') {
                    $pdf->AddPage();
                    continue;
                }
                $pdf->Write(0, $line, '', 0, 'L', true);
                $pdf->Ln(4);
            }

            // Save PDF
            $pdf->Output($outputFile, 'F'); // 'F' = save to file

            if (!file_exists($outputFile) || filesize($outputFile) < 100) {
                throw new \Exception('PDF file creation failed');
            }
        } catch (\Exception $e) {
            \Log::error('Word to PDF error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'Conversion failed: ' . $e->getMessage()
            ], 500);
        }

        $pdfSize = filesize($outputFile);
        $meta['convertedPath'] = "temp/converted/{$id}/" . basename($outputFile);
        $meta['pdfSize']       = $pdfSize;
        session(["word_topdf_{$id}" => $meta]);

        return response()->json([
            'success'      => true,
            'sessionId'    => $id,
            'originalSize' => $meta['size'],
            'pdfSize'      => $pdfSize,
        ]);
    }

    /**
     * Download the generated PDF
     */
    public function wordToPdfDownload(Request $request)
    {
        $request->validate([
            'sessionId' => 'required|string',
            'rename'    => 'nullable|string',
        ]);

        $id   = $request->input('sessionId');
        $meta = session("word_topdf_{$id}");

        if (!$meta || empty($meta['convertedPath']) || !Storage::disk('local')->exists($meta['convertedPath'])) {
            abort(404, 'File not found.');
        }

        $downloadName = $request->input('rename', 'converted.pdf');
        if (!Str::endsWith(strtolower($downloadName), '.pdf')) {
            $downloadName .= '.pdf';
        }

        $fullPath = Storage::disk('local')->path($meta['convertedPath']);

        return response()->download($fullPath, $downloadName, [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }

    public function imageToPdf()
    {
        return view('tools.pdf-tools.imageToPdf');
    }

    public function imageToPdfUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'    => 'required|file|mimes:jpg,jpeg,png,webp|max:20480',
            'rotation' => 'nullable|integer|in:0,90,180,270',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error'   => $validator->errors()->first()
            ], 422);
        }

        $file     = $request->file('image');
        $rotation = $request->input('rotation', 0);
        $id       = Str::uuid()->toString();
        $tempPath = "temp/images/{$id}";

        // Store original
        $fileName = $file->getClientOriginalName();
        $file->storeAs($tempPath, $fileName, 'local');

        // If rotation needed, rotate and save
        $fullPath = Storage::disk('local')->path("{$tempPath}/{$fileName}");
        if ($rotation > 0) {
            $img = ImageManager::gd()->read($fullPath);
            $img->rotate($rotation);
            $img->save($fullPath);
        }

        session()->put("image_topdf_{$id}", [
            'id'           => $id,
            'originalName' => $fileName,
            'storagePath'  => "{$tempPath}/{$fileName}",
            'size'         => $file->getSize(),
            'rotation'     => $rotation,
        ]);

        return response()->json([
            'success'  => true,
            'id'       => $id,
            'name'     => $fileName,
            'rotation' => $rotation,
        ]);
    }

    /**
     * Process all images into single PDF
     */
    public function imageToPdfProcess(Request $request)
    {
        $request->validate([
            'images'      => 'required|array|min:1|max:25',
            'images.*.id' => 'required|string',
            'pageSize'    => 'required|in:A4,LETTER,LEGAL,FIT',
            'orientation' => 'required|in:P,L,auto',
        ]);

        $images      = $request->input('images');
        $pageSize    = $request->input('pageSize');
        $orientation = $request->input('orientation');
        $sessionId   = Str::uuid()->toString();
        $outDir      = Storage::disk('local')->path("temp/converted/{$sessionId}");
        File::ensureDirectoryExists($outDir);
        $outputFile = "{$outDir}/output.pdf";

        // Page size definitions (mm)
        $pageSizes = [
            'A4'     => [210, 297],
            'LETTER' => [216, 279],
            'LEGAL'  => [216, 356],
        ];

        try {
            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator('CypherLabs');
            $pdf->SetAuthor('CypherLabs');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(0, 0, 0);
            $pdf->SetAutoPageBreak(false, 0);

            $pageCount = 0;

            foreach ($images as $imgData) {
                $id   = $imgData['id'];
                $meta = session("image_topdf_{$id}");

                if (!$meta || !Storage::disk('local')->exists($meta['storagePath'])) {
                    continue;
                }

                $imgPath = Storage::disk('local')->path($meta['storagePath']);

                // Get image dimensions
                $imageInfo = getimagesize($imgPath);
                if (!$imageInfo) continue;

                $imgWidth  = $imageInfo[0];
                $imgHeight = $imageInfo[1];
                $imgRatio  = $imgWidth / $imgHeight;

                // Determine page dimensions
                if ($pageSize === 'FIT') {
                    // Create custom page size matching image
                    $pxToMm = 0.264583; // 1px = ~0.264583mm at 96dpi
                    $pageW = $imgWidth * $pxToMm;
                    $pageH = $imgHeight * $pxToMm;
                } else {
                    list($pageW, $pageH) = $pageSizes[$pageSize];
                }

                // Handle orientation
                if ($orientation === 'L' || ($orientation === 'auto' && $imgWidth > $imgHeight)) {
                    // Landscape
                    $tmp = $pageW;
                    $pageW = $pageH;
                    $pageH = $tmp;
                }

                // Add page with calculated size
                $pdf->AddPage(
                    ($imgWidth > $imgHeight) ? 'L' : 'P',
                    $pageSize === 'FIT' ? [$pageW, $pageH] : $pageSize
                );

                // Calculate image placement (fit to page, maintain ratio)
                $pageRatio = $pageW / $pageH;
                if ($imgRatio > $pageRatio) {
                    // Image is wider - fit by width
                    $drawW = $pageW;
                    $drawH = $pageW / $imgRatio;
                    $x = 0;
                    $y = ($pageH - $drawH) / 2;
                } else {
                    // Image is taller - fit by height
                    $drawH = $pageH;
                    $drawW = $pageH * $imgRatio;
                    $x = ($pageW - $drawW) / 2;
                    $y = 0;
                }

                $pdf->Image($imgPath, $x, $y, $drawW, $drawH, '', '', '', true, 300);
                $pageCount++;
            }

            if ($pageCount === 0) {
                throw new \Exception('No valid images to convert');
            }

            $pdf->Output($outputFile, 'F');

            if (!file_exists($outputFile)) {
                throw new \Exception('PDF creation failed');
            }
        } catch (\Exception $e) {
            \Log::error('Image to PDF error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }

        $pdfSize = filesize($outputFile);

        session()->put("image_topdf_session_{$sessionId}", [
            'convertedPath' => "temp/converted/{$sessionId}/output.pdf",
            'imageCount'    => count($images),
            'pageCount'     => $pageCount,
            'pdfSize'       => $pdfSize,
        ]);

        // Cleanup individual image sessions
        foreach ($images as $imgData) {
            session()->forget("image_topdf_{$imgData['id']}");
        }

        return response()->json([
            'success'    => true,
            'sessionId'  => $sessionId,
            'imageCount' => count($images),
            'pageCount'  => $pageCount,
            'pdfSize'    => $pdfSize,
        ]);
    }

    /**
     * Download the generated PDF
     */
    public function imageToPdfDownload(Request $request)
    {
        $request->validate([
            'sessionId' => 'required|string',
            'rename'    => 'nullable|string',
        ]);

        $sessionId = $request->input('sessionId');
        $meta      = session("image_topdf_session_{$sessionId}");

        if (!$meta || empty($meta['convertedPath']) || !Storage::disk('local')->exists($meta['convertedPath'])) {
            abort(404, 'File not found.');
        }

        $downloadName = $request->input('rename', 'images.pdf');
        if (!Str::endsWith(strtolower($downloadName), '.pdf')) {
            $downloadName .= '.pdf';
        }

        $fullPath = Storage::disk('local')->path($meta['convertedPath']);

        return response()->download($fullPath, $downloadName, [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }

    public function ocr()
    {
        return view('tools.pdf-tools.ocr');
    }

    /**
     * Upload file for OCR
     */
    public function ocrUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error'   => $validator->errors()->first('file')
            ], 422);
        }

        $file     = $request->file('file');
        $id       = Str::uuid()->toString();
        $tempPath = "temp/ocr/{$id}";
        $fileName = $file->getClientOriginalName();
        $file->storeAs($tempPath, $fileName, 'local');

        // Count pages (only for PDF)
        $pages = 1;
        $fullPath = Storage::disk('local')->path("{$tempPath}/{$fileName}");

        if ($file->getClientOriginalExtension() === 'pdf' && $this->commandExists('pdfinfo')) {
            $output = shell_exec("pdfinfo " . escapeshellarg($fullPath) . " 2>/dev/null");
            if ($output && preg_match('/Pages:\s+(\d+)/i', $output, $m)) {
                $pages = (int) $m[1];
            }
        }

        session()->put("ocr_{$id}", [
            'id'           => $id,
            'originalName' => $fileName,
            'storagePath'  => "{$tempPath}/{$fileName}",
            'size'         => $file->getSize(),
            'pages'        => $pages,
            'type'         => $file->getClientOriginalExtension(),
        ]);

        return response()->json([
            'success' => true,
            'id'      => $id,
            'name'    => $fileName,
            'size'    => $file->getSize(),
            'pages'   => $pages,
        ]);
    }

    public function ocrProcess(Request $request)
    {
        $request->validate([
            'id'       => 'required|string',
            'language' => 'required|string',
        ]);

        $id   = $request->input('id');
        $lang = $request->input('language');
        $meta = session("ocr_{$id}");

        if (!$meta || !Storage::disk('local')->exists($meta['storagePath'])) {
            return response()->json(['success' => false, 'error' => 'File not found.'], 404);
        }

        $filePath = Storage::disk('local')->path($meta['storagePath']);
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        try {
            // 1. If PDF, try native PHP text parser first for 98%+ accuracy without external dependencies
            if ($ext === 'pdf') {
                try {
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf    = $parser->parseFile($filePath);
                    $text   = trim($pdf->getText());

                    if (strlen($text) > 20) {
                        // Successfully extracted native text from PDF
                        Storage::disk('local')->deleteDirectory("temp/ocr/{$id}");
                        session()->forget("ocr_{$id}");

                        return response()->json([
                            'success'    => true,
                            'text'       => $text,
                            'confidence' => 98,
                            'method'     => 'native_pdf_parser'
                        ]);
                    }
                } catch (\Exception $ex) {
                    Log::warning('Native PDF parsing failed, falling back to OCR API: ' . $ex->getMessage());
                }
            }

            // 2. Fallback to OCR engine for scanned PDFs / Images
            $result = $this->ocrImage($filePath, $lang);

            if (!$result['success']) {
                throw new \Exception($result['error']);
            }

            // Clean up
            Storage::disk('local')->deleteDirectory("temp/ocr/{$id}");
            session()->forget("ocr_{$id}");

            return response()->json([
                'success'    => true,
                'text'       => $result['text'],
                'confidence' => $result['confidence'],
                'method'     => 'ocr_engine'
            ]);
        } catch (\Exception $e) {
            Storage::disk('local')->deleteDirectory("temp/ocr/{$id}");
            session()->forget("ocr_{$id}");

            return response()->json([
                'success' => false,
                'error'   => 'OCR failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function ocrImage($imagePath, $language)
    {
        // APNI API KEY YAHAN DALO
        $apiKey = 'K83850140688957'; // <-- APNI KEY DALO

        if (!file_exists($imagePath)) {
            return ['success' => false, 'error' => 'File not found'];
        }

        // Compress if > 900KB
        $tempFile = $imagePath;
        if (filesize($imagePath) > 900 * 1024) {
            $tempFile = sys_get_temp_dir() . '/ocr_' . uniqid() . '.jpg';
            $this->compressImageForOcr($imagePath, $tempFile);
        }

        // Base64 method - all values as STRING
        $imgData = base64_encode(file_get_contents($tempFile));

        $postData = http_build_query([
            'apikey'            => $apiKey,
            'language'          => $language,
            'base64Image'       => 'data:image/jpeg;base64,' . $imgData,
            'isOverlayRequired' => 'false',
            'isTable'           => 'false',
            'OCREngine'         => '1',
            'scale'             => 'true',
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'https://api.ocr.space/parse/image',
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        // Cleanup temp
        if ($tempFile !== $imagePath && file_exists($tempFile)) {
            unlink($tempFile);
        }

        if ($error) {
            return ['success' => false, 'error' => $error];
        }

        $result = json_decode($response, true);

        if (empty($result)) {
            return ['success' => false, 'error' => 'Empty response'];
        }

        if (!empty($result['ErrorMessage'])) {
            $msg = is_array($result['ErrorMessage'])
                ? implode(', ', $result['ErrorMessage'])
                : $result['ErrorMessage'];
            return ['success' => false, 'error' => $msg];
        }

        if (empty($result['ParsedResults'])) {
            return ['success' => false, 'error' => 'No results'];
        }

        $text = $result['ParsedResults'][0]['ParsedText'] ?? '';
        $conf = (int)($result['ParsedResults'][0]['FileParseExitCode'] ?? 0);

        return [
            'success'    => true,
            'text'       => trim($text) ?: '(No text detected)',
            'confidence' => $conf,
        ];
    }

    private function compressImageForOcr($sourcePath, $destPath)
    {
        if (!extension_loaded('gd')) {
            copy($sourcePath, $destPath);
            return;
        }

        $info = @getimagesize($sourcePath);
        if (!$info) {
            copy($sourcePath, $destPath);
            return;
        }

        $src = match ($info['mime']) {
            'image/jpeg' => imagecreatefromjpeg($sourcePath),
            'image/png'  => imagecreatefrompng($sourcePath),
            'image/webp' => imagecreatefromwebp($sourcePath),
            default      => null,
        };

        if (!$src) {
            copy($sourcePath, $destPath);
            return;
        }

        $newW = min($info[0], 1500);
        $newH = min($info[1], 1500);

        $dst = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $info[0], $info[1]);

        $q = 60;
        imagejpeg($dst, $destPath, $q);

        while (filesize($destPath) > 800 * 1024 && $q > 20) {
            $q -= 10;
            imagejpeg($dst, $destPath, $q);
        }

        imagedestroy($src);
        imagedestroy($dst);
    }

    public function watermark()
    {
        return view('tools.pdf-tools.watermark');
    }


    public function watermarkUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()->first('file')], 422);
        }

        $file     = $request->file('file');
        $id       = Str::uuid()->toString();
        $tempPath = "temp/watermark/{$id}";
        $fileName = $file->getClientOriginalName();
        $file->storeAs($tempPath, $fileName, 'local');

        $fullPath = Storage::disk('local')->path("{$tempPath}/{$fileName}");
        $pages = 1;

        try {
            $mpdf = new Mpdf();
            $pages = $mpdf->SetSourceFile($fullPath);
        } catch (\Exception $e) {
            $pages = 1;
        }

        session()->put("watermark_{$id}", [
            'id'           => $id,
            'originalName' => $fileName,
            'storagePath'  => "{$tempPath}/{$fileName}",
            'size'         => $file->getSize(),
            'pages'        => $pages,
        ]);

        return response()->json([
            'success' => true,
            'id'      => $id,
            'name'    => $fileName,
            'size'    => $file->getSize(),
            'pages'   => $pages,
        ]);
    }

    public function watermarkProcess(Request $request)
    {
        $settings = json_decode($request->input('settings'), true);
        $id       = $settings['id'];
        $meta     = session("watermark_{$id}");

        if (!$meta || !Storage::disk('local')->exists($meta['storagePath'])) {
            return response()->json(['success' => false, 'error' => 'File not found.'], 404);
        }

        $sessionId  = Str::uuid()->toString();
        $inputPath  = Storage::disk('local')->path($meta['storagePath']);
        $outDir     = Storage::disk('local')->path("temp/watermarked/{$sessionId}");
        File::ensureDirectoryExists($outDir);
        $outputPath = "{$outDir}/watermarked.pdf";

        try {
            $pages = $this->parsePageRange($settings['pageRange'] ?? 'all', $meta['pages']);
            $this->applyWatermarkMpdf($inputPath, $outputPath, $settings, $request, $pages);

            $watermarkedSize = filesize($outputPath);

            session()->put("watermarked_{$sessionId}", [
                'convertedPath'   => "temp/watermarked/{$sessionId}/watermarked.pdf",
                'originalSize'    => $meta['size'],
                'watermarkedSize' => $watermarkedSize,
            ]);

            Storage::disk('local')->deleteDirectory("temp/watermark/{$id}");
            session()->forget("watermark_{$id}");

            return response()->json([
                'success'         => true,
                'sessionId'       => $sessionId,
                'originalSize'    => $meta['size'],
                'watermarkedSize' => $watermarkedSize,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function watermarkDownload(Request $request)
    {
        $request->validate([
            'sessionId' => 'required|string',
            'rename'    => 'nullable|string',
        ]);

        $sessionId = $request->input('sessionId');
        $meta      = session("watermarked_{$sessionId}");

        if (!$meta || empty($meta['convertedPath']) || !Storage::disk('local')->exists($meta['convertedPath'])) {
            abort(404, 'File not found.');
        }

        $downloadName = $request->input('rename', 'watermarked.pdf');
        if (!Str::endsWith(strtolower($downloadName), '.pdf')) {
            $downloadName .= '.pdf';
        }

        $fullPath = Storage::disk('local')->path($meta['convertedPath']);

        return response()->download($fullPath, $downloadName, [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }

    private function applyWatermarkMpdf($inputPath, $outputPath, $settings, $request, $pages)
    {
        $watermarkImagePath = null;

        // Save watermark image if uploaded
        if ($settings['type'] === 'image' && $request->hasFile('watermark_image')) {
            $imgFile = $request->file('watermark_image');
            $imgDir  = dirname($outputPath) . '/watermark_img';
            File::ensureDirectoryExists($imgDir);
            $watermarkImagePath = $imgDir . '/wm.' . $imgFile->getClientOriginalExtension();
            $imgFile->move($imgDir, 'wm.' . $imgFile->getClientOriginalExtension());
        }

        // Create mPDF instance
        $mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_left'   => 0,
            'margin_right'  => 0,
            'margin_top'    => 0,
            'margin_bottom' => 0,
        ]);

        // Import original PDF
        $pageCount = $mpdf->SetSourceFile($inputPath);

        for ($i = 1; $i <= $pageCount; $i++) {
            if (!in_array($i, $pages)) continue;

            // Import page
            $tplId = $mpdf->ImportPage($i);
            $size  = $mpdf->GetTemplateSize($tplId);

            // ✅ FIX: mPDF AddPage syntax
            $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';

            // Custom size ke liye format array
            $format = [$size['width'], $size['height']];

            $mpdf->AddPageByArray([
                'orientation' => $orientation,
                'sheet-size'  => $format,
                'margin-left' => 0,
                'margin-right' => 0,
                'margin-top'  => 0,
                'margin-bottom' => 0,
            ]);

            // Place original page
            $mpdf->UseTemplate($tplId, 0, 0, $size['width'], $size['height']);

            // Apply watermark
            $opacity = ($settings['opacity'] ?? 30) / 100;

            if ($settings['type'] === 'text') {
                $text     = $settings['text'] ?? 'WATERMARK';
                $fontSize = $settings['fontSize'] ?? 60;
                $color    = $this->hexToRgb($settings['color'] ?? '#ff0000');

                // Write HTML watermark
                $watermarkHtml = $this->generateWatermarkHtml(
                    $text,
                    $fontSize,
                    $color,
                    $opacity,
                    $settings['rotation'] ?? 45,
                    $settings['position'] ?? 'center',
                    $size
                );

                $mpdf->WriteHTML($watermarkHtml);
            }
        }

        $mpdf->Output($outputPath, 'F');
    }

    private function generateWatermarkHtml($text, $fontSize, $color, $opacity, $rotation, $position, $pageSize)
    {
        $r = $color['r'];
        $g = $color['g'];
        $b = $color['b'];

        $styles = [
            'tile' => "
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            opacity: {$opacity};
            z-index: 9999;
            pointer-events: none;
        ",
            'center' => "
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate({$rotation}deg);
            opacity: {$opacity};
            z-index: 9999;
            pointer-events: none;
            text-align: center;
        ",
            'top-left' => "
            position: fixed;
            top: 10%;
            left: 10%;
            transform: rotate({$rotation}deg);
            opacity: {$opacity};
            z-index: 9999;
            pointer-events: none;
        ",
            'top-right' => "
            position: fixed;
            top: 10%;
            right: 10%;
            transform: rotate({$rotation}deg);
            opacity: {$opacity};
            z-index: 9999;
            pointer-events: none;
        ",
            'bottom-left' => "
            position: fixed;
            bottom: 10%;
            left: 10%;
            transform: rotate({$rotation}deg);
            opacity: {$opacity};
            z-index: 9999;
            pointer-events: none;
        ",
            'bottom-right' => "
            position: fixed;
            bottom: 10%;
            right: 10%;
            transform: rotate({$rotation}deg);
            opacity: {$opacity};
            z-index: 9999;
            pointer-events: none;
        ",
        ];

        $style = $styles[$position] ?? $styles['center'];

        if ($position === 'tile') {
            $items = '';
            for ($x = 0; $x < 3; $x++) {
                for ($y = 0; $y < 4; $y++) {
                    $items .= "<div style='transform: rotate({$rotation}deg); padding: 20px;'>
                    <span style='font-size: {$fontSize}px; color: rgb({$r},{$g},{$b}); font-weight: bold; font-family: Arial, sans-serif;'>
                        {$text}
                    </span>
                </div>";
                }
            }
            return "<div style='{$style}'>{$items}</div>";
        }

        return "<div style='{$style}'>
        <span style='font-size: {$fontSize}px; color: rgb({$r},{$g},{$b}); font-weight: bold; font-family: Arial, sans-serif;'>
            {$text}
        </span>
    </div>";
    }

    private function getWatermarkPosition($position, $pageW, $pageH)
    {
        return match ($position) {
            'top-left'     => ['x' => $pageW * 0.25, 'y' => $pageH * 0.25],
            'top-right'    => ['x' => $pageW * 0.75, 'y' => $pageH * 0.25],
            'bottom-left'  => ['x' => $pageW * 0.25, 'y' => $pageH * 0.75],
            'bottom-right' => ['x' => $pageW * 0.75, 'y' => $pageH * 0.75],
            default        => ['x' => $pageW / 2, 'y' => $pageH / 2],
        };
    }

    private function parsePageRange($range, $totalPages)
    {
        if (empty($range) || $range === 'all') {
            return range(1, $totalPages);
        }

        $pages = [];
        $parts = explode(',', $range);

        foreach ($parts as $part) {
            $part = trim($part);
            if (strpos($part, '-') !== false) {
                list($start, $end) = explode('-', $part);
                for ($i = max(1, (int)$start); $i <= min($totalPages, (int)$end); $i++) {
                    $pages[] = $i;
                }
            } else {
                $page = (int)$part;
                if ($page >= 1 && $page <= $totalPages) {
                    $pages[] = $page;
                }
            }
        }

        return array_unique($pages) ?: range(1, $totalPages);
    }

    private function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        return [
            'r' => hexdec(strlen($hex) === 6 ? substr($hex, 0, 2) : 'ff'),
            'g' => hexdec(strlen($hex) === 6 ? substr($hex, 2, 2) : '00'),
            'b' => hexdec(strlen($hex) === 6 ? substr($hex, 4, 2) : '00'),
        ];
    }
}
