-- ============================================================================
-- SCRIPT GestionHallazgos
-- ============================================================================

-- Eliminar la base de datos si existe y crearla nuevamente
DROP DATABASE IF EXISTS GestionHallazgos;
CREATE DATABASE GestionHallazgos;
USE GestionHallazgos;

-- Crear la tabla de procesos
CREATE TABLE Proceso (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identificador único para cada proceso
    nombre VARCHAR(100) NOT NULL,       -- Nombre del proceso (ej. Control de Calidad)
    descripcion TEXT,                   -- Descripción detallada del proceso
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Fecha en la que se crea el registro
);

-- Crear la tabla de estados
CREATE TABLE Estado (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identificador único para cada estado
    nombre VARCHAR(50) NOT NULL         -- Nombre del estado (ej. Abierto, Cerrado)
);

-- Crear la tabla de usuarios
CREATE TABLE Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identificador único para cada usuario
    nombre VARCHAR(100) NOT NULL,       -- Nombre completo del usuario
    email VARCHAR(100) NOT NULL UNIQUE, -- Correo electrónico único para cada usuario
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Fecha en la que se registra el usuario
);

-- Crear la tabla de hallazgos
CREATE TABLE Hallazgo (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Identificador único para cada hallazgo
    titulo VARCHAR(150) NOT NULL,           -- Título breve del hallazgo
    descripcion TEXT NOT NULL,              -- Descripción detallada del hallazgo
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha en la que se crea el hallazgo
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Fecha de última actualización
    id_estado INT,                          -- Estado actual del hallazgo (referencia a Estado)
    id_usuario INT,                         -- Usuario responsable del hallazgo (referencia a Usuario)
    FOREIGN KEY (id_estado) REFERENCES Estado(id),  -- Llave foránea a la tabla Estado
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id) -- Llave foránea a la tabla Usuario
);

-- Crear la tabla de incidentes
CREATE TABLE Incidente (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Identificador único para cada incidente
    descripcion TEXT NOT NULL,              -- Descripción detallada del incidente
    fecha_ocurrencia DATE NOT NULL,         -- Fecha en la que ocurrió el incidente
    id_estado INT,                          -- Estado actual del incidente (referencia a Estado)
    id_usuario INT,                         -- Usuario que reportó o es responsable del incidente (referencia a Usuario)
    FOREIGN KEY (id_estado) REFERENCES Estado(id),  -- Llave foránea a la tabla Estado
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id) -- Llave foránea a la tabla Usuario
);

-- Crear la tabla de planes de acción
CREATE TABLE PlanAccion (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Identificador único para cada plan de acción
    descripcion TEXT NOT NULL,              -- Descripción detallada del plan de acción
    id_usuario INT,                         -- Usuario responsable del plan de acción (referencia a Usuario)
    fecha_inicio DATE,                      -- Fecha de inicio del plan de acción
    fecha_fin DATE,                         -- Fecha de finalización del plan de acción
    id_estado INT,                          -- Estado actual del plan de acción (referencia a Estado)
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id), -- Llave foránea a la tabla Usuario
    FOREIGN KEY (id_estado) REFERENCES Estado(id)    -- Llave foránea a la tabla Estado
);

-- Crear la tabla de unión Registro_PlanAccion para la relación indirecta entre registros y PlanAccion
CREATE TABLE Registro_PlanAccion (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Identificador único para cada registro en la tabla
    id_registro INT NOT NULL,                 -- Identificador del registro
    origen_registro ENUM('INCIDENTE', 'HALLAZGO') NOT NULL,  -- Origen del registro
    id_plan_accion INT,                       -- Identificador del plan de acción asociado
    FOREIGN KEY (id_plan_accion) REFERENCES PlanAccion(id)   -- Llave foránea a la tabla PlanAccion
);

