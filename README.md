# Getting started

### Pre-requirements
---
```
{
 "php": "^8.1"
}
```

### Run
---
```
git clone https://github.com/JordanPavlev/AppointmentsDemo.git

composer install

```

File `.env` should be updated with variables: 
- `DATABASE_URL=mysql://username:password@localhost:3306/database_name`
- `APP_ENV=production`

```
php bin/console doctrine:schema:create

php bin/console doctrine:fixtures:load

```
