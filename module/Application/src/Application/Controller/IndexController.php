<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as localStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Index\IndexForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Noticias;
use Application\Modelo\Entity\Eventos;
use Application\Modelo\Entity\Asignareval;
use Application\Modelo\Entity\Evaluar;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Multimedia;
use Application\Modelo\Entity\Documentosvinculados;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Redinvestigacion;
use Application\Modelo\Entity\Semillero;

class indexController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {
    	session_start();
    	$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$auth = $this->auth;

		$permisos = new Permisos($this->dbAdapter);
    	$identi = print_r($auth->getStorage()->read()->id_usuario,true);
		$resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
    	if($resultadoPermiso==0){
    		$this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
    		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
    	}

    	# Para pantallas accesadas por el menu, debo reiniciar el navegador
    	
    	if(isset($_SESSION['navegador'])){
    		unset($_SESSION['navegador']);
    	}
    	
		$IndexForm = new IndexForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
	     	$this->auth = new AuthenticationService();
        	$auth = $this->auth;

        	$identi=$auth->getIdentity();

		//verificar roles
		$per=array('id_rol'=>'');
		$dat=array('id_rol'=>'');
		$rolusuario = new Rolesusuario($this->dbAdapter);
		$permiso = new Permisos($this->dbAdapter);
		$noticias = new Noticias($this->dbAdapter);
		$eventos = new Eventos($this->dbAdapter);
		$rolUsuaCo = new Roles($this->dbAdapter);
		
		//me verifica el tipo de rol asignado al usuario
		foreach ($rolusuario->getRolUsuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}
		
		//setcookie("dep_u", "1");
		$usuarioCookies = new Usuarios($this->dbAdapter);
		$infoUsua = $usuarioCookies->getUsuarios($identi->id_usuario);

		echo '<script type="text/javascript">';
		echo 'localStorage.setItem("nombreUsuario", "'.mb_strtoupper(trim($infoUsua[0]["primer_nombre"])." ".trim($infoUsua[0]["segundo_nombre"])).'");';
		echo 'localStorage.setItem("id_usu", "'.mb_strtoupper($identi->id_usuario).'");';
		
		$nombre_completo = mb_strtoupper(trim($infoUsua[0]["primer_nombre"])." ".trim($infoUsua[0]["segundo_nombre"])." ".trim($infoUsua[0]["primer_apellido"])." ".trim($infoUsua[0]["segundo_apellido"]));
		
		echo 'localStorage.setItem("fotoUsuario", "'.trim($infoUsua[0]["archivo"]).'");';
		echo 'localStorage.setItem("apellidosUsuario", "'.mb_strtoupper(trim($infoUsua[0]["primer_apellido"])." ".trim($infoUsua[0]["segundo_apellido"])).'");';
		echo 'localStorage.setItem("documentoUsuario", "'.trim($infoUsua[0]["documento"]).'");';
		echo 'localStorage.setItem("mailUsuario", "'.trim($infoUsua[0]["usuario"]).'");';

		$pt = new Agregarvalflex($this->dbAdapter);
		$vincu = $pt->getvalflexseditar($infoUsua[0]["id_tipo_vinculacion"]);
		if(count($vincu)>0){
			echo 'localStorage.setItem("tipoVinculacion", "'.trim($vincu[0]["descripcion_valor"]).'");';
		}else{
			echo 'localStorage.setItem("tipoVinculacion", "");';
		}
		$rolNombre = $rolUsuaCo->getRolesid($dat["id_rol"]);
		if(count($rolNombre)>0){
			echo 'localStorage.setItem("rolNombre", "'.mb_strtoupper(trim($rolNombre[0]["descripcion"])).'");';
		}else{
			echo 'localStorage.setItem("rolNombre", "");';
		}

		$tipoDocu = $pt->getvalflexseditar($infoUsua[0]["id_tipo_documento"]);
		if(count($tipoDocu)>0){
			echo 'localStorage.setItem("tipoDocu", "'.trim($tipoDocu[0]["descripcion_valor"]).'");';
		}else{
			echo 'localStorage.setItem("tipoDocu", "");';
		}
		echo '</script>';

