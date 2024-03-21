<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Application\Convocatoria\EditartablafinForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Tablafinproy;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Tablafinp;

class EditartablafinpController extends AbstractActionController
{
    private $auth;
    public $dbAdapter;
    
    public function __construct() {
        //Cargamos el servicio de autenticacien el constructor
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
        
        $id = (int) $this->params()->fromRoute('id',0);
        $id2 = (int) $this->params()->fromRoute('id2',0);
        
        if($this->getRequest()->isPost()){
            $EditartablafinForm= new EditartablafinForm();
            $id = (int) $this->params()->fromRoute('id',0);
            $this->dbAdapter=$this->getServiceLocator()->get('db1');
            $tablaFinProy = new Tablafinp($this->dbAdapter);
            $auth = $this->auth;
    
            $identi=$auth->getStorage()->read();
            if($identi==false && $identi==null){
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
            }
            $data = $this->request->getPost();
            //print_r($data);
            $resultado=$tablaFinProy->updateTablafin($id,$data);
            
            $urlId = "/application/editarproyecto/index/".$id2;
    
            $conv = new Convocatoria($this->dbAdapter);
            $apli = new Aplicarm($this->dbAdapter);
    
            $id_convocatoria=$apli->getID($id);
            $tipo=$conv->getConvocatoriaid($id_convocatoria["id_convocatoria"]);
    
            if ($resultado==1){
    
                $urlId = "/application/editarproyecto/index/".$id2;
                $this->flashMessenger()->addMessage("Valor actualizado con exito");
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
    
            }else{
                $this->flashMessenger()->addMessage("La noticia no actualizo con exito");
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
            }
        }else
        {
            $EditartablafinForm= new EditartablafinForm();
            $this->dbAdapter=$this->getServiceLocator()->get('db1');
            $tablaFinProy = new Tablafinp($this->dbAdapter);
            $auth = $this->auth;
    
            $identi=$auth->getStorage()->read();
            if($identi==false && $identi==null){
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
            }
    
            $id = (int) $this->params()->fromRoute('id',0);
            
            $idRubro = 0;
            $Periodo = 0;
            $Descripcion = "";
            $Observaciones = "";
            
            foreach ($tablaFinProy->getTablaFinId($id) as $dat) {
                $idRubro = $dat["id_rubro"];
                $Periodo = $dat["periodo"];
                $Descripcion = $dat["descripcion"];
                $Observaciones = $dat["observaciones"];
            }
            
            $vf = new Agregarvalflex($this->dbAdapter);
            foreach ($vf->getArrayvalflexid($idRubro) as $datz) {
                $opz=$datz;
            }
    
            $EditartablafinForm->add(array(
                'name' => 'id_rubro_o',
                'attributes' => array(
                    'type'  => 'text',
                    'value'=>$opz,
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Rubro:',
                ),
            ));
    
            $f=$dat["id_fuente"];
    
            foreach ($vf->getArrayvalflexid($f) as $datz) {
                $opz=$datz;
            }
            
            $EditartablafinForm->add(array(
                'name' => 'id_fuente_o',
                'attributes' => array(
                    'type'  => 'text',
                    'value'=>$opz,
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Fuente:',
                ),
            ));
            	
            // create fields
            
            $EditartablafinForm->add ( array (
            		'name' => 'periodo',
            		'attributes' => array (
            				'type' => 'Number',
            				'placeholder' => 'Ingrese la vigencia',
            				'min' => 1,
            				'max' => 20,
            				'value'=>$Periodo,
            				'required' => 'required'
            		),
            		'options' => array (
            				'label' => 'Ingrese la vigencia de este rubro:'
            		)
            ) );
            
            $EditartablafinForm->add ( array (
            		'name' => 'descripcion',
            		'attributes' => array (
            				'type' => 'textarea',
            				'placeholder' => 'Ingrese la descripción',
            				'lenght' => 5000,
            				'value'=>$Descripcion,
            				'encoding' => 'UTF-8',
            				'required' => 'required'
            		),
            		'options' => array (
            				'label' => 'Descripción :'
            		)
            ) );
            
            $EditartablafinForm->add ( array (
            		'name' => 'observaciones',
            		'attributes' => array (
            				'type' => 'textarea',
            				'placeholder' => 'Ingrese la observaciones',
            				'lenght' => 5000,
            				'value'=>$Observaciones,
            				'encoding' => 'UTF-8',
            				'required' => 'required'
            		),
            		'options' => array (
            				'label' => 'Observaciones :'
            		)
            ) );
    
            //verificar roles
            $per=array('id_rol'=>'');
            $dat=array('id_rol'=>'');
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $permiso = new Permisos($this->dbAdapter);
    
            //me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
    
            if ($dat["id_rol"]!= ''){
                //me verifica el permiso sobre la pantalla
                $pantalla="editartablafinp";
                $panta=0;
                $pt = new Agregarvalflex($this->dbAdapter);
    
    
                foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
                    $panta["id_valor_flexible"];
                }
    
                $permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]);
                foreach ($permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]) as $per) {
                    $per["id_rol"];
                }
            }
            $id2 = (int) $this->params()->fromRoute('id2',0);
            if(true){
                $view = new ViewModel(array('form'=>$EditartablafinForm,
                    'titulo'=>"Editar Tabla Financiación",
                    'id'=>$id,
                    'id2'=>$id2,
                    'url'=>$this->getRequest()->getBaseUrl(),
                    'datos'=>$tablaFinProy->getTablafin($id),
                    'menu'=>$dat["id_rol"]));
                return $view;
            }
            else
            {
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaproyecto/index');
            }
        }
    }
}

?>