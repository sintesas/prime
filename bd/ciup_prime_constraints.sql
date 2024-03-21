--
-- Name: aps_documentos aps_doc; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_documentos
    ADD CONSTRAINT aps_doc PRIMARY KEY (id_doc);


--
-- Name: aps_eventosusu aps_eventosusu_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_eventosusu
    ADD CONSTRAINT aps_eventosusu_pkey PRIMARY KEY (id_evento);


--
-- Name: aps_identificadoresusu aps_identificadoresusu_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_identificadoresusu
    ADD CONSTRAINT aps_identificadoresusu_pkey PRIMARY KEY (id_identificador);


--
-- Name: aps_permisos aps_permiso; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_permisos
    ADD CONSTRAINT aps_permiso PRIMARY KEY (id_permiso);


--
-- Name: aps_roles aps_rol; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_roles
    ADD CONSTRAINT aps_rol PRIMARY KEY (id_rol);


--
-- Name: aps_roles_usuario aps_roles_usuario_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_roles_usuario
    ADD CONSTRAINT aps_roles_usuario_pk PRIMARY KEY (id_rol_usuario);


--
-- Name: aps_solicitudes aps_sol; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_solicitudes
    ADD CONSTRAINT aps_sol PRIMARY KEY (id_sol);


--
-- Name: aps_tipos_valores aps_tipval; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_tipos_valores
    ADD CONSTRAINT aps_tipval PRIMARY KEY (id_tipo_valor);


--
-- Name: aps_trabajogradousu aps_trabajogradousu_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_trabajogradousu
    ADD CONSTRAINT aps_trabajogradousu_pkey PRIMARY KEY (id_trabajogrado);


--
-- Name: aps_usuarios aps_usuario; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_usuarios
    ADD CONSTRAINT aps_usuario PRIMARY KEY (id_usuario);


--
-- Name: aps_valores_flexibles aps_valflex; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_valores_flexibles
    ADD CONSTRAINT aps_valflex PRIMARY KEY (id_valor_flexible);


--
-- Name: hsi_foro hsi_foro_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE hsi_foro
    ADD CONSTRAINT hsi_foro_pk PRIMARY KEY (id_foro);


--
-- Name: mgc_aplicar mgc_aplicar_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_aplicar
    ADD CONSTRAINT mgc_aplicar_pk PRIMARY KEY (id_aplicar);


--
-- Name: mgc_aspectos_eval mgc_aspecto_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_aspectos_eval
    ADD CONSTRAINT mgc_aspecto_pk PRIMARY KEY (id_aspecto);


--
-- Name: mgc_aspectos_eval_proy mgc_aspecto_proy_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_aspectos_eval_proy
    ADD CONSTRAINT mgc_aspecto_proy_pk PRIMARY KEY (id_aspecto);


--
-- Name: mgc_campos_add mgc_campos_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_campos_add
    ADD CONSTRAINT mgc_campos_pk PRIMARY KEY (id_campo_add);


--
-- Name: mgc_campos_add_proy mgc_campos_proy_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_campos_add_proy
    ADD CONSTRAINT mgc_campos_proy_pk PRIMARY KEY (id_campo_add);


--
-- Name: mgc_convocatoria mgc_convoca_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_convocatoria
    ADD CONSTRAINT mgc_convoca_pk PRIMARY KEY (id_convocatoria);


--
-- Name: mgc_cronograma mgc_cronograma_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_cronograma
    ADD CONSTRAINT mgc_cronograma_pk PRIMARY KEY (id_cronograma);


--
-- Name: mgc_evaluador_conv mgc_evaluador_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_evaluador_conv
    ADD CONSTRAINT mgc_evaluador_pk PRIMARY KEY (id_evaluador);


--
-- Name: mgc_financiacion mgc_financia_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_financiacion
    ADD CONSTRAINT mgc_financia_pk PRIMARY KEY (id_financiacion);


--
-- Name: mgc_financiacion_proy mgc_financia_proy_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_financiacion_proy
    ADD CONSTRAINT mgc_financia_proy_pk PRIMARY KEY (id_financiacion);


--
-- Name: mgc_requisitos_doc mgc_requisito_doc_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_requisitos_doc
    ADD CONSTRAINT mgc_requisito_doc_pk PRIMARY KEY (id_requisito_doc);


