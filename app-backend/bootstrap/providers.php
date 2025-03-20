<?php

return [
    \App\Core\Providers\AppServiceProvider::class,

    /* Application Service Providers */
    \App\Domains\User\Providers\DomainServiceProvider::class,
    \App\Domains\Profile\Providers\DomainServiceProvider::class,

    /* Infra Service Providers */
    \App\Infra\Providers\HelperServiceProvider::class,

];