-- Crear la tabla intermedia Hallazgo_Proceso para la relación de muchos a muchos entre hallazgos y procesos
CREATE TABLE Hallazgo_Proceso (
    id_hallazgo INT,                          -- Identificador del hallazgo (referencia a Hallazgo)
    id_proceso INT,                           -- Identificador del proceso (referencia a Proceso)
    PRIMARY KEY (id_hallazgo, id_proceso),    -- Llave primaria compuesta para garantizar la relación única
    FOREIGN KEY (id_hallazgo) REFERENCES Hallazgo(id), -- Llave foránea a la tabla Hallazgo
    FOREIGN KEY (id_proceso) REFERENCES Proceso(id)   -- Llave foránea a la tabla Proceso
);

-- Insertar datos iniciales en la tabla Estado
INSERT INTO Estado (nombre) VALUES
    ('Abierto'),              -- Estado indicando que el registro está abierto
    ('En Proceso'),           -- Estado indicando que el registro está siendo trabajado
    ('Resuelto'),             -- Estado indicando que el registro se ha resuelto
    ('Cerrado');              -- Estado indicando que el registro ha sido cerrado

-- Insertar datos de ejemplo en la tabla Proceso
INSERT INTO Proceso (nombre, descripcion) VALUES
    ('Control de Calidad', 'Proceso de control de calidad en la producción de alimentos.'),
    ('Almacenamiento', 'Proceso de almacenamiento de productos en condiciones adecuadas.'),
    ('Distribución', 'Proceso de distribución de productos a diferentes destinos.'),
    ('Higiene y Seguridad', 'Control de higiene en las instalaciones y seguridad de empleados.'),
    ('Producción', 'Supervisión de la producción en la planta.'),
    ('Empaque', 'Proceso de empaque de productos alimenticios.'),
    ('Auditoría Interna', 'Auditoría interna para cumplimiento de normativas.'),
    ('Evaluación de Proveedores', 'Proceso de evaluación de proveedores de insumos.'),
    ('Regulación y Cumplimiento', 'Control de cumplimiento con regulaciones alimentarias.'),
    ('Capacitación', 'Capacitación de empleados en normas de seguridad alimentaria.');

-- Insertar datos de ejemplo en la tabla Usuario
INSERT INTO Usuario (nombre, email) VALUES
    ('Ana López', 'ana.lopez@empresa.com'),
    ('Carlos Martínez', 'carlos.martinez@empresa.com'),
    ('Laura Jiménez', 'laura.jimenez@empresa.com'),
    ('Fernando García', 'fernando.garcia@empresa.com'),
    ('Elena Rojas', 'elena.rojas@empresa.com'),
    ('David Torres', 'david.torres@empresa.com'),
    ('Luis Pérez', 'luis.perez@empresa.com'),
    ('Sofía Morales', 'sofia.morales@empresa.com'),
    ('Jorge Álvarez', 'jorge.alvarez@empresa.com'),
    ('Marta Sánchez', 'marta.sanchez@empresa.com');

-- Insertar datos de ejemplo en la tabla Hallazgo
INSERT INTO Hallazgo (titulo, descripcion, id_estado, id_usuario) VALUES
    ('Control de Temperatura', 'Problema en el control de temperatura en almacenamiento.', 1, 1),
    ('Higiene en Planta', 'Falta de desinfección en área de producción.', 2, 2),
    ('Etiquetado Incorrecto', 'Etiquetas en idioma incorrecto en empaque.', 1, 3),
    ('Retraso en Distribución', 'Retraso en la entrega de productos a sucursales.', 3, 4),
    ('Error en Inspección de Calidad', 'Inconsistencias en el control de calidad.', 2, 5),
    ('Almacenamiento Inadecuado', 'Producto almacenado en lugar sin ventilación.', 1, 6),
    ('Falta de Capacitación', 'Faltan capacitaciones sobre nuevas normativas.', 2, 7),
    ('Incumplimiento de Normativa', 'No se cumple normativa de empaque.', 3, 8),
    ('Problemas de Trazabilidad', 'Dificultad para rastrear origen de materia prima.', 1, 9),
    ('Evaluación de Proveedores Deficiente', 'Proveedor no cumple con estándares.', 2, 10);
    
