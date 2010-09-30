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
        $action = $arguments['action'];
        $env = $options['env'];

        switch ($action) {
            case 'start':
                $this->start($env, $options);
                break;

            case 'stop':
                $this->stop($env, $options);

                break;

            case 'restart':
                $this->stop($env, $options);
                $this->start($env, $options);
                break;

            case 'status':
                $this->status($env, $options);
                break;
        }
    }

    protected function start($env, $options = array())
    {
        if ($this->isRunning($env)) {
            throw new sfException('Server is running, cannot start (pid file : ' . $this->getPidFile($env) . ')');
        }

        $command = sprintf('%s/plugins/sfElasticSearchPlugin/lib/vendor/elasticsearch/bin/elasticsearch -Des.config=%s/config/elasticsearch.yml  -p %s',
                        sfConfig::get('sf_root_dir'),
                        sfConfig::get('sf_root_dir'),
                        $this->getPidFile($env)
        );

        $this->logSection('exec ', $command);
        exec($command, $op);

        $pid = file_get_contents($this->getPidFile($env));

        $this->logSection("elasticsearch", "Server started with pid : " . $pid);
    }

    public function stop($env, $options = array())
    {
        if (!$this->isRunning($env)) {

            throw new sfException('Server is not running');
        }

        $pid = file_get_contents($this->getPidFile($env));

        if (!($pid > 0)) {

            throw new sfException('Invalid pid provided : ' . $pid);
        }

        if (method_exists($this->getFilesystem(), 'execute')) { // sf1.3 or greater
            $this->getFilesystem()->execute("kill -15 " . $pid);
        } else {
            $this->getFilesystem()->sh("kill -15 " . $pid);
        }

        unlink($this->getPidFile($env));
    }

    public function isRunning($env, $options = array())
    {

        return @file_exists($this->getPidFile($env));
    }

    public function getPidFile($env)
    {
        $file = sprintf('%s/plugins/sfElasticSearchPlugin/lib/vendor/elasticsearch/work/%s.pid',
                        sfConfig::get('sf_root_dir'),
                        $env
        );

        return $file;
    }

}
