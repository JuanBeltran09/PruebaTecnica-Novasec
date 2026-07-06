<!-- views/hallazgo/list.php -->
<?php include 'views/layout/header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Hallazgos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
    <!-- H-5995: Estilos para la alerta visual de confirmación -->
    <style>
        .alerta-estado {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
            display: none;
            animation: fadeInOut 3s ease-in-out;
        }
        @keyframes fadeInOut {
            0%   { opacity: 0; transform: translateY(-10px); }
            10%  { opacity: 1; transform: translateY(0); }
            80%  { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); }
        }
    </style>
</head>
<body>

<!-- H-5995: Alerta visual flotante para confirmación de cambio de estado -->
<div id="alerta-estado" class="alerta-estado alert" role="alert"></div>

<div class="container mt-4">
    <h1>Lista de Hallazgos</h1>
    <a href="index.php?entity=hallazgo&action=create" class="btn btn-primary mb-3">Crear Hallazgo</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Usuario</th>
                <th>Procesos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hallazgos as $hallazgo): ?>
            <tr>
                <td><?= $hallazgo['id'] ?></td>
                <td><?= $hallazgo['titulo'] ?></td>
                <td><?= $hallazgo['descripcion'] ?></td>
                <!-- H-5995: Se reemplaza el texto estático por un selector de estado -->
                <td>
                    <select class="form-control form-control-sm select-estado"
                            data-hallazgo-id="<?= $hallazgo['id'] ?>">
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= $estado['id'] ?>"
                                <?= ($estado['id'] == $hallazgo['id_estado']) ? 'selected' : '' ?>>
                                <?= $estado['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><?= $hallazgo['usuario_nombre'] ?></td>
                <td>
                    <ul>
                        <?php foreach ($hallazgo['procesos'] as $proceso): ?>
                            <li><?= $proceso['nombre'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </td>
                <td>
                    <a href="index.php?entity=hallazgo&action=show&id=<?= $hallazgo['id'] ?>" class="btn btn-info btn-sm">Ver</a>
                    <a href="index.php?entity=hallazgo&action=edit&id=<?= $hallazgo['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="index.php?entity=hallazgo&action=delete&id=<?= $hallazgo['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- H-5995: JavaScript con Fetch API para actualización asíncrona del estado -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    /**
     * Muestra una alerta flotante temporal en la esquina superior derecha.
     * @param {string} mensaje  - Texto a mostrar.
     * @param {string} tipo     - Clase Bootstrap: 'success', 'danger', 'warning'.
     */
    function mostrarAlerta(mensaje, tipo) {
        var alerta = document.getElementById('alerta-estado');
        alerta.className = 'alerta-estado alert alert-' + tipo;
        alerta.textContent = mensaje;
        alerta.style.display = 'block';
        // Reiniciar la animación para que funcione en cambios consecutivos
        alerta.style.animation = 'none';
        alerta.offsetHeight; // Forzar reflow
        alerta.style.animation = 'fadeInOut 3s ease-in-out';
        // Ocultar tras 3 segundos
        setTimeout(function () {
            alerta.style.display = 'none';
        }, 3000);
    }

    // Capturar el evento 'change' en cada selector de estado
    var selectores = document.querySelectorAll('.select-estado');

    selectores.forEach(function (select) {
        select.addEventListener('change', function () {
            var hallazgoId = this.getAttribute('data-hallazgo-id');
            var nuevoEstadoId = this.value;

            // Enviar petición asíncrona con Fetch API
            fetch('index.php?entity=hallazgo&action=update_estado&id=' + hallazgoId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id_estado: nuevoEstadoId })
            })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    mostrarAlerta('✅ ' + data.message + ' Nuevo estado: ' + data.estado_nombre, 'success');
                } else {
                    mostrarAlerta('❌ ' + data.message, 'danger');
                }
            })
            .catch(function (error) {
                mostrarAlerta('❌ Error de conexión: ' + error.message, 'danger');
            });
        });
    });
});
</script>

</body>
</html>