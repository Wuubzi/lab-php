<?php
require __DIR__ . '/../../core/Cloudinary.php';

class EstudiantesController extends Controller
{
    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function index()
    {
        try {
            $estudiante = $this->model('Estudiantes');
            $estudiantes = $estudiante->getEstudiantes();

            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'Estudiantes obtenidos correctamente',
                'data' => $estudiantes,
                'count' => count($estudiantes)
            ]);

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Error al obtener estudiantes: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id_user_historial = trim($_POST['id_user_historial' ?? '']);
                $nombre = trim($_POST['nombre'] ?? '');
                $apellido = trim($_POST['apellido'] ?? '');
                $usuario = trim($_POST['usuario'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $programa = trim($_POST['programa'] ?? '');
                $calificacion = trim($_POST['calificacion'] ?? '');

                $errors = [];

                if (empty($nombre))
                    $errors[] = "El nombre es obligatorio.";
                if (empty($apellido))
                    $errors[] = "El apellido es obligatorio.";
                if (empty($usuario))
                    $errors[] = "El usuario no es válido.";
                if (empty($programa))
                    $errors[] = "El programa no es valido";

                if (empty($email)) {
                    $errors[] = "El correo es obligatorio.";
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "El correo no es válido.";
                }

                if (empty($errors)) {
                    $Estudiante = $this->model('Estudiantes');
                    $result = $Estudiante->createEstudiante([
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'usuario' => $usuario,
                        'email' => $email,
                        'programa' => $programa,
                        'calificacion' => $calificacion
                    ]);

                    if ($result) {
                        $data = $Estudiante->getEstudiantesById($id_user_historial);
                        // Registrar en el historial
                        $Historial = $this->model('Historial');
                        $Historial->registrarCambio([
                            'usuario' => $data["usuario"],
                            'email' => $data["usuario"],
                            'accion' => 'CREAR',
                            'tabla_recurso' => 'usuarios',
                            'descripcion' => "Nuevo estudiante creado: {$nombre} {$apellido}",
                            'valores_nuevos' => [
                                'nombre' => $nombre,
                                'apellido' => $apellido,
                                'usuario' => $usuario,
                                'email' => $email,
                                'programa' => $programa,
                                'calificacion' => $calificacion
                            ]
                        ]);

                        return $this->jsonResponse([
                            'status' => 'success',
                            'message' => 'Usuario creado correctamente'
                        ], 201);
                    }

                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'Ocurrió un error al registrar el usuario.'
                    ], 500);
                }
            } catch (Exception $e) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => '' . $e->getMessage(),
                ], 500);
            }
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Método no permitido'
            ], 405);
        }

        try {
            $id_user_historial = trim($_POST['id_user_historial' ?? '']);
            $id = trim($_POST['id'] ?? '');
            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $usuario = trim($_POST['usuario'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $programa = trim($_POST['programa'] ?? '');
            $calificacion = trim($_POST['calificacion'] ?? 0);

            $fotoUrl = null;

            // Validaciones
            $errors = [];
            if (empty($id))
                $errors[] = "El id es obligatorio";
            if (empty($nombre))
                $errors[] = "El nombre es obligatorio.";
            if (empty($apellido))
                $errors[] = "El apellido es obligatorio.";
            if (empty($usuario))
                $errors[] = "El usuario no es válido.";
            if (empty($programa))
                $errors[] = "El programa no es válido";
            if (empty($email)) {
                $errors[] = "El correo es obligatorio.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "El correo no es válido.";
            }

            if (!empty($errors)) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Errores de validación',
                    'errors' => $errors
                ], 422);
            }

            $Estudiante = $this->model('Estudiantes');

            // Obtener datos anteriores para el historial
            $datosAnteriores = $Estudiante->getEstudiantesById($id);

            // Manejo de foto
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                require_once __DIR__ . '/../../core/Cloudinary.php';
                $cloudinary = new CloudinaryService();
                $fotoUrl = $cloudinary->uploadImage($_FILES['foto']);
            }

            if ($fotoUrl === null && !empty($datosAnteriores) && !empty($datosAnteriores['profile_url'])) {
                $fotoUrl = $datosAnteriores['profile_url'];
            }

            $dataToUpdate = [
                'id' => $id,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'usuario' => $usuario,
                'email' => $email,
                'programa' => $programa,
                'calificacion' => $calificacion
            ];

            if ($fotoUrl !== null) {
                $dataToUpdate['profile_url'] = $fotoUrl;
            }

            $updated = $Estudiante->updateEstudiante($dataToUpdate);

            if ($updated) {
                $data = $Estudiante->getEstudiantesById($id_user_historial);
                // Registrar en el historial
                $cambios = $this->detectarCambios($datosAnteriores, $dataToUpdate);
                $Historial = $this->model('Historial');
                $Historial->registrarCambio([
                    'usuario' => $data["usuario"],
                    'email' => $data["email"],
                    'accion' => 'MODIFICAR',
                    'tabla_recurso' => 'usuarios',
                    'descripcion' => "Estudiante actualizado: {$nombre} {$apellido} - Campos modificados: " . implode(', ', array_keys($cambios)),
                    'valores_anteriores' => $datosAnteriores,
                    'valores_nuevos' => $dataToUpdate
                ]);

                return $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'Estudiante actualizado correctamente',
                ], 200);
            } else {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'No se pudo actualizar el estudiante o no hubo cambios'
                ], 500);
            }

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id_user_historial = trim($_POST['id_user_historial' ?? '']);
                $id = trim($_POST['id'] ?? '');

                if (empty($id)) {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'El id es obligatorio.',
                    ], 400);
                }

                $estudiante = $this->model('Estudiantes');

                // Obtener datos antes de eliminar para el historial
                $datosAnteriores = $estudiante->getEstudiantesById($id);

                $result = $estudiante->deleteEstudiante($id);

                if ($result) {
                    $data = $estudiante->getEstudiantesById($id_user_historial);
                    // Registrar en el historial
                    $Historial = $this->model('Historial');
                    $Historial->registrarCambio([
                        'usuario' => $data["usuario"],
                        'email' => $data["email"],
                        'accion' => 'ELIMINAR',
                        'tabla_recurso' => 'usuarios',
                        'descripcion' => "Estudiante eliminado: {$datosAnteriores['nombre']} {$datosAnteriores['apellido']}",
                        'valores_anteriores' => $datosAnteriores
                    ]);

                    return $this->jsonResponse([
                        'status' => 'success',
                        'message' => 'Usuario Eliminado Correctamente',
                    ], 200);
                } else {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'No se pudo eliminar el usuario.',
                    ], 400);
                }
            } catch (Exception $e) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        }

        return $this->jsonResponse([
            'status' => 'error',
            'message' => 'Método no permitido',
        ], 405);
    }

    // Método para obtener el historial
    public function historial()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $limite = $_GET['limite'] ?? 50;
                $tabla = $_GET['tabla'] ?? null;

                if ($tabla) {
                    $Historial = $this->model('Historial');
                    $historial = $Historial->obtenerHistorialPorTabla($tabla, $limite);
                } else {
                    $Historial = $this->model('Historial');
                    $historial = $Historial->obtenerHistorial($limite);
                }

                return $this->jsonResponse([
                    'status' => 'success',
                    'data' => $historial,
                    'count' => count($historial)
                ]);

            } catch (Exception $e) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        return $this->jsonResponse([
            'status' => 'error',
            'message' => 'Método no permitido'
        ], 405);
    }

    // Método auxiliar para detectar cambios específicos
    private function detectarCambios($datosAnteriores, $datosNuevos)
    {
        $cambios = [];
        $camposComparables = ['nombre', 'apellido', 'usuario', 'email', 'programa', 'calificacion', 'profile_url'];

        foreach ($camposComparables as $campo) {
            $anterior = $datosAnteriores[$campo] ?? '';
            $nuevo = $datosNuevos[$campo] ?? '';

            if ($anterior != $nuevo) {
                $cambios[$campo] = [
                    'anterior' => $anterior,
                    'nuevo' => $nuevo
                ];
            }
        }

        return $cambios;
    }

    // Resto de métodos sin cambios...
    public function getEstudiantesByPrograma()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $programa = trim($_GET['programa']);

                if (empty($programa)) {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'El programa es obligatorio.'
                    ], 400);
                }

                $estudiante = $this->model("Estudiantes");
                $data = $estudiante->getEstudiantesByPrograma($programa);
                if ($data) {
                    return $this->jsonResponse([
                        'status' => 'success',
                        'data' => $data
                    ], 200);
                } else {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'Estudiante no encontrado.'
                    ], 404);
                }

            } catch (Exception $e) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function getEstudiantesByCalificaciones()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            try {
                $calificacion_inicio = trim($_GET['calificacion_inicio']);
                $calificacion_final = trim($_GET["calificacion_final"]);

                if (empty($calificacion_final)) {
                    return $this->jsonResponse([
                        "status" => "error",
                        "message" => "Calificacion final es obligatorio",
                    ], 400);
                }

                $estudiante = $this->model("Estudiantes");
                $data = $estudiante->getEstudiantesByCalificaciones(['calificacion_inicio' => $calificacion_inicio, 'calificacion_final' => $calificacion_final]);
                if ($data) {
                    return $this->jsonResponse([
                        'status' => 'success',
                        'data' => $data
                    ], 200);
                } else {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'Estudiante no encontrado.'
                    ], 404);
                }
            } catch (Exception $e) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function getEstudiantesByBusqueda()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $busqueda = trim($_GET['busqueda']);

                if (empty($busqueda)) {
                    return $this->jsonResponse([
                        "status" => "error",
                        "message" => "La busqueda es obligatoria",
                    ], 400);
                }

                $estudiante = $this->model("Estudiantes");
                $data = $estudiante->getEstudiantesByBusqueda($busqueda);
                if ($data) {
                    return $this->jsonResponse([
                        'status' => 'success',
                        'data' => $data
                    ], 200);
                } else {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'Estudiante no encontrado.'
                    ], 404);
                }
            } catch (Exception $e) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function getEstudianteById()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $id = trim($_GET['id'] ?? '');

                if (empty($id)) {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'El id es obligatorio.'
                    ], 400);
                }

                $estudiante = $this->model('Estudiantes');
                $data = $estudiante->getEstudiantesById($id);

                if ($data) {
                    return $this->jsonResponse([
                        'status' => 'success',
                        'data' => $data
                    ], 200);
                } else {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'Estudiante no encontrado.'
                    ], 404);
                }
            } catch (Exception $e) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        return $this->jsonResponse([
            'status' => 'error',
            'message' => 'Método no permitido'
        ], 405);
    }

    public function perfil()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('perfil');
        }
    }
}
?>