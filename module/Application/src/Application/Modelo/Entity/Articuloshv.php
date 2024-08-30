<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Articuloshv extends TableGateway{

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
					return parent::__construct('aps_hv_articulos', 
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
		
		//$this->id_ciudad=$datos["id_ciudad"];
		$idciudad = explode("-",$datos["id_ciudad"]);
		$this->id_ciudad = $idciudad[0];

		//$this->id_departamento=$datos["id_departamento"];
		$iddepartamento = explode("-",$datos["id_departamento"]);
		$this->id_departamento = $iddepartamento[0];
		
		$this->pagina_inicio=$datos["pagina_inicio"];
		$this->pagina_fin=$datos["pagina_fin"];
		$this->fasciculo=$datos["fasciculo"];
		$this->ano=$datos["ano"];
		$this->mes=$datos["mes"];
		$this->categoria=$datos["categoria"];
	}

	public function addArticuloshv($data=array(), $id, $archi){
		self::cargaAtributos($data);
			$array=array(
			'id_usuario'=>$id,
			'nombre_revista'=>$this->nombre_revista,
			'nombre_articulo'=>$this->nombre_articulo,
			'id_pais'=>$this->id_pais,
			'fecha'=>$this->fecha,
			'issn'=>$this->issn,
			'paginas'=>$this->paginas,
			'id_ciudad'=>$this->id_ciudad,
			'volumen'=>$this->volumen,
			'serie'=>$this->serie,
			'id_autor'=>$this->id_autor,
			'coautor'=>$this->coautor,
			'mes'=>$this->mes,
			'categoria'=>$this->categoria,
            'archivo' => $archi
            		 );

			if($this->num_paginas!=""){
				$array["num_paginas"] = $this->num_paginas;
			}

			if($this->ano!=""){
				$array["ano"] = $this->ano;
			}

			if($this->pagina_inicio!=""){
				$array["pagina_inicio"] = $this->pagina_inicio;
			}
			
			if($this->pagina_fin!=""){
				$array["pagina_fin"] = $this->pagina_fin;
			}
			
			if($this->fasciculo!=""){
				$array["fasciculo"] = $this->fasciculo;
			}
      		  $this->insert($array);
		return 1;
	}

    	public function getArticuloshv($id){
		$resultSet = $this->select(array('id_usuario'=>$id));
		return $resultSet->toArray();
   	}

    	public function getArticuloshvt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarArticuloshv($id) {
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
            'volumen' => $data->volumen,
            'serie' => $data->serie,
            'id_autor' => $data->id_autor,
            'id_ciudad' => $data->id_ciudad,
            'mes' => $data->mes,
            'categoria' => $data->categoria
        );

		if($data->num_paginas!=""){
			$array["num_paginas"] = $data->num_paginas;
		}else{
			$array["num_paginas"] = null;
		}

		if($data->ano!=""){
			$array["ano"] = $data->ano;
		}else{
			$array["ano"] = null;
		}

		if($data->pagina_inicio!=""){
			$array["pagina_inicio"] = $data->pagina_inicio;
		}else{
			$array["pagina_inicio"] = null;
		}
		
		if($data->pagina_fin!=""){
			$array["pagina_fin"] = $data->pagina_fin;
		}else{
			$array["pagina_fin"] = null;
		}
		
		if($data->fasciculo!=""){
			$array["fasciculo"] = $data->fasciculo;
		}else{
			$array["fasciculo"] = null;
		}

        $this->update($array, array(
            'id_gi_articulo' => $id
        ));

        return 1;
    }

    public function updatearchivoArticuloshv($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id_gi_articulo' => $id
        ));
        return 1;
    }

	public function getReportesbyArticuloshv($id) {
		$sql = "select u.id_usuario, concat(u.nombres, ' ', u.apellidos) nombre_completo, u.documento, a.id_gi_articulo, a.nombre_revista, a.nombre_articulo, a.id_pais pais, a.fecha, a.issn, a.paginas, a.num_paginas, a.volumen, a.serie, fn_get_nombres(a.id_autor) autor, a.coautor, a.pagina_inicio, a.pagina_fin, a.fasciculo, fn_get_valores_flexibles(a.id_departamento) departamento, a.id_ciudad ciudad, fn_get_meses(a.mes) mes, a.ano, fn_get_valores_flexibles(cast(a.categoria as integer)) categoria, a.archivo from aps_hv_articulos a left join vw_usuarios_personal u on a.id_usuario = u.id_usuario where u.id_usuario is not null and a.id_usuario = " . $id . ";";
		$statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
	}
}
?>