<?php

namespace Papac;

use Dompdf\Dompdf;
use Bow\Config\Config;
use Bow\Application\Service as BowService;

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
        
        $this->app->capsule('dompdf', function () use ($cf) {
            $dompdf = Dompdf($cf);

            return PDF::configure($dompdf);
        });
    }

    /**
     * Permet de lancer le service
     *
     * @return mixed
     */
    public function start()
    {
        $this->app->capsule('dompdf');
    }
}
