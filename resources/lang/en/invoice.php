<?php

return [
    // ** Invoice Paid Notification **
    'paid' => [
        // Mail Subject
        'subject' => 'Your Invoice Has Been Paid',

        // Mail Greeting
        'mail_greeting' => 'Hello :name,',

        // Paid Message (for SMS and database message)
        'message' => 'Your invoice #:invoice has been paid successfully for order #:order.',

        // View Invoice Action Text
        'view_invoice' => 'View Invoice',

        // Mail Footer
        'mail_footer' => 'Thank you for your business!',

        // Mail Salutation
        'mail_salutation' => 'Best regards, The Team',

        // Database Title
        'database_title' => 'Invoice Paid',

        // Category
        'category' => 'Payment',
    ],

    // ** Invoice Created Notification **
    'created' => [
        // Mail Subject
        'subject' => 'Your Invoice Has Been Generated',

        // Mail Greeting
        'mail_greeting' => 'Hello :name,',

        // Created Message (for SMS and database message)
        'message' => 'Your invoice #:invoice has been generated for order #:order.',

        // View Invoice Action Text
        'view_invoice' => 'View Invoice',

        // Mail Footer
        'mail_footer' => 'Thank you for your business!',

        // Mail Salutation
        'mail_salutation' => 'Best regards, The Team',

        // Database Title
        'database_title' => 'Invoice Generated',

        // Category
        'category' => 'Payment',
    ],
];
