<?php

/**
 * Provides services for order
 */
class OrderService
{
    private static $instance;
    
    /**
     * Returns instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderService();
        }

        return self::$instance;
    }

    /**
     * Saves order
     */
    public function saveOrder($data, $model)
    {
        
    }
}
