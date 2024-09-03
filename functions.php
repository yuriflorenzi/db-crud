<?php
// functions.php

include 'config.php';

function getAllUsers() {
    $conn = dbConnect();
    $sql = "SELECT * FROM usuarios";
    $result = $conn->query($sql);
    $users = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    $conn->close();
    return $users;
}

function createUser($nombre, $apellidos, $dni, $celular) {
    $conn = dbConnect();
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellidos, dni, celular) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $apellidos, $dni, $celular);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function updateUser($id, $nombre, $apellidos, $dni, $celular) {
    $conn = dbConnect();
    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, apellidos = ?, dni = ?, celular = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nombre, $apellidos, $dni, $celular, $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function deleteUser($id) {
    $conn = dbConnect();
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'create') {
        createUser($_POST['nombre'], $_POST['apellidos'], $_POST['dni'], $_POST['celular']);
    } elseif ($action == 'update') {
        updateUser($_POST['id'], $_POST['nombre'], $_POST['apellidos'], $_POST['dni'], $_POST['celular']);
    } elseif ($action == 'delete') {
        deleteUser($_POST['id']);
    }

    header("Location: index.php");
    exit();
}
?>
