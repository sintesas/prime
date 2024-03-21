<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Documentosbibliograficosred extends TableGateway
{
    // variables de la tabla
    private $id_red;
    private $nombre;    
    private $ano;
    private $pais;
    private $numero_indexacion;
    private $numero_paginas;
    private $mes;
    private $ciudad;
    private $url;
    private $instituciones;
    private $descripcion;
    private $medio_divulgacion;
    private $id_autor;

    //funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mri_documentosbibliograficos', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array(), $id)
    {
        $this->id_red = $id;
        $this->nombre = $datos["nombre"];
        $this->ano = $datos["ano"];
        $this->pais = $datos["pais"];
        $this->numero_indexacion = $datos["numero_indexacion"];
        $this->numero_paginas = $datos["numero_paginas"];
        $this->mes = $datos["mes"];
        $this->ciudad = $datos["ciudad"];
        $this->url = $datos["url"];
        $this->instituciones = $datos["instituciones"];
        $this->descripcion = $datos["descripcion"];
        $this->medio_divulgacion = $datos["medio_divulgacion"];
        $this->id_autor = $datos["id_autor"];
    }

    public function addDocumentosbibliograficosred($data, $id, $archi)
    {
        self::cargaAtributos($data, $id);
        $array = array(
            'id_red' => $this->id_red,
            'nombre' => $this->nombre,
            'ano' => $this->ano,
            'pais' => $this->pais,
            'numero_indexacion' => $this->numero_indexacion,
            'numero_paginas' => $this->numero_paginas,
            'mes' => $this->mes,
            'ciudad' => $this->ciudad,
            'url' => $this->url,
            'instituciones' => $this->instituciones,
            'descripcion' => $this->descripcion,
            'medio_divulgacion' => $this->medio_divulgacion,
            'id_autor' => $this->id_autor,
            'archivo' => $archi
        );
        $this->insert($array);
        return 1;
    }

    public function getDocumentosbibliograficosredid($id)
    {
        $resultSet = $this->select(array(
            'id_red' => $id
        ));
        return $resultSet->toArray();
    }

    public function getDocumentosbibliograficosredt()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }


    public function eliminarDocumentosbibliograficosred($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getBibliograficosredById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateBibliograficosred($id, $data = array())
    {   
        $array = array(
            'nombre' => $data->nombre,
            'numero_paginas' => $data->numero_paginas,
            'instituciones' => $data->instituciones,
            'ano' => $data->ano,
            'mes' => $data->mes,
            'numero_indexacion' => $data->numero_indexacion,
            'url' => $data->url,
            'medio_divulgacion' => $data->medio_divulgacion,
            'descripcion' => $data->descripcion,
            'pais' => $data->pais,
            'ciudad' => $data->ciudad,
            'id_autor' => $data->id_autor
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
    
    public function updatearchivoBibliograficosred($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>
