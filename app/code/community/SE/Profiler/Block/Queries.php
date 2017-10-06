<?php


class SE_Profiler_Block_Queries extends SE_Profiler_Block_Attributes
{
    private $queries;

    /**
     * SE_Profiler_Block_FlyMeTo constructor.
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->setTemplate('se/profiler/queries.phtml');
    }

    public function getTabText()
    {
        return "SQL";
    }

    public function getElementName()
    {
        return "sql";
    }

    /**
     * @return Zend_Db_Profiler_Query[]
     */
    public function getQueries()
    {
        if ($this->queries == null) {
            /** @var Zend_Db_Profiler $profiler */
            // replace getQuery with boundparams
            $profiler = Mage::getSingleton('core/resource')->getConnection('core_write')->getProfiler();
            /** @var Zend_Db_Profiler_Query[] $queryProfilers */
            $queryProfilers = $profiler->getQueryProfiles();
            $totalTime = 0;
            if (isset($queryProfilers) && count($queryProfilers) > 0 && $queryProfilers != null) {
                foreach ($queryProfilers as $queryProfiler) {
                    $query = $queryProfiler->getQuery();
                    $queryParameters = $queryProfiler->getQueryParams();
                    foreach ($queryParameters as $key => $value) {
                        if (strpos($key, "date") > 0) {
                            $value = "'" . $value . "'";
                        }
                        $query = str_replace($key, $value, $query);
                    }
                    $queryProfiler->fullQuery = $query;
                    $queryProfiler->msTime = round($queryProfiler->getElapsedSecs() * 1000, 2);
                    $totalTime += $queryProfiler->getElapsedSecs();
                    $queryProfiler->isSlow = $queryProfiler->msTime > 2;
                }
                $totalTime = round($totalTime * 1000, 2);
                $average = round($totalTime / count($queryProfilers), 2);


                $this->queries = array("profilers" => $queryProfilers, "totalTime" => $totalTime, "averageTime" => $average);
            } else {
                $this->queries = array();
            }
        }
        return $this->queries;
    }

    public function showQueries()
    {
        return true;
    }

    public function getNumberOfQueries()
    {
        return count($this->queries["profilers"]);
    }

    public function getTabSubtext()
    {
        return $this->getNumberOfQueries();
    }
}