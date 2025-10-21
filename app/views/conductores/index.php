<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/conductores.css" />

<main class="dashboard-main">
    <h2>Listado de Conductores</h2>
    <section>
        <a href="<?php echo BASE_URL; ?>conductores/create" class="btn btn-primary">Añadir Conductor</a>
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
                <?php foreach ($conductores as $conductor): ?>
                <tr>
                    <td><?php echo htmlspecialchars($conductor['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($conductor['email']); ?></td>
                    <td><?php echo htmlspecialchars($conductor['telefono']); ?></td>
                    <td>
                        <a href="<?php echo BASE_URL . 'conductores/edit/' . $conductor['id']; ?>">Editar</a>
                        |
                        <a href="<?php echo BASE_URL . 'conductores/delete/' . $conductor['id']; ?>"
                           onclick="return confirm('¿Está seguro de eliminar este conductor?');">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
