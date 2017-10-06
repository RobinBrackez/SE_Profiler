<?php

class SE_Profiler_Block_Attributes extends Mage_Adminhtml_Block_Template implements SE_Profiler_Block_TabInterface
{
    /**
     * SE_Profiler_Block_FlyMeTo constructor.
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->setTemplate('se/profiler/attributes.phtml');
    }

    public function getTabText()
    {
        return $this->getMainObjectType();
    }

    public function getElementName()
    {
        return "attributes";
    }

    public function getMainObjectType()
    {
        if ($this->hasCategory()) {
            return "Category";
        } elseif ($this->hasProduct()) {
            return "Product";
        } else {
            return false;
        }
    }

    public function hasProduct()
    {
        return array_key_exists("se_profiler_product", $_SESSION) && $this->getProduct() != null;
    }

    public function hasCategory()
    {
        return array_key_exists("se_profiler_category", $_SESSION) && $this->getCategory() != null;
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return $_SESSION["se_profiler_product"];
    }

    /**
     * @return Mage_Catalog_Model_Category
     */
    public function getCategory()
    {
        return $_SESSION["se_profiler_category"];
    }

    public function getStore()
    {
        return Mage::app()->getStore();
    }

    public function getId() {
        if ($this->hasCategory()) {
            return $this->getCategory()->getId();
        } elseif ($this->hasProduct()) {
            return $this->getProduct()->getId();
        } else
        return 0;
    }

    public function showAttributes()
    {
        return $this->getMainObjectType() !== false;
    }

    public function getTabSubtext()
    {
        if ($this->getId() > 0) {
            return "ID " . $this->getId();
        }
        return false;
    }

    public function getProductFrontendLinks()
    {
        /** @var Mage_Core_Model_Store $stores */
        $stores = Mage::app()->getStores();
        $product = $this->getProduct();
        $storeUrls = [];
        foreach ($product->getStoreIds() as $storeId) {
            $store = $stores[$storeId];
            if ($store->getIsActive()) {
                $_resource = $this->getProduct()->getResource();
                $customUrl = $_resource->getAttributeRawValue($this->getProduct()->getId(), 'url_key', $store);

                $seoUrlSuffix = Mage::getStoreConfig("faqs/seo/url_suffix", $store);

                $storeLink = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
                $storeUrls[$store->getName()] = [
                    "seo" => $storeLink . $customUrl . $seoUrlSuffix,
                    "by_id" => $storeLink . "catalog/product/view/id/" . $product->getId()
                ];
            }
        }
        return $storeUrls;
    }

    public function getProductEditLink()
    {
        $adminBaseUrl = Mage::app()->getStore(0)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
        $adminRouterName = (string)Mage::getConfig()->getNode(Mage_Adminhtml_Helper_Data::XML_PATH_ADMINHTML_ROUTER_FRONTNAME);
        $fullAdminUrlWrongDomain = Mage::helper("adminhtml")->getUrl("adminhtml/catalog_product/edit/", array("id" => $this->getProduct()->getId()));
        $backendUrl = $adminBaseUrl . substr($fullAdminUrlWrongDomain, strpos($fullAdminUrlWrongDomain, $adminRouterName));
        return $backendUrl;
    }

    public function getCategoryEditLink()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/catalog_category/edit', array('id' => $this->getCategory()->getId()));
    }
}