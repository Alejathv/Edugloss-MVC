<?php
require_once __DIR__ . '/database.php';
// En tu modelo CursoModel.php
class CursoModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    //CREACION DE CURSO PARA EL DOCENTE 
    public function crearCurso($nombre, $descripcion, $precio, $fecha_inicio, $fecha_fin, $estado) {
        $stmt = $this->db->prepare("INSERT INTO curso (nombre, descripcion, precio, fecha_inicio, fecha_fin, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Error en prepare: " . $this->db->error);
        }
        $stmt->bind_param("ssdssss", $nombre, $descripcion, $precio, $fecha_inicio, $fecha_fin, $estado);
        $exec = $stmt->execute();
        if (!$exec) {
            die("Error en execute: " . $stmt->error);
        }
        return $exec;
    }


    public function obtenerCursos() {
        $result = $this->db->query("SELECT * FROM curso");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //CREACION DE CURSO PARA EL DOCENTE 

    //MOSTRAR LOS CURSOS QUE ESTAN INSCRITOS POR USUARIO
    public function getCursosInscritosPorUsuario($id_usuario) {
        $stmt = $this->db->prepare("SELECT c.* FROM curso c
            JOIN inscripcion i ON c.id_curso = i.id_curso
            WHERE i.id_usuario = ? AND i.estado = 'activa'");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function eliminarCurso($id_curso) {
    // Primero eliminamos los módulos ligados al curso (clave foránea)
    $this->db->query("DELETE FROM modulo WHERE id_curso = $id_curso");
    return $this->db->query("DELETE FROM curso WHERE id_curso = $id_curso");
    }
    public function obtenerCursoPorId($id) {
    $stmt = $this->db->prepare("SELECT * FROM curso WHERE id_curso = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    }

    public function actualizarCurso($data) {
        $stmt = $this->db->prepare("UPDATE curso SET nombre = ?, descripcion = ?, precio = ?, fecha_inicio = ?, fecha_fin = ?, estado = ? WHERE id_curso = ?");
        $stmt->bind_param("ssisssi",
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['estado'],
            $data['id_curso']
        );
        return $stmt->execute();
    }


}

?>