		//print_r(utf8_decode($_COOKIE["mailUsuario"]));
		

		if ($dat["id_rol"]!= ''){
			//me verifica el permiso sobre la pantalla
			$pantalla="index";
			$pt = new Agregarvalflex($this->dbAdapter);


		foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
			$panta["id_valor_flexible"];
		}

			$permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]);
			foreach ($permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]) as $per) {
				$per["id_rol"];
			}
		}
$asignar = new Asignareval($this->dbAdapter);
$usm = new Proyectos($this->dbAdapter);

//envio q=15 dias, d= 10 dias, c=5 dias y n=no enviar
$fecha2=$fecha=date("Y").'-'.date("m").'-'.date("d");
$fecha2=strtotime($fecha2);
foreach($usm->getProyectoh() as $py){
if($py["envio"]!='n'){

$fecha1=strtotime(trim($py["fecha_limite"]));

if($fecha1!=null){
$res=$fecha1-$fecha2;

$dif=($res/3600)/24;
	if($dif>=0){
		if((30>=$dif && $dif>=15) && $py["envio"]==null){
			//echo $py["codigo_proy"]."1";
			//$usm->enviarCorreoliq($py["codigo_proy"],$py["fecha_limite"],$dif,$py["id_proyecto"],$py["nombre_proy"]);
		}elseif((15>$dif && $dif>=5) && ($py["envio"]==null || $py["envio"]=='d')){
			//echo $py["codigo_proy"]."2";
			//$usm->enviarCorreoliq($py["codigo_proy"],$py["fecha_limite"],$dif,$py["id_proyecto"],$py["nombre_proy"]);
		}elseif((5>$dif && $dif>=0) && ($py["envio"]==null || $py["envio"]=='c')){
			//echo $py["codigo_proy"]."3";
			//$usm->enviarCorreoliq($py["codigo_proy"],$py["fecha_limite"],$dif,$py["id_proyecto"],$py["nombre_proy"]);
		}
	}
}
}
}
//$usm->enviarCorreoliq();

$eval = new Evaluar($this->dbAdapter);
$aplicar = new Aplicar($this->dbAdapter);
$usuario=$identi->id_usuario;
$multimedia = new Multimedia($this->dbAdapter);
$Documentosvinculados = new Documentosvinculados($this->dbAdapter);
$Grupoinvestigacion = new Grupoinvestigacion($this->dbAdapter);
$Redinvestigacion = new Redinvestigacion($this->dbAdapter);
$Semillero = new Semillero($this->dbAdapter);
$Usuarios = new Usuarios($this->dbAdapter);
$Asignareval = new Asignareval($this->dbAdapter);

		$view = new ViewModel(
			array(
				'form'=>$IndexForm,
				'titulo'=>"Inicio",
				'menu'=>$rolNombre[0]["opcion_pantalla"],
				'noticias'=>$noticias->getNoticiash(),
				'eventos'=>$eventos->getEventosh(),
				'asignar'=>$asignar->getAsignarevalt(),
				'aplicar'=>$aplicar->getAplicarh(),
				'eval'=>$eval->getEvaluar(),
				'id_user'=>$identi->id_usuario,
				'usuario'=>$usuario,
				'multimedia' => $multimedia->getMultimedia(),
				'dataDocumentos' => $Documentosvinculados->getDocumentosvinculadosPendientesByUsuario($identi->id_usuario),
				'Grupoinvestigacion' => $Grupoinvestigacion->getGrupoinvestigacion(), 
	            'Redinvestigacion' => $Redinvestigacion->getRedinv(),
	            'Semillero' => $Semillero->getSemilleroinv(),
				'usuarios' => $Usuarios->getArrayusuarios(),
				'asignareval' => $Asignareval->getAsignarIndex($identi->id_usuario)
			)
		);
		return $view;
    }
}
