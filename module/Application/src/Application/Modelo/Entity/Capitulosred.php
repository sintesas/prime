<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Capitulosred extends TableGateway{

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
	private $titulo_capitulo;
	private $numero_capitulo;
	private $paginas_capitulo;
	private $pagina_inicio;
	private $pagina_fin;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('mri_capitulored', $adapter, $databaseSchema, $selectResultPrototype);
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
		$this->titulo_capitulo=$datos["titulo_capitulo"];
		$this->numero_capitulo=$datos["numero_capitulo"];
		$this->paginas_capitulo=$datos["paginas_capitulo"];
		$this->pagina_inicio=$datos["pagina_inicio"];
		$this->pagina_fin=$datos["pagina_fin"];
	}

	public function addCapitulored($data=array(), $id, $archi){
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
				'titulo_capitulo'=>$this->titulo_capitulo,
				'numero_capitulo'=>$this->numero_capitulo,
				'paginas_capitulo'=>$this->paginas_capitulo,
				'pagina_inicio'=>$this->pagina_inicio,
				'pagina_fin'=>$this->pagina_fin,
				'id_autor'=>$data->id_autor,
            	'archivo' => $archi
            );
      		$this->insert($array);
		return 1;
	}

    public function getCapitulored($id){
		$resultSet = $this->select(array('id_red'=>$id));
		return $resultSet->toArray();
   	}

    public function getCapitulot(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarCapitulored($id) {
    	$this->delete(array('id' => $id));
    }

    public function getCapituloredById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateCapitulored($id, $data = array())
    {   
        $array = array(
            'titulo' => $data->titulo,
            'paginas' => $data->paginas,
            'ano' => $data->ano,
            'mes' => $data->mes,
            'pais' => $data->pais,
            'ciudad' => $data->ciudad,
            'serie' => $data->serie,
            'editorial' => $data->editorial,
            'edicion' => $data->edicion,
            'isbn' => $data->isbn,
            'medio_divulgacion' => $data->medio_divulgacion,
            'titulo_capitulo' => $data->titulo_capitulo,
            'numero_capitulo' => $data->numero_capitulo,
            'paginas_capitulo' => $data->paginas_capitulo,
            'pagina_inicio' => $data->pagina_inicio,
            'pagina_fin' => $data->pagina_fin,
            'id_autor'=>$data->id_autor
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
    
    public function updatearchivoCapitulored($id, $archi)
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