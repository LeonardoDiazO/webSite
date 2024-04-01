<?php
include("../../bd.php");
include("../../css/css_admin.php");

if (isset($_GET['txtID'])) {

    $txtID = isset($_GET['txtID']) ? $_GET['txtID'] : ''; // Recupera el dato SELECCIONADO

    $sentencia = $conexion->prepare("SELECT * FROM tbl_equipo where  id=:id");

    $sentencia->bindParam(":id", $txtID);

    $sentencia->execute();

    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    $imagen = $registro['imagen'];
    $nombre_completo = $registro['nombre_completo'];
    $puesto = $registro['puesto'];
    $twitter = $registro['twitter'];
    $facebook = $registro['facebook'];
    $linkedin = $registro['linkedin'];
    //print_r($registro);
}

if ($_POST) {
    //print_r($_POST);
    //echo $_POST;
    //Recepcionamos los datos por cada valor que contiene
    $txtID = (isset($_POST['txtID']) ? $_POST['txtID'] : "");
    $nombre_completo = (isset($_POST['nombre_completo']) ? $_POST['nombre_completo'] : "");
    $puesto = (isset($_POST['puesto']) ? $_POST['puesto'] : "");
    $twitter = (isset($_POST['twitter']) ? $_POST['twitter'] : "");
    $facebook = (isset($_POST['facebook']) ? $_POST['facebook'] : "");
    $linkedin = (isset($_POST['linkedin']) ? $_POST['linkedin'] : "");

    $sentencia = $conexion->prepare("UPDATE tbl_equipo
    SET nombre_completo=:nombre_completo,puesto=:puesto,twitter=:twitter,facebook=:facebook,linkedin=:linkedin
    WHERE id=:id");

    $sentencia->bindParam(":nombre_completo", $nombre_completo);
    $sentencia->bindParam(":puesto", $puesto);
    $sentencia->bindParam(":twitter", $twitter);
    $sentencia->bindParam(":facebook", $facebook);
    $sentencia->bindParam(":linkedin", $linkedin);

    $sentencia->bindParam(":id", $txtID);

    $sentencia->execute();

    //Preguntamos si hay una imagen dentro de la carpeta
    if ($_FILES["imagen"]["name"] != "") {
        $imagen = (isset($_FILES['imagen']["name"])) ? $_FILES['imagen']["name"] : "";   //Recepcionamos la imagen

        $fecha_imagen = new DateTime();
        $nombre_archivo_imagen = ($imagen != "") ? $fecha_imagen->getTimestamp() . "_" . $imagen : "";
        $tmp_imagen = $_FILES["imagen"]["tmp_name"];
        //Validamos si existe la imagen para poder asignarla a la carpeta correspondiente

        //Mueve el archivo a la carpeta correspondiente
        move_uploaded_file($tmp_imagen, "../../../assets/img/team/" . $nombre_archivo_imagen);

        //Borrado del archivo anterior en la carpeta correspondiente
        $sentencia = $conexion->prepare("SELECT imagen FROM tbl_equipo WHERE id=:id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();

        $registros_imagen = $sentencia->fetch(PDO::FETCH_LAZY);

        if (isset($registros_imagen["imagen"])) {
            if (file_exists("../../../assets/img/team/" . $registros_imagen["imagen"])) {
                //echo "Imagen encontrada";
                unlink("../../../assets/img/team/" . $registros_imagen["imagen"]);
            }
        }

        //Actualizamos la imagen
        $sentencia = $conexion->prepare("UPDATE tbl_equipo
        SET imagen=:imagen WHERE id=:id");
        $sentencia->bindParam(":imagen", $nombre_archivo_imagen);
        $sentencia->bindParam(":id", $txtID);
        $imagen = $nombre_archivo_imagen;
        $sentencia->execute();
    }


    $mensaje = "Registro modificado exitosamente";
    header("Location:index.php?mensaje=" . $mensaje);
}

include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">Actualizar lista del equipo</div>
    <div class="card-body">

        <form action="" enctype="multipart/form-data" method="post">

            <div class="mb-3">
                <label for="txtID" class="form-label">Id:</label>
                <input readonly value="<?php echo $txtID; ?>" type="text" class="form-control" name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID" />
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <img style="border-radius: 50%; vertical-align: middle; margin-right: 10px; width: 50px;" src="../../../assets/img/abou/<?php echo $imagen; ?>" />
                <input type="file" class="form-control" name="imagen" id="imagen" placeholder="Imagen" aria-describedby="fileHelpId" />

            </div>

            <div class="mb-3">
                <label for="nombre_completo" class="form-label">Nombre:</label>
                <input type="text" value="<?php echo $nombre_completo; ?>" class="form-control" name="nombre_completo" id="nombre_completo" aria-describedby="helpId" placeholder="Nombre Completo" />

            </div>

            <div class="mb-3">
                <label for="puesto" class="form-label">Puesto:</label>
                <input type="text" value="<?php echo $puesto; ?>" class="form-control" name="puesto" id="puesto" aria-describedby="helpId" placeholder="Puesto o Cargo" />
            </div>


            <div class="mb-3">
                <label for="twitter" class="form-label">Twitter:</label>
                <input type="text" value="<?php echo $twitter; ?>" class="form-control" name="twitter" id="twitter" aria-describedby="helpId" placeholder="Twitter" />

            </div>

            <div class="mb-3">
                <label for="facebook" class="form-label">Facebook:</label>
                <input type="text" value="<?php echo $facebook; ?>" class="form-control" name="facebook" id="facebook" aria-describedby="helpId" placeholder="Facebook" />

            </div>

            <div class="mb-3">
                <label for="linkedin" class="form-label">Linkedin:</label>
                <input type="linkedin" value="<?php echo $linkedin; ?>" class="form-control" name="linkedin" id="linkedin" aria-describedby="helpId" placeholder="Linkedin" />

            </div>

            <button type="submit" class="btn btn-success">
                Actualizar
            </button>


            <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>


        </form>
    </div>
</div>

<?php

include("../../templates/footer.php");
?>