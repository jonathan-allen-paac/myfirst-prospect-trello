<?php

// Fetch function returns array of data 
function mysqlFetch($sql) {
    global $conn;
    $query = $conn->query($sql);
    if($query === FALSE) {
        $error = "MySQL Fetch Error: ". $conn->error ."<br>$sql";
        errorPrint(__FILE__,__LINE__, $error);
    } else {
        // SQL Success
        $data = $query;
        return $data;
    }
}

// Insert function returns the number of rows from the query
function mysqlNumrows($sql) {
    global $conn;
    $query = $conn->query($sql);
    if($query === FALSE) {
        $error = "MySQL Fetch Num Error: ". $conn->error ."<br>$sql";
        errorPrint(__FILE__,__LINE__, $error);
    } else {
        // SQL Success
        $data = $query->num_rows;
        if(!is_numeric($data)) {
            errorPrint(__FILE__,__LINE__, "");
        }
        return $data;
    }
}

// Insert function returns the ID or primary key of the inserted row
function mysqlInsert($sql) {
    global $conn;
    $query = $conn->query($sql);
    if($query != TRUE) {
        $error = "MySQL Insert Error: ". $conn->error ."<br>$sql";
        errorPrint(__FILE__,__LINE__, $error);
    } else {
        // SQL Success
        $data = $conn->insert_id;
        if(!is_numeric($data)) {
            errorPrint(__FILE__,__LINE__, "");
        }
        return $data;
    }
}

// Update function returns true on success
function mysqlUpdate($sql) {
    global $conn;
    $query = $conn->query($sql);
    if($query === FALSE) {
        $error = "MySQL Update Error: ". $conn->error ."<br>$sql";
        errorPrint(__FILE__,__LINE__, $error);
    } else {
        // SQL Success
        return true;
    }
}