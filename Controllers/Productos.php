<?php

require_once 'Config/Helpers/currency_helper.php';

class Productos extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['nombre_usuario'])) {
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }
    }
    public function index()
    {
        $data['title'] = 'productos';
        $data['categorias'] = $this->model->getCategorias();
        $this->views->getView('admin/productos', "index", $data);
    }
    public function listar()
    {
        // Obtiene los productos activos (estado 1) desde el modelo
        $data = $this->model->getProductos(1);

        // Itera sobre cada producto y agrega la imagen y las acciones de edición y eliminación
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['imagen'] = '<img class="img-thumbnail" src="' . $data[$i]['imagen'] . '" alt="' . $data[$i]['nombre'] . '" width="50">';
            $data[$i]['accion'] = '<div class="d-flex">
            <button class="btn btn-primary" type="button" onclick="editPro(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
            <button class="btn btn-danger" type="button" onclick="eliminarPro(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></button>
        </div>';
        }
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        // Verifica si los campos 'nombre' y 'precio' están presentes en la solicitud POST
        if (isset($_POST['nombre']) && isset($_POST['precio'])) {
            $nombre = $_POST['nombre'];
            $precio = ($_POST['precio']);
            $cantidad = $_POST['cantidad'];
            $descripcion = $_POST['descripcion'];
            $categoria = $_POST['categoria'];
            $imagen = $_FILES['imagen'];
            $tmp_name = $imagen['tmp_name'];
            $id = $_POST['id'];
            $ruta = 'assets/images/productos/';
            $nombreImg = date('YmdHis');

            if ( empty($nombre) || empty($precio) || empty($cantidad) || ( empty($tmp_name) && empty($id))) {
                $respuesta = ['msg' => 'todo los campos son requeridos', 'icono' => 'warning'];
            } else {
                if (!empty($imagen['name'])) {
                    $destino = $ruta . $nombreImg . '.jpg';
                } else if (!empty($_POST['imagen_actual']) && empty($imagen['name'])) {
                    $destino = $_POST['imagen_actual'];
                } else {
                    $destino = $ruta . 'default.png';
                }

                if (empty($id)) {
                    $data = $this->model->registrar($nombre, $descripcion, $precio, $cantidad, $destino, $categoria);
                    if ($data > 0) {
                        if (!empty($imagen['name'])) {
                            move_uploaded_file($tmp_name, $destino);
                        }
                        $respuesta = array('msg' => 'producto registrado', 'icono' => 'success');
                    } else {
                        $respuesta = array('msg' => 'error al registrar', 'icono' => 'error');
                    }
                } else {
                    $data = $this->model->modificar($nombre, $descripcion, $precio, $cantidad, $destino, $categoria, $id);
                    if ($data == 1) {
                        if (!empty($imagen['name'])) {
                            move_uploaded_file($tmp_name, $destino);
                        }
                        $respuesta = array('msg' => 'producto modificado', 'icono' => 'success');
                    } else {
                        $respuesta = array('msg' => 'error al modificar', 'icono' => 'error');
                    }
                }
            }
            echo json_encode($respuesta);
        }
        die();
    }
    //eliminar productos
    public function delete($idPro)
    {
        if (is_numeric($idPro)) {
            $data = $this->model->eliminar($idPro);
            if ($data == 1) {
                $respuesta = array('msg' => 'producto dado de baja', 'icono' => 'success');
            } else {
                $respuesta = array('msg' => 'error al eliminar', 'icono' => 'error');
            }
        } else {
            $respuesta = array('msg' => 'error desconocido', 'icono' => 'error');
        }
        echo json_encode($respuesta);
        die();
    }

    //editar productos
    public function edit($idPro)
    {
        if (is_numeric($idPro)) {
            $data = $this->model->getProducto($idPro);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function galeriaImagenes()
    {
        $id = $_POST['idProducto'];

        // Se establece el nombre de la carpeta para las imágenes del producto
        $folder_name = 'assets/images/productos/' . $id . '/';

        // Se verifica si hay archivos en la solicitud
        if (!empty($_FILES)) {

            // Si la carpeta no existe, se crea para almacenar las imágenes del producto
            if (!file_exists($folder_name)) {
                mkdir($folder_name);
            }

            // Se mueve el archivo cargado a la carpeta del producto, asegurando que cada 
            //archivo tenga un nombre único basado en la fecha y hora actual
            $temp_name = $_FILES['file']['tmp_name'];
            $ruta = $folder_name . date('YmdHis') . $_FILES['file']['name'];
            move_uploaded_file($temp_name, $ruta);
        }
    }

    public function verGaleria($id_producto)
    {
        $result = array();

        // Se establece el directorio de las imágenes del producto
        $directorio = 'assets/images/productos/' . $id_producto;
        if (file_exists($directorio)) {

            // Se obtiene la lista de archivos en el directorio
            $imagenes = scandir($directorio);
            if (false !== $imagenes) {
                foreach ($imagenes as $file) {

                    // Se ignoran las entradas '.' y '..' que representan el directorio actual y el directorio padre
                    if ('.' != $file && '..' != $file) {

                        // Se agrega el nombre del archivo a la lista de resultados
                        array_push($result, $file);
                    }
                }
            }
        }
        echo json_encode($result);
        die();
    }

    public function eliminarImg()
    {
        // Se obtiene el cuerpo de la solicitud que contiene los datos enviados en formato JSON
        $datos = file_get_contents('php://input');

        // Se decodifican los datos JSON a un array asociativo
        $json = json_decode($datos, true);

        // Se establece la ruta del archivo a eliminar basado en la URL proporcionada en los datos JSON
        $destino = 'assets/images/productos/' . $json['url'];
        if (unlink($destino)) {
            $res = array('msg' => 'IMAGEN ELIMINADO', 'icono' => 'success');
        } else {
            $res = array('msg' => 'ERROR AL ELIMINAR', 'icono' => 'error');
        }
        echo json_encode($res);
        die();
    }
}
