<?php


class SE_Profiler_Block_CacheLoads extends Mage_Adminhtml_Block_Template implements SE_Profiler_Block_TabInterface
{
    /**
     * SE_Profiler_Block_FlyMeTo constructor.
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->setTemplate('se/profiler/cacheLoad.phtml');
    }

    public function getTabText()
    {
        return "Load";
    }

    public function getElementName()
    {
        return "cache-load";
    }

    public function getCacheLoads()
    {
        if (isset($_SESSION["se_profiler_cache_load"])) {
            return $_SESSION["se_profiler_cache_load"];
        }
        return [];
    }

    public function showCacheLoads()
    {
        return $this->getNumberOfCacheLoads() > 0;
    }

    public function getNumberOfCacheLoads()
    {
        return count($this->getCacheLoads());
    }

    public function getTabSubtext()
    {
        return $this->getNumberOfCacheLoads();
    }
}