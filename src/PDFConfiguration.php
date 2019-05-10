<?php

namespace Papac;

use Dompdf\Dompdf;
use Bow\Configuration\Loader;
use Bow\Configuration\Configuration;

class PDFConfiguration extends Configuration
{
    /**
     * Permet de créer le service
     *
     * @param Loader $config
     */
    public function create(Loader $config)
    {
        $cf = $config['dompdf'];

        $r = require __DIR__.'/../config/dompdf.php';

        if (is_null($cf)) {
            $cf = $r;
        } else {
            $cf = array_merge($r, $cf);
        }
        
        $this->container->bind('dompdf', function () use ($cf) {
            $dompdf = Dompdf($cf);

            return PDF::configure($dompdf);
        });
    }

    /**
     * Permet de lancer le service
     *
     * @return mixed
     */
    public function run()
    {
        $this->container->make('dompdf');
    }
}
