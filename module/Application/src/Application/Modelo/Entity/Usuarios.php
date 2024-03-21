<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Mail\Transport\Smtp;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
Use Zend\Mime;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Application\Modelo\Entity\Rolesusuario;


use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;


class Usuarios extends TableGateway
{

    private $primer_nombre;

    private $segundo_nombre;

    private $primer_apellido;

    private $segundo_apellido;

    private $documento;

    private $direccion;

    private $id_ciudad;

    private $telefono;

    private $celular;

    private $email;

    private $fecha_nacimiento;

    private $id_lugar_nacimiento;

    private $id_sexo;

    public $usuario;

    public $contrasena;

    private $id_nacionalidad;

    private $id_estado;

    private $ContrasenaActual;

    private $ContrasenaNueva;

    private $ContrasenaNuevaR;

    protected $_name = 'aps_valores_flexibles';

    private $asunto;

    private $mensaje;

    private $id_usuario;

    private $nombre;

    private $apellido;

    private $archivo;

    private $estado_civil;

    private $institucion;

    private $rol_usuario;

    private $id_unidad_academica;

    private $id_tipo_documento;

    private $id_dependencia_academica;

    private $id_programa_academico;

    private $id_tipo_vinculacion;

    private $direccion_trabajo;

    private $telefono_trabajo;

    private $email2;

    private $id_usu;
    
    private $nombre_completo;

    private $cod_estudiante;

