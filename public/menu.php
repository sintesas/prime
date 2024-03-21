<style>
	.notifications .dropdown {display: inline;}
	.notifications .dropdown-menu {width: 500px;top: 32px;padding: 10px; }
	.dropdown-menu-inner {background: #E5E9F2;padding: 10px;display: inline-block; width: 100%;}
</style>



	<?php
		$bandLogin = true;
		if( strpos($_SERVER['REQUEST_URI'], "login") === false){
			$bandLogin = false;

		}	
	?>
	<?php 
		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		);

		/*		$footer = file_get_contents("https://estilos.upn.edu.co/identidad/header.json", false, stream_context_create($arrContextOptions));
		$footers=json_decode($footer);
		*/
		$footer_wordpress = file_get_contents("https://estilos.upn.edu.co/userfiles/json/gov_co_wordpress.json", false, stream_context_create($arrContextOptions));
		$footers_word=json_decode($footer_wordpress);
	?>
	<div><?php echo $footers_word->gov_co;?></div>
	
	<nav id="line_header">
		
		<div class="line-header"></div>
		<div class="block-header transition">
			
			

			<div class="left-bh">

				


				<a href="#">
					<img class="hideforresposive" src="<?php echo $this->basePath(); ?>/public/images/images_oscar/logo.png" alt="Logo" style="width: 100%">
					<img class="showforresposive" src="<?php echo $this->basePath(); ?>/public/images/images_oscar/logo-responsive.png" alt="Logo" style="width: 100%">
				</a>
			</div>
			<div class="right-bh">
				<?php if ($this->menu == 0 && $bandLogin) { ?>
					<div class="btn_left transition pointer login_user"><i class="fa fa-user" aria-hidden="true"></i> Iniciar Sesión</div>
				<?php } ?>
				<?php if ($this->menu != 0) { ?>
					
					<div id="div_nombre" class="btn_left transition pointer" style="border: none; padding-top: 8px; cursor: default;">
					</div>
					<div class="btn_left transition pointer user_card"><i class="fa fa-user" aria-hidden="true"></i> Usuario</div>
					<div class="btn_left transition pointer "><a href="<?php echo $this->basePath() ?>/application/login/cerrar">Cerrar Sesión</a></div>
				<?php } ?>
				<div class="menue-mobile openmenuemobile showforresposive"><i class="fa fa-bars" aria-hidden="true"></i></div>
			</div>
		</div>
		<div class="bottom-header">
			<?php if ($this->menu != 0) {?>
				<div id="notifications" class="tools-menue hideforresposive">
					<?php
	            		if($this->dataDocumentos != null){
							?>
								<div class="dropdown tools-item transition pointer" id="div_documents">
					            	<i class="fa fa-bell alerts_icon dropdown-toggle" data-toggle="dropdown" aria-hidden="true"></i>
									<div class="dropdown-menu dropdown-menu-right" style="left: -230px !important; min-width: 250px!important; color:black; background: #E5E9F2;">
										<div class="dropdown-menu-inner" id="div_txt_documents" style="text-align:justify !important; font-family: 'Noto Sans', sans-serif;">
											<?php
												$dataIni = $this->dataDocumentos[0];
												$varDocumento = "Le ha sido solicitada la vinculación de un producto. Por favor consulte el enlace de solicitudes de vinculación de productos. <br /> Solicitante: ";
												foreach ($this->usuarios as $usuaT) {
										            if($usuaT["id_usuario"] == $dataIni["id_usuario_solicitud"]){
										                $varDocumento .= trim($usuaT["primer_nombre"])." ".trim($usuaT["segundo_nombre"])." ".trim($usuaT["primer_apellido"])." ".trim($usuaT["segundo_apellido"]);
										            }
										        }
										        if($dataIni["modulo"] == 1){
													$varDocumento .= "<br /> Grupo: ";
													foreach ($this->Grupoinvestigacion as $grupo) {
					                                    if($grupo["id_grupo_inv"] == $dataIni["id_grupoinv"]){
					                                        $varDocumento .= $grupo["nombre_grupo"];
					                                        break;
					                                    }
                            						}
										        }else if($dataIni["modulo"] == 2){
										        	$varDocumento .= "<br /> Red: ";
										        	foreach ($this->Redinvestigacion as $red) {
					                                    if($red["id"] == $dataIni["id_grupoinv"]){
					                                        $varDocumento .= $red["nombre"];
					                                        break;
					                                    }
					                                }
										        }else if($dataIni["modulo"] == 3){
										        	$varDocumento .= "<br /> Semillero: ";
										        	foreach ($this->Semillero as $semillero) {
					                                    if($semillero["id"] == $dataIni["id_grupoinv"]){
					                                        $varDocumento .= $semillero["nombre"];
					                                        break;
					                                    }
					                                }
										        }
										        echo $varDocumento;
										        echo '<script type="text/javascript">';
												echo 'localStorage.setItem("varDocumento", "'.$varDocumento.'");';
												echo '</script>';

					 						?>
										</div>						
									</div>
								</div>
							<?php
	            		}else{
	            			?>
								<div class="dropdown tools-item transition pointer" id="div_documents" style="display:none;">
					            	<i class="fa fa-bell alerts_icon dropdown-toggle" data-toggle="dropdown" aria-hidden="true"></i>
									<div class="dropdown-menu dropdown-menu-right" style="left: -230px !important; min-width: 250px !important; color:black; background: #E5E9F2;justify-content: space-around;">
										<div class="dropdown-menu-inner" id="div_txt_documents" style="text-align:justify !important; font-family: 'Noto Sans', sans-serif;">
										</div>						
									</div>
								</div>
							<?php
	            		}
	            	?>
		            
					<?php
	            		if(count($this->asignareval) > 0){
							?>
								<div class="dropdown tools-item transition pointer" id="div_propuesta">
					            	<i class="fa fa-pencil-square-o alerts_icon dropdown-toggle" data-toggle="dropdown" aria-hidden="true"></i>
									<div class="dropdown-menu dropdown-menu-right" style="left: -230px !important; min-width: 250px!important; color:black; background: #E5E9F2;" id="div_txt_propuesta">
										<div class="dropdown-menu-inner" style="text-align:justify !important;font-family: 'Noto Sans', sans-serif;">
											<?php
												$dataIni = $this->asignareval[0];
												$varPropuesta = "Usted ha sido asignado como evaluador de la propuesta de investigación ";
												foreach ($this->aplicar as $aplicarm) {
				                                    if($aplicarm["id_aplicar"] == $dataIni["id_aplicar"]){
				                                        $varPropuesta = $varPropuesta.$aplicarm["nombre_proy"];
				                                        break;
				                                    }
				                                }
												$varPropuesta = $varPropuesta.". Por favor diríjase al modulo Convocatorias - Evaluar propuesta de investigación.";
												echo $varPropuesta;
												echo '<script type="text/javascript">';
												echo 'localStorage.setItem("varPropuesta", "'.$varPropuesta.'");';
												echo '</script>';
					 						?>
										</div>						
									</div>
								</div>
							<?php
	            		}else{
	            			?>
								<div class="dropdown tools-item transition pointer" id="div_propuesta" style="display:none;">
					            	<i class="fa fa-pencil-square-o alerts_icon dropdown-toggle" data-toggle="dropdown" aria-hidden="true"></i>
									<div class="dropdown-menu dropdown-menu-right" style="left: -230px !important; min-width: 250px!important; color:black; background: #E5E9F2;">
										<div class="dropdown-menu-inner" id="div_txt_propuesta" style="text-align:justify !important; font-family: 'Noto Sans', sans-serif;">
											
										</div>						
									</div>
								</div>
							<?php
	            		}
	            	?>
				</div>
			<?php } ?>
			<!-- Menu Start-->
			<div class="menue">
				<?php if ($this->menu == 0 && !$bandLogin) { ?>
					<div class="menue-item transition pointer">
						<a class="active hvr-bounce-to-right login_user" href="<?php echo $this->basePath() ?>/application/login/index">Iniciar sesión</a>
					</div>
				<?php } else if ($this->menu == 1) { ?>
					<div class="menue-item transition pointer">
						<a href="<?php echo $this->basePath() ?>/application/index/index">Inicio</a>
					</div>
					<div class="menue-item transition pointer">
						<a>Administración y Procesos de Soporte</a>
						<div class="submenu_web" style="display: none;">
							<a href="<?php echo $this->basePath() ?>/application/usuario/index">
								<div class="submenuli">Gestión de Usuarios</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/banner/index">
								<div class="submenuli">Banner</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/multimedia/index">
								<div class="submenuli">Multimedia</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/tipovalores/index">
								<div class="submenuli">Tipos de Valores</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/gestionarsolicitudes/index">
								<div class="submenuli">Gestionar Solicitudes</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/solicitudes/index">
								<div class="submenuli">Realizar Solicitudes</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/mensajeusuario/index">
								<div class="submenuli">Mensaje a Usuario(s)</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/roles/index">
								<div class="submenuli">Gestión de Roles y Permisos</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/gestionpantallas/index">
								<div class="submenuli">Gestión de módulos, submódulos y formularios</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/auditoria/index">
								<div class="submenuli">Auditoria</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/reportes/index">
								<div class="submenuli">Reportes</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/graficas/index">
								<div class="submenuli">Reportes estadísticos</div>
							</a>
						</div>
					</div>
					<div class="menue-item transition pointer">
						<a>Herramientas de Socialización</a>
						<div class="submenu_web" style="display: none;">
							<a href="<?php echo $this->basePath() ?>/application/noticias/index">
								<div class="submenuli">Noticias</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/eventos/index">
								<div class="submenuli">Eventos</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/foro/index">
								<div class="submenuli">Foro</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/repositorio/index">
								<div class="submenuli">Repositorio</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultagi/index">
								<div class="submenuli">Consulta de Grupos de Investigación</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultaredes/index">
								<div class="submenuli">Consulta Redes</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultasemi/index">
								<div class="submenuli">Consulta Semilleros/Otros procesos de formación</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultagp/index">
								<div class="submenuli">Consulta de Proyectos</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultai/index">
								<div class="submenuli">Consulta de Investigadores</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultae/index">
								<div class="submenuli">Consulta de Evaluadores</div>
							</a>
						</div>
					</div>
					<div class="menue-item transition pointer">
						<a>Grupos, Formación y Redes de Investigación</a>
						<div class="submenu_web" style="display: none;">
							<a href="<?php echo $this->basePath() ?>/application/grupoinv/index">
								<div class="submenuli">Gestión de Grupos de Investigación</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/redesinv/index">
								<div class="submenuli">Redes</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/semillerosinv/index">
								<div class="submenuli">Semilleros/Otros Procesos de Formación</div>
							</a>
						</div>
					</div>
					<div class="menue-item transition pointer">
						<a>Convocatorias</a>
						<div class="submenu_web" style="display: none;">
							<a href="<?php echo $this->basePath() ?>/application/convocatoria/index">
								<div class="submenuli">Crear Nueva Convocatoria</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consulconvocatoria/index">
								<div class="submenuli">Consulta de Convocatorias</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consulproyectos/index">
								<div class="submenuli">Consulta de Propuestas</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/evaluarproy/index">
								<div class="submenuli">Evaluar Propuesta de Investigación</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultaprocesom/index">
								<div class="submenuli">Consulta proceso de monitoria</div>
							</a>
						</div>
					</div>
					<div class="menue-item transition pointer">
						<a>Proyectos</a>
						<div class="submenu_web" style="display: none;">
							<a href="<?php echo $this->basePath() ?>/application/proyectos/index">
								<div class="submenuli">Crear Nuevo Proyecto</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultaproyecto/index">
								<div class="submenuli">Consulta de Proyectos</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultamonitores/index">
								<div class="submenuli">Consulta de monitores</div>
							</a>
						</div>
					</div>
				<?php } else if ($this->menu != 1 && $this->menu != 0) { ?>
					<div class="menue-item transition pointer">
						<a href="<?php echo $this->basePath() ?>/application/index/index">Inicio</a>
					</div>
					<div class="menue-item transition pointer">
						<a>Administración y Procesos de Soporte</a>
						<div class="submenu_web" style="display: none;">
							<a href="<?php echo $this->basePath() ?>/application/solicitudes/index">
								<div class="submenuli">Realizar Solicitudes</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/mensajeadministrador/index">
								<div class="submenuli">Mensaje al Administrador</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/cambiarcontrasena/index">
								<div class="submenuli">Cambiar de Clave </div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/reportes/index">
								<div class="submenuli">Reportes</div>
							</a>
						</div>
					</div>
					<div class="menue-item transition pointer">
						<a>Herramientas de Socialización</a>
						<div class="submenu_web" style="display: none;">
							<a href="<?php echo $this->basePath() ?>/application/noticias/index">
								<div class="submenuli">Noticias</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/foro/index">
								<div class="submenuli">Foro</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/repositorio/index">
								<div class="submenuli">Repositorio</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultagi/index">
								<div class="submenuli">Consulta de Grupos de Investigación</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultaredes/index">
								<div class="submenuli">Consulta Redes</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultasemi/index">
								<div class="submenuli">Consulta Semilleros/Otros procesos de formación</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultagp/index">
								<div class="submenuli">Consulta de Proyectos</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultai/index">
								<div class="submenuli">Consulta de Investigadores</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultae/index">
								<div class="submenuli">Consulta de Evaluadores</div>
							</a>
						</div>
					</div>
					<div class="menue-item transition pointer">
						<a>Grupos, Formación y Redes de Investigación</a>
						<div class="submenu_web" style="display: none;">
							<a href="<?php echo $this->basePath() ?>/application/grupoinv/index">
								<div class="submenuli">Editar Grupos de Investigación</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/redesinv/index">
								<div class="submenuli">Editar Redes</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/semillerosinv/index">
								<div class="submenuli">Editar Semilleros/Otros Procesos de Formación</div>
							</a>
						</div>
					</div>
					<div class="menue-item transition pointer">
						<a>Convocatorias</a>
						<div class="submenu_web" style="display: none;">
							<a href="<?php echo $this->basePath() ?>/application/evaluarproy/index">
								<div class="submenuli">Evaluar Propuesta de Investigación</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consulconvocatoria/index">
								<div class="submenuli">Consulta de Convocatorias</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consulproyectos/index">
								<div class="submenuli">Consulta de Propuestas</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultaprocesom/index">
								<div class="submenuli">Consulta proceso de monitoria</div>
							</a>
						</div>
					</div>
					<div class="menue-item transition pointer">
						<a>Proyectos</a>
						<div class="submenu_web" style="display: none;">
							<a href="<?php echo $this->basePath() ?>/application/consultaproyecto/index">
								<div class="submenuli">Consulta de Proyectos</div>
							</a>
							<a href="<?php echo $this->basePath() ?>/application/consultamonitores/index">
								<div class="submenuli">Consulta de monitores</div>
							</a>
						</div>
					</div>
				<?php } ?>
				<!-- Menu End-->
			</div>

		</div>
	</nav>
	<?php
	if (strpos($this->basePath(), "public", 0) == false) {
		$ruta_imagen = $this->basePath() . "/public";
	} else {
		$ruta_imagen = $this->basePath();
	}
	?>
	<div class="menu-slide transition">
		<div class="close-menu"><i class="fa fa-times" aria-hidden="true"></i></div>
		<div class="content-menu-slide">
			<div class="logo-uni"><img id="logo_carne" src="<?php echo $ruta_imagen; ?>/images/logo_carne.jpg"></div>
			<div class="avatar">
				<img src="" id="avatar">
			</div>
			<div class="name-user" id="name">... ...</div>
			<div class="info-row">C.C. <span id="iden">...</span></div>
			<div class="info-row">Email: <span id="email">...</span></div>
			<div class="info-row">Rol: <span id="rol">...</span></div>
			<div class="info-row">
				<div class="info-button"><a href="<?php echo $this->basePath() ?>/application/editarusuario/index/<?php echo $this->usuario ?>">Editar Usuario</a></div>
			</div>
			<?php
				if ($this->menu != 1) {
					?>
					<div class="info-row">
						<div class="info-button"><a href="<?php echo $this->basePath() ?>/application/mensajeadministrador/index">Pedir Ayuda</a></div>
					</div>
					<?php
				}
			?>
			<div class="info-row">
				<div class="info-button"><a href="<?php echo $this->basePath() ?>/application/login/cerrar">Cerrar Sesión</a></div>
			</div>
		</div>
	</div>
	<script>
		document.getElementById("avatar").setAttribute("src", "<?php echo $ruta_imagen; ?>/images/uploads/usu_" + localStorage.getItem("id_usu") + "_" + localStorage.getItem("fotoUsuario"));
		$('#name').html("").html(localStorage.getItem("nombreUsuario") + " " + localStorage.getItem("apellidosUsuario"));
		$('#iden').html("").html(localStorage.getItem("tipoDocu") + ": " + localStorage.getItem("documentoUsuario"));
		$('#email').html("").html(localStorage.getItem("mailUsuario"));
		$('#rol').html("").html(localStorage.getItem("tipoVinculacion") + " - " + localStorage.getItem("rolNombre"));
		$('#div_nombre').html("").html(localStorage.getItem("nombreUsuario") + " " + localStorage.getItem("apellidosUsuario"));
		if(document.getElementById('div_documents').style.display === 'none' && localStorage.getItem("varDocumento") != null){
			$('#div_txt_documents').html("").html(localStorage.getItem("varDocumento"));
			document.getElementById('div_documents').style.display = "block";
		}
		if(document.getElementById('div_propuesta').style.display === 'none' && localStorage.getItem("varPropuesta") != null){
			$('#div_txt_propuesta').html("").html(localStorage.getItem("varPropuesta"));
			document.getElementById('div_propuesta').style.display = "block";
		}
		$(document).ready(function() {
		    $(window).trigger('resize');
		});
		 
		$(window).resize(function() {
		    if(window.innerWidth < 750){
		    	document.getElementById("div_nombre").style.display = "none";
			}else{
				document.getElementById("div_nombre").style.display = "block";
			}
		});
		
	</script>
	<div class="content">
		<div class="boxed clearfix">
			<?php if($this->titulo != "Inicio") {
				if ($this->titulo != "bienvenido") { ?>
			<div class="col col100 clearfix">
				<?php if (isset($_SESSION['navegador'])) {
					if (array_key_exists($this->titulo, $_SESSION['navegador'])) {
						# si existe el titulo, borra todos los siguintes y deja el actual como el ultimo
						$eliminar = false;
						foreach ($_SESSION['navegador'] as $titulo => $url) {
							if (!$eliminar) {
								if ($titulo == $this->titulo) {
									$eliminar = true;
								}
							} else {
								unset($_SESSION['navegador'][$titulo]);
							}
						}
					} else {
						# si no existe solo lo crea de ultimo
						$actual_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
						# Guarda el titulo de la pantall mas la url actual, por si se quiere devolver
						$_SESSION['navegador'][$this->titulo] = $actual_url;
					}
				} else {
					$_SESSION['navegador'] = array();
					$actual_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					# Guarda el titulo de la pantall mas la url actual, por si se quiere devolver
					$_SESSION['navegador'][$this->titulo] = $actual_url;
				}
				# Creo la visual del navegador.
				if (isset($_SESSION['navegador']) && count($_SESSION['navegador']) > 0) { ?>
					<div class="bread">
						<div class="home-bread"><i class="fa fa-link" aria-hidden="true"></i> </div>
						<?php
						foreach ($_SESSION['navegador'] as $titulo => $url) {
							if ($titulo == $this->titulo) {
								echo '<div class="active-bread"><a href="' . $url . '">' . $titulo . '</a> </div>';
							} else {
								echo '<div class="item-bread"><a href="' . $url . '">' . $titulo . '</a> <i class="fa fa-chevron-right" aria-hidden="true"></i> </div>';
							}
						} ?>
					</div>
				<?php } ?>
			</div>
			<?php } } ?>

<script type="text/javascript" src="<?php echo $this->basePath() ?>/public/js/viejo/bootstrap.min.js"></script>