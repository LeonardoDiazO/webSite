<?php
include("../../bd.php");
include("../../css/css_admin.php");

if ($_POST) {

    //print_r($_POST);
    //Recepcionamos los datos por cada valor que contiene
    $imagen = (isset($_FILES['imagen']["name"])) ? $_FILES['imagen']["name"] : ""; //Recepcionamos la imagen
    $nombre_completo = (isset($_POST['nombre_completo']) ? $_POST['nombre_completo'] : "");
    $puesto = (isset($_POST['puesto']) ? $_POST['puesto'] : "");
    $twitter = (isset($_POST['twitter']) ? $_POST['twitter'] : "");
    $facebook = (isset($_POST['facebook']) ? $_POST['facebook'] : "");
    $linkedin = (isset($_POST['linkedin']) ? $_POST['linkedin'] : "");
    

    //Almacenamos la imagen en la carpeta correspondiente 
    $fecha_imagen=new DateTime();
    $nombre_archivo_imagen=($imagen!="")? $fecha_imagen->getTimestamp()."_".$imagen:"";
    $tmp_imagen= $_FILES["imagen"]["tmp_name"];
    //Validamos si existe la imagen para poder asignarla a la carpeta correspondiente
    if ($tmp_imagen!="") {
        //Mueve el archivo a la carpeta correspondiente
        move_uploaded_file($tmp_imagen,"../../../assets/img/team/".$nombre_archivo_imagen);
    }

    $sentencia = $conexion->prepare("INSERT INTO `tbl_equipo`(`id`,`imagen`,`nombre_completo`,`puesto`,`twitter`,`facebook`,`linkedin`) 
    VALUES (NULL, :imagen ,:nombre_completo , :puesto , :twitter , :facebook , :linkedin);");

    $sentencia->bindParam(":imagen", $nombre_archivo_imagen);
    $sentencia->bindParam(":nombre_completo", $nombre_completo);
    $sentencia->bindParam(":puesto", $puesto);
    $sentencia->bindParam(":twitter", $twitter);
    $sentencia->bindParam(":facebook", $facebook);
    $sentencia->bindParam(":linkedin", $linkedin);

    $sentencia->execute();

    $mensaje = "Registro agregado exitosamente";
    header("Location:index.php?mensaje=" . $mensaje);
}

include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">Equipo</div>
    <div class="card-body">

        <form action="" enctype="multipart/form-data" method="post">

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" class="form-control" name="imagen" id="imagen" placeholder="Imagen" aria-describedby="fileHelpId" />

            </div>

            <div class="mb-3">
                <label for="nombre_completo" class="form-label">Nombre completo:</label>
                <input type="text" class="form-control" name="nombre_completo" id="nombre_completo" aria-describedby="helpId" placeholder="Nombre completo" />
            </div>

            <div class="mb-3">
                <label for="puesto" class="form-label">Puesto:</label>
                <input type="text" class="form-control" name="puesto" id="puesto" aria-describedby="helpId" placeholder="Puesto" />

            </div>

            <div class="mb-3">
                <label for="twitter" class="form-label">Twitter:</label>
                <input type="text" class="form-control" name="twitter" id="twitter" aria-describedby="helpId" placeholder="Twitter" />

            </div>

            <div class="mb-3">
                <label for="facebook" class="form-label">Facebook:</label>
                <input type="text" class="form-control" name="facebook" id="facebook" aria-describedby="helpId" placeholder="Facebook" />

            </div>

            <div class="mb-3">
                <label for="linkedin" class="form-label">Linkedin:</label>
                <input type="text" class="form-control" name="linkedin" id="linkedin" aria-describedby="helpId" placeholder="Linkedin" />

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