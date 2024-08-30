<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Capitulosusuario extends TableGateway{

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
		return parent::__construct('aps_hv_capitulosusuario', $adapter, $databaseSchema, $selectResultPrototype);
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

	public function addCapitulousuario($data=array(), $id, $archi){
		self::cargaAtributos($data);
			$array=array(
				'id_usuario'=>$id,
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

    public function getCapitulousuario($id){
		$resultSet = $this->select(array('id_usuario'=>$id));
		return $resultSet->toArray();
   	}

    public function getCapitulot(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarCapitulousuario($id) {
        		$this->delete(array('id' => $id));
    }

     public function getCapituloById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateCapitulo($id, $data = array())
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

    public function updatearchivoCapitulousuario($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }

	public function getReportesbyCapitulosusu($id) {
		$sql = "select u.id_usuario, fn_get_nombres(u.id_usuario) nombre_completo, u.documento, c.id, c.titulo, c.paginas, c.ano, fn_get_meses(c.mes) mes, c.pais, c.ciudad, c.serie, c.editorial, c.edicion, c.isbn, c.lugar_publicacion, c.medio_divulgacion, c.titulo_capitulo, c.numero_capitulo, c.paginas_capitulo, c.pagina_inicio, c.pagina_fin, fn_get_nombres(c.id_autor) autor, c.archivo from aps_hv_capitulosusuario c left join aps_usuarios u on c.id_usuario = u.id_usuario where u.id_usuario is not null and c.id_usuario = " . $id . ";";
		$statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
	}
}


?>