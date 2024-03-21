<?php
//date_default_timezone_set('UTC');

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Proyectos extends TableGateway
{
    
    // variables de la tabla
    private $nombre_proy;
    private $codigo_proy;
    private $id_investigador;
    private $duracion;
    private $id_campo;
    private $id_linea_inv;
    private $id_unidad_academica;
    private $id_dependencia_academica;
    private $id_programa_academico;
    private $resumen_ejecutivo;
    private $objetivo_general;
    private $id_estado;
    private $observaciones;
    private $periodo;
    private $id_proyecto;
    private $facultad;
    private $programa;
    private $tipo_proyecto;
    private $fecha_limite;
    private $primera_vigencia;
    private $modificaciones_documento;
    private $documento_formalizacion;
    private $fecha_inicio;
    private $fecha_terminacion;
    private $prorroga;
    private $convocatoria;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgp_proyectos', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        
        $this->nombre_proy = $datos["nombre_proy"];
        $this->codigo_proy = $datos["codigo_proy"];
        $this->id_investigador = $datos["id_investigador"];
        $this->duracion = $datos["duracion"];
        $this->id_campo = $datos["id_campo"];
        $idLineaInv = explode("-",$datos["id_linea_inv"]);
        $this->id_linea_inv = $idLineaInv[0];
        $this->id_unidad_academica = $datos["id_unidad_academica"];
        $this->id_dependencia_academica = $datos["id_dependencia_academica"];
        $this->id_programa_academico = $datos["id_programa_academico"];
        $this->resumen_ejecutivo = $datos["resumen_ejecutivo"];
        $this->objetivo_general = $datos["objetivo_general"];
        $this->periodo = $datos["periodo"];
        $this->id_proyecto = $datos["id_proyecto"];
        $this->observaciones = $datos["observaciones"];
        $this->id_estado = $datos["id_estado"];
        $this->facultad = $datos["facultad"];
        $this->programa = $datos["programa"];
        $this->tipo_proyecto = $datos["tipo_proyecto"];
        $this->fecha_limite = $datos["fecha_limite"];
        $this->primera_vigencia = $datos["primera_vigencia"];
        $this->modificaciones_documento = $datos["modificaciones_documento"];
        $this->documento_formalizacion = $datos["documento_formalizacion"];
        $this->fecha_inicio = $datos["fecha_inicio"];
        $this->fecha_terminacion = $datos["fecha_terminacion"];
        $this->prorroga = $datos["prorroga"];
        $this->convocatoria = $datos["convocatoria"];
    }

    public function getData($datos = array())
    {
        self::cargaAtributos($datos);
        if ($this->id_proyecto != '' && $this->codigo_proy == '' && $this->nombre_proy == '' && $this->tipo_proyecto == '') {
            $id = $this->id_proyecto;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->where(array(
                    'id_proyecto' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($this->id_proyecto == '' && $this->codigo_proy != '' && $this->nombre_proy == '' && $this->tipo_proyecto == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '') {
            $cod = mb_strtoupper($this->codigo_proy);
            $rowset = $this->select(function (Where $select) use($cod)
            {
                $select->where(array(
                    'upper(codigo_proy) LIKE ?' => '%' . $cod . '%'
                ));
            });
            return $rowset->toArray();
        }
        if ($this->id_proyecto == '' && $this->codigo_proy == '' && $this->nombre_proy != '' && $this->tipo_proyecto == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '') {
            $nom = mb_strtoupper($this->nombre_proy);
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'upper(nombre_proy) LIKE ?' => '%' . $nom . '%'
                ));
            });
            return $rowset->toArray();
        }
        if ($this->id_proyecto == '' && $this->codigo_proy == '' && $this->nombre_proy == '' && $this->tipo_proyecto != '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '') {
            $nom = mb_strtoupper($this->tipo_proyecto);
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'tipo_conv' => $nom
                ));
            });
            return $rowset->toArray();
        }
        if ($this->id_proyecto == '' && $this->codigo_proy == '' && $this->nombre_proy == '' && $this->tipo_proyecto == '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '') {
            $nom = mb_strtoupper($this->id_unidad_academica);
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'id_unidad_academica' => $nom
                ));
            });
            return $rowset->toArray();
        }
        if ($this->id_proyecto == '' && $this->codigo_proy == '' && $this->nombre_proy == '' && $this->tipo_proyecto == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica != '' && $this->id_programa_academico == '') {
            $nom = mb_strtoupper($this->id_dependencia_academica);
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'id_dependencia_academica' => $nom
                ));
            });
            return $rowset->toArray();
        }
        if ($this->id_proyecto == '' && $this->codigo_proy == '' && $this->nombre_proy == '' && $this->tipo_proyecto == '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica == '' && $this->id_programa_academico != '') {
            $nom = mb_strtoupper($this->id_programa_academico);
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'id_programa_academico' => $nom
                ));
            });
            return $rowset->toArray();
        }
    }

    public function filtroMonitores($datos = array())
    {
        self::cargaAtributos($datos);
        if ($this->facultad != '' && $this->programa == '') {
            
            $nom = '%' . $this->facultad . '%';
            $nom = strtoupper($nom);
            $rowset = $this->select(function (Where $select) use($nom)
            {
                $select->where(array(
                    'upper(id_facultad) LIKE ?' => $nom,
                    'tipo_conv' => 'm'
                ));
            });
            return $rowset->toArray();
        }
        if ($this->facultad == '' && $this->programa != '') {
            
            $nom = '%' . $this->programa . '%';
            $nom = strtoupper($nom);
            $rowset = $this->select(function (Where $select) use($nom)
            {
                $select->where(array(
                    'upper(id_programa_curricular) LIKE ?' => $nom,
                    'tipo_conv' => 'm'
                ));
            });
            return $rowset->toArray();
        }
        if ($this->facultad == '' && $this->programa == '') {
            $nom = 0;
            $rowset = $this->select(function (Where $select) use($nom)
            {
                $select->where(array(
                    'tipo_conv' => 'm'
                ));
            });
            return $rowset->toArray();
        }
    }

    public function addProyectosconv($nom, $cod, $inv, $dur, $campo, $linea, $uni, $dep, $pro, $res, $obj, $per, $conv, $tip, $idap)
    {
        $array = array(
            'nombre_proy' => $nom,
            'codigo_proy' => $cod,
            
            'duracion' => $dur,
            'id_campo' => $campo,
            'id_linea_inv' => $linea,
            'id_unidad_academica' => $uni,
            'id_dependencia_academica' => $dep,
            'id_programa_academico' => $pro,
            'periodo' => $per,
            'resumen_ejecutivo' => $res,
            'objetivo_general' => $obj,
            'id_convocatoria' => $conv,
            'tipo_conv' => $tip,
            'id_estado' => 1,
            'id_aplicar' => $idap,
            'id_investigador' => $inv,
            'fecha_crea' => now
        );
        $this->insert($array);
        $id = $this->getAdapter()
            ->getDriver()
            ->getLastGeneratedValue("mgp_proyectos_inv");
        return $id;
    }

    public function addProyectosconvm($nom, $cod, $inv, $facultad, $procur, $conv, $tip, $idap)
    {
        self::cargaAtributos($data);
        
        $array = array(
            'nombre_proy' => $nom,
            'codigo_proy' => $cod,
            'id_investigador' => $inv,
            'id_facultad' => $facultad,
            'id_programa_curricular' => $procur,
            'tipo_conv' => $tip,
            'id_estado' => 1,
            'id_aplicar' => $idap,
            'fecha_crea' => now
        );
        $this->insert($array);
        
        $id = $this->getAdapter()
            ->getDriver()
            ->getLastGeneratedValue("mgp_proyectos_inv");
        
        return $id;
    }

    public function addProyectos($data = array())
    {
        self::cargaAtributos($data);
        $dep=substr($this->id_dependencia_academica,0,strpos($this->id_dependencia_academica, '-'));
        $prog=substr($this->id_programa_academico,0,strpos($this->id_programa_academico, '-'));
        
        $array = array(
            'nombre_proy' => $this->nombre_proy,
            'codigo_proy' => $this->codigo_proy,
            
            'id_linea_inv' => $this->id_linea_inv,
            'id_unidad_academica' => $this->id_unidad_academica,
            'periodo' => $this->periodo,
            'resumen_ejecutivo' => $this->resumen_ejecutivo,
            'primera_vigencia' => $this->primera_vigencia,
            'objetivo_general' => $this->objetivo_general,
            'stipo' => $data->stipo,
            'id_estado' => 1,
            'modificaciones_documento' => $this->modificaciones_documento,
            'documento_formalizacion' => $this->documento_formalizacion,
            'fecha_inicio' => $this->fecha_inicio,
            'prorroga' => $this->prorroga,
            'fecha_crea' => now
        );
        if($this->fecha_terminacion!=""){
            $array = $array + array('fecha_terminacion' => $this->fecha_terminacion);
        }
        if($this->id_investigador != ""){
            $array = $array + array('id_investigador' => $this->id_investigador);
        }
        if($this->tipo_proyecto != ""){
            $array = $array + array('tipo_conv' => $this->tipo_proyecto);
        }
        if($this->duracion != ""){
            $array = $array + array('duracion' => $this->duracion);
        }
        if($dep != ""){
            $array = $array + array('id_unidad_academica' => $dep);
        }
        if($prog != ""){
            $array = $array + array('id_programa_academico' => $prog);
        }
        if($this->convocatoria != ""){
            $array = $array + array('convocatoria' => $this->convocatoria);
        }
        if($this->id_campo != ""){
            $array = $array + array('id_campo' => $this->id_campo);
        }
        $this->insert($array);
        $id = $this->getAdapter()
            ->getDriver()
            ->getLastGeneratedValue("mgp_proyectos_inv");
        return $id;
    }

    public function getProyectoid($id)
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

    public function getProyectoparam($nom, $inv, $dur, $campo, $linea, $uni, $dep, $pro, $res, $obj, $per, $conv, $tip, $idap)
    {
        $rowset = $this->select(array(
            'nombre_proy' => $nom,
            'id_investigador' => $inv,
            'duracion' => $dur,
            'id_campo' => $campo,
            'id_linea_inv' => $linea,
            'id_unidad_academica' => $uni,
            'id_dependencia_academica' => $dep,
            'id_programa_academico' => $pro,
            'periodo' => $per,
            'id_convocatoria' => $conv,
            'tipo_conv' => $tip,
            'id_aplicar' => $idap
        ));
        
        return $rowset->toArray();
    }

    public function filtroProyectos($datos = array())
    {
        self::cargaAtributos($datos);
        
        //Filtro por id
        if ($this->id_proyecto != null && $this->codigo_proy == "" && $this->nombre_proy == null && $this->tipo_proyecto == "") {            
            $id = $this->id_proyecto;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->where(array(
                    'id_proyecto  = ?' => $id
                ));
            });            
            
            return $rowset->toArray();
        }
        
        //Filtro por codigo
        
        if ($this->id_proyecto == null && $this->codigo_proy != "" && $this->nombre_proy == "" && $this->tipo_proyecto == "") {
        
            $nom = '%' . $this->codigo_proy . '%';
            $nom = strtoupper($nom);
            $rowset = $this->select(function (Where $select) use($nom)
            {
                $select->where(array(
                    'upper(codigo_proy) LIKE ?' => $nom
                ));
            });
            
            return $rowset->toArray();
        }
        
        //Filtro por nombre        
        if ($this->id_proyecto == null && $this->codigo_proy == "" && $this->nombre_proy != "" && $this->tipo_proyecto == "") {
            $nom = mb_strtoupper('%' . $this->nombre_proy . '%');
            $rowset = $this->select(function (Where $select) use($nom)
            {
                $select->where(array(
                    'upper(nombre_proy) LIKE ?' => $nom
                ));
            });
        
            return $rowset->toArray();
        }

        if ($this->id_proyecto == null && $this->codigo_proy == "" && $this->nombre_proy == "" && $this->tipo_proyecto != "") {
            $id = $this->tipo_proyecto;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->where(array(
                    'tipo_conv  = ?' => $id
                ));
            });    
            return $rowset->toArray();
        }
        
        //Si no hay filtros
        if ($this->id_proyecto == null && $this->codigo_proy == "" && $this->nombre_proy == "" && $this->tipo_proyecto == "") {
            $rowset = $this->select();
            return $rowset->toArray();
        }
    }

    public function filtroProy($datos = array())
    {
        self::cargaAtributos($datos);
        if ($this->nombre_proy != null) {
            
            $nom = '%' . $this->nombre_proy . '%';
            $nom = strtoupper($nom);
            $rowset = $this->select(function (Where $select) use($nom)
            {
                $select->where(array(
                    'upper(nombre_proy) LIKE ?' => $nom
                ));
            });
            return $rowset->toArray();
        }
        
        if ($this->nom_proy == '') {
            $rowset = $this->select();
            return $rowset->toArray();
        }
    }

    public function monProyectos()
    {
        $id = 2; //Estado 2: Aprobado
        $rowset = $this->select(function (Where $select) use($id)
        {
            $select->where(array(
                'id_estado  =?' => $id
            ));
        });
        return $rowset->toArray();
    }

    public function updateProyectosestado($id)
    {
        $array = array(
            'id_estado' => 3
        );
        
        $this->update($array, array(
            'id_proyecto' => $id
        ));
    }

    public function updateProyectos($id, $data = array())
    {
        self::cargaAtributos($data);
        
        $arrayid_investigador = array();
        if ($this->id_investigador != '0') {
            $arrayid_investigador = array(
                'id_investigador' => $this->id_investigador
            );
        }
        
        $arrayid_campo = array();
        if ($this->id_campo != '0') {
            echo $this->id_campo."<-Campo2";
            $arrayid_campo = array(
                'id_campo' => $this->id_campo
            );
        }
        
        $arrayid_linea = array();
        if ($this->id_linea_inv != '0') {
            echo $this->id_linea_inv.":linea.";
            $arrayid_linea = array(
                'id_linea_inv' => $this->id_linea_inv
            );
        }
        
        $arrayid_unidad = array();
        if ($this->id_unidad_academica != '0') {
            //echo '</br>';
            //echo $this->id_unidad_academica;
            //echo $this->id_unidad_academica;
            $arrayid_unidad = array(
                'id_unidad_academica' => $this->id_unidad_academica
            );
        }
                
        $arrayid_dependencia = array();
        if ($this->id_dependencia_academica != '0') {
            //echo '</br>';
            echo $this->id_dependencia_academica.'/IF';
            $arrayid_dependencia = array(
                'id_dependencia_academica' => substr($this->id_dependencia_academica,0,strpos($this->id_dependencia_academica, '-'))
            );
        }else{
            echo $this->id_dependencia_academica.'/Else';
        }


        $arrayid_programa = array();
        if ($this->id_programa_academico != '0') {
            //echo '</br>';
            //echo 
            $arrayid_programa = array(
                'id_programa_academico' => substr($this->id_programa_academico,0,strpos($this->id_programa_academico, '-'))
            );
        }


        $array_fecha = array();
        if ($this->fecha_limite != '0') {
            
            $array_fecha = array(
                'fecha_limite' => $this->fecha_limite
            );
        }
        
        $arrayperiodo = array();
        if ($this->periodo != '') {
            $arrayperiodo = array(
                'periodo' => $this->periodo
            );
        }
        $arrayestado = array();
        if ($this->id_estado != null) {
            $arrayestado = array(
                'id_estado' => $this->id_estado
            );
        }
        
        $arrayobservacion = array();
        if ($this->observaciones != '') {
            $arrayobservacion = array(
                'observaciones' => $this->observaciones
            );
        }

        $prorroga = array();
        if ($this->prorroga != '') {
            $prorroga = array(
                'prorroga' => $this->prorroga
            );
        }

        $tipo_proyecto = array();
        if ($this->tipo_proyecto != '') {
            $tipo_proyecto = array(
                'tipo_conv' => $this->tipo_proyecto
            );
        }

        $fecha_inicio = array();
        if ($this->fecha_inicio != '') {
            $fecha_inicio = array(
                'fecha_inicio' => $this->fecha_inicio
            );
        }else{
            $fecha_inicio = array(
                'fecha_inicio' => null
            );
        }

        $convocatoria = array(
                'convocatoria' => $this->convocatoria
            );
        
        $array = array(
            'nombre_proy' => $this->nombre_proy,
            'codigo_proy' => $this->codigo_proy,
            'primera_vigencia' => $this->primera_vigencia,
            'duracion' => $this->duracion,
            'resumen_ejecutivo' => $this->resumen_ejecutivo,
            'objetivo_general' => $this->objetivo_general,            
            'documento_formalizacion' => $this->documento_formalizacion,
            'modificaciones_documento' => $this->modificaciones_documento
        );

        if($this->fecha_terminacion!=""){
            $array = $array + array('fecha_terminacion' => $this->fecha_terminacion);
        }

        $array = $array + $arrayid_investigador + $arrayid_campo + $array_fecha + $arrayid_linea + $arrayid_unidad + $arrayid_dependencia + $arrayid_programa + $arrayperiodo + $arrayestado + $arrayobservacion + $prorroga + $tipo_proyecto + $convocatoria + $fecha_inicio;
        
        $this->update($array, array(
            'id_proyecto' => $id
        ));
        
        return 1;
    }

    public function getProyecto($id)
    {
        $resultSet = $this->select(array(
            'id_proyecto' => $id
        ));
        return $resultSet->toArray();
    }

    public function getCodigo($conv, $dep, $uni, $pro)
    {
        if ($conv != '') {
            
            $rowset = $this->select(function (Where $select) use($conv)
            {
                $select->columns(array(
                    'codigo_proy'
                ));
                $select->where(array(
                    'id_convocatoria =?' => $conv
                ));
            });
            
            return $rowset->toArray();
        }
        
        if ($dep != '') {
            $rowset = $this->select(function (Where $select) use($dep)
            {
                $select->columns(array(
                    'codigo_proy'
                ));
                $select->where(array(
                    'id_convocatoria =?' => $dep
                ));
            });
            
            return $rowset->toArray();
        }
        
        if ($uni != '') {
            $rowset = $this->select(function (Where $select) use($uni)
            {
                $select->columns(array(
                    'codigo_proy'
                ));
                $select->where(array(
                    'id_unidad_academica =?' => $uni
                ));
            });
            
            return $rowset->toArray();
        }
        
        if ($pro != '') {
            $rowset = $this->select(function (Where $select) use($pro)
            {
                $select->columns(array(
                    'codigo_proy'
                ));
                $select->where(array(
                    'id_convocatoria =?' => $pro
                ));
            });
            
            return $rowset->toArray();
        }
    }

    public function reporteProyectos2($id_convocatoria, $fecha, $codigo, $titulo_conv, $inves, $evaluador, $obj, $apro, $sol, $puntaje, $elider)
    {
        if ($codigo != 0) {
            
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
            return $rowset;
        }
    }

    public function reporteProyectos($id_convocatoria, $nombre, $tipo, $estado, $fecha, $codigo, $titulo_conv, $inves, $evaluador, $obj, $apro, $sol, $puntaje, $elider)
    {
        if ($id_convocatoria != 0 && $tipo == 0 && $nombre == 0) {
            
            $id = $id_convocatoria;
            
            if ($codigo != 0 && $titulo_conv != 0 && $inves != 0 && $obj != 0) {
                
                $rowset = $this->select(function (Where $select) use($id)
                {
                    $select->columns(array(
                        'codigo_proy',
                        'nombre_proy',
                        'id_investigador',
                        'objetivo_general'
                    ));
                    $select->where(array(
                        'id_convocatoria  =?' => $id
                    ));
                });
                return $rowset->toArray();
            } elseif ($titulo_conv != 0 && $codigo == 0 && $inves == 0 && $obj == 0) {
                
                $rowset = $this->select(function (Where $select) use($id)
                {
                    $select->columns(array(
                        'nombre_proy'
                    ));
                    $select->where(array(
                        'id_convocatoria  =?' => $id
                    ));
                });
                return $rowset->toArray();
            } elseif ($titulo_conv == 0 && $codigo != 0 && $inves == 0 && $obj == 0) {
                
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
            } elseif ($titulo_conv == 0 && $codigo == 0 && $inves != 0 && $obj == 0) {
                
                $rowset = $this->select(function (Where $select) use($id)
                {
                    $select->columns(array(
                        'id_investigador'
                    ));
                    $select->where(array(
                        'id_convocatoria  =?' => $id
                    ));
                });
                return $rowset->toArray();
            } elseif ($titulo_conv == 0 && $codigo != 0 && $inves == 0 && $obj != 0) {
                
                $rowset = $this->select(function (Where $select) use($id)
                {
                    $select->columns(array(
                        'objetivo_general'
                    ));
                    $select->where(array(
                        'id_convocatoria  =?' => $id
                    ));
                });
                return $rowset->toArray();
            }
        }
        
        if ($id_convocatoria == 0 && $tipo != 0 && $nombre == 0) {
            
            $id = trim($tipo);
            
            if ($codigo != 0 && $titulo_conv != 0) {
                
                $rowset = $this->select(function (Where $select) use($id)
                {
                    $select->columns(array(
                        'codigo_proy',
                        'nombre_proy'
                    ));
                    $select->where(array(
                        'tipo_conv  =?' => $id
                    ));
                });
                return $rowset->toArray();
            } elseif ($titulo_conv != 0 && $codigo == 0) {
                
                $rowset = $this->select(function (Where $select) use($id)
                {
                    $select->columns(array(
                        'nombre_proy'
                    ));
                    $select->where(array(
                        'tipo_conv  =?' => $id
                    ));
                });
                return $rowset->toArray();
            } elseif ($titulo_conv == 0 && $codigo != 0) {
                
                $rowset = $this->select(function (Where $select) use($id)
                {
                    $select->columns(array(
                        'codigo_proy'
                    ));
                    $select->where(array(
                        'tipo_conv  =?' => $id
                    ));
                });
                return $rowset->toArray();
            }
        }
        
        if ($id_convocatoria == 0 && $tipo == 0 && $nombre != 0) {
            
            $nom = '%' . $nombre . '%';
            $nom = strtoupper($nom);
            
            if ($codigo != 0 && $titulo_conv != 0) {
                
                $rowset = $this->select(function (Where $select) use($nom)
                {
                    $select->columns(array(
                        'codigo_proy',
                        'nombre_proy'
                    ));
                    $select->where(array(
                        'upper(nombre_proy) LIKE  ?' => $nom
                    ));
                });
                return $rowset->toArray();
            } elseif ($titulo_conv != 0 && $codigo == 0) {
                
                $rowset = $this->select(function (Where $select) use($nom)
                {
                    $select->columns(array(
                        'nombre_proy'
                    ));
                    $select->where(array(
                        'upper(nombre_proy) LIKE ?' => $nom
                    ));
                });
                return $rowset->toArray();
            } elseif ($titulo_conv == 0 && $codigo != 0) {
                
                $rowset = $this->select(function (Where $select) use($nom)
                {
                    $select->columns(array(
                        'codigo_proy'
                    ));
                    $select->where(array(
                        'upper(nombre_proy) LIKE ?' => $nom
                    ));
                });
                return $rowset->toArray();
            }
        }
    }

    public function getProyectoh()
    {
        $rowset = $this->select(function (Where $select){
            $select->order(array('codigo_proy ASC'));
            });
            return $rowset->toArray();
    }

    public function mensajeProyectos($mensaje, $proy, $idproy, $conv, $email)
    {
        $filter = new StringTrim();
        $proy = $filter->filter($proy);
        $email = $filter->filter($email);
        $conv = $filter->filter($conv);
        $message = $filter->filter($mensaje) + ' Codigo del proyecto: ' . $idproy . ', nombre del Proyecto: ' . $proy . ' de la convocatoria ' . $conv . '.';
        $message = new \Zend\Mail\Message();
        $message->setBody($filter->filter($mensaje));
//        $message->setFrom('ricardo.sanchez.villabon@gmail.com');
        $message->setFrom('ricardo.sanchez.villabon@gmail.com');
        $message->setSubject('solicitud propuesta de investigacion');
        $message->addTo($email);
        
        $smtOptions = new \Zend\Mail\Transport\SmtpOptions();
        $smtOptions->setHost('relay01.upn.edu.co')->setport(25);
        
        $transport = new \Zend\Mail\Transport\Smtp($smtOptions);
        $transport->send($message);
    }

    public function getProyectosusuario($id)
    {
        $resultSet = $this->select(array(
            'id_investigador' => $id
        ));
        return $resultSet->toArray();
    }

    public function reporteProyectos3($id_proy, $codigo, $resumen, $titulo_conv, $sol, $inves, $evaluador, $obj, $unideppro, $elider, $informe, $duracion, $campo)
    {
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy'
                ));
                $select->where(array(
                    'id_proyecto  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        //
        if ($codigo != 0 && $resumen != 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'resumen_ejecutivo'
                ));
                $select->where(array(
                    'id_proyecto  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo != 0 && $resumen == 0 && $titulo_conv != 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'nombre_proy'
                ));
                $select->where(array(
                    'id_proyecto  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves != 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'id_investigador'
                ));
                $select->where(array(
                    'id_proyecto  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj != 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'objetivo_general'
                ));
                $select->where(array(
                    'id_proyecto  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro != 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'id_unidad_academica',
                    'id_dependencia_academica',
                    'id_programa_academico'
                ));
                $select->where(array(
                    'id_proyecto  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion != 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'codigo_proy',
                    'duracion',
                    'periodo'
                ));
                $select->where(array(
                    'id_proyecto  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        if ($codigo != 0 && $resumen == 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo != 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'id_campo',
                    'id_linea_inv'
                ));
                $select->where(array(
                    'id_proyecto  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        //
        if ($codigo == 0 && $resumen != 0 && $titulo_conv == 0 && $sol == 0 && $inves == 0 && $evaluador == 0 && $obj == 0 && $unideppro == 0 && $elider == 0 && $informe == 0 && $duracion == 0 && $campo == 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'resumen_ejecutivo'
                ));
                $select->where(array(
                    'id_proyecto  =?' => $id
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
                    'id_proyecto  =?' => $id
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
                    'id_proyecto  =?' => $id
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
                    'id_proyecto  =?' => $id
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
                    'id_proyecto  =?' => $id
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
                    'id_proyecto  =?' => $id
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
                    'id_proyecto  =?' => $id
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
                    'id_proyecto  =?' => $id
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
                    'id_proyecto  =?' => $id
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
                    'id_proyecto  =?' => $id
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
                    'id_proyecto  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
        
        if ($codigo != 0 && $resumen != 0 && $titulo_conv != 0 && $sol != 0 && $inves != 0 && $evaluador != 0 && $obj != 0 && $unideppro != 0 && $elider != 0 && $informe != 0 && $duracion != 0 && $campo != 0) {
            $id = $id_proy;
            $rowset = $this->select(function (Where $select) use($id)
            {
                $select->columns(array(
                    'id_proyecto',
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
                    'id_proyecto  =?' => $id
                ));
            });
            return $rowset->toArray();
        }
    }

    public function getProyectoc($id, $unidad, $dependencia, $programa, $categoria)
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

    public function getProyectol()
    {
        $id = 'n';
        $rowset = $this->select(function (Where $select) use($id)
        {
            $select->where(array(
                'envio != ?' => $id
            ));
        });
        return $rowset->toArray();
    }
    
    public function updatePais($id, $id2)
    {
        $array = array(
    
            'id_pais' => $id2
        )
        ;
    
        $this->update($array, array(
            'id_grupo_inv' => $id
        ));
        return 1;
    }
    
    public function enviarCorreoliq($cod, $fecha, $dif, $id, $nombre)
    {
        //Detiene el envio de correos debido a fallas en el servidor smtp
        $filter = new StringTrim();
        
        $message = new \Zend\Mail\Message();
        $message->setBody('El plazo para la liquidación del proyecto '.trim($nombre)." con código: ".trim($cod) . ', vence el ' . $fecha . '.');
        $message->setFrom('primeCIUP@pedagogica.edu.co');
        $message->setSubject('Alerta liquidacion proyecto ' . $cod);
        $message->addTo('primeCIUP@pedagogica.edu.co');
        
        $smtOptions = new \Zend\Mail\Transport\SmtpOptions();
        $smtOptions->setHost('relay01.upn.edu.co')->setport(25);
        
        $transport = new \Zend\Mail\Transport\Smtp($smtOptions);
        $transport->send($message);
        
        if (30>=$dif && $dif>=15) {
            $array = array(
                'envio' => 'd'
            );
        } elseif (15>$dif && $dif>=5) {
            
            $array = array(
                'envio' => 'c'
            );
        } elseif (5>$dif && $dif>=0) {
            $array = array(
                'envio' => 'n'
            );
        }
        $this->update($array, array(
            'id_proyecto' => $id
        ));
        
        
    }

    public function getProyectosByYear()
    {
        $sql = "SELECT date_part('year', fecha_crea) as ano, count(*) as cantidad FROM mgp_proyectos WHERE id_convocatoria IS NOT NULL AND fecha_crea IS NOT NULL group by date_part('year', fecha_crea) order by date_part('year', fecha_crea) ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }

    public function getProyectosByConvo()
    {
        $sql = "SELECT id_convocatoria, count(*) as cantidad FROM mgp_proyectos WHERE id_convocatoria IS NOT NULL group by id_convocatoria order by id_convocatoria ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }

    public function getProyectosByYearSinConvo()
    {
        $sql = "SELECT date_part('year', fecha_crea) as ano, count(*) as cantidad FROM mgp_proyectos WHERE id_convocatoria IS NULL AND fecha_crea IS NOT NULL group by date_part('year', fecha_crea) order by date_part('year', fecha_crea) ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }
}
?>