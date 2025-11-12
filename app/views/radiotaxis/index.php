<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/radiotaxis.css" />

<main class="dashboard-main">
    <h2>Radio Taxis</h2>
    <a href="<?php echo BASE_URL; ?>radiotaxis/create" class="btn btn-primary">Nuevo Radio Taxi</a>

    <table>
        <thead>
            <tr>
                <th>Placa</th>
                <th>Modelo</th>
                <th>Estado</th>
                <th>Conductor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($radiotaxis as $taxi): ?>
            <tr>
                <td><?= htmlspecialchars($taxi['placa'] ?? '') ?></td>
                <td><?= htmlspecialchars($taxi['modelo'] ?? '') ?></td>
                <td><?= htmlspecialchars($taxi['estado_descripcion'] ?? '') ?></td>
                <td><?= htmlspecialchars($taxi['conductor_nombre'] ?? 'Sin asignar') ?></td>
                <td>
                    <a href="<?= BASE_URL . 'radiotaxis/edit/' . $taxi['id'] ?>">Editar</a> |
                    <a href="<?= BASE_URL . 'radiotaxis/delete/' . $taxi['id'] ?>"
                       onclick="return confirm('¿Está seguro que desea eliminar este radio taxi?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
