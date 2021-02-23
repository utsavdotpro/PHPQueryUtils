# PHPQueryUtils
Simple <b>Query</b> class to easily and quickly write/execute MySQLi queries. You no more need to write specific queries for every table. Just create a data object pass with your table name through appropriate function and voila, it's done!  
  
Checkout different operations available:


## raw
**raw($query)**  

Some queries are just too complex to create objects for, just write it yourself and pass it to the raw() and get the data array (or boolean, if that's the case)

    Query::raw(
      "SELECT e.*, a.level FROM employees e
      LEFT OUTER JOIN authorization a ON a.emp_id=e.id
      WHERE e.id=12"
    );

    // returns: boolean or data array

## rawForResult
**rawForResult($query)**  

Not for general use but in case you want the result object instead of data array, rawForResult() is your buddy

    Query::rawForResult(
      "SELECT e.*, a.level FROM employees e
      LEFT OUTER JOIN authorization a ON a.emp_id=e.id
      WHERE e.id=12"
    );

    // returns: boolean or result object

## select
**select($tableName)**  

Pass the name of your table and get an data object of first row. Useful when selecting from config tables

    Query::select("settings");

    // query: SELECT * FROM settings
    // returns: data object

## selectWhere
**selectWhere($tableName, $whereString, $whereIdValue = null)**  

Returns first array from the table where the rows match conditions in whereString.  
You can directly separate all your conditions with whatever relation you like `AND` or `OR` . Check the example below 
Comparing rows with id value is one of the most common use case so there's a special filter for it, just pass the id as a third parameter and it will add <i> AND \`id\`='$your_passed id'</i>

    Query::selectWhere(
      "employees",
      "`email`='example@email.com' AND `password`='@123'"
    );

    // query: SELECT * FROM employees WHERE `email`='example@email.com' AND `password`='@123'
    // returns: data object

## insert
**insert($table, $dataObject, $ignoreMode = false)**  

To add a new row to your table use insert(). First create your data object (as shown in the example) and then just pass it the function.  
If you want to run `INSERT IGNORE INTO` instead of `INSERT INTO`, just enable ignoreMode using the third parameter.

    $data = [
      "first_name" => "Abdul",
      "last_name" => "Kalam",
    ];
    Query::insert("users", $data);  

    // query: INSERT INTO users (`first_name`, `last_name`) VALUES ('Abdul', 'Kalam')
    // returns: boolean or integer (inserted row id)

## replace
**replace($table, $dataObject)**  

To replace existing (or add new) row to your table, use replace()

    $data = [
      "first_name" => "Abdul",
      "last_name" => "Kalam",
    ];

    Query::replace("users", $data);  

    // query: REPLACE INTO users (`first_name`, `last_name`) VALUES ('Abdul', 'Kalam')
    // returns: boolean or integer (inserted row id)

## delete
**delete($table, $whereString, $whereIdValue = null)**  

Delete the rows from the table where the condition in whereString is matched.  
You can directly separate all your conditions with whatever relation you like `AND` or `OR` . Check the example below 
Comparing rows with id value is one of the most common use case so there's a special filter for it, just pass the id as a third parameter and it will add <i> AND \`id\`='$your_passed id'</i>

    Query::delete(
      "employees",
      "`email`='example@email.com' AND `password`='@123'"
    );

    // query: DELETE FROM employees WHERE `email`='example@email.com' AND `password`='@123'
    // returns: boolean

## updateWhere
**updateWhere($table, $dataObject, $whereString, $whereIdValue = null)**  

First create a data object and pass it to the update() and the rows from the table where the condition in whereString is matched are updated.  
You can directly separate all your conditions with whatever relation you like `AND` or `OR` . Check the example below 
Comparing rows with id value is one of the most common use case so there's a special filter for it, just pass the id as a third parameter and it will add <i> AND \`id\`='$your_passed id'</i>

    $data = [
      "first_name" => "Abdul",
      "last_name" => "Kalam",
    ];

    Query::updateWhere(
      "users",
      "`email`='example@email.com'"
    );

    // query: UPDATE users SET `first_name`='Abdul', `last_name`='Kalam' WHERE `email`='example@email.com'
    // returns: boolean

## iterateOnResult
**iterateOnResult($query, $callback = null, $emptyCallback = null)**  

To select multiple rows from a table and doing operations on them use iterateOrResult()  
Data objects returned inside the parameter has two extra properties `numRows` (total number of rows available) and `hasNext` (if has more data in the list)   
To do a different operation when the data is empty, pass a empty callback as third parameter (see example below) 

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

## truncate
**truncate($tableName)**  

To truncate (remove all rows) from a table, use truncate()

    Query::truncate("users");

    // returns: boolean

## Features

 - Fully adaptive
 - Lightweight

## Links
 - Live example
   - https://pricelistlite.isolpro.in
    - https://transactionslistlite.isolpro.in
