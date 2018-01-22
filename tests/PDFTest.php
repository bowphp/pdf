<?php

use Dompdf\Dompdf;
use Papac\PDF;

class PDFTest extends \PHPUnit\Framework\TestCase
{
    public function testConfigurationViaConstructor()
    {
        $domdpf = new Dompdf();
        $pdf = new PDF($domdpf);

        $this->assertInstanceOf(PDF::class, $pdf);
    }

    public function testConfigurationViaConfigureMethod()
    {
        $domdpf = new Dompdf();
        $pdf = PDF::configure($domdpf);

        $this->assertInstanceOf(PDF::class, $pdf);
    }

    public function testGetPaper()
    {
        $domdpf = new Dompdf();
        $pdf = PDF::configure($domdpf);

        $this->assertEquals($pdf->getPaper(), 'a4');
    }
}