<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PdfToolController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Admin\DeveloperBlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Frontend\BlogpostController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('privacy');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Redirect /blog/create to admin create route (guarded by admin middleware)
Route::get('/blog/create', function () {
    return redirect()->route('admin.blog.create');
})->name('blog.create');

Route::get('/blog', [BlogpostController::class, 'index'])->name('blog.index');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/archive/{year}/{month?}', [BlogController::class, 'archive'])->name('blog.archive');
Route::get('/blog/{slug}', [BlogpostController::class, 'show'])->name('blog.show');

// Admin only routes for creating & saving blogs
Route::middleware(['auth', 'admin'])->prefix('admin/blog')->group(function () {
    Route::get('/create', [DeveloperBlogController::class, 'create'])->name('admin.blog.create');
    Route::get('/{id}/edit', [DeveloperBlogController::class, 'edit'])->name('admin.blog.edit');
    Route::delete('/{id}', [DeveloperBlogController::class, 'destroy'])->name('admin.blog.destroy');
    Route::post('/autosave', [DeveloperBlogController::class, 'autoSave'])->name('admin.blog.autosave');
    Route::post('/save', [BlogpostController::class, 'save'])->name('admin.blog.save');
    Route::post('/store', [BlogController::class, 'store'])->name('blog.store');
    Route::post('/upload-image', [DeveloperBlogController::class, 'uploadImage'])->name('admin.blog.upload_image');
});

// Admin Category Management
Route::middleware(['auth', 'admin'])->prefix('admin/categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
});

Route::get('/articles', [BlogpostController::class, 'index'])->name('article.index');
Route::get('/article/{slug}', [BlogpostController::class, 'show'])->name('article.show');




// routes/web.php

Route::get('/tools/qr-generator', function () {
    return view('tools.qr.qr-generator');
})->name('tools.qr-generator');

Route::get('/image-compressor', function () {
    return view('tools.image-compressor.image-compressor');
})->name('tools.image-compressor');

Route::get('/JSON-Alchemist', function () {
    return view('tools.JSON-Alchemist.JSON-Alchemist');
})->name('tools.json-alchemist');

Route::get('/password-generator', function () {
    return view('tools.password-generator.password-generator');
})->name('tools.password-generator');

Route::get('/tools/password-generator', function () {
    return view('tools.password-generator.password-generator');
});

Route::get('/Base64-Vanguard', function () {
    return view('tools.Base64-Vanguard.Base64-Vanguard');
})->name('tools.base64-vanguard');

Route::get('/Case-Alchemy', function () {
    return view('tools.Case-Alchemy.Case-Alchemy');
})->name('tools.case-alchemy');

Route::get('/Chromatic-Studio', function () {
    return view('tools.Chromatic-Studio.Chromatic-Studio');
})->name('tools.chromatic-studio');

Route::get('/URI-Envoy', function () {
    return view('tools.URI-Envoy.URI-Envoy');
})->name('tools.uri-envoy');



// pdf tools routes 


Route::get('/pdf-tools', [PdfToolController::class, 'landing'])->name('pdf.landing');



// PDF Tools Landing
Route::get('/pdf-tools', [PdfToolController::class, 'landing'])
    ->name('pdf.tools');

// PDF Merge
Route::get('/pdf-merge', [PdfToolController::class, 'merge'])
    ->name('pdf.merge');

Route::post('/pdf-merge/upload', [PdfToolController::class, 'mergeUpload'])->name('pdf.merge.upload');
Route::post('/pdf-merge/process', [PdfToolController::class, 'mergeProcess'])->name('pdf.merge.process');

// PDF Split
Route::get('/pdf-split', [PdfToolController::class, 'split'])
    ->name('pdf.split');

Route::post('/pdf-split/upload', [PdfToolController::class, 'splitUpload'])->name('pdf.split.upload');
Route::post('/pdf-split/process', [PdfToolController::class, 'splitProcess'])->name('pdf.split.process');
Route::post('/pdf-split/download-single', [PdfToolController::class, 'splitDownloadSingle'])->name('pdf.split.download.single');
Route::post('/pdf-split/download-zip', [PdfToolController::class, 'splitDownloadZip'])->name('pdf.split.download.zip');

