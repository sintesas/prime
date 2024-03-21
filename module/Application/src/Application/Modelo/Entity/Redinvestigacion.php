<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Redinvestigacion extends TableGateway
{
    // variables de la tabla
    private $id;
    private $nombre;
    private $codigo;
    private $coordinador_uno;
    private $coordinador_dos;
    private $estado;

    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgi_red_inv', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->nombre = $datos["nombre_red"];
        $this->codigo = $datos["codigo_red"];
        $this->coordinador_uno = $datos["coordinador_1"];
        $this->coordinador_dos = $datos["coordinador_2"];
        $this->estado = $datos["estado_red"];
    }

    public function getRedinv()
    {
       $resultSet = $this->select(function ($select) {
            $select->order(array(
                'nombre ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function getRedinvById($data = array(), $id)
    {
        $array = array();
        if($data["nombre"] != ""){
           $array['upper(nombre) LIKE ?'] = "%".mb_strtoupper($data["nombre"],'utf-8'). "%";
        }
        if($data["codigo"] != ""){
           $array['upper(codigo) LIKE ?'] = "%".mb_strtoupper($data["codigo"],'utf-8'). "%";
        }
        $resultSet = $this->select($array);
        $row = $resultSet->toArray();
        return $row;
    }

    public function addRedinvestigacion($data = array())
    {
        self::cargaAtributos($data);
        
        $array = array(
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'estado' => $this->estado,
            'coordinador_uno' => $this->coordinador_uno,
            'fecha_creacion' => now
        );
        if($this->coordinador_dos != ""){
            $array['coordinador_dos'] = $this->coordinador_dos;
        }
        $this->insert($array);
        
        $id = $this->getAdapter()
            ->getDriver()
            ->getLastGeneratedValue("mgi_red_inv_id_red_inv_seq");
        
        $s = 'ID=' . $id . 'nombre' . '=' . $this->nombre . ',' . 'codigo' . '=' . $this->codigo. ',' . 'estado' . '=' . $this->estado . ',' . 'coordinador_uno' . '=' . $this->coordinador_uno . ',' . 'coordinador_dos' . '=' . $this->coordinador_dos . ',' . 'fecha_creacion' . '=' . now;
        
        return $s;
    }

    public function eliminarRedinv($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getredinvid($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function getRedinvid2($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        $row = $resultSet->current();
        return $row;
    }

    public function updateRedinv($id, $data = array(), $estadoAnt)
    {

        self::cargaAtributos($data);
        $arrayT = array();
        $arrayT['vision'] = utf8_encode($data->vision);
        $arrayT['mision'] = utf8_encode($data->mision);
        $arrayT['objetivos'] = utf8_encode($data->objetivos);
        $arrayT['antecedentes'] = utf8_encode($data->antecedentes);
        $arrayT['justificacion'] = utf8_encode($data->justificacion);
        $arrayT['descripcion'] = utf8_encode($data->descripcion);
        $arrayT['lineas_investigacion'] = utf8_encode($data->lineas_investigacion);
        $arrayT['instituciones_aliadas'] =utf8_encode( $data->instituciones_aliadas);
        $arrayT['socios'] = utf8_encode($data->socios);
        $arrayT['aliados'] = utf8_encode($data->aliados);
        
        if($this->nombre !=""){
            $arrayT['nombre'] = $this->nombre;    
        }

        if($data->coordinador_1 !=""){
            $arrayT['coordinador_uno'] = $data->coordinador_1;  
        }
        
        if($data->coordinador_2 !=""){
             $arrayT['coordinador_dos'] = $data->coordinador_2;   
        }
        
        if( $this->estado !=""){
            $arrayT['estado'] = $this->estado;   
            if($estadoAnt != $this->estado){
                $arrayT['fecha_estado'] = now;
            } 
        }        
       
        $this->update($arrayT, array(
            'id' => $id
        ));
        return 1;
    }


    private function cargaAtributos2($datos = array())
    {
        $this->id_grupo = $datos["id_grupo_inv"];
    }


    public function getEventosh()
    {
        $resultSet = $this->select(array(
            'estado' => 'A'
        ));
        return $resultSet->toArray();
    }
    
    public function getRedByYear()
    {
        $sql = "SELECT date_part('year', fecha_creacion) as ano, count(id) as cantidad FROM mgi_red_inv group by date_part('year', fecha_creacion)
                order by date_part('year', fecha_creacion) ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }

    public function getRedByEstadoYear()
    {
        $sql = "SELECT date_part('year', fecha_creacion) as ano, estado, count(*) as cantidad FROM mgi_red_inv group by date_part('year', fecha_creacion), estado order by date_part('year', fecha_creacion), estado ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
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
       echo $nom;
       
       // solo nombre_grupo
       $rowset = $this->select(function (Where $select) use($nom) {
           $select->where(array(
               'upper(nombre_grupo) LIKE ?' => $nom
           ));
       });
           
       return $rowset->toArray();
    }


    public function getRedesFiltro($data = array())
    {
        $array = array();
        if($data["codigo"] != ""){
           $array['codigo LIKE ?'] = $data["codigo"];
        }

        
        if($data["nombre"] != ""){
            $array['nombre LIKE ?'] = $data["nombre"];
        }

        if($data["estado"] != ""){
           $array['estado = ?'] = $data["estado"];
        }
        if($data["ano"] != ""){
           $array['EXTRACT(YEAR FROM fecha_creacion) = ?'] = $data["ano"];
        }
        if($data["coordinador_1"] != ""){
           $array['coordinador_uno = ?'] = $data["coordinador_1"];
        }
        if($data["coordinador_2"] != ""){
           $array['coordinador_dos = ?'] = $data["coordinador_2"];
        }
        
        $resultSet = $this->select($array);
        $row = $resultSet->toArray();
        return $row;
    }
}

?>