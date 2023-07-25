<?php

namespace pringuin\DataprivacyBundle\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Tool\Authentication;
use Pimcore\Translation\Translator;
use pringuin\DataprivacyBundle\Helper\Configurationhelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends FrontendController
{

    /**
     * @Route("/pringuin_dataprivacy")
     */
    public function indexAction(Request $request, Translator $translator): Response
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
                    if($request->get($siteid)){
                        $configurationdata = $request->get($siteid);
                        if(is_iterable($configurationdata)){
                            $result = Configurationhelper::setConfigurationForSite($siteid,$configurationdata);
                            if($result){
                                $responsemessage .= '<div>'.$translator
                                        ->trans(
                                            'configuration_save_success',
                                            ['%site%' => $siteid],
                                            'admin'
                                        ).'</div>';
                            }
                            else{
                                $responsemessage .= '<div>'.$translator
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

        return $this->render('@pringuinDataprivacy/admin/index.html.twig', ['message' => $responsemessage, 'configurations' => $configurations]);


    }




}
