<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Librored extends TableGateway{

	//variables de la tabla
	private $titulo;
	private $paginas;
	private $ano;
	private $mes;
	private $pais;
	private $ciudad;
	private $serie;
	private $editorial;
	private $edicion;
	private $isbn;
	private $medio_divulgacion;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('mri_librored', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->titulo=$datos["titulo"];
		$this->paginas=$datos["paginas"];
		$this->ano=$datos["ano"];
		$this->mes=$datos["mes"];
		$this->pais=$datos["pais"];
		$this->ciudad=$datos["ciudad"];
		$this->serie=$datos["serie"];
		$this->editorial=$datos["editorial"];
		$this->edicion=$datos["edicion"];
		$this->isbn=$datos["isbn"];
		$this->medio_divulgacion=$datos["medio_divulgacion"];
	}

	public function addLibrored($data=array(), $id, $archi){
		self::cargaAtributos($data);
			$array=array(
				'id_red'=>$id,
				'titulo'=>$this->titulo,
				'paginas'=>$this->paginas,
				'ano'=>$this->ano,
				'mes'=>$this->mes,
				'pais'=>$this->pais,
				'ciudad'=>$this->ciudad,
				'serie'=>$this->serie,
				'editorial'=>$this->editorial,
				'edicion'=>$this->edicion,
				'isbn'=>$this->isbn,
				'medio_divulgacion'=>$this->medio_divulgacion,
				'id_autor'=>$data->id_autor,
            	'archivo' => $archi,
            	'tipo_libro' => $data["tipo_libro"]
            );
      		$this->insert($array);
		return 1;
	}

    public function getLibrored($id){
		$resultSet = $this->select(array('id_red'=>$id));
		return $resultSet->toArray();
   	}

    public function getLibrost(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarLibrored($id) {
        		$this->delete(array('id' => $id));
    }

    public function getLibroredById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateLibrosred($id, $data = array())
    {   
        $array = array(
            'titulo' => $data->titulo,
            'paginas' => $data->paginas,
            'serie' => $data->serie,
            'editorial' => $data->editorial,
            'edicion' => $data->edicion,
            'isbn' => $data->isbn,
            'medio_divulgacion' => $data->medio_divulgacion,
            'mes' => $data->mes,
            'ano' => $data->ano,
            'pais' => $data->pais,
            'ciudad' => $data->ciudad,
            'id_autor' => $data->id_autor,
            'tipo_libro' => $data["tipo_libro"]
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
    public function updatearchivoLibrosred($id, $archi)
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
