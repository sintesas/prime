<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Articulos extends TableGateway{

	//variables de la tabla
	private $nombre_revista;
	private $nombre_articulo;
	private $id_pais;
	private $fecha;
	private $issn;
	private $paginas;
	private $id_ciudad;
	private $id_departamento;
	private $num_paginas;
	private $volumen;
	private $serie;
	private $id_autor;
	private $coautor;
	private $pagina_inicio;
	private $pagina_fin;
	private $fasciculo;
	private $ano;
	private $mes;
	private $categoria;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgi_gi_articulos', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->nombre_revista=$datos["nombre_revista"];
		$this->nombre_articulo=$datos["nombre_articulo"];
		$this->id_pais=$datos["id_pais"];
		$this->fecha=$datos["fecha"];
		$this->issn=$datos["issn"];
		$this->paginas=$datos["paginas"];
		$this->num_paginas=$datos["num_paginas"];
		$this->volumen=$datos["volumen"];
		$this->serie=$datos["serie"];
		$this->id_autor=$datos["id_autor"];
		$this->coautor=$datos["coautor"];
		
		$this->id_ciudad=$datos["id_ciudad"];
		//$idciudad = $datos["id_ciudad"];
		//$this->id_ciudad = $idciudad[0];
	
		$this->pagina_inicio=$datos["pagina_inicio"];
		$this->pagina_fin=$datos["pagina_fin"];
		$this->fasciculo=$datos["fasciculo"];
		$this->ano=$datos["ano"];
		$this->mes=$datos["mes"];
		$this->categoria=$datos["categoria"];
	}

	public function addArticulos($data=array(), $id, $archi){
		self::cargaAtributos($data);

		$arrayInt = array();
		$arrayInt['id_grupo_inv'] = $id;
		$arrayInt['archivo'] = $archi;

		if($this->nombre_revista != ""){
			$arrayInt['nombre_revista'] = $this->nombre_revista;
		}

		if($this->nombre_articulo != ""){
			$arrayInt['nombre_articulo'] = $this->nombre_articulo;
		}
		
		if($this->id_pais != ""){
			$arrayInt['id_pais'] = $this->id_pais;
		}
		
		if($this->fecha != ""){
			$arrayInt['fecha'] = $this->fecha;
		}
		
		if($this->issn != ""){
			$arrayInt['issn'] = $this->issn;
		}
		
		if($this->paginas != ""){
			$arrayInt['paginas'] = $this->paginas;
		}

		if($this->num_paginas != ""){
			$arrayInt['num_paginas'] = $this->num_paginas;
		}
		
		if($this->id_ciudad != ""){
			$arrayInt['id_ciudad'] = $this->id_ciudad;
		}
		
		if($this->volumen != ""){
			$arrayInt['volumen'] = $this->volumen;
		}
		
		if($this->serie != ""){
			$arrayInt['serie'] = $this->serie;
		}

		if($this->id_autor != ""){
			$arrayInt['id_autor'] = $this->id_autor;
		}
		
		if($this->coautor != ""){
			$arrayInt['coautor'] = $this->coautor;
		}
		
		if($this->pagina_inicio != ""){
			$arrayInt['pagina_inicio'] = $this->pagina_inicio;
		}
		
		if($this->pagina_fin != ""){
			$arrayInt['pagina_fin'] = $this->pagina_fin;
		}
			
		if($this->fasciculo != ""){
			$arrayInt['fasciculo'] = $this->fasciculo;
		}
		
		if($this->ano != ""){
			$arrayInt['ano'] = $this->ano;
		}
		
		if($this->mes != ""){
			$arrayInt['mes'] = $this->mes;
		}

		if($this->categoria != ""){
			$arrayInt['categoria'] = $this->categoria;
		}
		$this->insert($arrayInt);
		return 1;
	}

    	public function getArticulos($id){
		$resultSet = $this->select(array('id_grupo_inv'=>$id));
		return $resultSet->toArray();
   	}

    public function getArticulosi(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

    public function getArticuloid($id){
		$resultSet = $this->select(array('id_gi_articulo'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarArticulos($id) {
        $this->delete(array('id_gi_articulo' => $id));
    }

    public function getArticuloById($id)
    {
        $resultSet = $this->select(array(
            'id_gi_articulo' => $id
        ));
        return $resultSet->toArray();
    }

    public function updatArticulos($id, $data = array())
    {   
        $array = array(
            'nombre_revista' => $data->nombre_revista,
            'nombre_articulo' => $data->nombre_articulo,
            'id_pais' => $data->id_pais,
            'issn' => $data->issn,
            'paginas' => $data->paginas,
            'num_paginas' => $data->num_paginas,
            'volumen' => $data->volumen,
            'serie' => $data->serie,
            'id_autor' => $data->id_autor,
            'pagina_inicio' => $data->pagina_inicio,
            'pagina_fin' => $data->pagina_fin,
            'fasciculo' => $data->fasciculo,
            'id_ciudad' => $data->id_ciudad,
            'ano' => $data->ano,
            'mes' => $data->mes,
            'categoria' => $data->categoria
        );

        $this->update($array, array(
            'id_gi_articulo' => $id
        ));
        return 1;
    }

    public function updatearchivoArticulos($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id_gi_articulo' => $id
        ));
        return 1;
    }

}
?>
