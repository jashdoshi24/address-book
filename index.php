<?php
require_once "includes/functions.php";
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

    <!-- Add a New Contact Link-->
    <div class="row mt50">
        <div class="col s12 right-align">
            <a class="btn waves-effect waves-light blue lighten-2" href="add-contact.php"><i
                    class="material-icons left">add</i> Add
                New</a>
        </div>
    </div>
    <!-- /Add a New Contact Link-->

    <!-- Table of Contacts -->
    <div class="row">
        <div class="col s12">
            <table class="highlight centered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Email ID</th>
                        <th>Date Of Birth</th>
                        <th>Phone Number</th>
                        <th>Address</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
<?php
                    //HANDLING PAGINATION
                    $page=1;
                    if(isset($_GET['page'])){
                        $page = $_GET['page'];
                    }
                    $no_of_records_per_page = 5;
                    $start = ($page-1) * $no_of_records_per_page;

                    $result = db_select("SELECT COUNT(*) AS total FROM contacts");
                    if(!$result){
                        dd(db_error());
                    }
                    $total_count = $result[0]['total'];
                    $total_pages = ceil($total_count / $no_of_records_per_page);

                    if($page > $total_pages){
                        //LOAD page 404
                        dd("How did you reached here?");
                    }
                    $query = "SELECT * FROM contacts LIMIT $start,$no_of_records_per_page";
                    $result = db_select($query);
                    if(!$result){
                        dd(db_error());
                    }

                    foreach($result as $row):
?>
                    <tr>
                        <td><img class="circle" src="images/users/<?= $row['image_name'] ?>" alt="" height="78px" width="78px"></td>
                        <td><?= $row['first_name']." ".$row['last_name'];?></td>
                        <td><?= $row['email'];?></td>
                        <td><?= $row['birthdate'];?></td>
                        <td><?= $row['telephone'];?></td>
                        <td><?= $row['address'];?></td>
                        <td><a href="edit-contact.php?id=<?=$row['id'];?>" class="btn btn-floating green lighten-2"><i class="material-icons">edit</i></a></td>
                        <td>
                            <a data-id="<?=$row['id'];?>" class="btn btn-floating red lighten-2 modal-trigger delete-contact" href="#deleteModal"><i class="material-icons">delete_forever</i></a>
                        </td>
                    </tr>
<?php
                    endforeach;
?>                
                    
                </tbody>
            </table>
        </div>
    </div>
    <!-- /Table of Contacts -->
    <!-- Pagination -->
    <div class="row">
        <div class="col s12">
            <ul class="pagination">
                <li class="<?= $page==1 ? 'disabled': 'waves-effect';?>">
                    <a href="<?=$page>1 ? '?page='.($page-1) : '#';?>">
                        <i class="material-icons">chevron_left</i>
                    </a>
                </li>
<?php
                for($i=1;$i<=$total_pages;$i++):
?>
                    <li class="waves-effect <?=$page==$i ? 'active' : '';?>" >
                        <a href="?page=<?= $i;?>"><?=$i;?></a>
                    </li>
                
<?php
                endfor;
?>
                <li class="waves-effect">
                    <a href="<?= $page<$total_pages ? '?page='.($page+1) : '#';?>">
                        <i class="material-icons">chevron_right</i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Pagination -->
    <!-- Footer -->
    <footer class="page-footer p0">
        <div class="footer-copyright ">
            <div class="container">
                <p class="center-align">© 2020 Study Link Classes</p>
            </div>
        </div>
    </footer>
    <!-- /Footer -->
    <!-- Delete Modal Structure -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h4>Delete Contact?</h4>
            <p>Are you sure you want to delete the record?</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close btn blue-grey lighten-2 waves-effect">Cancel</a>
            <a id="modal-agree-button" href="#!" class="modal-close btn waves-effect red lighten-2">Agree</a>
        </div>
    </div>
    <!-- /Delete Modal Structure -->
    <!--JQuery Library-->
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <!--JavaScript at end of body for optimized loading-->
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <!--Include Page Level Scripts-->
    <script src="js/home.js"></script>
    <!--Custom JS-->
    <script src="js/custom.js" type="text/javascript"></script>

    <script>
    var color = "green draken-4";
<?php
        $q = "";
        $op = "";
        if(isset($_GET['q'])){
            $q = $_GET['q'];
        }
        if(isset($_GET['op'])){
            $op = $_GET['op'];
        }
        if($q==="success" && $op==="insert"):
?>
            var toastHTML = '<span>New Contact Added Successfully!</span>';
<?php
        elseif($q==="success" && $op==="update"):
?>
            var toastHTML = '<span>Contact Updated Successfully!</span>'; 
<?php
        elseif($q==="success" && $op==="del"):
?>
            var toastHTML = '<span>Contact Deleted Successfully!</span>'; 
<?php
        elseif($q==="error" && $op==="del"):
?>
            var toastHTML = '<span>Issue While Deleting</span>';
            color = "red draken-4";
<?php
            
        endif;
?>
        M.toast({
            html: toastHTML,
            classes: color
        });
    </script>

</body>

</html>