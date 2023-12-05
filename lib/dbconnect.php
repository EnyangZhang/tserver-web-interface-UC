<?php
    require_once(__DIR__.'/config.php');
    require_once(__DIR__.'/../phpObj/Bootstrap3.php');

    function ConnectDB() {
        global $hostname, $username, $password, $database;

        $conn = new mysqli($hostname, $username, $password, $database);
        if ($conn->connect_error) {
            Bootstrap3::createAlert("Connection Error!");
        }
        return $conn;
    }

    function QueryDB($query, array $argv) {
        $conn = ConnectDB();
        if ($conn->connect_error) {
            Bootstrap3::createAlert("Lost connection during query.");
            return false;
        }
        $statement = vsprintf($query, $argv); 
        // echo $statement;
        // Bootstrap3::createAlert($statement);
        $exec = mysqli_query($conn, $statement);

        // Bootstrap3::createAlert($conn->error);
        $conn->close();
        return $exec;
    }

    // Call this method at the start of every page
    function PageHeaderPHP(){
        session_start();
        if (!isset($_SESSION['curr_user'])) {
            header("Location: logout.php");
        }
        CheckInactivity();
    }

    // Auto check when a page reloaded or redirect
    function CheckInactivity()  {
        // $_SESSION["LAST_ACTIVE"] update only here and upon login
        $max_inactive = 600; // in sec
        if(isset($_SESSION["curr_user"]))  
        { 
            $interval = time()-$_SESSION["LAST_ACTIVE"];
            if($interval > $max_inactive)   
            {
                $message = sprintf("You have been INACTIVE for %d minutes (Max %d minutes)", (int)($interval/60), (int)($max_inactive/60));
                Bootstrap3::createAlert($message, "logout.php");
            } 
            else {
                // Update timestamp when go to new page
                $_SESSION["LAST_ACTIVE"] = time(); 
            }
        }
    }

    // Call this method when executed a task successfully
    function UpdateActivity() {
        $_SESSION["LAST_ACTIVE"] = time(); 
    }
?>