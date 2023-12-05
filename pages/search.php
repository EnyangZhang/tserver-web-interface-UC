<?php
    require_once(__DIR__."/../lib/User.php");
    require_once(__DIR__."/../phpObj/Bootstrap3.php");
    require_once(__DIR__."/../lib/Event.php");
    require_once(__DIR__ ."/../lib/System.php");

    PageHeaderPHP();
    $user = unserialize($_SESSION['curr_user']);
    error_reporting(E_ERROR | E_PARSE);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tserver Search</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="../img/icons/home.ico"/>

    <link rel="stylesheet" href="../css/phpObj/searchEngine.css">
    <link rel="stylesheet" href="../css/phpObj/navbar.css">
    <link rel="stylesheet" href="../css/search/main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
<div class="wrapper">
    <?php include_once(__DIR__."/../phpObj/verticalNav.php"); ?>
    <div id="content">
        <?php include_once(__DIR__."/../phpObj/horizontalNav.php"); ?>
        <form class="search-engine" method="GET">
            <div class="row search-section">
                <div class="col-2">
                    <div class="search-input-group">
                        <label class="search-label">event name</label>
                        <input id="search-machine" class="input-style" type="text" placeholder="ie/STAT" name="event">
                    </div>
                </div>
                <p style="padding: 30px 10px 0px 10px">OR</p>
                <div class="col-2">
                    <div class="search-input-group">
                        <label class="search-label">from date (optional)</label>
                        <input class="input-style" type="text" name="from_date" id="input-start" placeholder="MM/DD/YYYY" autocomplete="off">
                    </div>
                </div>

                <div class="col-2">
                    <div class="search-input-group">
                        <label class="search-label">to date (optional)</label>
                        <input class="input-style" type="text" name="to_date" id="input-end" placeholder="MM/DD/YYYY" autocomplete="off">
                    </div>
                </div>
                <div class="col-btn">
                    <input class="btn-search" type="submit" name="btnSubmit" value="Search"/>
                </div>
            </div>
        </form>
        <div class="search-results">
            <?php
                System::searchResult();
            ?>
        </div>

        <?php include_once(__DIR__."/../phpObj/modalEdit.php"); ?>
    </div>
</div>
</body>
</html>

<?php
// edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_time = strtotime($_POST['eventTime']);
    $strtime = date("h:ia d/m/Y", $new_time);

    Event::editEvent(explode(", ", $_POST['txtLinker']), $_POST['txtName'], array($_POST['dropCluster'], $_POST['txtOldCluster']), 
        $new_time, explode(", ", $_POST['txtEventLoc']),  explode(", ", $_POST['txtNewEventLoc']));
    Bootstrap3::createAlert(sprintf("Saved event with name: %s (%s) at %s. Location changed from: %s to %s",
        $_POST['txtName'], $_POST['dropCluster'], $strtime, $_POST['txtEventLoc'], $_POST['txtNewEventLoc']), "search.php");
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="../js/search/main.js"></script>