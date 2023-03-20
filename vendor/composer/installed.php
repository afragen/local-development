<?php return array(
    'root' => array(
        'name' => 'afragen/local-development',
        'pretty_version' => 'dev-master',
        'version' => 'dev-master',
        'reference' => '33b40b905bb6effd656e5318949cf6ec78978c8e',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'afragen/local-development' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => '33b40b905bb6effd656e5318949cf6ec78978c8e',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'afragen/singleton' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => '5b6534c51bc867d235e74fa4bf7d451c43bfc1fc',
            'type' => 'library',
            'install_path' => __DIR__ . '/../afragen/singleton',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'wp-cli/wp-config-transformer' => array(
            'pretty_version' => 'v1.3.1',
            'version' => '1.3.1.0',
            'reference' => 'c5b5349b86a3eea6c8a3f401f556f21a717aa80e',
            'type' => 'library',
            'install_path' => __DIR__ . '/../wp-cli/wp-config-transformer',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
