<?php

/**
 * This is currently not in use
 *
 *
 * Class SE_Profiler_FlyMeToController
 *
 */
class SE_Profiler_FlyMeToController extends Mage_Core_Controller_Front_Action
{
    const EVERYTHING = 1;
    const PRODUCT = 2;
    const CATEGORY = 3;
    const ORDER = 4;
    const CUSTOMER = 5;

    public function indexAction()
    {
        $type = $this->getRequest()->get('type') * 1;
        $text = $this->getRequest()->get('text');
        $destination = $this->getRequest()->get('destination');
        $directly = $this->getRequest()->get('directly');
        $pageSize = $directly ? 1 : 30;
        $jsonResults = [];

        switch ($type) {
            case self::PRODUCT:
                $jsonResults = $this->findProduct($text);
                break;
        }
        $this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
        $this->getResponse()->setBody(json_encode($jsonResults));
    }

    private function findProduct($text, $pageSize = 30)
    {
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToFilter(
                array(
                    array('attribute'=> 'sku','like' => $text),
                    array('attribute'=> 'entity_id','like' => $text),
                    array('attribute'=> 'name','like' => "%$text%"),
                )
            )->setPageSize($pageSize);
        $productArray = [];
        /** @var SE_Profiler_Helper_Data $helper */
        $helper = Mage::helper('se_profiler');
        foreach ($collection as $product) {
            $productArray[] = [
                'id' => $product->getId(), 'name' => $product->getName(), 'sku' => $product->getSku(),
                'frontend-link' => $helper->getProductViewLink($product->getId()),
                'backend-link' => $helper->getProductEditLink($product->getId())];
        }

        return $productArray;
    }
}