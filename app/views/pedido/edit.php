<?php require_once APP_ROOT . '/views/partials/header.php'; ?>
<?php require_once APP_ROOT . '/views/partials/sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/pedidos.css" />

<main class="dashboard-main">
    <h2>Modificar Estado Pedido #<?= htmlspecialchars($pedido['id']) ?></h2>
    <a class="btn btn-primary" href="<?= BASE_URL ?>pedido">Volver a la lista de pedidos</a>

    <form action="<?= BASE_URL ?>pedido/actualizarEstado/<?= $pedido['id'] ?>" method="POST">
        <label for="estado">Estado:</label>
        <select name="estado" id="estado" required>
            <option value="1" <?= ($pedido['id_estado_pedido'] == 1) ? 'selected' : '' ?>>Pendiente</option>
            <option value="2" <?= ($pedido['id_estado_pedido'] == 2) ? 'selected' : '' ?>>Asignado</option>
            <option value="3" <?= ($pedido['id_estado_pedido'] == 3) ? 'selected' : '' ?>>En camino</option>
            <option value="4" <?= ($pedido['id_estado_pedido'] == 4) ? 'selected' : '' ?>>Finalizado</option>
            <option value="5" <?= ($pedido['id_estado_pedido'] == 5) ? 'selected' : '' ?>>Cancelado</option>
        </select>
        <button type="submit" class="btn btn-primary">Actualizar Estado</button>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
