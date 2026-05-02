# Wasmer Deployment Notes

This is a PHP + MySQL app. Uploading the PHP files alone is not enough: the app also needs the database from `joker_club (4).sql`.

## Database variables

`config/database.php` reads these environment variables when the app is deployed:

```text
DB_HOST
DB_PORT
DB_NAME
DB_USER or DB_USERNAME
DB_PASSWORD
```

If those variables are missing, it falls back to the local XAMPP/WAMP values:

```text
host: localhost
database: joker_club
user: root
password: empty
```

## Deployment checklist

1. Deploy this project folder to Wasmer.
2. Attach or create a MySQL database for the app.
3. Import `joker_club (4).sql` into that database.
4. Make sure the database environment variables above are present.
5. Redeploy or restart the app.

If you manage deployment with `app.yaml`, the database section should look like this:

```yaml
capabilities:
  database:
    engine: mysql
```

Use the region/database settings shown in your Wasmer dashboard if Wasmer asks for them.
