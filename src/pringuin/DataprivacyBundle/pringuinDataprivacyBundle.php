<?php

namespace pringuin\DataprivacyBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class pringuinDataprivacyBundle extends AbstractPimcoreBundle
{
    const PACKAGE_NAME = 'pringuin/dataprivacybundle';

    public function getJsPaths(): array
    {
        return [
            '/bundles/pringuindataprivacy/js/pimcore/startup.js'
        ];
    }

    public function getNiceName(): string
    {
        return 'Dataprivacy Bundle';
    }

    public function getDescription(): string
    {
        return 'Dataprivacy Bundle for Pimcore';
    }

    protected function getComposerPackageName(): string
    {
        return self::PACKAGE_NAME;
    }

}
