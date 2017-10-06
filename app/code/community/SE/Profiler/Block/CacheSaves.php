<?php


class SE_Profiler_Block_CacheSaves extends Mage_Adminhtml_Block_Template implements SE_Profiler_Block_TabInterface
{
    /**
     * SE_Profiler_Block_FlyMeTo constructor.
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->setTemplate('se/profiler/cacheSave.phtml');
    }

    public function getTabText()
    {
        return "Save";
    }

    public function getElementName()
    {
        return "cache-save";
    }

    public function getCacheSaves()
    {
        if (isset($_SESSION["se_profiler_cache_save"])) {
            return $_SESSION["se_profiler_cache_save"];
        }
        return [];
    }

    public function showCacheSaves()
    {
        return $this->getNumberOfCacheSaves() > 0;
    }

    public function getNumberOfCacheSaves()
    {
        return count($this->getCacheSaves());
    }

    public function getTabSubtext()
    {
        return $this->getNumberOfCacheSaves();
    }
}