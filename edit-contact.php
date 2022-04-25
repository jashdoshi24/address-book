<?php
require_once "includes/functions.php";
$error_flag = false;
$page_load_flag = true;
if(isset($_GET['id']) || isset($_POST['id']))
{
    $id = sanitize_input($_REQUEST['id']);
    $page_load_flag = false;
}
if(isset($_POST['id']))
{
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);

    $email = sanitize_input($_POST['email']);

    $birthdate = sanitize_input($_POST['birthdate']);
    $birthdate = date('Y-m-d',strtotime($birthdate));

    $telephone = sanitize_input($_POST['telephone']);
    $address = sanitize_input($_POST['address']);

    $image_has_been_changed = false;
    $image_name = strtolower($first_name . "-" . $last_name);
    if(isset($_FILES['pic']['name']) && !empty($_FILES['pic']['name']))
    {
        $image_has_been_changed = true;
        $file_name = $_FILES['pic']['name'];
        $file_tmp = $_FILES['pic']['tmp_name'];

        $temp = explode(".", $file_name);
        $file_extension = strtolower(end($temp));

        $image_name .= "." . $file_extension;
        move_uploaded_file($file_tmp, "images/users/$image_name");
    }

    //TRICKY PART
    $row = db_select("SELECT * FROM contacts WHERE id = $id");
    if(!$row)
    {
        dd(db_error());
    }
    else
    {
        $old_file_name = $row[0]['image_name'];

        $path_to_imge = "images/users/";
        if($image_has_been_changed)
        {
            if($old_file_name === $image_name)
            {
                //Nothing to do, as image was already overwritten!
            }
            else
            {
                unlink($path_to_imge . $old_file_name);
            }
        }
        else
        {
            //I have to copy the old extension in new image file name
            $temp = explode(".", $old_file_name);
            $old_file_extension = strtolower(end($temp));
            $image_name .= ".".$old_file_extension;
            if($old_file_name === $image_name)
            {

            }
            else
            {
                $old_file_path = $path_to_imge . $old_file_name;
                $new_file_path = $path_to_imge . $image_name;
                rename($old_file_path, $new_file_path);
            }
        }
    }
    $query = "UPDATE contacts SET `first_name` = '$first_name', `last_name` = '$last_name',`email` = '$email',`birthdate` = '$birthdate',`telephone` = '$telephone',`address` = '$address',`image_name` = '$image_name' WHERE id = $id ";

    $result = db_query($query);

    if($result)
    {
        header("Location: index.php?q=success&op=update");
    }
    else
    {
        $error_flag = true;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />

    <!--Import Csutom CSS-->
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Add Contact</title>
</head>

<body>
    <!--NAVIGATION BAR-->
    <nav>
        <div class="nav-wrapper">
            <!-- Dropdown Structure -->
            <ul id="dropdown1" class="dropdown-content">
                <li><a href="#!">Profile</a></li>
                <li><a href="#!">Signout</a></li>
            </ul>
            <nav>
                <div class="nav-wrapper">
                    <a href="#!" class="brand-logo center">Contact Info</a>
                    <ul class="right hide-on-med-and-down">

                        <!-- Dropdown Trigger -->
                        <li><a class="dropdown-trigger" href="#!" data-target="dropdown1"><i
                                    class="material-icons right">more_vert</i></a></li>
                    </ul>
                </div>
            </nav>
            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        </div>
    </nav>
    <!--/NAVIGATION BAR-->
<?php
    if($page_load_flag):
?>
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <h1>Invalid Access to the Pages!</h1>
                </div>
            </div>
        </div>
<?php
    else:
?>
    <div class="container">
        <div class="row mt50">
            <h2>Edit Contact</h2>
        </div>
<?php
        if($error_flag):
?>
        <div class="row">
            
            <div class="materialert error">
                <div class="material-icons">error_outline</div>
                Oh! What a beautiful alert :)
                <button type="button" class="close-alert">×</button>
            </div>
            <!-- <div class="materialert success">
                <div class="material-icons">check</div>
                Oh! What a beautiful alert :)
                <button type="button" class="close-alert">×</button>
            </div>
            <div class="materialert warning">
                <div class="material-icons">warning</div>
                Oh! What a beautiful alert :)
                <button type="button" class="close-alert">×</button>
             </div>-->
        </div>
<?php
        endif;
?>
<?php
    $row = db_select("SELECT * FROM contacts WHERE id = $id");
   
?>
        <div class="row">
            <form class="col s12 formValidate" action="<?= $_SERVER['PHP_SELF'];?>" id="add-contact-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="<?=$id;?>" name="id">
                <div class="row mb10">
                    <div class="input-field col s6">
                        <input id="first_name" name="first_name" type="text" class="validate" data-error=".first_name_error" value="<?=$row[0]['first_name'];?>">
                        <label for="first_name">First Name</label>
                        <div class="first_name_error "></div>
                    </div>
                    <div class="input-field col s6">
                        <input id="last_name" name="last_name" type="text" class="validate" data-error=".last_name_error" value="<?=$row[0]['last_name'];?>">
                        <label for="last_name">Last Name</label>
                        <div class="last_name_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="input-field col s6">
                        <input id="email" name="email" type="email" class="validate" data-error=".email_error" value="<?=$row[0]['email'];?>">
                        <label for="email">Email</label>
                        <div class="email_error "></div>
                    </div>
                    <div class="input-field col s6">
                        <input id="birthdate" name="birthdate" type="text" class="datepicker" data-error=".birthday_error" value="<?=$row[0]['birthdate'];?>">
                        <label for="birthdate">Birthdate</label>
                        <div class="birthday_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="input-field col s12">
                        <input id="telephone" name="telephone" type="tel" class="validate" data-error=".telephone_error" value="<?=$row[0]['telephone'];?>">
                        <label for="telephone">Telephone</label>
                        <div class="telephone_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="input-field col s12">
                        <textarea id="address" name="address" class="materialize-textarea" data-error=".address_error"><?=$row[0]['address'];?></textarea>
                        <label for="address">Addess</label>
                        <div class="address_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="col s2">
                        <img id="temp_pic" src="images/users/<?=$row[0]['image_name'];?>">
                    </div>
                    <div class="col s1"></div>
                    <div class="file-field input-field col s9">
                        <div class="btn">
                            <span>Image</span>
                            <input type="file" name="pic" id="pic" data-error=".pic_error">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Upload Your Image">
                        </div>
                        <div class="pic_error "></div>
                    </div>
                </div>
                <button class="btn waves-effect waves-light right" type="submit" name="action">Submit
                        <i class="material-icons right">send</i>
                    </button>
            </form>
        </div>
    </div>
    <footer class="page-footer p0">
        <div class="footer-copyright ">
            <div class="container">
                <p class="center-align">© 2020 Study Link Classes</p>
            </div>
        </div>
    </footer>
    <!--JQuery Library-->
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <!--JavaScript at end of body for optimized loading-->
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <!--JQuery Validation Plugin-->
    <script src="vendors/jquery-validation/validation.min.js" type="text/javascript"></script>
    <script src="vendors/jquery-validation/additional-methods.min.js" type="text/javascript"></script>
    <!--Include Page Level Scripts-->
    <script src="js/pages/edit-contact.js"></script>
    <!--Custom JS-->
    <script src="js/custom.js" type="text/javascript"></script>
<?php
        endif;
?>
</body>

</html>