<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			new JMS\DiExtraBundle\JMSDiExtraBundle(),
    		new JMS\AopBundle\JMSAopBundle(),
    		new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
            new AppBundle\AppBundle(),
			new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Ivory\GoogleMapBundle\IvoryGoogleMapBundle(),
			new FM\ElfinderBundle\FMElfinderBundle(),
			new Twitter\BootstrapBundle\TwitterBootstrapBundle(),
			new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
			new Liip\ImagineBundle\LiipImagineBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new FOS\UserBundle\FOSUserBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            
            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        }

        return $bundles;
    }

    public function getAbsRootDir()
    {
        return dirname(__DIR__);
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }
    public function getWebDir()
    {
        return dirname(__DIR__).'/web';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config-'.$this->getEnvironment().'.yml');
    }
}
