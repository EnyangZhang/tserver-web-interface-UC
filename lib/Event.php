<?php
require_once(__DIR__ . "/dbconnect.php");

class Event
{
    // CALL getEventsByDate('2020-08-18');
    // CALL getEventLocations('EMTH119-20S2 Tuesday', 'MapleTA', '19:00:00', '2020-08-18');
    // SELECT * FROM `vw_front_event` WHERE `date` = '2020-08-18';
    protected $event_name;      // string
    protected $cluster_name;    // string
    protected $machine_group;   // array
    protected $datetime;        // timestamp
    protected $off_datetime;    // timestamp
    public $linker;

    public function __construct($event, $cluster, $date, $time, $time_off, array $ids, $m_group = array())
    {
        $this->event_name = $event;
        $this->cluster_name = $cluster;
        $this->datetime = strtotime($date." ".$time);
        $this->off_datetime = strtotime($date." ".$time_off);
        $this->machine_group = $m_group;

        $key = array("eventId", "actionId", "weeklyId");
        for ($i=0; $i < count($ids); $i++){
            $this->linker[$key[$i]] = $ids[$i];
        }
    }

    public function getName() { return $this->event_name; }
    public function getCluster() { return $this->cluster_name; }
    public function getLocation(): string { return implode(", ", $this->machine_group); }
    public function getTime(string $format): string { return date($format, $this->datetime); }
    public function getOffTime(string $format): string { return date($format, $this->off_datetime); }
    public function setName(string $name) { $this->event_name = $name; }    
    public function setCluster(string $cluster) { $this->cluster_name = $cluster; }
    public function setLocation(array $machine) { $this->machine_group = $machine; }
    public function setTime($datetime) { $this->datetime = $datetime; }

    public function getEventID() { return $this->linker["eventId"]; }
    public function getActionID() { return $this->linker["actionId"]; }
    public function getWeeklyID() { return $this->linker["weeklyId"]; }
    public function getLinkerStr() { return implode(", ", array_values($this->linker));}
    
    public function getEventLocations()
    {
        // call getEventLocations('MATH110-19S1 Test Wednesday E033','Labs','02:25:00','2020-02-26');
        $location = array();
        $exec = QueryDB("CALL getEventLocations(%d, %d)",
            array($this->getEventID(), $this->getActionID()));

        if ($exec == false) return $location;
        while ($result = mysqli_fetch_assoc($exec)) {
            array_push($location, $result['machine_group']);
        }
        return $location;
    }

    public function close_lab($lab_close_offset)
    {
        $conn = ConnectDB();
        $stmt = $conn->prepare("CALL add_front_action_disable(?, ?, 'Labs')");
        $stmt->bind_param('ss', $this->event_name, $lab_close_offset);
        $stmt->execute();
        return true;
    }

    public function open_lab($lab_open_offset)
    {
        $conn = ConnectDB();
        $stmt = $conn->prepare("CALL add_front_action_enable(?, ?, 'Labs')");
        $stmt->bind_param('ss', $this->event_name, $lab_open_offset);
        $stmt->execute();
        return true;
    }

    public function addFrontEvent()
    {
        $conn = ConnectDB();
        $event_status = 1;
        $stmt = $conn->prepare("CALL add_front_event(?, ?)");
        $stmt->bind_param('si', $this->event_name, $event_status);
        $stmt->execute();
    }

    public function addFrontAction($time_offset_start, $time_offset_end)
    {
        $conn = ConnectDB();

        $stmt = $conn->prepare("CALL add_front_action_enable(?, ?, ?)");
        $stmt->bind_param('sss', $this->event_name, $time_offset_start, $this->cluster_name);
        $stmt->execute();

        $stmt = $conn->prepare("CALL add_front_action_disable(?, ?, ?)");
        $stmt->bind_param('sss', $this->event_name, $time_offset_end, $this->cluster_name);
        $stmt->execute();
    }

