<?php


class SE_Profiler_Block_CacheRemoves extends Mage_Adminhtml_Block_Template implements SE_Profiler_Block_TabInterface
{
    /**
     * SE_Profiler_Block_FlyMeTo constructor.
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->setTemplate('se/profiler/cacheRemove.phtml');
    }

    public function getTabText()
    {
        return "Remove";
    }

    public function getElementName()
    {
        return "cache-remove";
    }

    public function getCacheRemoves()
    {
        if (isset($_SESSION["se_profiler_cache_remove"])) {
            return $_SESSION["se_profiler_cache_remove"];
        }
        return [];
    }

    public function showCacheRemoves()
    {
        return $this->getNumberOfCacheRemoves() > 0;
    }

    public function getNumberOfCacheRemoves()
    {
        return count($this->getCacheRemoves());
    }

    public function getTabSubtext()
    {
        return $this->getNumberOfCacheRemoves();
    }
}