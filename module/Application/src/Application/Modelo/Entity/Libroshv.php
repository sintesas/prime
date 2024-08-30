<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Libroshv extends TableGateway{

		//variables de la tabla
	private $titulo_libro;
	private $num_paginas;
	private $editorial;
	private $serie;
	private $edicion;
	private $isbn;
	private $medio_divulgacion;
	private $autores;
	private $id_autor;
	private $mes;
	private $ano;
	private $pais;
	private $ciudad;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('aps_hv_libros', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->titulo_libro=$datos["titulo_libro"];
		$this->num_paginas=$datos["num_paginas"];
		$this->editorial=$datos["editorial"];
		$this->serie=$datos["serie"];
		$this->edicion=$datos["edicion"];
		$this->isbn=$datos["isbn"];
		$this->medio_divulgacion=$datos["medio_divulgacion"];
		$this->autores=$datos["autores"];
		$this->id_autor=$datos["id_autor"];
		$this->mes=$datos["mes"];
		$this->ano=$datos["ano"];
		$this->pais=$datos["pais"];
		$this->ciudad=$datos["ciudad"];
	}

	public function addLibroshv($data=array(), $id, $archi){
		self::cargaAtributos($data);

        		$array=array(
			'id_usuario'=>$id,
			'titulo_libro'=>$this->titulo_libro,
			'num_paginas'=>$this->num_paginas,
			'editorial'=>$this->editorial,
			'serie'=>$this->serie,
			'edicion'=>$this->edicion,
			'isbn'=>$this->isbn,
			'medio_divulgacion'=>$this->medio_divulgacion,
			'autores'=>$this->autores,
			'id_autor'=>$this->id_autor,	
			'mes'=>$this->mes,
			'ano'=>$this->ano,
			'pais'=>$this->pais,
			'ciudad'=>$this->ciudad,
            'archivo' => $archi,
            'tipo_libro' => $data["tipo_libro"]
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getLibroshv($id){
		$resultSet = $this->select(array('id_usuario'=>$id));
		return $resultSet->toArray();
   	}

    	public function getLibroshvt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarLibroshv($id) {
        		$this->delete(array('id_gi_libro' => $id));
    	}
    	
    public function getLibroById($id)
    {
        $resultSet = $this->select(array(
            'id_gi_libro' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateLibros($id, $data = array())
    {   
        $array = array(
            'titulo_libro' => $data->titulo_libro,
            'num_paginas' => $data->num_paginas,
            'serie' => $data->serie,
            'editorial' => $data->editorial,
            'edicion' => $data->edicion,
            'isbn' => $data->isbn,
            'medio_divulgacion' => $data->medio_divulgacion,
            'id_autor' => $data->id_autor,
            'mes' => $data->mes,
            'ano' => $data->ano,
            'pais' => $data->pais,
            'ciudad' => $data->ciudad,
            'fecha' => $data->fecha,
            'tipo_libro' => $data["tipo_libro"]
        );

        $this->update($array, array(
            'id_gi_libro' => $id
        ));
        return 1;
    }

    public function updatearchivoLibroshv($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id_gi_libro' => $id
        ));
        return 1;
    }

	public function getReportesbyLibroshv($id) {
		$sql = "select u.id_usuario, concat(u.nombres, ' ', u.apellidos) nombre_completo, u.documento, l.id_gi_libro, l.titulo_libro, l.paginas, l.num_paginas, l.fecha, l.serie, l.editorial, l.edicion, l.isbn, l.lugar_publicacion, l.medio_divulgacion, l.autores, l.instituciones, l.capitulo_libro, l.mes, l.ano, l.pais, l.ciudad, (select concat(t.nombres, ' ', t.apellidos) from vw_usuarios_personal t where t.id_usuario = l.id_autor) autor, l.archivo, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = cast(l.tipo_libro as integer)) tipo_libro from aps_hv_libros l left join vw_usuarios_personal u on l.id_usuario = u.id_usuario where u.id_usuario is not null and l.id_usuario = " . $id . ";";
		$statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
	}
}
?>