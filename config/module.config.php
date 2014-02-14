<?php

return array(
    'static_file_versioning' => array(
        'prefix' => '?v=',
        'suffix' => '',
        'resolvers' => array(
            'default' => function($input, $serviceLocator) {
                    ltrim($input, '/');
                    $pwd = getcwd();
                    foreach (array('public', 'htdocs') as $dir) {
                        $path = $pwd . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $input;
                        if (file_exists($path)) {
                            return filemtime($path);
                        }
                    }    
                    return 0;        
                }
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'BitStaticFileVersioning\Options\ModuleOptions' => function ($sl) {
                    $config = $sl->get('Config');
                    return new \BitStaticFileVersioning\Options\ModuleOptions($config['static_file_versioning']);
                }
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'staticVersion' => function($helperPluginManager) {
                    $serviceLocator = $helperPluginManager->getServiceLocator();
                    $viewHelper = new \BitStaticFileVersioning\View\Helper\StaticVersion();
                    $viewHelper->setServiceLocator($serviceLocator);
                    return $viewHelper;
                }
        )
    )
);