<?php

namespace Papac;

use Dompdf\Dompdf;
use Bow\Application\Services;

class PDFService extends Services
{

    /**
     * Permet de créer le service
     *
     * @param Config $config
     */
    public function make(Config $config)
    {
        $dompdf = Dompdf();
        PDF::configure($dompdf, $config);
    }

    /**
     * Permet de lancer le service
     *
     * @return mixed
     */
    public function start()
    {
        //
    }
}