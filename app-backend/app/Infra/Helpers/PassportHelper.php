<?php

use App\Infra\Enums\ProfileType;

if ( !function_exists('isGuardAdmin') ) {

    /**
     * @param string $date
     * @return bool
     */
    function isGuardAdmin(string $profile): bool
    {
        return $profile == ProfileType::Admin->value;
    }
}
