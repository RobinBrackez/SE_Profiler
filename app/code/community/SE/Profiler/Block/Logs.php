<?php


class SE_Profiler_Block_Logs extends Mage_Adminhtml_Block_Template implements SE_Profiler_Block_TabInterface
{
    /**
     * SE_Profiler_Block_FlyMeTo constructor.
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->setTemplate('se/profiler/logs.phtml');
    }

    public function getTabText()
    {
        return "Logs";
    }

    public function getElementName()
    {
        return "logs";
    }

    public function getLogs()
    {
        if (isset($_SESSION["se_profiler_logs"])) {
            return $_SESSION["se_profiler_logs"];
        }
        return [];
    }

    public function showLogs()
    {
        return $this->getNumberOfLogs() > 0;
    }

    public function getNumberOfLogs()
    {
        return count($this->getLogs());
    }

    public function getTabSubtext()
    {
        return $this->getNumberOfLogs();
    }
}