-- Insertar datos de ejemplo en la tabla Hallazgo_Proceso para asociar hallazgos a múltiples procesos
INSERT INTO Hallazgo_Proceso (id_hallazgo, id_proceso) VALUES
    (1, 2), (1, 3), 
    (2, 4), (2, 5), 
    (3, 6), (3, 10), 
    (4, 3), (4, 9), 
    (5, 1), (5, 4), 
    (6, 2), (6, 8), 
    (7, 10), (7, 5), 
    (8, 6), (8, 9), 
    (9, 7), (9, 8), 
    (10, 5), (10, 7);

-- Insertar datos de ejemplo en la tabla Incidente
INSERT INTO Incidente (descripcion, fecha_ocurrencia, id_estado, id_usuario) VALUES
    ('Derrame de líquidos en zona de empaque', '2024-10-01', 1, 3),
    ('Fallo en equipo de refrigeración', '2024-09-20', 2, 5),
    ('Ingesta de producto contaminado', '2024-09-15', 1, 1),
    ('Personal sin equipo de protección', '2024-08-25', 3, 4),
    ('Desabastecimiento de insumos', '2024-08-30', 2, 6),
    ('Error en etiquetado de lote', '2024-07-18', 1, 7),
    ('Contaminación en línea de producción', '2024-06-22', 1, 2),
    ('Rotura de embalaje en almacenamiento', '2024-05-05', 4, 8),
    ('Incumplimiento de medidas de seguridad', '2024-04-17', 3, 10),
    ('Atraso en entrega de proveedor crítico', '2024-03-15', 2, 9);

-- Insertar datos de ejemplo en la tabla PlanAccion, asignando id_usuario como responsable
INSERT INTO PlanAccion (descripcion, id_usuario, fecha_inicio, fecha_fin, id_estado) VALUES
    ('Reparar equipo de refrigeración', 2, '2024-10-05', '2024-10-20', 2),
    ('Implementar sistema de etiquetas bilingües', 1, '2024-09-25', '2024-10-10', 1),
    ('Capacitar personal en manipulación de alimentos', 3, '2024-10-01', '2024-10-15', 2),
    ('Revisión del sistema de ventilación en almacenes', 4, '2024-09-15', '2024-09-30', 3),
    ('Cambio de proveedor para insumos', 5, '2024-08-20', '2024-09-10', 1),
    ('Actualizar procedimientos de limpieza', 6, '2024-07-01', '2024-07-15', 3),
    ('Revisión de trazabilidad del lote', 7, '2024-06-10', '2024-06-25', 4),
    ('Instalar señalización en zona de empaque', 8, '2024-05-10', '2024-05-25', 2),
    ('Evaluación de proveedores alternativos', 9, '2024-04-20', '2024-05-15', 1),
    ('Actualizar protocolos de emergencia', 10, '2024-03-01', '2024-03-15', 4);

-- Asociar planes de acción a incidentes en la tabla Registro_PlanAccion
INSERT INTO Registro_PlanAccion (id_registro, origen_registro, id_plan_accion) VALUES
    (1, 'INCIDENTE', 1),
    (2, 'INCIDENTE', 2),
    (3, 'INCIDENTE', 3),
    (4, 'INCIDENTE', 4),
    (5, 'INCIDENTE', 5),
    (6, 'INCIDENTE', 6),
    (7, 'INCIDENTE', 7),
    (8, 'INCIDENTE', 8),
    (9, 'INCIDENTE', 9),
    (10, 'INCIDENTE', 10);

-- ============================================================================
-- MODULO DE AUDITORIAS
-- ============================================================================