    public function addFrontDaily($machine_group_names, $day_of_week, $start_time){
        $conn = ConnectDB();

        foreach ($machine_group_names as $group_name) {
            // echo $group_name;
            // echo $this->event_name;
            // echo $day_of_week;
            // echo $start_time;
            $stmt = $conn->prepare("CALL add_front_daily(?, ?, ?, ?)");
            $stmt->bind_param('ssis', $this->event_name, $group_name, $day_of_week, $start_time);
            $stmt->execute();
        }
    }

    public function addFrontWeekly($week_of_year, $event_year){
        $conn = ConnectDB();

        $stmt = $conn->prepare("CALL add_front_weekly(?, ?, ?)");
        $stmt->bind_param('sii', $this->event_name, $week_of_year, $event_year);
        $stmt->execute();
    }

    public static function editEvent(array $linker, $new_name, array $cluster, $new_time, array $init_loc, array $update_loc) {
        $event_id = $linker[0];
        $weekly_id = $linker[2];
        $startTime = date("H:i:s", $new_time);
        $dayOfWeek = (int)date("w", $new_time);
        $week = (int)date("W", $new_time);
        $year = (int)date("Y", $new_time);
        QueryDB("CALL updateEventName('%s', %d)", array($new_name, $event_id));
        QueryDB("CALL updateEventCluster('%s', %d, '%s')", array($cluster[0], $event_id, $cluster[1]));
        QueryDB("CALL updateFrontWeekly(%d, %d, %d)", array($week, $year, $weekly_id));

        foreach($init_loc as $location) {
            QueryDB("CALL updateFrontDaily(%d, '%s', %d, '%s')", array($dayOfWeek, $startTime, $event_id, $location));
        }

        $del_loc = array_diff($init_loc, $update_loc);
        $add_loc = array_diff($update_loc, $init_loc);
        foreach($add_loc as $location) {
            QueryDB("CALL updateLocationAppend(%d, '%s', %d, '%s')", array($dayOfWeek, $startTime, $event_id, $location));
        }
        foreach($del_loc as $location) {
            QueryDB("CALL updateLocationRemove(%d, '%s')", array($event_id, $location));
        }
    }


    public static function getEventsByDate(string $start, string $end="", int $limit=0, int $offset=0) :array {
        if ($start == "") return array();     
        $event_list = array();

        if ($end != "") {
            $exec = QueryDB("CALL getEventsByDateRange('%s', '%s', '%s', '%s')", array($start, $end, $limit, $offset));
        } else {
            $exec = QueryDB("CALL getEventsByDate('%s')", array($start));
        }

        while ($result = mysqli_fetch_assoc($exec)) {
            $ids = array($result["event_id"], $result["action_id"], $result["weekly_id"]);
            $event = new Event($result['event_name'], $result['cluster_name'],
                $result['date'], $result['start_time'], $result['end_time'], $ids);
            array_push($event_list, $event);
        }

        foreach ($event_list as $e) {
            $e->setLocation($e->getEventLocations());
        }
        return $event_list;
    }
    
    public static function getEventsByName($eventName, $limit, $offset) {
        $events = array();
        $exec = QueryDB("CALL getEventsByName('%s', %d, %d)", array($eventName, $limit, $offset));
        while($result = mysqli_fetch_assoc($exec)) {
            $ids = array($result["event_id"], $result["action_id"], $result["weekly_id"]);
            $event = new Event( $result['event_name'], $result['cluster_name'],
                                $result['date'], $result['start_time'], $result['end_time'], $ids);
            array_push($events, $event);
        }

        foreach($events as $e){
            $e->setLocation($e->getEventLocations());
        }
        return $events;
    }

    public static function getPastEvents($limit) {
        $date_today = time();
        $event_list = array();
        // Bootstrap3::createAlert(date('Y-m-d', $date_today));
        $exec = QueryDB("CALL getPastDate('%s')", array(date('Y-m-d', $date_today)));
        while (count($event_list) < $limit) {
            $date_res = mysqli_fetch_assoc($exec);
            $event_list = array_merge($event_list, self::getEventsByDate($date_res['date']));
        }
        return $event_list;
    }
}

?>