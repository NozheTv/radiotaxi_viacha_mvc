<?php require_once APP_ROOT . '/views/partials/header.php'; ?>
<?php require_once APP_ROOT . '/views/partials/sidebar.php'; ?>

<main class="dashboard-main">
    <h2>Modificar Estado Pedido #<?= htmlspecialchars($pedidos['id']) ?></h2>

    <form action="<?= BASE_URL ?>pedidos/cambiarEstado/<?= $pedidos['id'] ?>" method="POST">
        <label for="estado">Estado:</label>
        <select name="estado" id="estado" required>
            <option value="1" <?= ($pedidos['id_estado_pedido'] == 1) ? 'selected' : '' ?>>Pendiente</option>
            <option value="2" <?= ($pedidos['id_estado_pedido'] == 2) ? 'selected' : '' ?>>Asignado</option>
            <option value="3" <?= ($pedidos['id_estado_pedido'] == 3) ? 'selected' : '' ?>>En camino</option>
            <option value="4" <?= ($pedidos['id_estado_pedido'] == 4) ? 'selected' : '' ?>>Finalizado</option>
            <option value="5" <?= ($pedidos['id_estado_pedido'] == 5) ? 'selected' : '' ?>>Cancelado</option>
        </select>
        <button type="submit" class="btn btn-primary">Actualizar Estado</button>
    </form>

    <p><a href="<?= BASE_URL ?>pedido">Volver a la lista de pedidos</a></p>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
