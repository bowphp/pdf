<?php

namespace Papac;

use Dompdf\Dompdf;
use Bow\Application\Services as BowService;

class PDFService extends BowService
{
    /**
     * Permet de créer le service
     *
     * @param Config $config
     */
    public function make(Config $config)
    {    
        $cf = $config['dompdf'];
        $r = require __DIR__.'/../config/dompdf.php';

        if (is_null($cf)) {
            $cf = $r;
        } else {
            $cf = array_merge($r, $cf);
        }
        
        $dompdf = Dompdf($cf);

        PDF::configure($dompdf);
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