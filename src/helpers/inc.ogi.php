<?php 

function postUpdateOGI($xml) {
    $base = "https://www.openinterchange.co.uk/OpenInterchange/OpenInterchange";

    $xmlHead = '
        <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"> 
        <SOAP-ENV:Header/>
        <SOAP-ENV:Body>
            <ns1:processMessage xmlns:ns1="www.opengi.co.uk">
            <messageType>OICXS</messageType>
            <marsReference>MYFI001</marsReference>
            <bNumber>B03104</bNumber>
            <licenceKey>A1KL6F9GP</licenceKey>
            <xmlSubmission>
        
                <![CDATA[';
    $xmlFoot = ']]>
 
            </xmlSubmission>
            <timeout>20</timeout>
            </ns1:processMessage>
        </SOAP-ENV:Body> 
        </SOAP-ENV:Envelope>';
    $xml = $xmlHead.$xml.$xmlFoot;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'SOAPAction: "processMessage"',
        'Content-Type: text/xml'
    ));
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, $base);
    curl_setopt($curl, CURLOPT_REFERER, $base);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
    $str = curl_exec($curl);
    //error handling
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $type = "ERROR - Function:".__FUNCTION__;
    $backtrace = debug_backtrace();
    $callerFile = $backtrace[0]['file'];
    $callerLine = $backtrace[0]['line'];
    $message = "ERROR: ".basename($callerFile)." (Line: $callerLine) API HTTP Code $httpCode. Response: $str";
    if($httpCode != 200 && $httpCode != 201) {echo "$type - $message";logEntry($type,$message,$xml);return "API ERROR";}

    curl_close($curl);
    return $str;
    
}