--
-- Name: mgc_requisitos mgc_requisito_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_requisitos
    ADD CONSTRAINT mgc_requisito_pk PRIMARY KEY (id_requisito);


--
-- Name: mgc_roles mgc_rol_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_roles
    ADD CONSTRAINT mgc_rol_pk PRIMARY KEY (id_rol_conv);


--
-- Name: mgc_urls mgc_url_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_urls
    ADD CONSTRAINT mgc_url_pk PRIMARY KEY (id_url);


--
-- Name: mgi_eventosgru mgi_eventosgru_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgi_eventosgru
    ADD CONSTRAINT mgi_eventosgru_pkey PRIMARY KEY (id_evento);


--
-- Name: mgi_identificadoresgru mgi_identificadoresgru_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgi_identificadoresgru
    ADD CONSTRAINT mgi_identificadoresgru_pkey PRIMARY KEY (id_identificador);


--
-- Name: mgi_trabajogradogru mgi_trabajogradogru_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgi_trabajogradogru
    ADD CONSTRAINT mgi_trabajogradogru_pkey PRIMARY KEY (id_trabajogrado);


--
-- Name: mgp_entidadesejecutoras mgp_entidadesejecutoras_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgp_entidadesejecutoras
    ADD CONSTRAINT mgp_entidadesejecutoras_pkey PRIMARY KEY (id_entidadejecutora);


--
-- Name: mri_eventosred mri_eventosred_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_eventosred
    ADD CONSTRAINT mri_eventosred_pkey PRIMARY KEY (id_evento);


--
-- Name: mri_identificadoresred mri_identificadoresred_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_identificadoresred
    ADD CONSTRAINT mri_identificadoresred_pkey PRIMARY KEY (id_identificador);


--
-- Name: mri_trabajogradored mri_trabajogradored_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_trabajogradored
    ADD CONSTRAINT mri_trabajogradored_pkey PRIMARY KEY (id_trabajogrado);


--
-- Name: msi_eventossemi msi_eventossemi_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_eventossemi
    ADD CONSTRAINT msi_eventossemi_pkey PRIMARY KEY (id_evento);


--
-- Name: msi_identificadoressemi msi_identificadoressemi_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_identificadoressemi
    ADD CONSTRAINT msi_identificadoressemi_pkey PRIMARY KEY (id_identificador);


--
-- Name: msi_trabajogradosemi msi_trabajogradosemi_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_trabajogradosemi
    ADD CONSTRAINT msi_trabajogradosemi_pkey PRIMARY KEY (id_trabajogrado);


--
-- Name: multimedia multimedia_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE multimedia
    ADD CONSTRAINT multimedia_pkey PRIMARY KEY (id_multimedia);


--
-- Name: adm_modulo pk_adm_modulo; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE adm_modulo
    ADD CONSTRAINT pk_adm_modulo PRIMARY KEY (id);


--
-- Name: adm_pantalla pk_adm_pantalla; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE adm_pantalla
    ADD CONSTRAINT pk_adm_pantalla PRIMARY KEY (id);


--
-- Name: adm_submodulo pk_adm_submodulo; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE adm_submodulo
    ADD CONSTRAINT pk_adm_submodulo PRIMARY KEY (id);


--
-- Name: aps_auditoria pk_aps_auditoria; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_auditoria
    ADD CONSTRAINT pk_aps_auditoria PRIMARY KEY (id_auditoria);


--
-- Name: aps_hv_autorusuario pk_aps_hv_autorusuario; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_hv_autorusuario
    ADD CONSTRAINT pk_aps_hv_autorusuario PRIMARY KEY (id);


--
-- Name: aps_hv_capitulosusuario pk_aps_hv_capitulosusuario; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_hv_capitulosusuario
    ADD CONSTRAINT pk_aps_hv_capitulosusuario PRIMARY KEY (id);


--
-- Name: mri_articulored pk_articulored; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_articulored
    ADD CONSTRAINT pk_articulored PRIMARY KEY (id);


--
-- Name: mri_capitulored pk_capitulored; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_capitulored
    ADD CONSTRAINT pk_capitulored PRIMARY KEY (id);


--
-- Name: mri_equipodirectivo pk_gri_equipodirectivo; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_equipodirectivo
    ADD CONSTRAINT pk_gri_equipodirectivo PRIMARY KEY (id);


