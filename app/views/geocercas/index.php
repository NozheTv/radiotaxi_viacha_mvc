<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/geocercas.css" />

<main class="dashboard-main">
    <h2>Geocercas Registradas</h2>
    <a href="<?php echo BASE_URL; ?>geocercas/create" class="btn btn-primary">Nueva Geocerca</a>

    <table>
        <thead>
            <tr>
                <th>Nombre Zona</th>
                <th>Tarifa Fija</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($geocercas as $g): ?>
            <tr>
                <td><?= htmlspecialchars($g['nombre_zona']?? '') ?></td>
                <td><?= htmlspecialchars(number_format($g['tarifa_fija'], 2)) ?> Bs.</td>
                <td>
                    <a href="<?= BASE_URL . 'geocercas/edit/' . $g['id'] ?>">Editar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