    private $cargo_actual;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Submit"})
     */
    public $submit;

    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('aps_usuarios', $adapter, $databaseSchema, $selectResultPrototype);
    }
    
    // Funcion para cargar los atributos en las variables para ser utilizadas en el modelo
    private function cargaAtributos($datos = array())
    {
        if ($datos["primer_nombre"] != '' || $datos["segundo_nombre"] || $datos["primer_apellido"] || $datos["segundo_apellido"]) {
            $this->nombre_completo = $datos["primer_nombre"] + ' ' + $datos["segundo_nombre"] + ' ' + $datos["primer_apellido"] + ' ' + $datos["segundo_apellido"];
        }
        
        if ($datos["cod_estudiante"] != '') {
            $this->cod_estudiante = $datos["cod_estudiante"];
        }
        if ($datos["cargo_actual"] != '') {
            $this->cargo_actual = $datos["cargo_actual"];
        }
        if ($datos["primer_nombre"] != '') {
            $this->primer_nombre = $datos["primer_nombre"];
        }
        if ($datos["segundo_nombre"] != '') {
            $this->segundo_nombre = $datos["segundo_nombre"];
        }
        if ($datos["rol_usuario"] != '') {
            $this->rol_usuario = $datos["rol_usuario"];
        }
        if ($datos["primer_apellido"] != '') {
            $this->primer_apellido = $datos["primer_apellido"];
        }
        if ($datos["segundo_apellido"] != '') {
            $this->segundo_apellido = $datos["segundo_apellido"];
        }
        if ($datos["documento"] != '') {
            $this->documento = $datos["documento"];
        }
        if ($datos["direccion"] != '') {
            $this->direccion = $datos["direccion"];
        }
        if ($datos["id_ciudad"] != '') {
            $this->id_ciudad = $datos["id_ciudad"];
        }
        if ($datos["direccion"] != '') {
            $this->direccion = $datos["direccion"];
        }
        if ($datos["direccion_trabajo"] != '') {
            $this->direccion_trabajo = $datos["direccion_trabajo"];
        }
        if ($datos["telefono"] != '') {
            $this->telefono = $datos["telefono"];
        }
        if ($datos["telefono_trabajo"] != '') {
            $this->telefono_trabajo = $datos["telefono_trabajo"];
        }
        if ($datos["celular"] != '') {
            $this->celular = $datos["celular"];
        }
        if ($datos["email"] != '') {
            $this->email = $datos["email"];
        }
        if ($datos["email2"] != '') {
            $this->email2 = $datos["email2"];
        }
        if ($datos["fecha_nacimiento"] != '') {
            $this->fecha_nacimiento = $datos["fecha_nacimiento"];
        }
        if ($datos["id_lugar_nacimiento"] != '') {
            $this->id_lugar_nacimiento = $datos["id_lugar_nacimiento"];
        }
        if ($datos["estado_civil"] != '') {
            $this->estado_civil = $datos["estado_civil"];
        }
        if ($datos["institucion"] != '') {
            $this->institucion = $datos["institucion"];
        }
        if ($datos["id_unidad_academica"] != '') {
            $this->id_unidad_academica = $datos["id_unidad_academica"];
        }
        if ($datos["id_dependencia_academica"] != '') {
            $idDependenciaAcademica = explode("-", $datos["id_dependencia_academica"]);
            $this->id_dependencia_academica = $idDependenciaAcademica[0];
        }
        if ($datos["id_programa_academico"] != '') {
            $idDependenciaAcademica = explode("-", $datos["id_programa_academico"]);
            $this->id_programa_academico = $idDependenciaAcademica[0];
        }
        if ($datos["id_tipo_vinculacion"] != '') {
            $this->id_tipo_vinculacion = $datos["id_tipo_vinculacion"];
        }
        if ($datos["id_tipo_documento"] != '') {
            $this->id_tipo_documento = $datos["id_tipo_documento"];
        }
        if ($datos["id_sexo"] != '') {
            $this->id_sexo = $datos["id_sexo"];
        }
        if ($datos["archivo"] != '') {
            $this->archivo = $datos["archivo"];
        }
        if ($datos["usuario"] != '') {
            $this->usuario = $datos["usuario"];
        }
        if ($datos["contrasena"] != '') {
            $this->contrasena = $datos["contrasena"];
        }
        if ($datos["id_nacionalidad"] != '') {
            $this->id_nacionalidad = $datos["id_nacionalidad"];
        }
        if ($datos["id_estado"] != '') {
            $this->id_estado = $datos["id_estado"];
        }
        if ($datos["id_usuario"] != '') {
            $this->id_usuario = $datos["id_usuario"];
        }
        if ($datos["nombre"] != '') {
            $this->nombre = $datos["nombre"];
        }
        if ($datos["apellido"] != '') {
            $this->apellido = $datos["apellido"];
        }
        if ($datos["id_usu"] != '') {
            $this->id_usu = $datos["id_usu"];
        }
    }
    
    // funcion obtener los usuarios de la tabla aps_usuarios
    public function getUsuarios($id)
    {
        if ($id == '') {
            $resultSet = $this->select(function ($select) {
                $select->order(array(
                    'primer_nombre ASC'
                ));
            });
            return $resultSet->toArray();
        } else {
            $resultSet = $this->select(array(
                'id_usuario' => $id
            ));
            return $resultSet->toArray();
        }
        return $resultSet->toArray();
    }

    public function getArrayusuarios()
    {
        $resultSet = $this->select(function ($select) {
                $select->order(array(
                'primer_nombre ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function getArrayusuariosConsultaInvestigadores()
    {
        $resultSet = $this->select(function ($select) {
            $select->where(array(
                "id_estado != 'B'"
            ));
            $select->order(array(
                'primer_nombre ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function updateUni($id, $id2)
    {
        $array = array(
            
            'id_unidad_academica' => $id2
        );
        $this->update($array, array(
            'id_usuario' => $id
        ));
        return $id2;
    }

    public function updateDep($id, $id2)
    {
        $array = array(
            
            'id_dependencia_academica' => $id2
        );
        
        $this->update($array, array(
            'id_usuario' => $id
        ));
        return 1;
    }

    public function getArrayusuariosid($id)
    {
        $filter = new StringTrim();
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'primer_nombre',
                'segundo_nombre',
                'primer_apellido',
                'segundo_apellido',
                'id_tipo_documento',
                'documento',
                'direccion',
                'direccion_trabajo',
                'id_ciudad',
                'id_unidad_academica',
                'id_tipo_vinculacion',
                'celular',
                'email',
                'email2',
                'fecha_nacimiento',
                'id_lugar_nacimiento',
                'id_sexo',
                'telefono',
                'telefono_trabajo',
                'institucion',
                'estado_civil',
                'id_nacionalidad',
                'id_estado',
                'cod_estudiante'
            ));
            $select->where(array(
                'id_usuario = ?' => $id
            ));
        });
        $row = $resultSet->current();
        
        return $row;
    }
    
    // funcion cambiar contrase
    public function cambiarContrasena($id, $datos = array(), $user)
    {
        $this->ContrasenaActual = $datos["ContrasenaActual"];
        $this->ContrasenaNueva = $datos["ContrasenaNueva"];
        $this->ContrasenaNuevaR = $datos["ContrasenaNuevaR"];
        
        if ($this->ContrasenaNueva == $this->ContrasenaNuevaR) {
            $rowset = $this->select(array(
                'id_usuario' => $id,
                'contrasena' => $this->ContrasenaActual
            ));
            $row = $rowset->current();
            if (! $row) {
                return $row;
            } else {
                echo "cambio la contrase";
                $contra = array(
                    'contrasena' => $this->ContrasenaNueva,
                    'usuario_mod' => $user,
                    'fecha_mod' => now
                );
                $this->update($contra, array(
                    'id_usuario' => $id
                ));
                return 2;
            }
        } else {
            // throw new \Exception("las contrase no coinciden");
            return 1;
        }
    }

    public function cambiarContrausuario($datos = array(), $user, $id)
    {
        $this->ContrasenaNueva = $datos["ContrasenaNueva"];
        
        $contra = array(
            'contrasena' => $this->ContrasenaNueva,
            'usuario_mod' => $user,
            'fecha_mod' => now
        );
        $this->update($contra, array(
            'id_usuario' => $id
        ));
        return 1;
    }

    public function updateEstado($id)
    {
        $array = array(
            'id_estado' => 'B'
        );
        $this->update($array, array(
            'id_usuario' => $id
        ));
    }
    
    // funcion actualizar tabla aps_usuarios
    public function updateUintentos($id, $int)
    {
        $array = array(
            'intentos' => $int
        );
        $this->update($array, array(
            'id_usuario' => $id
        ));
    }

    public function updateUsuario($id, $data = array(), $user, $archi)
    {
        self::cargaAtributos($data);
        $arrayid_estado = array();
        $array1 = array();
        $array_intentos = array();
        
        $arrayid_cod_estudiante = array();
        if ($this->cod_estudiante != '0') {
            $arrayid_cod_estudiante = array(
                'cod_estudiante' => $this->cod_estudiante
            );
        }

        $array_id_tipo_documento = array();
        if ($this->id_tipo_documento != '0') {
            $array_id_tipo_documento = array(
                'id_tipo_documento' => $this->id_tipo_documento
            );
        }
        if ($this->id_estado != '') {
            if ($this->id_estado == 'S') {
                $array_intentos = array(
                    'intentos' => 0
                );
            }
            $arrayid_estado = array(
                'id_estado' => $this->id_estado
            );
        }
        
        $arrayid_ciudad = array();
        if ($this->id_ciudad != '0') {
            $arrayid_ciudad = array(
                'id_ciudad' => $this->id_ciudad
            );
        }

        $array_id_tipo_documento = array();
        if ($this->id_tipo_documento != '0') {
            $array_id_tipo_documento = array(
                'id_tipo_documento' => $this->id_tipo_documento
            );
        }

        $arrayid_tipodocumento = array();
        if ($this->id_ciudad != '0') {
            $arrayid_ciudad = array(
                'id_ciudad' => $this->id_ciudad
            );
        }
        
        $array_estado = array();
        if ($this->estado_civil != '0') {
            $array_estado = array(
                'estado_civil' => $this->estado_civil
            );
        }
        
        $arrayid_unidad = array();
        if ($this->id_unidad_academica != '0') {
            $arrayid_unidad = array(
                'id_unidad_academica' => $this->id_unidad_academica
            );
        }
        
        $arrayid_dependencia = array();
        if ($this->id_dependencia_academica != '0') {
            $arrayid_dependencia = array(
                'id_dependencia_academica' => $this->id_dependencia_academica
            );
        }

        $arrayid_prograama = array();
        if ($this->id_programa_academico != '0') {
            $arrayid_prograama = array(
                'id_programa_academico' => $this->id_programa_academico
            );
        }
        
        $arrayid_tipodoc = array();
        if ($this->id_tipo_documento != '0') {
            
            $arrayid_tipodoc = array(
                'id_tipo_documento' => $this->id_tipo_documento
            );
        }
        
        $arrayid_vinculacion = array();
        if ($this->id_tipo_vinculacion != '0') {
            $arrayid_vinculacion = array(
                'id_tipo_vinculacion' => $this->id_tipo_vinculacion
            );
        }
        $arrayid_lugar_nacimiento = array();
        
        if ($this->id_lugar_nacimiento != '0') {
            $arrayid_lugar_nacimiento = array(
                'id_lugar_nacimiento' => $this->id_lugar_nacimiento
            );
        }
        $arrayid_sexo = array();
        
        if ($this->id_sexo != '0') {
            $arrayid_sexo = array(
                'id_sexo' => $this->id_sexo
            );
        }
        $arrayid_nacionalidad = array();
        
        if ($this->id_nacionalidad != '0') {
            $arrayid_nacionalidad = array(
                'id_nacionalidad' => $this->id_nacionalidad
            );
        }
        $arrayfecha_nacimiento = array();
        if ($this->fecha_nacimiento != '') {
            $arrayfecha_nacimiento = array(
                'fecha_nacimiento' => $this->fecha_nacimiento
            );
        }
        
        $arrayarchi = array();
        $arrayfoto = array();
        if ($archi != '') {
            $arrayarchi = array(
                'archivo' => $archi
            );
            $arrayfoto = array(
                'new_archivo' => "Si"
            );
        }

        if($data->cargo_actual==""){
            $data->cargo_actual=0;
        }

        if($data->institucion==""){
            $data->institucion=0;
        }

        if($data->tipo_evaluador == ""){
            $data->tipo_evaluador=0;
        }
        
        $array = array(
            'primer_nombre' => $this->primer_nombre,
            'segundo_nombre' => $this->segundo_nombre,
            'primer_apellido' => $this->primer_apellido,
            'segundo_apellido' => $this->segundo_apellido,
            'documento' => $this->documento,
            'direccion' => $this->direccion,
            'direccion_trabajo' => $this->direccion_trabajo,
            'institucion' => $this->institucion,
            'telefono' => $this->telefono,
            'telefono_trabajo' => $this->telefono_trabajo,
            'celular' => $this->celular,
            'email' => $this->email,
            'email2' => $this->email2,
            'cargo_actual' => $data->cargo_actual,
            'evaluador' => $data->evaluador,
            'tipo_evaluador' => $data->tipo_evaluador,
            'usuario_mod' => $user,
            'fecha_mod' => now
        );
        $array = $array  + $arrayid_ciudad + $arrayid_lugar_nacimiento + $arrayarchi + $array_estado + $arrayid_unidad + $arrayid_dependencia + $arrayid_sexo + $arrayid_tipodoc + $arrayid_nacionalidad + $arrayfecha_nacimiento + $arrayid_estado + $array_intentos + $arrayid_vinculacion + $arrayid_prograama + $arrayid_cod_estudiante + $arrayfoto;
        
        $this->update($array, array(
            'id_usuario' => $id
        ));
    }
    
    // funcion crear un nuevo registro tabla aps_usuarios
    public function addUsuario($data = array(), $user)
    {
        self::cargaAtributos($data);
        $array = array(
            
            'primer_nombre' => $this->primer_nombre,
            'segundo_nombre' => $this->segundo_nombre,
            'primer_apellido' => $this->primer_apellido,
            'segundo_apellido' => $this->segundo_apellido,
            'documento' => $this->documento,
            'direccion' => $this->direccion,
            'id_ciudad' => $this->id_ciudad,
            'telefono' => $this->telefono,
            'celular' => $this->celular,
            'email' => $this->email,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'id_lugar_nacimiento' => $this->id_lugar_nacimiento,
            'id_sexo' => $this->id_sexo,
            'id_tipo_documento' => $this->id_tipo_documento,
            'usuario' => $this->email,
            'contrasena' => $this->documento,
            'id_nacionalidad' => $this->id_nacionalidad,
            'id_estado' => 'S',
            'usuario_crea' => $user,
            'fecha_crea' => now
        );
        
        $this->insert($array);
        return 1;
    }

    public function addUsuariomares($primerNombre, $segundoNombre, $primerApellido, $segundoApellido, $doc, $email)
    {
        echo "addUsuariomares";
        $array = array(
            'primer_nombre' => $primerNombre,
            'segundo_nombre' => $segundoNombre,
            'primer_apellido' => $primerApellido,
            'segundo_apellido' => $segundoApellido,
            'documento' => $doc,
            'email' => $email,
            'usuario' => $email,
            'contrasena' => $doc,
            'id_estado' => 'S',
            'id_unidad_academica' => 150,
            'id_dependencia_academica' => 157,
            'id_tipo_vinculacion' => 101
        );
        
        $this->insert($array);
        return 1;
    }
    
    // funcion obtener usuario por id
    public function getUsuariosid($id)
    {
        $id = (int) $id;
        $rowset = $this->select(array(
            'id_usuario' => $id
        ));
        $row = $rowset->current();
        if (! $row) {
            throw new \Exception("no hay id asociado");
        }
        return $row;
    }
    
    // funcion obtener usuario por nombre
    public function getUsuarionombre($usuario = array())
    {
        self::cargaAtributos($usuario);
        
        if ($this->usuario != '') {
            $nom = trim($this->usuario);
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'id_usuario' => $nom
                ));
            });
            return $rowset->toArray();
        }
        return 1;
    }

    public function enviarCorreoliq()
    {
        $filter = new StringTrim();
        
        $message = new \Zend\Mail\Message();
        $message->setBody('Recuerde su fecha de');
        $message->setFrom('primeCIUP@pedagogica.edu.co');
        $message->setSubject('Fecha de Liquidacion');
        $message->addTo('primeCIUP@pedagogica.edu.co');
        
        $smtOptions = new \Zend\Mail\Transport\SmtpOptions();
        $smtOptions->setHost('relay01.upn.edu.co')->setport(25);
        
        $transport = new \Zend\Mail\Transport\Smtp($smtOptions);
        $transport->send($message);
    }

    public function comprobarCorreo($data = array())
    {
        self::cargaAtributos($data);
        $filter = new StringTrim();
        $resultSet = $this->select(array(
            'email' => $filter->filter($this->email)
        ));
        $row = $resultSet->current();
        
        if ($row != '') {
            return 0;
        } else {
            return 1;
        }
    }

    public function getUsuarioeditar($id)
    {
        $resultSet = $this->select(array(
            'id_usuario' => $id
        ));
        return $resultSet->toArray();
    }
    
    // funcion recuperar contrase
    public function recuperarContra($data = array())
    {
        self::cargaAtributos($data);
        $rowset = $this->select(array(
            'usuario' => $this->usuario,
            'documento' => $this->documento
        ));
        $row = $rowset->current();  
       
        if($row != ""){
            $filter = new StringTrim();
            $message = new \Zend\Mail\Message();
            $message->setBody('su clave es :' . trim($row["contrasena"]) . ' "Si desea puede cambiar la contraseña en el sistema."');
            $message->setFrom($filter->filter($this->usuario));
            $message->setSubject('Recuperacion clave usuario :' . $this->usuario);
            $message->addTo($filter->filter($row["email"]));
            $smtOptions = new \Zend\Mail\Transport\SmtpOptions();
            $smtOptions->setHost('relay01.upn.edu.co')->setport(25);
            $transport = new \Zend\Mail\Transport\Smtp($smtOptions);
            $transport->send($message);   
            return 1;
        }else{
            return 0;
        }
    }
    
    // funcion enviar mensaje al administrador
    public function mensajeAdministrador($datos = array(), $email)
    {
        $filter = new StringTrim();
        $this->asunto = $datos["asunto"];
        $this->mensaje = $datos["mensaje"];
        
        $message = new \Zend\Mail\Message();
        $message->setBody($this->mensaje);
        $message->setFrom($filter->filter($email));
        $message->setSubject($this->asunto);
        $message->addTo('primeCIUP@pedagogica.edu.co');
        
        $smtOptions = new \Zend\Mail\Transport\SmtpOptions();
        $smtOptions->setHost('relay01.upn.edu.co')->setport(25);
        
        $transport = new \Zend\Mail\Transport\Smtp($smtOptions);
        $transport->send($message);
        return 1;
    }
    
    // funcion enviar mensaje a usuario
    public function mensajeUsuario($datos = array(), $email = array(), $archi)
    {
        $filter = new StringTrim();
        $this->asunto = $datos["asunto"];
        $this->mensaje = $datos["mensaje"];
        
        $myImage = 'public/images/uploads/' . $archi;
        $filename = basename($myImage);
        
        $text = new Mime\Part($this->mensaje);
        $text->type = Mime\Mime::TYPE_TEXT;
        $text->charset = 'utf-8';
        
        $fileContent = fopen($myImage, 'r');
        $attachment = new Mime\Part($fileContent);
        $attachment->filename = $filename;
        $attachment->disposition = Mime\Mime::DISPOSITION_ATTACHMENT;
        // Setting the encoding is recommended for binary data
        $attachment->encoding = Mime\Mime::ENCODING_BASE64;
        
        $mimeMessage = new Mime\Message();
        $mimeMessage->setParts(array(
            $text,
            $attachment
        ));
        
        // Adjuntar el archivo al correo
        
        foreach ($email as $t) {
            $x = str_replace(' ', ',', $filter->filter($t["EMAILS"]));
            $x = str_replace(',', ', ', $filter->filter($x));
            
            $message = new \Zend\Mail\Message();
            $message->setBody($mimeMessage);
            $message->setFrom('primeCIUP@pedagogica.edu.co');
            $message->setSubject($this->asunto);
            $message->addTo($x);
            $message->addCc('primeCIUP@pedagogica.edu.co');
            
            $smtOptions = new \Zend\Mail\Transport\SmtpOptions();
            $smtOptions->setHost('relay01.upn.edu.co')->setport(25);
            $transport = new \Zend\Mail\Transport\Smtp($smtOptions);
            $transport->send($message);
        }
    }
    
    // funcion enviar mensaje a roles
    public function mensajeRoles($datos = array(), $roles = array(), $rolesu = array(), $u = array(), $archi)
    {
        $filter = new StringTrim();
        $this->asunto = $datos["asunto"];
        $this->mensaje = $datos["mensaje"];
        foreach ($rolesu as $rolu) {
            if ($datos["usuario"] == $rolu["id_rol"]) {
                foreach ($u as $usuario) {
                    if ($usuario["id_usuario"] == $rolu["id_usuario"]) {
                        $x = str_replace(' ', ',', $filter->filter($usuario["email"]));
                        $x = str_replace(',', ', ', $filter->filter($x));
                        $message = new \Zend\Mail\Message();

                        $myImage = 'public/images/uploads/' . $archi;
                        $filename = basename($myImage);




                        $message->setBody($this->mensaje."  Imagen:".$filename);
                        $message->setFrom('ricardo.sanchez.villabon@gmail.com');
                        $message->setSubject($this->asunto);
                        $message->addTo($x);
                        
                        $smtOptions = new \Zend\Mail\Transport\SmtpOptions();
                        $smtOptions->setHost('relay01.upn.edu.co')->setport(25);
                        
                        $transport = new \Zend\Mail\Transport\Smtp($smtOptions);
                        $transport->send($message);
                    }
                }
            }
        }
        return 1;
    }
    
    public function filtroRedes($datos = array()){
        if($datos==""){
            $resultSet = $this->select();
            return $resultSet->toArray();
        }else{
            $where = array();
            if($datos["nombre"]!=""){
                $nom = '%'.mb_strtoupper($datos["nombre"],'utf-8').'%';
                $where["upper(concat(primer_nombre,' ',segundo_nombre)) LIKE ?"]= $nom;
            }

            if($datos["documento"]!=""){
                $where["documento = ?"] = $datos["documento"];
            }

            if($datos["apellido"]!=""){
                $nom = '%'.mb_strtoupper($datos["apellido"],'utf-8').'%';
                $where["upper(concat(primer_apellido,' ',segundo_apellido)) LIKE ?"]= $nom;
            }

            if($datos["usuario"]!=""){
                $where["upper(usuario) LIKE ?"] = '%'.mb_strtoupper($datos["usuario"],'utf-8').'%';
            }
            
            $rowset = $this->select(function (Where $select) use($where) {
                $select->where($where);
            });
            return $rowset->toArray();
        }
    }

    // funcion para filtrar usuarios
    public function filtroUsuario($datos = array())
    {
        self::cargaAtributos($datos);
        
        $filter = new StringTrim();
        
        if ($this->nombre != '') {
            $x = str_replace(' ', ',', $filter->filter($this->nombre));
            $nom = '%' . mb_strtoupper($x). '%';
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    "upper(concat(primer_nombre,' ',segundo_nombre)) LIKE ?" => $nom
                ));
            });
            return $rowset->toArray();
        }
        
        if ($this->apellido != '') {
            $ape = '%' . $this->apellido . '%';
            $ape = mb_strtoupper($ape);
            $rowset = $this->select(function (Where $select) use($ape) {
                $select->where(array(
                    "upper(concat(primer_apellido,' ',segundo_apellido)) LIKE ?" => '%' . $ape . '%'
                ));
            });
            return $rowset->toArray();
        }
        
        if ($this->usuario != '') {
            $us = '%' . $this->usuario . '%';
            $us = mb_strtoupper($us);
            $rowset = $this->select(function (Where $select) use($us) {
                $select->where(array(
                    'upper(usuario) LIKE ?' => '%' . $us . '%'
                ));
            });
            return $rowset->toArray();
        }
        if ($this->documento != '') {
            $doc = $this->documento;
            $rowset = $this->select(function (Where $select) use($doc) {
                $select->where(array(
                    'documento' => $doc
                ));
            });
            return $rowset->toArray();
        }
        
        if ($this->id_usu != '') {
            $doc = $this->id_usu;
            $rowset = $this->select(function (Where $select) use($doc) {
                $select->where(array(
                    'id_usuario' => $doc
                ));
            });
            return $rowset->toArray();
        }
        
        if ($this->nombre == '' && $this->documento == '' && $this->apellido == '' && $this->usuario == '') {
            $sql = "SELECT id_usuario, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, usuario, documento FROM aps_usuarios order by primer_nombre, primer_apellido ASC;";
            $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
            return  $statement->toArray();
            //$rowset = $this->select();
            //return $rowset->toArray();
        }
    }


    // funcion para filtrar usuarios
    public function filtroUsuarioInvestigadores($datos = array())
    {
        self::cargaAtributos($datos);
        
        $filter = new StringTrim();
        
        if ($this->nombre != '') {
            $x = str_replace(' ', ',', $filter->filter($this->nombre));
            $nom = '%' . mb_strtoupper($x). '%';
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    "upper(concat(primer_nombre,' ',segundo_nombre)) LIKE ?" => $nom,
                    "id_estado != 'B'"
                ));
            });
            return $rowset->toArray();
        }
        
        if ($this->apellido != '') {
            $ape = '%' . $this->apellido . '%';
            $ape = mb_strtoupper($ape);
            $rowset = $this->select(function (Where $select) use($ape) {
                $select->where(array(
                    "upper(concat(primer_apellido,' ',segundo_apellido)) LIKE ?" => '%' . $ape . '%',
                    "id_estado != 'B'"
                ));
            });
            return $rowset->toArray();
        }
        
        if ($this->usuario != '') {
            $us = '%' . $this->usuario . '%';
            $us = mb_strtoupper($us);
            $rowset = $this->select(function (Where $select) use($us) {
                $select->where(array(
                    'upper(usuario) LIKE ?' => '%' . $us . '%',
                    "id_estado != 'B'"
                ));
            });
            return $rowset->toArray();
        }
        if ($this->documento != '') {
            $doc = $this->documento;
            $rowset = $this->select(function (Where $select) use($doc) {
                $select->where(array(
                    'documento' => $doc,
                    "id_estado != 'B'"
                ));
            });
            return $rowset->toArray();
        }
        
        if ($this->id_usu != '') {
            $doc = $this->id_usu;
            $rowset = $this->select(function (Where $select) use($doc) {
                $select->where(array(
                    'id_usuario' => $doc,
                    "id_estado != 'B'"
                ));
            });
            return $rowset->toArray();
        }
        
        if ($this->nombre == '' && $this->documento == '' && $this->apellido == '' && $this->usuario == '') {
            $sql = "SELECT id_usuario, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, usuario, documento FROM aps_usuarios WHERE id_estado != 'B' order by primer_nombre, primer_apellido ASC;";
            $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
            return  $statement->toArray();
            //$rowset = $this->select();
            //return $rowset->toArray();
        }
    }

    public function filtroUsuarioEvaluadores($datos = array())
    {
        self::cargaAtributos($datos);
       
        $filter = new StringTrim();
        
        $where="";
        if($datos["nombre"]!=""){
            $where.= "UPPER(primer_nombre) LIKE '%".mb_strtoupper($datos->nombre)."%'  OR UPPER(segundo_nombre) LIKE '%".mb_strtoupper($datos->nombre)."%' OR UPPER(primer_apellido) LIKE '%".mb_strtoupper($datos->nombre)."%'  OR UPPER(segundo_apellido) LIKE '%".mb_strtoupper($datos->nombre)."%'";
        }

        if($datos["documento"]!=""){
            if($where!=""){ $where.= " OR ";}
            $where.= "documento='".$datos->documento."'";
        }

        if($datos["usuario"]!=""){
            if($where!=""){ $where.= " OR ";}
            $where.= "UPPER(usuario)='".mb_strtoupper($datos->usuario)."'";
        }

        if($datos["tipo_evaluador"]!=""){
            if($where!=""){ $where.= " OR ";}
            $where.= "UPPER(tipo_evaluador)='".mb_strtoupper($datos->tipo_evaluador)."'";
        }
        
        if($where==""){
            $where= "evaluador='Si'";
        }else{
            $where = "(".$where.")"." AND evaluador='Si'";
        }

        $sql = "SELECT id_usuario, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, usuario, documento FROM aps_usuarios WHERE ".$where.";";
            $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
            return  $statement->toArray();
            

    }
    
    // funcion para filtrar usuarios para agregar integrantes
    public function filtroUsuarioIntegrantes($datos = array())
    {
        self::cargaAtributos($datos);
        $primer_nombre = $this->primer_nombre;
        $segundo_nombre = $this->segundo_nombre;
        $primer_apellido =  $this->primer_apellido;
        $segundo_apellido = $this->segundo_apellido;
        $documento = $this->documento;
        $usuario = $this->usuario;

        if($primer_nombre=='' && $segundo_nombre=='' && $primer_apellido=='' && $segundo_apellido=='' && $usuario=='' && $documento==null){
            $rowset=$this->select();
        }else{
            $rowset = $this->select(function (Where $select) use($primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $documento, $usuario) {
                if ($primer_nombre!=''){
                    $tWhere['upper(primer_nombre) LIKE ?'] = mb_strtoupper("%".$primer_nombre."%");
                }
                if ($segundo_nombre!=''){
                    $tWhere['upper(segundo_nombre) LIKE ?'] = mb_strtoupper("%".$segundo_nombre."%");
                }
                if ($primer_apellido!=''){
                    $tWhere['upper(primer_apellido) LIKE ?'] = mb_strtoupper("%".$primer_apellido."%");
                }
                if ($segundo_apellido!=''){
                    $tWhere['upper(segundo_apellido) LIKE ?'] = mb_strtoupper("%".$segundo_apellido."%");
                }
                if ($usuario!=''){
                    $tWhere['upper(usuario) LIKE ?'] = mb_strtoupper("%".$usuario."%");
                }
                if($documento!=null){
                    $tWhere['documento = ?'] = $documento;
                }
                $select->where($tWhere);
            });
        }
        return $rowset->toArray();
    }

    public function getUsuariosByEstado()
    {
        $sql = "SELECT id_estado, count(*) as cantidad FROM aps_usuarios group by id_estado order by id_estado ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }

    public function fetchFilter($datos = array())
    {
        $select = new Select('aps_usuarios');
        $select->columns(array(
            'id_usuario',
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido'
        ));
        $sql=array();
        if($datos->nombre!=""){
            $mbStr = '%'.mb_strtoupper($datos->nombre).'%';
            $sql+=array('upper(primer_nombre) LIKE ?' => $mbStr);
            //$sql+=array('upper(primer_nombre) LIKE ?' => $mbStr);
        }

        if($datos->apellido!=""){
            $mbStr = '%'.mb_strtoupper($datos->apellido).'%';
            $sql+=array('upper(primer_apellido) LIKE ?' => $mbStr);
            //$sql+=array('upper(segundo_apellido) LIKE ?' => $mbStr);
        }

        if($datos->documento!=""){
            $mbStr = $datos->documento;
            $sql+=array('documento = ?' => $mbStr);
        }

        if($datos->usuario!=""){
            $mbStr = '%'.mb_strtoupper($datos->usuario).'%';
            $sql+=array('upper(usuario) LIKE ?' => $mbStr);
        }

        if($sql){//array empty
            $select->where($sql);
        }        
        $select->order('primer_nombre ASC');
        $resultSetPrototype = new ResultSet();
        $paginatorAdapter = new DbSelect($select, $this->getAdapter(), $resultSetPrototype);
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    public function fetchAll()
    {
        $select = new Select('aps_usuarios');
        $select->columns(array(
            'id_usuario',
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido',
            'usuario',
            'documento'
        ));
        $select->order('primer_nombre ASC');
        $resultSetPrototype = new ResultSet();
        $paginatorAdapter = new DbSelect($select, $this->getAdapter(), $resultSetPrototype);
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    public function fetchFilterUsuarios($datos = array(), $usuarios = array())
    {
        $select = new Select('aps_usuarios');
        $select->columns(array(
            'id_usuario',
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido',
            'usuario',
            'documento',
            'id_estado'
        ));
        $sql=array();
        if($datos->nombre!=""){
            $mbStr = '%'.mb_strtoupper($datos->nombre).'%';
            $sql+=array("upper(concat(primer_nombre,' ',segundo_nombre)) LIKE ?" => $mbStr);
        }

        if($datos->apellido!=""){
            $mbStr = '%'.mb_strtoupper($datos->apellido).'%';
            $sql+=array("upper(concat(primer_apellido,' ',segundo_apellido)) LIKE ?" => $mbStr);
        }

        if($datos->documento!=""){
            $mbStr = $datos->documento;
            $sql+=array('documento = ?' => $mbStr);
        }

        if($datos->usuario!=""){
            $mbStr = '%'.mb_strtoupper($datos->usuario).'%';
            $sql+=array('upper(usuario) LIKE ?' => $mbStr);
        }

        if($datos->id_usu!=""){
            $sql+=array('id_usuario = ?' => $datos->id_usu);
        }

        if($datos->id_estado!=""){
            $sql+=array('id_estado = ?' => $datos->id_estado);
        }

        if($sql){//array empty
            if($datos->rol_usuario != "" || $datos->id_grupo_inv != ""){
                $select->where($sql)->where->and->in("id_usuario",  $usuarios);
            }else{
                $select->where($sql);
            }
        }else{
            if($datos->rol_usuario != "" || $datos->id_grupo_inv != ""){
                $select->where->in("id_usuario",  $usuarios);
            }
        }       
        //$usuarios = array(1, 2);
        //$select->where->in("id_usuario",  $usuarios);

        $select->order('primer_nombre ASC');
        $resultSetPrototype = new ResultSet();
        $paginatorAdapter = new DbSelect($select, $this->getAdapter(), $resultSetPrototype);
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    public function cambiarUsuario($datos = array(), $user, $id)
    {        
        $usuario = trim($datos["usuario"]);
        $contra = array(
            'usuario' => $usuario,
            'usuario_mod' => $user,
            'fecha_mod' => now
        );
        $this->update($contra, array(
            'id_usuario' => $id
        ));
        return 1;
    }
}
?>