# Getting started

### Pre-requirements
---
```
{
 "php": "^8.1",
 "composer": "^2.5.8",
 "scoop": "",
 "symfony-cli": ""
}
```

### Run
---
```
git clone https://github.com/JordanPavlev/AppointmentsDemo.git

composer install

```

Make sure the database user has the needed permissions.
File `.env` should be updated with variables(or create `.env.local` in the root of project): 
- `DATABASE_URL=mysql://username:password@localhost:3306/database_name`
- `APP_ENV=dev`

Now update the database schema and load the security accounts. The only way to create new account is with (fixtures, using some REPL or direct insert into database).

```
php bin/console doctrine:schema:create

php bin/console doctrine:fixtures:load

php bin/console cache:clear

```

# Deployment

root dir should be point to `public/index.php` <br>
APP_ENV should switch to `production`

### For container deployement look to compose.yaml

# Preview
![Alt text](https://i.imgur.com/udfiC7G.png)
