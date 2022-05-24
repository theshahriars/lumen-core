<?php


namespace Gpdevs\Lumen;

class Application extends \Laravel\Lumen\Application
{
    /**
     * @var array $availableEnvs
     */
    protected $availableEnvs = Constants::AVAILABLE_ENVS;

    /**
     * @var array $allowedEnvs
     */
    protected $allowedEnvs = Constants::ALLOWED_ENVS;

    /**
     * @var array $restrictedEnvs
     */
    protected $restrictedEnvs = Constants::RESTRICTED_ENVS;

    /**
     * @var string $appEnv
     */
    protected $appEnv;

    /**
     * @var string $defaultEnv
     */
    protected $defaultEnv = Constants::DEFAULT_ENV;

    /**
     * @var string $productionEnvs
     */
    protected $productionEnvs = Constants::PRODUCTION_ENVS;

    /**
     * @var string $envMap
     */
    protected $envMap = Constants::ENV_MAP;

    /**
     * @var string $envPropertyName
     */
    protected $envPropertyName = Constants::ENV_PROPERTY_NAME;

    /**
     * @var bool $loadEnv
     */
    protected $loadEnv = false;

    /**
     * @param array $availableEnvs
     * @return Application
     */
    public function setAvailableEnvs(array $availableEnvs): Application
    {
        $this->availableEnvs = $availableEnvs;

        return $this;
    }

    /**
     * @param array $allowedEnvs
     * @return Application
     */
    public function setAllowedEnvs(array $allowedEnvs): Application
    {
        $this->allowedEnvs = $allowedEnvs;

        return $this;
    }

    /**
     * @param array $restrictedEnvs
     * @return Application
     */
    public function setRestrictedEnvs(array $restrictedEnvs): Application
    {
        $this->restrictedEnvs = $restrictedEnvs;

        return $this;
    }

    /**
     * @param string $defaultEnv
     * @return Application
     */
    public function setDefaultEnv(string $defaultEnv): Application
    {
        $this->defaultEnv = $defaultEnv;

        return $this;
    }

    /**
     * @param string $productionEnvs
     * @return Application
     */
    public function setProductionEnvs(string $productionEnvs): Application
    {
        $this->productionEnvs = $productionEnvs;

        return $this;
    }

    /**
     * @param string $envMap
     * @return Application
     */
    public function setEnvMap(string $envMap): Application
    {
        $this->envMap = $envMap;

        return $this;
    }

    /**
     * @param string $envPropertyName
     * @return Application
     */
    public function setEnvPropertyName(string $envPropertyName): Application
    {
        $this->envPropertyName = $envPropertyName;

        return $this;
    }

    /**
     * @param bool $loadEnv
     * @return Application
     */
    public function setLoadEnv(bool $loadEnv): Application
    {
        $this->loadEnv = $loadEnv;

        return $this;
    }

    /**
     * Load/register configurations
     *
     * @param Application $app
     * @param array $configs
     */
    public function loadConfigurations(Application $app, array $configs): void
    {
        $currentEnv = $this->appEnv = $app->environment();

        if (!in_array($currentEnv, $this->productionEnvs)) {

            $currentEnv = env($this->envPropertyName, $this->defaultEnv);

            if (!empty($_SERVER['HTTP_X_' . $this->envPropertyName]))
                $currentEnv = $_SERVER['HTTP_X_' . $this->envPropertyName];

            $currentEnv = !empty($this->envMap[$currentEnv])
                ? $this->envMap[$currentEnv]
                : $this->appEnv;

            if (!$this->checkIfInProductionOrRestricted($currentEnv)) {

                if ($this->loadEnv && in_array($currentEnv, $this->allowedEnvs)) {
                    $dotenv = \Dotenv\Dotenv::createMutable(base_path(), '.env.' . $currentEnv);
                    $dotenv->load();
                }

            } else {

                $currentEnv = $this->appEnv;

            }

        }

        foreach ($configs as $config) {
            $app->configure($config, $currentEnv);
        }
    }

    /**
     * Override configure method of \Laravel\Lumen\Application
     *
     * @param $name
     * @param $env
     */
    public function configure($name, $env = ''): void
    {
        if (in_array($env, $this->availableEnvs)) {

            if (isset($this->loadedConfigurations[$name]))
                return;

            $this->loadedConfigurations[$name] = true;

            if (strlen($env) && !$this->checkIfInProductionOrRestricted($env))
                $path = $env . DIRECTORY_SEPARATOR . $name;
            else
                $path = $name;

            $path = $this->getConfigurationPath($path);

            if ($path)
                $this->make('config')->set($name, require $path);

        }
    }

    /**
     * Check if application environment is in production or restricted for modifying the default configurations
     *
     * @param $env
     * @return bool
     */
    private function checkIfInProductionOrRestricted($env): bool
    {
        return in_array($this->appEnv, $this->productionEnvs) || ($env == $this->productionEnvs) || in_array($env, $this->restrictedEnvs);
    }
}
