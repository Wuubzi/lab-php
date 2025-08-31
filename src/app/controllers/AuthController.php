<?php
class AuthController extends Controller
{
    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';
            $errors = [];

            if (empty($usuario)) {
                $errors[] = "El username no es válido.";
            }

            if (strlen($contrasena) < 6) {
                $errors[] = "La contraseña debe tener al menos 6 caracteres.";
            }

            if (empty($errors)) {
                $user = $this->model('Users');
                $userdata = $user->findByUsuario($usuario);

                if (!$userdata || !password_verify($contrasena, $userdata['contrasena'])) {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'Credenciales inválidas.'
                    ], 401);
                }

                session_start();
                $_SESSION['id_user'] = $userdata['id'];
                $_SESSION['usuario'] = $userdata['usuario'];

                return $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'Login correcto',
                    'id_user' => $userdata['id'],
                    'usuario' => $userdata['usuario'],
                ]);
            }

            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $errors
            ], 400);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = ['title' => 'Login'];
            return $this->view('login', $data);
        }

        return $this->jsonResponse([
            'status' => 'error',
            'message' => 'Método no permitido'
        ], 405);
    }
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $usuario = trim($_POST['usuario'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $contrasena = trim($_POST['contrasena'] ?? '');
            $programa = trim($_POST['programa'] ?? '');

            $errors = [];

            if (empty($nombre))
                $errors[] = "El nombre es obligatorio.";
            if (empty($apellido))
                $errors[] = "El apellido es obligatorio.";
            if (empty($usuario))
                $errors[] = "El usuario no es válido.";
            if (strlen($contrasena) < 6)
                $errors[] = "La contraseña debe tener al menos 6 caracteres.";
            if (empty($programa))
                $errors[] = "El programa no es valido";

            if (empty($email)) {
                $errors[] = "El correo es obligatorio.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "El correo no es válido.";
            }

            if (empty($errors)) {
                $hashed_password = password_hash($contrasena, PASSWORD_BCRYPT);

                $User = $this->model('Users');
                $result = $User->create([
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'usuario' => $usuario,
                    'email' => $email,
                    'contrasena' => $hashed_password,
                    'programa' => $programa,
                ]);

                if ($result) {
                    return $this->jsonResponse([
                        'status' => 'success',
                        'message' => 'Usuario registrado correctamente'
                    ], 201);
                }

                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Ocurrió un error al registrar el usuario.'
                ], 500);
            }

            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $errors
            ], 400);
        }


        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = ['title' => 'Registro'];
            return $this->view('register', $data);
        }

        return $this->jsonResponse([
            'status' => 'error',
            'message' => 'Método no permitido'
        ], 405);
    }

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $errors = [];

            if (empty($email)) {
                $errors[] = "El correo es obligatorio.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "El correo no es válido.";
            }

            if (!empty($errors)) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'errors' => $errors
                ], 400);
            }

            $User = $this->model('Users');
            $userData = $User->findByEmail($email);
            if (!$userData) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'No existe un usuario con ese correo.'
                ], 404);
            }


            $codigo = random_int(100000, 999999);


            session_start();
            $_SESSION['reset_code'] = $codigo;
            $_SESSION['email'] = $email;
            $_SESSION['reset_expires'] = time() + (15 * 60);
            $_SESSION['reset_email'] = $email;


            require __DIR__ . '/../../vendor/autoload.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            try {

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'carlosasalas321@gmail.com';
                $mail->Password = 'qdiy iful peld eghz';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;


                $mail->setFrom('carlosasalas321@gmail.com', 'Sistema');
                $mail->addAddress($email);


                $mail->isHTML(true);
                $mail->Subject = 'Recuperación de contraseña';
                $mail->Body = "Tu código de recuperación es: <b>{$codigo}</b>";

                $mail->send();

                return $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'Código enviado al correo'
                ]);
            } catch (Exception $e) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => "No se pudo enviar el correo. Error: {$mail->ErrorInfo}"
                ], 500);
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = ['title' => 'Recuperar contraseña'];
            return $this->view('recuperar_contraseña', $data);
        }
    }


    public function validateCode()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $code = trim($_POST['code'] ?? '');


            if (!isset($_SESSION['reset_code']) || time() > $_SESSION['reset_expires']) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'El código ha expirado.'
                ], 400);
            }

            if ($code != $_SESSION['reset_code']) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'El código no es válido.'
                ], 400);
            }
            $_SESSION['reset_password'] = "true";
            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'Código válido, ahora puedes actualizar tu contraseña.'
            ]);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = ['title' => 'Verificar Código'];
            return $this->view('verificar_codigo', $data);
        }
    }


    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $contrasena = trim($_POST['contrasena'] ?? '');
            $confirmarContrasena = trim($_POST['confirmarContrasena'] ?? '');
            $errors = [];

            if (strlen($contrasena) < 6) {
                $errors[] = "La contraseña debe tener al menos 6 caracteres.";
            }
            if (strlen($confirmarContrasena) < 6) {
                $errors[] = "La contraseña debe tener al menos 6 caracteres.";
            }

            if ($contrasena !== $confirmarContrasena) {
                $errors[] = "las contraseñas no coinciden";
            }


            if (!isset($_SESSION['reset_password'])) {
                $errors[] = "No hay un proceso de recuperación activo.";
            }

            if (!empty($errors)) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'errors' => $errors
                ], 400);
            }


            $hashed = password_hash($contrasena, PASSWORD_BCRYPT);
            $User = $this->model('Users');
            $User->updatePassword($_SESSION['email'], $hashed);

            unset($_SESSION['reset_email'], $_SESSION['reset_code'], $_SESSION['reset_expires']);

            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'Contraseña actualizada correctamente.'
            ]);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = ['title' => 'Actualizar Contraseña'];
            return $this->view('actualizar_contraseña', $data);
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();

        return $this->jsonResponse([
            'status' => 'success',
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}