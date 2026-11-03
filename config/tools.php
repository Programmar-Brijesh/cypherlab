<?php

return [
    'qr-generator' => [
        'name' => 'QR Architect',
        'slug' => 'tools/qr-generator',
        'route_name' => 'tools.qr-generator',
        'short_description' => 'Generate custom high-resolution QR codes with live color customization and instant PNG export.',
        'category' => 'Developer Utilities',
        'icon' => 'fas fa-qrcode',
        'seo_title' => 'Free Online QR Code Generator – Custom Color & High-Res Export',
        'meta_description' => 'Generate custom high-resolution QR codes online with live color customization, error correction, and instant PNG export. 100% free & private client-side tool by CypherLab.',
        'faqs' => [
            'Is this QR Code Generator free?' => 'Yes, CypherLab QR Architect is 100% free with unlimited generation and zero restrictions.',
            'Are generated QR codes permanent?' => 'Yes! Static QR codes encode text or URLs directly into the pattern and will never expire.',
            'Is my data stored on any server?' => 'No, all QR codes are rendered 100% client-side in your browser using JavaScript Web APIs.'
        ],
        'related_tools' => ['image-compressor', 'json-alchemist', 'base64-vanguard', 'uri-envoy'],
    ],
    'image-compressor' => [
        'name' => 'Visual Forge',
        'slug' => 'image-compressor',
        'route_name' => 'tools.image-compressor',
        'short_description' => 'Compress JPEG, PNG & WebP images with up to 85% file size reduction in your browser.',
        'category' => 'Image Tools',
        'icon' => 'fas fa-file-image',
        'seo_title' => 'Free Online Image Compressor – Reduce JPG, PNG & WebP Size',
        'meta_description' => 'Compress JPEG, PNG & WebP images online without losing quality. Up to 85% file size reduction. 100% free & private client-side tool by CypherLab.',
        'faqs' => [
            'Does image compression lower visual quality?' => 'Visual Forge uses smart canvas compression to maximize size savings while keeping visual quality virtually indistinguishable.',
            'Is my image uploaded to any server?' => 'No! All compression runs 100% locally in your browser using HTML5 Canvas APIs.',
            'What image formats are supported?' => 'JPEG, PNG, WebP, and GIF images are fully supported.'
        ],
        'related_tools' => ['qr-generator', 'json-alchemist', 'pdf-compress', 'image-to-pdf'],
    ],
    'json-alchemist' => [
        'name' => 'JSON Alchemist',
        'slug' => 'JSON-Alchemist',
        'route_name' => 'tools.json-alchemist',
        'short_description' => 'Format, beautify, validate, and minify JSON data instantly with syntax highlighting.',
        'category' => 'Developer Utilities',
        'icon' => 'fas fa-code',
        'seo_title' => 'Free Online JSON Formatter, Validator & Minifier',
        'meta_description' => 'Format, beautify, validate, and minify JSON data online with instant syntax highlighting and error detection. 100% private client-side tool by CypherLab.',
        'faqs' => [
            'How do I validate JSON for syntax errors?' => 'Paste your raw JSON string into JSON Alchemist to automatically parse and highlight line-by-line syntax errors.',
            'Is my sensitive API data private?' => 'Yes! JSON Alchemist processes all input 100% client-side in JavaScript.'
        ],
        'related_tools' => ['base64-vanguard', 'case-alchemy', 'uri-envoy', 'password-generator'],
    ],
    'password-generator' => [
        'name' => 'Crypto Vault',
        'slug' => 'password-generator',
        'route_name' => 'tools.password-generator',
        'short_description' => 'Generate cryptographically secure random passwords with Web Crypto API and entropy scoring.',
        'category' => 'Security Utilities',
        'icon' => 'fas fa-key',
        'seo_title' => 'Free Secure Random Password Generator – Web Crypto API',
        'meta_description' => 'Generate cryptographically secure random passwords with Web Crypto API. Custom length, character rules, and real-time entropy score. 100% private tool by CypherLab.',
        'faqs' => [
            'Are these passwords truly random?' => 'Yes! The generator relies on window.crypto.getRandomValues() for cryptographically secure random numbers.',
            'Are generated passwords stored in a database?' => 'No! Password generation occurs entirely in your local browser runtime.'
        ],
        'related_tools' => ['base64-vanguard', 'json-alchemist', 'qr-generator'],
    ],
    'base64-vanguard' => [
        'name' => 'Base64 Vanguard',
        'slug' => 'Base64-Vanguard',
        'route_name' => 'tools.base64-vanguard',
        'short_description' => 'Encode and decode Base64 strings, UTF-8 Unicode, and binary files with full security.',
        'category' => 'Developer Utilities',
        'icon' => 'fas fa-binary',
        'seo_title' => 'Free Online Base64 Encoder & Decoder – UTF-8 & Emoji Support',
        'meta_description' => 'Fast, secure, and private Base64 encoder and decoder online. Full UTF-8 Unicode & emoji support. 100% client-side tool by CypherLab.',
        'faqs' => [
            'Does this tool support special characters and emojis?' => 'Yes, full UTF-8 Unicode and emoji encoding/decoding is supported.',
            'Is my input sent to a server?' => 'No, Base64 conversion executes locally in your web browser.'
        ],
        'related_tools' => ['uri-envoy', 'json-alchemist', 'case-alchemy'],
    ],
    'case-alchemy' => [
        'name' => 'Case Alchemy',
        'slug' => 'Case-Alchemy',
        'route_name' => 'tools.case-alchemy',
        'short_description' => 'Convert text case instantly: camelCase, kebab-case, snake_case, Title Case, and UPPERCASE.',
        'category' => 'Text Utilities',
        'icon' => 'fas fa-font',
        'seo_title' => 'Free Online Text Case Converter – camelCase, snake_case & Title Case',
        'meta_description' => 'Convert text case instantly online: UPPERCASE, lowercase, Title Case, camelCase, kebab-case, and snake_case. 100% private tool by CypherLab.',
        'faqs' => [
            'What text transformations are available?' => 'UPPERCASE, lowercase, Title Case, Sentence case, camelCase, kebab-case, snake_case, and Alternating case.',
            'Is there any character limit?' => 'No! Thousands of lines of text can be converted instantly in memory.'
        ],
        'related_tools' => ['json-alchemist', 'base64-vanguard', 'uri-envoy'],
    ],
    'chromatic-studio' => [
        'name' => 'Chromatic Studio',
        'slug' => 'Chromatic-Studio',
        'route_name' => 'tools.chromatic-studio',
        'short_description' => 'Generate UI themes, Tailwind tokens, CSS gradients, and HSL color palettes.',
        'category' => 'Design Utilities',
        'icon' => 'fas fa-palette',
        'seo_title' => 'Free Online Color Palette & CSS Gradient Studio',
        'meta_description' => 'Generate website color schemes, premium UI themes, Tailwind tokens, and CSS gradients based on custom colors or categories. 100% private tool by CypherLab.',
        'faqs' => [
            'Can I export Tailwind CSS tokens?' => 'Yes! Chromatic Studio generates ready-to-copy Tailwind color configuration blocks.',
            'Is it free to use?' => 'Yes, 100% free with unlimited export options.'
        ],
        'related_tools' => ['qr-generator', 'image-compressor', 'case-alchemy'],
    ],
    'uri-envoy' => [
        'name' => 'URI Envoy',
        'slug' => 'URI-Envoy',
        'route_name' => 'tools.uri-envoy',
        'short_description' => 'Encode, decode, parse URL parameters, clean tracking UTM parameters, and generate API code.',
        'category' => 'Developer Utilities',
        'icon' => 'fas fa-link',
        'seo_title' => 'Free Online URL Encoder, Decoder & Parameter Inspector',
        'meta_description' => 'Encode, decode, parse, validate, clean tracking parameters, batch process URLs, and generate API code snippets. 100% private client-side tool by CypherLab.',
        'faqs' => [
            'What does URL encoding do?' => 'URL encoding converts characters into format that can be safely transmitted over the Internet.',
            'Can I strip UTM tracking tags from URLs?' => 'Yes, URI Envoy includes an automated parameter cleaning inspector.'
        ],
        'related_tools' => ['base64-vanguard', 'json-alchemist', 'qr-generator'],
    ],
    'pdf-tools' => [
        'name' => 'PDF Suite Hub',
        'slug' => 'pdf-tools',
        'route_name' => 'pdf.tools',
        'short_description' => 'Complete PDF toolkit: merge, split, compress, convert Word/Images, OCR, and watermark PDFs.',
        'category' => 'PDF Utilities',
        'icon' => 'fas fa-file-pdf',
        'seo_title' => 'Free Online PDF Tools Hub – Merge, Split, Compress & Convert PDF',
        'meta_description' => 'Complete PDF toolkit online: merge PDFs, split pages, compress file size, convert Word to PDF, OCR text extraction & watermarking. 100% free by CypherLab.',
        'faqs' => [
            'Are these PDF tools free?' => 'Yes, all CypherLab PDF utilities are completely free with no user registration or daily limits.',
            'Is PDF processing secure?' => 'Yes! Files are processed securely in temporary isolated memory and automatically deleted.'
        ],
        'related_tools' => ['pdf-merge', 'pdf-split', 'pdf-compress', 'pdf-to-word', 'image-to-pdf'],
    ],
    'pdf-merge' => [
        'name' => 'PDF Merger',
        'slug' => 'pdf-merge',
        'route_name' => 'pdf.merge',
        'short_description' => 'Combine multiple PDF files into one clean document with custom page reordering.',
        'category' => 'PDF Utilities',
        'icon' => 'fas fa-object-group',
        'seo_title' => 'Free Online PDF Merger – Combine PDF Files Securely',
        'meta_description' => 'Merge multiple PDF files into one clean document online. Reorder pages, drag & drop, and download instantly. 100% free & secure tool by CypherLab.',
        'faqs' => [
            'Can I reorder PDF files before merging?' => 'Yes, drag and drop files to rearrange page ordering before combining.',
            'Is there a file limit?' => 'Combine up to 50MB of PDF files per merge request.'
        ],
        'related_tools' => ['pdf-split', 'pdf-compress', 'pdf-tools'],
    ],
    'pdf-split' => [
        'name' => 'PDF Splitter',
        'slug' => 'pdf-split',
        'route_name' => 'pdf.split',
        'short_description' => 'Split PDF documents by page ranges, extract specific pages, or export all pages.',
        'category' => 'PDF Utilities',
        'icon' => 'fas fa-cut',
        'seo_title' => 'Free Online PDF Splitter – Extract & Separate PDF Pages',
        'meta_description' => 'Split PDF documents by page range, extract specific pages, or export as individual files online. Fast, secure & free tool by CypherLab.',
        'faqs' => [
            'How do I extract a single page from a PDF?' => 'Upload your document, enter the target page number, and click process to download.',
            'Can I download extracted pages as a ZIP archive?' => 'Yes, multi-page splits offer single PDF or ZIP archive downloads.'
        ],
        'related_tools' => ['pdf-merge', 'pdf-compress', 'pdf-tools'],
    ],
    'pdf-compress' => [
        'name' => 'PDF Compressor',
        'slug' => 'pdf-compress',
        'route_name' => 'pdf.compress',
        'short_description' => 'Reduce PDF file size with adjustable compression levels while preserving document readability.',
        'category' => 'PDF Utilities',
        'icon' => 'fas fa-compress-alt',
        'seo_title' => 'Free Online PDF Compressor – Reduce PDF File Size',
        'meta_description' => 'Compress PDF files online with low, medium, or high compression levels. Reduce file size for email sharing. 100% free tool by CypherLab.',
        'faqs' => [
            'Does compressing a PDF reduce text quality?' => 'High compression optimizes embedded images while preserving text vector readability.',
            'What is the maximum upload size?' => 'Supports PDF documents up to 50MB.'
        ],
        'related_tools' => ['image-compressor', 'pdf-merge', 'pdf-tools'],
    ],
    'pdf-to-word' => [
        'name' => 'PDF to Word Converter',
        'slug' => 'pdf-to-word',
        'route_name' => 'pdf.to-word',
        'short_description' => 'Convert PDF documents to editable DOCX Word files with layout formatting preserved.',
        'category' => 'PDF Utilities',
        'icon' => 'fas fa-file-word',
        'seo_title' => 'Free Online PDF to Word Converter – Convert PDF to DOCX',
        'meta_description' => 'Convert PDF files to editable Word (DOCX) documents online. Fast, layout-preserved, secure & free tool by CypherLab.',
        'faqs' => [
            'Will the converted Word document be editable?' => 'Yes! Text, paragraphs, and layout elements are converted into editable Microsoft Word DOCX format.',
            'Is my file stored?' => 'Files are stored in temporary processing memory and deleted automatically after conversion.'
        ],
        'related_tools' => ['word-to-pdf', 'pdf-tools', 'pdf-ocr'],
    ],
    'word-to-pdf' => [
        'name' => 'Word to PDF Converter',
        'slug' => 'word-to-pdf',
        'route_name' => 'word.to-pdf',
        'short_description' => 'Convert Word DOCX/DOC documents into clean, professional PDF files instantly.',
        'category' => 'PDF Utilities',
        'icon' => 'fas fa-file-pdf',
        'seo_title' => 'Free Online Word to PDF Converter – Convert DOCX to PDF',
        'meta_description' => 'Convert Microsoft Word (DOCX/DOC) documents to clean PDF files online with layout formatting preserved. 100% free tool by CypherLab.',
        'faqs' => [
            'Are DOC and DOCX files supported?' => 'Yes, both legacy .DOC and modern .DOCX formats are supported.',
            'Is formatting preserved?' => 'Fonts, headings, images, and alignment are accurately rendered in the generated PDF.'
        ],
        'related_tools' => ['pdf-to-word', 'image-to-pdf', 'pdf-tools'],
    ],
    'image-to-pdf' => [
        'name' => 'Image to PDF Converter',
        'slug' => 'image-to-pdf',
        'route_name' => 'image.to-pdf',
        'short_description' => 'Convert JPG, PNG, and WebP images to single or multi-page PDF documents.',
        'category' => 'PDF Utilities',
        'icon' => 'fas fa-images',
        'seo_title' => 'Free Online Image to PDF Converter – Convert JPG & PNG to PDF',
        'meta_description' => 'Convert JPG, PNG, and WebP images to PDF. Fully customizable page sizes, orientation, margins, rotation, and drag & drop reordering. 100% free tool by CypherLab.',
        'faqs' => [
            'Can I combine multiple photos into one PDF?' => 'Yes! Upload multiple images, arrange their order, and export as a single combined PDF document.',
            'Can I adjust page margins and orientation?' => 'Yes, customizable page sizes (A4, Letter), orientation, and margin options are included.'
        ],
        'related_tools' => ['image-compressor', 'word-to-pdf', 'pdf-tools'],
    ],
    'pdf-ocr' => [
        'name' => 'PDF OCR Text Extractor',
        'slug' => 'pdf-ocr',
        'route_name' => 'pdf.ocr',
        'short_description' => 'Extract 95%+ accurate text from PDF documents and scanned images online.',
        'category' => 'PDF Utilities',
        'icon' => 'fas fa-eye',
        'seo_title' => 'Free Online PDF OCR Reader – Extract Text from Scanned PDFs',
        'meta_description' => 'Extract 95%+ accurate text from PDF documents and scanned images online. Multi-language support, page-by-page extraction, copy text & download TXT.',
        'faqs' => [
            'How accurate is the OCR extraction?' => 'Optical Character Recognition (OCR) achieves 95%+ text accuracy on clean scanned documents.',
            'Can I download extracted text as a file?' => 'Yes, copy text to clipboard or download as a .TXT file.'
        ],
        'related_tools' => ['pdf-to-word', 'pdf-tools'],
    ],
    'pdf-watermark' => [
        'name' => 'PDF Watermark Generator',
        'slug' => 'pdf-watermark',
        'route_name' => 'pdf.watermark',
        'short_description' => 'Add custom text or image watermarks to PDF files with opacity and angle controls.',
        'category' => 'PDF Utilities',
        'icon' => 'fas fa-stamp',
        'seo_title' => 'Free Online PDF Watermark Generator – Add Text or Stamp to PDF',
        'meta_description' => 'Add text or image watermarks to PDF files. Customize opacity, rotation, position & page range. Free online tool by CypherLab.',
        'faqs' => [
            'Can I customize watermark opacity and rotation?' => 'Yes, adjust watermark transparency, rotation angle, font size, and positioning.',
            'Is the watermark permanently embedded?' => 'Yes, watermarks are flattened into the PDF document stream.'
        ],
        'related_tools' => ['pdf-merge', 'pdf-compress', 'pdf-tools'],
    ],
];
