<?php
require_once(__DIR__ . "/../lib/dbconnect.php");

$groups = array();
$event = strtoupper(trim($_GET['name']));
if (strlen($event) >= 3) {
    $exec = QueryDB("CALL getEventNameByTokens('%s')", array($event .'%'));
    while ($result = mysqli_fetch_assoc($exec)) {
        array_push($groups, $result['event_name']);
    }
}
echo json_encode($groups);
exit;
?>
