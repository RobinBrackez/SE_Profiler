<?php


class SE_Profiler_StockController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        
    }
    
    public function setInStockAction()
    {
        $this->handleStock(100);
    }

    public function setOutOfStockAction()
    {
        $this->handleStock(0);
    }

    public function handleStock($qty)
    {
        $productId = $this->getProductId();
        if ($productId > 0) {
            $this->changeStock($productId, $qty);
            $this->getResponse()->setBody(json_encode(true));
        } else {
            $this->getResponse()->setBody(json_encode(false));
        }
    }

    private function changeStock($productId, $qty)
    {
        // Check if there is a stock item object
        /** @var Mage_CatalogInventory_Model_Stock_Item $stockItem */
        $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
        $stockItemData = $stockItem->getData();
        if (empty($stockItemData)) {
            // Create the initial stock item object
            $stockItem->setData('manage_stock', 1);
            $stockItem->setData('is_in_stock', $qty ? 1 : 0);
            $stockItem->setData('use_config_manage_stock', 0);
            $stockItem->setData('stock_id', 1);
            $stockItem->setData('product_id', $productId);
            $stockItem->setData('qty', $qty);
            $stockItem->save();

            // Init the object again after it has been saved so we get the full object
            $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
        }

        $stockItem->setData('manage_stock', 1);
        $stockItem->setData('qty', $qty);
        $stockItem->setData('is_in_stock', $qty ? 1 : 0);
        $stockItem->save();
    }

    /**
     * @return mixed
     */
    private function getProductId()
    {
        return intval($this->getRequest()->getParam("id"));
    }
}