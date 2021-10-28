<?php
// Include config file
require_once "config/configuracion.php";

// Define variables and initialize with empty values
$fecha_reserva = $hora_reserva = $meal = "";
$fecha_reserva_err= $hora_reserva_err = $meal_err  = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    // Validate  date
    $input_fecha_reserva = trim($_POST["fecha_reserva"]);
    if(empty($input_fecha_reserva)){
        $fecha_reserva_err = "Please enter date.";
    } elseif(!filter_var($input_fecha_reserva, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $fecha_reserva_err = "Please enter a valid date.";
    } else{
        $fecha_reserva = $input_fecha_reserva;
    }

    // Validate hora

    $input_hora_reserva = trim($_POST["hora_reserva"]);
    if(empty($input_hora_reserva)){
        $hora_reserva_err = "Please enter a name.";
    } elseif(!filter_var($input_hora_reserva, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $hora_reserva_err = "Please enter a valid name.";
    } else{
        $hora_reserva = $input_hora_reserva;
    }

    // Validate meal

    $input_meal = trim($_POST["meal"]);
    if(empty($input_meal)){
        $meal_err = "Please enter a name.";
    } elseif(!filter_var($input_meal, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $meal_err = "Please enter a valid name.";
    } else{
        $meal = $input_meal;
    }

    // Check input errors before inserting in database
    if(empty($nombre_err) && empty($fabricante_err) && empty($nombrelargo_err)&& empty($numdosis_err)){
        // Prepare an update statement
        $sql = "UPDATE prueba SET fecha_reserva=?, hora_reserva=?, meal=? WHERE id=?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssi",$param_fecha_reserva,$param_hora_reserva,$param_meal,$param_id);


            // Set parameters
            $param_fecha_reserva = $fecha_reserva;
            $param_hora_reserva = $hora_reserva;
            $param_meal = $meal;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: tureserva.php");
                exit();
            } else{
                echo "Oops! Algo fue mal. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $mysqli->close();
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM prueba WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $result = $stmt->get_result();

                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $fecha_reserva = $row["fecha_reserva"];
                    $hora_reserva = $row["hora_reserva"];
                    $meal= $row["meal"];

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Algo fue mal. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();

        // Close connection
        $mysqli->close();
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
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
        <h1> MODIFICAR REGISTRO</h1>
    </div>
    <div class="row ">
        <div class="col-lg-7 mx-auto">
            <div class="card mt-2 mx-auto p-4 bg-light">
                <div class="card-body bg-light">
                    <div class="container">
                        <form id="contact-form" role="form"action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                            <div class="controls">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"> <label for="form_fecha_reserva">Fecha </label> <input id="form_fecha_reserva" name="fecha_reserva" placeholder="Elije fecha *" class="form-control <?php echo (!empty($fecha_reserva_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fecha_reserva; ?>">
                                            <span class="invalid-feedback"><?php echo $fecha_reserva_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group"> <label for="form_hora_reserva">Hora</label> <input id="form_hora_reserva" name="hora_reserva" placeholder="hh:mm"  class="form-control <?php echo (!empty($hora_reserva_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $hora_reserva; ?>">
                                                <span class="invalid-feedback"><?php echo $hora_reserva_err; ?></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group"> <label for="form_meal">Comida</label> <input id="form_meal" name="meal" placeholder="Comida / Cena"  class="form-control <?php echo (!empty($meal_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $meal; ?>">
                                                    <span class="invalid-feedback"><?php echo $meal_err; ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                         <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                        <div class="col-md-8"><input type="submit" class="btn btn-success btn-send pt-2 btn-block " value="Guardar"></div>
                                        <div class="col-md-8"><a href="tureserva.php" class="btn btn-success btn-send pt-2 btn-block ">Cancelar</a></div>
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