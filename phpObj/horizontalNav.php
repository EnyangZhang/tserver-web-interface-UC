<!-- Page Content Holder -->

<nav class="navbar navbar-default">
    <div id="horizonNav" class="container-fluid">
        <div class="col-sm-2" style="display:inline;">
            <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
                <i class="glyphicon glyphicon-align-left"></i>
            </button>
        </div>
        <div class="col-sm-10" style="display:inline;">
            <ul id="leftUl" class="nav navbar-nav navbar-right" style="float:right; margin-bottom:0px; margin-top:0px;" >
                <li style="display:inline-block;">
                    <a href="search.php">
                        <svg width="1.5em" height="2em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                            <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                        </svg>
                    </a>
                </li>

                <li style="display:inline-block; padding-left:10px;">
                    <div class="dropdown">
                        <button class="dropdown-toggle icon-no-margin" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <?php echo $user->getUserLevel().': '.$user->getUserName(); ?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><a href="profile.php"><span style="font-size:18px" class="fa dropdown-icon">&#xf2be;</span>Profile</a></li>
                            <!-- <li><a href="#"><span style="font-size:22px" class="fa dropdown-icon">&#xf013;</span>Preferences</a></li> -->
                            <li><a href="https://assist.canterbury.ac.nz/selfservice/application.jsp#services/49"><span style="font-size:23px" class="fa fa-phone dropdown-icon" aria-hidden="true"></span>Contact IT support</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a role="button" onclick="confirmLogout()"><span style="font-size:18px" class="glyphicon dropdown-icon">&#xe163;</span>Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">
    function confirmLogout() {
        var r = confirm("Are you sure you wanna log out?");
        if (r == true) {
            window.location.href="logout.php";
        }
    }

    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');

            var panel = document.getElementById('leftUl');
            var w = document.getElementById('horizonNav').offsetWidth;
            
            if (panel.style.display == "none"){
                panel.style.display = "block";
            } else {    
                if (w < 500) 
                    panel.style.display = "none";
                
            }
        });

        $('#action-menu-toggle-1').on('click', function(){
            $('')
        })
    });
</script>
