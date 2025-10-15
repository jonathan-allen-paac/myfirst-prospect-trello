<?php
// require config
require_once($_SERVER['DOCUMENT_ROOT']."/../config.php");

// Include DB model
require_once(ROOT.INC_MODELS."ogi_BQS.php");

$day = date("Y-m-d");
//$day = "2025-04-30";
$sync = ogi_sync_BQS($day);
print_r($sync);