<?php
include("../../bd.php");
include("../../css/css_admin.php");
if (isset($_GET['txtID'])) {

    $txtID = isset($_GET['txtID']) ? $_GET['txtID'] : ''; // Recupera el dato SELECCIONADO

    $sentencia = $conexion->prepare("SELECT * FROM tbl_entrada where  id=:id");

    $sentencia->bindParam(":id", $txtID);

    $sentencia->execute();

    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    $fecha = $registro['fecha'];
    $titulo = $registro['titulo'];
    $descripcion = $registro['descripcion'];
    $imagen = $registro['imagen'];
    //print_r($registro);
}

if ($_POST) {
    //print_r($_POST);
    //echo $_POST;
    //Recepcionamos los datos por cada valor que contiene
    $txtID = (isset($_POST['txtID']) ? $_POST['txtID'] : "");
    $fecha = (isset($_POST['fecha']) ? $_POST['fecha'] : "");
    $titulo = (isset($_POST['titulo']) ? $_POST['titulo'] : "");
    $descripcion = (isset($_POST['descripcion']) ? $_POST['descripcion'] : "");

    $sentencia = $conexion->prepare("UPDATE tbl_entrada
    SET fecha=:fecha,titulo=:titulo,descripcion=:descripcion
    WHERE id=:id");

    $sentencia->bindParam(":fecha", $fecha);
    $sentencia->bindParam(":titulo", $titulo);
    $sentencia->bindParam(":descripcion", $descripcion);

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
        move_uploaded_file($tmp_imagen, "../../../assets/img/about/" . $nombre_archivo_imagen);

        //Borrado del archivo anterior en la carpeta correspondiente
        $sentencia = $conexion->prepare("SELECT imagen FROM tbl_entrada WHERE id=:id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();

        $registros_imagen = $sentencia->fetch(PDO::FETCH_LAZY);

        if (isset($registros_imagen["imagen"])) {
            if (file_exists("../../../assets/img/about/" . $registros_imagen["imagen"])) {
                //echo "Imagen encontrada";
                unlink("../../../assets/img/about/" . $registros_imagen["imagen"]);
            }
        }

        //Actualizamos la imagen
        $sentencia = $conexion->prepare("UPDATE tbl_entrada
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
    <div class="card-header">Actualizar lista del entradas</div>
    <div class="card-body">

        <form action="" enctype="multipart/form-data" method="post">

            <div class="mb-3">
                <label for="txtID" class="form-label">Id:</label>
                <input readonly value="<?php echo $txtID; ?>" type="text" class="form-control" name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID" />
            </div>

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha:</label>
                <input type="date" value="<?php echo $fecha; ?>" class="form-control" name="fecha" id="fecha" aria-describedby="helpId" placeholder="Fecha" />

            </div>

            <div class="mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" value="<?php echo $titulo; ?>" class="form-control" name="titulo" id="titulo" aria-describedby="helpId" placeholder="Título" />
            </div>


            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" value="<?php echo $descripcion; ?>" class="form-control" name="descripcion" id="descripcion" aria-describedby="helpId" placeholder="Descripción" />

            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <img style="border-radius: 50%; vertical-align: middle; margin-right: 10px; width: 50px;" src="../../../assets/img/abou/<?php echo $imagen; ?>" />
                <input type="file" class="form-control" name="imagen" id="imagen" placeholder="Imagen" aria-describedby="fileHelpId" />

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