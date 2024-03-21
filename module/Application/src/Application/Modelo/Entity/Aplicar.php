<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;
use Zend\Filter\AbstractUnicode;

class Aplicar extends TableGateway
{
    
    // variables de la tabla
    private $nombre_proy;

    private $codigo_proy;

    private $id_investigador;

    private $id_convocatoria;

    private $duracion;

    private $id_categoria;

    private $id_programa_inv;

    private $id_campo;

    private $id_linea_inv;

    private $id_unidad_academica;

    private $id_area_tematica;

    private $recursos_funcion;

    private $recursos_inversion;

    private $id_dependencia_academica;

    private $id_programa_academico;

    private $total_financia;

    private $total_proy;

    private $resumen_ejecutivo;

    private $objetivo_general;

    private $periodo;

    private $id_aplicar;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgc_aplicar', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->nombre_proy = $datos["nombre_proy"];
        $this->codigo_proy = $datos["codigo_proy"];
        $this->id_investigador = $datos["id_investigador"];
        $this->duracion = $datos["duracion"];

        $idCategoria = explode("-",$datos["id_categoria"]);
        $this->id_categoria = $idCategoria[0];

        $this->id_convocatoria = $datos["id_convocatoria"];
        $this->id_programa_inv = $datos["id_programa_inv"];
        $this->id_campo = $datos["id_campo"];
        // explode de idLineaInvestigacion
        
        $idLineaInv = explode("-",$datos["id_linea_inv"]);
        $this->id_linea_inv = $idLineaInv[0];
        
        $this->id_unidad_academica = $datos["id_unidad_academica"];
        $this->id_area_tematica = $datos["id_area_tematica"];
        $this->recursos_funcion = $datos["recursos_funcion"];
        // explode de idDepedenciaAcademica
        
        $idDepedenciaAcademica = explode("-",$datos["id_dependencia_academica"]);
        $this->id_dependencia_academica = $idDepedenciaAcademica[0];
        // explode de idProgramaAcademico
        
        $idProgramaAcademico = explode("-",$datos["id_programa_academico"]);
        $this->id_programa_academico = $idProgramaAcademico[0];
        
