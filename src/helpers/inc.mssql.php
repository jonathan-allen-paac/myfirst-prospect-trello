<?php

// Fetch function returns array of data 
function mssqlFetch($sql) {
    global $conn_ms;
    $query = sqlsrv_query($conn_ms,$sql);
    if($query === FALSE) {
        $error = "MSSQL Fetch Error: ". sqlsrv_errors() ."<br>";
        errorPrint(__FILE__,__LINE__, $error);
    } else {
        // SQL Success
        
        return $query;
    }
}

// Returns the number of rows from the query
function mssqlNumrows($sql) {
    global $conn_ms;
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $query = sqlsrv_query($conn_ms,$sql,$params,$options);
    if($query === FALSE) {
        $error = "MSSQL Fetch Num Error: ". sqlsrv_errors() ."<br>";
        errorPrint(__FILE__,__LINE__, $error);
    } else {
        // SQL Success
        $data = sqlsrv_num_rows($query);
        return $data;
    }
}