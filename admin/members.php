<?php
/*
 * members page here you can edit | add | delete members*/
session_start();
if (isset($_SESSION['logedin'])) {
    $pageTitle = 'Members';
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    //start manage page/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ($do == 'Manage') {
        //select all user from database expect admin
        $stmt = $conn->prepare("SELECT * FROM users WHERE GroupID != 1");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <!--manage members page -->
        <h1 class="text-center">Manage Member</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Register Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['UserID'] . "</td>";
                        echo "<td>" . $row['Username'] . "</td>";
                        echo "<td>" . $row['Email'] . "</td>";
                        echo "<td>" . $row['FullName'] . "</td>";
                        echo "<td>" . $row['Date'] . "</td>";
                        echo " 
                            <td>
                            <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                            <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>
                            </td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
        </div>

        <!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
    <?php } elseif ($do == 'Add') {  // add page
        ?>
        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- start user filed -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">User Name :</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="username" class="form-control" autocomplete="off" required="required"
                               placeholder="Enter User name"/>
                    </div>
                </div>
                <!-- end user filed -->
                <!-- start password filed -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Password :</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="password" name="password" class="password form-control" autocomplete="new-password"
                               required="required" placeholder="Enter Password"/>
                        <i class="show-pass fa fa-eye fa-1x"></i>
                    </div>
                </div>
                <!-- end password filed -->
                <!-- start email filed -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">E-mail :</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="email" name="email" class="form-control" required="required"
                               placeholder="Enter E-mail"/>
                    </div>
                </div>
                <!-- end email filed -->
                <!-- start full name filed -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Full Name :</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="full" class="form-control" required="required"
                               placeholder="Enter Full name"/>
                    </div>
                </div>
                <!-- end full name filed -->
                <!-- start button filed -->
                <div class="form-group">
                    <div class=" col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Member" class="btn btn-success"/>
                    </div>
                </div>
                <!-- end button filed -->
            </form>
        </div>

        <?php
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    } elseif ($do == 'Insert') { // insert user into database

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //get variables from the form of edit user
            echo "<h1 class='text-center'>Insert New Member</h1>";
            echo "<div class='container'>";

            $user = $_POST['username'];
            $pass = $_POST['password'];
            $email = $_POST['email'];
            $name = $_POST['full'];
            $hasdpass = sha1($_POST['password']);

            //validate the form of update
            $formerror = array();
            if (strlen($user) < 4) {
                $formerror[] = 'user name can not be less than <strong>4 characters</strong>';

            }
            if (strlen($user) > 20) {
                $formerror[] = 'user name can not be more  than <strong>20 characters</strong> ';

            }
            if (empty($user)) {
                $formerror[] = 'user name can not be <strong>Empty</strong>';

            }
            if (empty($pass)) {
                $formerror[] = 'Password can not be <strong>Empty</strong>';

            }
            if (empty($email)) {
                $formerror[] = 'email can not be<strong>Empty</strong> ';

            }
            if (empty($name)) {
                $formerror[] = 'full name can not be <strong>Empty</strong>';

            }
            foreach ($formerror as $error) {
                echo ' <div class="alert alert-danger"> ' . $error . '</div>';
            }
            //check if there's no error in the form
            if (empty($formerror)) {
                //check if user is exist in database
                $check = checker("Username", "users", $user);
                if ($check == 1) {
                    $msg = '<div class="alert alert-danger">Sorry This User is Exist</div> ';
                    redirect($msg, 'back');
                } else {


                    // insert new user in the database with new info

                    $stmt = $conn->prepare("INSERT INTO users (Username,Password,Email,FullName,RegStatus,Date) VALUES (:zuser,:zpass,:zmail,:zname,:zreg,now())");
                    $stmt->execute(array('zuser' => $user, 'zpass' => $hasdpass, 'zmail' => $email, 'zname' => $name ,'zreg' => 1));

                    echo "<div class='container'>";
                    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' ' . "Record Inserted thanks to you</div> ";
                    redirect($msg, 'back');
                    echo "</div>";
                }
            }

        } else {
            $msg = '<div class="alert alert-danger">you are not allowed to be here </div>';
            redirect($msg, 'back');
        }
        echo "</div>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    } elseif ($do == 'Edit') { // edit page
        $user = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1 ");
        $stmt->execute(array($user));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($stmt->rowCount() > 0) {
            ?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="usrid" value="<?php echo $user ?>"/>
                    <!-- start user filed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">User Name :</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="username" value="<?php echo $row['Username'] ?>"
                                   class="form-control" autocomplete="off" required="required"/>
                        </div>
                    </div>
                    <!-- end user filed -->
                    <!-- start password filed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Password :</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>"/>
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password"
                                   placeholder="Leave Blank If you dont want to cahnge "/>
                        </div>
                    </div>
                    <!-- end password filed -->
                    <!-- start email filed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">E-mail :</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control"
                                   required="required"/>
                        </div>
                    </div>
                    <!-- end email filed -->
                    <!-- start full name filed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Full Name :</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control"
                                   required="required"/>
                        </div>
                    </div>
                    <!-- end full name filed -->
                    <!-- start button filed -->
                    <div class="form-group">
                        <div class=" col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Update" class="btn btn-success"/>
                        </div>
                    </div>
                    <!-- end button filed -->
                </form>
            </div>
            <?php
        } else {
            echo "<div class='container'>";
            $msg = '<div class="alert alert-danger">Theres no such id like that</div> ';
            redirect($msg);
            echo "</div>";
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    } elseif ($do == 'Update') {  // update page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //get variables from the form of edit user
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";

            $id = $_POST['usrid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['full'];
            //password trick
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
            //validate the form of update
            $formerror = array();
            if (strlen($user) < 4) {
                $formerror[] = 'user name can not be less than <strong>4 characters</strong>';

            }
            if (strlen($user) > 20) {
                $formerror[] = 'user name can not be more  than <strong>20 characters</strong> ';

            }
            if (empty($user)) {
                $formerror[] = 'user name can not be <strong>Empty</strong>';

            }
            if (empty($email)) {
                $formerror[] = 'email can not be<strong>Empty</strong> ';

            }
            if (empty($name)) {
                $formerror[] = 'full name can not be <strong>Empty</strong>';

            }
            foreach ($formerror as $error) {
                echo ' <div class="alert alert-danger"> ' . $error . '</div>';
            }
            //check if there's no error in the form
            if (empty($formerror)) {
                // update the database with new info
                $stmt = $conn->prepare("UPDATE users SET Username =? , Email =? ,FullName =? , Password = ? WHERE UserID =?");
                $stmt->execute(array($user, $email, $name, $pass, $id));
                $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' ' . "Record Updated thanks to you</div> ";
                redirect($msg, 'back');
            }

        } else {
            $msg = '<div class="alert alert-danger">you are not allowed to be here </div>';
            redirect($msg);
        }
        echo "</div>";
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    } elseif ($do == 'Delete') { // delete members from database
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";
        $user = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $check = checker('userid', "users", $user);

        if ($check > 0) {
            $stmt = $conn->prepare("DELETE FROM users WHERE UserID = :zuser");
            $stmt->bindParam(":zuser", $user);
            $stmt->execute();
            $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' ' . "Record Deleted thanks to you</div> ";
            redirect($msg);
        } else {
            $msg = '<div class="alert alert-danger">No id such like that </div>';
            redirect($msg);
        }

    }
    echo "</div>";
    include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}