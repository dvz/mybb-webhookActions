<?php

// core files
require MYBB_ROOT . 'inc/plugins/webhookActions/core.php';

// hook files
require MYBB_ROOT . 'inc/plugins/webhookActions/hooks_frontend.php';

// autoloading
spl_autoload_register(function ($path) {
    $prefix = 'webhookActions\\Action';
    $baseDir = MYBB_ROOT . 'inc/plugins/webhookActions/Action';

    if (strpos($path, $prefix) === 0) {
        $className = str_replace('\\', '/', substr($path, strlen($prefix)));
        $file = $baseDir . $className . '.php';

        if (file_exists($file)) {
            require $file;
        }
    }
});

// hooks
$plugins->add_hook('misc_start', '\\webhookActions\\Hooks\\misc_start');

function webhookActions_info()
{
    return [
        'name'          => 'Webhook Actions',
        'description'   => 'Runs operations basing on webhook data.',
        'website'       => '',
        'author'        => 'Tomasz \'Devilshakerz\' Mlynski',
        'authorsite'    => 'https://devilshakerz.com',
        'version'       => '1.0',
        'compatibility' => '18*',
    ];
}
