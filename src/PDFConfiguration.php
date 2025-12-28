<?php

namespace Bow\Pdf;

use Dompdf\Dompdf;
use Bow\Configuration\Configuration;
use Bow\Configuration\Loader as Config;

class PDFConfiguration extends Configuration
{
    /**
     * {@inheritDoc}
     */
    public function create(Config $config): void
    {
        $config = (array) ($config['pdf'] ?? $config['dompdf']);

        $base_config = require __DIR__.'/../config/pdf.php';

        if (is_null($config)) {
            $config = $base_config;
        } else {
            $config = array_merge($base_config, $config);
        }

        $this->container->bind('pdf', function () use ($config) {
            $dompdf = new Dompdf($config);

            return PDF::configure($dompdf);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        $this->container->make('dompdf');
    }
}
