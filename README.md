# client

## Installation
Nette directories & bash scripts:  
```
chmod a+w src/temp src/log tests/tmp
chmod +x bin/*
```

#### PHP packages:  
```
composer install
```

#### NPM & Bower packages:
```
npm install --global yarn gulp
yarn
gulp
```

#### Database:  
```
cp src/app/config/config.local.sample.neon src/app/config/config.local.neon
```
In config.local.neon edit:
```
user
password
dbname
```
Create empty database `dbname`.

Let Doctrine generate tables:
```
./bin/r.sh orm:schema-tool:create
```
Manually import countries into database, e.g.:
```
mysql -u user -p dbname < res/countries.sql
```
