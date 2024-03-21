<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarusuario\FormacionacahvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Formacionacahv;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class FormacionacahvController extends AbstractActionController
{

    private $auth;

    public $dbAdapter;

    public function __construct()
    {
        // Cargamos el servicio de autenticacien el constructor
        $this->auth = new AuthenticationService();
    }

    public function indexAction()
    {
        $this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
        $permisos = new Permisos($this->dbAdapter);
        $identi = print_r($auth->getStorage()->read()->id_usuario,true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
        if($resultadoPermiso==0){
            $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }

        function get_real_ip()
		{
			if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				return $_SERVER["HTTP_CLIENT_IP"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
				return $_SERVER["HTTP_X_FORWARDED"];
			} elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_FORWARDED"])) {
				return $_SERVER["HTTP_FORWARDED"];
			} else {
				return $_SERVER["REMOTE_ADDR"];
			}
		}

        $au = new Auditoria($this->dbAdapter);
        $ad = new Auditoriadet($this->dbAdapter);
        
        if ($this->getRequest()->isPost()) {
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Formacionacahv($this->dbAdapter);
            $auth = $this->auth;
            
            // verifica si esta conectado
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            // obtiene la informacion de las pantallas
            $data = $this->request->getPost();
            $FormacionacahvForm = new FormacionacahvForm();
            // adiciona la noticia
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            
            $upload = new \Zend\File\Transfer\Adapter\Http();            

            $file_name = "";
            $error_file = false;
            if($upload->isUploaded()){
                //Archivo cargado
                $upload->setValidators(array(
                    'Size'  => array('min' => 1, 'max' => 50000000),
                ));
                if($upload->isValid()){
                    $files = $upload->getFileInfo();
                    foreach($files as $f){
                        $file_name=$f["name"];
                    }
                    if($id2!=0){
                        $registro = $not->getFormacionacahvById($id2);
                        if($registro != null){
                            unlink("public/images/uploads/hv/formaciona/hv_fa__".$id."_".$registro[0]["archivo"]);
                        }
                    }
                    $upload->setDestination('./public/images/uploads/hv/formaciona/');
                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "hv_fa__".$id."_".$file_name,'overwrite' => true));
                    $rtn = array('success' => null);
                    $rtn['success'] = $upload->receive();
                }else{
                    $error_file = true;
                }
            }

            if($id2!=0){
                $resultado=$not->updateFormacionacahv($id2, $data, $file_name);
                $evento = 'Edición de formación académica : ' . $resultado . ' (aps_hv_formacion_aca)';
            }else{
                $resultado = $not->addFormacionacahv($data, $id, $file_name);
                $evento = 'Creación de formación académica : ' . $resultado . ' (aps_hv_formacion_aca)';
            }    
            
            // redirige a la pantalla de inicio del controlador
            if ($resultado == 1) {
                if($error_file){
                    $this->flashMessenger()->addMessage("Formación creada sin archivo adjunto. Recuerde que el tamaño máximo del archivo es de 25MB.");
                }else{
                    $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);					
					$ad->addAuditoriadet($evento, $resul);

                    $this->flashMessenger()->addMessage("Formacion academica creada con exito");
                }
            } else {
                $this->flashMessenger()->addMessage("La creacion de la formacion academica fallo");
            }
            return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editarusuario/index/' . $id);
        } else {
            $FormacionacahvForm = new FormacionacahvForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Formacionacahv($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            $id = (int) $this->params()->fromRoute('id', 0);
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            
            $id2 = (int) $this->params()->fromRoute('id2', 1);
            
            
            // ----------------------------------------------------------
            // PAIS
            
            $vf = new Agregarvalflex($this->dbAdapter);
            //$resvf = $vf->getValoresflexid($v_pais);
            $opciones = array();
            foreach ($vf->getArrayvalores(20) as $xx) {
                $op = array(
                    "" => ""
                );
                $op = $op + array(
                    $xx["id_valor_flexible"] => $xx["descripcion_valor"]
                );
                
                $opciones = $opciones + $op;
            }
            
            $FormacionacahvForm->add(array(
                'name' => 'id_pais',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_pais',
                    'onchange' => 'FunctionPais();'
                    /*'onchange' => 'myFunction(this.id);'*/
                ),
                'options' => array(
                    'label' => 'Pais : ',
                    'value_options' => $opciones
                )
            ));

            $vf = new Agregarvalflex($this->dbAdapter);
            //$resvf = $vf->getValoresflexid($v_pais);
            $opciones = array();
            foreach ($vf->getArrayvalores(59) as $xx) {
                $op = array();
                $op = $op + array(
                    $xx["id_valor_flexible"] => $xx["descripcion_valor"]
                );
                
                $opciones = $opciones + $op;
            }
             $FormacionacahvForm->add(array(
                'name' => 'tipo_formacion',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'tipo_formacion'
                ),
                'options' => array(
                    'label' => 'Modalidad/Tipo de formación:',
                    'value_options' => $opciones
                )
            ));
            // ----------------------------------------------------------
            // DEPARTAMENTO
            
            $id3 = (int) $this->params()->fromRoute('id3', 1);
            
            
            // define el campo departamento
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(22) as $xx) {
                
                $op = array(
                    "" => ""
                );
                
                $op = $op + array(
                    $xx["id_valor_flexible"] . '-' . $xx["valor_flexible_padre_id"] => $xx["descripcion_valor"]
                );
                
                $opciones = $opciones + $op;
            }
            
            
            $FormacionacahvForm->add(array(
                'name' => 'id_departamento',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_departamento',
                    'disabled' => 'disabled',
                    'onchange' => 'FunctionDepartamento();'
                ),
                'options' => array(
                    'label' => 'Departamento : ',
                    'value_options' => $opciones
                )
            ));
            
            // ----------------------------------------------------------
            // CIUDAD
            
            // define el campo ciudad
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones1 = array();
            foreach ($vf->getArrayvalores(1) as $xx) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $xx["id_valor_flexible"] . '-' . $xx["valor_flexible_padre_id"] => $xx["descripcion_valor"]
                );
                $opciones1 = $opciones1 + $op;
            }
            $FormacionacahvForm->add(array(
                'name' => 'id_ciudad',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_ciudad',
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Ciudad : ',
                    'value_options' => $opciones1
                )
            ));
            
            // verificar roles
            $per = array(
                'id_rol' => ''
            );
            $dat = array(
                'id_rol' => ''
            );
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $permiso = new Permisos($this->dbAdapter);
            
            // me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            $opciones = array('' => '');
            foreach ($vf->getArrayvalores(38) as $datFlex) {                
                $op = array(
                    $datFlex["id_valor_flexible"] => $datFlex["descripcion_valor"]
                );  
                $opciones = $opciones  + $op;
            }

            $FormacionacahvForm->get('institucion')->setValueOptions($opciones);

            if ($dat["id_rol"] != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "editarusuario";
                $panta = 0;
                $pt = new Agregarvalflex($this->dbAdapter);
                
                foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
                    $panta["id_valor_flexible"];
                }
                
                $permiso->verificarPermiso($dat["id_rol"], $panta["id_valor_flexible"]);
                foreach ($permiso->verificarPermiso($dat["id_rol"], $panta["id_valor_flexible"]) as $per) {
                    $per["id_rol"];
                }
            }
            
            if (true) {
                $id = (int) $this->params()->fromRoute('id', 0);
                $id2 = (int) $this->params()->fromRoute('id2', 0);
                if($id2!=0){
                    $datBase = $u->getFormacionacahvById($id2);
                    if($datBase!=null){
                        $FormacionacahvForm->get('tipo_formacion')->setValue($datBase[0]["tipo_formacion"]);
                        $FormacionacahvForm->get('nombre_formacion')->setValue($datBase[0]["nombre_formacion"]);
                        $FormacionacahvForm->get('titulo_formacion')->setValue($datBase[0]["titulo_obtenido"]);
                        $FormacionacahvForm->get('institucion')->setValue($datBase[0]["institucion"]);
                        $FormacionacahvForm->get('pais')->setValue($datBase[0]["id_pais"]);
                        $FormacionacahvForm->get('ciudad')->setValue($datBase[0]["id_ciudad"]);
                        $FormacionacahvForm->get('fecha_inicio')->setValue($datBase[0]["fecha_inicio"]);
                        $FormacionacahvForm->get('fecha_fin')->setValue($datBase[0]["fecha_fin"]);
                        $FormacionacahvForm->get('fecha_grado')->setValue($datBase[0]["fecha_grado"]);
                        $FormacionacahvForm->get('horas')->setValue($datBase[0]["horas"]);
                        $FormacionacahvForm->get('tipo_formacion')->setValue($datBase[0]["tipo_formacion"]);
                        $FormacionacahvForm->get('submit')->setValue("Actualizar");
                    }
                }
                $view = new ViewModel(array(
                    'form' => $FormacionacahvForm,
                    'titulo' => "Formación Académica",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'datos' => $u->getFormacionacahv($id),
                    'id' => $id,
                    'id2' => $id2,
                    'id3' => $id3,
                    'menu' => $dat["id_rol"]
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
        }
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Formacionacahv($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getFormacionacahvById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/hv/formaciona/hv_fa__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/hv/formaciona/hv_fa__".$id."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Formacionacahv($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarFormacionacahv($id);

        function get_real_ip()
		{
			if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				return $_SERVER["HTTP_CLIENT_IP"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
				return $_SERVER["HTTP_X_FORWARDED"];
			} elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_FORWARDED"])) {
				return $_SERVER["HTTP_FORWARDED"];
			} else {
				return $_SERVER["REMOTE_ADDR"];
			}
		}

        $auth = $this->auth;
        $identi = $auth->getStorage()->read();

        $au = new Auditoria($this->dbAdapter);
        $ad = new Auditoriadet($this->dbAdapter);

        $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
        $evento = 'Eliminación de formación académica : ' . $id . ' (aps_hv_formacion_com)';
        $ad->addAuditoriadet($evento, $resul);

        $this->flashMessenger()->addMessage("Formacion academica eliminada con exito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarusuario/index/' . $id2);
    }

    public function delAction()
    {
        $FormacionacahvForm = new FormacionacahvForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Formacionacahv($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $FormacionacahvForm,
            'titulo' => "Eliminar registro",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}