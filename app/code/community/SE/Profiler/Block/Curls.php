<?php

class SE_Profiler_Block_Curls extends Mage_Adminhtml_Block_Template implements SE_Profiler_Block_TabInterface
{
    /**
     * SE_Profiler_Block_FlyMeTo constructor.
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->setTemplate('se/profiler/curls.phtml');
    }

    public function getTabText()
    {
        return "Curls";
    }

    public function getElementName()
    {
        return "curls";
    }

    public function getCurls()
    {
        if (isset($_SESSION['se_profiler_curl'])) {
            return $_SESSION['se_profiler_curl'];
        } else {
            return [];
        }
    }

    public function getNumberOfCurls()
    {
        return count($this->getCurls());
    }

    public function showCurls()
    {
        return $this->getNumberOfCurls() > 0;
    }

    public function getTabSubtext()
    {
        return $this->getNumberOfCurls();
    }
}