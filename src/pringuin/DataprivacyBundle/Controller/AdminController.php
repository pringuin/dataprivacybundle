<?php

namespace pringuin\DataprivacyBundle\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Tool\Authentication;
use pringuin\DataprivacyBundle\Helper\Configurationhelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends FrontendController
{

    public function onKernelController(FilterControllerEvent $event)
    {
        // set auto-rendering to twig
        $this->setViewAutoRender($event->getRequest(), true, 'twig');
    }

    /**
     * @Route("/pringuin_dataprivacy")
     */
    public function indexAction(Request $request)
    {
        $user = Authentication::authenticateSession($request);
        if(!$user){
            throw new AccessDeniedHttpException();
        }

        $allsites = array('default');

        $sitelisting = new \Pimcore\Model\Site\Listing();
        $sites = $sitelisting->getSites();

        if(is_iterable($sites)){
            foreach ($sites as $site){
                if($site instanceof \Pimcore\Model\Site){
                    if($site->getId()){
                        $allsites[] = $site->getId();
                    }
                }
            }
        }

        $defaultconfig = Configurationhelper::getDefaultConfig();

        $responsemessage = '';
        if($request->isMethod('post')){
            if(is_iterable($allsites)) {
                foreach ($allsites as $siteid) {
                    if($request->request->get($siteid)){
                        $configurationdata = $request->request->get($siteid);
                        if(is_iterable($configurationdata)){
                            $result = Configurationhelper::setConfigurationForSite($siteid,$configurationdata);
                            if($result){
                                $responsemessage .= '<div>'.$this
                                        ->get('translator')
                                        ->trans(
                                            'configuration_save_success',
                                            ['%site%' => $siteid],
                                            'admin'
                                        ).'</div>';
                            }
                            else{
                                $responsemessage .= '<div>'.$this
                                        ->get('translator')
                                        ->trans(
                                            'configuration_save_fail',
                                            ['%site%' => $siteid],
                                            'admin'
                                        ).'</div>';
                            }
                        }
                    }
                }
            }
        }

        $configurations = array();
        if(is_iterable($allsites)){
            foreach($allsites as $siteid){
                $configuration = Configurationhelper::getConfigurationForSite($siteid);
                if($defaultconfig && is_iterable($defaultconfig)){
                    foreach($defaultconfig as $defaultconfigkey => $defaultconfigentry){
                        if(!key_exists($defaultconfigkey,$configuration)){
                            $configuration[$defaultconfigkey] = $defaultconfigentry;
                        }
                    }
                }
                $configurations[$siteid] = $configuration;
            }
        }

        $this->view->message = $responsemessage;
        $this->view->configurations = $configurations;

        //return new Response('Hello world from pringuin_dataprivacy');
    }




}
