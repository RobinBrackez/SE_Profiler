<?php

/**
 * Rewrite SOLR Bridge module
 *
 * Class SE_Profiler_Model_Solrsearch_Resource_Solr
 *
 */
class SE_ProfilerSeSolrsearch_Model_Resource_Solr extends SE_Solrsearch_Model_Resource_Solr
{
    public function doRequest($url, $postFields = null, $type='array')
    {
        $output = parent::doRequest($url, $postFields, $type);
        $_SESSION["se_profiler_curl"][$url] = $output;
        return $output;
    }
}