// PDF Compressor
Route::get('/pdf-compress', [PdfToolController::class, 'compress'])
    ->name('pdf.compress');

Route::post('/pdf-compress/upload', [PdfToolController::class, 'compressUpload'])->name('pdf.compress.upload');
Route::post('/pdf-compress/process', [PdfToolController::class, 'compressProcess'])->name('pdf.compress.process');
Route::post('/pdf-compress/download', [PdfToolController::class, 'compressDownload'])->name('pdf.compress.download');

// PDF to Word
Route::get('/pdf-to-word', [PdfToolController::class, 'pdfToWord'])
    ->name('pdf.to-word');
Route::post('/pdf/toword/upload', [PdfToolController::class, 'pdfToWordUpload'])->name('pdf.toword.upload');
Route::post('/pdf/toword/process', [PdfToolController::class, 'pdfToWordProcess'])->name('pdf.toword.process');
Route::post('/pdf/toword/download', [PdfToolController::class, 'pdfToWordDownload'])->name('pdf.toword.download');

// Word to PDF
Route::get('/word-to-pdf', [PdfToolController::class, 'wordToPdf'])
    ->name('word.to-pdf');
Route::post('/word/topdf/upload', [PdfToolController::class, 'wordToPdfUpload'])->name('word.topdf.upload');
Route::post('/word/topdf/process', [PdfToolController::class, 'wordToPdfProcess'])->name('word.topdf.process');
Route::post('/word/topdf/download', [PdfToolController::class, 'wordToPdfDownload'])->name('word.topdf.download');

// Image to PDF
Route::get('/image-to-pdf', [PdfToolController::class, 'imageToPdf'])
    ->name('image.to-pdf');
Route::post('/image/topdf/upload', [PdfToolController::class, 'imageToPdfUpload'])->name('image.topdf.upload');
Route::post('/image/topdf/process', [PdfToolController::class, 'imageToPdfProcess'])->name('image.topdf.process');
Route::post('/image/topdf/download', [PdfToolController::class, 'imageToPdfDownload'])->name('image.topdf.download');

// OCR Reader
Route::get('/pdf-ocr', [PdfToolController::class, 'ocr'])
    ->name('pdf.ocr');
Route::post('/ocr/upload', [PdfToolController::class, 'ocrUpload'])->name('ocr.upload');
Route::post('/ocr/process', [PdfToolController::class, 'ocrProcess'])->name('ocr.process');

// PDF Watermark
Route::get('/pdf-watermark', [PdfToolController::class, 'watermark'])
    ->name('pdf.watermark');
Route::post('/pdf/watermark/upload', [PdfToolController::class, 'watermarkUpload'])->name('pdf.watermark.upload');
Route::post('/pdf/watermark/preview', [PdfToolController::class, 'watermarkPreview'])->name('pdf.watermark.preview');
Route::post('/pdf/watermark/process', [PdfToolController::class, 'watermarkProcess'])->name('pdf.watermark.process');
Route::post('/pdf/watermark/download', [PdfToolController::class, 'watermarkDownload'])->name('pdf.watermark.download');



// login routes


Route::get('/auth/google', [GoogleController::class, 'redirect'])
    ->name('google.login');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('google.callback');




Route::get('/registerUser', [AuthController::class, 'registerPage'])
    ->name('register.page');
Route::get('/register', [AuthController::class, 'registerPage']);

Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

Route::post('/check-email', [AuthController::class, 'checkEmail'])
    ->name('check.email');

Route::get('/verify/{id}', [AuthController::class, 'verifyPage'])
    ->name('verify.page');

Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])
    ->name('verify.otp');

Route::get('/login', [AuthController::class, 'loginPage'])
    ->name('login.page');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/resend-otp', [AuthController::class, 'resendOtp'])
    ->name('resend.otp');

Route::get('/dashboard', [AuthController::class, 'dashboard'])
    ->name('dashboard');

Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get('/edit-profile', [AuthController::class, 'editProfile'])
    ->name('profile.edit');

Route::post('/update-profile', [AuthController::class, 'updateProfile'])
    ->name('profile.update');

Route::get('/delete-account', [AuthController::class, 'deleteAccount'])
    ->name('account.delete');
