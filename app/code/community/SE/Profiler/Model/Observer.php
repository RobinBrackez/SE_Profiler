<?php


class SE_Profiler_Model_Observer
{
    public function alterResponse(Varien_Event_Observer $observer)
    {
        if ($this->isPost()) {
            return;
        }

        $response = $observer->getResponse();
        $responseContent = $response->getBody();

        if (strpos($responseContent, '<') === FALSE || strpos($responseContent, '<html') === FALSE) { //there's no html, probably json or other ajax
            return;
        }

        /** @var SE_Profiler_Block_Toolbar $block */
        $block = Mage::getSingleton('core/layout')->createBlock('SE_Profiler_Block_Toolbar');
        $block->setTemplate('se/profiler/profiler.phtml');
        $profiler = $block->toHtml();

        // If we can find a closing HTML tag in the response, let's add the
        // profiler content inside it.
        if (($pos = strrpos($responseContent, '</html>')) !== false)  {
            $responseContent = substr($responseContent, 0, $pos) . $profiler .substr($responseContent, $pos);
        }
        // If we cannot find a closing HTML tag, we'll just append the profiler
        // at the very end of the response's content.
        else {
            $responseContent .= $profiler;
        }

        $response->setBody($responseContent);
        $this->emptySessions();
    }

    private function emptySessions()
    {
        $_SESSION["se_profiler_product"] = null;
        $_SESSION["se_profiler_category"] = null;
        $_SESSION["se_profiler_cache_load"] = null;
        $_SESSION["se_profiler_cache_save"] = null;
        $_SESSION["se_profiler_cache_remove"] = null;
        $_SESSION["se_profiler_curl"] = null;
        $_SESSION["se_profiler_logs"] = [];
        $_SESSION["sparkow"] = null;
    }

    /**
     *
     *
     * @deprecated  use Mage::registry('current_product')
     * @param Varien_Event_Observer $observer
     */
    public function setProduct(Varien_Event_Observer $observer)
    {
        if ($this->isPost()) {
            return;
        }
        // this will only work if FULLPAGE CACHE is off.
        $product = Mage::registry('current_product');
        if (!$product instanceof Mage_Catalog_Model_Product) {
            $product = Mage::registry('product');
            if (!$product instanceof Mage_Catalog_Model_Product) {
                $product = $observer->getData('product');
                if (!$product instanceof Mage_Catalog_Model_Product) {
                    return;
                }
            }
        }
        if ($product->getId > 0 || strlen($product->getData('name')) > 0) {
            $_SESSION["se_profiler_product"] = $product;
        }
    }

    /**
     * @deprecated  use Mage::registry('current_category')
     * @param Varien_Event_Observer $observer
     */
    public function setCategory(Varien_Event_Observer $observer)
    {
        if ($this->isPost()) {
            return;
        }
        $category = Mage::registry('current_category');
        if (!$category instanceof Mage_Catalog_Model_Category) {
            $category = $observer->getEvent()->getCategory();
        }
        if ($category instanceof Mage_Catalog_Model_Category) {
            $_SESSION["se_profiler_category"] = $category;
        }
    }

    public function setCart(Varien_Event_Observer $observer)
    {
        if ($this->isPost()) {
            return;
        }
    }

    public function frontInitBefore(Varien_Event_Observer $observer)
    {
        if ($this->isPost()) {
            return;
        }
        $_SESSION["start_execution_time"] = time();
        Varien_Profiler::enable();
        Mage::getBlockSingleton("SE_Profiler_Block_Toolbar");
    }

    public function isAdmin()
    {
        //works only after Mage:app()->getStore() is defined
        if (Mage::app()->getStore()->isAdmin()) {
            return true;
        }

        if (Mage::getDesign()->getArea() == 'adminhtml') {
            return true;
        }

        return false;
    }

    private function isPost()
    {
        return ($_SERVER["REQUEST_METHOD"] == "POST" ||
            (array_key_exists("HTTP_X_REQUESTED_WITH", $_SERVER) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest"));
    }

}
