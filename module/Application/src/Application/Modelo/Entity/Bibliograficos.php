<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Bibliograficos extends TableGateway{

	//variables de la tabla
	private $nombre_documento;
	private $numero_paginas;
	private $ano;
	private $mes;
	private $num_indexacion;
	private $url;
	private $medio_divulgacion;
	private $autores;
	private $instituciones;
	private $descripcion;
	private $id_autor;
	private $pais;
	private $ciudad;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgi_gi_bibliograficos', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->nombre_documento=$datos["nombre_documento"];
		$this->numero_paginas=$datos["numero_paginas"];
		$this->ano=$datos["ano"];
		$this->mes=$datos["mes"];
		$this->num_indexacion=$datos["num_indexacion"];
		$this->url=$datos["url"];
		$this->medio_divulgacion=$datos["medio_divulgacion"];
		$this->autores=$datos["autores"];
		$this->instituciones=$datos["instituciones"];
		$this->descripcion=$datos["descripcion"];
		$this->id_autor=$datos["id_autor"];
		$this->pais=$datos["pais"];
		$this->ciudad=$datos["ciudad"];
	}

	public function addBibliograficos($data=array(), $id, $archi){
		self::cargaAtributos($data);

        		$array=array(
			'id_grupo_inv'=>$id,
			'nombre_documento'=>$this->nombre_documento,
			'numero_paginas'=>$this->numero_paginas,
			'ano'=>$this->ano,
			'mes'=>$this->mes,
			'num_indexacion'=>$this->num_indexacion,
			'url'=>$this->url,
			'medio_divulgacion'=>$this->medio_divulgacion,
			'autores'=>$this->autores,
			'instituciones'=>$this->instituciones,
			'descripcion'=>$this->descripcion,
			'id_autor'=>$this->id_autor,
			'ciudad'=>$this->ciudad,
			'pais'=>$this->pais,
            'archivo' => $archi
            		 );
      		  $this->insert($array);
		return 1;
	}

    public function getBibliograficos($id){
		$resultSet = $this->select(array('id_grupo_inv'=>$id));
		return $resultSet->toArray();
   	}

    public function getBibliograficost(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarBibliograficos($id) {
        $this->delete(array('id_bibliografico' => $id));
    }

    public function getBibliograficosById($id)
    {
        $resultSet = $this->select(array(
            'id_bibliografico' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateBibliograficos($id, $data = array())
    {   
        $array = array(
            'nombre_documento' => $data->nombre_documento,
            'numero_paginas' => $data->numero_paginas,
            'instituciones' => $data->instituciones,
            'ano' => $data->ano,
            'mes' => $data->mes,
            'num_indexacion' => $data->num_indexacion,
            'url' => $data->url,
            'medio_divulgacion' => $data->medio_divulgacion,
            'descripcion' => $data->descripcion,
            'autores' => $data->autores,
            'id_autor' => $data->id_autor,
            'pais' => $data->pais,
            'ciudad' => $data->ciudad
        );

        $this->update($array, array(
            'id_bibliografico' => $id
        ));
        return 1;
    }

    public function updatearchivoeBibliograficos($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id_bibliografico' => $id
        ));
        return 1;
    }
}
?>