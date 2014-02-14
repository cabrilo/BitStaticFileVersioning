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
        
        foreach (array('prefix', 'suffix') as $var) {
            if (!${$var}) {
                ${$var} = (isset($cfg['static_file_versioning']) && isset($cfg['static_file_versioning'][$var]))
                    ? $cfg['static_file_versioning'][$var]
                    : '';
            }
        }

        $resolverFunction = $cfg['static_file_versioning']['resolvers'][$resolver];
        if (!is_callable($resolverFunction)) {
            throw new Exception('Invalid resolver function');
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