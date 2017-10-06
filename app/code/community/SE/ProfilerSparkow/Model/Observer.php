<?php

class SE_ProfilerSparkow_Model_Observer
{
    public function addTab($observer)
    {
        // bit of a hassle here, but it's the only way I found to add items to an array that's passed back to the caller
        $tabs = $observer->getEvent()->getTabcontainer()->getNewTabs();
        $tabs['sparkow'] = "SE_ProfilerSparkow_Block_Sparkow";
        $observer->getEvent()->getTabcontainer()->setNewTabs($tabs);
        return $this;
    }

    public function removeSession()
    {
        $_SESSION["se_profiler_sparow"] = [];
    }
}