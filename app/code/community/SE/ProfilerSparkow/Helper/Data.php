<?php


class SE_ProfilerSparkow_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param $sparkowPath
     * @param $store
     * @return string
     */
    public function getSparkowUrl($sparkowPath, $store)
    {
        $frontApi = Mage::getStoreConfig(Veritas_Sparkow_Helper_Data::XML_PATH_SPARKOW_FRONTAPI_URL, $store);
        $sparkowUrl = $frontApi . $sparkowPath;
        return $sparkowUrl;
    }

    public function logSparkowUrl($sparkowPath, $store)
    {
        $sparkowUrl = $this->getSparkowUrl($sparkowPath, $store);
        $_SESSION['se_profiler_sparow'][$sparkowUrl] = $sparkowUrl;
    }
}