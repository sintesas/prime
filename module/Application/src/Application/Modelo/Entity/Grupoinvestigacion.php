<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select as Where;

class Grupoinvestigacion extends TableGateway
{
    
    // variables de la tabla
    private $id_grupo_inv;

    private $nombre_grupo;

    private $fecha_creacion;

    private $fecha_creacion_grupo;

    private $id_lider;

    private $id_clasificacion;

    private $estado;

    private $id_unidad_academica;

    private $id_dependencia_academica;

    private $id_programa_academico;

    private $id_pais;

    private $id_departamento;

    private $id_ciudad;

    private $url;

    private $id_campo_investigacion;

    private $plan_accion;

    private $mision;

    private $vision;

    private $email;

    private $telefono;

    private $dir_postal;

    private $plan_estrategico;

    private $sectores_aplicacion;

    private $cod_grupo;

    private $estado_arte;

    private $retos;

    private $nombre;

    private $apellido;

    private $documento;

    private $grupo;

    private $proyecto;

    private $descripcion;

    private $descriptores;

    private $id_grupo;
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgi_grupo_inv', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->nombre_grupo = $datos["nombre_grupo"];
        $this->fecha_creacion = $datos["fecha_creacion"];
        $this->fecha_creacion_grupo = $datos["fecha_creacion_grupo"];
        $this->id_lider = $datos["id_lider"];
        $this->id_clasificacion = $datos["id_clasificacion"];
        $this->estado = $datos["estado"];
        $this->id_unidad_academica = $datos["id_unidad_academica"];
        // $this->id_dependencia_academica = $datos["id_dependencia_academica"];
        // explode de idDepedenciaAcademica
        $idDepedenciaAcademica = explode("-", $datos["id_dependencia_academica"]);
        $this->id_dependencia_academica = $idDepedenciaAcademica[0];
        
        // $this->id_programa_academico = $datos["id_programa_academico"];
        // explode de idProgramaAcademico
        $idProgramaAcademico = explode("-", $datos["id_programa_academico"]);
        $this->id_programa_academico = $idProgramaAcademico[0];
        
        $this->id_pais = $datos["id_pais"];
        
        // explode de $iddepartamento
        // $this->id_departamento = $datos["id_departamento"];
        $iddepartamento = explode("-", $datos["id_departamento"]);
        $this->id_departamento = $iddepartamento[0];
        
        // explode de $idciudad
        // $this->id_ciudad = $datos["id_ciudad"];
        $idciudad = explode("-", $datos["id_ciudad"]);
        $this->id_ciudad = $idciudad[0];
        
