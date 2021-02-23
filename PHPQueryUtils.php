<?php

class Query {
  public static function raw($query) {
    $q = mysqli_query($GLOBALS['con'], $query);
    if (!is_bool($q)) {
      return mysqli_fetch_array($q);
    } else {
      return $q;
    }
  }

  public static function rawForResult($query) {
    $r = mysqli_query($GLOBALS['con'], $query);

    if (mysqli_num_rows($r) <= 0)
      return false;

    return $r;
  }

  public static function select($table) {
    //rest parameters: fields to select (empty if *)

    $fields_array = array_slice(func_get_args(), 1);

    $fields = "";
    if (count($fields_array) > 0) {
      foreach ($fields_array as $field) {
        $fields .= "`$field`,";
      }

      $fields = substr_replace($fields, "", -1);
    } else {
      $fields = "*";
    }

    $selectQuery = "SELECT $fields FROM $table WHERE 1";

    if ($d = Query::raw($selectQuery)) {
      return (object) $d;
    }
    return 0;
  }

  public static function selectWhere($table, $whereStatement, $idFilter = null) {
    $whereStatement = !empty($whereStatement) ? $whereStatement : "1=1";
    $idFilter = $idFilter != null ? "`id`='$idFilter'" : "1=1";

    $selectQuery = "SELECT * FROM $table WHERE $whereStatement AND $idFilter";

    if ($d = Query::raw($selectQuery)) {
      return (object) $d;
    }
    return 0;
  }

  public static function insert($table, $dataObj, $ignoreMode = false) {
    $ignore = $ignoreMode ? "IGNORE" : "";

    $insertQuery = "INSERT $ignore INTO `" . $table . "` (";

    foreach ($dataObj as $key => $value) {
      $insertQuery .= "`$key`,";
    }

    $insertQuery .= "`created_at`";
    $insertQuery .= ") VALUES (";

    foreach ($dataObj as $key => $value) {
      $value .= "";
      if (strlen($value) <= 0) {
        $insertQuery .= "'',";
      } else if ($value[0] == '(' || $value[strlen($value) - 1] == ')') {
        $insertQuery .= "$value,";
      } else {
        $insertQuery .= "'$value',";
      }
    }

    $insertQuery .= "CURRENT_TIMESTAMP";
    $insertQuery .= ")";

    if (Query::raw($insertQuery)) {
      return mysqli_insert_id($GLOBALS['con']);
    }

    return 0;
  }

  public static function replace($table, $dataObj) {
    $replaceQuery = "REPLACE INTO `" . $table . "` (";

    foreach ($dataObj as $key => $value) {
      $replaceQuery .= "`$key`,";
    }

    $replaceQuery .= "`created_at`";
    $replaceQuery .= ") VALUES (";

    foreach ($dataObj as $key => $value) {
      $value .= "";
      if (strlen($value) <= 0) {
        $replaceQuery .= "'',";
      } else if ($value[0] == '(' || $value[strlen($value) - 1] == ')') {
        $replaceQuery .= "$value,";
      } else {
        $replaceQuery .= "'$value',";
      }
    }

    $replaceQuery .= "CURRENT_TIMESTAMP";
    $replaceQuery .= ")";

    // unexpectedTerminate($replaceQuery);

    if (Query::raw($replaceQuery)) {
      return mysqli_insert_id($GLOBALS['con']);
    }

    return 0;
  }

  public static function delete($table, $where, $idFilter = null) {
    $where = !empty($where) ? $where : "1=1";
    $idFilter = $idFilter != null ? "`id`='$idFilter'" : "1=1";

    $deleteQuery = "DELETE FROM $table WHERE $where AND $idFilter";

    if (Query::raw($deleteQuery)) {
      return 1;
    }

    return 0;
  }

  public static function updateWhere($table, $dataObj, $where, $idFilter = null) {
    $where = !empty($where) ? $where : "1=1";
    $idFilter = $idFilter != null ? "`id`='$idFilter'" : "1=1";

    $updateQuery = "UPDATE `$table` SET ";

    foreach ($dataObj as $key => $value) {
      $value .= "";
      if (strlen($value) <= 0) {
        $updateQuery .= "`$key`='',";
        // } else if ($value[0] == '(' || $value[strlen($value) - 1] == ')') {
      } else if ($value[0] == '(') {
        $updateQuery .= "`$key`=$value,";
      } else {
        $updateQuery .= "`$key`='$value',";
      }
    }

    $updateQuery = substr_replace($updateQuery, "", -1); //Delete the last comma

    $updateQuery .= " WHERE $where AND $idFilter";

    if (Query::raw($updateQuery)) {
      if (mysqli_affected_rows($GLOBALS['con']) > 0) {
        return 1;
      }
    }

    return 0;
  }

  public static function iterateOnResult($query, $callback = null, $emptyCallback = null) {
    if ($r = Query::rawForResult($query)) {
      $rows = mysqli_num_rows($r);
      $count = 1;
      while ($d = mysqli_fetch_array($r)) {
        $d = (object) $d;

        $d->numRows = $rows;
        $d->hasNext = $count++ < $rows ? 1 : 0;

        if ($callback != null)
          $callback($d);
      }
    } else {
      if ($emptyCallback != null)
        $emptyCallback();
    }
  }

  public static function truncate($table) {
    return (bool) Query::raw("TRUNCATE TABLE $table");
  }
}
