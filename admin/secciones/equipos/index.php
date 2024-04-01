<?php
include("../../bd.php");
include("../../css/css_admin.php");
if (isset($_GET['txtID'])) {

    $txtID = isset($_GET['txtID']) ? $_GET['txtID'] : '';

    //Buscamos la imagen para poderla borrar de la misma carpeta contenida
    $sentencia=$conexion->prepare("SELECT imagen FROM tbl_equipo WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();

    $registros_imagen = $sentencia->fetch(PDO::FETCH_LAZY);

    if (isset($registros_imagen["imagen"])) {
        if (file_exists("../../../assets/img/team/".$registros_imagen["imagen"])) {
            //echo "Imagen encontrada";
            unlink("../../../assets/img/team/".$registros_imagen["imagen"]);
        }
    }

    $sentencia=$conexion->prepare("DELETE FROM tbl_equipo WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    
}

//Selecciona todos los campos de la tabla de tbl_equipo
$sentencia = $conexion->prepare("SELECT * FROM `tbl_equipo`");
$sentencia->execute();
$lista_equipo = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar registros</a>
    </div>
    <div class="card-body">

        <div class="table-responsive-sm">
            <table class="table ">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Nombre Completo</th>
                        <th scope="col">Puesto - Cargo</th>
                        <th scope="col">Twitter</th>
                        <th scope="col">Facebook</th>
                        <th scope="col">Linkedin</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($lista_equipo as $registros) { ?>
                        <tr class="">
                            <td><?php echo $registros['id']; ?></td>
                            <td><img style="border-radius: 50%; vertical-align: middle; margin-right: 10px; width: 50px;" src="../../../assets/img/about/<?echo $registros['imagen'];?>"/></td>
                            <td><?php echo $registros['nombre_completo']; ?></td>
                            <td><?php echo $registros['puesto']; ?></td>
                            <td><?php echo $registros['twitter']; ?></td>
                            <td><?php echo $registros['facebook']; ?></td>
                            <td><?php echo $registros['linkedin']; ?></td>
                            <td>
                                <a name="" id="" class="edit" href="editar.php?txtID=<?php echo $registros['id'];?>" role="button">
                                <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                
                                <a name="" id="" class="delete" href="index.php?txtID=<?php echo $registros['id'];?>" role="button">
                                <i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>


    </div>

</div>

<?php

include("../../templates/footer.php");
?>