        $this->url = $datos["url"];
        $this->id_campo_investigacion = $datos["id_campo_investigacion"];
        $this->plan_accion = $datos["plan_accion"];
        $this->mision = $datos["mision"];
        $this->vision = $datos["vision"];
        $this->email = $datos["email"];
        $this->telefono = $datos["telefono"];
        $this->dir_postal = $datos["dir_postal"];
        $this->plan_estrategico = $datos["plan_estrategico"];
        $this->sectores_aplicacion = $datos["sectores_aplicacion"];
        $this->retos = $datos["retos"];
        $this->estado_arte = $datos["estado_arte"];
        $this->cod_grupo = $datos["cod_grupo"];
        $this->nombre = $datos["nombre"];
        $this->apellido = $datos["apellido"];
        $this->documento = $datos["documento"];
        $this->grupo = $datos["grupo"];
        $this->proyecto = $datos["proyecto"];
        $this->descripcion = $datos["descripcion"];
        $this->descriptores = $datos["descriptores"];
    }

    private function cargaAtributos2($datos = array())
    {
        $this->id_grupo = $datos["id_grupo_inv"];
    }

    public function addGrupoinvestigacion($data = array())
    {
        self::cargaAtributos($data);
        
        $array = array(
            'nombre_grupo' => $this->nombre_grupo,
            'cod_grupo' => $this->cod_grupo,
            'estado' => $this->estado,
            'url' => $this->url,
            'id_lider' => $this->id_lider,
            'fecha_creacion' => now,
            'id_clasificacion' => $this->id_clasificacion
        );
        $this->insert($array);
        
        $id = $this->getAdapter()
            ->getDriver()
            ->getLastGeneratedValue("mgi_grupo_inv_id_grupo_inv_seq");
        
        $s = 'ID=' . $id . 'nombre_grupo' . '=' . $this->nombre_grupo . ',' . 'cod_grupo' . '=' . $this->cod_grupo . ',' . 'estado' . '=' . $this->estado . ',' . 'url' . '=' . $this->url . ',' . 'id_lider' . '=' . $this->id_lider . ',' . 'fecha_crea' . '=' . now . ',' . 'id_clasificacion' . '=' . $this->id_clasificacion;
        
        return $s;
    }

    public function getGrupoinvestigacion()
    {
        //$resultSet = $this->select();
       $resultSet = $this->select(function ($select) {
            $select->order(array(
                'nombre_grupo ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function getGrupoinvestigacionOrderByCodigo()
    {
        //$resultSet = $this->select();
       $resultSet = $this->select(function ($select) {
            $select->order(array(
                'cod_grupo ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function getEventosh()
    {
        $resultSet = $this->select(array(
            'estado' => 'A'
        ));
        return $resultSet->toArray();
    }

    public function getGrupoinvid($id)
    {
        $resultSet = $this->select(array(
            'id_grupo_inv' => $id
        ));
        return $resultSet->toArray();
    }

    public function getGrupoinvid2($id)
    {
        $resultSet = $this->select(array(
            'id_grupo_inv' => $id
        ));
        $row = $resultSet->current();
        
        return $row;
    }

    public function eliminarGrupoinv($id)
    {
        $this->delete(array(
            'id_grupo_inv' => $id
        ));
    }

    public function updatePais($id, $id2)
    {
        $array = array(
            
            'id_pais' => $id2
        );
        
        $this->update($array, array(
            'id_grupo_inv' => $id
        ));
        return 1;
    }

    public function updateUni($id, $id2)
    {
        $array = array(
            
            'id_unidad_academica' => $id2
        );
        $this->update($array, array(
            'id_grupo_inv' => $id
        ));
        return $id2;
    }

    public function updateDep($id, $id2)
    {
        $array = array(
            
            'id_dependencia_academica' => $id2
        );
        
        $this->update($array, array(
            'id_grupo_inv' => $id
        ));
        return 1;
    }

    public function updateCiu($id, $id2)
    {
        $array = array(
            
            'id_ciudad' => $id2
        );
        
        $this->update($array, array(
            'id_grupo_inv' => $id
        ));
        return 1;
    }

    public function updateDept($id, $id2)
    {
        $array = array(
            
            'id_departamento' => $id2
        );
        
        $this->update($array, array(
            'id_grupo_inv' => $id
        ));
        return 1;
    }
    
    // funcion actualizar tabla hsi_noticias
    public function updateGrupoinv($id, $data = array())
    {
        self::cargaAtributos($data);
        $arrayestado = array();
        $arraypais = array();
        $arraydepartamento = array();
        $arrayciudad = array();
        $arrayunidad = array();
        $arraydependencia = array();
        $arrayprograma = array();
        $arraycampo = array();
        $arrayfecha = array();
        $arrayLider = array();
        $arrayclasificacion = array();
        $nombreG = array();
        $codigoG = array();

        if ($data->coordinador_2 != '') {
            $arrayLider = array(
                'id_lider' => $data->coordinador_2
            );
        }
        
        if ($this->estado != '') {
            $arrayestado = array(
                'estado' => $this->estado
            );
        }
        
        if ($this->id_pais != '') {
            $arraypais = array(
                'id_pais' => $this->id_pais
            );
        }
        
        if ($this->fecha_creacion_grupo != '') {
            $arrayfecha = array(
                'fecha_creacion_grupo' => $this->fecha_creacion_grupo
            );
        }
        
        if ($this->id_departamento != '') {
            $arraydepartamento = array(
                'id_departamento' => $this->id_departamento
            );
        }
        
        if ($this->id_ciudad != '') {
            $arrayciudad = array(
                'id_ciudad' => $this->id_ciudad
            );
        }
        
        if ($this->id_unidad_academica != '') {
            $arrayunidad = array(
                'id_unidad_academica' => $this->id_unidad_academica
            );
        }
        
        if ($this->id_dependencia_academica != '') {
            $arraydependencia = array(
                'id_dependencia_academica' => $this->id_dependencia_academica
            );
        }

         if ($this->id_clasificacion != '') {
            $arrayclasificacion = array(
                'id_clasificacion' => $this->id_clasificacion
            );
        }
        
        if ($this->id_programa_academico != '') {
            $arrayprograma = array(
                'id_programa_academico' => $this->id_programa_academico
            );
        }
        
        if ($this->id_campo_investigacion != '') {
            $arraycampo = array(
                'id_campo_investigacion' => $this->id_campo_investigacion
            );
        }
        if ($this->telefono == '') {
            $this->telefono = null;
        }

        if ($this->nombre_grupo != '') {
            $nombreG = array(
                'nombre_grupo' => $this->nombre_grupo
            );
        }

        if ($this->cod_grupo != '') {
            $codigoG = array(
                'cod_grupo' => $this->cod_grupo
            );
        }

        $array = array(
            'url' => $this->url,
            'plan_accion' => $this->plan_accion,
            'mision' => $this->mision,
            'vision' => $this->vision,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'dir_postal' => $this->dir_postal,
            'plan_estrategico' => $this->plan_estrategico,
            'descripcion' => $this->descripcion,
            'descriptores' => $this->descriptores,
            'sectores_aplicacion' => $this->sectores_aplicacion,
            'retos' => $this->retos,
            'estado_arte' => $this->estado_arte
        );
        
        $array = $array + $arrayestado + $arraypais + $arrayciudad + $arraydepartamento + $arrayunidad + $arrayprograma + $arraydependencia + $arraycampo + $arrayfecha + $arrayLider + $arrayclasificacion + $nombreG + $codigoG;
        
        $s = 'ID=' . $id . ', ' . 'nombre_grupo' . $this->nombre_grupo . ', ' . 'url' . $this->url . ', ' . 'plan_accion' . $this->plan_accion . ', ' . 'mision' . $this->mision . ', ' . 'vision' . $this->vision . ', ' . 'email' . $this->email . ', ' . 'cod_grupo' . $this->cod_grupo . ', ' . 'telefono' . $this->telefono . ', ' . 'dir_postal' . $this->dir_postal . ', ' . 'plan_estrategico' . $this->plan_estrategico . ', ' . 'descripcion' . $this->descripcion . ', ' . 'descriptores' . $this->descriptores . ', ' . 'sectores_aplicacion' . $this->sectores_aplicacion . ', ' . 'retos' . $this->retos . ', ' . 'estado_arte' . $this->estado_arte;
        
        $this->update($array, array(
            'id_grupo_inv' => $id
        ));
        return $s;
    }
    
    // funcion para filtrar noticias
    public function filtroEventos($datos = array())
    {
        self::cargaAtributos($datos);
        if ($this->titulo != '' && $this->evento == '') {
            $tit = '%' . $this->titulo . '%';
            $tit = strtoupper($tit);
            $rowset = $this->select(function (Where $select) use($tit) {
                $select->where(array(
                    'upper(titulo) LIKE ?' => $tit
                ));
            });
            return $rowset->toArray();
        }
        if ($this->evento != '' && $this->titulo == '') {
            $not = '%' . $this->evento . '%';
            $not = strtoupper($not);
            $rowset = $this->select(function (Where $select) use($not) {
                $select->where(array(
                    'upper(evento) LIKE ?' => '%' . $not . '%'
                ));
            });
            return $rowset->toArray();
        }
        
        if ($this->titulo != '' && $this->evento != '') {
            $not = '%' . $this->evento . '%';
            $tit = '%' . $this->titulo . '%';
            $tit = strtoupper($tit);
            $not = strtoupper($not);
            $rowset = $this->select(function (Where $select) use($not, $tit) {
                $select->where(array(
                    'upper(evento) LIKE ?' => '%' . $not . '%',
                    'upper(titulo) LIKE ?' => $tit
                ));
            });
            return $rowset->toArray();
        }
        if ($this->titulo == '' && $this->evento == '') {
            $rowset = $this->select();
            return $rowset->toArray();
        }
    }
    
    public function filtroRedes($datos = array()){
        if($datos==""){
            $resultSet = $this->select();
            return $resultSet->toArray();
        }else{
            $where = array();
            if($datos["nombre"]!=""){
                $nom = '%' . mb_strtoupper($datos["nombre"],'utf-8') . '%';
                $where["upper(nombre_grupo) LIKE ?"]= $nom;
            }

            if($datos["codigo"]!=""){
                $cod = '%' . mb_strtoupper($datos["codigo"],'utf-8') . '%';
                $where["upper(cod_grupo) LIKE ?"]= '%' . $cod . '%';
            }

            if($datos["id_autor"]!=""){
                $where["id_lider = ?"] = $datos["id_autor"];
            }

            if($datos["unidad_academica"]!=""){
                $where["id_unidad_academica = ?"] = $datos["unidad_academica"];
            }

            if($datos["dependencia_academica"]!=""){
                $where["id_dependencia_academica = ?"] = $datos["dependencia_academica"];
            }

            if($datos["programa_academico"]!=""){
                $where["id_programa_academico = ?"] = $datos["programa_academico"];
            }
            
            $rowset = $this->select(function (Where $select) use($where) {
                $select->where($where);
            });
            return $rowset->toArray();
        }
    }



    // funcion para filtrar noticias
    public function filtroGruposGI($datos = array(), $res = array())
    {
        self::cargaAtributos($datos);
        
        if ($res != null) {
            self::cargaAtributos2($res);
        }
        if ($this->id_grupo == null) {
            $res == '';
        }

        // solo nombre_grupo
        if ($this->nombre_grupo != '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $nom = '%' . mb_strtoupper($this->nombre_grupo,'utf-8') . '%';
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => $nom,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        
        // nombre_grupo y unidad
        if ($this->nombre_grupo != '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $nom = '%' . $this->nombre_grupo . '%';
            $uni = $this->id_unidad_academica;
            $rowset = $this->select(function (Where $select) use($nom, $uni) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => $nom,
                    'id_unidad_academica = ?' => $uni,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        
        // nombre_grupo, unidad y dependencia
        if ($this->nombre_grupo != '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica != '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $nom = '%' . $this->nombre_grupo . '%';
            $nom = strtoupper($nom);
            $uni = $this->id_unidad_academica;
            $uni = strtoupper($uni);
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $rowset = $this->select(function (Where $select) use($nom, $uni, $dep) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => $nom,
                    'id_unidad_academica = ?' => $uni,
                    'id_dependencia_academica = ?' => $dep,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        
        // nombre_grupo, unidad, dependencia y programa
        if ($this->nombre_grupo != '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica != '' && $this->id_programa_academico != '' && $this->cod_grupo == '') {
            $nom = '%' . $this->nombre_grupo . '%';
            $nom = strtoupper($nom);
            $uni = $this->id_unidad_academica;
            $uni = strtoupper($uni);
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $pro = $this->id_programa_academico;
            $pro = strtoupper($pro);
            $rowset = $this->select(function (Where $select) use($nom, $uni, $dep, $pro) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => $nom,
                    'id_unidad_academica = ?' => $uni,
                    'id_dependencia_academica = ?' => $dep,
                    'id_programa_academico = ?' => $pro,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        
        // solo unidad
        if ($this->nombre_grupo == '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $uni = $this->id_unidad_academica;
            $uni = strtoupper($uni);
            $rowset = $this->select(function (Where $select) use($uni) {
                $select->where(array(
                    '(id_unidad_academica) = ?' => $uni,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        
        // solo unidad y dependencia
        if ($this->nombre_grupo == '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica != '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $uni = $this->id_unidad_academica;
            $uni = strtoupper($uni);
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $rowset = $this->select(function (Where $select) use($uni, $dep) {
                $select->where(array(
                    '(id_unidad_academica) = ?' => $uni,
                    'id_dependencia_academica = ?' => $dep,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        
        // solo unidad, dependencia, programa
        // nombre_grupo, unidad, dependencia y programa
        if ($this->nombre_grupo == '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica != '' && $this->id_programa_academico != '' && $this->cod_grupo == '') {
            $uni = $this->id_unidad_academica;
            $uni = strtoupper($uni);
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $pro = $this->id_programa_academico;
            $pro = strtoupper($pro);
            $rowset = $this->select(function (Where $select) use($uni, $dep, $pro) {
                $select->where(array(
                    'id_unidad_academica = ?' => $uni,
                    'id_dependencia_academica = ?' => $dep,
                    'id_programa_academico = ?' => $pro,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        
        // solo dependencia
        if ($this->nombre_grupo == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica != '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $rowset = $this->select(function (Where $select) use($dep) {
                $select->where(array(
                    'id_dependencia_academica = ?' => $dep,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        
        // solo dependencia y programa
        if ($this->nombre_grupo == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica != '' && $this->id_programa_academico != '' && $this->cod_grupo == '') {
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $pro = $this->id_programa_academico;
            $pro = strtoupper($pro);
            $rowset = $this->select(function (Where $select) use($dep, $pro) {
                $select->where(array(
                    'id_dependencia_academica = ?' => $dep,
                    'id_programa_academico = ?' => $pro,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        
        // solo programa
        if ($this->nombre_grupo == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico != '' && $this->cod_grupo == '') {
            $pro = $this->id_programa_academico;
            $pro = strtoupper($pro);
            $rowset = $this->select(function (Where $select) use($pro) {
                $select->where(array(
                    'id_programa_academico = ?' => $pro,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        
        // lineas
        if ($this->nombre_grupo == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_grupo != null && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $res = $this->id_grupo;
            
            $rowset = $this->select(function (Where $select) use($res) {
                $select->where(array(
                    'id_grupo_inv = ?' => $res,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
         
        // solo codigo
        if ($this->nombre_grupo == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo != '') {
            $cod = mb_strtoupper($this->cod_grupo);
            $rowset = $this->select(function (Where $select) use($cod) {
                $select->where(array(
                    'upper(cod_grupo) LIKE ?' => '%' . $cod . '%',
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        
        // solo codigo y nombre
        if ($this->nombre_grupo != '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo != '') {
            $cod = '%' . $this->codigo_grupo . '%';
            $cod = strtoupper($cod);
            $nom = '%' . $this->nombre_grupo . '%';
            $nom = strtoupper($nom);
            $rowset = $this->select(function (Where $select) use($cod, $nom) {
                $select->where(array(
                    'upper(cod_grupo) LIKE ?' => '%' . $cod . '%',
                    'upper(nombre_grupo) LIKE ?' => $nom,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        
        // todos llenos
        if ($this->nombre_grupo != '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica != '' && $this->id_programa_academico != '' && $this->cod_grupo != '') {
            $nom = '%' . $this->nombre_grupo . '%';
            $uni = $this->id_unidad_academica;
            $dep = $this->id_dependencia_academica;
            $pro = $this->id_programa_academico;
            $cod = '%' . $this->cod_grupo . '%';
            $cod = strtoupper($cod);
            $nom = strtoupper($nom);
            $uni = strtoupper($uni);
            $dep = strtoupper($dep);
            $pro = strtoupper($pro);
            $rowset = $this->select(function (Where $select) use($nom, $uni, $dep, $pro, $cod, $res) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => $nom,
                    '(id_unidad_academica) = ?' => $uni,
                    '(id_dependencia_academica) = ?' => $dep,
                    '(id_grupo_inv) = ?' => $res,
                    '(id_programa_academico) = ?' => $pro,
                    'upper(cod_grupo) LIKE ?' => '%' . $cod . '%',
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        if ($this->nombre_grupo != '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $nom = '%' . $this->nombre_grupo . '%';
            $uni = $this->id_unidad_academica;
            $nom = strtoupper($nom);
            $uni = $uni;
            $rowset = $this->select(function (Where $select) use($nom, $uni) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => '%' . $nom . '%',
                    'id_unidad_academica = ?' => $uni,
                    'estado = ?' => 'A'
                ));
            });
            return $rowset->toArray();
        }
        if ($this->nombre_grupo == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            
            $where["estado = ?"] = "A";
            $rowset = $this->select(function (Where $select) use($where) {
                $select->where($where);
            });

            //$rowset = $this->select();
            return $rowset->toArray();
        }
    }


    // funcion para filtrar noticias
    public function filtroGrupos($datos = array(), $res = array())
    {
        self::cargaAtributos($datos);
        
        if ($res != null) {
            self::cargaAtributos2($res);
        }
        if ($this->id_grupo == null) {
            $res == '';
        }

        // solo nombre_grupo
        if ($this->nombre_grupo != '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $nom = '%' . mb_strtoupper($this->nombre_grupo,'utf-8') . '%';
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => $nom
                ));
            });
            return $rowset->toArray();
        }
        
        // nombre_grupo y unidad
        if ($this->nombre_grupo != '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $nom = '%' . $this->nombre_grupo . '%';
            $uni = $this->id_unidad_academica;
            $rowset = $this->select(function (Where $select) use($nom, $uni) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => $nom,
                    'id_unidad_academica = ?' => $uni
                ));
            });
            return $rowset->toArray();
        }
        
        // nombre_grupo, unidad y dependencia
        if ($this->nombre_grupo != '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica != '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $nom = '%' . $this->nombre_grupo . '%';
            $nom = strtoupper($nom);
            $uni = $this->id_unidad_academica;
            $uni = strtoupper($uni);
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $rowset = $this->select(function (Where $select) use($nom, $uni, $dep) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => $nom,
                    'id_unidad_academica = ?' => $uni,
                    'id_dependencia_academica = ?' => $dep
                ));
            });
            return $rowset->toArray();
        }
        
        // nombre_grupo, unidad, dependencia y programa
        if ($this->nombre_grupo != '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica != '' && $this->id_programa_academico != '' && $this->cod_grupo == '') {
            $nom = '%' . $this->nombre_grupo . '%';
            $nom = strtoupper($nom);
            $uni = $this->id_unidad_academica;
            $uni = strtoupper($uni);
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $pro = $this->id_programa_academico;
            $pro = strtoupper($pro);
            $rowset = $this->select(function (Where $select) use($nom, $uni, $dep, $pro) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => $nom,
                    'id_unidad_academica = ?' => $uni,
                    'id_dependencia_academica = ?' => $dep,
                    'id_programa_academico = ?' => $pro
                ));
            });
            return $rowset->toArray();
        }
        
        // solo unidad
        if ($this->nombre_grupo == '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $uni = $this->id_unidad_academica;
            $uni = strtoupper($uni);
            $rowset = $this->select(function (Where $select) use($uni) {
                $select->where(array(
                    '(id_unidad_academica) = ?' => $uni
                ));
            });
            return $rowset->toArray();
        }
        
        // solo unidad y dependencia
        if ($this->nombre_grupo == '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica != '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $uni = $this->id_unidad_academica;
            $uni = strtoupper($uni);
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $rowset = $this->select(function (Where $select) use($uni, $dep) {
                $select->where(array(
                    '(id_unidad_academica) = ?' => $uni,
                    'id_dependencia_academica = ?' => $dep
                ));
            });
            return $rowset->toArray();
        }
        
        // solo unidad, dependencia, programa
        // nombre_grupo, unidad, dependencia y programa
        if ($this->nombre_grupo == '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica != '' && $this->id_programa_academico != '' && $this->cod_grupo == '') {
            $uni = $this->id_unidad_academica;
            $uni = strtoupper($uni);
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $pro = $this->id_programa_academico;
            $pro = strtoupper($pro);
            $rowset = $this->select(function (Where $select) use($uni, $dep, $pro) {
                $select->where(array(
                    'id_unidad_academica = ?' => $uni,
                    'id_dependencia_academica = ?' => $dep,
                    'id_programa_academico = ?' => $pro
                ));
            });
            return $rowset->toArray();
        }
        
        // solo dependencia
        if ($this->nombre_grupo == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica != '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $rowset = $this->select(function (Where $select) use($dep) {
                $select->where(array(
                    'id_dependencia_academica = ?' => $dep
                ));
            });
            return $rowset->toArray();
        }
        
        // solo dependencia y programa
        if ($this->nombre_grupo == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica != '' && $this->id_programa_academico != '' && $this->cod_grupo == '') {
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $pro = $this->id_programa_academico;
            $pro = strtoupper($pro);
            $rowset = $this->select(function (Where $select) use($dep, $pro) {
                $select->where(array(
                    'id_dependencia_academica = ?' => $dep,
                    'id_programa_academico = ?' => $pro
                ));
            });
            return $rowset->toArray();
        }
        
        // solo programa
        if ($this->nombre_grupo == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico != '' && $this->cod_grupo == '') {
            $pro = $this->id_programa_academico;
            $pro = strtoupper($pro);
            $rowset = $this->select(function (Where $select) use($pro) {
                $select->where(array(
                    'id_programa_academico = ?' => $pro
                ));
            });
            return $rowset->toArray();
        }
        
        // lineas
        if ($this->nombre_grupo == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_grupo != null && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $res = $this->id_grupo;
            
            $rowset = $this->select(function (Where $select) use($res) {
                $select->where(array(
                    'id_grupo_inv = ?' => $res
                ));
            });
            return $rowset->toArray();
        }
         
        // solo codigo
        if ($this->nombre_grupo == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo != '') {
            $cod = mb_strtoupper($this->cod_grupo);
            $rowset = $this->select(function (Where $select) use($cod) {
                $select->where(array(
                    'upper(cod_grupo) LIKE ?' => '%' . $cod . '%'
                ));
            });
            return $rowset->toArray();
        }
        
        // solo codigo y nombre
        if ($this->nombre_grupo != '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo != '') {
            $cod = '%' . $this->codigo_grupo . '%';
            $cod = strtoupper($cod);
            $nom = '%' . $this->nombre_grupo . '%';
            $nom = strtoupper($nom);
            $rowset = $this->select(function (Where $select) use($cod, $nom) {
                $select->where(array(
                    'upper(cod_grupo) LIKE ?' => '%' . $cod . '%',
                    'upper(nombre_grupo) LIKE ?' => $nom
                ));
            });
            return $rowset->toArray();
        }
        
        // todos llenos
        if ($this->nombre_grupo != '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica != '' && $this->id_programa_academico != '' && $this->cod_grupo != '') {
            $nom = '%' . $this->nombre_grupo . '%';
            $uni = $this->id_unidad_academica;
            $dep = $this->id_dependencia_academica;
            $pro = $this->id_programa_academico;
            $cod = '%' . $this->cod_grupo . '%';
            $cod = strtoupper($cod);
            $nom = strtoupper($nom);
            $uni = strtoupper($uni);
            $dep = strtoupper($dep);
            $pro = strtoupper($pro);
            $rowset = $this->select(function (Where $select) use($nom, $uni, $dep, $pro, $cod, $res) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => $nom,
                    '(id_unidad_academica) = ?' => $uni,
                    '(id_dependencia_academica) = ?' => $dep,
                    '(id_grupo_inv) = ?' => $res,
                    '(id_programa_academico) = ?' => $pro,
                    'upper(cod_grupo) LIKE ?' => '%' . $cod . '%'
                ));
            });
            return $rowset->toArray();
        }
        if ($this->nombre_grupo != '' && $this->id_unidad_academica != '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $nom = '%' . $this->nombre_grupo . '%';
            $uni = $this->id_unidad_academica;
            $nom = strtoupper($nom);
            $uni = $uni;
            $rowset = $this->select(function (Where $select) use($nom, $uni) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => '%' . $nom . '%',
                    'id_unidad_academica = ?' => $uni
                ));
            });
            return $rowset->toArray();
        }
        if ($this->nombre_grupo == '' && $this->id_unidad_academica == '' && $this->id_dependencia_academica == '' && $this->id_programa_academico == '' && $this->cod_grupo == '') {
            $rowset = $this->select();
            return $rowset->toArray();
        }
    }
    
    // funcion para filtrar noticias
    public function filtroInvestigadores($datos = array())
    {
        self::cargaAtributos($datos);
        if ($this->nombre != '' && $this->apellido == '' && $this->documento == '' && $this->grupo == '' && $this->proyecto == '') {
            $nom = '%' . $this->nombre . '%';
            $nom = strtoupper($nom);
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => $nom
                ));
            });
            return $rowset->toArray();
        }
        if ($this->nombre == '' && $this->apellido != '' && $this->documento == '' && $this->grupo == '' && $this->proyecto == '') {
            $uni = $this->id_unidad_academica;
            $uni = strtoupper($uni);
            $rowset = $this->select(function (Where $select) use($uni) {
                $select->where(array(
                    '(id_unidad_academica) = ?' => $uni
                ));
            });
            return $rowset->toArray();
        }
        if ($this->nombre == '' && $this->apellido == '' && $this->documento != '' && $this->grupo == '' && $this->proyecto == '') {
            $dep = $this->id_dependencia_academica;
            $dep = strtoupper($dep);
            $rowset = $this->select(function (Where $select) use($dep) {
                $select->where(array(
                    'id_dependencia_academica = ?' => $dep
                ));
            });
            return $rowset->toArray();
        }
        if ($this->nombre == '' && $this->apellido == '' && $this->documento == '' && $this->grupo != '' && $this->proyecto == '') {
            $pro = $this->id_programa_academico;
            $pro = strtoupper($pro);
            $rowset = $this->select(function (Where $select) use($pro) {
                $select->where(array(
                    'id_programa_academico = ?' => $pro
                ));
            });
            return $rowset->toArray();
        }
        if ($this->nombre == '' && $this->apellido == '' && $this->documento == '' && $this->grupo == '' && $this->proyecto != '') {
            $cod = '%' . $this->codigo_grupo . '%';
            $cod = strtoupper($cod);
            $rowset = $this->select(function (Where $select) use($cod) {
                $select->where(array(
                    'upper(cod_grupo) LIKE ?' => '%' . $cod . '%'
                ));
            });
            return $rowset->toArray();
        }
        if ($this->nombre != '' && $this->apellido != '' && $this->documento != '' && $this->grupo != '' && $this->proyecto != '') {
            $nom = '%' . $this->nombre_grupo . '%';
            $uni = $this->id_unidad_academica;
            $dep = $this->id_dependencia_academica;
            $pro = $this->id_programa_academico;
            $cod = '%' . $this->cod_grupo . '%';
            $cod = strtoupper($cod);
            $nom = strtoupper($nom);
            $uni = strtoupper($uni);
            $dep = strtoupper($dep);
            $pro = strtoupper($pro);
            $rowset = $this->select(function (Where $select) use($nom, $uni, $dep, $pro, $cod) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => $nom,
                    '(id_unidad_academica) = ?' => $uni,
                    '(id_dependencia_academica) = ?' => $dep,
                    '(id_programa_academico) = ?' => $pro,
                    'upper(cod_grupo) LIKE ?' => '%' . $cod . '%'
                ));
            });
            return $rowset->toArray();
        }
        if ($this->nombre != '' && $this->apellido != '' && $this->documento == '' && $this->grupo == '' && $this->proyecto == '') {
            $nom = '%' . $this->nombre_grupo . '%';
            $uni = $this->id_unidad_academica;
            $nom = strtoupper($nom);
            $uni = $uni;
            $rowset = $this->select(function (Where $select) use($nom, $uni) {
                $select->where(array(
                    'upper(nombre_grupo) LIKE ?' => '%' . $nom . '%',
                    'id_unidad_academica = ?' => $uni
                ));
            });
            return $rowset->toArray();
        }
        if ($this->nombre == '' && $this->apellido == '' && $this->documento == '' && $this->grupo == '' && $this->proyecto == '') {
            $rowset = $this->select();
            return $rowset->toArray();
        }
    }

    public function filtroGruposNombreGrupo($datos = array())
    {
       $this->nombre_grupo = $datos["nombre_grupo"];
       $nom = $this->nombre_grupo;       
       // solo nombre_grupo
       $rowset = $this->select(function (Where $select) use($nom) {
           $select->where(array(
               'upper(nombre_grupo) LIKE ?' => $nom
           ));
       });
           
       return $rowset->toArray();
    }


    public function getGrupoByEstadoYear()
    {
        $sql = "SELECT date_part('year', fecha_creacion) as ano, estado, count(*) as cantidad FROM mgi_grupo_inv group by date_part('year', fecha_creacion), estado order by date_part('year', fecha_creacion), estado ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }

    public function getGruposByYear()
    {
        $sql = "SELECT date_part('year', fecha_creacion) as ano, count(*) as cantidad FROM mgi_grupo_inv group by date_part('year', fecha_creacion) order by date_part('year', fecha_creacion) ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }


    public function getGruposFiltro($data = array())
    {
        $array = array();
        if($data["codigo"] != ""){
           $array['cod_grupo LIKE ?'] = $data["codigo"];
        }

        if($data["nombre"] != ""){
            $array['nombre_grupo LIKE ?'] = $data["nombre"];
        }

        if($data["estado"] != ""){
           $array['estado = ?'] = $data["estado"];
        }
        if($data["ano"] != ""){
           $array['EXTRACT(YEAR FROM fecha_creacion) = ?'] = $data["ano"];
        }
        if($data["lider"] != ""){
           $array['id_lider = ?'] = $data["lider"];
        }
        if($data["clasificacion"] != ""){
           $array['id_clasificacion = ?'] = $data["clasificacion"];
        }
        
        $resultSet = $this->select($array);
        $row = $resultSet->toArray();
        return $row;
    }

}

?>