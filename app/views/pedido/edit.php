/* ==== ESTILO BASE ==== */
body {
    background-color: #f4f7fa;
    color: #333;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

/* ==== TÍTULO PRINCIPAL ==== */
.dashboard-main h2 {
    margin-bottom: 1.5rem;
    color: #40c766;
    font-size: 1.8rem;
    font-weight: 700;
}

/* ==== TABLAS ==== */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

thead {
    background: linear-gradient(to right, #40c776, #66ffa3);
    color: #fff;
}

thead th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
}

tbody td {
    padding: 0.8rem 1rem;
    border-bottom: 1px solid #eee;
}

tbody tr:hover {
    background-color: #f0f4ff;
}

/* ==== BOTONES ==== */
.btn, .btn-primary {
    display: inline-block;
    padding: 0.6rem 1.25rem;
    margin-bottom: 1.25rem;
    background: linear-gradient(to right, #40c781, #66ff8a);
    color: #fff;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s ease, transform 0.2s ease;
    border: none;
}

.btn:hover, .btn-primary:hover {
    background: linear-gradient(to right, #2f3d4a, #559be2);
    transform: scale(1.03);
}

/* ==== FORMULARIOS ==== */
form {
    background: #ffffff;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin-bottom: 2rem;
}

label {
    display: block;
    margin-bottom: 0.4rem;
    color: #333;
    font-weight: 600;
}

input[type="text"],
input[type="number"],
textarea,
select {
    width: 100%;
    padding: 0.65rem;
    margin-bottom: 1.2rem;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.2s ease;
}

input:focus,
textarea:focus,
select:focus {
    border-color: #40c766;
    box-shadow: 0 0 5px rgba(64, 199, 111, 0.3);
    outline: none;
}

/* ==== BOTÓN DE ENVÍO ==== */
button[type="submit"] {
    background: linear-gradient(to right, #40c776, #66ffab);
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
}

button[type="submit"]:hover {
    background: linear-gradient(to right, #2f3d4a, #55e26a);
    transform: translateY(-2px);
}

button#btnLimpiar {
    background: linear-gradient(to right, #40c776, #66ffab);
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
    margin-left: 1rem;
}

button#btnLimpiar:hover {
    background: linear-gradient(to right, #2f3d4a, #55e26a);
    transform: translateY(-2px);
}

/* ==== MAPBOX MAP ==== */
#map {
    margin-bottom: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    height: 400px;
}

/* ==== POLÍGONO GEOJSON (oculto) ==== */
#poligono_geojson {
    position: absolute;
    left: -9999px;
    width: 1px;
    height: 1px;
    overflow: hidden;
}
