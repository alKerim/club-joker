# Wasmer Deployment Notes

This is a PHP + MySQL app. The PHP files alone are not enough: the database schema and seed data in `joker_club.sql` must be imported into the MySQL database used by the deployed app.

## Environment Variables

`config/database.php` reads these variables:

```text
DB_HOST
DB_PORT
DB_NAME
DB_USER or DB_USERNAME
DB_PASSWORD
```

Local fallback values are still available for XAMPP/WAMP:

```text
DB_HOST=localhost
DB_NAME=joker_club
DB_USER=root
DB_PASSWORD=
```

## Wasmer Checklist

1. Deploy the project folder to Wasmer.
2. Keep `app.yaml` in the project root so Wasmer can attach MySQL.
3. Create or attach the MySQL database in Wasmer.
4. Import `joker_club.sql` into that MySQL database.
5. Make sure the database environment variables above are set.
6. Restart or redeploy the app.

The current `app.yaml` requests a MySQL database in `be-mons1`.

## Important Tiiny Host Note

`club-joker.tiiny.io` is a Tiiny Host URL. Tiiny is not the right target for this project because this app is multi-file PHP and requires MySQL.

Tiiny's own help docs say they support single PHP files, and that Tiiny cannot host your database by itself. Use Wasmer or another PHP + MySQL host for this project.
