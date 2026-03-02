<?php
/**
 * Configures wp-config.php with database credentials and fresh salts.
 * Usage: php docker/configure-wp.php /path/to/cms
 */

$cmsDir = $argv[1] ?? '/var/www/html/cms';
$configFile = $cmsDir . '/wp-config.php';

if (!file_exists($configFile)) {
    echo "Error: $configFile not found\n";
    exit(1);
}

$config = file_get_contents($configFile);

// Database credentials
$config = str_replace('database_name_here', 'wordpress', $config);
$config = str_replace('username_here', 'wpuser', $config);
$config = str_replace('password_here', 'wppass', $config);
$config = str_replace("'localhost'", "'db'", $config);

// Fetch fresh salts
$salts = file_get_contents('https://api.wordpress.org/secret-key/1.1/salt/');
if ($salts === false) {
    echo "Warning: Could not fetch salts from WordPress API. Using defaults.\n";
} else {
    // Remove placeholder salt definitions
    $config = preg_replace(
        "/define\(\s*'(AUTH_KEY|SECURE_AUTH_KEY|LOGGED_IN_KEY|NONCE_KEY|AUTH_SALT|SECURE_AUTH_SALT|LOGGED_IN_SALT|NONCE_SALT)'.*?\);\s*/s",
        '',
        $config
    );
    // Insert real salts before "stop editing" comment
    $config = str_replace(
        "/* That's all, stop editing!",
        $salts . "\n/* That's all, stop editing!",
        $config
    );
    echo "Salts generated.\n";
}

file_put_contents($configFile, $config);
echo "wp-config.php configured.\n";