--
-- Name: mgi_red_inv pk_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgi_red_inv
    ADD CONSTRAINT pk_id PRIMARY KEY (id);


--
-- Name: mri_librored pk_librored; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_librored
    ADD CONSTRAINT pk_librored PRIMARY KEY (id);


--
-- Name: mgc_coinvestigadores pk_mgc_coinvestigadores; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_coinvestigadores
    ADD CONSTRAINT pk_mgc_coinvestigadores PRIMARY KEY (id);


--
-- Name: mgc_contratacionpersonal pk_mgc_contratacionpersonal; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_contratacionpersonal
    ADD CONSTRAINT pk_mgc_contratacionpersonal PRIMARY KEY (id);


--
-- Name: mgc_gruposaplicari pk_mgc_gruposaplicari; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_gruposaplicari
    ADD CONSTRAINT pk_mgc_gruposaplicari PRIMARY KEY (id);


--
-- Name: mgc_objetivometas pk_mgc_objetivometas; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_objetivometas
    ADD CONSTRAINT pk_mgc_objetivometas PRIMARY KEY (id);


--
-- Name: mgc_objetivosespecificos pk_mgc_objetivosespecificos; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_objetivosespecificos
    ADD CONSTRAINT pk_mgc_objetivosespecificos PRIMARY KEY (id);


--
-- Name: mgc_paresevaluadores pk_mgc_paresevaluadores; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_paresevaluadores
    ADD CONSTRAINT pk_mgc_paresevaluadores PRIMARY KEY (id);


--
-- Name: mgc_responsablesap pk_mgc_responsables; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_responsablesap
    ADD CONSTRAINT pk_mgc_responsables PRIMARY KEY (id);


--
-- Name: mgc_sesionesestudiantes pk_mgc_sesionesestudiantes; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_sesionesestudiantes
    ADD CONSTRAINT pk_mgc_sesionesestudiantes PRIMARY KEY (id);


--
-- Name: mgc_sesionesformacion pk_mgc_sesionesformacion; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_sesionesformacion
    ADD CONSTRAINT pk_mgc_sesionesformacion PRIMARY KEY (id);


--
-- Name: mgi_autorgrupo pk_mgi_autorgrupo; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgi_autorgrupo
    ADD CONSTRAINT pk_mgi_autorgrupo PRIMARY KEY (id);


--
-- Name: mgi_capitulosgrupo pk_mgi_capitulosgrupo; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgi_capitulosgrupo
    ADD CONSTRAINT pk_mgi_capitulosgrupo PRIMARY KEY (id);


--
-- Name: mgi_documentosvinculados pk_mgi_documentosvinculados; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgi_documentosvinculados
    ADD CONSTRAINT pk_mgi_documentosvinculados PRIMARY KEY (id);


--
-- Name: mri_autorred pk_mri_autorred; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_autorred
    ADD CONSTRAINT pk_mri_autorred PRIMARY KEY (id);


--
-- Name: mri_contactored pk_mri_contactored; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_contactored
    ADD CONSTRAINT pk_mri_contactored PRIMARY KEY (id);


--
-- Name: mri_documentosbibliograficos pk_mri_documentosbibliograficos; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_documentosbibliograficos
    ADD CONSTRAINT pk_mri_documentosbibliograficos PRIMARY KEY (id);


--
-- Name: mri_gruposred pk_mri_gruposred; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_gruposred
    ADD CONSTRAINT pk_mri_gruposred PRIMARY KEY (id);


--
-- Name: mri_integrantesred pk_mri_integrantesred; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_integrantesred
    ADD CONSTRAINT pk_mri_integrantesred PRIMARY KEY (id);


--
-- Name: mri_proyectored pk_mri_proyectored; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_proyectored
    ADD CONSTRAINT pk_mri_proyectored PRIMARY KEY (id);


--
-- Name: mri_serviciosred pk_mri_serviciosred; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_serviciosred
    ADD CONSTRAINT pk_mri_serviciosred PRIMARY KEY (id);


--
-- Name: msi_area pk_msi_area; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_area
    ADD CONSTRAINT pk_msi_area PRIMARY KEY (id);


--
-- Name: msi_articulosemillero pk_msi_articulosemillero; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_articulosemillero
    ADD CONSTRAINT pk_msi_articulosemillero PRIMARY KEY (id);


