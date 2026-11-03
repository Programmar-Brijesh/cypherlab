<?php

namespace Tests\Feature;

use Tests\TestCase;

class QrGeneratorToolTest extends TestCase
{
    public function test_qr_generator_page_renders_successfully(): void
    {
        $response = $this->get(route('tools.qr-generator'));

        $response->assertStatus(200);
        $response->assertSee('Generate QR Code');
        $response->assertSee('onclick="generateQR()"', false);
        $response->assertSee('id="qrOutputBox"', false);
        $response->assertSee('id="qrText"', false);
        $response->assertSee('id="qrSize"', false);
        $response->assertSee('id="qrDark"', false);
        $response->assertSee('id="qrLight"', false);
        $response->assertSee('id="qrCorrection"', false);
        $response->assertSee('qrcode.min.js', false);
        $response->assertSee('function generateQR()', false);
        $response->assertSee('function downloadPNG()', false);
        $response->assertSee('function copyQRImage()', false);
    }
}

