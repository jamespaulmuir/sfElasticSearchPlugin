<?php

abstract class sfElasticSearchBaseTask extends sfBaseTask
{

    protected function bootstrapSymfony($app, $env, $debug = true)
    {
        $configuration = ProjectConfiguration::getApplicationConfiguration($app, $env, $debug);

        sfContext::createInstance($configuration);
    }

    protected function standardBootstrap($app, $env = 'search')
    {
        $this->bootstrapSymfony($app, $env, true);
    }

}
