#!/bin/sh
# First-run installer for the local Docker stack. Safe to run more than once.
set -e

cd /var/www/html

# Wait for the WordPress container to copy core files into the shared volume.
i=0
until [ -f wp-config.php ] && [ -f wp-includes/version.php ]; do
	i=$((i + 1))
	if [ "$i" -gt 60 ]; then
		echo "WordPress files did not appear in time." >&2
		exit 1
	fi
	sleep 2
done

if ! wp core is-installed 2>/dev/null; then
	wp core install \
		--url="${SITE_URL}" \
		--title="Signal & Shield Consulting" \
		--admin_user=admin \
		--admin_password=admin \
		--admin_email=hello@signalshield.example \
		--skip-email
fi

wp option update blogdescription "Wireless, network and cybersecurity consulting in Doha, Qatar"
wp option update timezone_string "Asia/Qatar"
wp option update date_format "j F Y"
wp rewrite structure '/%postname%/' --hard

wp theme activate signal-shield
wp plugin activate signal-shield-core

# Remove default sample content.
wp post delete 1 2 3 --force >/dev/null 2>&1 || true
wp comment delete 1 --force >/dev/null 2>&1 || true

# Create the site pages and set the front page.
wp signal-shield setup

echo ""
echo "Done. Visit ${SITE_URL} (admin: ${SITE_URL}/wp-admin, admin / admin)."
