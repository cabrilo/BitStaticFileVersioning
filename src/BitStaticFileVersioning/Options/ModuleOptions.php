<?php

namespace BitStaticFileVersioning\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    protected $prefix;
    protected $suffix;
    protected $resolvers;

    /**
     * @param string $v
     */
    public function setPrefix($v)
    {
        $this->prefix = $v;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix ? $this->prefix : '?v=';
    }

    /**
     * @param string $v
     */
    public function setSuffix($v)
    {
        $this->suffix = $v;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param array $v
     */
    public function setResolvers(Array $v)
    {
        $this->resolvers = $v;
    }

    /**
     * @param callable $r
     *
     * @return bool
     */
    public function getResolver($r = 'default') 
    {
        return (isset($this->resolvers[$r])) ? $this->resolvers[$r] : false;
    }
}