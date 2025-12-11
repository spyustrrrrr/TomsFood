<?php

return [
    /**
     * File: config/midtrans.php
     * Konfigurasi Midtrans Payment Gateway
     */
    
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', 'your_merchant_id'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'your_client_key'),
    'server_key' => env('MIDTRANS_SERVER_KEY', 'your_server_key'),
    
    // Set to true for production, false for sandbox/testing
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    
    // Set to true to enable 3D Secure
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    
    // Notification URL (untuk callback dari Midtrans)
    'notification_url' => env('APP_URL') . '/payment/notification',
    
    // Sanitized (set to true for production)
    'is_sanitized' => true,
];