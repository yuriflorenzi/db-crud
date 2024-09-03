<?php
include 'functions.php';
$users = getAllUsers();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Gestión de Usuarios</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openUserModal()">Añadir Usuario</button>
        <div class="card">
            <div class="card-header">
                <h5>Usuarios Registrados</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>DNI</th>
                            <th>Celular</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $user): ?>
                                <tr data-id="<?= $user['id'] ?>">
                                    <td class="user-id"><?= htmlspecialchars($user['id']) ?></td>
                                    <td class="user-nombre"><?= htmlspecialchars($user['nombre']) ?></td>
                                    <td class="user-apellidos"><?= htmlspecialchars($user['apellidos']) ?></td>
                                    <td class="user-dni"><?= htmlspecialchars($user['dni']) ?></td>
                                    <td class="user-celular"><?= htmlspecialchars($user['celular']) ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openUserModal(<?= $user['id'] ?>)">Editar</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $user['id'] ?>)">Eliminar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay usuarios registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Añadir Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" name="id" id="userId">
                        <input type="hidden" name="action" id="formAction" value="create">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                        </div>
                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" required>
                        </div>
                        <div class="mb-3">
                            <label for="celular" class="form-label">Celular</label>
                            <input type="text" class="form-control" id="celular" name="celular" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openUserModal(userId = null) {
            const modalTitle = document.getElementById('userModalLabel');
            const formAction = document.getElementById('formAction');
            const userIdInput = document.getElementById('userId');
            const userForm = document.getElementById('userForm');

            if (userId) {
                modalTitle.textContent = 'Actualizar Usuario';
                formAction.value = 'update';
                userIdInput.value = userId;

                const userRow = document.querySelector(`tr[data-id='${userId}']`);
                document.getElementById('nombre').value = userRow.querySelector('.user-nombre').textContent;
                document.getElementById('apellidos').value = userRow.querySelector('.user-apellidos').textContent;
                document.getElementById('dni').value = userRow.querySelector('.user-dni').textContent;
                document.getElementById('celular').value = userRow.querySelector('.user-celular').textContent;
            } else {
                modalTitle.textContent = 'Añadir Usuario';
                formAction.value = 'create';
                userIdInput.value = '';
                userForm.reset();
            }
        }

        $('#userForm').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'functions.php',
                data: formData,
                success: function () {
                    location.reload();
                },
                error: function () {
                    alert('Ocurrió un error al guardar los datos.');
                }
            });
        });

        function deleteUser(userId) {
            if (confirm('¿Estás seguro de eliminar este usuario?')) {
                $.ajax({
                    type: 'POST',
                    url: 'functions.php',
                    data: {action: 'delete', id: userId},
                    success: function () {
                        location.reload();
                    },
                    error: function () {
                        alert('Ocurrió un error al eliminar el usuario.');
                    }
                });
            }
        }
    </script>
</body>
</html>
