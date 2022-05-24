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
APP_ENV=local # Values: 'local', 'dev', 'development', 'mock', 'test', 'stg', 'staging', 'prod', 'production'
```

Changing configuration environment with request header:
```
X-App-Env: mock
```
