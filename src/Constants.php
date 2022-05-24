<?php


namespace Gpdevs\Lumen;


class Constants
{
    const AVAILABLE_ENVS = ['local', 'dev', 'development', 'mock', 'test', 'stg', 'staging', 'prod', 'production'];

    const ALLOWED_ENVS = ['local', 'dev', 'development', 'mock', 'test', 'stg', 'staging'];

    const RESTRICTED_ENVS = ['prod', 'production'];

    const DEFAULT_ENV = 'local';

    const PRODUCTION_ENVS = ['prod', 'production'];

    const ENV_MAP = [
        'local' => 'local',
        'dev' => 'dev',
        'development' => 'dev',
        'mock' => 'mock',
        'test' => 'mock',
        'stg' => 'stg',
        'staging' => 'stg',
        'prod' => 'prod',
        'production' => 'prod'
    ];

    const ENV_PROPERTY_NAME = 'APP_ENV';
}
