<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "config/configuracion.php";

    // Prepare a select statement
    $sql = "SELECT * FROM prueba WHERE id = ?";

    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $result->fetch_array(MYSQLI_ASSOC);

                // Retrieve individual field value
                $fecha_reserva = $row["fecha_reserva"];
                $hora_reswerva = $row["hora_reserva"];
                $meal = $row["meal"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
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
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Vacuna</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mt-5 mb-3">Ver Reserva</h1>
                <div class="form-group">
                    <label>Fecha Reserva</label>
                    <p><b><?php echo $row["fecha_reserva"]; ?></b></p>
                </div>
                <div class="form-group">
                    <label>hora Reserva</label>
                    <p><b><?php echo $row["hora_reserva"]; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Comida</label>
                    <p><b><?php echo $row["meal"]; ?></b></p>
                </div>
                <div class="col-md-4"><a href="tureserva.php" class="btn btn-success btn-send pt-2 btn-block ">Volver</a></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>