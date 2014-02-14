<?php

namespace BitStaticFileVersioning\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    protected $prefix;
    protected $suffix;
    protected $resolvers;
    
    public function setPrefix($v)
    {
        $this->prefix = $v;
    }
    
    public function getPrefix()
    {
        return $this->prefix ? $this->prefix : '?v=';
    }

    public function setSuffix($v)
    {
        $this->suffix = $v;
    }
    
    public function getSuffix()
    {
        return $this->suffix;
    }
    
    public function setResolvers(Array $v)
    {
        $this->resolvers = $v;
    }
    
    public function getResolver($r = 'default') 
    {
        return (isset($this->resolvers[$r])) ? $this->resolvers[$r] : false;
    }
}