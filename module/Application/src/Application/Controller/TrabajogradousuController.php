<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarusuario\TrabajogradousuForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Trabajogradousu;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarautorred;
use Application\Modelo\Entity\Semillero;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class TrabajogradousuController extends AbstractActionController
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
		
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Trabajogradousu($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
			

			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}

			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$TrabajogradousuForm = new TrabajogradousuForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2', 0);
			$upload = new \Zend\File\Transfer\Adapter\Http();
	                
			if($id2!=0){
				$resultado=$not->updateTrabajogradousu($id2, $data);
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
                        $resultado = $not->getTrabajogradousuById($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/formacion/us_for__".$id."_".$id2);
                        }
                        $upload->setDestination('./public/images/uploads/formacion/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "us_for__".$id."_".$id2,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->updatearchivoTrabajogradousu($id2,$archi);

						$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
						$evento = 'Edición de formación : ' . $resultado . ' (aps_trabajogradousu)';
						$ad->addAuditoriadet($evento, $resul);

                        $this->flashMessenger()->addMessage("Formación actualizada con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Formación actualizada sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Producción actualizado con éxito.");
                }
			}else{
				$resultado=$not->addTrabajogradousu($data,$id,"");
				//Subir el nuevo archivo
	            /* */
	                $upload->setDestination('./public/images/uploads/formacion/');
	                $files = $upload->getFileInfo();
	                foreach($files as $f){
	                    $archi=$f["name"];
	                }

	                $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "us_for__".$id."_".$resultado,'overwrite' => true));
	                $upload->setValidators(array(
	                    'Size'  => array('min' => 1, 'max' => 50000000),
	                ));
	                
	                if($upload->isValid()){
	                    $rtn = array('success' => null);
	                    $rtn['success'] = $upload->receive();
	                    $not->updatearchivoTrabajogradousu($resultado,$archi);

						$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
						$evento = 'Creación de formación : ' . $resultado . ' (aps_trabajogradousu)';
						$ad->addAuditoriadet($evento, $resul);

	                    $this->flashMessenger()->addMessage(" agregado con éxito.");
	                }else{
	                    $this->flashMessenger()->addMessage("Formación creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
	                }
	            /* */
			}			
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id);
		}
		else
		{
			$TrabajogradousuForm = new TrabajogradousuForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Trabajogradousu($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

            //Verifica permisos sobre la pantalla
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
			$pantalla="editarusuario";
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

			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2', 0);
			if($id2!=0){
				$datBase = $u->getTrabajogradousuById($id2);
				if($datBase!=null){
					$TrabajogradousuForm->get('nombre_trabajo')->setValue($datBase[0]["nombre_trabajo"]);
					$TrabajogradousuForm->get('id_tipotrabajo')->setValue($datBase[0]["id_tipotrabajo"]);
					$TrabajogradousuForm->get('id_estadotipotrabajo')->setValue($datBase[0]["id_estadotipotrabajo"]);
					$TrabajogradousuForm->get('id_tipoparticipacion')->setValue($datBase[0]["id_tipoparticipacion"]);
					$TrabajogradousuForm->get('id_autor')->setValue($datBase[0]["id_autor"]);
					$TrabajogradousuForm->get('id_investigador')->setValue($datBase[0]["id_investigador"]);
					$TrabajogradousuForm->get('id_institucion')->setValue($datBase[0]["id_institucion"]);
					$TrabajogradousuForm->get('ciudad_trabajo')->setValue($datBase[0]["ciudad_trabajo"]);
					$TrabajogradousuForm->get('id_unidad')->setValue($datBase[0]["id_unidad"]);
					$TrabajogradousuForm->get('id_dependencia')->setValue($datBase[0]["id_dependencia"]."-".$datBase[0]["id_unidad"]);
					$TrabajogradousuForm->get('id_programa')->setValue($datBase[0]["id_programa"]."-".$datBase[0]["id_dependencia"]);
					$TrabajogradousuForm->get('fecha_inicio')->setValue($datBase[0]["fecha_inicio"]);
					$TrabajogradousuForm->get('fecha_fin')->setValue($datBase[0]["fecha_fin"]);
					$TrabajogradousuForm->get('descripcion')->setValue($datBase[0]["descripcion"]);
					$TrabajogradousuForm->get('otra_informacion')->setValue($datBase[0]["otra_informacion"]);
					$TrabajogradousuForm->get('id_formacioninvestigador')->setValue($datBase[0]["id_formacioninvestigador"]);
					$TrabajogradousuForm->get('codigo_proyecto')->setValue($datBase[0]["codigo_proyecto"]);
					$TrabajogradousuForm->get('nombre_proyecto')->setValue($datBase[0]["nombre_proyecto"]);
					$TrabajogradousuForm->get('id_institucion_proyecto')->setValue($datBase[0]["id_institucion_proyecto"]);
					$TrabajogradousuForm->get('ciudad_proyecto')->setValue($datBase[0]["ciudad_proyecto"]);
					$TrabajogradousuForm->get('personas_formadas')->setValue($datBase[0]["personas_formadas"]);
					$TrabajogradousuForm->get('fecha_fin_proyecto')->setValue($datBase[0]["fecha_fin_proyecto"]);
					$TrabajogradousuForm->get('fecha_inicio_proyecto')->setValue($datBase[0]["fecha_inicio_proyecto"]);
					$TrabajogradousuForm->get('descripcion_proyecto')->setValue($datBase[0]["descripcion_proyecto"]);
					$TrabajogradousuForm->get('descripcion_formacion')->setValue($datBase[0]["descripcion_formacion"]);
					$TrabajogradousuForm->get('otra_informacion_proyecto')->setValue($datBase[0]["otra_informacion_proyecto"]);
					$TrabajogradousuForm->get('id_semillero')->setValue($datBase[0]["id_semillero"]);
					$TrabajogradousuForm->get('id_institucion_semillero')->setValue($datBase[0]["id_institucion_semillero"]);
					$TrabajogradousuForm->get('id_rolparticipacion')->setValue($datBase[0]["id_rolparticipacion"]);
					$TrabajogradousuForm->get('fecha_inicio_semillero')->setValue($datBase[0]["fecha_inicio_semillero"]);
					$TrabajogradousuForm->get('fecha_fin_semillero')->setValue($datBase[0]["fecha_fin_semillero"]);
					$TrabajogradousuForm->get('tematica')->setValue($datBase[0]["tematica"]);
					$TrabajogradousuForm->get('descripcion_semillero')->setValue($datBase[0]["descripcion_semillero"]);
					$TrabajogradousuForm->get('submit')->setValue("Actualizar");
				}
			}

			$opciones=array();
			foreach ($pt->getArrayvalores(75) as $dept) {
				$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $TrabajogradousuForm->get('id_tipotrabajo')->setValueOptions($opciones);

            $opciones=array();
			foreach ($pt->getArrayvalores(76) as $dept) {
				$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $TrabajogradousuForm->get('id_estadotipotrabajo')->setValueOptions($opciones);

			$opciones=array();
			foreach ($pt->getArrayvalores(77) as $dept) {
				$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $TrabajogradousuForm->get('id_tipoparticipacion')->setValueOptions($opciones);

            $opciones=array();
			foreach ($pt->getArrayvalores(38) as $dept) {
				$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $TrabajogradousuForm->get('id_institucion')->setValueOptions($opciones);
            $TrabajogradousuForm->get('id_institucion_proyecto')->setValueOptions($opciones);
            $TrabajogradousuForm->get('id_institucion_semillero')->setValueOptions($opciones);

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
            $TrabajogradousuForm->get('id_autor')->setValueOptions($opciones);
			$TrabajogradousuForm->get('id_investigador')->setValueOptions($opciones);
            
            $opciones=array();
			foreach ($pt->getArrayvalores(78) as $dept) {
				$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $TrabajogradousuForm->get('id_formacioninvestigador')->setValueOptions($opciones);

            $opciones=array();
			foreach ($pt->getArrayvalores(55) as $dept) {
				$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $TrabajogradousuForm->get('id_rolparticipacion')->setValueOptions($opciones);

            $opciones=array();
			foreach ($pt->getArrayvalores(23) as $dept) {
				$op=array($dept["id_valor_flexible"] => $dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $TrabajogradousuForm->get('id_unidad')->setValueOptions($opciones);

            $opciones=array();
			foreach ($pt->getArrayvalores(33) as $dept) {
				$op=array($dept["id_valor_flexible"] . '-' . $dept["valor_flexible_padre_id"]  => $dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $TrabajogradousuForm->get('id_dependencia')->setValueOptions($opciones);

            $opciones=array();
			foreach ($pt->getArrayvalores(34) as $dept) {
				$op=array($dept["id_valor_flexible"] . '-' . $dept["valor_flexible_padre_id"]=> $dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $TrabajogradousuForm->get('id_programa')->setValueOptions($opciones);

            $semilleros = new Semillero($this->dbAdapter);
            $opciones=array();
			foreach ($semilleros->getSemilleroinv() as $sem) {
				$op=array($sem["id"]=>$sem["codigo"]."-".$sem["nombre"]);
				$opciones=$opciones+$op;
			}
			ksort($opciones);
            $TrabajogradousuForm->get('id_semillero')->setValueOptions($opciones);

			$view = new ViewModel(
				array(
					'form'=>$TrabajogradousuForm,
					'titulo'=>"Formación",
					'url'=>$this->getRequest()->getBaseUrl(),
					'id'=>$id,
					'id2'=>$id2,
					'menu'=>$dat["id_rol"]
				)
			);
			return $view;
		}
    }

	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Trabajogradousu($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarTrabajogradousu($id);

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
		$evento = 'Eliminación de formación : ' . $id . ' (aps_trabajogradousu)';
		$ad->addAuditoriadet($evento, $resul);		

		$this->flashMessenger()->addMessage("Formación eliminada con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id2);	
	}

	public function delAction(){
		$TrabajogradousuForm = new TrabajogradousuForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Trabajogradousu($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$view = new ViewModel(
			array(
				'form'=>$TrabajogradousuForm,
				'titulo'=>"Eliminar Formación",
				'url'=>$this->getRequest()->getBaseUrl(),
				'id'=>$id,
				'id2'=>$id2
			)
		);
		return $view;
	}

	public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Trabajogradousu($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getTrabajogradousuById($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/formacion/us_for__".$id2."_".$id, 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/formacion/us_for__".$id2."_".$id));
    	$response->setHeaders($headers);
        return $response;
    }
}