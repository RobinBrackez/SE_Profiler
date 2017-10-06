<?php

class SE_ProfilerSparkow_Block_Sparkow extends Mage_Adminhtml_Block_Template implements SE_Profiler_Block_TabInterface
{
    /**
     * SE_Profiler_Block_FlyMeTo constructor.
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->setTemplate('se/profiler/sparkow.phtml');
    }

    public function getTabSubtext()
    {
        return $this->getNumberOfSparkowCalls();
    }

    public function getTabText()
    {
        return "Sparkow";
    }

    public function getElementName()
    {
        return "sparkow";
    }

    public function getSparkowCalls()
    {
        if (isset($_SESSION["se_profiler_sparow"])) {
            return $_SESSION["se_profiler_sparow"];
        }
        return [];
    }

    public function showSparkow()
    {
        return $this->getNumberOfSparkowCalls() > 0;
    }

    public function getNumberOfSparkowCalls()
    {
        return count($this->getSparkowCalls());
    }
}