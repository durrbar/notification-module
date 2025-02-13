<?php

return [
    // ** Invoice Paid Notification **
    'paid' => [
        // Mail Subject
        'subject' => 'আপনার ইনভয়েস পরিশোধিত হয়েছে',

        // Mail Greeting
        'mail_greeting' => 'হ্যালো :name,',

        // Paid Message (for SMS and database message)
        'message' => 'আপনার ইনভয়েস #:invoice সফলভাবে পরিশোধিত হয়েছে অর্ডার #:order জন্য।',

        // View Invoice Action Text
        'view_invoice' => 'ইনভয়েস দেখুন',

        // Mail Footer
        'mail_footer' => 'আপনার ব্যবসার জন্য ধন্যবাদ!',

        // Mail Salutation
        'mail_salutation' => 'শুভেচ্ছান্তে, টিম',

        // Database Title
        'database_title' => 'ইনভয়েস পরিশোধিত',

        // Category
        'category' => 'পেমেন্ট',
    ],

    // ** Invoice Created Notification **
    'created' => [
        // Mail Subject
        'subject' => 'আপনার ইনভয়েস তৈরি হয়েছে',

        // Mail Greeting
        'mail_greeting' => 'হ্যালো :name,',

        // Created Message (for SMS and database message)
        'message' => 'আপনার ইনভয়েস #:invoice অর্ডার #:order এর জন্য তৈরি হয়েছে।',

        // View Invoice Action Text
        'view_invoice' => 'ইনভয়েস দেখুন',

        // Mail Footer
        'mail_footer' => 'আপনার ব্যবসার জন্য ধন্যবাদ!',

        // Mail Salutation
        'mail_salutation' => 'শুভেচ্ছান্তে, টিম',

        // Database Title
        'database_title' => 'ইনভয়েস তৈরি হয়েছে',

        // Category
        'category' => 'পেমেন্ট',
    ],
];
