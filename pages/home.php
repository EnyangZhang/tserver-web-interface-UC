<?php
    require_once(__DIR__."/../lib/Event.php");
    require_once(__DIR__."/../lib/User.php");
    require_once(__DIR__."/../phpObj/Bootstrap3.php");

    PageHeaderPHP();
    $user = unserialize($_SESSION['curr_user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Tserver Home</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="../img/icons/home.ico"/>

    <link rel="stylesheet" href="../css/home/main.css">
    <link rel="stylesheet" href="../css/phpObj/navbar.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="wrapper">
        <?php include_once(__DIR__."/../phpObj/verticalNav.php"); ?>
        <div id="content">
            <?php include_once(__DIR__."/../phpObj/horizontalNav.php"); ?>

            <h2>Upcoming Events</h2>
            <div id='main-body' class='row' style='margin:0px;'>
                <?php
                    $next_month = date("Y") . '/' . (date("m")+1) . '/' . date("t");
                    $date_tmr = date("Y") . '/' . date("m") . '/' . (date("d")+1);
                    $events = Event::getEventsByDate(date('Y-m-d', strtotime($date_tmr)), date('Y-m-d', strtotime($next_month)), 6, 0);
                    if (sizeof($events) == 0) {
                        echo "<h4>" . "There is no upcoming event this month and next month." . "</h4>";
                    } else {
                        foreach ($events as $event) {
                            $header = "<h3>" . $event->getName() . "</h3>";
                            $body = sprintf("<h4>Date: %s - %s</h4><br>Location: %s<br>Method: %s",
                                $event->getTime("h:ia"), $event->getOffTime("h:ia d/m/Y"), $event->getLocation(), $event->getCluster());
                            Bootstrap3::createEventPanel($header, $body, "info");
                        }
                    }
                ?>
            </div>

            <div class="line"></div>
            <h2>Current Events</h2>
            <div id='main-body' class='row' style='margin:0px;'>
                <?php
                $events = Event::getEventsByDate(date('Y-m-d', strtotime(date("Y-m-d"))), date('Y-m-d', strtotime(date("Y-m-d"))), 6, 0);
                if (sizeof($events) == 0) {
                    echo "<h4>" . "There is no upcoming event today." . "</h4>";
                } else {
                    foreach ($events as $event) {
                        $header = "<h3>" . $event->getName() . "</h3>";
                        $body = sprintf("<h4>Date: %s - %s</h4><br>Location: %s<br>Method: %s",
                            $event->getTime("h:ia"), $event->getOffTime("h:ia d/m/Y"), $event->getLocation(), $event->getCluster());
                        Bootstrap3::createEventPanel($header, $body, "info");
                    }
                }
                ?>
            </div>

            <div class="line"></div>
            <h2>Past Events</h2>
            <div class='panel-group' id='accordion'>
                <?php
                    $counter = 0;
                    foreach (Event::getPastEvents(5) as $event) {
                        $counter++;
                        $header = "<h4>" . $event->getName() . "</h4>";
                        $body = sprintf("<h4>Date: %s - %s</h4><br>Location: %s<br>Method: %s",
                            $event->getTime("h:ia"), $event->getOffTime("h:ia d/m/Y"), $event->getLocation(), $event->getCluster());
                        Bootstrap3::createToggleList($header, $body, $event, $counter);
                    }
                ?>
            </div>
        </div> 
    </div>
</body>
</html>
