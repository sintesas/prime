<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\ConsultagiForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Grupoinvestigacion;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Lineas;
use Application\Modelo\Entity\Libros;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Asociaciones;
use Application\Modelo\Entity\Semilleros;
use Application\Modelo\Entity\Instituciones;
use Application\Modelo\Entity\Capitulosgrupo;
use Application\Modelo\Entity\Agregarautorgrupo;
use Application\Modelo\Entity\Otrasproducciones;
use Application\Modelo\Entity\Archivos;
use Application\Modelo\Entity\Redinvestigacion;
use Application\Modelo\Entity\Semillero;
use Application\Modelo\Entity\Integrantes;
use Application\Modelo\Entity\Reconocimientos;
use Application\Modelo\Entity\Redes;
use Application\Modelo\Entity\Articulos;
use Application\Modelo\Entity\Bibliograficos;
use Application\Modelo\Entity\Autores;
use Application\Modelo\Entity\Gruposrel;
use Application\Modelo\Entity\Proyectosint;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Identificadoresgru;
use Application\Modelo\Entity\Eventosgru;
use Application\Modelo\Entity\Trabajogradogru;

use Application\Modelo\Entity\Documentosvinculados;
use Application\Modelo\Entity\Articuloshv;
use Application\Modelo\Entity\Libroshv;
use Application\Modelo\Entity\Capitulosusuario;
use Application\Modelo\Entity\Agregarautorusuario;
use Application\Modelo\Entity\Otrasproduccioneshv;
use Application\Modelo\Entity\Bibliograficoshv;
use Application\Modelo\Entity\Proyectosexthv;
use Application\Modelo\Entity\Proyectosintusua;
use Application\Modelo\Entity\Lineashv;

