<?php return array(
    'root' => array(
        'name' => 'afragen/local-development',
        'pretty_version' => 'dev-develop',
        'version' => 'dev-develop',
        'reference' => '482449a5f0a789bb27f09dfbdfad626139b778f5',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'afragen/local-development' => array(
            'pretty_version' => 'dev-develop',
            'version' => 'dev-develop',
            'reference' => '482449a5f0a789bb27f09dfbdfad626139b778f5',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'afragen/singleton' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => '7bc443172710d85c45dd29196af7e2309d64d941',
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
