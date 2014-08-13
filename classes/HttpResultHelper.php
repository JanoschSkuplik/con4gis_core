<?php

namespace c4g;

/**
 * Provides shortcuts to common http error results.
 */
class HttpResultHelper
{
    public static function BadRequest()
    {
        self::EndRequest('HTTP/1.1 400 Bad Request');
    } 
    
    public static function NotFound()
    {
        self::EndRequest('HTTP/1.1 404 Not Found');
    } 
    
    public static function MethodNotAllowed()
    {
        self::EndRequest('HTTP/1.1 405 Method Not Allowed');
    } 
    
    public static function InternalServerError() 
    {
        self::EndRequest('HTTP/1.1 500 Internal Server Error');
    }
    
    protected static function EndRequest($strHeader) {
        header($strHeader);
        die;
    }
}
