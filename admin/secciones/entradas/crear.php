<?php
include("../../bd.php");
include("../../css/css_admin.php");
if ($_POST) {

    //print_r($_POST);
    //Recepcionamos los datos por cada valor que contiene
    $fecha = (isset($_POST['fecha']) ? $_POST['fecha'] : "");
    $titulo = (isset($_POST['titulo']) ? $_POST['titulo'] : "");
    $descripcion = (isset($_POST['descripcion']) ? $_POST['descripcion'] : "");
    $imagen = (isset($_FILES['imagen']["name"])) ? $_FILES['imagen']["name"] : ""; //Recepcionamos la imagen
    

    //Almacenamos la imagen en la carpeta correspondiente 
    $fecha_imagen=new DateTime();
    $nombre_archivo_imagen=($imagen!="")? $fecha_imagen->getTimestamp()."_".$imagen:"";
    $tmp_imagen= $_FILES["imagen"]["tmp_name"];
    //Validamos si existe la imagen para poder asignarla a la carpeta correspondiente
    if ($tmp_imagen!="") {
        //Mueve el archivo a la carpeta correspondiente
        move_uploaded_file($tmp_imagen,"../../../assets/img/about/".$nombre_archivo_imagen);
    }

    $sentencia = $conexion->prepare("INSERT INTO `tbl_entrada`(`id`,`fecha`,`titulo`,`descripcion`,`imagen`) 
    VALUES (NULL, :fecha , :titulo , :descripcion , :imagen);");

    $sentencia->bindParam(":fecha", $fecha);
    $sentencia->bindParam(":titulo", $titulo);
    $sentencia->bindParam(":descripcion", $descripcion);
    $sentencia->bindParam(":imagen", $nombre_archivo_imagen);

    $sentencia->execute();

    $mensaje = "Registro agregado exitosamente";
    header("Location:index.php?mensaje=" . $mensaje);
}

include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">Producto de entrada</div>
    <div class="card-body">

        <form action="" enctype="multipart/form-data" method="post">

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha:</label>
                <input type="date" class="form-control" name="fecha" id="fecha" aria-describedby="helpId" placeholder="Fecha" />
            </div>



            <div class="mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" class="form-control" name="titulo" id="titulo" aria-describedby="helpId" placeholder="titulo" />

            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" class="form-control" name="descripcion" id="descripcion" aria-describedby="helpId" placeholder="Descripción" />

            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" class="form-control" name="imagen" id="imagen" placeholder="Imagen" aria-describedby="fileHelpId" />

            </div>


            <button type="submit" class="btn btn-success">
                Agregar
            </button>


            <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>

        </form>

    </div>
</div>

<?php

include("../../templates/footer.php");
?>