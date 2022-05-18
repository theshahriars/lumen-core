<?php


namespace Gpdevs\Lumen;


class Application extends \Laravel\Lumen\Application
{
    /**
     * Load/register configurations
     *
     * @param Application $app
     * @param $configs
     * @param $configEnvs
     */
    public function loadConfigurations(Application $app, $configs, $configEnvs = [])
    {
        if (!count($configEnvs)) $configEnvs = ['local', 'mock', 'dev', 'stg', 'prod'];

        $configEnv = $_SERVER['HTTP_X_APP_CONFIG_ENV'] ?? env('APP_CONFIG_ENV', 'prod');

        if (!in_array($configEnv, $configEnvs)) $configEnv = env('APP_CONFIG_ENV', 'prod');

        foreach ($configs as $config) {
            $app->configure($config, $configEnv);
        }
    }

    /**
     * Override configure method of \Laravel\Lumen\Application
     *
     * @param $name
     * @param $env
     */
    public function configure($name, $env = 'local'): void
    {
        if (isset($this->loadedConfigurations[$name])) {
            return;
        }

        $this->loadedConfigurations[$name] = true;

        if (strlen($env))
            $path = $env . DIRECTORY_SEPARATOR . $name;
        else
            $path = $name;

        $path = $this->getConfigurationPath($path);

        if ($path) {
            $this->make('config')->set($name, require $path);
        }
    }
}
