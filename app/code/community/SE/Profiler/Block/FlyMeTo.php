<?php


class SE_Profiler_Block_FlyMeTo extends Mage_Adminhtml_Block_Template implements SE_Profiler_Block_TabInterface
{
    // * Fly me to cat/prod id/sku/custom unique field/orderid/klantnaam (frontend/backend)


    /**
     * SE_Profiler_Block_FlyMeTo constructor.
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->setTemplate('se/profiler/flyMeTo.phtml');
    }

    public function getTabText()
    {
        return "Fly me to";
    }

    public function getElementName()
    {
        return "fly-me-to";
    }

    public function showFlyMeTo()
    {
        return true;
    }

    public function getTabSubtext()
    {
        return false;
    }
}