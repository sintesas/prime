<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Repositorio\RepositorioForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Repositorio;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Agregarvalfelx;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Agregarautorrepositorio;

class RepositorioController extends AbstractActionController
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
        # Para pantallas accesadas por el menu, debo reiniciar el navegador
        session_start();
        if(isset($_SESSION['navegador'])){unset($_SESSION['navegador']);}
        
        $this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;

        $permisos = new Permisos($this->dbAdapter);
        if ($auth->getStorage()->read() == false && $auth->getStorage()->read() == null) {
            $identi = new \stdClass();
                $identi->id_usuario = '0';
        }
        else{
            $identi = print_r($auth->getStorage()->read()->id_usuario,true);
            $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
            if($resultadoPermiso==0){
                $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
            }
        }

        $datosRepositorio = array();
      
      
        $RepositorioForm = new RepositorioForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');

        $pt = new Agregarvalflex($this->dbAdapter);
        $auth = $this->auth;
        $identi = $auth->getStorage()->read();
        if ($identi == false && $identi == null) {
            $identi = new \stdClass();
            $identi->id_usuario = '0';
            /*return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/login/index');
            */
        }
        
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
        
         $us = new Usuarios($this->dbAdapter);
            $a='';
            $opciones=array();
            foreach ($us->getUsuarios($a) as $datoU) {
                $op=array($datoU["id_usuario"] => $datoU["primer_nombre"]." ".$datoU["segundo_nombre"]." ".$datoU["primer_apellido"].' '.$datoU["segundo_apellido"]);
                $opciones=$opciones+$op;
            }
            $RepositorioForm->add(array(
                'name' => 'autor',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'autor'
                ),
                'size' => 25,
                'options' => array(
                    'label' => 'Autor: ',
                    'empty_option' => 'Seleccione el autor',
                    'value_options' => $opciones,
                ),
            ));

        if ($dat["id_rol"] != '') {
            // me verifica el permiso sobre la pantalla
            $pantalla = "repositorio";
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


         $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(24) as $uni) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $RepositorioForm->add(array(
                'name' => 'id_tipo',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Tipo de documento: ',
                    'value_options' => $opciones
                )
            ));
        //define campos lista que apuntan a valores flexibles
           

        $usua = new Usuarios($this->dbAdapter);
        $valflex = new Agregarvalflex($this->dbAdapter);
        $d = '';
        $u = new Repositorio($this->dbAdapter);
        $elementosRepo = array();
        $autoresrepositorio = new Agregarautorrepositorio($this->dbAdapter);
        $data = array();
        if ($this->getRequest()->isPost()){
            $data = $this->request->getPost();
            $datosRepositorio = $u->getRepositorioFiltro($data);
            if($data["autor"]!=""){
                $autoRepo = $autoresrepositorio->getAgregarautorrepositorioFiltro($data["autor"]);
                foreach ($datosRepositorio as $datoRepo) {
                    foreach ($autoRepo as $datoAuto) {
                        if($datoRepo["id_repositorio"] == $datoAuto["id_repositorio"]){
                            array_push($elementosRepo, $datoRepo); 
                        }                
                    }
                }
            }else{
                 $elementosRepo = $datosRepositorio;
            }
        }else{
            $elementosRepo = $u->getRepositoriot();    
        }
        $view = new ViewModel(array(
            'form' => $RepositorioForm,
            'titulo' => "Repositorio",
            'url' => $this->getRequest()->getBaseUrl(),
            'datosRepositorio' => $elementosRepo,
            'd_user' => $usua->getUsuarios($d),
            'd_val' => $valflex->getValoresf(),
            'id_user' => $identi->id_usuario,
            'menu' => $dat["id_rol"],
            'autoresrepositorio' => $autoresrepositorio->getAutoresrepositoriot(),
            'data'=>$data
        ));
        return $view;
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Repositorio($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        
        $data = $u->getRepositorioid($id);
        
        foreach ($data as $d) {
            $d["archivo"];
        }
        
        $fileName = $d["archivo"];
        
        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen("public/images/uploads/" . $filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
            ->addHeaderLine('Content-Length', filesize("public/images/uploads/" . $filter->filter($fileName)));
        
        $response->setHeaders($headers);
        return $response;
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Repositorio($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $u->eliminarParticipacioneventosred($id);
        $this->flashMessenger()->addMessage("Documento eliminado con éxito.");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/repositorio/index');
    }

    public function delAction()
    {
        $ArchivosForm = new RepositorioForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Repositorio($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);        
        $view = new ViewModel(array(
            'form' => $ArchivosForm,
            'titulo' => "Eliminar repositorio",
            'url' => $this->getRequest()->getBaseUrl(),
            'id' => $id
        ));
        return $view;
    }

}