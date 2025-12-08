<footer class="dashboard-footer">
    <p>&copy; 2025 RadioTaxi Viacha - Todos los derechos reservados</p>
</footer>
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.getEntries().filter(e => e.entryType === "navigation")[0].type === 'back_forward')) {
                window.location.reload();
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Datos estáticos de ejemplo, reemplaza con datos reales PHP si quieres dinámico
    const usuariosData = {
        labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
        datasets: [{
            label: 'Usuarios registrados',
            data: [12, 19, 7, 15, 20, 10, 8],
            backgroundColor: 'rgba(40, 167, 69, 0.6)',
            borderColor: '#28a745',
            borderWidth: 1,
            fill: true,
            tension: 0.3
        }]
    };

    const viajesData = {
        labels: ['Completados', 'Pendientes', 'Cancelados', 'En progreso'],
        datasets: [{
            label: 'Viajes',
            data: [89, 5, 3, 7],
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#17a2b8'
            ]
        }]
    };

    const geocercasData = {
        labels: ['Zona urbana', 'Zona rural', 'Zonas especiales'],
        datasets: [{
            label: 'Geocercas',
            data: [7, 3, 2],
            backgroundColor: [
                '#007bff',
                '#6c757d',
                '#ffc107'
            ]
        }]
    };

    const configUsuarios = {
        type: 'line',
        data: usuariosData,
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    };

    const configViajes = {
        type: 'doughnut',
        data: viajesData,
        options: { responsive: true }
    };

    const configGeocercas = {
        type: 'bar',
        data: geocercasData,
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    };

    // Inicializar los gráficos
    new Chart(document.getElementById('usuariosChart'), configUsuarios);
    new Chart(document.getElementById('viajesChart'), configViajes);
    new Chart(document.getElementById('geocercasChart'), configGeocercas);
</script>

</body>
</html>
