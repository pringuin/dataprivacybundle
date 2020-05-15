<?php

namespace pringuin\DataprivacyBundle\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\Document;
use Pimcore\Model\Document\Service;
use Pimcore\Tool\Authentication;
use pringuin\DataprivacyBundle\Helper\Configurationhelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends FrontendController
{

    public function onKernelController(FilterControllerEvent $event)
    {
        // set auto-rendering to twig
        $this->setViewAutoRender($event->getRequest(), true, 'twig');
    }

    public function defaultAction(Request $request)
    {
        if(\Pimcore\Model\Site::isSiteRequest()) {
            $site = \Pimcore\Model\Site::getCurrentSite()->getId();
        } else {
            $site = 'default';
        }

        $configuration = Configurationhelper::getConfigurationForSite($site);

        //Make replacements for locales
        foreach($configuration as $key => $value){
            if(strpos($value,'%locale%')){
                $configuration[$key] = str_replace('%locale%',$request->getLocale(),$value);
            }
        }

        if(is_numeric($configuration['privacyUrl'])){
            $documentService = $this->get('pimcore.document_service');
            $document = Document::getById($configuration['privacyUrl']);
            $translations = $documentService->getTranslations($document);
            if(!empty($translations[$request->getLocale()])){
                $configuration['privacyUrl'] = Document::getById($translations[$request->getLocale()])->getFullPath();
            } else {
                $configuration['privacyUrl'] = $document->getFullPath();
            }
        }

        $this->view->configuration = $configuration;

    }
}
