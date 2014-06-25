<?php

/**
 * Provides services for checkou
 */
class CheckoutService
{
    private static $instance;
    
    /**
     * Returns instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new CheckoutService();
        }

        return self::$instance;
    }

    /**
     * Process order
     */
    public function processOrder()
    {
        
    }

}
