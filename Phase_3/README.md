## CS6400 Jaunty Jalopies

## Getting Started
These instructions the Jaunty Jalopies project up and running on your local machine for development and testing purposes. 

## Installation
Install Bitnami *AMP stack: [https://bitnami.com/stacks/infrastructure](https://bitnami.com/stacks/infrastructure)

Copy the phase 3 folder into: C:\Bitnami\wampstack-7.0.11-0\apache2\htdocs\
![ScreenShot](https://github.gatech.edu/cs6400-2021-03-fall/cs6400-2021-03-Team019/blob/master/Phase_3/img/gt_online_wamp_v7.png)

Now login as ‘root’ to phpMyAdmin: [http://127.0.0.1:80/phpmyadmin/](http://127.0.0.1:80/phpmyadmin/)

Now add a new username: "gatechUser" with password “gatech123” using phpMyAdmin 
(Select localhost and Data only privileges)
![ScreenShot](https://github.gatech.edu/cs6400-2021-03-fall/cs6400-2021-03-Team019/blob/master/Phase_3/img/add_sql_user.png)

## Configuring the application

```
define('DB_HOST', "localhost");
define('DB_PORT', "3306");
define('DB_USER', "gatechUser");
define('DB_PASS', "gatech123");
define('DB_SCHEMA', "cs6400_fa21_team019");
```

Then run the SQL script through phpMyAdmin --> Import to create the DB you need.

Either "team019_fa21_p3_complete_v7" or "team019_fa21_p3_complete_v7_demo" under sql folder. 

Then restart your Apache server:
Now launch the URL: 
[http://localhost:80/Phase_3/index.php](http://localhost:80/Phase_3/index.php)
 
Lastly, login with username and password below (prefilled): 
```
username: roland
password: roland
```

Note: by default, the queries are shown to the user as a learning tool.  To turn this off, flip the boolean flag on lib/common.php for the showQueries = false;

If needed, read the server logs:
Bitnami *AMP Stack Manager Tool --> Manager Server --> Configure --> Open Error Log:

### Congratulations!
You've successfully set up the Jaunty Jalopies project on your local development machine!

## Authors
* __Li Li__  email: [lli474@gatech.edu](mailto:lli474@gatech.edu)
* __Zihan Ma__  email: [zma328@gatech.edu](mailto:zma328@gatech.edu)
* __Dong Xu__  email: [dxu329@gatech.edu](mailto:dxu329@gatech.edu)
* __Zhi Zheng__  email: [zzheng329@gatech.edu](mailto:zzheng329@gatech.edu)

