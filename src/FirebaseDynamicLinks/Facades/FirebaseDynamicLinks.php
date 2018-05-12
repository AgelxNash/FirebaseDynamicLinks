<?php namespace AgelxNash\FirebaseDynamicLinks\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;

class FirebaseDynamicLinks extends BaseFacade
{

    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'FirebaseDynamicLinks';
    }
}
