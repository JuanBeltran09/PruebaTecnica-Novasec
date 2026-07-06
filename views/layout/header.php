<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Gestión</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?= ($entity === 'hallazgo') ? 'active' : '' ?>">
                <a class="nav-link" href="index.php?entity=hallazgo&action=index">Hallazgos</a>
            </li>
            <li class="nav-item <?= ($entity === 'incidente') ? 'active' : '' ?>">
                <a class="nav-link" href="index.php?entity=incidente&action=index">Incidentes</a>
            </li>
            <li class="nav-item <?= ($entity === 'auditoria') ? 'active' : '' ?>">
                <a class="nav-link" href="index.php?entity=auditoria&action=index">Auditorías</a>
            </li>
        </ul>
    </div>
</nav>