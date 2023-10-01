<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:ambielecki/2023-divelog-api-v2.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('137.184.202.43')
    ->set('remote_user', 'ambielecki')
    ->set('deploy_path', '/var/www/2023-divelog-api');

// Hooks

after('deploy:failed', 'deploy:unlock');
