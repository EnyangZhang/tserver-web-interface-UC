<?php
require_once(__DIR__ . "/../lib/User.php");
require_once(__DIR__ . "/../lib/Event.php");
require_once(__DIR__ . "/../lib/System.php");
require_once(__DIR__ . "/../phpObj/Bootstrap3.php");
PageHeaderPHP();
$user = unserialize($_SESSION['curr_user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tserver New Event</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="../img/icons/home.ico"/>

    <link rel="stylesheet" href="../css/phpObj/navbar.css">
    <link rel="stylesheet" href="../css/event/createEvent.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css">

</head>
<body>
<!-- <div class="container-login100" style="padding:0px; background-image: url('img/background.jpg');"> -->
<div class="wrapper">
    <?php include_once(__DIR__ . "/../phpObj/verticalNav.php"); ?>
    <div id="content">
        <?php include_once(__DIR__ . "/../phpObj/horizontalNav.php"); ?>

        <form action="createEvent.php" method="POST">
            <div class="event-content">
                <div class="profile-content col-lg-12">
                    <h1 class="profile-content">New Event</h1>
                    <div class="row">
                        <?php
                            $choices = $user->getUserSubjects();
                            Bootstrap3::createDropdown("selectCourse", "Select course *", "col-md-4", $choices);
                            Bootstrap3::createEntry("txtTerm", "col-md-2", "Year-Term", "ie. 20S2");
                        ?>
                        <div class='col-md-4'>
                            <label for='exampleFormControlSelect1'>More referred name</label>
                            <div class="input-group">
                                <span class="input-group-addon" id="previewName">Pending</span>
                                <input id="msg" type="text" class="form-control" name="msg"
                                       placeholder="Additional Info (Lab, Test, ...)">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php
                        $choices = System::getAllCluster();
                        Bootstrap3::createDropdown("selectCluster", "Select cluster name *", "col-md-4", $choices);
                        ?>
                        <!-- Datetime Picker -->
                        <div class='col-sm-6'>
                            <label for="exampleFormControlSelect1">Select datetime *</label>
                            <div class="form-group">
                                <div class='input-group date' id='event_time'>
                                    <input type='text' id='eventTime' name='eventTime' class="form-control" placeholder="Select datetime" autocomplete="off"/>
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php
                            Bootstrap3::createEntry("startOffset", 'col-sm-2', 'Start time offset', "in minutes");
                            Bootstrap3::createEntry("endOffset", 'col-sm-2', 'End time offset', "in minutes");
                        ?>
                        <div class='col-sm-2'>
                            <label for="exampleFormControlSelect1">If need to close labs</label>
                            <div class="form-group">
                                <input type="checkbox" id="ifLabClose" name="ifLabClose" value="true">
                            </div>
                        </div>
                        <?php
                            Bootstrap3::createEntry("labEndOffset", 'col-sm-2', 'Lab end time offset', "in minutes");
                            Bootstrap3::createEntry("labStartOffset", 'col-sm-2', 'Lab start time offset', "in minutes");
                        ?>
                    </div>

                    <div class="row">
                        <?php
                        $choices = System::getAllGroup();
                        Bootstrap3::createDropdown("selectLocation", "Select machine group", "col-md-4", $choices);
                        ?>
                        <div class='col-md-6'>
                            <label for='exampleFormControlSelect1'>Current Locations</label>
                            <ul id='lstLocation' class='list-group'></ul>
                        </div>
                    </div>

                    <br><br>
                    <div class="row">
                        <div class="col-md-10 submit-btn">
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
                                    data-target="#confirmEvent" <?php if($user->getUserLevel() == 'Tutor') echo "disabled" ?>
                                    onclick="updatePreviewModal()">Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once(__DIR__."/../phpObj/modalConfirm.php"); ?>
        </form>
    </div>
</div>
</body>
</html>

<?php
function checkRequiredfields()
{
    $inputData = array($_POST['selectCourse'], $_POST['selectCluster']);
    $errMess = [
        "Please select a valid Subject this event belong to.",
        "Please select a valid Cluster this event belong to.",
    ];
    $errCode = System::validateInput($inputData, "Select your option");
    if ($errCode != 0) {
        return $errMess[$errCode - 1];
    }

    if ($_POST['eventTime'] == "") {
        return "Please select a valid Date and Time of the New Event";
    }

    if ($_POST['txtEventLocation'] == "") {
        return "Please select locations of the New Event";
    }

    // Some event doesn't need location defined. Can do in edit event.
    return "OK";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errMess = checkRequiredfields();

    if ($errMess == "OK"){
        $eventName = $_POST['selectCourse'];
        $termYear = $_POST['txtTerm'];
        $referredName = $_POST['msg'];
        $clusterName = $_POST['selectCluster'];

        $labClose = $_POST["ifLabClose"];
        $startTimeOffset = System::convertOffset($_POST['startOffset']);
        $endTimeOffset = System::convertOffset($_POST['endOffset']);
        $closeLabOffset = System::convertOffset($_POST['labEndOffset']);
        $startLabOffset = System::convertOffset($_POST['labStartOffset']);

        $mGroupName = explode(", ", $_POST["txtEventLocation"]);
        $EventDate = explode(" ", $_POST['eventTime']);
        $formatDate = strtotime($EventDate[0]);
        $dayOfWeek = date( "w", $formatDate);
        $EventTime = $EventDate[1].":00";

        $weekOfYear = date( "W", $formatDate);
        $eventYear = date( "o", $formatDate);

        if ($termYear != null) {
            $eventName = $eventName . "-" . $termYear;
        }

        if ($referredName != null) {
            $eventName = $eventName . "-" . $referredName;
        }

        $current_event = new Event($eventName, $clusterName, '1', '1', '1', array());
        $current_event->addFrontEvent();
        if ($labClose == "true") {
            $current_event->close_lab($closeLabOffset);
        }
        $current_event->addFrontAction($startTimeOffset, $endTimeOffset);
        if ($labClose == "true") {
            $current_event->open_lab($startLabOffset);
        }

        $current_event->addFrontDaily($mGroupName, $dayOfWeek, $EventTime);
        $current_event->addFrontWeekly($weekOfYear, $eventYear);

        Bootstrap3::createAlert("Saved event with name: ".$_POST['selectCourse'] . " at ".$_POST['txtEventLocation'], 'home.php');
    } else {
        Bootstrap3::createAlert($errMess, 'createEvent.php');
    }
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript" src="../js/event/main.js"></script>