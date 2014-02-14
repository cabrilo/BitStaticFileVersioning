<?php

namespace BitStaticFileVersioning\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceManager;

class StaticVersion extends AbstractHelper
{
    /**
     * Service Locator
     * @var ServiceManager
     */
    protected $serviceLocator;

    /**
     * @param string $path      A relative path which resolver will use to map to filesystem file.
     * @param string $resolver  Name of callable, specified in configuration, to resolve modification date
     * @param string $prefix
     * @param string $suffix
     *
     * @return string
     * @throws Exception
     */
    public function __invoke($path, $resolver = 'default', $prefix = '?v=', $suffix = '')
    {
        $cfg = $this->serviceLocator->get('Config');
        
        $options = $this->serviceLocator->get('BitStaticFileVersioning\Options\ModuleOptions');
                
        if (!$prefix) $prefix = $options->getPrefix();
        if (!$suffix) $suffix = $options->getSuffix();

        $resolverFunction = $options->getResolver($resolver);
        if (!is_callable($resolverFunction)) {
            throw new \InvalidArgumentException('Invalid resolver function');
        }
        
        $version = call_user_func($resolverFunction, $path, $this->serviceLocator);
        
        return $path . $prefix . $version . $suffix;
    }

    /**
     * Setter for $this->serviceLocator
     * @param ServiceManager $serviceLocator
     */
    public function setServiceLocator(ServiceManager $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}