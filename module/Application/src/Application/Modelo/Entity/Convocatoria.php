<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Convocatoria extends TableGateway
{
    
    // variables de la tabla
    private $titulo;

    private $descripcion;

    private $observaciones;

    private $fecha_apertura;

    private $fecha_cierre;

    private $hora_apertura;

    private $hora_cierre;

    private $id_entidad;

    private $id_proyectos;

    private $numero_monitores;

    private $fecha_lim_soporte;

    private $id_convocatoria;

    private $cronograma;

    private $id_tipo_conv;

    private $id_estado;

    private $nombre;

    private $ano;

    private $investigador;

    private $codigo;

    private $titulo_conv;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgc_convocatoria', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->titulo = $datos["titulo"];
        $this->descripcion = $datos["descripcion"];
        $this->observaciones = $datos["observaciones"];
        $this->fecha_apertura = $datos["fecha_apertura"];
        $this->fecha_cierre = $datos["fecha_cierre"];
        $this->hora_apertura = $datos["hora_apertura"];
        $this->hora_cierre = $datos["hora_cierre"];
        $this->id_entidad = $datos["id_entidad"];
        $this->id_proyectos = $datos["id_proyectos"];
        $this->numero_monitores = $datos["numero_monitores"];
        $this->fecha_lim_soporte = $datos["fecha_lim_soporte"];
        $this->id_convocatoria = $datos["id_convocatoria"];
        $this->cronograma = $datos["cronograma"];
        $this->id_tipo_conv = $datos["id_tipo_conv"];
        $this->id_estado = $datos["id_estado"];
        $this->investigador = $datos["investigador"];
        $this->codigo = $datos["codigo"];
        $this->titulo_conv = $datos["titulo_conv"];
    }

    private function cargaAtributos3($datos = array())
    {
        $this->titulo = $datos["titulo"];
    }

    private function cargaAtributos2($datos = array())
    {
        $this->descripcion = $datos["descripcion"];
        $this->observaciones = $datos["observaciones"];
        $this->fecha_apertura = $datos["fecha_apertura"];
        $this->fecha_cierre = $datos["fecha_cierre"];
        $this->hora_apertura = $datos["hora_apertura"];
        $this->hora_cierre = $datos["hora_cierre"];
        $this->id_entidad = $datos["id_entidad"];
        $this->id_proyectos = $datos["id_proyectos"];
        $this->numero_monitores = $datos["numero_monitores"];
        $this->fecha_lim_soporte = $datos["fecha_lim_soporte"];
        $this->cronograma = $datos["cronograma"];
        $this->id_tipo_conv = $datos["tipo_conv"];
        $this->id_estado = $datos["id_estado"];
    }

    private function cargaReporte($datos = array())
    {
        $this->ano = $datos["ano"];
        $this->id_convocatoria = $datos["id_convocatoria"];
        $this->nombre = $datos["nombre"];
        $this->id_tipo_conv = $datos["id_tipo_conv"];
        $this->id_estado = $datos["id_estado"];
    }

    public function addConvocatoria($data = array(), $tipo)
    {
        self::cargaAtributos($data);
        
        $arrayid_entidad = array();
        if ($this->id_entidad != '0') {
            $arrayid_entidad = array(
                'id_entidad' => $this->id_entidad
            );
        }
        
        $arrayid_proyectos = array();
        if ($this->id_proyectos != '0') {
            $arrayid_proyectos = array(
                'id_proyectos' => $this->id_proyectos
            );
        }
        
        $array_num_monitores = array();
        if ($this->numero_monitores != null) {
            $array_num_monitores = array(
                'numero_monitores' => $this->numero_monitores
            );
        }
        
        $arrayfecha_lim_soporte = array();
        if ($this->fecha_lim_soporte != null) {
            $arrayfecha_lim_soporte = array(
                'fecha_lim_soporte' => $this->fecha_lim_soporte
            );
        }
        
        $array = array(
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'observaciones' => $this->observaciones,
            'fecha_apertura' => $this->fecha_apertura,
            'cronograma' => $this->cronograma,
            'fecha_cierre' => $this->fecha_cierre,
            'tipo_conv' => $tipo,
            'id_estado' => 'A'
        );
        
        $array = $array + $arrayid_entidad + $arrayid_proyectos + $array_num_monitores + $arrayfecha_lim_soporte;
        $this->insert($array);
        $id = $this->getAdapter()
            ->getDriver()
            ->getLastGeneratedValue("mgc_convocatoria_id_convocatoria_seq");
        
        return $id;
    }

    public function getConcop($id)
    {
        $filter = new StringTrim();
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'descripcion',
                'observaciones',
                'tipo_conv',
                'fecha_apertura',
                'fecha_cierre',
                'id_entidad',
                'id_proyectos',
                'numero_monitores',
                'fecha_lim_soporte',
                'cronograma',
                'id_estado',
                'usuario_crea',
                'fecha_crea',
                'usuario_mod',
                'fecha_mod',
                'hora_cierre',
                'hora_apertura'
            ));
            $select->where(array(
                'id_convocatoria = ?' => $id
            ));
        });
        $row = $resultSet->current();
        
        return $row;
    }

    public function getTipo($id)
    {
        $filter = new StringTrim();
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'tipo_conv'
            ));
            $select->where(array(
                'id_convocatoria = ?' => $id
            ));
        });
        $row = $resultSet->current();
        
        return $row;
    }

    public function addCopiar($id, $data = array(), $arr = array())
    {
        self::cargaAtributos3($data);
        self::cargaAtributos2($arr);
        
        $array = array(
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'observaciones' => $this->observaciones,
            'fecha_apertura' => $this->fecha_apertura,
            'cronograma' => $this->cronograma,
            'fecha_cierre' => $this->fecha_cierre,
            'tipo_conv' => $this->id_tipo_conv,
            'id_estado' => 'A'
        );
        
        $this->insert($array);
        $id = $this->getAdapter()
            ->getDriver()
            ->getLastGeneratedValue("mgc_convocatoria_id_convocatoria_seq");
        
        return $id;
    }

    public function updateEstado($id, $estado)
    {
        $array = array(
            'id_estado' => $estado
        );
        
        $this->update($array, array(
            'id_convocatoria' => $id
        ));
    }

    public function updateConvocatoria($id, $data = array())
    {
        self::cargaAtributos($data);
        
        $arrayid_entidad = array();
        if ($this->id_entidad != null) {
            $arrayid_entidad = array(
                'id_entidad' => $this->id_entidad
            );
        }
        
        $arrayid_estado = array();
        if ($this->id_estado != null) {
            $arrayid_estado = array(
                'id_estado' => $this->id_estado
            );
        }
        
        $arrayid_proyectos = array();
        if ($this->id_proyectos != null) {
            $arrayid_proyectos = array(
                'id_proyectos' => $this->id_proyectos
            );
        }
        
        $array_num_monitores = array();
        if ($this->numero_monitores != null) {
            $array_num_monitores = array(
                'numero_monitores' => $this->numero_monitores
            );
        }
        
        $arrayfecha_lim_soporte = array();
        if ($this->fecha_lim_soporte != null) {
            $arrayfecha_lim_soporte = array(
                'fecha_lim_soporte' => $this->fecha_lim_soporte
            );
        }
        
        $arrayfecha_apertura = array();
        if ($this->fecha_apertura != null) {
            $arrayfecha_apertura = array(
                'fecha_apertura' => $this->fecha_apertura
            );
        }
        
        $arrayfecha_cierre = array();
        if ($this->fecha_cierre != null) {
            $arrayfecha_cierre = array(
                'fecha_cierre' => $this->fecha_cierre
            );
        }
        
        $arrayhora_apertura = array();
        if ($this->hora_apertura != null) {
            $arrayhora_apertura = array(
                'hora_apertura' => $this->hora_apertura
            );
        }
        
        $arrayhora_cierre = array();
        if ($this->hora_cierre != null) {
            $arrayhora_cierre = array(
                'hora_cierre' => $this->hora_cierre
            );
        }
        
        $descripcion = array();
        if ($this->descripcion != null) {
            $descripcion = array(
                'descripcion' => $this->descripcion
            );
        }
        
        $observaciones = array();
        if ($this->observaciones != null) {
            $observaciones = array(
                'observaciones' => $this->observaciones
            );
        }

        $observaciones = array();
        if ($this->observaciones != null) {
            $observaciones = array(
                'observaciones' => $this->observaciones
            );
        }

        $cronoArray = array();
        if ($this->cronograma != null) {
            $cronoArray = array(
                'cronograma' => $this->cronograma
            );
        }
        
        $array = $cronoArray + $arrayid_entidad + $observaciones + $descripcion + $arrayid_proyectos + $array_num_monitores + $arrayid_estado + $arrayfecha_lim_soporte + $arrayfecha_apertura + $arrayfecha_cierre + $arrayhora_apertura + $arrayhora_cierre;
        
        $this->update($array, array(
            'id_convocatoria' => $id
        ));
        
        $s = 'ID=' . $id . ',titulo' . '=' . $this->titulo . ',' . 'descripcion' . '=' . $this->descripcion . ',' . 'observaciones' . '=' . $this->observaciones . ',' . 'fecha_apertura' . '=' . $this->fecha_apertura . ',' . 'hora_apertura' . '=' . $this->hora_apertura . ',' . 'fecha_cierre' . '=' . $this->fecha_cierre . ',' . 'hora_cierre' . '=' . $this->hora_cierre . ',' . 

        'estado' . '=' . $this->id_estado;
        return $s;
    }

    public function idConvocatoria($id_convocatoria, $titulo, $tipo, $estado)
    {
        if ($id_convocatoria != '0' && $titulo == 0 && $tipo == 0 && $estado == 0) {
            $c = $id_convocatoria;
            $rowset = $this->select(function (Where $select) use($c) {
                $select->columns(array(
                    'id_convocatoria'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $c
                ));
            });
            return $rowset->toArray();
        } elseif ($id_convocatoria == 0 && $titulo != '0' && $tipo == 0 && $estado == 0) {
            $t = '%' . $titulo . '%';
            $t = strtoupper($t);
            $rowset = $this->select(function (Where $select) use($t) {
                $select->columns(array(
                    'id_convocatoria'
                ));
                $select->where(array(
                    'upper(titulo) LIKE ?' => $t
                ));
            });
            return $rowset->toArray();
        } elseif ($id_convocatoria == 0 && $titulo == 0 && $tipo != '0' && $estado == 0) {
            $ti = '%' . $tipo . '%';
            $ti = strtoupper($ti);
            $rowset = $this->select(function (Where $select) use($ti) {
                $select->columns(array(
                    'id_convocatoria'
                ));
                $select->where(array(
                    'upper(tipo_conv) LIKE ?' => $ti
                ));
            });
            return $rowset->toArray();
        } elseif ($id_convocatoria == 0 && $titulo == 0 && $tipo == 0 && $estado != '0') {
            $e = '%' . $estado . '%';
            $e = strtoupper($e);
            $rowset = $this->select(function (Where $select) use($e) {
                $select->columns(array(
                    'id_convocatoria'
                ));
                $select->where(array(
                    'upper(id_estado) LIKE ?' => $e
                ));
            });
            return $rowset->toArray();
        } elseif ($id_convocatoria == 0 && $titulo == 0 && $tipo == 0 && $estado == 0) {
            $rowset = $this->select();
        }
        return $rowset->toArray();
    }

    public function reporteConvo($data)
    {
        self::cargaReporte($data);
       // print_r($data);
        
        if ($this->nombre != null) {
            $nombre = '%' . $this->nombre . '%';
            $nombre = strtoupper($nombre);
        } else {
            $nombre = '';
        }
        $ano = $this->ano;
        
        $id_convocatoria = $this->id_convocatoria;
        
        $id_tipo_conv = $this->id_tipo_conv;
        
        $id_estado = $this->id_estado;
        
        if ($nombre != null && $ano == null && $id_convocatoria == null && $id_tipo_conv == null && $id_estado == null) {
            $rowset = $this->select(function (Where $select) use($nombre) {
                $select->columns(array(
                    'id_convocatoria'
                ));
                $select->where(array(
                    'upper(titulo) LIKE ?' => $nombre
                ));
            });
            return $rowset->toArray();
        } elseif ($nombre == '' && $ano != null && $id_convocatoria == null && $id_tipo_conv == null && $id_estado == null) {
            $rowset = $this->select(function (Where $select) use($ano) {
                $select->columns(array(
                    'id_convocatoria'
                ));
                $select->where(array(
                    "trim(to_char(fecha_apertura,'YYYY')) = ?" => $ano
                ));
            });
            return $rowset->toArray();
        } elseif ($nombre == '' && $ano == null && $id_convocatoria != null && $id_tipo_conv == null && $id_estado == null) {
            $rowset = $this->select(function (Where $select) use($id_convocatoria) {
                $select->columns(array(
                    'id_convocatoria'
                ));
                $select->where(array(
                    'id_convocatoria  =?' => $id_convocatoria
                ));
            });
            return $rowset->toArray();
        } elseif ($nombre == '' && $ano == null && $id_convocatoria == null && $id_tipo_conv != null && $id_estado == null) {
            $rowset = $this->select(function (Where $select) use($id_tipo_conv) {
                $select->columns(array(
                    'id_convocatoria'
                ));
                $select->where(array(
                    'upper(tipo_conv)  =?' => $id_tipo_conv
                ));
            });
            return $rowset->toArray();
        } elseif ($nombre == '' && $ano == null && $id_convocatoria == null && $id_tipo_conv == null && $id_estado != null) {
            $rowset = $this->select(function (Where $select) use($id_estado) {
                $select->columns(array(
                    'id_convocatoria'
                ));
                $select->where(array(
                    'id_estado =?' => $id_estado
                ));
            });
            return $rowset->toArray();
        } elseif ($nombre == '' && $ano != null && $id_convocatoria == null && $id_tipo_conv != null && $id_estado == null) {
            $rowset = $this->select(function (Where $select) use($ano, $id_tipo_conv) {
                $select->columns(array(
                    'id_convocatoria'
                ));
                $select->where(array(
                    "trim(to_char(fecha_apertura,'YYYY')) = ?" => $ano,
                    'upper(tipo_conv)  =?' => $id_tipo_conv
                ));
            });
            return $rowset->toArray();
        } elseif ($nombre == '' && $ano != null && $id_convocatoria == null && $id_tipo_conv == null && $id_estado != null) {
            $rowset = $this->select(function (Where $select) use($ano, $id_estado) {
                $select->columns(array(
                    'id_convocatoria'
                ));
                $select->where(array(
                    "trim(to_char(fecha_apertura,'YYYY')) = ?" => $ano,
                    'id_estado  =?' => $id_estado
                ));
            });
            return $rowset->toArray();
        }
    }

    public function reporteConvocatoria($id_convocatoria, $titulo, $tipo, $estado, $fecha, $codigo, $titulo_conv)
    {
        if ($id_convocatoria != 0 && $tipo == null) {
            
            $id = $id_convocatoria;
            
            if ($codigo != 0 && $titulo_conv != 0) {
                
                $rowset = $this->select(function (Where $select) use($id) {
                    $select->columns(array(
                        'id_convocatoria',
                        'titulo'
                    ));
                    $select->where(array(
                        'id_convocatoria  =?' => $id
                    ));
                });
                return $rowset->toArray();
            } elseif ($titulo_conv != 0 && $codigo == 0) {
                
                $rowset = $this->select(function (Where $select) use($id) {
                    $select->columns(array(
                        'titulo'
                    ));
                    $select->where(array(
                        'id_convocatoria  =?' => $id
                    ));
                });
                return $rowset->toArray();
            } elseif ($titulo_conv == 0 && $codigo != 0) {
                
                $rowset = $this->select(function (Where $select) use($id) {
                    $select->columns(array(
                        'id_convocatoria'
                    ));
                    $select->where(array(
                        'id_convocatoria  =?' => $id
                    ));
                });
                return $rowset->toArray();
            }
        }
        
        if ($id_convocatoria == 0 && $tipo != null) {
            
            $id = trim($tipo);
            
            if ($codigo != 0 && $titulo_conv != 0) {
                
                $rowset = $this->select(function (Where $select) use($id) {
                    $select->columns(array(
                        'id_convocatoria',
                        'titulo'
                    ));
                    $select->where(array(
                        'tipo_conv  =?' => $id
                    ));
                });
                return $rowset->toArray();
            } elseif ($titulo_conv != 0 && $codigo == 0) {
                
                $rowset = $this->select(function (Where $select) use($id) {
                    $select->columns(array(
                        'titulo'
                    ));
                    $select->where(array(
                        'tipo_conv  =?' => $id
                    ));
                });
                return $rowset->toArray();
            } elseif ($titulo_conv == 0 && $codigo != 0) {
                
                $rowset = $this->select(function (Where $select) use($id) {
                    $select->columns(array(
                        'id_convocatoria'
                    ));
                    $select->where(array(
                        'tipo_conv  =?' => $id
                    ));
                });
                return $rowset->toArray();
            }
        }
    }

    public function filtroConvocatoria($datos = array())
    {
        self::cargaAtributos($datos);

        $nombre = $this->titulo;
        $fecha_apertura = $this->fecha_apertura;
        $id_convocatoria =  $this->id_convocatoria;
        $id_tipo_conv = $this->id_tipo_conv;
        $id_estado = $this->id_estado;

        if($id_convocatoria==null && $nombre=='' && $fecha_apertura==null && $id_tipo_conv==null && $id_estado==null){
            $rowset=$this->select();
        }else{
            $rowset = $this->select(function (Where $select) use($id_convocatoria, $nombre, $fecha_apertura, $id_tipo_conv, $id_estado) {
                if($id_convocatoria!=null){
                    $tWhere['id_convocatoria  = ?'] = $id_convocatoria;
                }
                if ($nombre!=''){
                    $tWhere['upper(titulo) LIKE ?'] = mb_strtoupper("%".$nombre."%");
                }
                if ($fecha_apertura!=null){
                    $tWhere["fecha_apertura  = ?"] = $fecha_apertura;
                }
                if($id_tipo_conv!=null){
                    $tWhere['tipo_conv = ?'] = $id_tipo_conv;
                }
                if($id_estado!=null){
                    $tWhere['id_estado  = ?']=$id_estado;
                }
                $select->where($tWhere);
            });
        }
        return $rowset->toArray();
    }
    public function filtroConvocatoriaReporte($datos = array()){
        if ($datos["nombre"] != null) {
            $nombre = '%' . $datos["nombre"] . '%';
            $nombre = mb_strtoupper($nombre);
        } else {
            $nombre = '';
        }
        $ano = $datos["ano"];
        $id_convocatoria = $datos["id_convocatoria"];
        $id_tipo_conv = $datos["id_tipo_conv"];
        $id_estado = $datos["id_estado"];

        if($id_convocatoria==null && $nombre=='' && $ano==null && $id_tipo_conv==null && $id_estado==null){
            $rowset=$this->select();
        }else{
            $rowset = $this->select(function (Where $select) use($id_convocatoria, $nombre, $ano, $id_tipo_conv, $id_estado) {
                if($id_convocatoria!=null){
                    $tWhere['id_convocatoria  = ?'] = $id_convocatoria;
                }
                if ($nombre!=''){
                    $tWhere['upper(titulo) LIKE ?'] = $nombre;
                }
                if ($ano!=null){
                    $tWhere["trim(to_char(fecha_apertura,'YYYY')) = ?"] = $ano;
                }
                if($id_tipo_conv!=null){
                    $tWhere['upper(tipo_conv)  = ?'] = $id_tipo_conv;
                }
                if($id_estado!=null){
                    $tWhere['id_estado  = ?']=$id_estado;
                }
                $select->where($tWhere);
            });
        }
        return $rowset->toArray();
    }

    public function getConvocatoria()
    {
        $resultSet = $this->select(function ($select) {
            $select->order(array(
                'id_convocatoria ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function getConvocatoriaMonitores()
    {
        $resultSet = $this->select(function ($select) {
            $select->where(array(
                'tipo_conv = ?' => 'm'
            ));

            $select->order(array(
                'id_convocatoria ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function getConvocatoriaid($id)
    {
        $resultSet = $this->select(array(
            'id_convocatoria' => $id
        ));
        return $resultSet->toArray();
    }

    public function getProyectoId($id)
    {
        $resultSet = $this->select(array(
            '$id_proyectos' => $id
        ));
        return $resultSet->toArray();
    }

    public function getTipoconv($id)
    {
        $filter = new StringTrim();
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'tipo_conv'
            ));
            $select->where(array(
                'id_convocatoria = ?' => $id
            ));
        });
        $row = $resultSet->current();
        
        return $row;
    }

    public function eliminarConvocatoria($id)
    {
        $this->delete(array(
            'id_convocatoria' => $id
        ));
    }

    public function getConvoByTipoYear()
    {
        $sql = "SELECT date_part('year', fecha_apertura) as ano, tipo_conv, count(*) as cantidad FROM mgc_convocatoria group by date_part('year', fecha_apertura), tipo_conv order by date_part('year', fecha_apertura), tipo_conv ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }
}
?>