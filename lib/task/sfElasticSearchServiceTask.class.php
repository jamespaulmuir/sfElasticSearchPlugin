<?php

require_once(dirname(__FILE__) . '/sfElasticSearchBaseTask.class.php');

class sfElasticSearchServiceTask extends sfElasticSearchBaseTask
{

    public function configure()
    {
        $this->addArguments(array(
            //new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
            new sfCommandArgument('action', sfCommandArgument::REQUIRED, 'The action name')
        ));

        $this->addOptions(array(
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod')
        ));

        $this->namespace = 'es';
        $this->name = 'service';
        $this->briefDescription = 'start or stop the ElasticSearch server (only *nix platforms)';
    }

    protected function execute($arguments = array(), $options = array())
    {
        //$app = $arguments['application'];
        $app = 'frontend';
        $action = $arguments['action'];
        $env = $options['env'];

        switch ($action) {
            case 'start':
                $this->start($app, $env, $options);
                break;

            case 'stop':
                $this->stop($app, $env, $options);

                break;

            case 'restart':
                $this->stop($app, $env, $options);
                $this->start($app, $env, $options);
                break;

            case 'status':
                $this->status($app, $env, $options);
                break;
        }
    }

    protected function start($env, $options = array())
    {
        $command = sprintf('%s/plugins/sfElasticSearchPlugin/lib/vendor/elasticsearch/bin/elasticsearch -f -Des.config=%s/config/elasticsearch.yml',
                        sfConfig::get('sf_root_dir'),
                        sfConfig::get('sf_root_dir')
        );

        $this->logSection('exec ', $command);
        exec($command, $op);

        
    }

}
