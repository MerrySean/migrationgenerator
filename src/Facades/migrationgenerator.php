<?php

namespace MerrySean\migrationgenerator\Facades;

use Illuminate\Support\Facades\Facade;

class migrationgenerator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'migrationgenerator';
    }
}
