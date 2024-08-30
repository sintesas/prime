<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Proyectosintusua extends TableGateway{
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('aps_hv_proyectosint', $adapter, $databaseSchema, $selectResultPrototype);
	}

	public function addProyectos($id, $id2){
		print_r($data);
		$array=array(
			'id_grupo_inv'=>$id,
			'id_proyecto'=>$id2
    	);
		$this->insert($array);
		return 1;
	}

    public function getProyectos($id){
		$resultSet = $this->select(array('id_grupo_inv'=>$id));
		return $resultSet->toArray();
   	}
    
    public function getProyectosi(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarProyecto($id) {
    	$this->delete(array('id' => $id));
    }

    public function getProyectointusuaById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateProyectointusua($id, $id2, $file_name)
    {   
        $array = array(
            'id_proyecto' => $id2, 
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id' => $id
        ));
    }

    public function updateArchivo($id, $file_name)
    {   
        $array = array(
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }

    public function getReportesbyProyectosintusua($id) {
		$sql = "select pi.id_proyecto, p.codigo_proy, p.nombre_proy, fn_get_nombres(p.id_investigador) investigador, p.duracion, fn_get_valores_flexibles(p.id_unidad_academica) unidad_academica, fn_get_valores_flexibles(p.id_dependencia_academica) dependencia_academica, p.resumen_ejecutivo, p.objetivo_general, fn_get_valores_flexibles(t.id_rol) rol, fn_get_nombres(t.id_integrante) integrante, pi.archivo from aps_hv_proyectosint pi inner join mgp_proyectos p on pi.id_proyecto = p.id_proyecto inner join mgp_tabla_equipos t on pi.id_proyecto = t.id_proyecto group by pi.id_proyecto, p.codigo_proy, p.nombre_proy, p.id_investigador, p.duracion, p.id_unidad_academica, p.id_dependencia_academica, p.resumen_ejecutivo, p.objetivo_general, t.id_rol, t.id_integrante, pi.archivo;";
		$statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
	}
}
?>