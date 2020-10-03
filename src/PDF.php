<?php

namespace Bow;

use Dompdf\Dompdf;
use Dompdf\Options;

class PDF
{
    /**
     * The Dompdf instance
     *
     * @var Dompdf
     */
    private $dompdf;

    /**
     * The rendering indicator Flag
     *
     * @var bool
     */
    private $rendered = false;

    /**
     * The paper orientation
     *
     * @var string
     */
    private $orientation = 'portrait';
    
    /**
     * The paper type
     *
     * @var string
     */
    private $paper = 'a4';
    
    /**
     * The PDF instance
     *
     * @var PDF
     */
    private static $instance;

    /**
     * PDF constructor
     *
     * @param Dompdf $dompdf
     * @return void
     */
    public function __construct(Dompdf $dompdf)
    {
        $this->dompdf = $dompdf;
    }

    /**
     * Get the DomPDF instance
     *
     * @return Dompdf
     */
    public function getDomPDF()
    {
        return $this->dompdf;
    }

    /**
     * Get paper type
     *
     * @return string
     */
    public function getPaper()
    {
        return $this->paper;
    }

    /**
     * Get orientation
     *
     * @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set the paper size (default A4)
     *
     * @param string $paper
     * @param string $orientation
     * @return PDF
     */
    public function setPaper($paper, $orientation = 'portrait')
    {
        $this->paper = $paper;

        $this->orientation = $orientation;

        $this->dompdf->setPaper($paper, $orientation);

        return $this;
    }

    /**
     * Load a HTML string
     *
     * @param string $string
     * @param string $encoding
     * @return PDF
     */
    public function html($string, $encoding = null)
    {
        $string = $this->convertEntities($string);

        $this->dompdf->loadHtml($string, $encoding);

        $this->rendered = false;
        
        return $this;
    }

    /**
     * Load a HTML file
     *
     * @param string $file
     * @return PDF
     */
    public function file($file)
    {
        $this->dompdf->loadHtmlFile($file);

        $this->rendered = false;
        
        return $this;
    }

    /**
     * Load a View and convert to HTML
     *
     * @param string $view
     * @param array $data
     * @return PDF
     */
    public function view($view, $data = array())
    {
        $html = view($view, $data);
        
        return $this->html($html, $encoding);
    }

    /**
     * Set/Change an option in DomPdf
     *
     * @param array $options
     * @return PDF
     */
    public function setOptions(array $options)
    {
        $options = new Options($options);

        $this->dompdf->setOptions($options);

        return $this;
    }

    /**
     * Output the PDF as a string.
     *
     * @return string
     */
    public function output()
    {
        if (!$this->rendered) {
            $this->render();
        }

        return $this->dompdf->output();
    }

    /**
     * Save the PDF to a file
     *
     * @param string $filename
     * @return PDF
     */
    public function save($filename)
    {
        $this->files->put($filename, $this->output());

        return $this;
    }

    /**
     * Make the PDF downloadable by the user
     *
     * @param string $filename
     */
    public function download($filename = 'document.pdf')
    {
        $output = $this->output();

        return response()->download($output, $filename, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  'attachment; filename="'.$filename.'"'
        ));
    }

    /**
     * Return a response with the PDF to show in the browser
     *
     * @param string $filename
     * @return \Bow\Http\Response
     */
    public function stream($filename = 'document.pdf')
    {
        $output = $this->output();

        return response($output, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  'inline; filename="'.$filename.'"',
        ));
    }

    /**
     * Render the PDF
     *
     * @return void
     */
    protected function render()
    {
        if (!$this->dompdf) {
            throw new Exception('DOMPDF not created yet');
        }

        $this->dompdf->setPaper($this->paper, $this->orientation);

        $this->dompdf->render();

        $this->rendered = true;
    }

    /**
     * Converte Special entities
     *
     * @param string $subject
     * @return string
     */
    protected function convertEntities($subject)
    {
        $entities = array(
            '€' => '&#0128;',
            '£' => '&pound;',
        );

        foreach ($entities as $search => $replace) {
            $subject = str_replace($search, $replace, $subject);
        }
        
        return $subject;
    }

    /**
     * Configure pdf class
     *
     * @param Dompdf $dompdf
     * @return PDF
     */
    public static function configure(Dompdf $dompdf)
    {
        if (is_null(static::$instance)) {
            return static::$instance = new static($dompdf);
        }

        return static::$instance;
    }

    /**
     * Get PDF instance
     * @return PDF
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * __callStatic
     *
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws PDFException
     */
    public static function __callStatic($method, $args)
    {
        if (is_null(static::$instance)) {
            throw new PDFException('PDF not configurate');
        }

        if (method_exists(static::$instance, $method)) {
            return call_user_func_array([static::$instance, $method], $args);
        }

        throw new PDFException(sprintf('%s method not exists', $method));
    }
}
