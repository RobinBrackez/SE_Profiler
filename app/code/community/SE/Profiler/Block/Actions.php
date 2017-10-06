<?php

class SE_Profiler_Block_Actions extends SE_Profiler_Block_Attributes
{

    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->setTemplate('se/profiler/actions.phtml');
    }

    public function getTabText()
    {
        return "Actions";
    }

    public function getElementName()
    {
        return "actions";
    }

    public function getTabSubtext()
    {
        return false;
    }

    public function showActions()
    {
        return true;
    }

}