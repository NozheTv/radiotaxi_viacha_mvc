<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/clientes.css" />

<main class="dashboard-main">
    <h2>Listado de Clientes</h2>
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
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                    <td>
                        <a href="<?php echo BASE_URL . 'clientes/edit/' . $cliente['id']; ?>">Editar</a>
                        |
                        <a href="<?php echo BASE_URL . 'clientes/delete/' . $cliente['id']; ?>"
                           onclick="return confirm('¿Está seguro de eliminar este cliente?');">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
