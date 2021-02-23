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
    // returns: data object

## selectWhere
**selectWhere($tableName, $whereString, $whereIdValue = null)**  

Returns first array from the table where the rows match conditions in whereString. Comparing rows with id value is one of the most common use case so there's a special filter for it, just pass the id as a third parameter and it will add AND id='$your_passed id'

    Query::selectWhere(
      "employees",
      "`email`='example@email.com' AND `password`='@123'"
    );
    // returns: data object

## insert
**insert($table, $dataObject, $ignoreMode = false)**  

Some queries are just too complex to create objects for, just write it yourself and pass it to the raw()

    Query::raw(
      "SELECT e.*, a.level FROM employees e
      LEFT OUTER JOIN authorization a ON a.emp_id=e.id
      WHERE e.id=12"
    );

## replace
**replace($table, $dataObject)**  

Some queries are just too complex to create objects for, just write it yourself and pass it to the raw()

    Query::raw(
      "SELECT e.*, a.level FROM employees e
      LEFT OUTER JOIN authorization a ON a.emp_id=e.id
      WHERE e.id=12"
    );

## delete
**delete($table, $whereString, $whereIdValue = null)**  

Some queries are just too complex to create objects for, just write it yourself and pass it to the raw()

    Query::raw(
      "SELECT e.*, a.level FROM employees e
      LEFT OUTER JOIN authorization a ON a.emp_id=e.id
      WHERE e.id=12"
    );

## updateWhere
**updateWhere($table, $dataObject, $whereString, $whereIdValue = null)**  

Some queries are just too complex to create objects for, just write it yourself and pass it to the raw()

    Query::raw(
      "SELECT e.*, a.level FROM employees e
      LEFT OUTER JOIN authorization a ON a.emp_id=e.id
      WHERE e.id=12"
    );

## iterateOnResult
**iterateOnResult($query, $callback = null, $emptyCallback = null)**  

Some queries are just too complex to create objects for, just write it yourself and pass it to the raw()

    Query::raw(
      "SELECT e.*, a.level FROM employees e
      LEFT OUTER JOIN authorization a ON a.emp_id=e.id
      WHERE e.id=12"
    );

## truncate
**truncate($tableName)**  

Some queries are just too complex to create objects for, just write it yourself and pass it to the raw()

    Query::raw(
      "SELECT e.*, a.level FROM employees e
      LEFT OUTER JOIN authorization a ON a.emp_id=e.id
      WHERE e.id=12"
    );

## Features

 - Fully adaptive
 - Lightweight

## Links
 - Live example
   - https://pricelistlite.isolpro.in
    - https://transactionslistlite.isolpro.in
