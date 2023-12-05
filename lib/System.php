<?php
require_once(__DIR__ . "/dbconnect.php");

class System {
    public static function getAllCluster(){
        $clusters = array();
        $exec = QueryDB("CALL getAllCluster()", array());
        while ($result = mysqli_fetch_assoc($exec)) {
            array_push($clusters, $result['cluster_name']);
        }
        return $clusters;
    }

    public static function getAllGroup(){
        $groups = array();
        $exec = QueryDB("CALL getAllGroup()", array());
        while ($result = mysqli_fetch_assoc($exec)) {
            array_push($groups, $result['machine_group']);
        }
        return $groups;
    }

    public static function validateInput(array $argv, string $defaultVal) {
        $counter = 1;
        foreach ($argv as $arg){
            if ($arg == $defaultVal) {
                break;
            }
            $counter++;
        }

        if ($counter > sizeof($argv)) {
            return 0;
        } else {
            return $counter;
        }
    }

    public static function convertOffset(string $offsetTime){
        $offsetTime = (int)$offsetTime;
        if ($offsetTime == 0 || $offsetTime == null){
            return "00:00:00";
        } elseif($offsetTime > 0){
            $hours = (int)($offsetTime / 60);
            $minutes = fmod($offsetTime, 60);
            return (string)$hours.":".(string)$minutes.":00";
        } else {
            $hours = (int)($offsetTime / 60);
            $minutes = fmod(abs($offsetTime), 60);
            return "-".(string)$hours.":".(string)$minutes.":00";
        }
    }

    public static function searchResult(){
        $events = array(); $event = '';
        $to_date = ""; $from_date = ""; $limit = 10;

        if (isset($_GET['btnSubmit']) && $_GET['btnSubmit'] != '') {
            echo "<div>";
            $start = isset($_GET['start']) ? $_GET['start']: 0;
    
            if ($_GET['event'] != '') {
                //removes whitespace and other predefined characters from both sides of a string
                $event = trim($_GET['event']);
                $events = Event::getEventsByName($event, $limit, $start);
                echo "<br>Your search key " . $event . "<br>";
            } else if ($_GET['from_date'] != '') {
                $from_date = trim(str_replace('-', '/', $_GET['from_date']));
                $to_date = trim(str_replace('-', '/', $_GET['to_date']));
                echo "<br>Your search key date from " . $_GET['from_date'] . " to " . $_GET['to_date'] . "<br>";
    
                $events = Event::getEventsByDate(date("Y-m-d", strtotime($from_date)), date("Y-m-d", strtotime($to_date)), $limit, $start);
            }
            // pagination part
            $prev = $start - $limit;
            $next = $start + $limit;
    
            $pagination = "";
            $visibleLink = "<a href='search.php?event=%s&from_date=%s&to_date=%s&btnSubmit=Search&start=%s' class='btn btn-primary'>%s</a>";
            $disabledLink = "<a href='search.php?event=%s&from_date=%s&to_date=%s&btnSubmit=Search&start=%s' class='btn btn-primary disabled'>%s</a>";
    
            $last_events = '';
            if (sizeof($events) > 0) {
                echo "<hr/>";
                if ($start >= 0) {
                    $pagination .= '<div class="paginate-info-section"><div style="float: right; padding-left: 10px;width: 170px"><p>';

                    $pagination .= "show results " . ($start+1) . "-" . ($start+$limit);
//
                    $pagination .= "</p></div>";
                    $pagination .= "<div style='float: right'>";
                    if ($prev >= 0) {
                        $pagination .= sprintf($visibleLink, $event, urlencode($from_date), urlencode($to_date), $prev, '&laquo;');
                    } else {
                        $pagination .= sprintf($disabledLink, $event, urlencode($from_date), urlencode($to_date), $prev, '&laquo;');
                    }
                }
                if (sizeof($events) == $limit) {
                    if ($_GET['event'] != '') {
                        $last_events = Event::getEventsByName($event, $limit, $next);
                    } else if ($_GET['from_date'] != '') {
                        $last_events = Event::getEventsByDate(date("Y-m-d", strtotime($from_date)), date("Y-m-d", strtotime($to_date)), $limit, $next);
                    }
                    if (sizeof($last_events) != 0) {
                        $pagination .= sprintf($visibleLink, $event, urlencode($from_date), urlencode($to_date), $next, '&raquo;');
                    } else {
                        $pagination .= sprintf($disabledLink, $event, urlencode($from_date), urlencode($to_date), $next, '&raquo;');
                    }
                } else {
                    $pagination .= sprintf($disabledLink, $event, urlencode($from_date), urlencode($to_date), $next, '&raquo;');
                }
                $pagination .= '</div></div>';
                echo $pagination;
    
                // table of results
                global $user;
                Bootstrap3::createSearchTable($events, $user);            
            } else {
                echo "<hr/>";
                echo "No result found <br>";
                echo "Please make sure you enter at least 3 symbols and select an exact event name from the dropdown or choose a date to search.";
            }
            echo "</div>";
        }
    }
}

?>