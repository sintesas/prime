<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Aplicarm extends TableGateway
{
    
    // variables de la tabla
    private $num_codigo;

    private $id_facultad;

    private $id_proyecto;

    private $id_programa_curricular;

    private $creditos_aprobados;

    private $horas_monitoria;

    private $id_usuario;

    private $id_aplicar;

    private $facultad;

    private $programa;
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgc_aplicarm', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->num_codigo = $datos["num_codigo"];
        $this->id_facultad = $datos["id_facultad"];
        $this->id_programa_curricular = $datos["id_programa_curricular"];
        $this->creditos_aprobados = $datos["creditos_aprobados"];
        $this->horas_monitoria = $datos["horas_monitoria"];
        $this->id_usuario = $datos["id_usuario"];
        $this->id_aplicar = $datos["id_aplicar"];
        $this->id_proyecto = $datos["id_proyecto"];
        $this->facultad = $datos["facultad"];
        $this->programa = $datos["programa"];
    }

    public function addAplicarm($data = array(), $id, $dataMares = array(), $id_usua)
    {
        self::cargaAtributos($data);
        
        $array = array(
            'id_convocatoria' => $id,
            'id_facultad' => $dataMares["FACULTAD"],
            'id_proyecto' => $this->id_proyecto,
            'id_programa_curricular' => $dataMares["PROGRAMA"],
            'id_usuario' => $id_usua,
            'promedio_ponderado' => $dataMares["PROMEDIO"],
            'creditos_aprobados' => $dataMares["CRED_VALIDOS"],
            'cumplimiento_conjunto' => $dataMares["TOP_25"],
            'creditos_programa' => $dataMares["CRED_PROGRAMA"],
            'semestre' => $data["semestre"],
            'fecha' => date("Y-m-d"),
            'justificacion' => $data["justificacion"],
            'evaluacion_cuantitativa' => 0
        );
        $this->insert($array);
        $id = $this->getAdapter()
           ->getDriver()
           ->getLastGeneratedValue("mgc_aplicar_id_aplicar_seq");
        return $id;
    }

    public function filtroAplicarm($datos = array())
    {
        self::cargaAtributos($datos);
        if ($this->id_aplicar != '') {
            $id = $this->id_aplicar;
            $rowset = $this->select(function (Where $select) use($id) {
                $select->where(array(
                    'id_aplicar  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($this->id_aplicar == '') {
            $rowset = $this->select();
            return $rowset->toArray();
        }
    }

    public function filtroMonitoresexcel($facultad, $programa)
    {
        if ($facultad != '' && $programa == '') {
            
            $nom = '%' . $facultad . '%';
            $nom = strtoupper($nom);
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'upper(id_facultad) LIKE ?' => $nom
                ));
            });
            return $rowset->toArray();
        }
        if ($facultad == '' && $programa != '') {
            
            $nom = '%' . $programa . '%';
            $nom = strtoupper($nom);
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'upper(id_programa_curricular) LIKE ?' => $nom
                ));
            });
            return $rowset->toArray();
        }
        
        if ($facultad != '' && $programa != '') {
            
            $nom = '%' . $facultad . '%';
            $nom = strtoupper($nom);
            $nom2 = '%' . $programa . '%';
            $nom2 = strtoupper($nom);
            
            $rowset = $this->select(function (Where $select) use($nom, $nom2) {
                $select->where(array(
                    'upper(id_facultad) LIKE ?' => $nom,
                    'upper(id_programa_curricular) LIKE ?' => $nom2
                ));
            });
            return $rowset->toArray();
        }
        if ($facultad == '' && $programa == '') {
            $rowset = $this->select();
            return $rowset->toArray();
        }
    }

    public function filtroMonitores($datos = array())
    {
        self::cargaAtributos($datos);
        if ($this->facultad != '' && $this->programa == '') {
            
            $nom = '%' . $this->facultad . '%';
            $nom = strtoupper($nom);
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'upper(id_facultad) LIKE ?' => $nom
                ));
            });
            return $rowset->toArray();
        }
        if ($this->facultad == '' && $this->programa != '') {
            
            $nom = '%' . $this->programa . '%';
            $nom = strtoupper($nom);
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'upper(id_programa_curricular) LIKE ?' => $nom
                ));
            });
            return $rowset->toArray();
        }
        
        if ($this->facultad != '' && $this->programa != '') {
            
            $nom = '%' . $this->facultad . '%';
            $nom = strtoupper($nom);
            $nom2 = '%' . $this->programa . '%';
            $nom2 = strtoupper($nom);
            
            $rowset = $this->select(function (Where $select) use($nom, $nom2) {
                $select->where(array(
                    'upper(id_facultad) LIKE ?' => $nom,
                    'upper(id_programa_curricular) LIKE ?' => $nom2
                ));
            });
            return $rowset->toArray();
        }
        if ($this->facultad == '' && $this->programa == '') {
            $rowset = $this->select();
            return $rowset->toArray();
        }
    }

    public function getAplicarm($id)
    {
        $resultSet = $this->select(array(
            'id_aplicar' => $id
        ));
        return $resultSet->toArray();
    }

    public function getAplicarusuariom($id)
    {
        $resultSet = $this->select(array(
            'id_usuario' => $id
        ));
        return $resultSet->toArray();
    }

    public function getID($id)
    {
        $filter = new StringTrim();
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'id_convocatoria'
            ));
            $select->where(array(
                'id_aplicar = ?' => $id
            ));
        });
        $row = $resultSet->current();
        
        return $row;
    }

    public function getAplicarmconv($id)
    {
        $rowset = $this->select(function (Where $select) use($id) {
            $select->where(array(
                'id_convocatoria' => $id
            ));
            $select->order(array(
                'id_proyecto DESC'
            ));
        });
        return $rowset->toArray();
    }

    public function getAplicar($id)
    {
        $resultSet = $this->select(array(
            'id_aplicar' => $id
        ));
        return $resultSet->toArray();
    }

    public function getAplicarmid($id)
    {
        $id = (int) $id;
        $rowset = $this->select(array(
            'id_aplicar' => $id
        ));
        $row = $rowset->current();
        if (! $row) {
            throw new \Exception("no hay id asociado");
        }
        
        return $row;
    }
    
    public function getAplicarmPDF($datos = array())
    {
        $array = array();
        if($datos["facultad"]!=""){
            $array["id_facultad"] = $datos["facultad"];
        }

        if($datos["programa"]!=""){
            $array["id_programa_curricular"] = $datos["programa"];
        }

        $array["estado_aprobacionm"] = "Aprobado";

        $resultSet = $this->select($array);
        return $resultSet->toArray();
    }

    public function getAplicarmExcel($facultad,$programa, $id_convocatoria, $todos)
    {
        $array = array();
        if($facultad!=0){
            $array["id_facultad"] = $facultad;
        }

        if($programa!=0){
            $array["id_programa_curricular"] = $programa;
        }

        if($id_convocatoria!=0){
            $array["id_convocatoria"] = $id_convocatoria;
        }

        if($todos!="1"){
            $array["estado_aprobacionm"] =  "Aprobado";
        }

        $resultSet = $this->select($array);
        return $resultSet->toArray();
    }

    public function getAplicarh()
    {
        $resultSet = $this->select(function ($select) {
            $select->order(array(
                'evaluacion_cuantitativa DESC'
            ));
        });
        // $sql = "SELECT * FROM vw_consulta_proceso_monitoria";
        // $resultSet = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return $resultSet->toArray();
    }

    public function getAplicarhMonitores()
    {
        $sql = "SELECT mgc.id_proyecto, mgc.id_usuario, mgc.estado_seleccionado, mgc.id_convocatoria, mgc.estado_aprobacionm, mgc.obervaciones_aprobacion, (SELECT CONCAT(us.primer_nombre,' ',us.segundo_nombre,' ',us.primer_apellido,' ',us.segundo_apellido) FROM aps_usuarios AS us WHERE us.id_usuario=mgc.id_usuario) AS nombre, id_aplicar FROM mgc_aplicarm AS mgc order by mgc.evaluacion_cuantitativa DESC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }

    public function getAplicarTodos()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function eliminarAplicar($id)
    {
        $this->delete(array(
            'id_aplicar' => $id
        ));
    }

    public function updateAplicar($id, $data = array())
    {
        $array = array();
        if($data->justificacion != null){
            $array["justificacion"] = $data->justificacion;
        }else{
             $array["justificacion"] = null;
        }

        if($data->semestre != null){
            $array["semestre"] = $data->semestre;
        }else{
             $array["semestre"] = null;
        }
         
        $this->update($array, array(
            'id_aplicar' => $id
        ));

        return 1;
    }

    public function updateAplicarCuantitativa($id, $data)
    {
        $array = array();
        $array["evaluacion_cuantitativa"] = $data;
        
         
        $this->update($array, array(
            'id_aplicar' => $id
        ));

        return 1;
    }

    public function updateAplicarEstado($id, $estado)
    {   
        $array = array();
        if($estado != null){
            $array["estado_seleccionado"] = $estado;
        }else{
             $array["estado_seleccionado"] = null;
        }
        $this->update($array, array(
            'id_aplicar' => $id
        ));
        return 1;
    }

    public function updateAplicarEntrevista($id, $data = array())
    {
        $array = array();
        $array["fecha_entrevista"] = date("Y-m-d");
        
        if($data->obervaciones_entrevista != null){
            $array["obervaciones_entrevista"] = $data->obervaciones_entrevista;
        }else{
             $array["obervaciones_entrevista"] = null;
        }
        
        $this->update($array, array(
            'id_aplicar' => $id
        ));
        return 1;
    }

    public function updateAplicarAprobacion($id, $data = array())
    {
        $array = array();
        
        $array["fecha_verificacion"] = date("Y-m-d");
        if($data->estado_aprobacion != null){
            $array["estado_aprobacion"] = $data->estado_aprobacion;
        }

        if($data->obervaciones_aprobacion != null){
            $array["obervaciones_aprobacion"] = $data->obervaciones_aprobacion;
        }else{
             $array["obervaciones_aprobacion"] = null;
        }
        
        $this->update($array, array(
            'id_aplicar' => $id
        ));
        return 1;
    }

    public function updateAplicarAprobar($id, $estado)
    {
        $array = array();
        if($estado != null){
            $array["estado_aprobacionm"] = $estado;
        }else{
             $array["estado_aprobacionm"] = null;
        }
        $this->update($array, array(
            'id_aplicar' => $id
        ));

        return 1;
    }

    public function updateAplicarProy($data = array(), $id)
    {
        $array = array(
            'id_proyecto' => $data->id_proyecto
        );
        
        $this->update($array, array(
            'id_aplicar' => $id
        ));

        return 1;
    }

    public function delAplicarProy($id)
    {
        $array = array(
            'id_proyecto' => null,
            'fecha_entrevista' => null,
            'obervaciones_entrevista' => null           
        );
        
        $this->update($array, array(
            'id_aplicar' => $id
        ));

        return 1;
    }

    public function getAplicarmFacultad(){
        $resultSet = $this->select(function ($select) 
        {
            $select->columns(array(
                'id_facultad'
            ));
            $select->where(array(
                'estado_aprobacionm' => "Aprobado"
            ));
            $select->group(array('id_facultad')); 
        });
        return $resultSet->toArray();
    }

    public function getAplicarmPrograma(){
        $resultSet = $this->select(function ($select) 
        {
            $select->columns(array(
                'id_programa_curricular'
            ));
            $select->where(array(
                'estado_aprobacionm' => "Aprobado"
            ));
            $select->group(array('id_programa_curricular')); 
        });
        return $resultSet->toArray();
    }

    public function getAplicacionesByConvocatoria()
    {
        $sql = "(SELECT id_convocatoria, count(*) as cantidad FROM mgc_aplicar group by id_convocatoria) UNION ALL (SELECT id_convocatoria, count(*) as cantidad FROM mgc_aplicarm group by id_convocatoria) ORDER BY id_convocatoria ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }
}
?>