        $this->recursos_inversion = $datos["recursos_inversion"];
        $this->total_financia = $datos["total_financia"];
        $this->total_proy = $datos["total_proy"];
        $this->resumen_ejecutivo = $datos["resumen_ejecutivo"];
        $this->objetivo_general = $datos["objetivo_general"];
        $this->periodo = $datos["periodo"];
        $this->id_aplicar = $datos["id_aplicar"];
    }

    public function addAplicar($data = array(), $id)
    {
        self::cargaAtributos($data);
        $filter = new StringTrim();
        $array = array(
            'id_convocatoria' => $id,
            'nombre_proy' => $this->nombre_proy,
            'id_investigador' => $this->id_investigador,
            'duracion' => $this->duracion,
            'id_programa_inv' => $this->id_programa_inv,
            'id_campo' => $this->id_campo,
            'id_linea_inv' => $this->id_linea_inv,
            'id_unidad_academica' => $this->id_unidad_academica,
            'id_dependencia_academica' => $this->id_dependencia_academica,
            'id_programa_academico' => $this->id_programa_academico,
            'id_area_tematica' => $this->id_area_tematica,
            'recursos_funcion' => $this->recursos_funcion,
            'recursos_inversion' => $this->recursos_inversion,
            'total_financia' => $this->total_financia,
            'periodo' => $this->periodo,
            'total_proy' => $this->total_proy,
            'resumen_ejecutivo' => $filter->filter($this->resumen_ejecutivo),
            'objetivo_general' => $filter->filter($this->objetivo_general),
            'fecha_crea' => now
        );
        $this->insert($array);
        
        $id = $this->getAdapter()
            ->getDriver()
            ->getLastGeneratedValue("mgc_aplicar_id_aplicar_seq");
        return $id;
    }

    public function updateAplicarestado($id)
    {
        $array = array(
            'id_estado' => '1'
        )
        ;
        
        $this->update($array, array(
            'id_aplicar' => $id
        ));
    }

    public function getAplicarestado($id)
    {
        $resultSet = $this->select(array(
            'id_convocatoria' => $id,
            'id_estado' => 1
        ));
        return $resultSet->toArray();
    }

    public function updateAplicar($id, $data = array())
    {
        
        self::cargaAtributos($data);
        
        $arrayid_investigador = array();
        if ($this->id_investigador != '0') {
            $arrayid_investigador = array(
                'id_investigador' => $this->id_investigador
            );
        }
        
        $arrayid_categoria = array();
        if ($this->id_categoria != '0') {
            $arrayid_categoria = array(
                'id_categoria' => $this->id_categoria
                
            );
        }
        
        $arrayid_programa = array();
        if ($this->id_programa_inv != '0') {
            $aux = explode("-",$this->id_programa_inv,2);
            $this->id_programa_inv = $aux[0];

            $arrayid_programa = array(
                'id_programa_academico' => $this->id_programa_inv
            );
        }
        
        $arrayid_campo = array();
        if ($this->id_campo != '0') {
            
            $arrayid_campo = array(
                'id_campo' => $this->id_campo
            );
        }
        
        $arrayid_linea = array();
        if ($this->id_linea_inv != '0') {
            $arrayid_linea = array(
                'id_linea_inv' => $this->id_linea_inv
            );
        }

        $arrayid_dependencia = array();
        if ($this->id_dependencia_academica != '0') {
            $aux = explode("-",$this->id_dependencia_academica,2);
            $this->id_dependencia_academica = $aux[0];

            $arrayid_dependencia = array(
                'id_dependencia_academica' => $this->id_dependencia_academica
            );
        }
        
        $arrayid_unidad = array();
        if ($this->id_unidad_academica != '0') {
            $arrayid_unidad = array(
                'id_unidad_academica' => $this->id_unidad_academica
            );
        }

        $arrayid_area = array();
        if ($this->id_area_tematica != '0') {
            $arrayid_area = array(
                'id_area_tematica' => $this->id_area_tematica
            );
        }
        
        $arrayperiodo = array();
        if ($this->periodo != '') {
            $arrayperiodo = array(
                'periodo' => $this->periodo
            );
        }
        
        $objetivo_general = array();
        if ($this->objetivo_general != '') {
            $objetivo_general = array(
                'objetivo_general' => $this->objetivo_general
            );
        }
        
        $resumen_ejecutivo = array();
        if ($this->resumen_ejecutivo != '') {
            $resumen_ejecutivo = array(
                'resumen_ejecutivo' => $this->resumen_ejecutivo
            );
        }
        $array = array(
            'nombre_proy' => $this->nombre_proy,
            'duracion' => $this->duracion,
            'recursos_funcion' => $this->recursos_funcion,
            'recursos_inversion' => $this->recursos_inversion,
            'total_financia' => $this->total_financia,
            'total_proy' => $this->total_proy
        );
        
        $array = $array + $arrayid_investigador + $objetivo_general + $resumen_ejecutivo + $arrayid_categoria + $arrayid_programa + $arrayid_dependencia + $arrayid_campo + $arrayid_linea + $arrayid_unidad + $arrayid_area + $arrayperiodo;
        
        $this->update($array, array(
            'id_aplicar' => $id
        ));
        
        $s = 'ID=' . $id . ',nombre_proy' . '=' . $this->nombre_proy . ',' . 'objetivo_general' . '=' . $this->objetivo_general . ',' . 'resumen_ejecutivo' . '=' . $this->resumen_ejecutivo . ',' . 'recursos_funcion' . '=' . $this->recursos_funcion . ',' . 'recursos_inversion' . '=' . $this->recursos_inversion . ',' . 'total_financia' . '=' . $this->total_financia . ',' . 'total_proy' . '=' . $this->total_proy;

        //echo "R:".$this->recursos_inversion."Total:".$this->total_proy;
        
        
        
        return $s;
    }

    public function filtroAplicar($datos = array())
    {
        self::cargaAtributos($datos);
        
        $id = $this->id_aplicar;
        $rowset = $this->select(function (Where $select) use($id)
        {
            $select->where(array(
                'id_aplicar  =?' => $id
            ));
        });
        return $rowset->toArray();
    }

    public function getPropuestasc($id, $unidad, $dependencia, $programa, $categoria)
    {
        $rowset = $this->select(function (Where $select) use($unidad, $dependencia, $programa, $categoria, $id) {
            if($unidad!=0){
                $tWhere['id_unidad_academica = ?'] = $unidad;
            }
            if ($dependencia!=0){
                $tWhere['id_dependencia_academica = ?'] = $dependencia;
            }
            if ($programa!=0){
                $tWhere["id_programa_academico = ?"] = $programa;
            }
            if($categoria!=0){
                $tWhere['id_categoria = ?'] = $categoria;
            }
            $tWhere['id_convocatoria  = ?']=$id;
            $select->where($tWhere);
        });
        return $rowset->toArray();
    }

    public function filtroAplicarp($datos = array(), $id, $rol)
    {
        self::cargaAtributos($datos);
        
        if($rol == 1){
            //-------------------------------------------------------------------------77
            // Id Propuesta
            
            
            if ($this->id_aplicar != '' && $this->nombre_proy == '' && $this->id_convocatoria == '') {
                $ida = $this->id_aplicar;
                $resultSet = $this->select(array(
                    'id_aplicar' => $ida
                ));
                return $resultSet->toArray();
            }
            
            //-------------------------------------------------------------------------77
            // Id Propuesta
            
            if ($this->id_aplicar == '' && $this->nombre_proy != '' && $this->id_convocatoria == '') {
                $nom = '%' . $this->nombre_proy . '%';
                $nom = strtoupper($nom);
            
                $rowset = $this->select(function (Where $select) use($nom)
                {
                    $select->where(array(
                        'upper(nombre_proy)  LIKE ?' => $nom
                    ));
                });
                return $rowset->toArray();
            }
            
            //-------------------------------------------------------------------------77
            // Id Convocatoria
            
            if ($this->id_aplicar == '' && $this->nombre_proy == '' && $this->id_convocatoria != '') {
                $ida = $this->id_convocatoria;
                $resultSet = $this->select(array(
                    'id_convocatoria' => $ida
                ));
                return $resultSet->toArray();
            }
            
            if ($this->id_aplicar == '' && $this->nombre_proy == '' && $this->id_convocatoria == '') {
                $resultSet = $this->select();
                return $resultSet->toArray();
            }
            
        }else{
            //-------------------------------------------------------------------------77
            // Id Propuesta
            
            if ($this->id_aplicar != null && $this->nombre_proy == null && $this->id_convocatoria == null) {
                $ida = $this->id_aplicar;
                $resultSet = $this->select(array(
                    'id_aplicar' => $ida,
                    'id_investigador' => $id
                ));
                return $resultSet->toArray();
            }
            
            //-------------------------------------------------------------------------77
            // Nombre Propuesta
            
            if ($this->id_aplicar == '' && $this->nombre_proy != '' && $this->id_convocatoria == '') {
                $nom = '%' . $this->nombre_proy . '%';
                $nom = strtoupper($nom);
                
                $rowset = $this->select(function (Where $select) use($nom)
                {
                    $select->where(array(
                        'upper(nombre_proy)  LIKE ?' => $nom,
                        'id_investigador' => $id
                    ));
                });
                return $rowset->toArray();
            }
            
            //-------------------------------------------------------------------------77
            // Id Convocatoria
            
            if ($this->id_aplicar == '' && $this->nombre_proy == '' && $this->id_convocatoria != '') {
                $ida = $this->id_convocatoria;
                $resultSet = $this->select(array(
                    'id_convocatoria' => $ida ,
                    'id_investigador' => $id
                ));
                return $resultSet->toArray();
            }      
            
            if ($this->id_aplicar == '' && $this->nombre_proy == null && $this->id_convocatoria == '') {
                $resultSet = $this->select(array(
                    'id_investigador' => $id
                ));
                return $resultSet->toArray();
            }
        }
    }

    public function filtroAplicarpr($datos = array(), $id)
    {
        self::cargaAtributos($datos);
        
        if ($this->id_aplicar != null && $this->nombre_proy == null) {
            $ida = $this->id_aplicar;
            $resultSet = $this->select(array(
                'id_aplicar' => $ida,
                'id_convocatoria' => $id
            ));
            return $resultSet->toArray();
        }
        if ($this->id_aplicar == null && $this->nombre_proy != null) {
            $nom = '%' . $this->nombre_proy . '%';
            $nom = strtoupper($nom);
            
            $rowset = $this->select(function (Where $select) use($nom, $id)
            {
                $select->where(array(
                    'upper(nombre_proy)  LIKE ?' => $nom,
                    'id_convocatoria' => $id
                ));
            });
            return $rowset->toArray();
        }

        if ($this->id_aplicar == null && $this->codigo_proy == null) {
            
            $resultSet = $this->select(array(
                'id_convocatoria' => $id
            ));
            return $resultSet->toArray();
        }

        if ($this->id_aplicar != null && $this->nombre_proy != null) {
            $nom = '%' . $this->nombre_proy . '%';
            $nom = strtoupper($nom);
            $ida = $this->id_aplicar;

            $rowset = $this->select(function (Where $select) use($nom, $ida, $id)
            {
                $select->where(array(
                    'upper(nombre_proy)  LIKE ?' => $nom,
                    'id_aplicar' => $ida, 
                    'id_convocatoria' => $id
                ));
            });
            return $rowset->toArray();
        }
    }

    public function getAplicar($id)
    {
        $resultSet = $this->select(array(
            'id_aplicar' => $id
        ));
        return $resultSet->toArray();
    }

    public function getAplicarconv($id)
    {
        $resultSet = $this->select(array(
            'id_convocatoria' => $id
        ));
        return $resultSet->toArray();
    }

    public function getAplicarid($id)
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
    public function filtroreporteConvocatorias($datos = array()){
        $id_dependencia=$datos["id_dependencia_academica"];
        $id_unidad=$datos["id_unidad_academica"];
        $id_programa=$datos["id_programa_academico"];
        $id_categoria=$datos["id_categoria"];
        if ($id_dependencia != null && $id_unidad == null && $id_programa == null && $id_categoria == null) {
            $rowset = $this->select(function (Where $select) use($id_dependencia) {
                $select->where(array(
                    'id_dependencia_academica = ?' => $id_dependencia
                ));
            });
            return $rowset->toArray();
        }elseif($id_dependencia == null && $id_unidad != null && $id_programa == null && $id_categoria == null){
             $rowset = $this->select(function (Where $select) use($id_unidad) {
                $select->where(array(
                    'id_unidad_academica = ?' => $id_unidad
                ));
            });
            return $rowset->toArray();
        }elseif($id_dependencia == null && $id_unidad == null && $id_programa != null && $id_categoria == null){
             $rowset = $this->select(function (Where $select) use($id_programa) {
                $select->where(array(
                    'id_programa_academico = ?' => $id_programa
                ));
            });
            return $rowset->toArray();
        }elseif($id_dependencia == null && $id_unidad == null && $id_programa == null && $id_categoria != null){
             $rowset = $this->select(function (Where $select) use($id_categoria) {
                $select->where(array(
                    'id_categoria = ?' => $id_categoria
                ));
            });
            return $rowset->toArray();
        }elseif($id_dependencia == null && $id_unidad == null && $id_programa == null && $id_categoria == null){
            $rowset = $this->select();
            return $rowset->toArray();
        }elseif($id_dependencia != null && $id_unidad != null && $id_programa != null && $id_categoria != null){
            $rowset = $this->select(function (Where $select) use($id_programa, $id_categoria, $id_dependencia, $id_unidad) {
                $select->where(array(
                    'id_programa_academico = ?' => $id_programa,
                    'id_categoria = ?' => $id_categoria,
                    'id_dependencia_academica = ?' => $id_dependencia,
                    'id_unidad_academica = ?' => $id_unidad
                ));
            });
        }



    }

    public function reporteProyectos2($id_convocatoria, $codigo, $resumen, $titulo_conv, $inves, $evaluador, $obj, $apro, $sol, $puntaje, $elider, $categoria, $programa, $campo, $linea, $area)
    {
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        // 2
        if ($codigo != 0 && $resumen == 0 && $titulo_conv != 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'nombre_proy'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $inves != 0 && $evaluador == 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'id_investigador'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $inves == 0 && $evaluador != 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'id_investigador'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $inves == 0 && $evaluador == 0 && $obj != 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'objetivo_general'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $apro != 0 && $sol != 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'id_unidad_academica',
                    'id_dependencia_academica'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria != 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'id_categoria'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa != 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'id_programa_inv'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo != 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'id_campo'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea != 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'id_linea_inv'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area != 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'id_area_tematica'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves != 0 && $evaluador == 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves != 0 && $evaluador != 0 && $obj == 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador',
                    'id_investigador'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves != 0 && $evaluador != 0 && $obj != 0 && $apro == 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador',
                    'id_investigador',
                    'objetivo_general'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves != 0 && $evaluador != 0 && $obj != 0 && $apro != 0 && $sol == 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador',
                    'id_investigador',
                    'objetivo_general',
                    'id_unidad_academica'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves != 0 && $evaluador != 0 && $obj != 0 && $apro != 0 && $sol != 0 && $puntaje == 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador',
                    'id_investigador',
                    'objetivo_general',
                    'id_unidad_academica',
                    'id_dependencia_academica'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves != 0 && $evaluador != 0 && $obj != 0 && $apro != 0 && $sol != 0 && $puntaje != 0 && $elider == 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador',
                    'id_investigador',
                    'objetivo_general',
                    'id_unidad_academica',
                    'id_dependencia_academica',
                    'id_investigador'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves != 0 && $evaluador != 0 && $obj != 0 && $apro != 0 && $sol != 0 && $puntaje != 0 && $elider != 0 && $categoria == 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador',
                    'id_investigador',
                    'objetivo_general',
                    'id_unidad_academica',
                    'id_dependencia_academica',
                    'id_investigador',
                    'id_investigador'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves != 0 && $evaluador != 0 && $obj != 0 && $apro != 0 && $sol != 0 && $puntaje != 0 && $elider != 0 && $categoria != 0 && $programa == 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador',
                    'id_investigador',
                    'objetivo_general',
                    'id_unidad_academica',
                    'id_dependencia_academica',
                    'id_investigador',
                    'id_investigador',
                    'id_categoria'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves != 0 && $evaluador != 0 && $obj != 0 && $apro != 0 && $sol != 0 && $puntaje != 0 && $elider != 0 && $categoria != 0 && $programa != 0 && $campo == 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador',
                    'id_investigador',
                    'objetivo_general',
                    'id_unidad_academica',
                    'id_dependencia_academica',
                    'id_investigador',
                    'id_investigador',
                    'id_categoria',
                    'id_programa_inv'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves != 0 && $evaluador != 0 && $obj != 0 && $apro != 0 && $sol != 0 && $puntaje != 0 && $elider != 0 && $categoria != 0 && $programa != 0 && $campo != 0 && $linea == 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador',
                    'id_investigador',
                    'objetivo_general',
                    'id_unidad_academica',
                    'id_dependencia_academica',
                    'id_investigador',
                    'id_investigador',
                    'id_categoria',
                    'id_programa_inv',
                    'id_campo'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves != 0 && $evaluador != 0 && $obj != 0 && $apro != 0 && $sol != 0 && $puntaje != 0 && $elider != 0 && $categoria != 0 && $programa != 0 && $campo != 0 && $linea != 0 && $area == 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador',
                    'id_investigador',
                    'objetivo_general',
                    'id_unidad_academica',
                    'id_dependencia_academica',
                    'id_investigador',
                    'id_investigador',
                    'id_categoria',
                    'id_programa_inv',
                    'id_campo',
                    'id_linea_inv'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $inves != 0 && $evaluador != 0 && $obj != 0 && $apro != 0 && $sol != 0 && $puntaje != 0 && $elider != 0 && $categoria != 0 && $programa != 0 && $campo != 0 && $linea != 0 && $area != 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador',
                    'id_investigador',
                    'objetivo_general',
                    'id_unidad_academica',
                    'id_dependencia_academica',
                    'id_investigador',
                    'id_investigador',
                    'id_categoria',
                    'id_programa_inv',
                    'id_campo',
                    'id_linea_inv',
                    'id_area_tematica'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen == 0 && $titulo_conv != 0 && $inves != 0 && $evaluador == 0 && $obj == 0 && $apro != 0 && $sol != 0 && $puntaje == 0 && $elider == 0 && $categoria != 0 && $programa != 0 && $campo != 0 && $linea != 0 && $area != 0) {
            
            $id = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'nombre_proy',
                    'id_investigador',
                    'id_unidad_academica',
                    'id_dependencia_academica',
                    'id_categoria',
                    'id_programa_inv',
                    'id_campo',
                    'id_linea_inv',
                    'id_area_tematica'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
    }

    public function getAplicarusuario($id)
    {
        $resultSet = $this->select(array(
            'id_investigador' => $id
        ));
        return $resultSet->toArray();
    }

    public function getAplicarh()
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

    public function getPropuesta($id, $unidad, $dependencia, $programa, $categoria)
    {
        if ($unidad != 0 && $dependencia == 0 && $programa == 0 && $categoria == 0) {
            $rowset = $this->select(function (Where $select) use($id, $unidad)
            {
                $select->where(array(
                    'id_convocatoria= ?' => $id,
                    'id_unidad_academica= ?' => $unidad
                ));
            });
            return $rowset->toArray();
        }
        
        if ($unidad == 0 && $dependencia != 0 && $programa == 0 && $categoria == 0) {
            $rowset = $this->select(function (Where $select) use($id, $dependencia)
            {
                $select->where(array(
                    'id_convocatoria= ?' => $id,
                    'id_dependencia_academica= ?' => $dependencia
                ));
            });
            return $rowset->toArray();
        }
        
        if ($unidad == 0 && $dependencia == 0 && $programa != 0 && $categoria == 0) {
            $rowset = $this->select(function (Where $select) use($id, $programa)
            {
                $select->where(array(
                    'id_convocatoria= ?' => $id,
                    'id_programa_academico= ?' => $programa
                ));
            });
            return $rowset->toArray();
        }
        
        if ($unidad == 0 && $dependencia == 0 && $programa == 0 && $categoria != 0) {
            $rowset = $this->select(function (Where $select) use($id, $categoria)
            {
                $select->where(array(
                    'id_convocatoria= ?' => $id,
                    'id_categoria= ?' => $categoria
                ));
            });
            return $rowset->toArray();
        }
        
        if ($unidad == 0 && $dependencia == 0 && $programa == 0 && $categoria == 0) {
            $rowset = $this->select(function (Where $select) use($id, $categoria)
            {
                $select->where(array(
                    'id_convocatoria= ?' => $id
                ));
            });
            return $rowset->toArray();
        }
    }

    public function reporteProyectos3($id_aplicar, $codigo, $resumen, $titulo_conv, $sol, $inves, $evaluador, $obj, $unideppro, $elider, $informe, $duracion, $campo)
    {
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'id_aplicar'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo == 0 && $resumen != 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'resumen_ejecutivo'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo == 0 && $resumen == 0 && $titulo_conv != 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'nombre_proy'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo == 0 && $resumen == 0 && $titulo_conv == 0 && $sol != 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'nombre_proy'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo == 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves != 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'id_investigador'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo == 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador != 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'id_investigador'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo == 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj != 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'objetivo_general'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo == 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro != 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'id_unidad_academica',
                    'id_dependencia_academica',
                    'id_programa_academico'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo == 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider != 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'id_investigador'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo == 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe != 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'id_proyecto'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo == 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion != 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'duracion',
                    'periodo'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo == 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo != 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'id_campo',
                    'id_linea_inv'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $sol != 0 && $inves != 0 && $evaluador != 0 && $obj != 0 && $unideppro != 0 && $elider != 0 && $informe != 0 && $duracion != 0 && $campo != 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'id_aplicar',
                    'codigo_proy',
                    'resumen_ejecutivo',
                    'nombre_proy',
                    'id_investigador',
                    'objetivo_general',
                    'id_campo',
                    'id_linea_inv',
                    'id_unidad_academica',
                    'id_dependencia_academica',
                    'id_programa_academico',
                    'duracion',
                    'periodo'
                ));
                $select->where(array(
                    'id_aplicar =?' => $id
                ));
            });
            return $rowset->toArray();
        }
    }

    public function addAplicari($data = array(), $id, $id_investigador, $nombre_modalidad)
    {
        $filter = new StringTrim();
        $array = array(
            'id_convocatoria' => $id,
            'nombre_proy' => $data->nombre_proy,
            'id_investigador' => $id_investigador,
            'id_categoria' => explode("-",$data->id_categoria)[0],
            'id_linea_inv' => explode("-",$data->id_linea_inv)[0],
            'id_campo' => $data->id_campo,
            'id_area_tematica' => $data->id_area_tematica,
            'recursos_funcion' => $data->recursos_funcion,
            'recursos_inversion' => $data->recursos_inversion,
            'total_financia' => $data->total_financia,
            'id_unidad_academica' => $data->id_unidad_academica,
            'id_dependencia_academica' => explode("-",$data->id_dependencia_academica)[0],
            'id_programa_academico' => explode("-",$data->id_programa_academico)[0],
            'total_proy' => $data->recursos_funcion + $data->recursos_inversion + $data->total_financia,
            'duracion' => $data->duracion,
            'periodo' => $data->periodo,
            'resumen_ejecutivo' => $filter->filter($data->resumen_ejecutivo),
            'objetivo_general' => $filter->filter($data->objetivo_general),
            'instituciones_coofinanciacion' => $filter->filter($data->instituciones_coofinanciacion),
            'area_tematica' => $filter->filter($data->area_tematica),
            'descriptores' => $filter->filter($data->descriptores),
            'antecedentes' => $filter->filter($data->antecedentes),
            'planteamiento_problema' => $filter->filter($data->planteamiento_problema),
            'fecha_crea' => now,
            'nombre_modalidad' => $nombre_modalidad
        );
        $this->insert($array);
        
        $id = $this->getAdapter()
            ->getDriver()
            ->getLastGeneratedValue("mgc_aplicar_id_aplicar_seq");
        return $id;
    }
    public function updateAplicari($data = array(), $id, $nombre_modalidad)
    {
        $filter = new StringTrim();
        $array = array(
            'nombre_proy' => $data->nombre_proy,
            'id_categoria' => explode("-",$data->id_categoria)[0],
            'id_linea_inv' => explode("-",$data->id_linea_inv)[0],
            'id_campo' => $data->id_campo,
            'id_area_tematica' => $data->id_area_tematica,
            'recursos_funcion' => $data->recursos_funcion,
            'recursos_inversion' => $data->recursos_inversion,
            'total_financia' => $data->total_financia,
            'id_unidad_academica' => $data->id_unidad_academica,
            'id_dependencia_academica' => explode("-",$data->id_dependencia_academica)[0],
            'id_programa_academico' => explode("-",$data->id_programa_academico)[0],
            'total_proy' => $data->recursos_funcion + $data->recursos_inversion + $data->total_financia,
            'duracion' => $data->duracion,
            'periodo' => $data->periodo,
            'resumen_ejecutivo' => $filter->filter($data->resumen_ejecutivo),
            'objetivo_general' => $filter->filter($data->objetivo_general),
            'instituciones_coofinanciacion' => $filter->filter($data->instituciones_coofinanciacion),
            'area_tematica' => $filter->filter($data->area_tematica),
            'descriptores' => $filter->filter($data->descriptores),
            'antecedentes' => $filter->filter($data->antecedentes),
            'planteamiento_problema' => $filter->filter($data->planteamiento_problema),
            'marco_teorico' => $filter->filter($data->marco_teorico),
            'estado_arte' => $filter->filter($data->estado_arte),
            'bibliografia' => $filter->filter($data->bibliografia),
            'metodologia' => $filter->filter($data->metodologia),
            'momentos_proyecto' => $filter->filter($data->momentos_proyecto),
            'compromisos_conocimiento' => $filter->filter($data->compromisos_conocimiento),
            'id_semillero' => $data->id_semillero,
            'semestresano' => $data->semestresano,
            'nombre_modalidad' => $nombre_modalidad
        );

        $this->update($array, array(
            'id_aplicar' => $id
        ));
        return 1;
    }   
}
?>