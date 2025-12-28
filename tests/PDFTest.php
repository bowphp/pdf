<?php

namespace Tests;

use Dompdf\Dompdf;
use Bow\Pdf\PDF;
use Dompdf\Options;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use \ReflectionProperty;

class PDFTest extends TestCase
{
    public function test_configure_returns_singleton_and_get_instance(): void
    {
        $dompdf = $this->dompdfMock();
        $pdf = PDF::configure($dompdf);

        $this->assertSame($pdf, PDF::getInstance());
        $this->assertSame($dompdf, $pdf->getDomPDF());
        $this->assertSame('a4', $pdf->getPaper());
        $this->assertSame('portrait', $pdf->getOrientation());

        $dompdfTwo = $this->dompdfMock();
        $pdfAgain = PDF::configure($dompdfTwo);

        $this->assertSame($pdf, $pdfAgain, 'configure should keep the first instance');
    }

    public function test_call_static_without_configuration_throws(): void
    {
        $this->expectException(\Bow\Pdf\PDFException::class);

        PDF::undefinedMethod();
    }

    public function test_set_paper_updates_state_and_delegates(): void
    {
        $dompdf = $this->dompdfMock();
        $dompdf->expects($this->once())
            ->method('setPaper')
            ->with('a3', 'landscape');

        $pdf = new PDF($dompdf);

        $pdf->setPaper('a3', 'landscape');

        $this->assertSame('a3', $pdf->getPaper());
        $this->assertSame('landscape', $pdf->getOrientation());
    }

    public function test_html_converts_entities_and_resets_render_flag(): void
    {
        $dompdf = $this->dompdfMock();
        $dompdf->expects($this->once())
            ->method('loadHtml')
            ->with($this->stringContains('&#0128;'), 'UTF-8');

        $pdf = new PDF($dompdf);

        // Simulate already-rendered state then ensure html() resets it.
        $rendered = new ReflectionProperty(PDF::class, 'rendered');
        $rendered->setAccessible(true);
        $rendered->setValue($pdf, true);

        $pdf->html('Total: 10€', 'UTF-8');

        $this->assertFalse($rendered->getValue($pdf));
    }

    public function test_file_loads_html_and_resets_render_flag(): void
    {
        $dompdf = $this->dompdfMock();
        $dompdf->expects($this->once())
            ->method('loadHtmlFile')
            ->with('/tmp/file.html');

        $pdf = new PDF($dompdf);

        $rendered = new ReflectionProperty(PDF::class, 'rendered');
        $rendered->setAccessible(true);
        $rendered->setValue($pdf, true);

        $pdf->file('/tmp/file.html');

        $this->assertFalse($rendered->getValue($pdf));
    }

    public function test_output_triggers_render_when_needed(): void
    {
        $dompdf = $this->dompdfMock();
        $dompdf->expects($this->once())
            ->method('setPaper')
            ->with('a4', 'portrait');
        $dompdf->expects($this->once())
            ->method('render');
        $dompdf->expects($this->once())
            ->method('output')
            ->willReturn('PDF_DATA');

        $pdf = new PDF($dompdf);

        $this->assertSame('PDF_DATA', $pdf->output());
    }

    public function test_output_reuses_rendered_content(): void
    {
        $dompdf = $this->dompdfMock();
        $dompdf->expects($this->never())->method('render');
        $dompdf->expects($this->once())->method('output')->willReturn('PDF_DATA');

        $pdf = new PDF($dompdf);

        $rendered = new ReflectionProperty(PDF::class, 'rendered');
        $rendered->setAccessible(true);
        $rendered->setValue($pdf, true);

        $this->assertSame('PDF_DATA', $pdf->output());
    }

    public function test_set_options_wrapped_in_dompdf_options(): void
    {
        $dompdf = $this->dompdfMock();
        $dompdf->expects($this->once())
            ->method('setOptions')
            ->with($this->isInstanceOf(Options::class));

        $pdf = new PDF($dompdf);

        $this->assertSame($pdf, $pdf->setOptions(['chroot' => '/tmp']));
    }

    private function dompdfMock(): MockObject|Dompdf
    {
        return $this->getMockBuilder(Dompdf::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setPaper', 'render', 'output', 'loadHtml', 'loadHtmlFile', 'setOptions'])
            ->getMock();
    }
}
