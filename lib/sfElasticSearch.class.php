<?php

require_once dirname(__FILE__).'/vendor/ElasticSearchClient/ElasticSearchClient.php';

class sfElasticSearch
{

    protected static $instance;

    public static function getInstance()
    {
        if(!self::$instance){
            self::$instance = new ElasticSearchTransportHTTP("localhost", 9200);
        }
        return self::$instance;
    }

    /**
     *
     * @param String $indexName
     * @param String $type
     * @return ElasticSearchClient
     */
    public static function getIndex($indexName, $type)
    {
        return new ElasticSearchClient(self::getInstance(),$indexName, $type);
    }

    
}