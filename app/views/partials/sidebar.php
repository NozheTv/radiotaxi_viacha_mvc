<nav class="dashboard-nav">
    <ul>
        <li><a href="<?php echo BASE_URL; ?>admin/dashboard" class="<?php echo ($currentPath === '/admin/dashboard') ? 'active' : ''; ?>">Presentacion</a></li>
        <li><a href="<?php echo BASE_URL; ?>clientes" class="<?php echo ($currentPath === '/clientes') ? 'active' : ''; ?>">Clientes</a></li>
        <li><a href="<?php echo BASE_URL; ?>conductores" class="<?php echo ($currentPath === '/conductores') ? 'active' : ''; ?>">Conductores</a></li>
        <li><a href="<?php echo BASE_URL; ?>radiotaxis" class="<?php echo ($currentPath === '/radiotaxis') ? 'active' : ''; ?>">Radio Taxis</a></li>
        <li><a href="<?php echo BASE_URL; ?>pedido" class="<?php echo ($currentPath === '/pedido') ? 'active' : ''; ?>">Pedidos</a></li>
        <li><a href="<?php echo BASE_URL; ?>rutas" class="<?php echo ($currentPath === '/rutas') ? 'active' : ''; ?>">Historial de Viajes</a></li>
        <li><a href="<?php echo BASE_URL; ?>geocercas" class="<?php echo ($currentPath === '/geocercas') ? 'active' : ''; ?>">Geocercas</a></li>
        <li><a href="<?php echo BASE_URL; ?>auth/logout">Cerrar sesión</a></li>
        <img src="<?php echo BASE_URL; ?>img/imagen31deenero.jpg" alt="">
    </ul>
</nav>
