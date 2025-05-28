<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
class Admin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }

    //muestra la página de inicio de sesión si el administrador no está logueado
    public function index()
    {
        if (!empty($_SESSION['nombre_usuario'])) {
            header('Location: '. BASE_URL . 'admin/home');
            exit;
        }
        $data['title'] = 'Acceso al sistema';
        $this->views->getView('admin', "login", $data);
    }

    //NEW!!!
    public function recovery() {
        // Puedes enviar datos a la vista si lo necesitas, por ejemplo, el título de la página
        $data['title'] = 'Recuperar Contraseña';
        $this->views->getView('admin', "recovery", $data);
    }

    public function sendRecovery() {
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $correo = $_POST['email'];
            $dataUser = $this->model->getUsuario($correo);
            if (!empty($dataUser)) {
                $token = md5(uniqid(rand(), true));
                $update = $this->model->updateToken($correo, $token);
                if ($update) {
                    $nombre = $dataUser['nombres'];

                    ob_start();
                    include __DIR__ . '/../Views/admin/email_cambiarPassword.php';
                    $htmlBody = ob_get_clean();

                    $mail = new PHPMailer(true);
                    try {
                        //Configuración del servidor
                        $mail->SMTPDebug = 0;
                        $mail->isSMTP();
                        $mail->Host       = HOST_SMTP;        // Ej.: smtp.gmail.com
                        $mail->SMTPAuth   = true;
                        $mail->Username   = USER_SMTP;        // Tu usuario SMTP
                        $mail->Password   = PASS_SMTP;        // Tu contraseña SMTP
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                        $mail->Port       = PUERTO_SMTP;       // Ej.: 465

                        $mail->CharSet = 'UTF-8';
                        
                        //Destinatarios
                        $mail->setFrom('pijamas.shalom.notificaciones@gmail.com', TITLE);
                        $mail->addAddress($correo);

                        //Imagenes
                        $mail->addEmbeddedImage(
                            __DIR__ . '/../assets/images/facebook-logo-black.png',
                            'facebook_logo'
                        );
                        $mail->addEmbeddedImage(
                            __DIR__ . '/../assets/images/instagram-logo-black.png',
                            'instagram_logo'
                        );
                        $mail->addEmbeddedImage(
                            __DIR__ . '/../assets/images/logo_shalom_circularmodified_3.png',
                            'logo_shalom'
                        );
                        
                        // Contenido del correo
                        $mail->isHTML(true);
                        $mail->Subject = 'Recuperación de Contraseña - ' . TITLE;
                        $mail->Body    = $htmlBody;
                        $mail->AltBody = 'Visita: ' . BASE_URL . 'admin/resetPassword/' . $token;

                        $mail->send();
                        $mensaje = array('msg' => 'Correo enviado. Revisa tu bandeja de entrada.', 'icono' => 'success');
                    } catch (Exception $e) {
                        $mensaje = array('msg' => 'Error al enviar correo: ' . $mail->ErrorInfo, 'icono' => 'error');
                    }
                } else {
                    $mensaje = array('msg' => 'Error al actualizar el token.', 'icono' => 'error');
                }
            } else {
                $mensaje = array('msg' => 'El correo no existe.', 'icono' => 'error');
            }
        } else {
            $mensaje = array('msg' => 'El correo es requerido.', 'icono' => 'error');
        }
        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        exit;
    }

//restablecer contraseña nueva
    public function resetPassword($token) {
        $user = $this->model->getUserByToken($token);
        if (empty($user)) {
            header('Location: ' . BASE_URL . 'admin?msg=token_invalido');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
                $data['error'] = 'Todos los campos son requeridos.';
                $data['title'] = 'Restablecer Contraseña';
                $data['token'] = $token;
                $this->views->getView('admin', 'reset_password', $data);
                return;
            }
            
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            if (!preg_match('/^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).*$/', $newPassword)) {
                $data = [
                    'error' => 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.',
                    'title' => 'Restablecer Contraseña',
                    'token' => $token
                ];
                return $this->views->getView('admin', 'reset_password', $data);
            }
            
            if ($newPassword !== $confirmPassword) {
                $data['error'] = 'Las contraseñas no coinciden.';
                $data['title'] = 'Restablecer Contraseña';
                $data['token'] = $token;
                $this->views->getView('admin', 'reset_password', $data);
                return;
            }
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $update = $this->model->updateNewPassword($user['correo'], $hashedPassword);
            if ($update) {
                $this->model->clearToken($user['correo']);
                header('Location: ' . BASE_URL . 'admin?msg=password_updated');
                exit;
            } else {
                $data['error'] = 'Error al actualizar la contraseña. Inténtalo de nuevo.';
                $data['title'] = 'Restablecer Contraseña';
                $data['token'] = $token;
                $this->views->getView('admin', 'reset_password', $data);
                return;
            }
        } else {
            $data['title'] = 'Restablecer Contraseña';
            $data['token'] = $token;
            $this->views->getView('admin', 'reset_password', $data);
        }
    }
    

    //validar las credenciales de inicio de sesión del administración
    public function validar()
    {
        if (isset($_POST['email']) && isset($_POST['clave'])) {
            if (empty($_POST['email']) || empty($_POST['clave'])) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                $data = $this->model->getUsuario($_POST['email']);
                if (empty($data)) {
                    $respuesta = array('msg' => 'el correo no existe', 'icono' => 'warning');
                } else {
                    if (password_verify($_POST['clave'], $data['clave'])) {
                        $_SESSION['email'] = $data['correo'];
                        $_SESSION['nombre_usuario'] = $data['nombres'];
                        $respuesta = array('msg' => 'datos correcto', 'icono' => 'success');
                    } else {
                        $respuesta = array('msg' => 'contraseña incorrecta', 'icono' => 'warning');
                    }
                }
            }
        } else {
            $respuesta = array('msg' => 'error desconocido', 'icono' => 'error');
        }
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        die();
    }

    //muestra la página principal de administración si el está logueado, muestra las estadísticas
    public function home()
    {
        if (empty($_SESSION['nombre_usuario'])) {
            header('Location: '. BASE_URL . 'admin');
            exit;
        }
        $data['title'] = 'administracion';
        $data['pendientes'] = $this->model->getTotales(1);
        $data['procesos'] = $this->model->getTotales(2);
        $data['finalizados'] = $this->model->getTotales(3);
        $data['productos'] = $this->model->getProductos();
        $this->views->getView('admin/administracion', "index", $data);
    }

    //obtener los productos con cantidades mínimas
    public function productosMinimos()
    {
        if (empty($_SESSION['nombre_usuario'])) {
            header('Location: '. BASE_URL . 'admin');
            exit;
        }
        $data = $this->model->productosMinimos();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();

    }

    //obtener los productos más vendidos
    public function topProductos()
    {
        if (empty($_SESSION['nombre_usuario'])) {
            header('Location: '. BASE_URL . 'admin');
            exit;
        }
        $data = $this->model->topProductos();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();

    }
    
    // Método para cerrar la sesión del usuario
    public function salir()
    {
        session_destroy();
        header('Location: ' . BASE_URL);
    }
}
