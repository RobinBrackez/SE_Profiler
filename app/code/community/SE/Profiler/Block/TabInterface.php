<?php


interface SE_Profiler_Block_TabInterface
{
    public function getTabText();
    public function getElementName();
    public function getTabSubtext();

    /** additional: get$Typenames() */
}