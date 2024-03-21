<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarusuario\ArticuloshvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Articuloshv;
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

class ArticuloshvController extends AbstractActionController
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
            $not = new Articuloshv($this->dbAdapter);
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
            $ArticuloshvForm = new ArticuloshvForm();
            // adiciona la noticia
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $upload = new \Zend\File\Transfer\Adapter\Http();
            if($id2!=0){
                $resultado=$not->updatArticulos($id2, $data);
                if($upload->isUploaded()){
                    //Archivo cargado
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));
                    if($upload->isValid()){
                        $files = $upload->getFileInfo();
                        foreach($files as $f){
                            $archi=$f["name"];
                        }
                        $resultado = $not->getArticuloById($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/articulos/us_art__".$id."_".$resultado[0]["archivo"]);
                        }
                        $upload->setDestination('./public/images/uploads/articulos/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "us_art__".$id."_".$archi,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->updatearchivoArticuloshv($id2,$archi);

                        $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                        $evento = 'Edición de artículos de investigación : ' . $resultado . ' (aps_hv_articulos)';
                        $ad->addAuditoriadet($evento, $resul);

                        $this->flashMessenger()->addMessage("Artículo actualizado con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Artículo actualizado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Artículo actualizado con éxito.");
                }
            }else{
                //Subir el nuevo archivo
                /* */
                    $upload = new \Zend\File\Transfer\Adapter\Http();
                    $upload->setDestination('./public/images/uploads/articulos/');
                    $files = $upload->getFileInfo();
                    foreach($files as $f){
                        $archi=$f["name"];
                    }

                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "us_art__".$id."_".$archi,'overwrite' => true));
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));
                    
                    if($upload->isValid()){
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->addArticuloshv($data, $id, $archi);

                        $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                        $evento = 'Creación de artículos de investigación : ' . $resultado . ' (aps_hv_articulos)';
                        $ad->addAuditoriadet($evento, $resul);

                        $this->flashMessenger()->addMessage("Artículo agregado con éxito.");
                    }else{
                        $resultado = $not->addArticuloshv($data, $id, "");
                        $this->flashMessenger()->addMessage("Artículo creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                /* */
            }
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editarusuario/index/' . $id);
        } else {
            $ArticuloshvForm = new ArticuloshvForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Articuloshv($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            //$v_pais = $id2;
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
            
            
            $vf = new Agregarvalflex($this->dbAdapter);            
            $ArticuloshvForm->add(array(
                'name' => 'id_pais',
                'type' => 'text',
                'attributes' => array(
                    'id' => 'id_pais'
        /*'onchange' => 'myFunction(this.id);'*/
    ),
                'options' => array(
                    'label' => 'Pais:'
                )
            ));
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(57) as $dept) {
                $op = array(
                    $dept["id_valor_flexible"] => $dept["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $ArticuloshvForm->add(array(
                'name' => 'categoria',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Categorización: ',
                    'value_options' => $opciones
                )
            ));
            
            // ----------------------------------------------------------
            // DEPARTAMENTO
            //$v_depar = $id3;
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
            
            
            $ArticuloshvForm->add(array(
                'name' => 'id_departamento',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_departamento',
                    'disabled' => 'disabled',
                    'onchange' => 'FunctionDepartamentoA();'
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
            $ArticuloshvForm->add(array(
                'name' => 'id_ciudad',
                'type' => 'text',
                'attributes' => array(
                    'id' => 'id_ciudad'
                ),
                'options' => array(
                    'label' => 'Ciudad:'
                )
            ));
                       
            $usuario = new Usuarios($this->dbAdapter);
            $opcionesSelect = array();
            foreach ($usuario->getUsuarios('') as $pa) {
                $op = array(
                    $pa["id_usuario"] => $pa["primer_nombre"] . ' ' . $pa["segundo_nombre"] . ' ' . $pa["primer_apellido"] . ' ' . $pa["segundo_apellido"]
                );
                $opcionesSelect = $opcionesSelect + $op;
            }
            $ArticuloshvForm->add(array(
                'name' => 'id_autor',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_autor'
                ),
                'options' => array(
                    'label' => 'Autor: ',
                    'value_options' => $opcionesSelect
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
            
            if ($dat["id_rol"] != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "editargrupoinv";
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
            
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            if($id2!=0){
                $linea = new Articuloshv($this->dbAdapter);
                $datBase = $linea->getArticuloById($id2);
                if($datBase!=null){
                    $ArticuloshvForm->get('nombre_revista')->setValue($datBase[0]["nombre_revista"]);
                    $ArticuloshvForm->get('nombre_articulo')->setValue($datBase[0]["nombre_articulo"]);
                    $ArticuloshvForm->get('id_pais')->setValue($datBase[0]["id_pais"]);
                    $ArticuloshvForm->get('issn')->setValue($datBase[0]["issn"]);
                    $ArticuloshvForm->get('paginas')->setValue($datBase[0]["paginas"]);
                    $ArticuloshvForm->get('num_paginas')->setValue($datBase[0]["num_paginas"]);
                    $ArticuloshvForm->get('volumen')->setValue($datBase[0]["volumen"]);
                    $ArticuloshvForm->get('serie')->setValue($datBase[0]["serie"]);
                    $ArticuloshvForm->get('id_autor')->setValue($datBase[0]["id_autor"]);
                    $ArticuloshvForm->get('pagina_inicio')->setValue($datBase[0]["pagina_inicio"]);
                    $ArticuloshvForm->get('pagina_fin')->setValue($datBase[0]["pagina_fin"]);
                    $ArticuloshvForm->get('fasciculo')->setValue($datBase[0]["fasciculo"]);
                    $ArticuloshvForm->get('id_ciudad')->setValue($datBase[0]["id_ciudad"]);
                    $ArticuloshvForm->get('ano')->setValue($datBase[0]["ano"]);
                    $ArticuloshvForm->get('mes')->setValue($datBase[0]["mes"]);
                    $ArticuloshvForm->get('categoria')->setValue($datBase[0]["categoria"]);
                    $ArticuloshvForm->get('submit')->setValue("Actualizar");
                }
            }
            $view = new ViewModel(array(
                'form' => $ArticuloshvForm,
                'titulo' => "Artículos de Investigación Hv",
                'url' => $this->getRequest()->getBaseUrl(),
                'datos' => $u->getArticuloshv($id),
                'id' => $id,
                'id2' => $id2,
                'id3' => $id3,
                'menu' => $dat["id_rol"]
            ));
            return $view;
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Articuloshv($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarArticuloshv($id);

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
        $identi=$auth->getStorage()->read();

        $au = new Auditoria($this->dbAdapter);
        $ad = new Auditoriadet($this->dbAdapter);

        $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
        $evento = 'Eliminación de artículos de investigación : ' . $id . ' (aps_hv_articulos)';
        $ad->addAuditoriadet($evento, $resul);

        $this->flashMessenger()->addMessage("Articulo eliminado con exito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarusuario/index/' . $id2);
    }

    public function delAction()
    {
        $ArticuloshvForm = new ArticuloshvForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Articuloshv($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $ArticuloshvForm,
            'titulo' => "Eliminar registro",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Articuloshv($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getArticuloById($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/articulos/us_art__".$id2."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/articulos/us_art__".$id2."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }
}