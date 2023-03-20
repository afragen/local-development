<?php return array(
    'root' => array(
        'name' => 'afragen/local-development',
        'pretty_version' => 'dev-develop',
        'version' => 'dev-develop',
        'reference' => '33299cfd53246d4e75e3a123e7a463ad5405fa5c',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'afragen/local-development' => array(
            'pretty_version' => 'dev-develop',
            'version' => 'dev-develop',
            'reference' => '33299cfd53246d4e75e3a123e7a463ad5405fa5c',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'afragen/singleton' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => '5a5f348e798149d342b2a3ccdadec75448214d41',
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