-- ----------------------------------------------------------------------------
-- Plan de auditoría
-- ----------------------------------------------------------------------------
CREATE TABLE PlanAuditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- Identificador único del plan
    codigo VARCHAR(50),                         -- Código identificador (ej. AUD-2025-001)
    nombre VARCHAR(150) NOT NULL,               -- Título de la auditoría
    alcance TEXT NOT NULL,                      -- Procesos, áreas o sistemas auditados
    justificacion TEXT,                         -- Motivo que origina la auditoría
    tipo_plan VARCHAR(50),                       -- Interna, Externa, Seguimiento, etc.
    costo_estimado DECIMAL(12,2),               -- Valor proyectado de la auditoría
    fecha_inicio DATE,                          -- Fecha de inicio estimada
    fecha_fin DATE,                             -- Fecha de fin estimada
    id_estado INT,                              -- Estado del plan (referencia a Estado)
    id_responsable INT,                         -- Usuario responsable (referencia a Usuario)
    id_usuario_creador INT,                     -- Usuario creador (referencia a Usuario)
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_estado) REFERENCES Estado(id),
    FOREIGN KEY (id_responsable) REFERENCES Usuario(id),
    FOREIGN KEY (id_usuario_creador) REFERENCES Usuario(id)
);

-- ----------------------------------------------------------------------------
-- Tareas del plan
-- ----------------------------------------------------------------------------
CREATE TABLE TareaAuditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- Identificador único de la tarea
    id_plan INT NOT NULL,                       -- Plan al que pertenece (referencia a PlanAuditoria)
    id_padre INT DEFAULT NULL,                  -- Tarea padre, para el listado jerárquico (subtareas)
    orden INT DEFAULT 0,                        -- Posición de la tarea en el listado/Gantt
    codigo VARCHAR(50),                         -- Código identificador de la tarea
    nombre VARCHAR(150) NOT NULL,               -- Título descriptivo de la tarea
    descripcion TEXT,                           -- Explicación detallada de la tarea
    id_responsable INT,                         -- Usuario asignado (referencia a Usuario)
    fecha_inicio DATE,                          -- Fecha de inicio
    fecha_fin DATE,                             -- Fecha de fin
    duracion_estimada INT,                      -- Número de días estimados
    duracion_real INT DEFAULT 0,                -- Número de días reales
    prioridad VARCHAR(20),                       -- Alta, Media, Baja
    costo_estimado DECIMAL(12,2),               -- Presupuesto aproximado
    progreso INT DEFAULT 0,                     -- Porcentaje de avance (0-100)
    estado_tarea VARCHAR(30) DEFAULT 'Activa',   -- Activa, Completada, Fallida, Indefinida, Suspendida
    FOREIGN KEY (id_plan) REFERENCES PlanAuditoria(id),
    FOREIGN KEY (id_padre) REFERENCES TareaAuditoria(id),
    FOREIGN KEY (id_responsable) REFERENCES Usuario(id)
);

-- ----------------------------------------------------------------------------
-- Pruebas de la tarea
-- ----------------------------------------------------------------------------
CREATE TABLE PruebaTarea (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- Identificador único de la prueba
    id_tarea INT NOT NULL,                      -- Tarea a la que pertenece (referencia a TareaAuditoria)
    codigo VARCHAR(50),                         -- Código identificador de la prueba
    nombre VARCHAR(150) NOT NULL,               -- Título descriptivo de la prueba
    tipo VARCHAR(50),                            -- Funcional, Técnica, Incursión, etc.
    descripcion TEXT,                           -- Propósito y forma de ejecución
    categoria VARCHAR(100),                     -- Clasificación temática
    subcategoria VARCHAR(100),                  -- Clasificación que refina el tipo
    estado_prueba VARCHAR(30) DEFAULT 'Incompleta', -- Incompleta, En desarrollo, Finalizada
    id_creador INT,                             -- Usuario que registró la prueba (referencia a Usuario)
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tarea) REFERENCES TareaAuditoria(id),
    FOREIGN KEY (id_creador) REFERENCES Usuario(id)
);

-- ----------------------------------------------------------------------------
-- Resultados de la prueba (Resultado / Observación / Seguimiento)
-- ----------------------------------------------------------------------------
CREATE TABLE ResultadoPrueba (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- Identificador único del resultado
    id_prueba INT NOT NULL,                     -- Prueba aplicada (referencia a PruebaTarea)
    descripcion TEXT,                           -- Detalle del resultado obtenido
    observaciones TEXT,                         -- Observaciones registradas
    fecha_observacion DATE,                     -- Fecha de la observación
    tipo VARCHAR(30) DEFAULT 'Resultado',        -- Resultado, Observación, Hallazgo, Seguimiento
    id_usuario INT,                             -- Usuario que registró (referencia a Usuario)
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_prueba) REFERENCES PruebaTarea(id),
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id)
);

