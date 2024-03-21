<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarredinv\IntegrantesredForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Integrantesred;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class IntegrantesredController extends AbstractActionController
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
        
        if ($this->getRequest()->isPost()) {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Integrantesred($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $data = $this->request->getPost();
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
                        $registro = $u->getIntegrantesredById($id2);
                        if($registro != null){
                            unlink("public/images/uploads/ri/integranter/ri_ir__".$id."_".$registro[0]["archivo"]);
                        }
                    }
                    $upload->setDestination('./public/images/uploads/ri/integranter/');
                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_ir__".$id."_".$file_name,'overwrite' => true));
                    $rtn = array('success' => null);
                    $rtn['success'] = $upload->receive();
                }else{
                    $error_file = true;
                }
            }

            if($id2 == "0"){
                $resul = $u->addIntegrantered($data, $id, $file_name);
            }else{
                $resul = $u->updateIntegranteRed($id2, $data, $file_name);
            }
            
            if ($resul == 1) {
                if($id2 == "0"){
                    $this->flashMessenger()->addMessage("Miembro agregado correctamente.");
                }
                else{
                    $this->flashMessenger()->addMessage("Miembro actualizado correctamente.");
                }
                
            } else {
                $this->flashMessenger()->addMessage("El miembro no pudo ser agregado.");
            }
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editarredinv/index/' . $id);
            return $view;
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $IntegrantesredForm = new IntegrantesredForm();
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            if($id2 != "0"){
                $u = new Integrantesred($this->dbAdapter);
                $inte = $u->getIntegrantesredById($id2);
            }

            $id = (int) $this->params()->fromRoute('id', 0);
            $us = new Usuarios($this->dbAdapter);

            $per = array(
                'id_rol' => ''
            );
            $datusu = array(
                'id_rol' => ''
            );
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $permiso = new Permisos($this->dbAdapter);
            
            // me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $datusu) {
                $datusu["id_rol"];
            }
            
            if ($datusu["id_rol"] != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "editarredinv";
                $panta = 0;
                $pt = new Agregarvalflex($this->dbAdapter);
                
                foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
                    $panta["id_valor_flexible"];
                }
                
                $permiso->verificarPermiso($datusu["id_rol"], $panta["id_valor_flexible"]);
                foreach ($permiso->verificarPermiso($datusu["id_rol"], $panta["id_valor_flexible"]) as $per) {
                    $per["id_rol"];
                }
            }
                        // define el campo autor
            $usuario = new Usuarios($this->dbAdapter);
            $opciones = array();
            $a = '';
            
            foreach ($usuario->getUsuarios($a) as $pa) {
                $nombre_completo = $pa["primer_nombre"] . ' ' . $pa["segundo_nombre"] . ' ' . $pa["primer_apellido"] . ' ' . $pa["segundo_apellido"];
                $op = array(
                    $pa["id_usuario"] => $nombre_completo
                );
                $opciones = $opciones + $op;
            }

            if($id2 == "0"){
                $IntegrantesredForm->add(array(
                    'name' => 'id_autor',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'id_autor',
                        'required' => 'required' 
                    ),
                    'options' => array(
                        
                        'label' => 'Autor:',
                        'value_options' => $opciones
                    )
                ));

                $IntegrantesredForm->add(array(
                    'name' => 'filtro_autor',
                    'attributes' => array(
                        'id' => 'filtro_autor',
                        'type' => 'Text',
                        'placeholder' => 'Filtrar',
                        'onkeyup' => 'showHint(this.value)'
                    ),
                    'options' => array(
                        'label' => 'Filtro autor:'
                    )
                ));
            }else{
                $IntegrantesredForm->add(array(
                    'name' => 'id_autor',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'id_autor',
                        'disabled' => true,
                        'value' => $inte[0]["id_integrantered"]
                    ),
                    'options' => array(
                        'label' => 'Autor:',
                        'value_options' => $opciones
                    )
                ));

                $IntegrantesredForm->add(array(
                    'name' => 'filtro_autor',
                    'attributes' => array(
                        'id' => 'filtro_autor',
                        'type' => 'Text',
                        'placeholder' => 'Filtrar',
                        'onkeyup' => 'showHint(this.value)',
                        'disabled' => true
                    ),
                    'options' => array(
                        'label' => 'Filtro autor:'
                    )
                ));


            }

            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(36) as $dept) {
                $op = array(
                    $dept["id_valor_flexible"] => $dept["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }

            if($id2 == "0"){
                $IntegrantesredForm->add(array(
                    'name' => 'tipo_vinculacion',
                    'type' => 'Zend\Form\Element\Select',
                    'options' => array(
                        'label' => 'Tipo de vinculación: ',
                        'value_options' => $opciones
                    )
                ));
            }else{
                $IntegrantesredForm->add(array(
                    'name' => 'tipo_vinculacion',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'value' => $inte[0]["tipo_vinculacion"]
                    ),
                    'options' => array(
                        'label' => 'Tipo de vinculación: ',
                        'value_options' => $opciones
                    )
                ));
            }

            if($id2 == "0"){
                $IntegrantesredForm->add(array(
                    'name' => 'fecha_vinculacion',
                    'attributes' => array(
                        'type'  => 'date',
                        'required' => 'required' ,
                    ),
                    'options' => array(
                        'label' => 'Fecha de vinculación:',
                    ),
                ));

                $IntegrantesredForm->add(array(
                    'name' => 'fin_vinculacion',
                    'attributes' => array(
                        'type'  => 'date'
                    ),
                    'options' => array(
                        'label' => 'Fecha fin de vinculación:',
                    ),
                ));
            }else{
                $IntegrantesredForm->add(array(
                    'name' => 'fecha_vinculacion',
                    'attributes' => array(
                        'type'  => 'date',
                        'disabled' => true,
                        'value' => $inte[0]["fecha_vinculacion"]
                    ),
                    'options' => array(
                        'label' => 'Fecha de vinculación:',
                    ),
                ));

                $IntegrantesredForm->add(array(
                    'name' => 'fin_vinculacion',
                    'attributes' => array(
                        'type'  => 'date',
                        'value' => $inte[0]["fin_vinculacion"]
                    ),
                    'options' => array(
                        'label' => 'Fecha fin de vinculación:',
                    ),
                ));
            }

            $view = new ViewModel(array(
                'form' => $IntegrantesredForm,
                'titulo' => "Agregar miembros de la red de la UPN",
                'red' => $id,
                'datos' => $us->getUsuarios(""),
                'menu' => $datusu["id_rol"]
            ));
            return $view;
        
        }
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Integrantesred($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getIntegrantesredById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/ri/integranter/ri_ir__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/ri/integranter/ri_ir__".$id."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }

    public function asignarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Integrantesred($this->dbAdapter);
        $data = $this->request->getPost();
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $resul = $u->addIntegrantered($id, $id2);
        if ($resul == 1) {
            $this->flashMessenger()->addMessage("Se agregó el miembro a la red.");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/editarredinv/index/' . $id2);
        } else {
            $this->flashMessenger()->addMessage("No se agregó el miembro a la red.");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/editarredinv/index/' . $id2);
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Integrantesred($this->dbAdapter);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarIntegrantered($id);
        $this->flashMessenger()->addMessage("Miembro eliminado con éxito.");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarredinv/index/' . $id2);
    }

    public function delAction()
    {
        $IntegrantesredForm = new IntegrantesredForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $IntegrantesredForm,
            'titulo' => "Eliminar miembro de la red de investigación",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}