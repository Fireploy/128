<?php

require_once 'Config/Helpers/currency_helper.php';

class Principal extends Controller
{
    public function __construct() {
        parent::__construct();
        session_start();
    }
    //obtener producto a partir de la lista de carrito
    public function listaProductos()
    {
        $datos = file_get_contents('php://input');
        $json = json_decode($datos, true);
        $array['productos'] = array();
        //NEW!!!
        $total = 0;
        if (!empty($json)) {
            foreach ($json as $producto) {
                $result = $this->model->getProducto($producto['idProducto']);
                $data['id'] = $result['id'];
                $data['nombre'] = $result['nombre'];
                //NEW!!!
                $data['precio'] = ($result['precio']);
                $data['cantidad'] = $producto['cantidad'];
                $data['imagen'] = $result['imagen'];
                $subTotal = $result['precio'] * $producto['cantidad'];
                //NEW!!!
                $data['subTotal'] = ($subTotal);
                array_push($array['productos'], $data);
                $total += $subTotal;
            }
        }  
        //NEW!!!      
        $array['total'] = ($total);
        //NEW!!! REVISAR!!!
        $array['moneda'] = MONEDA;
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function busqueda($valor)
    {
        $data = $this->model->getBusqueda($valor);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    //vista Tienda
    public function shop($page)
    {
        $pagina = (empty($page)) ? 1 : $page ;
        $porPagina = 20;
        $desde = ($pagina - 1) * $porPagina;
        $data['title'] = 'Nuestros Productos';
        $data['productos'] = $this->model->getProductos($desde, $porPagina);
        $data['pagina'] = $pagina;
        $total = $this->model->getTotalProductos();
        $data['total'] = ceil($total['total'] / $porPagina);
        $this->views->getView('principal', "shop", $data);
    }

    //NEW!!!
    //vista lista deseos
    public function deseo()
    {
        $data['title'] = 'Tu lista de deseo';
        $this->views->getView('principal', "deseo", $data);
    }

    //obtener producto a partir de la lista de deseo
    public function listaDeseo()
    {
        $datos = file_get_contents('php://input');
        $json =json_decode($datos, true);
        $array['productos'] = array();
        foreach ($json as $producto) {
            $result = $this->model->getListaDeseo($producto['idProducto']);
            $data['id'] = $result['id'];
            $data['nombre'] = $result['nombre'];
            $data['precio'] = ($result['precio']);
            $data['talla'] = isset($producto['talla']) ? $producto['talla'] : null;
            $data['cantidad'] = $producto['cantidad'];
            $data['imagen'] = $result['imagen'];
            array_push($array['productos'], $data);
        }
        $array['moneda'] = MONEDA;
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }

    //vista detail
    public function detail($id_producto)
    {
        $data['producto'] = $this->model->getProducto($id_producto);
        $id_categoria = $data['producto']['id_categoria'];
        $data['relacionados'] = $this->model->getAleatorios($id_categoria, $data['producto']['id']);
        $data['title'] = $data['producto']['nombre'];
        $data['tallas'] = $this->model->obtenerTallasPorProducto($id_producto);
        $this->views->getView('principal', "detail", $data);
        
    }
    
    //vista categorias
    public function categorias($datos)
    {
        $id_categoria = 1;
        $page = 1;
        $array = explode(',', $datos);
        if (isset($array[0])) {
            if(!empty($array[0])){
                $id_categoria = $array[0];
            }
        }
        if (isset($array[1])) {
            if(!empty($array[1])){
                $page = $array[1];
            }
        }
        $pagina = (empty($page)) ? 1 : $page ;
        $porPagina = 15;
        $desde = ($pagina - 1) * $porPagina;

        $data['pagina'] = $pagina;
        $total = $this->model->getTotalProductosCat($id_categoria);
        $data['total'] = ceil($total['total'] / $porPagina);

        $data['productos'] = $this->model->getProductosCat($id_categoria, $desde, $porPagina);
        $data['title'] = 'Categorias';
        $data['id_categoria'] = $id_categoria;
        $this->views->getView('principal', "categorias", $data);
    }
    
}