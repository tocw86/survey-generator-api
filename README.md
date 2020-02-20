NPS Api Installation guide

## Installation
### 1. Clone repository
```bash
git clone
```
### 1. Dependencies installation
Install project dependencies with composer using the following command in the project folder
```bash
composer install
```
### 3. Configuration
Use this command to copy the environment variables settings file.
```bash
cp .env.example .env
```
Then, open .env file and enter database credentials.

Run 
```bash
php bin/console doctrine:schema:create
```
 to create necessary database structure.

### 4. Finish


