<?php


class SE_Profiler_CartController extends Mage_Core_Controller_Front_Action
{
    public function fillAction()
    {
        $products = $this->getProductCollection();

        foreach ($products->getItems() as $product) {
            $cart = Mage::getSingleton('checkout/cart');
            $cart->init();

            $sql = (string)$products->getSelect();
            // Add a product (simple); id:12,  qty: 3
            try {
                $cart->addProduct($product->getId(), 1);
            } catch(Exception $e) {
                $this->getResponse()->setBody(json_encode(false));
            }
            $cart->save();
        }
        $this->getResponse()->setBody(json_encode(true));
    }

    public function emptyAction()
    {
        /** @var Mage_Checkout_Model_Cart $cart */
        $cart = Mage::getSingleton('checkout/cart');
        $cart->truncate();
        $cart->save();
        $this->getResponse()->setBody(json_encode(true));
    }

    /**
     * @return mixed
     */
    private function getProductCollection()
    {
        $products = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('qty')
            ->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1 AND {{table}}.qty>0',
                'inner')
            ->setOrder("id", "desc")
            ->setPageSize(5);

//        $products->getSelect()->order(new Zend_Db_Expr('RAND()')); // takes 7 seconds
        return $products;
    }
}