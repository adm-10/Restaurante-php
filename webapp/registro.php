<?php
// Include config file
require_once "config/configuracion.php";

// Define variables and initialize with empty values
$name =$surname = $email = $telephone = $password = $confirm_password = "";
$name_err= $surname_err =$email_err = $telephone_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }

    // Validate surname

    $input_surname = trim($_POST["surname"]);
    if(empty($input_surname)){
        $surname_err = "Please enter a surname.";
    } elseif(!filter_var($input_surname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $surname_err = "Please enter a valid name.";
    } else{
        $surname = $input_surname;
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a username.";
    } elseif(!preg_match('/^\S+@\S+\.\S+$/', trim($_POST["email"]))){
        $email_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM usuarios WHERE email = ?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();

                if($stmt->num_rows == 1){
                    $email_err = "This username is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate telephone

    $input_telephone = trim($_POST["telephone"]);
    if(empty($input_telephone)){
        $telephone_err = "Please enter a name.";
    } elseif(!filter_var($input_telephone, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9]{9,9}$/")))){
        $telephone_err = "Please enter a valid name.";
    } else{
        $telephone = $input_telephone;
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if( empty($name_err) && empty($surname_err) && empty($email_err) && empty($telephone_err) && empty($password_err) &&  empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO usuarios (name,surname,email,telephone, password) VALUES (?,?,?,?,?)";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss",$param_name,$param_surname,$param_email,$param_telephone,$param_password );

            // Set parameters
            $param_name = $name;
            $param_surname = $surname;
            $param_email = $email;
            $param_telephone = $telephone;
            $param_password = password_hash($password, PASSWORD_BCRYPT); // Creates a password hash

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Main CSS-->
    <link href="css/main.css" rel="stylesheet" media="all">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

</head>

<body>

<div class="container"> <div class=" text-center mt-5 ">
        <h1>REGISTRO</h1>
    </div>
    <div class="row ">
        <div class="col-lg-7 mx-auto">
            <div class="card mt-2 mx-auto p-4 bg-light">
                <div class="card-body bg-light">
                    <div class="container">
                        <form id="contact-form" role="form" action="<?php echo htmlspecialchars($_SERVER["SCRIPT_NAME"]); ?>" method="post">
                            <div class="controls">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"> <label for="form_name">Nombre *</label> <input id="form_name" name="name" placeholder="Please enter your firstname *" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"> <label for="form_lastname">Apellido *</label> <input id="form_lastname" name="surname" placeholder="Please enter your surname *"  class="form-control <?php echo (!empty($surname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $surname; ?>">
                                            <span class="invalid-feedback"><?php echo $surname_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group"> <label for="form_email">Email *</label> <input id="form_email" name="email" placeholder="Please enter your email *"  class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                                                <span class="invalid-feedback"><?php echo $email_err; ?></span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group"> <label for="form_telefono">Telefono *</label> <input id="form_telefono" name="telephone" placeholder="Please enter your telephone *"  class="form-control <?php echo (!empty($telephone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $telephone; ?>">
                                                    <span class="invalid-feedback"><?php echo $telephone_err; ?></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group"> <label  for ="form_password">Password *</label> <input type="password" id="form_password" name="password" placeholder="Please enter your confirm_password *"  class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                                                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group"> <label  for ="confirm_password"> Confirm Password *</label> <input type="password"  id="form_confirm_password" name="confirm_password" placeholder="Confirm Password  *"  class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                                                                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="col-md-8"> <input type="submit" class="btn btn-success btn-send pt-2 btn-block " value="Submit"> </div>
                                                    <hr>
                                                    <div class="col-md-8"> <input type="reset" class="btn btn-success btn-send pt-2 btn-block " value="Reset"> </div>
                                                    <p>Already have an account? <a href="login.php">Login here</a>.</p>
                        </form>
                    </div>
                </div>
            </div> <!-- /.8 -->
        </div> <!-- /.row-->
    </div>
</div>
<!-- Main JS-->


</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>