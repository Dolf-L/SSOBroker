SSO Broker
===
### Installation
Require this package in your composer.json and run composer update.
```
    "require": {
        "dolf/sso": "dev"
    }
    
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "dolf/sso",
                "version":"dev",
                "dist": {
                    "url": "https://github.com/Dolf-L/packages/tree/master/dolf/sso",
                }
            }
        }
    ]
```

Make your User table, it mast contain four fields 'email', 'first_name', 'last_name', 'last_ip', 
than your can add your custom fields to the table.
Your User Model should extends Illuminate\Foundation\Auth\User class
Run migration


###Configuration
Add to .env
```
    API_KEY = ... //same API_KEY as in SSO Server
    SERVER_URL = ...
    BROKER_URL = ...
```
###Usage 
Look at examples

