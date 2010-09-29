<?php

require_once(dirname(__FILE__).'/sfElasticSearchBaseTask.class.php');

class sfElasticSearchInitializeTask extends sfElasticSearchBaseTask
{

    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name')
        ));

        $this->addOptions(array(
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'search')
        ));

        $this->aliases = array('es-init');
        $this->namespace = 'es';
        $this->name = 'initialize';
        $this->briefDescription = 'Initializes the ElasticSearch configuration files';

        $this->detailedDescription = <<<EOF
The [es:intialize|INFO] initializes the configuration files for your application.

This task will simply create a skeleton of the [elasticsearch.yml|COMMENT] file to get you started
with using ElasticSearch.  Do not run this task if you are upgrading.

If current elasticsearch.yml is newer than the skeleton files, then nothing is done.
EOF;
    }

    public function  execute($arguments = array(), $options = array())
    {
        $app = $arguments['application'];
        $this->checkAppExists($app);
        $this->standardBootstrap($app, $options['env']);

        $skeletonDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'skeleton';
        $projectConfig = sfConfig::get('sf_config_dir') . DIRECTORY_SEPARATOR . 'elasticsearch.yml';

        $this->getFilesystem()->copy($skeletonDir.DIRECTORY_SEPARATOR.'project'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'elasticsearch.yml', $projectConfig);

    }

}