<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

$env = $app->detectEnvironment(function () use ($app) {
    $environmentPath = __DIR__ . '/../.env';
    $setEnv = trim(file_get_contents($environmentPath));
    if($setEnv == 'local'){
        $app->loadEnvironmentFrom('.env.local'); //本地开发
    }else if($setEnv == 'test'){
        $app->loadEnvironmentFrom('.env.test'); //测试环境
    }else if($setEnv == 'release'){
        $app->loadEnvironmentFrom('.env.release'); //预发环境
    }else if($setEnv == 'product'){
        $app->loadEnvironmentFrom('.env.product'); //线上环境
    }else{
        $app->loadEnvironmentFrom('.env.product'); // 默认
    }
});


return $app;
