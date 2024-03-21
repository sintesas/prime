<?php
return array(
    'view_helpers' => array(
        'invokables' => array(
            'viewhelpercaptcha' => 'Zend\Form\Custom\Captcha\ViewHelperCaptcha'
        )
        
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index'
                    )
                )
            ),
            'pdf' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/pdf',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Pdf',
                        'action' => 'index'
                    )
                )
            ),
            
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            
                            // 'route' => '/[:controller[/:action]]',
                            'route' => '/[:controller[/:action[/:id][/:id2][/:id3][/:id4][/:id5][/:id6][/:id7][/:id8][/:id9][/:id10][/:id11][/:id12][/:id13][/:id14][/:id15][/:id16][/:id17][/:id18][/:id19][/:id20][/:id21][/:id22][/:id23][/:id24][/:id25][/:id26][/:id27]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    ),
                    'paginator' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:controller/[page/:page]',
                            'constraints' => array(
                                'page' => '[0-9]*'
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller',
                                'controller' => 'mensajeusuario',
                                'action' => 'buscar'
                            )
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator'
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo'
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Identificadoresusu' => 'Application\Controller\IdentificadoresusuController',
            'Application\Controller\Identificadoresgru' => 'Application\Controller\IdentificadoresgruController',
            'Application\Controller\Identificadoresred' => 'Application\Controller\IdentificadoresredController',
            'Application\Controller\Identificadoressemi' => 'Application\Controller\IdentificadoressemiController',
            'Application\Controller\Eventosusu' => 'Application\Controller\EventosusuController',
            'Application\Controller\Eventosgru' => 'Application\Controller\EventosgruController',
            'Application\Controller\Eventosred' => 'Application\Controller\EventosredController',
            'Application\Controller\Eventossemi' => 'Application\Controller\EventossemiController',
            'Application\Controller\Trabajogradousu' => 'Application\Controller\TrabajogradousuController',
            'Application\Controller\Trabajogradogru' => 'Application\Controller\TrabajogradogruController',
            'Application\Controller\Trabajogradored' => 'Application\Controller\TrabajogradoredController',
            'Application\Controller\Trabajogradosemi' => 'Application\Controller\TrabajogradosemiController',
            'Application\Controller\Archivosinformem' => 'Application\Controller\ArchivosinformemController',
            'Application\Controller\Banner' => 'Application\Controller\BannerController',
            'Application\Controller\Multimedia' => 'Application\Controller\MultimediaController',
            'Application\Controller\graficas' => 'Application\Controller\GraficasController',
            'Application\Controller\Autenticar' => 'Application\Controller\AutenticarController',
            'Application\Controller\Usuario' => 'Application\Controller\UsuarioController',
            'Application\Controller\Crearusuario' => 'Application\Controller\CrearusuarioController',
            'Application\Controller\Editarusuario' => 'Application\Controller\EditarusuarioController',
            'Application\Controller\Cambiarcontrasena' => 'Application\Controller\CambiarcontrasenaController',
            'Application\Controller\Tipovalores' => 'Application\Controller\TipovaloresController',
            'Application\Controller\Agregarvalflex' => 'Application\Controller\AgregarvalflexController',
            'Application\Controller\Editarvalflex' => 'Application\Controller\EditarvalflexController',
            'Application\Controller\Recuperarcontra' => 'Application\Controller\RecuperarcontraController',
            'Application\Controller\Mensajeadministrador' => 'Application\Controller\MensajeadministradorController',
            'Application\Controller\Mensajeusuario' => 'Application\Controller\MensajeusuarioController',
            'Application\Controller\Auditoria' => 'Application\Controller\AuditoriaController',
            'Application\Controller\Roles' => 'Application\Controller\RolesController',
            'Application\Controller\Reportes' => 'Application\Controller\ReportesController',
            'Application\Controller\Login' => 'Application\Controller\LoginController',
            'Application\Controller\Solicitudes' => 'Application\Controller\SolicitudesController',
            'Application\Controller\Solconvocatoria' => 'Application\Controller\SolconvocatoriaController',
            'Application\Controller\Solcambioequipo' => 'Application\Controller\SolcambioequipoController',
            'Application\Controller\Solcambiorubro' => 'Application\Controller\SolcambiorubroController',
            'Application\Controller\Solprorroga' => 'Application\Controller\SolprorrogaController',
            'Application\Controller\Soltrabajocampo' => 'Application\Controller\SoltrabajocampoController',
            'Application\Controller\Solsocializacion' => 'Application\Controller\SolsocializacionController',
            'Application\Controller\Solfotocopias' => 'Application\Controller\SolfotocopiasController',
            'Application\Controller\Solmaterialbiblio' => 'Application\Controller\SolmaterialbiblioController',
            'Application\Controller\Solmateriales' => 'Application\Controller\SolmaterialesController',
            'Application\Controller\Solcompraequipos' => 'Application\Controller\SolcompraequiposController',
            'Application\Controller\Soltransporte' => 'Application\Controller\SoltransporteController',
            'Application\Controller\Solicitudesvinculacion' => 'Application\Controller\SolicitudesvinculacionController',
            'Application\Controller\Solcontratacion' => 'Application\Controller\SolcontratacionController',
            'Application\Controller\Solcancelacion' => 'Application\Controller\SolcancelacionController',
            'Application\Controller\Gestionarsolicitudes' => 'Application\Controller\GestionarsolicitudesController',
            'Application\Controller\Gestionarunitaria' => 'Application\Controller\GestionarunitariaController',
            'Application\Controller\Permisos' => 'Application\Controller\PermisosController',
            'Application\Controller\Rolesusuario' => 'Application\Controller\RolesusuarioController',
            'Application\Controller\Anexodocumento' => 'Application\Controller\AnexodocumentoController',
            'Application\Controller\Editartipovalores' => 'Application\Controller\EditartipovaloresController',
            'Application\Controller\Cambiarcontrausuario' => 'Application\Controller\CambiarcontrausuarioController',
            'Application\Controller\Cambiarusuario' => 'Application\Controller\CambiarusuarioController',
            'Application\Controller\Auditoriadet' => 'Application\Controller\AuditoriadetController',
            'Application\Controller\Noticias' => 'Application\Controller\NoticiasController',
            'Application\Controller\Cargarimagen' => 'Application\Controller\CargarimagenController',
            'Application\Controller\Crearnoticias' => 'Application\Controller\CrearnoticiasController',
            'Application\Controller\Editarnoticias' => 'Application\Controller\EditarnoticiasController',
            'Application\Controller\Eventos' => 'Application\Controller\EventosController',
            'Application\Controller\Creareventos' => 'Application\Controller\CreareventosController',
            'Application\Controller\Editareventos' => 'Application\Controller\EditareventosController',
            'Application\Controller\Foro' => 'Application\Controller\ForoController',
            'Application\Controller\Repositorio' => 'Application\Controller\RepositorioController',
            'Application\Controller\Crearforo' => 'Application\Controller\CrearforoController',
            'Application\Controller\Editarforo' => 'Application\Controller\EditarforoController',
            'Application\Controller\Responderforo' => 'Application\Controller\ResponderforoController',
            'Application\Controller\Grupoinv' => 'Application\Controller\GrupoinvController',
            'Application\Controller\Redesinv' => 'Application\Controller\RedesinvController',
            'Application\Controller\Gestionpantallas' => 'Application\Controller\GestionpantallasController',
            'Application\Controller\Gestionmodulos' => 'Application\Controller\GestionmodulosController',
            'Application\Controller\Gestionsubmodulos' => 'Application\Controller\GestionsubmodulosController',
            'Application\Controller\Gestionformularios' => 'Application\Controller\GestionformulariosController',
            'Application\Controller\Semillerosinv' => 'Application\Controller\SemillerosinvController',
            'Application\Controller\Consultaprocesom' => 'Application\Controller\ConsultaprocesomController',
            'Application\Controller\Consultamonitores' => 'Application\Controller\ConsultamonitoresController',
            'Application\Controller\Crearsemillerosinv' => 'Application\Controller\CrearsemillerosinvController',
            'Application\Controller\Crearrepositorio' => 'Application\Controller\CrearrepositorioController',
            'Application\Controller\Editargrupoinv' => 'Application\Controller\EditargrupoinvController',
            'Application\Controller\Editarredinv' => 'Application\Controller\EditarredinvController',
            'Application\Controller\Editarsemilleroinv' => 'Application\Controller\EditarsemilleroinvController',
            'Application\Controller\Creargrupoinvestigacion' => 'Application\Controller\CreargrupoinvestigacionController',
            'Application\Controller\Crearredinvestigacion' => 'Application\Controller\CrearredinvestigacionController',
            'Application\Controller\Lineas' => 'Application\Controller\LineasController',
            'Application\Controller\Gruposrel' => 'Application\Controller\GruposrelController',
            'Application\Controller\Asociaciones' => 'Application\Controller\AsociacionesController',
            'Application\Controller\Reconocimientos' => 'Application\Controller\ReconocimientosController',
            'Application\Controller\Redes' => 'Application\Controller\RedesController',
            'Application\Controller\Proyectosint' => 'Application\Controller\ProyectosintController',
            'Application\Controller\Proyectosintred' => 'Application\Controller\ProyectosintredController',
            'Application\Controller\Proyectosintsemi' => 'Application\Controller\ProyectosintsemiController',
            'Application\Controller\Proyectosintusua' => 'Application\Controller\ProyectosintusuaController',
            'Application\Controller\Integrantes' => 'Application\Controller\IntegrantesController',
            'Application\Controller\Integrantesred' => 'Application\Controller\IntegrantesredController',
            'Application\Controller\Integrantessemillero' => 'Application\Controller\IntegrantessemilleroController',
            'Application\Controller\Participacioneventos' => 'Application\Controller\ParticipacioneventosController',
            'Application\Controller\Participacioneventosred' => 'Application\Controller\ParticipacioneventosredController',
            'Application\Controller\Areasemillero' => 'Application\Controller\AreasemilleroController',
            'Application\Controller\Reconocimientossemillero' => 'Application\Controller\ReconocimientossemilleroController',
            'Application\Controller\Equipodirectivo' => 'Application\Controller\EquipodirectivoController',
            'Application\Controller\Objetivosespecificos' => 'Application\Controller\ObjetivosespecificosController',
            'Application\Controller\Objetivosmetas' => 'Application\Controller\ObjetivosmetasController',
            'Application\Controller\Contactored' => 'Application\Controller\ContactoredController',
            'Application\Controller\Editarroles' => 'Application\Controller\EditarrolesController',
            'Application\Controller\Coinvestigadores' => 'Application\Controller\CoinvestigadoresController',
            'Application\Controller\Documentosbibliograficosred' => 'Application\Controller\DocumentosbibliograficosredController',
            'Application\Controller\Documentosbibliograficossemillero' => 'Application\Controller\DocumentosbibliograficossemilleroController',
            'Application\Controller\Proyectored' => 'Application\Controller\ProyectoredController',
            'Application\Controller\Serviciosred' => 'Application\Controller\ServiciosredController',
            'Application\Controller\Contratacionpersonal' => 'Application\Controller\ContratacionpersonalController',
            'Application\Controller\Articulos' => 'Application\Controller\ArticulosController',
            'Application\Controller\Articulosred' => 'Application\Controller\ArticulosredController',
            'Application\Controller\Articulossemillero' => 'Application\Controller\ArticulossemilleroController',
            'Application\Controller\Agregarcoautor' => 'Application\Controller\AgregarcoautorController',
            'Application\Controller\Libros' => 'Application\Controller\LibrosController',
            'Application\Controller\Librosred' => 'Application\Controller\LibrosredController',
            'Application\Controller\Librossemillero' => 'Application\Controller\LibrossemilleroController',
            'Application\Controller\Capitulosred' => 'Application\Controller\CapitulosredController',
            'Application\Controller\Capitulosusuario' => 'Application\Controller\CapitulosusuarioController',
            'Application\Controller\Capitulosgrupo' => 'Application\Controller\CapitulosgrupoController',
            'Application\Controller\Capitulossemillero' => 'Application\Controller\CapitulossemilleroController',
            'Application\Controller\Produccionesinvred' => 'Application\Controller\ProduccionesinvredController',
            'Application\Controller\Produccionesinvsemillero' => 'Application\Controller\ProduccionesinvsemilleroController',
            'Application\Controller\Agregarautorgrupo' => 'Application\Controller\AgregarautorgrupoController',
            'Application\Controller\Agregarautorrepositorio' => 'Application\Controller\AgregarautorrepositorioController',
            'Application\Controller\Agregarautorred' => 'Application\Controller\AgregarautorredController',
            'Application\Controller\Agregarautorrol' => 'Application\Controller\AgregarautorrolController',
            'Application\Controller\Agregarautorusuario' => 'Application\Controller\AgregarautorusuarioController',
            'Application\Controller\Agregarautorsemillero' => 'Application\Controller\AgregarautorsemilleroController',
            'Application\Controller\Gruposred' => 'Application\Controller\GruposredController',
            'Application\Controller\Gruposaplicari' => 'Application\Controller\GruposaplicariController',
            'Application\Controller\Grupossemillero' => 'Application\Controller\GrupossemilleroController',
            'Application\Controller\Proyectosext' => 'Application\Controller\ProyectosextController',
            'Application\Controller\Consultasemi' => 'Application\Controller\ConsultasemiController',
            'Application\Controller\hsemilleroinvestigacion' => 'Application\Controller\hsemilleroinvestigacionController',
            'Application\Controller\Consultaredes' => 'Application\Controller\ConsultaredesController',
            'Application\Controller\hredinvestigacion' => 'Application\Controller\hredinvestigacionController',
            'Application\Controller\Semilleros' => 'Application\Controller\SemillerosController',
            'Application\Controller\Agregarresponsable' => 'Application\Controller\AgregarresponsableController',
            'Application\Controller\Sesionesperiodicasformacion' => 'Application\Controller\SesionesperiodicasformacionController',
            'Application\Controller\Sesionesperiodicasestudiantes' => 'Application\Controller\SesionesperiodicasestudiantesController',
            'Application\Controller\Instituciones' => 'Application\Controller\InstitucionesController',
            'Application\Controller\Otrasproducciones' => 'Application\Controller\OtrasproduccionesController',
            'Application\Controller\Otrasproduccioneshv' => 'Application\Controller\OtrasproduccioneshvController',
            'Application\Controller\Articuloshv' => 'Application\Controller\ArticuloshvController',
            'Application\Controller\Libroshv' => 'Application\Controller\LibroshvController',
            'Application\Controller\Lineashv' => 'Application\Controller\LineashvController',
            'Application\Controller\Proyectosexthv' => 'Application\Controller\ProyectosexthvController',
            'Application\Controller\Experiencialabhv' => 'Application\Controller\ExperiencialabhvController',
            'Application\Controller\Formacionacahv' => 'Application\Controller\FormacionacahvController',
            'Application\Controller\Formacioncomhv' => 'Application\Controller\FormacioncomhvController',
            'Application\Controller\Archivos' => 'Application\Controller\ArchivosController',
            'Application\Controller\Archivosred' => 'Application\Controller\ArchivosredController',
            'Application\Controller\Archivossemillero' => 'Application\Controller\ArchivossemilleroController',
            'Application\Controller\Consultagi' => 'Application\Controller\ConsultagiController',
            'Application\Controller\hgrupoinvestigacion' => 'Application\Controller\hgrupoinvestigacionController',           
            'Application\Controller\hvinvestigador' => 'Application\Controller\hvinvestigadorController',
            'Application\Controller\Consultagp' => 'Application\Controller\ConsultagpController',
            'Application\Controller\Consultai' => 'Application\Controller\ConsultaiController',
            'Application\Controller\Consultae' => 'Application\Controller\ConsultaeController',
            'Application\Controller\Paresevaluadores' => 'Application\Controller\ParesevaluadoresController',
            'Application\Controller\Idiomashv' => 'Application\Controller\IdiomashvController',
            'Application\Controller\Areashv' => 'Application\Controller\AreashvController',
            'Application\Controller\Actividadeshv' => 'Application\Controller\ActividadeshvController',
            'Application\Controller\Bibliograficos' => 'Application\Controller\BibliograficosController',
            'Application\Controller\Bibliograficoshv' => 'Application\Controller\BibliograficoshvController',
            'Application\Controller\Convocatoria' => 'Application\Controller\ConvocatoriaController',
            'Application\Controller\Propuestainv' => 'Application\Controller\PropuestainvController',
            'Application\Controller\Convocatoriai' => 'Application\Controller\ConvocatoriaiController',
            'Application\Controller\Editarconvocatoriai' => 'Application\Controller\EditarconvocatoriaiController',
            'Application\Controller\Convocatoriae' => 'Application\Controller\ConvocatoriaeController',
            'Application\Controller\Editarconvocatoriae' => 'Application\Controller\EditarconvocatoriaeController',
            'Application\Controller\Convocatoriam' => 'Application\Controller\ConvocatoriamController',
            'Application\Controller\Editarconvocatoriam' => 'Application\Controller\EditarconvocatoriamController',
            'Application\Controller\Convocatorias' => 'Application\Controller\ConvocatoriasController',
            'Application\Controller\Editarconvocatorias' => 'Application\Controller\EditarconvocatoriasController',
            'Application\Controller\Cronograma' => 'Application\Controller\CronogramaController',
            'Application\Controller\Criterioevaluacion' => 'Application\Controller\CriterioevaluacionController',
            'Application\Controller\Cronogramaap' => 'Application\Controller\CronogramaapController',
            'Application\Controller\Aspectoeval' => 'Application\Controller\AspectoevalController',
            'Application\Controller\Asignareval' => 'Application\Controller\AsignarevalController',
            'Application\Controller\Requisitos' => 'Application\Controller\RequisitosController',
            'Application\Controller\Requisitosdoc' => 'Application\Controller\RequisitosdocController',
            'Application\Controller\Requisitosapdoc' => 'Application\Controller\RequisitosapdocController',
            'Application\Controller\Requisitosap' => 'Application\Controller\RequisitosapController',
            'Application\Controller\Tablafin' => 'Application\Controller\TablafinController',
            'Application\Controller\Tablafinp' => 'Application\Controller\TablafinpController',
            'Application\Controller\Consulconvocatoria' => 'Application\Controller\ConsulconvocatoriaController',
            'Application\Controller\Rolesconv' => 'Application\Controller\RolesconvController',
            'Application\Controller\Archivosconv' => 'Application\Controller\ArchivosconvController',
            'Application\Controller\Url' => 'Application\Controller\UrlController',
            'Application\Controller\Camposadd' => 'Application\Controller\CamposaddController',
            'Application\Controller\Editareval' => 'Application\Controller\EditarevalController',
            'Application\Controller\Camposaddproy' => 'Application\Controller\CamposaddproyController',
            'Application\Controller\Aplicar' => 'Application\Controller\AplicarController',
            'Application\Controller\Aplicarm' => 'Application\Controller\AplicarmController',
            'Application\Controller\Aplicari' => 'Application\Controller\AplicariController',
            'Application\Controller\Editaraplicari' => 'Application\Controller\EditaraplicariController',
            'Application\Controller\Editaraplicar' => 'Application\Controller\EditaraplicarController',
            'Application\Controller\Editaraplicarm' => 'Application\Controller\EditaraplicarmController',
            'Application\Controller\Entrevistam' => 'Application\Controller\EntrevistamController',
            'Application\Controller\Aprobacionm' => 'Application\Controller\AprobacionmController',
            'Application\Controller\Editartablafin' => 'Application\Controller\EditartablafinController',
            'Application\Controller\Editartablafinp' => 'Application\Controller\EditartablafinpController',
            'Application\Controller\Consulproyectos' => 'Application\Controller\ConsulproyectosController',
            'Application\Controller\Consulmisproyectos' => 'Application\Controller\ConsulmisproyectosController',
            'Application\Controller\Evaluarproy' => 'Application\Controller\EvaluarproyController',
            'Application\Controller\Evaluar' => 'Application\Controller\EvaluarController',
            'Application\Controller\Editarevaluar' => 'Application\Controller\EditarevaluarController',
            'Application\Controller\Evaluarcriterio' => 'Application\Controller\EvaluarcriterioController',
            'Application\Controller\Prueba' => 'Application\Controller\PruebaController',
            'Application\Controller\Tablaequipo' => 'Application\Controller\TablaequipoController',
            'Application\Controller\Tablaequipop' => 'Application\Controller\TablaequipopController',
            'Application\Controller\Gruposparticipantes' => 'Application\Controller\GruposparticipantesController',
            'Application\Controller\Editartablae' => 'Application\Controller\EditartablaeController',
            'Application\Controller\Editartablaep' => 'Application\Controller\EditartablaepController',
            'Application\Controller\Asignarol' => 'Application\Controller\AsignarolController',
            'Application\Controller\Proyectosinv' => 'Application\Controller\ProyectosinvController',
            'Application\Controller\Agregarproyectosinv' => 'Application\Controller\AgregarproyectosinvController',
            'Application\Controller\Agregarhorario' => 'Application\Controller\AgregarhorarioController',
            'Application\Controller\Proyectos' => 'Application\Controller\ProyectosController',
            'Application\Controller\Consultaproyecto' => 'Application\Controller\ConsultaproyectoController',
            'Application\Controller\Consultamonitor' => 'Application\Controller\ConsultamonitorController',
            'Application\Controller\Editarproyecto' => 'Application\Controller\EditarproyectoController',
            'Application\Controller\Copiarconvocatoria' => 'Application\Controller\CopiarconvocatoriaController',
            'Application\Controller\Gestionrequi' => 'Application\Controller\GestionrequiController',
            'Application\Controller\Gestionproy' => 'Application\Controller\GestionproyController',
            'Application\Controller\Gestionproym' => 'Application\Controller\GestionproymController',
            'Application\Controller\Articulo038' => 'Application\Controller\Articulo038Controller',
            'Application\Controller\Informes' => 'Application\Controller\InformesController',
            'Application\Controller\Informesm' => 'Application\Controller\InformesmController',
            'Application\Controller\Avalcumpliconvo' => 'Application\Controller\AvalcumpliconvoController',
            'Application\Controller\Editarinforme' => 'Application\Controller\EditarinformeController',
            'Application\Controller\Editarinformem' => 'Application\Controller\EditarinformemController',
            'Application\Controller\Editarmonitor' => 'Application\Controller\EditarmonitorController',
            'Application\Controller\Actas' => 'Application\Controller\ActasController',
            'Application\Controller\Entidadesejecutoras' => 'Application\Controller\EntidadesejecutorasController',
            'Application\Controller\Reporteconvocatoria' => 'Application\Controller\ReporteconvocatoriaController',
            'Application\Controller\Reporteinvpre' => 'Application\Controller\ReporteinvpreController',
            'Application\Controller\Pdf' => 'Application\Controller\PdfController',
            'Application\Controller\Pdfgi' => 'Application\Controller\PdfgiController',
            'Application\Controller\Pdfmonitores' => 'Application\Controller\PdfmonitoresController',
            'Application\Controller\Pdfsemilleros' => 'Application\Controller\PdfsemillerosController',
            'Application\Controller\Pdfredes' => 'Application\Controller\PdfredesController',
            'Application\Controller\Excelsemilleros' => 'Application\Controller\ExcelsemillerosController',
            'Application\Controller\Excelsemillerostodo' => 'Application\Controller\ExcelsemillerostodoController',
            'Application\Controller\Excelredestodo' => 'Application\Controller\ExcelredestodoController',
            'Application\Controller\Excelgrupostodo' => 'Application\Controller\ExcelgrupostodoController',
            'Application\Controller\Excelredes' => 'Application\Controller\ExcelredesController',
            'Application\Controller\Reportesemilleros' => 'Application\Controller\ReportesemillerosController',
            'Application\Controller\Reporteredes' => 'Application\Controller\ReporteredesController',
            'Application\Controller\Pdfsiafi' => 'Application\Controller\PdfsiafiController',
            'Application\Controller\Pdfsiafi' => 'Application\Controller\PdfsiafiController',
            'Application\Controller\Gruposproy' => 'Application\Controller\GruposproyController',
            'Application\Controller\Actasm' => 'Application\Controller\ActasmController',
            'Application\Controller\Excelmonitores' => 'Application\Controller\ExcelmonitoresController'
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'partials/paginator' => __DIR__ . '/../view/partials/paginator.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array()
        )
    )
);
