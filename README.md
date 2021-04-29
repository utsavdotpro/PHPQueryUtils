# PHPQueryUtils
Simple `Query` class to easily and quickly write/execute MySQLi queries.  

You no more need to write specific queries for every table and every operation. Just create a data object pass with your table name through appropriate function and voila, it's done!  

---
  
Checkout different operations available:

  - [raw](#raw): run any query, get data array
  - [rawForResult](#rawforresult): run any query, get result object
  - [select](#select): get first row from table as object
  - [selectWhere](#selectwhere): get any row from table as object
  - [insert](#insert): add new row to table
  - [insertMultiple](#insertMultiple): add new rows to table
  - [replace](#replace): add new or replace existing row in table
  - [delete](#delete): delete any row from table
  - [updateWhere](#updatewhere): update any row in table
  - [updateMultiple](#updatemultiple): update multiple rows in the table
  - [iterateOnResult](#iterateonresult): loop on selected rows from table, with empty callback
  - [truncate](#truncate): delete all rows from table
  - [upsert](#upsert): update or insert row in table

---

## raw
`raw($query)`  

Some queries are just too complex to create objects for, just write it yourself and pass it to the raw()

````php
Query::raw(
  "SELECT e.*, a.level FROM employees e
  LEFT OUTER JOIN authorization a ON a.emp_id=e.id
  WHERE e.id=12"
);


// returns: boolean || data array
````

````sql
/* query executed: whatever query you wrote */
SELECT e.*,
       a.level
FROM employees e
LEFT OUTER JOIN
AUTHORIZATION a ON a.emp_id=e.id
WHERE e.id=12
````

## rawForResult
`rawForResult($query)`  

Not for general use but in case you want the result object instead of data array, rawForResult() is your buddy

````php
Query::rawForResult(
  "SELECT e.*, a.level FROM employees e
  LEFT OUTER JOIN authorization a ON a.emp_id=e.id
  WHERE e.id=12"
);

// returns: boolean || result object
````

````sql
/* query executed: whatever query you wrote */
SELECT e.*,
       a.level
FROM employees e
LEFT OUTER JOIN
AUTHORIZATION a ON a.emp_id=e.id
WHERE e.id=12
````

## select
`select($tableName)`  

Pass the name of your table and get an data object of first row. Useful when selecting from config tables

````php
Query::select("settings");

// returns: data object
````


````sql
/* query executed */
SELECT *
FROM settings
WHERE 1=1
````

## selectWhere
`selectWhere($tableName, $whereString, $whereIdValue = null)`  

Returns first array from the table where the rows match conditions in whereString.  
You can directly separate all your conditions with whatever relation you like `AND` or `OR` . Check the example below 
Comparing rows with id value is one of the most common use case so there's a special filter for it, just pass the id as a third parameter and it will add <i> AND \`id\`='$your_passed_id'</i> (see example below)

````php
Query::selectWhere(
  "employees",
  "`email`='example@email.com' AND `password`='@123'",
  1
);

// returns: data object
````

````sql
/* query executed */
SELECT *
FROM employees
WHERE `email`='example@email.com'
  AND `password`='@123'
  AND `id`='1'
````

## insert
`insert($table, $dataObject, $ignoreMode = false)`  

To add a new row to your table use insert(). First create your data object (see example below) and then just pass it to the function.  
If you want to run `INSERT IGNORE INTO` instead of `INSERT INTO`, just enable ignoreMode using the third parameter.

````php
$data = [
  "first_name" => "Abdul",
  "last_name" => "Kalam",
];

Query::insert("users", $data);  

// returns: boolean || integer (inserted row id)
````

````sql
/* query executed */
INSERT INTO users (`first_name`, `last_name`)
VALUES ('Abdul', 'Kalam')
````

## insertMultiple
`insertMultiple($table, $dataArray, $ignoreMode = false, $column = "", $columnId = 0)`  

To add multiple rows to your table use insertMultiple(). First create your data array (see example below) and then just pass it to the function.  
If you want to run `INSERT IGNORE INTO` instead of `INSERT INTO`, just enable ignoreMode using the third parameter.  
This is generally to be used to insert mappings, so if you want to pass the reference column id additionally, you can use the 4th and 5th parameters.

`````php
$data = [
  [
    "first_name" => "Abdul",
    "last_name" => "Kalam",
  ],
  [
    "first_name" => "C.V.",
    "last_name" => "Raman",
  ],
  [
    "first_name" => "Srinivasa",
    "last_name" => "Ramanujan",
  ],
];

Query::insertMultiple("users", $data);  

// returns: boolean
`````

`````sql
/* query executed */
INSERT INTO users (`first_name`, `last_name`)
VALUES ('Abdul', 'Kalam'),
       ('C.V.', 'Raman'),
       ('Srinivasa', 'Ramanujan')
`````

## replace
`replace($table, $dataObject)`  

To replace existing (or add new) row to your table, use replace()

`````php
$data = [
  "first_name" => "Abdul",
  "last_name" => "Kalam",
];

Query::replace("users", $data);  

// returns: boolean || integer (inserted row id)
`````

`````sql
/* query executed */
REPLACE INTO users (`first_name`, `last_name`)
VALUES ('Abdul', 'Kalam')
`````

## delete
`delete($table, $whereString, $whereIdValue = null)`  

Delete the rows from the table where the condition in whereString is matched.  
You can directly separate all your conditions with whatever relation you like `AND` or `OR` . Check the example below 
Comparing rows with id value is one of the most common use case so there's a special filter for it, just pass the id as a third parameter and it will add <i> AND \`id\`='$your_passed id'</i>

`````php
Query::delete(
  "employees",
  "`email`='example@email.com' AND `password`='@123'"
);

// returns: boolean
`````

`````sql
/* query executed */
DELETE
FROM employees
WHERE `email`='example@email.com'
  AND `password`='@123'
`````

## updateWhere
`updateWhere($table, $dataObject, $whereString, $whereIdValue = null)`  

First create a data object and pass it to the updateWhere() and the rows from the table where the condition in whereString is matched are updated.  
You can directly separate all your conditions with whatever relation you like `AND` or `OR`   
Comparing rows with id value is one of the most common use case so there's a special filter for it, just pass the id as a third parameter and it will add <i> AND \`id\`='$your_passed id'</i>

`````php
$data = [
  "first_name" => "Abdul",
  "last_name" => "Kalam",
];

Query::updateWhere(
  "users",
  "`email`='example@email.com'"
);

// returns: boolean
`````

`````sql
/* query executed */
UPDATE users
SET `first_name`='Abdul',
    `last_name`='Kalam'
WHERE `email`='example@email.com'
`````

## updateMultiple
`updateMultiple($table, $dataArray)`  

To update multiple rows in your table use updateMultiple(). First create your data array (see example below) and then just pass it to the function.  
  
**Note: for this to be able to work, your table must have at least one column with `PRIMARY` OR `UNIQUE` key and you must keep that column in the data array.**

`````php
$data = [
  [
    "id" => 1, // here id column has PRIMARY key
    "first_name" => "Abdul",
    "last_name" => "Kalam",
  ],
  [
    "id" => 2,
    "first_name" => "C.V.",
    "last_name" => "Raman",
  ],
  [
    "id" => 3,
    "first_name" => "Srinivasa",
    "last_name" => "Ramanujan",
  ],
];

Query::updateMultiple("users", $data);  

// returns: boolean
`````

`````sql
/* query executed */
INSERT INTO users (`id`, `first_name`, `last_name`)
VALUES (1, 'Abdul', 'Kalam'),
       (2, 'C.V.', 'Raman'),
       (3, 'Srinivasa', 'Ramanujan')
ON DUPLICATE KEY UPDATE id=VALUES(id),
  first_name=VALUES(first_name),
  last_name=VALUES(last_name)
`````

> We're using multi INSERT query here in the UPDATE mode so basically, if a column exists, it will be updated. And if it doesn't exists, it will be created.  
> So be sure that you only pass the column that already exist in the table until unless you **knowingly** don't want to.

## iterateOnResult
`iterateOnResult($query, $callback = null, $emptyCallback = null)`  

To select multiple rows from a table and doing operations on them use iterateOrResult()  
Pass whatever SELECT query you like and start working on them in the callback  
Data objects returned inside the parameter has two extra properties `numRows` (total number of rows available) and `hasNext` (if has more data in the list)   
To do a different operation when the data is empty, pass a empty callback as third parameter (see example below) 

`````php
Query::iterateOnResult(
  "SELECT e.*, a.level FROM employees e
  LEFT OUTER JOIN authorization a ON a.emp_id=e.id",
  function ($dataRow) {
    // do something with dataRow
  },
  function () {
    // show message like there's no data in the table
  }
);

// returns: void
`````

````sql
/* query executed: whatever query you wrote */
SELECT e.*,
       a.level
FROM employees e
LEFT OUTER JOIN
AUTHORIZATION a ON a.emp_id=e.id
````

## truncate
`truncate($tableName)`  

To truncate (remove all rows) from a table, use truncate()

`````php
Query::truncate("users");

// returns: boolean
`````

````sql
/* query executed */
TRUNCATE TABLE users;
````

## upsert
`upsert($tableName, $dataObject, $whereString)`  

To update or insert a row in the table, use upsert(). It internally calls the `insert()` if row doesn't exists else, it calls the `updateWhere()`.

`````php
Query::upsert("users", ["name"=>"Abdul"], "`name`='John'");

// returns: boolean || integer (inserted row id)
`````

## Features

 - Reliable
 - Lightweight
 - Easy to use and implement
 - Great code readability
 - Highly customizable

## Contribution
Want to contribute? Great fork this repo and create a pull request if you
- found any error
- want to add a new function
- upgraded an existing function

## Credits