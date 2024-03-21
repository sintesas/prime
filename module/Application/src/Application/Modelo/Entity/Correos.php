<?php

namespace Application\Modelo\Entity;
use Zend\Db\Sql\Select as Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
class Correos extends TableGateway{
	private $NOMBRES;
	private $EMAIL;
    public function __construct(Adapter $adapter = null, 
							   $databaseSchema =null,
							   ResultSet $selectResultPrototype =null){
      return parent::__construct('APS_LISTA_EMAIL', $adapter, $databaseSchema,$selectResultPrototype);
   }
   
	private function cargaAtributos($datos=array())
	{
		$this->NOMBRE=$datos["nombre"];
		$this->EMAIL=$datos["email"];
	}
	
	
    public function getCorreos(){
      $resultSet = $this->select();
	  return $resultSet->toArray();
    }
	
	public function addCorreos($correo)
	{

		//$this->insert($data);
		$c=0;
		$resultado=1;
		$filter = new StringTrim();

				$array=array
				(
					'NOMBRE'=>'lista',
					'EMAILS'=>$filter->filter($correo),
				);
				$this->insert($array);
		
		return 1;
	}
	
    public function eliminarCorreos($email)
    {
if($email!=''){
        $this->delete(array('ID_LISTA_EMAIL' => $email));
}else{
  $this->delete(array('NOMBRE' => 'lista'));
}

      return 1;
    }
   
}

?>