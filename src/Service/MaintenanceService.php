<?php

namespace App\Service;

class MaintenanceService
{
    private string $webmaster='webmaster@my-domain.org';

    /**
     * @return string
     */
    public function getWebmaster(): string
    {
        return $this->webmaster;
    }

}