class ConsultagiController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
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

    	$identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
			$identi = new \stdClass();
        	$identi->id_usuario = '0';
			//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}

		$rol = new Roles($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
	 	$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}

        if($this->getRequest()->isPost()){

			$not = new Grupoinvestigacion($this->dbAdapter);

			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$ConsultagiForm = new ConsultagiForm();


			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(23) as $uni) {
			$op=array(''=>'');
			$op=$op+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ConsultagiForm->add(array(
				'name' => 'id_unidad_academica',
				'type' => 'Zend\Form\Element\Select',
				'options' => array(
		            'label' => 'Unidad Académica : ',
		            'value_options' => $opciones,
					),
				));

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(33) as $dept) {
				$op=array(''=>'');
				$op=$op+array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
			$ConsultagiForm->add(array(
				'name' => 'id_dependencia_academica',
				'type' => 'Zend\Form\Element\Select',
				'options' => array(
		            'label' => 'Dependencia Académica : ',
		            'value_options' => $opciones,
					),
				)); 

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(34) as $dept) {
				$op=array(''=>'');
				$op=$op+array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
			$ConsultagiForm->add(array(
				'name' => 'id_programa_academico',
				'type' => 'Zend\Form\Element\Select',
				'options' => array(
		            'label' => 'Programa Académico : ',
		            'value_options' => $opciones,
					),
				)); 
			
            //LINEAS DE INVESTIGACION
			$lineas = new Lineas($this->dbAdapter);
			$opciones = array();
			$op = array(
                    0 => ''
            );
            foreach ($lineas->getLineast() as $dept) {
                $op = $op + array(
                    $dept["id_linea_inv"] => $dept["nombre_linea"]
                );
                $opciones = $opciones + $op;
            }
            
			$ConsultagiForm->add(array(
				'name' => 'id_lineas',
				'type' => 'Zend\Form\Element\Select',
				'required'=>'required',
				'options' => array(
		            'label' => 'Líneas de Investigación: ',
		            'value_options' => $opciones,
					),
				)); 

		    $ConsultagiForm->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'integrantes',
		    'options' => array(
		        'label' => 'Integrantes',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
			    )
			));
			$ConsultagiForm->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'infogeneral',
		    'options' => array(
		        'label' => 'Información general',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
			    )
			));

		    $ConsultagiForm->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'lineas',
		    'options' => array(
		        'label' => 'Líneas de investigación',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
			    )
			));

			$ConsultagiForm->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'grupos',
		    'options' => array(
		        'label' => 'Grupos relacionados o colaboracón entre grupos',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
			    )
			));

		    $ConsultagiForm->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'redes',
			    'options' => array(
			        'label' => 'Redes de grupo',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			 $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'asociaciones',
			    'options' => array(
			        'label' => 'Asociaciones',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

		    $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'reconocimientos',
			    'options' => array(
			        'label' => 'Reconocimientos',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			 $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'semilleros',
			    'options' => array(
			        'label' => 'Semilleros de colectivos del grupo',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			 $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'instituciones',
			    'options' => array(
			        'label' => 'Instituciones con integrantes en el grupo',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

		    $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'articulos',
			    'options' => array(
			        'label' => 'Artículos',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'libros',
			    'options' => array(
			        'label' => 'Libros',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'capitulos',
			    'options' => array(
			        'label' => 'Capitulos de libros',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'otras',
			    'options' => array(
			        'label' => 'Otras producciones de investigación',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'bibliograficos',
			    'options' => array(
			        'label' => 'Documentos Bibliográficos',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

		    $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'proyectosext',
			    'options' => array(
			        'label' => 'Proyectos de investigación externos',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'proyectosint',
			    'options' => array(
			        'label' => 'Proyectos de investigación internos',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'produccion',
			    'options' => array(
			        'label' => 'Producción académica',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'identificadores',
			    'options' => array(
			        'label' => 'Identificadores de Investigación',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'divulgacion',
			    'options' => array(
			        'label' => 'Divulgación',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'formacion',
			    'options' => array(
			        'label' => 'Formación',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));
		    
		    
			$libros = new Libros($this->dbAdapter);
			$lineas = new Lineas($this->dbAdapter);
 			$int = new Integrantes($this->dbAdapter);
 			$red = new Redes($this->dbAdapter);
			$rec = new Reconocimientos($this->dbAdapter);
			$art = new Articulos($this->dbAdapter);
			$biblio = new Bibliograficos($this->dbAdapter);
			$auto = new Autores($this->dbAdapter);
			$gr = new Gruposrel($this->dbAdapter);
			$usuario = new Usuarios($this->dbAdapter);
			$as = new Asociaciones($this->dbAdapter);
			$sem = new Semilleros($this->dbAdapter);
            $inst = new Instituciones($this->dbAdapter);
			$capitulosgrupo = new Capitulosgrupo($this->dbAdapter);
			$autoresgrupo = new Agregarautorgrupo($this->dbAdapter);
			$otras = new Otrasproducciones($this->dbAdapter);
			$auto = new Autores($this->dbAdapter);
			$grupostotal = new Grupoinvestigacion($this->dbAdapter);
			$arch = new Archivos($this->dbAdapter);
			$redinvestigacion = new Redinvestigacion($this->dbAdapter);
			$semillerosinvestigacion = new Semillero($this->dbAdapter);
			$usua = new Usuarios($this->dbAdapter);
			$proyext = new Proyectosext($this->dbAdapter);
			$valflex = new Agregarvalflex($this->dbAdapter);
			$proyectos = new Proyectos($this->dbAdapter);
            $tablaequipo = new Tablaequipop($this->dbAdapter);
            $proyectosint = new Proyectosint($this->dbAdapter); 
			$identificadores = new Identificadoresgru($this->dbAdapter); 
        	$eventos = new Eventosgru($this->dbAdapter); 
        	$formacion = new Trabajogradogru($this->dbAdapter);
        	$semillero = new Semillero($this->dbAdapter); 

            $Documentosvinculados = new Documentosvinculados($this->dbAdapter);  
            $Articuloshv = new Articuloshv($this->dbAdapter);   
            $libroshv = new Libroshv($this->dbAdapter);
            $Capitulosusuario = new Capitulosusuario($this->dbAdapter);
            $autoresusuario = new Agregarautorusuario($this->dbAdapter);
            $Otrasproduccioneshv = new Otrasproduccioneshv($this->dbAdapter);
            $Bibliograficoshv = new Bibliograficoshv($this->dbAdapter);
            $Proyectosexthv = new Proyectosexthv($this->dbAdapter);
            $Proyectosintusua = new Proyectosintusua($this->dbAdapter); 
            $LineasHv = new Lineashv($this->dbAdapter);

			//redirige a la pantalla de inicio del controlador
			$view = new ViewModel(
				array(
					'form'=>$ConsultagiForm,
					'titulo'=>"Consulta grupos de investigación", 
					'datos'=>$not->filtroGruposGI($data,$lineas->getLinea($data)),
					'url'=>$this->getRequest()->getBaseUrl(),
					'info'=>$data,
					'datos2'=>$lineas->getLineast(),
					'datos3'=>$libros->getLibrost(),
					'datos4'=>$proyext->getProyectosextt(),
					'datos5'=>$int->getIntegrantesi(),
					'redes'=>$red->getRedesi(),
					'datos7' => $rec->getReconocimientosi(),
					'datos8' => $art->getArticulosi(),
					'datos9' => $biblio->getBibliograficost(),
					'autores' => $auto->getAutoresi(),
					'valflex'=>$valflex->getValoresf(),
					'd_user' => $usua->getUsuarios(''),
					'semillerost' => $sem->getSemillerost(),
					'instituciones' => $inst->getInstitucionest(),
					'capitulos' => $capitulosgrupo->getCapitulot(),
					'grupos' => $gr->getGruposrelt(),
					'otras' => $otras->getOtrasproduccionest(),
					'usuarios' => $usuario->getArrayusuarios(),		
					'grupostotal' => $grupostotal->getGrupoinvestigacion(),					
                	'autoresgrupo' => $autoresgrupo->getAgregarautorgrupot(),
					'asociaciones' => $as->getAsociacionest(),
					'auto' => $auto->getAutoresi(),
					'consulta'=>1,
					'arch' => $arch->getArchivost(),
					'menu'=>$dat["id_rol"],
					'redinvestigacion' => $redinvestigacion->getRedinv(),
					'proyectosint' =>  $proyectosint->getProyectosi(),
                    'proyectos' =>  $proyectos->getProyectoh(),
                    'tablaequipo' =>  $tablaequipo->getTablaequipot(),
					'semillerosinvestigacion' => $semillerosinvestigacion->getSemilleroinv(),
					'identificadores' => $identificadores->getIdentificadoresgrut(),
            		'eventos' => $eventos->getEventosgrut(),
            		'formacion' => $formacion->getTrabajogradogrut(),
            		'semilleros' => $semillero->getSemilleroinv(),

					'dataDocumentos' => $Documentosvinculados->getDocumentosvinculadosByActivo("1"),
                    'Articuloshv' => $Articuloshv->getArticuloshvt(),
                    'libroshv' => $libroshv->getLibroshvt(),
                    'capituloshv' => $Capitulosusuario->getCapitulot(),
                    'autoresusuario' => $autoresusuario->getAgregarautorusuariot(),
                    'Otrasproduccioneshv' => $Otrasproduccioneshv->getOtrasproduccioneshvt(),
                    'Bibliograficoshv' => $Bibliograficoshv->getBibliograficost(),
                    'Proyectosexthv' => $Proyectosexthv->getProyectosexthvt(),
                    'Proyectosintusua' =>  $Proyectosintusua->getProyectosi(),
                    'LineasHv' => $LineasHv->getLineashvt()
				)
			);
			return $view;
		}else{
			$ConsultagiForm = new ConsultagiForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Grupoinvestigacion($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				$identi = new \stdClass();
            	$identi->id_usuario = '0';
				//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(23) as $uni) {
			$op=array(''=>'');
			$op=$op+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ConsultagiForm->add(array(
			'name' => 'id_unidad_academica',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Unidad Académica : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(33) as $dept) {
			$op=array(''=>'');
			$op=$op+array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ConsultagiForm->add(array(
			'name' => 'id_dependencia_academica',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Dependencia Académica : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(34) as $dept) {
			$op=array(''=>'');
			$op=$op+array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ConsultagiForm->add(array(
			'name' => 'id_programa_academico',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Programa Académico : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 

			//LINEAS DE INVESTIGACION
			$lineas = new Lineas($this->dbAdapter);
			$opciones = array();
			$op = array(
                    0 => ''
            );
            foreach ($lineas->getLineast() as $dept) {
                $op = $op + array(
                    $dept["id_linea_inv"] => $dept["nombre_linea"]
                );
                $opciones = $opciones + $op;
            }
            
			$ConsultagiForm->add(array(
			    'name' => 'id_lineas',
			    'type' => 'Zend\Form\Element\Select',
			    'required'=>'required',
			    'options' => array(
			        'label' => 'Líneas de Investigación : ',
			        'value_options' =>
			        $opciones
			        ,
			    ),
			));
			
$ConsultagiForm->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'integrantes',
		    'options' => array(
		        'label' => 'Integrantes',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
			    )
			));

		    $ConsultagiForm->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'lineas',
		    'options' => array(
		        'label' => 'Líneas de investigación',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
			    )
			));

			$ConsultagiForm->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'grupos',
		    'options' => array(
		        'label' => 'Grupos relacionados o colaboracón entre grupos',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
			    )
			));

		    $ConsultagiForm->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'redes',
			    'options' => array(
			        'label' => 'Redes de grupo',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			 $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'asociaciones',
			    'options' => array(
			        'label' => 'Asociaciones',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			 $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'infogeneral',
			    'options' => array(
			        'label' => 'Información general',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));


		    $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'reconocimientos',
			    'options' => array(
			        'label' => 'Reconocimientos',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			 $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'semilleros',
			    'options' => array(
			        'label' => 'Semilleros de colectivos del grupo',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			 $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'instituciones',
			    'options' => array(
			        'label' => 'Instituciones con integrantes en el grupo',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

		    $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'articulos',
			    'options' => array(
			        'label' => 'Artículos',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'libros',
			    'options' => array(
			        'label' => 'Libros',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'capitulos',
			    'options' => array(
			        'label' => 'Capitulos de libros',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'otras',
			    'options' => array(
			        'label' => 'Otras producciones de investigación',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'bibliograficos',
			    'options' => array(
			        'label' => 'Documentos Bibliográficos',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    )
			));

		    $ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'proyectosext',
			    'options' => array(
			        'label' => 'Proyectos de investigación externos',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'proyectosint',
			    'options' => array(
			        'label' => 'Proyectos de investigación internos',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));


			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'produccion',
			    'options' => array(
			        'label' => 'Producción académica',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'identificadores',
			    'options' => array(
			        'label' => 'Identificadores de Investigación',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'divulgacion',
			    'options' => array(
			        'label' => 'Divulgación',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));

			$ConsultagiForm->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'formacion',
			    'options' => array(
			        'label' => 'Formación',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));

			$rolusuario = new Rolesusuario($this->dbAdapter);
			$permiso = new Permisos($this->dbAdapter);
			
			$libros = new Libros($this->dbAdapter);
			$lineas = new Lineas($this->dbAdapter);
			$proyext = new Proyectosext($this->dbAdapter);
			$valflex = new Agregarvalflex($this->dbAdapter);
			$view = new ViewModel(array('form'=>$ConsultagiForm,
										'titulo'=>"Consulta grupos de investigación",
										'titulo2'=>"Tipos Valores Existentes", 
										'url'=>$this->getRequest()->getBaseUrl(),
										'consulta'=>0,
										'menu'=>$dat["id_rol"]));
			return $view;
			
		}
    }


}
