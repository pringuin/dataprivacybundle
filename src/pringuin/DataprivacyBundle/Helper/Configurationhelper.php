<?php

namespace pringuin\DataprivacyBundle\Helper;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

abstract class Configurationhelper {

    /**
     * @var string
     */
    private static $configurationPath = PIMCORE_APP_ROOT . '/config/pringuin_dataprivacy';

    /**
     * @param String $site
     * @return string
     */
    public static function getConfigurationFileForSite(string $site) {
        $file = self::$configurationPath . '/siteconfig_' . $site . '.yml';

        if(!is_file($file)){
            if (!is_dir(self::$configurationPath)) {
                mkdir(self::$configurationPath);
            }
            $defaultcontent = self::getDefaultConfig();
            $yaml = Yaml::dump($defaultcontent);
            file_put_contents($file, $yaml);
        }

        return $file;
    }

    /**
     * @param String $site
     * @return array
     */
    public static function getConfigurationForSite(string $site) {

        $filename = self::getConfigurationFileForSite($site);

        try {
            $ymlarray = Yaml::parseFile($filename);
        } catch (ParseException $exception) {
            return array();
        }

        return $ymlarray;

    }

    /**
     * @param String $site
     * @param array $configuration
     * @return boolean
     */
    public static function setConfigurationForSite(string $site, array $configuration) {

        $filename = self::getConfigurationFileForSite($site);

        try {
            $yaml = Yaml::dump($configuration);
            file_put_contents($filename, $yaml);
        } catch (\Exception $exception) {
            return false;
        }
        return true;

    }

    /**
     * @return string
     */
    public static function getDefaultConfigFile(){
        try{
            return __DIR__.'/../Resources/var/defaultconfiguration.yml';
        }
        catch (\Exception $e){
            return '';
        }
    }

    /**
     * @return array
     */
    public static function getDefaultConfig(){
        $filename = self::getDefaultConfigFile();

        try {
            $ymlarray = Yaml::parseFile($filename);
        } catch (ParseException $exception) {
            return array();
        }

        return $ymlarray;
    }

}
