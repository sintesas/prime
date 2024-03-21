<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Mail\Transport\Smtp;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Asignareval extends TableGateway
{
    
    // variables de la tabla
    private $id_aplicar;

    private $id_usuario;

    private $descripcion;

    private $id_estado;

    private $objetivo;

    private $fecha_maxima;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgc_evaluador_conv', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->id_aplicar = $datos["id_aplicar"];
        $this->id_usuario = $datos["id_usuario"];
        $this->id_estado = $datos["id_estado"];
        $this->descripcion = $datos["descripcion"];
        $this->objetivo = $datos["objetivo"];
        $this->fecha_maxima = $datos["fecha_maxima"];
    }

    public function addAsignareval($id, $id2)
    {
        $array = array(
            'id_aplicar' => $id,
            'id_usuario' => $id2
        );
        $this->insert($array);
        return 1;
    }

    public function getAsignar($id, $id2)
    {
        $filter = new StringTrim();
        $resultSet = $this->select(function ($select) use($id, $id2)
        {
            $select->columns(array(
                'id_evaluador'
            ));
            $select->where(array(
                'id_aplicar = ?' => $id,
                'id_usuario = ?' => $id2
            ));
        });
        $row = $resultSet->current();
        
        return $row;
    }

    public function getAsignarevalt()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function updateAsignareval($id, $data = array())
    {
        self::cargaAtributos($data);
        
        $arrayfecha = array();
        if ($this->fecha_maxima != null) {
            $arrayfecha = array(
                'fecha_maxima' => $this->fecha_maxima
            );
        }
        
        $arraydescripcion = array();
        if ($this->descripcion != null) {
            $arraydescripcion = array(
                'descripcion' => $this->descripcion
            );
        }
        $arrayfecha = $arrayfecha + $arraydescripcion;
        $this->update($arrayfecha, array(
            'id_evaluador' => $id
        ));
    }

    public function enviarcorreoEval($correo, $nombre, $data = array())
    {
        self::cargaAtributos($data);
        $filter = new StringTrim();
        
        $x = str_replace(' ', ',', $filter->filter($correo));
        $x = str_replace(',', ', ', $filter->filter($x));
        
        $n = str_replace(' ', ',', $filter->filter($nombre));
        $n = str_replace(',', ', ', $filter->filter($n));
        
        $message = new \Zend\Mail\Message();
        $message->setBody('Recuerde que debe evaluar el proyecto ' . $n . ' plazo maximo ' . $this->fecha_maxima . '. Ingrese a la siguiente URL http://pgil2.pedagogica.edu.co/UPNp/public/application/editaraplicar/index/47');
        $message->setFrom('ricardo.sanchez.villabon@gmail.com');
        $message->setSubject('Evaluacion propuesta investigacion');
        $message->addTo($x);
        
        $smtOptions = new \Zend\Mail\Transport\SmtpOptions();
        $smtOptions->setHost('relay01.upn.edu.co')->setport(25);
        
        $transport = new \Zend\Mail\Transport\Smtp($smtOptions);
        $transport->send($message);
    }

    public function getAsignareval($id)
    {
        $resultSet = $this->select(array(
            'id_aplicar' => $id
        ));
        return $resultSet->toArray();
    }

    public function eliminarAsignareval($id)
    {
        $this->delete(array(
            'id_integrantes' => $id
        ));
    }

    public function getAsignarIndex($id_usuario)
    {
        $sql = "SELECT * FROM mgc_evaluador_conv WHERE descripcion IS NULL AND id_usuario=".$id_usuario." ORDER BY id_evaluador DESC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }
}
?>