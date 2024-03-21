<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\ReporteinvpreForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Asignareval;
use Application\Modelo\Entity\Usuarios;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Lineas;
use Application\Modelo\Entity\Libros;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Cronograma;
use Application\Modelo\Entity\Archivosconv;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Tablaequipo;
use Application\Modelo\Entity\Url;
use Application\Modelo\Entity\Prueba;
use Application\Modelo\Entity\Tablafinp;
use Application\Modelo\Entity\Tablafinproy;


class ReporteinvpreController extends AbstractActionController
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
        
    	if($this->getRequest()->isPost()){
    		$dato = $this->request->getPost();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
    		
    		$idc = "";
    		$url="/application/reporteinvpre/";
    		if($dato->tipo_reporte==1){
    			$url .= "reportpresupuesto";
    		}else{
    			$url .= "reportequipo";
    		}

			//adiciona la noticia
			$conv = new Convocatoria($this->dbAdapter);
			foreach($conv->reporteConvo($dato) as $t){
				$idc.=$t["id_convocatoria"].',';
			}
			
			$url .= (strcmp($idc,"")!=0) ? '/'.$idc : "/0";
			$url .= '/'.$dato->id_unidad_academica;
			$url .= '/'.$dato->id_dependencia_academica;
			$url .= '/'.$dato->id_programa_academico;
			$url .= '/'.$dato->id_categoria;
			$url .= '/'.$dato->propuestas_proyectos;
			return  $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$url);
    		
    	}else{
    		//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Proyectos($this->dbAdapter);
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
			$ReporteinvpreForm= new ReporteinvpreForm();
			$info=$not->getData($data);

			//define el campo ciudad
			$ReporteinvpreForm->add(array(
				'type' => 'Zend\Form\Element\Select',
				'name' => 'id_tipo_conv',
				'required'=>'required',
				'options' => array(
				    'label' => 'Tipo de convocatoria:',
				    'value_options' => array(
				        '' => '',
						'I'=>'Interna',
						'E'=>'Externa'
				    ),
				),
				'attributes' => array(
				    'value' => '' //set selected to '1'
				)
			));

			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(23) as $uni) {
				$opciones=$opciones+array('0'=>'');
				$opciones=$opciones+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			}
			$ReporteinvpreForm->add(array(
			'name' => 'id_unidad_academica',
			'type' => 'Zend\Form\Element\Select',
					'options' => array(
		        		'label' => 'Unidad académica:',
		       		 	'value_options' => $opciones,
					),
                   'attributes' => array(
                       'id' => 'id_unidad_academica'
                   ),
			));

			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(33) as $uni) {
				$opciones=$opciones+array('0'=>'');
				$opciones=$opciones+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			}
			$ReporteinvpreForm->add(array(
			'name' => 'id_dependencia_academica',
			'type' => 'Zend\Form\Element\Select',
				'options' => array(
            		'label' => 'Dependencia académica:',
           			'value_options' => $opciones,
				),
                'attributes' => array(
                	'id' => 'id_unidad_academica'
                ),
			));

			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(34) as $uni) {
				$opciones=$opciones+array('0'=>'');
				$opciones=$opciones+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			}
			$ReporteinvpreForm->add(array(
			'name' => 'id_programa_academico',
			'type' => 'Zend\Form\Element\Select',
				'options' => array(
            		'label' => 'Programa académico:',
           			'value_options' => $opciones,
				),
                'attributes' => array(
                	'id' => 'id_unidad_academica'
                  ),
			));


			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(41) as $uni) {
				$opciones=$opciones+array('0'=>'');
				$opciones=$opciones+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			}
			$ReporteinvpreForm->add(array(
			'name' => 'id_categoria',
			'type' => 'Zend\Form\Element\Select',
				'options' => array(
            		'label' => 'Categoría/modalidad:',
           			 'value_options' => $opciones,
				),
                'attributes' => array(
                	'id' => 'id_unidad_academica',
                ),
			));


			//define el campo ciudad
	        $ReporteinvpreForm->add(array(
	            'type' => 'Zend\Form\Element\Select',
	            'name' => 'id_estado',
				'required'=>'required',
	            'options' => array(
	                'label' => 'Estado de la Convocatoria:',
	                'value_options' => array(
	                    '' => '',
	                    'R' => 'Cerrada',
	                    'H' => 'Archivada',
	                    'N' => 'Anulada',
	                    'P' => 'Con aplicaciones',
	                    'B' => 'Abierta',
	                ),
	            ),
	            'attributes' => array(
	                'value' => '1' //set selected to '1'
	            )
	        ));

	        $ReporteinvpreForm->add(array(
	            'type' => 'Zend\Form\Element\Select',
	            'name' => 'tipo_reporte',
	            'options' => array(
	                'label' => 'Tipo de reporte:',
	                'value_options' => array(
	                    '' => '',
	                    '1' => 'Presupuestal',
	                    '2' => 'Equipos de trabajo',
	                ),
	            ),
	            'attributes' => array(
	                'value' => '', 
	                'required' => 'required'
	            )
	        ));

	        $ReporteinvpreForm->add(array(
	            'type' => 'Zend\Form\Element\Select',
	            'name' => 'propuestas_proyectos',
	            'options' => array(
	                'label' => 'Propuestas/Proyectos:',
	                'value_options' => array(
	                    '1' => 'Propuestas',
	                    '2' => 'Proyectos',
	                ),
	            ),
	            'attributes' => array(
	                'value' => '1', 
	                'required' => 'required'
	            )
	        ));


			//adiciona la noticia
			$view = new ViewModel(array('form'=>$ReporteinvpreForm,
				'titulos'=>"Reporte equipos de investigación y presupuesto", 
				'url'=>$this->getRequest()->getBaseUrl(),
				'consulta'=>0,
				'menu'=>$dat["id_rol"]));
			return $view;
    	}
		
    }



	public function reportpresupuestoAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$aplicar = new Aplicar($this->dbAdapter);
		$convocatoria = new convocatoria($this->dbAdapter);
		$usuario = new Usuarios($this->dbAdapter);
		$valflex = new Agregarvalflex($this->dbAdapter);
		$proyecto = new Proyectos($this->dbAdapter);
		$unidad = $this->params()->fromRoute('id2');
		$dependencia = $this->params()->fromRoute('id3');
		$programa = $this->params()->fromRoute('id4');
		$categoria = $this->params()->fromRoute('id5');
		$proyecto_propuesta = (int)$this->params()->fromRoute('id6', 0);
	    $this->dbAdapter2=$this->getServiceLocator()->get('db4');
		$presupuesto = new Prueba($this->dbAdapter2);
		$Tablafinproy = new Tablafinp($this->dbAdapter);
		$Tablafinprop = new Tablafinproy($this->dbAdapter);

		$id = $this->params()->fromRoute('id');
		$arreglo=array();
		$cont=0;
		$ids = explode(",", $id);
		for($i=0; $i<count($ids); $i++){
			$idconv = trim($ids[$i]);
			if($idconv != ''){
				if($proyecto_propuesta==2){
					$prco=$proyecto->getProyectoc($idconv, $unidad, $dependencia, $programa, $categoria);
					foreach($prco as $dt){
						foreach($Tablafinproy->caseSql($dt["id_proyecto"])as $x) {
							$txIdRubro="";
							foreach ($valflex->getValoresf() as $vf ) {
								if($vf["id_valor_flexible"] == $x["id_rubro"]) {
									$txIdRubro = $vf["descripcion_valor"];
								}
							}
							$arreglo[$cont]=array(
								'recursos_inversion'		=> $x["recursos_inversion"],
								'recursos_funcionamiento'	=> $x["recursos_funcionamiento"],
								'recursos_cofinanciacion'	=> $x["recursos_cofinanciacion"],
								'total'						=> $x["total"],
								'periodo'					=> $x["periodo"],
								'id_rubro'					=> $txIdRubro,
							 	'id_proyecto'				=> $dt["id_proyecto"],
							 	'nombre_proy'				=> $dt["nombre_proy"],
							 	'codigo_proy'				=> $dt["codigo_proy"],
							 	'id_convocatoria'		=>$dt["id_convocatoria"]);
							$cont++;
						}
					}
				}else{
					$propuestas=$aplicar->getPropuestasc($idconv, $unidad, $dependencia, $programa, $categoria);
					foreach($propuestas as $dt){	
						foreach($Tablafinprop->caseSql($dt["id_aplicar"])as $x) {
							$txIdRubro="";
							foreach ($valflex->getValoresf() as $vf ) {
								if($vf["id_valor_flexible"] == $x["id_rubro"]) {
									$txIdRubro = $vf["descripcion_valor"];
								}
							}
							$arreglo[$cont]=array(
								'recursos_inversion'		=> $x["recursos_inversion"],
								'recursos_funcionamiento'	=> $x["recursos_funcionamiento"],
								'recursos_cofinanciacion'	=> $x["recursos_cofinanciacion"],
								'total'						=> $x["total"],
								'periodo'					=> $x["periodo"],
								'id_rubro'					=> $txIdRubro,
							 	'id_proyecto'				=> $dt["id_aplicar"],
							 	'nombre_proy'				=> $dt["nombre_proy"],
							 	'codigo_proy'				=> $dt["codigo_proy"],
							 	'id_convocatoria'		=>$dt["id_convocatoria"]);
							$cont++;
						}						
					}	
				}
			}
		}
		$view = new ViewModel(array(
			'datos'=>$arreglo));
	    $view->setTerminal(true);
	    return $view;
	}

	public function reportequipoAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$aplicar = new Aplicar($this->dbAdapter);
		$equi = new Tablaequipop($this->dbAdapter);
		$equip = new Tablaequipo($this->dbAdapter);
		$usuario = new Usuarios($this->dbAdapter);
		$valflex = new Agregarvalflex($this->dbAdapter);
		$proyecto = new Proyectos($this->dbAdapter);
		$unidad = $this->params()->fromRoute('id2');
		$dependencia = $this->params()->fromRoute('id3');
		$programa = $this->params()->fromRoute('id4');
		$categoria = $this->params()->fromRoute('id5');
		$proyecto_propuesta = (int)$this->params()->fromRoute('id6', 0);

		$id = $this->params()->fromRoute('id');
		$arreglo=array();
		$cont=0;
		$ids = explode(",", $id);
		for($i=0; $i<count($ids); $i++){
			$idconv = trim($ids[$i]);
			if($idconv != ''){
				if($proyecto_propuesta==2){
					$prco=$proyecto->getProyectoc($idconv, $unidad, $dependencia, $programa, $categoria);
					foreach($prco as $dt){
						foreach($equi->getTablaequipo($dt["id_proyecto"]) as $x){
							$arreglo[$cont]=array(
								'id_integrante'		=>$x["id_integrante"],
								'id_rol'			=>$x["id_rol"],
							 	'id_tipo_dedicacion'=>$x["id_tipo_dedicacion"],
							 	'horas_sol'			=>$x["horas_sol"],
							 	'id_proyecto'		=>$x["id_proyecto"],
							 	'ano'				=>$x["ano"],
							 	'periodo'			=>$x["periodo"],
							 	'horas_apro'		=>$x["horas_apro"],
							 	'nombre_proy'		=>$dt["nombre_proy"],
							 	'codigo_proy'		=>$dt["codigo_proy"],
							 	'id_convocatoria'		=>$dt["id_convocatoria"]);
							$cont++;
						}
					}
				}else{
					$propuestas=$aplicar->getPropuestasc($idconv, $unidad, $dependencia, $programa, $categoria);
					foreach($propuestas as $dt){
						$auxCant = 0;
						foreach($equip->getTablaequipo($dt["id_aplicar"]) as $x){
							$arreglo[$cont]=array(
								'id_integrante'		=>$x["id_integrante"],
								'id_rol'			=>$x["id_rol"],
							 	'id_tipo_dedicacion'=>$x["id_tipo_dedicacion"],
							 	'horas_sol'			=>$x["horas_sol"],
							 	'id_proyecto'		=>$x["id_aplicar"],
							 	'ano'				=> "",
							 	'periodo'			=> "",
							 	'horas_apro'		=>$x["horas_apro"],
							 	'nombre_proy'		=>$dt["nombre_proy"],
							 	'codigo_proy'		=>$dt["codigo_proy"],
								'id_convocatoria'		=>$dt["id_convocatoria"]);
							$cont++;
							$auxCant += 1;
						}
						if($auxCant==0){
							$arreglo[$cont]=array(
								'id_integrante'		=>"",
								'id_rol'			=>"",
							 	'id_tipo_dedicacion'=>"",
							 	'horas_sol'			=>"",
							 	'id_proyecto'		=>$x["id_aplicar"],
							 	'ano'				=> "",
							 	'periodo'			=> "",
							 	'horas_apro'		=>"",
							 	'nombre_proy'		=>$dt["nombre_proy"],
							 	'codigo_proy'		=>$dt["codigo_proy"],
							 	'id_convocatoria'		=>$dt["id_convocatoria"]);
							$cont++;
						}
					}
				}
			}
		}
		$view = new ViewModel(array(
			'datos'=>$arreglo,
			'valflex'=>$valflex->getValoresf(),
			'usuarios'=>$usuario->getArrayusuarios(),
			'id'=>$id));
	    $view->setTerminal(true);
	    return $view;
	}

}
