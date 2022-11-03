<?php /** @var string $seccion */ ?>
<?php /** @var int $registro_id */ ?>
<?php /** @var string $session_id */ ?>

<a href="index.php?seccion=org_empresa&accion=identidad&registro_id=<?php echo $controlador->registro_id; ?>&session_id=<?php echo $session_id; ?>">
    <?php include "templates/$controlador->seccion/_base/buttons/number.gris.php"; ?>
</a>
