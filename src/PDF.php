<?php


namespace Papac;


class PDF
{
    /**
     * @var Dompdf
     */
    protected $dompdf;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var bool
     */
    protected $rendered = false;

    /**
     * @var string
     */
    protected $orientation;
    
    /**
     * @var string
     */
    protected $paper;
    
    /**
     * @var PDF
     */
    protected static $instance;

    /**
     * @param Dompdf $dompdf
     * @param Config $config
     */
    public function __construct(Dompdf $dompdf, Config $config){

        $this->dompdf = $dompdf;
        $this->config = $config;
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
     * get paper type
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
     * 
     * @return $this
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
     * @param string $encoding Not used yet
     * 
     * @return $this
     */
    public function loadHTML($string, $encoding = null)
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
     * 
     * @return $this
     */
    public function loadFile($file)
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
     * 
     * @return $this
     */
    public function loadView($view, $data = array())
    {
        $html = view($view, $data);
        
        return $this->loadHTML($html, $encoding);
    }

    /**
     * Set/Change an option in DomPdf
     *
     * @param array $options
     * 
     * @return $this
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
        if(!$this->rendered){
            $this->render();
        }

        return $this->dompdf->output();
    }

    /**
     * Save the PDF to a file
     *
     * @param $filename
     * @return static
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
     * @return \Bow\Http\Response
     */
    public function download($filename = 'document.pdf' )
    {
        $output = $this->output();

        return new Response($output, 200, array(
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
    public function stream($filename = 'document.pdf' )
    {
        $output = $this->output();

        return new Response($output, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  'inline; filename="'.$filename.'"',
        ));
    }

    /**
     * Render the PDF
     */
    protected function render()
    {
        if(!$this->dompdf){
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
     * 
     * @return string
     */
    protected function convertEntities($subject)
    {
        $entities = array(
            '€' => '&#0128;',
            '£' => '&pound;',
        );

        foreach($entities as $search => $replace) {
            $subject = str_replace($search, $replace, $subject);
        }
        
        return $subject;
    }

    /**
     * Configure pdf class
     * 
     * @param Dompdf $dompdf
     * @param Config $config
     */
    public static function configure(Dompdf $dompdf, Config $config)
    {
        if (is_null(static::$instance)) {
            return static::$instance = new static($dompdf, $config);
        }

        return static::$instance;
    }

    /**
     * Get PDF instance
     * 
     * @return PDF
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * __callStatic
     * 
     * 
     * @param string $method
     * @param array $args
     * 
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        if (is_null(static::$instance)) {
            throw new PDFException('PDF not configurate');
        }

        if (method_exists(static::$instance, $method)) {
            return call_user_func_array([static::$instance, $method], $args);
        }

        throw new PDFException(sprint('%s method not exists', $method));
    }
}