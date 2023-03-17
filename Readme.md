
# Easy Store

Store form data to database easily, comes with all basic features like - validation, error handling, json response.
Easy configuration.

&nbsp;





## Installation

Add files to your server root directory.


#### 1. Setup mysql
Create database and table, add columns to the table.

#### 2. Edit config.php file
Add mysql credentials and field names


Here 'field' means: the value of 'name' attribute of 'input/textarea/select' elements of html form and also the column names of the sql table.


For example, email and password are required fields, but gender can be optional so -

```php
$fields = [
   "user_email" => 1,
    "user_pass" => 1,
    "gender" => 0,
];
```

Where user_email and user_pass is the db column names and also values of name attribute of form inputs.

1 for required field and
0 for non-required field

#### use ``/easy-store.php`` as endpoint

#### All set! 🤞
&nbsp;

## Response Examples



```json
{
    "status": 0,
    "data": {
        "user_email": "user_email is a required field",
        "user_pass": "user_pass is a required field"
    }
}
```

```json
{
    "status": 1,
    "data": {
        "message": "Data saved successfully"
    }
}
```

```json
{
    "status": 0,
    "data": {
        "message": "error message"
    }
}
```
&nbsp;
#
![MIT License](https://img.shields.io/badge/License-MIT-brightgreen)

![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-brightgreen)

