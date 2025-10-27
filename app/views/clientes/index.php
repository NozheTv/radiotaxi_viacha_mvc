<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/clientes.css?v=<?php echo time(); ?>" />

<main class="dashboard-main">
    <h2>Listado de Clientes</h2>

    <!-- Mensajes de éxito o error -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <section>
        <a href="<?php echo BASE_URL; ?>clientes/create" class="btn btn-primary">Añadir Cliente</a>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($clientes)): ?>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cliente['nombre'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($cliente['email'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($cliente['telefono'] ?? '-'); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL . 'clientes/edit/' . $cliente['id']; ?>">Editar</a>
                                |
                                <a href="<?php echo BASE_URL . 'clientes/delete/' . $cliente['id']; ?>"
                                   onclick="return confirm('¿Está seguro de eliminar este cliente?');">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay clientes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
