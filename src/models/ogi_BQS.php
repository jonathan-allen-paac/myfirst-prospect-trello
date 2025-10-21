<?php

$OGIQuery = "SELECT
        P.[Branch@],
        P.[Key@],
        C.[#Name],
        C.Email,
        C.Tel,
        C.Addr1,
        C.Addr2,
        C.Addr3,
        C.Addr4,
        C.Pcode,
        C.Dob,
        Freetext2,
        P.[PolicyRef@],
        P.[ClientRef@],
        B.LastUpdatedTime,
		V.Model,
		M.VTDescription AS Make,
		O.VTDescription AS Occupation,
		T.VTDescription AS LicenseType
    FROM
        icp_Daprospect C
		INNER JOIN icp_Dapolicy P ON C.[Branch@] = P.[Branch@] AND C.[ClientRef@] = P.[ClientRef@]
		INNER JOIN icp_DP_BQS B ON B.[Branch@] = C.[Branch@] AND B.[PolicyRef@] = P.[PolicyRef@]
		LEFT JOIN icp_DP_CF V on V.[PolicyRef@] = P.[PolicyRef@]
		LEFT JOIN icp_QR26_VT M ON M.VTId = V.Make_QR26_VTId
		LEFT JOIN icp_DP_PC L ON L.[PolicyRef@] = P.[PolicyRef@]
		LEFT JOIN icp_QR35_VT O ON O.VTId = L.Ft_occup_QR35_VTId
		LEFT JOIN icp_QR05_VT T ON T.VTId = L.Licence_QR05_VTId";

//OGI CHKD Sync
function ogi_sync_BQS($day) {

    global $conn,$OGIQuery;
    //fetch list by passed date - OGI
    $mssqlRows = mssqlFetch("$OGIQuery
    WHERE P.[Branch@] = 0 and CONVERT(DATE, B.[LastUpdatedTime]) = '$day'
    ORDER BY B.[LastUpdatedTime] DESC");

    //fetch list by passed date - local
    $mysqlRows = mysqlFetch("SELECT `B@`
    ,`Key@`
    FROM `myfirst-sync`.`myfirst-prospect-trello`
    WHERE DATE(`LastUpdatedTime`) = '$day'");
    //bulid matching array from local data
    $existingKeys = [];
    while($row = mysqli_fetch_array($mysqlRows)) {
        $key = $row['B@'] . '|' . $row['ClientRef@'];
        $existingKeys[$key] = true;
    }

    $rowsToInsert = [];

    //compare data from OGI and build difference list
    while ($row = sqlsrv_fetch_array($mssqlRows, SQLSRV_FETCH_ASSOC)) {
        $key = $row['Branch@'] . '|' . $row['ClientRef@'];
    
        if (!isset($existingKeys[$key])) {
            $dob = $row['Dob'] ? $row['Dob']->format('Y-m-d') : "";
            $rowsToInsert[] = [
                'B' => $row['Branch@'],
                'Key' => $row['Key@'],
                'LastUpdatedTime' => $row['LastUpdatedTime']->format('Y-m-d H:i:s'),
                'ClientRef@' => $row['ClientRef@'],
                'PolicyRef@' => $row['PolicyRef@'],
                '#Name' => $row['#Name'],
                'Email' => $row['Email'],
                'Tel' => $row['Tel'],
                'Addr1' => $row['Addr1'],
                'Addr2' => $row['Addr2'],
                'Addr3' => $row['Addr3'],
                'Addr4' => $row['Addr4'],
                'Pcode' => $row['Pcode'],
                'Dob' => $dob,
                'Freetext2' => $row['Freetext2'],
                'Model' => $row['Model'],
                'Make' => $row['Make'],
                'Occupation' => $row['Occupation'],
                'LicenseType' => $row['LicenseType']
            ];
        }
    }
    if (count($rowsToInsert) == 0) {
        mysqlInsert("INSERT INTO `run_log` (`function`) VALUES ('".__FUNCTION__."')");
        if(isset($msc)) {$msc2=microtime(true)-$msc; echo "<span>Server processed in".round($msc2,5)." seconds</span>";}
        return "No entries to sync<br>";
    } else {
        //insert differences to database - local
        foreach ($rowsToInsert as $row) {
            $B = $conn->escape_string($row['B']);
            $Key = $conn->escape_string($row['Key']);
            $LastUpdatedTime = $conn->escape_string($row['LastUpdatedTime']);
            $ClientRef = $conn->escape_string($row['ClientRef@']);
            $PolicyRef = $conn->escape_string($row['PolicyRef@']);
            $Name = $row['#Name'];
            $Email = $row['Email'];
            $Tel = $row['Tel'];
            $Addr1 = $row['Addr1'];
            $Addr2 = $row['Addr2'];
            $Addr3 = $row['Addr3'];
            $Addr4 = $row['Addr4'];
            $Pcode = $row['Pcode'];
            $Dob = $row['Dob'];
            $Freetext2 = $row['Freetext2'];
            $Model = $row['Model'];
            $Make = $row['Make'];
            $Occupation = $row['Occupation'];
            $LicenseType = $row['LicenseType'];
            
            mysqlInsert("INSERT INTO `myfirst-prospect-trello` (`B@`, `Key@`, `LastUpdatedTime`, `ClientRef@`, `PolicyRef@`,`Synced`,
            `#Name`,`Email`,`Tel`,`Addr1`,`Addr2`,`Addr3`,`Addr4`,`Pcode`,`Dob`,`Freetext2`,Model,Make,Occupation,LicenseType)
            VALUES ('$B', '$Key', '$LastUpdatedTime', '$ClientRef', '$PolicyRef', NOW(),
            '$Name','$Email','$Tel','$Addr1','$Addr2','$Addr3','$Addr4','$Pcode','$Dob','$Freetext2',
            '$Model','$Make','$Occupation','$LicenseType')");
        }
        mysqlInsert("INSERT INTO `run_log` (`function`) VALUES ('".__FUNCTION__."')");
        if(isset($msc)) {$msc2=microtime(true)-$msc; echo "<span>Server processed in".round($msc2,5)." seconds</span>";}
        return $rowsToInsert;
    }
}
