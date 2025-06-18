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
    $consulta = $this->db->prepare("INSERT INTO curso (nombre, descripcion, precio, fecha_inicio, fecha_fin, estado) VALUES (?, ?, ?, ?, ?, ?)");
    $consulta->bind_param("ssdsss", $nombre, $descripcion, $precio, $fecha_inicio, $fecha_fin, $estado);
    return $consulta->execute();
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
    public function obtenerCursosDisponibles() {
        $stmt = $this->db->prepare("SELECT id_curso, nombre, descripcion, precio FROM curso WHERE estado = 'disponible'");
        $stmt->execute();
        $resultado = $stmt->get_result();
        $cursos = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $cursos;
    }

    public function obtenerCursoPorId($id_curso) {
        $stmt = $this->db->prepare("SELECT id_curso, nombre, descripcion, precio FROM curso WHERE id_curso = ?");
        $stmt->bind_param("i", $id_curso);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $curso = $resultado->fetch_assoc();
        $stmt->close();
        
        return $curso;
    }

}

?>