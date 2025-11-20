#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

# Set ownership for upload directories.
# The chown command changes the owner of our upload folders to www-data,
# which is the user Apache runs as.
chown -R www-data:www-data /var/www/html/public/assets/img
chown -R www-data:www-data /var/www/html/cvs

# Execute the default command for the image (in this case, start Apache).
exec "$@"