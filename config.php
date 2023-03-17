<?php

const DB_HOST = 'localhost';
const DB_PORT = 3306;


const DB_NAME = "DATABASE_NAME";
const DB_TABLE_NAME = "TABLE_NAME";
const DB_USERNAME = "root";
const DB_PASSWORD = "";


$fields = [
    "field1" => 1,
    "field2" => 0,
    "field3" => 1,
    "field4" => 0,
];



/*
Here, Field is the value of 'name' attribute of 'input/textarea/select' elements of html form
and the columns of the sql table.
For example, email and password are required fields, but gender can be optional so -

$fields = [
    "user_email" => 1,
    "user_pass" => 1,
    "gender" => 0,
];

1 for required field
0 for non required field
*/


