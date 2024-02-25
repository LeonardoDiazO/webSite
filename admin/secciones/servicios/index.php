<?php
include("../../bd.php");
include("../../css/css_admin.php");
if (isset($_GET['txtID'])) {

    $txtID=isset($_GET['txtID'])?$_GET['txtID']:'';

    $sentencia= $conexion->prepare("DELETE FROM tbl_servicios where  id=:id");

    $sentencia->bindParam(":id",$txtID);

    $sentencia->execute();
}
//Selecciona todos los campos de la tabla de tbl_servicios
$sentencia = $conexion->prepare("SELECT * FROM `tbl_servicios`");
$sentencia->execute();
$lista_servicios = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//print_r("lista_servicios");


include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar registros</a>

    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Icono</th>
                        <th scope="col">Titulo</th>
                        <th scope="col">Descripcion</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_servicios as $registros) { ?>
                        <tr class="">
                            <td><?php echo $registros['id']; ?></td>
                            <td><?php echo $registros['icono']; ?></td>
                            <td><?php echo $registros['titulo']; ?></td>
                            <td><?php echo $registros['descripcion']; ?></td>
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