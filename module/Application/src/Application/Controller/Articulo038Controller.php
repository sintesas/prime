<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\Articulo038Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Asignareval;
use Application\Modelo\Entity\Evaluar;
use Application\Modelo\Entity\Usuarios;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Lineas;
use Application\Modelo\Entity\Libros;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Cronogramaap;
use Application\Modelo\Entity\Requisitos;
use Application\Modelo\Entity\Aspectoeval;
use Application\Modelo\Entity\Requisitosdoc;
use Application\Modelo\Entity\Requisitosapdoc;
use Application\Modelo\Entity\Propuestainv;
use Application\Modelo\Entity\Rolesconv;
use Application\Modelo\Entity\Archivosconv;
use Application\Modelo\Entity\Url;
use Application\Modelo\Entity\Tablafin;
use Application\Modelo\Entity\Camposadd;
use Application\Modelo\Entity\Tablaequipo;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Gruposparticipantes;
use Application\Modelo\Entity\Tablafinproy;
use Application\Modelo\Entity\Camposaddproy;
use Application\Modelo\Entity\Talentohumano;
use Application\Modelo\Entity\Evaluarproy;
use Application\Modelo\Entity\Mares;
use Application\Modelo\Entity\CargueTH;

class Articulo038Controller extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Aplicar($this->dbAdapter);
		        $this->auth = new AuthenticationService();
			$auth = $this->auth;

$result = $auth->hasIdentity();

			//verifica si esta conectado
			$identi=$auth->getStorage()->read();
			
			

			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}

			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$Articulo038Form = new Articulo038Form();


			//define el campo ciudad
	

			//adiciona la noticia
			$evaluador = new Asignareval($this->dbAdapter);

			$usuario = new Usuarios($this->dbAdapter);
			$proyext = new Proyectosext($this->dbAdapter);
			$eval= new Evaluar($this->dbAdapter);
			//redirige a la pantalla de inicio del controlador
			$view = new ViewModel(array('form'=>$Articulo038Form,
						'titulo'=>"Gestión de Requisitos y Evaluaciones Articulo", 
						'datos'=>$not->filtroAplicar($data),
										'evaluados'=>$eval->getEvaluar(),
						'evaluador'=>$evaluador->getAsignarevalt(),
						'usuario'=>$usuario->getArrayusuarios(),
						'url'=>$this->getRequest()->getBaseUrl(),
										'consulta'=>1,
						'menu'=>$dat["id_rol"]));
			return $view;
		}
		else
		{
			$Articulo038Form = new Articulo038Form();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
$this->dbAdapterMares=$this->getServiceLocator()->get('db3');


			$this->auth = new AuthenticationService();
			$auth = $this->auth;
	
$result = $auth->hasIdentity();

			$identi=$auth->getStorage()->read();


			$filter = new StringTrim();

			//define el campo ciudad


			//verificar roles
			$per=array('id_rol'=>'');
			$dat=array('id_rol'=>'');
$permiso_1='';
$roles_1='';
			$rolusuario = new Rolesusuario($this->dbAdapter);
			$permiso = new Permisos($this->dbAdapter);
		
			//me verifica el tipo de rol asignado al usuario
			$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$roles_1=$dat["id_rol"];
			}
		
			if ($dat["id_rol"]!= ''){
			//me verifica el permiso sobre la pantalla
			$pantalla="grupoinv";
			$panta=0;
			$pt = new Agregarvalflex($this->dbAdapter);


			foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
			$panta["id_valor_flexible"];
			}

			$permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]);
			foreach ($permiso->verificarPermiso($roles_1,$panta["id_valor_flexible"]) as $per) {
				$permiso_1=$per["id_rol"];
			}
			}

$id = (int) $this->params()->fromRoute('id',0);
$this->dbAdapterTH=$this->getServiceLocator()->get('db2');
$usuarios = new Usuarios($this->dbAdapter);

$th = new CargueTH($this->dbAdapterTH);
foreach($th->getUsuariosid($id) as $um){


$existes=0;
foreach($usuarios->getArrayusuarios() as $usa){
	if($usa["documento"]==$um["EMP_CODIGO"]){
		$existes=1;	
	}
}



if($existes==0){

	$usuarios->addUsuariomares(ucwords(strtolower($um["VINCULACION"])),'',ucwords(strtolower($um["EMP_APELLIDO1"])),ucwords(strtolower($um["EMP_APELLIDO2"])),$um["EMP_CODIGO"],$um["EMP_CORREO"]);

}


}


$mr = new Mares($this->dbAdapterMares);


foreach($mr->getUsuariosid($id) as $umar){

$nombre = explode(' ',$umar["NOMBRE"]);
echo $nombre[0];
echo $nombre[1];
echo $nombre[2];
echo $nombre[3];
echo ',';
echo $umar["CORREO"];
echo ',';
echo $umar["CEDULA"];
echo ',';

$existe=0;
foreach($usuarios->getArrayusuarios() as $usa){
	if($usa["documento"]==$umar["CEDULA"]){
		$existe=1;	
	}
}


if($existe==0){
	echo 'el usuario no existe';
	$usuarios->addUsuariomares($nombre[0],$nombre[1],$nombre[2],$nombre[3],$umar["CEDULA"],$umar["CORREO"]);

}else{
	echo 'el usuario existe';
}

}

			if(true){

			$view = new ViewModel(array('form'=>$Articulo038Form,
										'titulo'=>"Gestión de Requisitos y Evaluaciones Articulo",
										'titulo2'=>"Tipos Valores Existentes", 
'mares'=>$mr->getUsuariosid($id),
										'consulta'=>0,
										'menu'=>$dat["id_rol"]));
			return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }


}
