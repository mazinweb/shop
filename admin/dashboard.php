<?php

session_start();
if (isset($_SESSION['logedin'])) {
    $pageTitle = 'Dash Board';
    include 'init.php';
    ?>
    <!--start dashboard page-->
    <div class="container home-state text-center">
        <h1>Dash Board</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="state st-members">
                    Total Members
                    <span><a href="members.php"><?php echo countitem('UserID', 'users') ?></a></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="state st-pending">
                    Pending Members
                    <span>200</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="state st-items">
                    Total Items
                    <span>200</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="state st-comments">
                    Total Comments
                    <span>200</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container latest">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-users"></i> Last Registered Users
                        <div class="panel-body">
                            test
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-tags"></i> Last Items
                        <div class="panel-body">
                            test
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--end dashboard page-->
    <?php
    include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}