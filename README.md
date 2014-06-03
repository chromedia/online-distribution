online-distribution
===================

Ecommerce website code for self-hosted online stores

##Status

- Work in Progress

##Basics

- Written with PHP, Javascript, HTML, CSS

##Resources

- [Apache HTTP Server Official Docs](https://httpd.apache.org/)
- [PHP Official Docs](http://www.php.net/)
- [MySQL Official Docs](http://dev.mysql.com/)
- [Mozilla's Javascript Docs](https://developer.mozilla.org/en-US/docs/Web/JavaScript?redirectlocale=en-US&redirectslug=JavaScript)
- [JQuery Official Docs (JS)](http://jquery.com/)
- [Foundation by Zurb (HTML/CSS/JS Package)](http://foundation.zurb.com/)

- [Htaccess Guide](http://htaccess-guide.com/)

##Development Tools

- Sublime Text - Text Editor
- [FileZilla - FTP Client](https://filezilla-project.org/)
- [Firebug - Web Development Tool for Firefox Browser](https://getfirebug.com/whatisfirebug)

##Architecture

Main computer languages used:

- PHP - general-purpose server-side scripting
- Javascript - dynamic on-page scripting
- HTML - webpage markup
- CSS - webpage styling

File and folder organization:

- Mainly MVCL (Model-View-Controller-Language)
- View contains CSS/HTML/JS
- Model/Controller contains PHP
- Language contains PHP assigning variables to text strings

Data organization:

- Files on server
- MySQL database on server

Code style:

- Object oriented programming with PHP, heavy use of ajax with JQuery

##Upload Folder Basics

```
/admin
/catalog
/download - empty storage
/image
/install
/system
.htaccess
config.php
index.php
php.ini
```

- The system folder has foundational logic and filepath code
- The admin folder is for the back-end dashboard, where administrators go to fulfill orders and modify store settings
- The catalog folder is for the front-end store, what the users see
- The install folder is for installing the store (check server requirements, configure filepaths, set admin username/password, MySQL database connection, ...)

```
config.php
/admin/config.php
```

- Config files contain defined filepaths and defined usernames/passwords/keys

```
catalog/view/default/stylesheet/
admin/view/stylesheet/
install/view/stylesheet/
```

- Stylesheet folders contain CSS files for styling the front-end, back-end, and installation pages

```
image/
catalog/view/default/image/
admin/view/image/
install/view/image/
```

- Image folders store raw image files

```
system/cache/ - empty storage
system/config/ - empty storage
system/database/
system/engine/
system/helper/
system/library/
system/logs/ - empty storage
```

- The engine folder contains top class definitions for controller, model, ...

##Products, Cart, Checkout Basics

- The product page retrieves product data from the database
- Add to cart button will send product ID, quantity, unit price, profiles, options via POST to the add function in cart.php
- The add function will use the product ID to retrieve product data from the database, then add the product variables/arrays to the cart array

## Integrations

- Stripe is used for payment processing
- Shippo is used for shipment label purchasing with major carriers
- Their API libraries are in the system library folder

```
system/library/stripe/
```

- Public and private keys must be defined somewhere like the config.php

```
// Stripe
define('STRIPE_PRIVATE_KEY', 'KEY_HERE');
define('STRIPE_PUBLIC_KEY', 'KEY_HERE');
```

##Credits

- Based on opencart codebase. Thanks OpenCart team! [OpenCart Website](http://www.opencart.com/)