--
-- Name: msi_autorsemillero pk_msi_autorsemillero; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_autorsemillero
    ADD CONSTRAINT pk_msi_autorsemillero PRIMARY KEY (id);


--
-- Name: msi_capitulosemillero pk_msi_capitulosemillero; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_capitulosemillero
    ADD CONSTRAINT pk_msi_capitulosemillero PRIMARY KEY (id);


--
-- Name: msi_documentosbibliograficos pk_msi_documentosbibliograficos; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_documentosbibliograficos
    ADD CONSTRAINT pk_msi_documentosbibliograficos PRIMARY KEY (id);


--
-- Name: msi_grupossemillero pk_msi_grupossemillero; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_grupossemillero
    ADD CONSTRAINT pk_msi_grupossemillero PRIMARY KEY (id);


--
-- Name: msi_integrantes pk_msi_integrantes; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_integrantes
    ADD CONSTRAINT pk_msi_integrantes PRIMARY KEY (id);


--
-- Name: msi_librossemillero pk_msi_librossemillero; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_librossemillero
    ADD CONSTRAINT pk_msi_librossemillero PRIMARY KEY (id);


--
-- Name: msi_produccionesinvsemillero pk_msi_produccionesinvsemillero; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_produccionesinvsemillero
    ADD CONSTRAINT pk_msi_produccionesinvsemillero PRIMARY KEY (id);


--
-- Name: msi_reconocimientos pk_msi_reconocimientos; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_reconocimientos
    ADD CONSTRAINT pk_msi_reconocimientos PRIMARY KEY (id);


--
-- Name: msi_semillerosinv pk_msi_semillerosinv; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE msi_semillerosinv
    ADD CONSTRAINT pk_msi_semillerosinv PRIMARY KEY (id);


--
-- Name: rep_autor pk_rep_autor; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE rep_autor
    ADD CONSTRAINT pk_rep_autor PRIMARY KEY (id);


--
-- Name: mri_produccionesinvred pk_roduccionesinvred; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mri_produccionesinvred
    ADD CONSTRAINT pk_roduccionesinvred PRIMARY KEY (id);


--
-- Name: adm_pantalla adm_pantalla_id_submodulo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE adm_pantalla
    ADD CONSTRAINT adm_pantalla_id_submodulo_fkey FOREIGN KEY (id_submodulo) REFERENCES adm_submodulo(id);


--
-- Name: adm_submodulo adm_submodulo_id_modulo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE adm_submodulo
    ADD CONSTRAINT adm_submodulo_id_modulo_fkey FOREIGN KEY (id_modulo) REFERENCES adm_modulo(id);


--
-- Name: aps_permisos aps_permisos_id_pantalla_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_permisos
    ADD CONSTRAINT aps_permisos_id_pantalla_fkey FOREIGN KEY (id_pantalla) REFERENCES adm_pantalla(id);


--
-- Name: aps_permisos aps_permisos_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_permisos
    ADD CONSTRAINT aps_permisos_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES aps_roles(id_rol);


--
-- Name: aps_roles_usuario aps_roles_usuario_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_roles_usuario
    ADD CONSTRAINT aps_roles_usuario_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES aps_roles(id_rol);


--
-- Name: aps_solicitudes aps_solicitudes_id_estado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_solicitudes
    ADD CONSTRAINT aps_solicitudes_id_estado_fkey FOREIGN KEY (id_estado) REFERENCES aps_valores_flexibles(id_valor_flexible);


--
-- Name: aps_valores_flexibles aps_valores_flexibles_id_tipo_valor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE aps_valores_flexibles
    ADD CONSTRAINT aps_valores_flexibles_id_tipo_valor_fkey FOREIGN KEY (id_tipo_valor) REFERENCES aps_tipos_valores(id_tipo_valor);


--
-- Name: hsi_foro hsi_foro_id_autor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE hsi_foro
    ADD CONSTRAINT hsi_foro_id_autor_fkey FOREIGN KEY (id_autor) REFERENCES aps_usuarios(id_usuario);


--
-- Name: mgc_objetivometas mgc_objetivometas_id_objetivo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE mgc_objetivometas
    ADD CONSTRAINT mgc_objetivometas_id_objetivo_fkey FOREIGN KEY (id_objetivo) REFERENCES mgc_objetivosespecificos(id);
