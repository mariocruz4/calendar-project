<?php
include "calendar.php";
?>

<!DOCTYPE html>
<html lang="es-419" dir="ltf">
    <head>

        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Un proyecto de calendario interactivo" />

        <title>Mi Calendario</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="css/style.css" />

    </head>

    <body>
        <!-- Header -->

            <header>
                <h1>🗓 Calendario<br> Mi primer proyecto Full-Stack</h1>
            </header>

        <!-- Reloj -->

            <div class="clock-container">
                <div id="clock"></div>
            </div>

        <!-- Sección del Calendario -->

            <div class="calendar">
                <div class="nav-btn-container">
                    <button class="nav-btn">⏪</button>
                    <h2 id="monthYear" style="margin: 0"></h2>
                    <button class="nav-btn">⏩</button>
                </div>

                <div class="calendar-grid" id="calendar"></div>
            </div>

        <!-- Modal para Agregar/Editar/Borrar citas -->
            <div class="modal" id="eventModal">
                <div class="modal-content">

                    <div id="eventSelectorWrapper">
                        <label for="eventSelector">
                            <strong>📅 Selecciona una cita:</strong>
                        </label>
                        <select id="eventSelector">
                            <option disable selected>Escoje una cita...</option>
                        </select>
                    </div>

            <!-- Formulario Principal -->

                    <form method="POST" id="eventForm">
                        <input type="hidden" name="action" id="formAction" value="add" />
                        <input type="hidden" name="event_id" id="eventId" />

                        <label for="courseName">Título de la cita:</label>
                        <input type="text" name="course_name" id="courseName" required />

                        <label for="instructor">Invitado:</label>
                        <input type="text" name="instructor_name" id="instructorName" required />

                        <label for="startDate">Fecha de inicio:</label>
                        <input type="date" name="start_date" id="startDate" required />

                        <label for="endDate">Fecha de fin:</label>
                        <input type="date" name="end_date" id="endDate" required />

                        <label for="startTime">Hora de inicio:</label>
                        <input type="time" name="start_time" id="startTime" required />

                        <label for="endTime">Hora de fin:</label>
                        <input type="time" name="end_time" id="endTime" required />

                        <button type="submit">💾 Guardar</button>
                    </form>

            <!-- Formulario para Borrar -->

                    <form method="POST" onsubmit="return confirm('¿Estás seguro que quieres borrar esta cita?')">
                        <input type="hidden" name="action" value="delete" />
                        <input type="hidden" name="event_id" id="deleteEventID" />
                        <button type="submit" class="delete-btn">🗑 Borrar</button>
                    </form>

            <!-- ❌ Cancelar -->
                    <button type="button" class="submit-btn">❌ Cancelar</button>

                </div>
            </div>

        <script>
            const events = <?= json_encode($eventsFromDB, JSON_UNESCAPED_UNICODE); ?>;
        </script>

        <script src="js/calendar.js"></script>

    </body>

</html>