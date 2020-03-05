# jet-wp-backup
A Backup and Restore Plugin for WordPress

## Usage

Pretty straightforward. Just download the zip and install it in the Admin panel.

## Configuration

As-is, it'll try to store backups in a (newly-created) 'jet-wp-backup' folder under 'uploads'. This is because it was developed for an Openshift installation that will leave that directory alone on deployment. (Storing in the plugin's folder - the other option that is currently commented out in config.php - results in backups getting overwritten upon deployment.

## Looking Forward

Upcoming developments will include:
1. Develop a service to host backups remotely.
1. Develop a restore function. (Right now you'd just need to manually run the sql script and replace the uploads folder.)
