<?php


class SE_ProfilerSparkow_Model_Product extends Veritas_Sparkow_Model_Product
{

    /**
     * Initialise a JSON decoded Sparkow object from the provided Sparkow path.
     *
     * @param  string $sparkowPath
     * @param  string|int|null $store
     * @return bool|Veritas_Sparkow_Model_Product
     */
    public function initBySparkowPath($sparkowPath, $store = null)
    {
        $this->getHelper()->logSparkowUrl($sparkowPath, $store);
        $response = parent::initBySparkowPath($sparkowPath, $store);
        return $response;
    }

    /**
     * @return SE_ProfilerSparkow_Helper_Data
     */
    private function getHelper()
    {
        return Mage::helper('se_profilersparkow');
    }

}