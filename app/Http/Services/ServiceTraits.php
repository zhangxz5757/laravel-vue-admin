<?php

namespace App\Http\Services;

use App\Utils\ContainerUtils;

trait ServiceTraits
{


    /**
     * @return WebConfigService
     */
    public function getWebConfigService()
    {
        return ContainerUtils::getClass(WebConfigService::class);
    }
}