-- ----------------------------------------------------------------------------
-- Datos de ejemplo
-- ----------------------------------------------------------------------------
INSERT INTO PlanAuditoria (codigo, nombre, alcance, justificacion, tipo_plan, costo_estimado, fecha_inicio, fecha_fin, id_estado, id_responsable, id_usuario_creador) VALUES
    ('AUD-2025-001', 'Auditoría Interna de Calidad 2025', 'Procesos de control de calidad y empaque de la planta principal.', 'Cumplimiento normativo anual.', 'Interna', 5000.00, '2025-01-15', '2025-03-30', 1, 1, 2),
    ('AUD-2025-002', 'Auditoría Externa de Proveedores', 'Evaluación de proveedores críticos de insumos.', 'Renovación de contratos.', 'Externa', 12000.00, '2025-02-01', '2025-04-15', 2, 4, 1),
    ('AUD-2025-003', 'Seguimiento de Higiene y Seguridad', 'Revisión de medidas de higiene en áreas de producción.', 'Seguimiento a hallazgos previos.', 'Seguimiento', 3000.00, '2025-03-10', '2025-05-20', 1, 5, 3);

INSERT INTO TareaAuditoria (id_plan, id_padre, orden, codigo, nombre, descripcion, id_responsable, fecha_inicio, fecha_fin, duracion_estimada, duracion_real, prioridad, costo_estimado, progreso, estado_tarea) VALUES
    (1, NULL, 1, 'T-001', 'Fase de almacenamiento', 'Auditoría del proceso de almacenamiento en frío.', 3, '2025-01-15', '2025-02-05', 21, 18, 'Alta', 1400.00, 70, 'Activa'),
    (1, 1,    2, 'T-001.1', 'Revisión de registros de temperatura', 'Verificar las bitácoras de temperatura en almacenamiento.', 3, '2025-01-15', '2025-01-22', 7, 7, 'Alta', 800.00, 100, 'Completada'),
    (1, 1,    3, 'T-001.2', 'Inspección de etiquetado', 'Comprobar el cumplimiento del etiquetado bilingüe.', 6, '2025-01-23', '2025-02-05', 10, 4, 'Media', 600.00, 40, 'Activa'),
    (2, NULL, 1, 'T-010', 'Evaluación documental de proveedor A', 'Revisar certificaciones vigentes del proveedor.', 9, '2025-02-01', '2025-02-10', 9, 0, 'Alta', 1500.00, 0, 'Indefinida');

INSERT INTO PruebaTarea (id_tarea, codigo, nombre, tipo, descripcion, categoria, subcategoria, estado_prueba, id_creador) VALUES
    (2, 'PR-001', 'Validar rango de temperatura', 'Funcional', 'Comprobar que la temperatura no supera los 4°C.', 'Almacenamiento', 'Cumplimiento', 'Finalizada', 3),
    (2, 'PR-002', 'Validar continuidad de registros', 'Técnica', 'Verificar que no existan huecos en los registros diarios.', 'Documentos', 'Pertinencia', 'En desarrollo', 3),
    (3, 'PR-010', 'Validar idioma de etiqueta', 'Funcional', 'Confirmar etiquetas en español e inglés.', 'Empaque', 'Cumplimiento Legal', 'Incompleta', 6);

INSERT INTO ResultadoPrueba (id_prueba, descripcion, observaciones, fecha_observacion, tipo, id_usuario) VALUES
    (1, 'Temperatura dentro de rango durante todo el periodo.', 'Sin novedades en la cámara fría 2.', '2025-01-20', 'Resultado', 3),
    (1, 'Pico de temperatura detectado el 18/01.', 'Se recomienda revisar el compresor.', '2025-01-18', 'Observación', 3),
    (2, 'Falta el registro del día 17/01.', 'Posible olvido del turno nocturno.', '2025-01-21', 'Hallazgo', 3);