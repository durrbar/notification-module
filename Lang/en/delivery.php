<?php

return [
    // ** Delivery Scheduled Notifications **
    'scheduled' => [
        // Mail Subject
        'subject' => 'Your Delivery Has Been Scheduled',

        // Mail Greeting
        'mail_greeting' => 'Hello :name,',

        // Scheduled Message (for SMS and database message)
        'message' => 'A delivery # :delivery has been scheduled for your order # :order.',

        // View Order Details Action Text
        'view_order_details' => 'View Order Details',

        // Mail Footer
        'mail_footer' => 'Thank you for shopping with us!',

        // Mail Salutation
        'mail_salutation' => 'Best regards, The Team',

        // Database Title
        'database_title' => 'Delivery Scheduled',

        // Category
        'category' => 'Order',
    ],

    // ** Delivery Completed Notifications **
    'completed' => [
        // Mail Subject
        'subject' => 'Your Delivery Has Been Completed',

        // Mail Greeting
        'mail_greeting' => 'Hello :name,',

        // Completed Message (for SMS and database message)
        'message' => 'A delivery # :delivery has been completed for your order # :order.',

        // View Order Details Action Text
        'view_order_details' => 'View Order Details',

        // Mail Footer
        'mail_footer' => 'Thank you for shopping with us!',

        // Mail Salutation
        'mail_salutation' => 'Best regards, The Team',

        // Database Title
        'database_title' => 'Delivery Completed',

        // Category
        'category' => 'Order',
    ],
];
