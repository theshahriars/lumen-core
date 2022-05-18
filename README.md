# lumen-core

### Installation guide:

Initialize Gpdevs\Lumen\Application class inside boostrap/app.php
```
$app = new \Gpdevs\Lumen\Application(
    dirname(__DIR__)
);
```

Load/register configurations:
```
$app->loadConfigurations($app, [
    'app'
]);
```


Add default configuration environment in .env file:
```
APP_CONFIG_ENV=prod # Values: 'local', 'mock', 'dev', 'stg', 'prod'
```
