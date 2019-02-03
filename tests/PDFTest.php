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

        return $pdf;
    }

    /**
     * @depends testConfigurationViaConfigureMethod
     */
    public function testGetPaper(PDF $pdf)
    {
        $this->assertEquals($pdf->getPaper(), 'a4');
        
        return $pdf;
    }

    /**
     * @depends testGetPaper
     */
    public function testGetOrientation(PDF $pdf)
    {
        $this->assertEquals($pdf->getOrientation(), 'portrait');

        return $pdf;
    }

    /**
     * @depends testGetOrientation
     */
    public function testSetPaper(PDF $pdf)
    {
        $pdf->setPaper('a3', 'landscape');

        $this->assertEquals($pdf->getOrientation(), 'landscape');
        $this->assertEquals($pdf->getPaper(), 'a3');

        $this->assertNotEquals($pdf->getOrientation(), 'portrait');
        $this->assertNotEquals($pdf->getPaper(), 'a4');
    }
}
