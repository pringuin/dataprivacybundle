<?php
namespace pringuin\DataprivacyBundle\EventListener;

use Pimcore\Event\BundleManager\PathsEvent;

class PimcoreAdminListener
{
    public function addCSSFiles(PathsEvent $event): void
    {
        $event->setPaths(
            array_merge(
                $event->getPaths(),
                [
                    '/bundles/pringuindataprivacy/css/admin-style.css'
                ]
            )
        );
    }

    public function addJSFiles(PathsEvent $event): void
    {
        $event->setPaths(
            array_merge(
                $event->getPaths(),
                [
                    '/bundles/pringuindataprivacy/js/pimcore/startup.js'
                ]
            )
        );
    }
}
