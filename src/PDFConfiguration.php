<?php

namespace Bow;

use Dompdf\Dompdf;
use Bow\Configuration\Loader as Config;
use Bow\Configuration\Configuration;

class PDFConfiguration extends Configuration
{
    /**
     * {@inheritDoc}
     */
    public function create(Config $config)
    {
        $cf = (array) ($config['pdf'] ?? $config['dompdf']);

        $r = require __DIR__.'/../config/pdf.php';

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
     * {@inheritDoc}
     */
    public function run()
    {
        $this->container->make('dompdf');
    }
}
