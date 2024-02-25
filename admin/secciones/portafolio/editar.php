<?php
include("../../bd.php");
include("../../css/css_admin.php");
if (isset($_GET['txtID'])) {

    $txtID = isset($_GET['txtID']) ? $_GET['txtID'] : ''; // Recupera el dato SELECCIONADO

    $sentencia = $conexion->prepare("SELECT * FROM tbl_portafolio where  id=:id");

    $sentencia->bindParam(":id", $txtID);

    $sentencia->execute();

    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    $titulo = $registro['titulo'];
    $subtitulo = $registro['subtitulo'];
    $imagen = $registro['imagen'];
    $descripcion = $registro['descripcion'];
    $cliente = $registro['cliente'];
    $categoria = $registro['categoria'];
    $url = $registro['url'];
    //print_r($registro);
}

if ($_POST) {
    //print_r($_POST);
    //echo $_POST;
    //Recepcionamos los datos por cada valor que contiene
    $txtID = (isset($_POST['txtID']) ? $_POST['txtID'] : "");
    $titulo = (isset($_POST['titulo']) ? $_POST['titulo'] : "");
    $subtitulo = (isset($_POST['subtitulo']) ? $_POST['subtitulo'] : "");
    $descripcion = (isset($_POST['descripcion']) ? $_POST['descripcion'] : "");
    $cliente = (isset($_POST['cliente']) ? $_POST['cliente'] : "");
    $categoria = (isset($_POST['categoria']) ? $_POST['categoria'] : "");
    $url = (isset($_POST['url']) ? $_POST['url'] : "");

    $sentencia = $conexion->prepare("UPDATE tbl_portafolio
    SET titulo=:titulo,subtitulo=:subtitulo,descripcion=:descripcion,cliente=:cliente,categoria=:categoria,url=:url
    WHERE id=:id");

    $sentencia->bindParam(":titulo", $titulo);
    $sentencia->bindParam(":subtitulo", $subtitulo);
    $sentencia->bindParam(":descripcion", $descripcion);

    $sentencia->bindParam(":cliente", $cliente);
    $sentencia->bindParam(":categoria", $categoria);
    $sentencia->bindParam(":url", $url);

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
        move_uploaded_file($tmp_imagen, "../../../assets/img/portfolio/" . $nombre_archivo_imagen);

        //Borrado del archivo anterior en la carpeta correspondiente
        $sentencia = $conexion->prepare("SELECT imagen FROM tbl_portafolio WHERE id=:id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();

        $registros_imagen = $sentencia->fetch(PDO::FETCH_LAZY);

        if (isset($registros_imagen["imagen"])) {
            if (file_exists("../../../assets/img/portfolio/" . $registros_imagen["imagen"])) {
                //echo "Imagen encontrada";
                unlink("../../../assets/img/portfolio/" . $registros_imagen["imagen"]);
            }
        }

        //Actualizamos la imagen
        $sentencia = $conexion->prepare("UPDATE tbl_portafolio
        SET imagen=:imagen WHERE id=:id");
        $sentencia->bindParam(":imagen", $nombre_archivo_imagen);
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
    }


    $mensaje = "Registro modificado exitosamente";
    header("Location:index.php?mensaje=" . $mensaje);
}


include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">Actualizar lista del portafolio</div>
    <div class="card-body">

        <form action="" enctype="multipart/form-data" method="post">

            <div class="mb-3">
                <label for="txtID" class="form-label">Id:</label>
                <input readonly value="<?php echo $txtID; ?>" type="text" class="form-control" name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID" />
            </div>

            <div class="mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" value="<?php echo $titulo; ?>" class="form-control" name="titulo" id="titulo" aria-describedby="helpId" placeholder="Título" />
            </div>



            <div class="mb-3">
                <label for="subtitulo" class="form-label">Subtitulo:</label>
                <input type="text" value="<?php echo $subtitulo; ?>" class="form-control" name="subtitulo" id="subtitulo" aria-describedby="helpId" placeholder="Subtitulo" />

            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <img style="border-radius: 50%; vertical-align: middle; margin-right: 10px; width: 50px;" src="../../../assets/img/portfolio/<?php echo $imagen; ?>" />
                <input type="file" class="form-control" name="imagen" id="imagen" placeholder="Imagen" aria-describedby="fileHelpId" />

            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" value="<?php echo $descripcion; ?>" class="form-control" name="descripcion" id="descripcion" aria-describedby="helpId" placeholder="Descripción" />

            </div>

            <div class="mb-3">
                <label for="cliente" class="form-label">Cliente:</label>
                <input type="text" value="<?php echo $cliente; ?>" class="form-control" name="cliente" id="cliente" aria-describedby="helpId" placeholder="Cliente" />

            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría:</label>
                <input type="text" value="<?php echo $categoria; ?>" class="form-control" name="categoria" id="categoria" aria-describedby="helpId" placeholder="Categoria" />

            </div>

            <div class="mb-3">
                <label for="url" class="form-label">URL:</label>
                <input type="text" value="<?php echo $url; ?>" class="form-control" name="url" id="url" aria-describedby="helpId" placeholder="URL del proyecto" />

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