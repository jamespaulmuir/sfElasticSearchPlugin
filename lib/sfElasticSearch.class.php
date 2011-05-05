<?php


class sfElasticSearch
{
    protected static $instance;
    protected static $registered = false;

    public static function register()
    {
        if(!self::$registered){

            set_include_path( realpath(dirname(__FILE__) . '/vendor/Elastica/lib') .  PATH_SEPARATOR . get_include_path());

            sfAutoload::register(array('sfElasticSearch', 'autoload'));
            self::$registered = true;
        }
    }


    public static function autoload($class)
    {
       $file = str_replace('_', '/', $class) . '.php';
       require_once $file;
    }




    public static function getInstance()
    {
        if(!self::$instance){
            self::$instance = new Elastica_Client();
        }
        return self::$instance;
    }

    /**
     * @static
     * @param  $query
     * @param  $index
     * @return Elastica_ResultSet
     */
    public static function search($query, $index)
    {
        $q = new Elastica_Query($query);
        return $index->search($q);
    }

    public static function ensureMapping($index, $type, $mapping)
    {
       $type = self::getType($index, $type);

        try {
            $current_mapping = $type->getMapping();
            if(!isset($current_mapping['message']) || $current_mapping['message'] == '{}'){
                throw new Exception;
            }
        }
        catch(Exception $e){
            $type->setMapping($mapping);
        }
    }

    /**
     * @static
     * @param Array $data
     * @param  $id
     * @param Elastica_Type $type
     * @return Elastica_Response
     */
    public static function addDocument($data, $id, Elastica_Type $type)
    {
        $doc =  new Elastica_Document($id, $data);
        return $type->addDocument($doc);
    }

    /**
     *
     * @param String $indexName
     * @param String $type
     * @return Elastica_Type
     */
    public static function getType($index, $type)
    {
        self::register();

        $index = self::getInstance()->getIndex($index);
        try {
            $index->create();
        }
        catch(Exception $e){
            // index already exists
        }
        $type = $index->getType($type);
        return $type;
    }

    
}