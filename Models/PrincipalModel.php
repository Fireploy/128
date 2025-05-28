<?php
class PrincipalModel extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function getProducto($id_producto) {
        $sql = "SELECT p.*, c.categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id WHERE p.id = ?";
        return $this->select($sql, [$id_producto]);
    }

    public function getBusqueda($valor) {
        $sql = "SELECT * FROM productos WHERE nombre LIKE ? OR descripcion LIKE ? LIMIT 5";
        return $this->selectAll($sql, ["%$valor%", "%$valor%"]);
    }

    public function getProductos($desde, $porPagina) {
        $sql = "SELECT * FROM productos LIMIT ?, ?";
        return $this->selectAll($sql, [$desde, $porPagina]);
    }

    public function getTotalProductos() {
        $sql = "SELECT COUNT(*) AS total FROM productos";
        return $this->select($sql);
    }

    public function getProductosCat($id_categoria, $desde, $porPagina) {
        $sql = "SELECT * FROM productos WHERE id_categoria = ? LIMIT ?, ?";
        return $this->selectAll($sql, [$id_categoria, $desde, $porPagina]);
    }

    public function getTotalProductosCat($id_categoria) {
        $sql = "SELECT COUNT(*) AS total FROM productos WHERE id_categoria = ?";
        return $this->select($sql, [$id_categoria]);
    }

    //productos relacionados aleatorios
    public function getAleatorios($id_categoria, $id_producto) {
        $sql = "SELECT * FROM productos WHERE id_categoria = ? AND id != ? ORDER BY RAND() LIMIT 20";
        return $this->selectAll($sql, [$id_categoria, $id_producto]);
    }

    //NEW!!!
    //obtener producto a partir de la lista de deseo
    public function getListaDeseo($id_producto) {
        $sql = "SELECT * FROM productos WHERE id = $id_producto";
        return $this->select($sql);
    }

    public function obtenerTallasPorProducto($producto_id) {
        $sql = "SELECT DISTINCT t.nombre AS talla
        FROM tallas t
        INNER JOIN categoria_talla ct ON t.id = ct.talla_id
        INNER JOIN productos p ON p.id_categoria = ct.categoria_id
        WHERE p.id = ?";
        $query = $this->con->prepare($sql);
        $query->execute([$producto_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    }
?>
