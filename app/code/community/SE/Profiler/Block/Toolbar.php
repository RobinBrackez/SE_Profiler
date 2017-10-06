<?php

class SE_Profiler_Block_Toolbar extends Mage_Adminhtml_Block_Template
{
    /**
     * Initialize factory instance
     *
     * @param array $args
     */
    public function __construct(array $args = array())
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" ||
            (array_key_exists("HTTP_X_REQUESTED_WITH", $_SERVER) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest")) {
            return;
        }
        parent::__construct($args);
        // add childs
        foreach ($this->getTabs() as $alias => $class) {
            $childBlock = Mage::getSingleton('core/layout')->createBlock($class);
            $this->setChild($alias, $childBlock);
        }
    }


    protected function _prepareLayout()
    {
        $head = $this->getLayout()
            ->getBlock('head');

        if ($head != null) {
            $head->addJs('se_profiler/jquery.js')
                ->addJs('se_profiler/profiler.js')
                ->addJs('se_profiler/flyMeTo.js')
                ->addCss('se_profiler/profiler.css');
        }
    }

    public function getTabs()
    {
        // type => classname
        $tabs = array(
            "logs" => "SE_Profiler_Block_Logs",
            "queries" => "SE_Profiler_Block_Queries",
            "curls" => "SE_Profiler_Block_Curls",
            "cacheLoads" => "SE_Profiler_Block_CacheLoads",
            "cacheSaves" => "SE_Profiler_Block_CacheSaves",
            "cacheRemoves" => "SE_Profiler_Block_CacheRemoves",
            "attributes" => "SE_Profiler_Block_Attributes",
//            "flyMeTo" => "SE_Profiler_Block_FlyMeTo", //under construction
            "actions" => "SE_Profiler_Block_Actions",
        );
        $otherModulesTabs = new Varien_Object();
        $otherModulesTabs->setNewTabs([]);
        Mage::dispatchEvent(
            'se_profiler_get_tabs',
            array('tabcontainer' => $otherModulesTabs)
        );
        $tabs = array_merge($tabs, $otherModulesTabs->getNewTabs());
        return $tabs;
    }

    public function getIncludedFiles()
    {
        return array();
    }

    public function getTimers()
    {
        $timers = Varien_Profiler::getTimers();
        return $timers;
    }

    public function getMemoryUsage()
    {
        return round(memory_get_usage(true) / 1024 / 1024, 2);
    }

    public function getQuote()
    {
        $quote = Mage::getModel('checkout/cart')->getQuote();
        return $quote;
    }

    public function hasQuote()
    {
        return $this->getQuote() != null && $this->getQuote()->getId() > 0;
    }

    public function getLoadTime()
    {
        return 0;
    }

}
