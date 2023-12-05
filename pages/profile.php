<?php
    require_once(__DIR__."/../lib/User.php");
    require_once(__DIR__."/../phpObj/Bootstrap3.php");

    PageHeaderPHP();
    $user = unserialize($_SESSION['curr_user']);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tserver Profile</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="../img/icons/home.ico"/>

    <link rel="stylesheet" href="../css/profile/profile.css">
    <link rel="stylesheet" href="../css/phpObj/navbar.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="wrapper">
        <?php include_once(__DIR__."/../phpObj/verticalNav.php"); ?>
        <div id="content">
            <?php include_once(__DIR__."/../phpObj/horizontalNav.php"); ?>
        
            <div class="container-profile">
                <form method="POST">
                    <div class="profile-content col-lg-12">
                        <h1 class="profile-content">Profile</h1>
                        <div class="row-content">
                            <img class="profile-img" src="../img/avatar/no_profile.png" alt="Avatar">
                            <span class="glyphicon glyphicon-pencil" role="button" title="Upload photo" aria-hidden="true"></span>
                            <span class="profile-username"><?php echo $user->getUserName();?></span>
                            <div class="edit-box" id="controlPanel">
                                <a class="btn btn-primary" id="btnEdit" onclick="editOnPage()">Quick edit</a>
                                <input class="btn btn-success" id="btnSubmit" type="submit" value="Save changes" style="display: none;" />
                            </div>
                        </div>
                    </div>

                    <div class="profile-box col-md-6">
                        <div class="profile-tree col-md-12">
                            <br>
                            <div class="well well-lg">
                                <section class="category">
                                    <h2>Contact details</h2>
                                    <ul>
                                        <li class="content-node">
                                            <dt>Email address</dt>
                                            <dd><?php echo $user->getEmail();?></dd>
                                            <!-- <dd><a href="mailto:name@example.com">bvv10@uclive.ac.nz</a></dd> -->
                                            <p id="errEmail"></p>
                                        </li>
                                        <li class="content-node">
                                            <dt>Phone number</dt>
                                            <dd><?php echo $user->getPhone();?></dd>
                                            <p id="errPhone"></p>
                                        </li>
                                    </ul>
                                </section>
                            </div>
                        </div>
                        
                        <br>
                        <div class="profile-tree col-md-12">
                            <br>
                            
                            <div class="well well-lg">
                                <section class="category">
                                    <h2>Addresses</h2>
                                    <ul>
                                        <li class="content-node">
                                            <dt>Home address</dt>
                                            <dd><?php echo $user->getAddress();?></dd>
                                        </li>
                                        <li class="content-node">
                                            <dt>City</dt>
                                            <dd><?php echo $user->getCity();?></dd>
                                        </li>
                                        <li class="content-node">
                                            <dt>Country</dt>
                                            <dd><?php echo $user->getCountry();?></dd>
                                        </li>
                                    </ul>
                                </section>
                            </div>                            
                        </div>
                    </div>

                    <div class="profile-tree col-md-6">
                        <br>
                        <div class="well well-lg">
                            <section class="category">
                                <h2>Additional Information</h2>
                                <ul>
                                    <li class="content-node">
                                        <dt>Gender</dt>
                                        <dd><?php echo $user->getGender();?></dd>
                                    </li>
                                    <li class="content-node">
                                        <dt>Date of birth</dt>
                                        <dd><?php echo $user->getDoB();?></dd>
                                    </li>
                                    <li class="content-node">
                                        <dt>Role</dt>
                                        <dd><?php echo $user->getUserLevel(); ?></dd>
                                    </li>
                                </ul>
                            </section>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<script type="text/javascript" src="../js/profile/main.js"></script>

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $user->setEmail($_POST['email']);
        $user->setPhone($_POST['phone']);
        $user->setAddress($_POST['address']);
        $user->setCity($_POST['city']);
        $user->setCountry($_POST['country']);
        $user->setGender($_POST['gender']);
        $user->setDoB($_POST['dob']);
        $_SESSION['curr_user'] = serialize($user);
        $user->updateUserCredentials();
        Bootstrap3::createAlert("Saved", "profile.php");
    }
?>