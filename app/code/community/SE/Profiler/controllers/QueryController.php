<?php


class SE_Profiler_QueryController extends Mage_Core_Controller_Front_Action
{
    /**
     * /profiler/operation/sql (POST: $sql)
     */
    public function selectAction()
    {
        $this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
        $sql = $_POST['sql'];
        if ($this->startsWith($sql, 'SELECT')) {
            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            try {
                $results = $readConnection->fetchAll($sql);
            } catch(Exception $e) {
                $this->getResponse()->setException($e);
                $this->getResponse()->setHttpResponseCode(500);
                $this->getResponse()->setBody("Error: " . $e->getMessage());
                return;
            }
            $array = [];
            foreach ($results as $result) {
                $array[] = $result;
            }
            $this->getResponse()->setBody(json_encode($array));
        } else {
            $this->getResponse()->setHttpResponseCode(500);
            $this->getResponse()->setBody("Only select queries are supported");
        }
    }

    function startsWith($haystack, $needle)
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    /**
     * @todo this is a vague idea, not in use
     */
    public function eraseThisCache() {
        // erase the cache of a certain category or product

        Mage::app()->cleanCache(array(Mage_Catalog_Model_Category::CACHE_TAG.'_1234'));

        Mage::getSingleton('index/indexer')->processEntityAction(
            $category, Mage_Catalog_Model_Category::ENTITY,
            Mage_Index_Model_Event::TYPE_SAVE
        );
    }
}