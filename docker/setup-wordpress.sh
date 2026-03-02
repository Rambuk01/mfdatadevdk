#!/bin/bash
# Downloads and configures WordPress in the cms/ subdirectory.
# Run this INSIDE the web container: docker compose exec web bash docker/setup-wordpress.sh

set -e

CMS_DIR="/var/www/html/cms"

if [ -f "$CMS_DIR/wp-config.php" ]; then
    echo "WordPress already installed at $CMS_DIR"
    exit 0
fi

echo "Downloading WordPress..."
curl -sO https://wordpress.org/latest.tar.gz
tar -xzf latest.tar.gz
mv wordpress "$CMS_DIR"
rm latest.tar.gz

echo "Creating wp-config.php..."
cp "$CMS_DIR/wp-config-sample.php" "$CMS_DIR/wp-config.php"

# Configure database and salts via PHP (avoids sed escaping issues)
php /var/www/html/docker/configure-wp.php "$CMS_DIR"

# Set file permissions
chown -R www-data:www-data "$CMS_DIR"

echo ""
echo "WordPress installed!"
echo "Visit http://localhost:8000/cms/ to complete setup."
echo ""
