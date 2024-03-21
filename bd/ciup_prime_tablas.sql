CREATE SEQUENCE adm_modulo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

--
-- Name: adm_modulo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE adm_modulo (
    id integer DEFAULT nextval('adm_modulo_id_seq') NOT NULL,
    nombre character varying(100) NOT NULL
);


--
-- Name: adm_pantalla_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE adm_pantalla_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: adm_pantalla; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE adm_pantalla (
    id integer DEFAULT nextval('adm_pantalla_id_seq') NOT NULL,
    id_submodulo integer NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion character varying(800)
);


--
-- Name: adm_submodulo_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE adm_submodulo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: adm_submodulo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE adm_submodulo (
    id integer DEFAULT nextval('adm_submodulo_id_seq') NOT NULL,
    id_modulo integer NOT NULL,
    nombre character varying(100) NOT NULL
);


--
-- Name: aps_auditoria_id_auditoria_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_auditoria_id_auditoria_seq
    START WITH 38960
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_auditoria; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_auditoria (
    id_auditoria integer DEFAULT nextval('aps_auditoria_id_auditoria_seq') NOT NULL,
    id_usuario integer,
    fecha_ingreso timestamp without time zone,
    fecha_salida timestamp without time zone,
    ip_terminal character(20),
    evento character(600),
    sid character(100)
);


--
-- Name: aps_auditoria_det_id_auditoria_det_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_auditoria_det_id_auditoria_det_seq
    START WITH 64535
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_auditoria_det; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_auditoria_det (
    id_auditoria_det integer DEFAULT nextval('aps_auditoria_det_id_auditoria_det_seq') NOT NULL,
    id_auditoria integer,
    evento character varying(45000)
);


--
-- Name: aps_documentos_id_doc_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_documentos_id_doc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_documentos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_documentos (
    id_doc integer DEFAULT nextval('aps_documentos_id_doc_seq') NOT NULL,
    nombre character(100),
    descripcion character(500),
    archivo_bytes bytea,
    archivo_oid oid,
    mime character(100),
    size double precision,
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date
);


--
-- Name: aps_eventosusu; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_eventosusu (
    id_evento integer NOT NULL,
    id_tipoevento integer,
    id_tipoparticipacion integer,
    nombre_evento character varying(500),
    nombre_trabajo character varying(500),
    id_institucion integer,
    ciudad_trabajo character varying(500),
    fecha_inicio character varying(10),
    fecha_fin character varying(10),
    otra_informacion character varying(4000),
    id_tipomedio integer,
    nombre_trabajo_medio character varying(500),
    id_autor integer,
    id_institucion_medio integer,
    ciudad_medio character varying(500),
    medio_divulgacion character varying(500),
    fecha_medio character varying(10),
    descripcion_medio character varying(4000),
    otra_informacion_medio character varying(4000),
    archivo character varying(500),
    id_red integer
);


--
-- Name: aps_eventosusu_id_evento_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_eventosusu_id_evento_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_eventosusu_id_evento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE aps_eventosusu_id_evento_seq OWNED BY aps_eventosusu.id_evento;


--
-- Name: aps_experiencia_prof_id_experiencia_prof_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_experiencia_prof_id_experiencia_prof_seq
    START WITH 3641
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_experiencia_prof; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_experiencia_prof (
    id_experiencia_prof integer DEFAULT nextval('aps_experiencia_prof_id_experiencia_prof_seq') NOT NULL,
    empresa character varying(500),
    tipo_vinculacion character varying(500),
    dedicacion_horaria character varying(50),
    periodo_vinculacion character(100),
    cargo character varying(300),
    descripcion_actividades character varying(4000),
    otra_info character varying(4000),
    id_usuario integer,
    fecha_inicio date,
    fecha_fin date,
    archivo character varying(500)
);


--
-- Name: aps_hv_actividades_id_actividades_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_actividades_id_actividades_seq
    START WITH 3157
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_actividades; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_actividades (
    id_actividades integer DEFAULT nextval('aps_hv_actividades_id_actividades_seq') NOT NULL,
    id_usuario integer,
    tipo character varying(120),
    descripcion character varying(500),
    fecha date,
    id_pais character varying(50),
    dedicacion character varying(50),
    tema integer,
    instituciones integer,
    documento_vinculacion character varying(2000),
    valor character varying(100),
    archivo character varying(500)
);


--
-- Name: aps_hv_areas_id_area_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_areas_id_area_seq
    START WITH 639
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_areas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_areas (
    id_area integer DEFAULT nextval('aps_hv_areas_id_area_seq') NOT NULL,
    id_usuario integer,
    objeto character varying(4000),
    nombre_area integer,
    archivo character varying(500)
);


--
-- Name: aps_hv_articulos_id_gi_articulo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_articulos_id_gi_articulo_seq
    START WITH 2137
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_articulos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_articulos (
    id_gi_articulo integer DEFAULT nextval('aps_hv_articulos_id_gi_articulo_seq') NOT NULL,
    nombre_revista character varying(500),
    nombre_articulo character varying(500),
    id_pais character varying(50),
    fecha date,
    issn character varying(100),
    paginas character varying(20),
    num_paginas integer,
    volumen character varying(200),
    serie character varying(200),
    id_autor integer,
    coautor character varying(200),
    id_usuario integer,
    pagina_inicio integer,
    pagina_fin integer,
    fasciculo integer,
    id_departamento integer,
    id_ciudad character varying(50),
    mes character varying(10),
    ano character varying(10),
    categoria character varying(10),
    archivo character varying(500)
);


--
-- Name: aps_hv_autorusuario_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_autorusuario_id_seq
    START WITH 479
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_autorusuario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_autorusuario (
    id integer DEFAULT nextval('aps_hv_autorusuario_id_seq') NOT NULL,
    id_grupo integer NOT NULL,
    id_usuario integer NOT NULL,
    seccion character varying(20) NOT NULL,
    id_objeto integer
);


--
-- Name: aps_hv_bibliograficos_id_bibliografico_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_bibliograficos_id_bibliografico_seq
    START WITH 347
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_bibliograficos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_bibliograficos (
    id_bibliografico integer DEFAULT nextval('aps_hv_bibliograficos_id_bibliografico_seq') NOT NULL,
    id_usuario integer,
    nombre_documento character varying(300),
    numero_paginas integer,
    instituciones character varying(300),
    ano character varying(10),
    mes character varying(10),
    num_indexacion integer,
    url character varying(120),
    medio_divulgacion character varying(130),
    descripcion character varying(500),
    autores character varying(120),
    pais character varying(50),
    ciudad character varying(50),
    id_autor integer,
    archivo character varying(500)
);


--
-- Name: aps_hv_capitulosusuario_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_capitulosusuario_id_seq
    START WITH 892
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_capitulosusuario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_capitulosusuario (
    id integer DEFAULT nextval('aps_hv_capitulosusuario_id_seq') NOT NULL,
    titulo character varying(500) NOT NULL,
    paginas character varying(20),
    ano character varying(20) NOT NULL,
    mes character varying(20) NOT NULL,
    pais character varying(200),
    ciudad character varying(200),
    serie character varying(50),
    editorial character varying(500),
    edicion character varying(500),
    isbn character varying(20),
    lugar_publicacion character varying(500),
    medio_divulgacion character varying(500),
    titulo_capitulo character varying(500) NOT NULL,
    numero_capitulo character varying(50) NOT NULL,
    paginas_capitulo character varying(50) NOT NULL,
    pagina_inicio character varying(20) NOT NULL,
    pagina_fin character varying(20) NOT NULL,
    id_usuario integer NOT NULL,
    id_autor integer,
    archivo character varying(500)
);


--
-- Name: aps_hv_formacion_aca_id_formacion_aca_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_formacion_aca_id_formacion_aca_seq
    START WITH 1632
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_formacion_aca; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_formacion_aca (
    id_formacion_aca integer DEFAULT nextval('aps_hv_formacion_aca_id_formacion_aca_seq') NOT NULL,
    tipo_formacion character varying(10),
    titulo_obtenido character varying(500),
    fecha_inicio date,
    fecha_grado date,
    id_pais character varying(50),
    horas integer,
    id_usuario integer,
    id_ciudad character varying(50),
    id_departamento integer,
    fecha_fin date,
    nombre_formacion character varying(500),
    institucion integer,
    archivo character varying(500)
);


--
-- Name: aps_hv_formacion_com_id_formacion_com_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_formacion_com_id_formacion_com_seq
    START WITH 1267
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_formacion_com; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_formacion_com (
    id_formacion_com integer DEFAULT nextval('aps_hv_formacion_com_id_formacion_com_seq') NOT NULL,
    tipo_formacion character varying(500),
    nombre_formacion character varying(500),
    titulo_formacion character varying(500),
    fecha_inicio date,
    fecha_grado date,
    horas integer,
    id_usuario integer,
    fecha_fin date,
    pais character varying(50),
    ciudad character varying(50),
    institucion integer,
    archivo character varying(500)
);


--
-- Name: aps_hv_idiomas_id_idioma_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_idiomas_id_idioma_seq
    START WITH 840
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_idiomas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_idiomas (
    id_idioma integer DEFAULT nextval('aps_hv_idiomas_id_idioma_seq') NOT NULL,
    id_usuario integer,
    nombre character varying(500),
    oir character varying(4),
    hablar character varying(4),
    escribir character varying(4),
    leer character varying(4),
    modalidad character varying(50),
    archivo character varying(500)
);


--
-- Name: aps_hv_libro_id_gi_libro_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_libro_id_gi_libro_seq
    START WITH 872
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_libros; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_libros (
    id_gi_libro integer DEFAULT nextval('aps_hv_libro_id_gi_libro_seq') NOT NULL,
    titulo_libro character varying(200),
    paginas character varying(200),
    num_paginas character varying(20),
    fecha date,
    serie character varying(200),
    editorial character varying(200),
    edicion character varying(200),
    isbn character varying(500),
    lugar_publicacion character varying(500),
    medio_divulgacion character varying(500),
    autores character varying(500),
    instituciones character varying(500),
    id_usuario integer,
    capitulo_libro character varying(20),
    mes character varying(10),
    ano character varying(10),
    pais character varying(50),
    ciudad character varying(50),
    id_autor integer,
    archivo character varying(500),
    tipo_libro character varying(20)
);


--
-- Name: aps_hv_lineas_id_grupo_inv_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_lineas_id_grupo_inv_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_lineas_id_linea_inv_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_lineas_id_linea_inv_seq
    START WITH 1193
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_lineas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_lineas (
    id_linea_inv integer DEFAULT nextval('aps_hv_lineas_id_linea_inv_seq') NOT NULL,
    id_usuario integer DEFAULT nextval('aps_hv_lineas_id_grupo_inv_seq') NOT NULL,
    nombre_linea character varying(200) DEFAULT 'nextval(''aps_hv_lineas_nombre_linea_seq'')'::character varying NOT NULL,
    objetivo character varying(4000) DEFAULT 'nextval(''aps_hv_lineas_objetivo_seq'')'::character varying NOT NULL,
    efectos character varying(4000),
    logros character varying(4000),
    id_estado integer,
    archivo character varying(500)
);


--
-- Name: aps_hv_lineas_nombre_linea_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_lineas_nombre_linea_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_lineas_objetivo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_lineas_objetivo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_otra_prod_id_otra_prod_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_otra_prod_id_otra_prod_seq
    START WITH 3220
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_otra_prod; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_otra_prod (
    id_otra_prod integer DEFAULT nextval('aps_hv_otra_prod_id_otra_prod_seq') NOT NULL,
    id_usuario integer,
    nombre_producto character varying(300),
    descripcion_producto character varying(5000),
    tipo_producto character varying(5000),
    fecha date,
    id_pais character varying(50),
    id_ciudad character varying(50),
    instituciones character varying(5000),
    registro character varying(5000),
    autores character varying(500),
    otra_info character varying(5000),
    id_departamento integer,
    mes character varying(10),
    ano character varying(10),
    id_autor integer,
    archivo character varying(500)
);


--
-- Name: aps_hv_proyectos_ext_id_proyecto_ext_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_proyectos_ext_id_proyecto_ext_seq
    START WITH 530
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_proyectos_ext; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_proyectos_ext (
    id_proyecto_ext integer DEFAULT nextval('aps_hv_proyectos_ext_id_proyecto_ext_seq') NOT NULL,
    id_usuario integer,
    codigo_proyecto character varying(300),
    tipo_proyecto character varying(300),
    fecha_inicio date,
    fecha_fin date,
    resumen_ejecutivo character varying(500),
    objetivo_general character varying(500),
    equipo_trabajo character varying(500),
    productos_derivados character varying(4000),
    nombre_proyecto character varying(500),
    id_rol character varying(50),
    archivo character varying(500)
);


--
-- Name: aps_hv_proyectosint_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_hv_proyectosint_id_seq
    START WITH 561
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_hv_proyectosint; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_hv_proyectosint (
    id integer DEFAULT nextval('aps_hv_proyectosint_id_seq') NOT NULL,
    id_grupo_inv integer,
    id_proyecto integer,
    archivo character varying(500)
);


--
-- Name: aps_identificadoresusu; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_identificadoresusu (
    id_identificador integer NOT NULL,
    id_tipoidentificador integer,
    id_tipocategoria integer,
    id_field character varying(50),
    fecha_registro character varying(10),
    nombre character varying(500),
    web character varying(500),
    ciudad character varying(500),
    descripcion character varying(4000),
    otra_informacion character varying(4000),
    archivo character varying(500),
    id_red integer
);


--
-- Name: aps_identificadoresusu_id_identificador_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_identificadoresusu_id_identificador_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_identificadoresusu_id_identificador_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE aps_identificadoresusu_id_identificador_seq OWNED BY aps_identificadoresusu.id_identificador;


--
-- Name: aps_permisos_id_permiso_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_permisos_id_permiso_seq
    START WITH 581
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_permisos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_permisos (
    id_permiso integer DEFAULT nextval('aps_permisos_id_permiso_seq') NOT NULL,
    id_rol integer,
    id_pantalla integer
);


--
-- Name: aps_roles_id_rol_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_roles_id_rol_seq
    START WITH 27
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_roles (
    id_rol integer DEFAULT nextval('aps_roles_id_rol_seq') NOT NULL,
    descripcion character(100),
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date,
    observaciones character(300),
    opcion_pantalla integer
);


--
-- Name: aps_roles_usuario_id_rol_usuario_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_roles_usuario_id_rol_usuario_seq
    START WITH 12611
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_roles_usuario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_roles_usuario (
    id_rol_usuario integer DEFAULT nextval('aps_roles_usuario_id_rol_usuario_seq') NOT NULL,
    id_usuario integer,
    id_rol integer,
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date
);


--
-- Name: aps_roles_usuario2; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_roles_usuario2 (
    id_rol_usuario integer NOT NULL,
    id_usuario integer,
    id_rol integer,
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date
);


--
-- Name: aps_solicitudes_id_sol_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_solicitudes_id_sol_seq
    START WITH 49
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_solicitudes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_solicitudes (
    id_sol integer DEFAULT nextval('aps_solicitudes_id_sol_seq') NOT NULL,
    id_tipo_sol integer,
    justificacion character(200),
    observaciones character(300),
    fecha date,
    id_estado integer,
    lugar character(60),
    valor integer,
    nueva_fecha date,
    fecha_inicio date,
    fecha_fin date,
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date,
    archivo character(120),
    archivo_res character(300),
    codigo_proy character(120)
);


--
-- Name: aps_tipos_valores_id_tipo_valor_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_tipos_valores_id_tipo_valor_seq
    START WITH 59
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_tipos_valores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_tipos_valores (
    id_tipo_valor integer DEFAULT nextval('aps_tipos_valores_id_tipo_valor_seq') NOT NULL,
    descripcion character(70),
    activo character(1),
    id_tipo_valor_padre integer,
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date
);


--
-- Name: aps_trabajogradousu; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_trabajogradousu (
    id_trabajogrado integer NOT NULL,
    nombre_trabajo character varying(500),
    id_tipotrabajo integer,
    id_estadotipotrabajo integer,
    id_tipoparticipacion integer,
    id_autor integer,
    id_institucion integer,
    ciudad_trabajo character varying(500),
    id_unidad integer,
    id_dependencia integer,
    id_programa integer,
    fecha_inicio character varying(10),
    fecha_fin character varying(10),
    descripcion character varying(4000),
    otra_informacion character varying(4000),
    id_formacioninvestigador integer,
    codigo_proyecto character varying(500),
    nombre_proyecto character varying(500),
    id_institucion_proyecto integer,
    ciudad_proyecto character varying(500),
    personas_formadas character varying(30),
    fecha_inicio_proyecto character varying(10),
    fecha_fin_proyecto character varying(10),
    descripcion_proyecto character varying(4000),
    descripcion_formacion character varying(4000),
    otra_informacion_proyecto character varying(4000),
    id_semillero integer,
    id_institucion_semillero integer,
    id_rolparticipacion integer,
    fecha_inicio_semillero character varying(10),
    fecha_fin_semillero character varying(10),
    tematica character varying(4000),
    descripcion_semillero character varying(4000),
    archivo character varying(500),
    id_red integer,
    id_investigador integer
);


--
-- Name: aps_trabajogradousu_id_trabajogrado_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_trabajogradousu_id_trabajogrado_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_trabajogradousu_id_trabajogrado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE aps_trabajogradousu_id_trabajogrado_seq OWNED BY aps_trabajogradousu.id_trabajogrado;


--
-- Name: aps_usuarios_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_usuarios_id_usuario_seq
    START WITH 13646
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_usuarios; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_usuarios (
    id_usuario integer DEFAULT nextval('aps_usuarios_id_usuario_seq') NOT NULL,
    primer_nombre character varying(100),
    segundo_nombre character varying(100),
    primer_apellido character varying(100),
    segundo_apellido character varying(100),
    documento character varying(50),
    direccion character(100),
    id_ciudad integer,
    telefono character(50),
    celular character(15),
    email character(60),
    fecha_nacimiento date,
    id_lugar_nacimiento integer,
    id_sexo integer,
    usuario character(60),
    contrasena character(20),
    id_nacionalidad integer,
    id_estado character(1),
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date,
    intentos integer,
    estado_civil integer,
    id_unidad_academica integer,
    id_dependencia_academica integer,
    id_tipo_vinculacion integer,
    direccion_trabajo character(120),
    telefono_trabajo character(50),
    email2 character(100),
    id_tipo_documento integer,
    archivo character(120),
    id_programa_academico integer,
    cod_estudiante character varying(50),
    new_archivo character varying(3),
    cargo_actual integer,
    evaluador character varying(2),
    tipo_evaluador character varying(8),
    institucion integer
);


--
-- Name: aps_valores_flexibles_id_valor_flexible_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE aps_valores_flexibles_id_valor_flexible_seq
    START WITH 855
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aps_valores_flexibles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE aps_valores_flexibles (
    id_valor_flexible integer DEFAULT nextval('aps_valores_flexibles_id_valor_flexible_seq') NOT NULL,
    id_tipo_valor integer,
    descripcion_valor character varying(1000),
    activo character(1),
    sigla_valor character(50),
    valor_flexible_padre_id integer,
    atributo1 character(250),
    atributo2 character(250),
    atributo3 character(250),
    atributo4 character(250),
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date
);


--
-- Name: ciup_registrados; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE ciup_registrados (
    ano numeric(4,0) NOT NULL,
    periodo numeric(2,0) NOT NULL,
    cedula character varying(20) NOT NULL,
    nombre character varying(62) NOT NULL,
    facultad character varying(60) NOT NULL,
    programa character varying(60) NOT NULL,
    promedio character varying(15) NOT NULL,
    creditos_validos character varying(15) NOT NULL,
    creditos_programa character varying(15) NOT NULL,
    creditos_pendientes character varying(15) NOT NULL,
    registro_not character varying(15),
    top_25 character varying(15) NOT NULL,
    fecha date NOT NULL
);


--
-- Name: hsi_eventos_id_evento_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE hsi_eventos_id_evento_seq
    START WITH 53
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hsi_eventos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE hsi_eventos (
    id_evento integer DEFAULT nextval('hsi_eventos_id_evento_seq') NOT NULL,
    titulo character varying(200),
    evento character varying(10000),
    archivo character varying(120),
    fecha_inicio date,
    fecha_fin date,
    estado character varying(1),
    fecha_crea date,
    usuario_crea character(60),
    fecha_mod date,
    usuario_mod character(100),
    url character varying(500),
    new_archivo character varying(20)
);


--
-- Name: hsi_foro_id_foro_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE hsi_foro_id_foro_seq
    START WITH 84
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hsi_foro; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE hsi_foro (
    id_foro integer DEFAULT nextval('hsi_foro_id_foro_seq') NOT NULL,
    id_autor integer,
    titulo character varying(500),
    mensaje character varying(10000),
    fecha timestamp without time zone,
    respuestas integer,
    identificador integer,
    ult_respuesta date,
    estado character(1),
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date,
    archivo character(120)
);


--
-- Name: hsi_noticias_id_noticia_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE hsi_noticias_id_noticia_seq
    START WITH 53
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hsi_noticias; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE hsi_noticias (
    id_noticia integer DEFAULT nextval('hsi_noticias_id_noticia_seq') NOT NULL,
    titulo character varying(10000),
    noticia character varying(500),
    fecha_inicio date,
    fecha_fin date,
    estado character varying(1),
    usuario_crea character varying(500),
    fecha_crea date,
    usuario_mod character varying(500),
    fecha_mod date,
    url character varying(500),
    archivo character varying(120),
    new_archivo character varying(20),
    imagen character varying(1000)
);


--
-- Name: mg_archivos_conv_id_archivo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mg_archivos_conv_id_archivo_seq
    START WITH 208
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_aplicar_id_aplicar_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_aplicar_id_aplicar_seq
    START WITH 11412
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_aplicar; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_aplicar (
    id_aplicar integer DEFAULT nextval('mgc_aplicar_id_aplicar_seq') NOT NULL,
    id_convocatoria integer,
    codigo_proy character(50),
    nombre_proy character(1500),
    id_investigador integer,
    duracion integer,
    id_categoria integer,
    id_programa_inv integer,
    id_campo integer,
    id_linea_inv integer,
    id_unidad_academica integer,
    id_area_tematica integer,
    recursos_funcion integer,
    recursos_inversion integer,
    total_financia integer,
    total_proy character varying(30),
    resumen_ejecutivo character(14000),
    objetivo_general character(8000),
    id_doc integer,
    id_estado integer,
    id_aprobada integer,
    nombre_estudiante character(300),
    codigo_estudiante character(300),
    documento_estudiante character(300),
    facultad character(30),
    programa character(30),
    direccion character(30),
    telefono character(15),
    creditos_aprobados character(10),
    horas_monitoria character(10),
    horario_disponible character(10),
    horario_clase character(10),
    perfil_laboral character(500),
    aceptar_condiciones character(1),
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date,
    periodo character(1),
    id_dependencia_academica integer,
    id_programa_academico integer,
    instituciones_coofinanciacion integer,
    area_tematica character varying(52000),
    descriptores character varying(2000),
    antecedentes character varying(46000),
    planteamiento_problema character varying(20000),
    marco_teorico character varying(115000),
    estado_arte character varying(115000),
    bibliografia character varying(22000),
    metodologia character varying(60000),
    compromisos_conocimiento character varying(42000),
    semestresano character varying(500),
    momentos_proyecto character varying(127000),
    id_semillero integer,
    nombre_modalidad character varying(5000)
);


--
-- Name: mgc_aplicarm; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_aplicarm (
    id_aplicar integer DEFAULT nextval('mgc_aplicar_id_aplicar_seq') NOT NULL,
    num_codigo integer,
    id_facultad character(300),
    id_programa_curricular character(300),
    id_usuario integer,
    id_convocatoria integer,
    id_proyecto integer,
    promedio_ponderado character varying(10),
    creditos_aprobados character varying(10),
    cumplimiento_conjunto character varying(10),
    creditos_programa character varying(10),
    semestre integer,
    fecha date,
    justificacion character varying(15000),
    fecha_entrevista date,
    obervaciones_entrevista character varying(15000),
    estado_seleccionado character varying(30),
    fecha_verificacion date,
    estado_aprobacion character varying(30),
    obervaciones_aprobacion character varying(15000),
    estado_aprobacionm character varying(30),
    evaluacion_cuantitativa numeric(3,2)
);


--
-- Name: mgc_aplicarm_horario_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_aplicarm_horario_id_seq
    START WITH 3881
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_aplicarm_horario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_aplicarm_horario (
    id integer DEFAULT nextval('mgc_aplicarm_horario_id_seq') NOT NULL,
    id_aplicar integer NOT NULL,
    dia character varying(30),
    hora_inicio character(20),
    hora_fin character(20)
);


--
-- Name: mgc_archivos_conv; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_archivos_conv (
    id_archivo integer DEFAULT nextval('mg_archivos_conv_id_archivo_seq') NOT NULL,
    id_convocatoria integer,
    archivo character(120),
    id_tipo_archivo integer,
    visible character(1),
    new_archivo character varying(3)
);


--
-- Name: mgc_aspectos_eval_id_aspecto_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_aspectos_eval_id_aspecto_seq
    START WITH 215
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_aspectos_eval; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_aspectos_eval (
    id_aspecto integer DEFAULT nextval('mgc_aspectos_eval_id_aspecto_seq') NOT NULL,
    id_convocatoria integer,
    id_tipo_aspecto integer,
    ponderacion1 integer,
    id_ponderacion2 integer,
    id_estado integer,
    descripcion character(1000),
    observaciones character(1000),
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date,
    id_tipo_ponderacion integer
);


--
-- Name: mgc_aspectos_eval_proy_id_aspecto_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_aspectos_eval_proy_id_aspecto_seq
    START WITH 81
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_aspectos_eval_proy; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_aspectos_eval_proy (
    id_aspecto integer DEFAULT nextval('mgc_aspectos_eval_proy_id_aspecto_seq') NOT NULL,
    id_aplicar integer,
    id_ponderacion1 integer,
    id_ponderacion2 integer,
    id_estado integer,
    descripcion character(1000),
    observaciones character(1000),
    fecha_evaluacion date
);


--
-- Name: mgc_avalcumplimiento_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_avalcumplimiento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_avalcumplimiento; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_avalcumplimiento (
    id integer DEFAULT nextval('mgc_avalcumplimiento_id_seq') NOT NULL,
    informe character(120),
    fecha_limite date,
    estado character varying(30),
    id_convocatoria integer
);


--
-- Name: mgc_campos_add_id_campo_add_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_campos_add_id_campo_add_seq
    START WITH 47
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_campos_add; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_campos_add (
    id_campo_add integer DEFAULT nextval('mgc_campos_add_id_campo_add_seq') NOT NULL,
    id_convocatoria integer,
    titulo character(100),
    descripcion character(500),
    objetivo character(500),
    obligatorio character(1),
    valor character(500),
    id_tipo_campo integer,
    id_tipo_valor integer,
    id_estado integer,
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date
);


--
-- Name: mgc_campos_add_proy_id_campo_add_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_campos_add_proy_id_campo_add_seq
    START WITH 1710
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_campos_add_proy; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_campos_add_proy (
    id_campo_add integer DEFAULT nextval('mgc_campos_add_proy_id_campo_add_seq') NOT NULL,
    id_aplicar integer,
    titulo character(100),
    valor character(500)
);


--
-- Name: mgc_coinvestigadores_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_coinvestigadores_id_seq
    START WITH 13
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_coinvestigadores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_coinvestigadores (
    id integer DEFAULT nextval('mgc_coinvestigadores_id_seq') NOT NULL,
    id_aplicari integer NOT NULL,
    id_tipodocumento integer,
    documento character varying(200),
    apellidos character varying(200),
    nombres character varying(200),
    profesion character varying(200),
    intitucion character varying(200),
    telefono character varying(200),
    email character varying(200),
    horas character varying(200)
);


--
-- Name: mgc_contratacionpersonal_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_contratacionpersonal_id_seq
    START WITH 82
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_contratacionpersonal; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_contratacionpersonal (
    id integer DEFAULT nextval('mgc_contratacionpersonal_id_seq') NOT NULL,
    id_aplicari integer NOT NULL,
    tipo_vinculacion integer,
    personas character varying(20),
    objeto character varying(2500),
    justificacion character varying(2500),
    valor character varying(20)
);


--
-- Name: mgc_convocatoria_id_convocatoria_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_convocatoria_id_convocatoria_seq
    START WITH 276
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_convocatoria; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_convocatoria (
    id_convocatoria integer DEFAULT nextval('mgc_convocatoria_id_convocatoria_seq') NOT NULL,
    titulo character(500),
    descripcion character varying(5000),
    observaciones character varying(5000),
    tipo_conv character varying(2),
    fecha_apertura date,
    fecha_cierre date,
    id_entidad integer,
    id_proyectos integer,
    numero_monitores integer,
    fecha_lim_soporte date,
    cronograma character(1),
    id_estado character(1),
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date,
    hora_cierre character(10),
    hora_apertura character(10)
);


--
-- Name: mgc_criterioevaluacion_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_criterioevaluacion_id_seq
    START WITH 31
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_criterioevaluacion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_criterioevaluacion (
    id integer DEFAULT nextval('mgc_criterioevaluacion_id_seq') NOT NULL,
    id_convocatoria integer NOT NULL,
    criterio character varying(10000) NOT NULL
);


--
-- Name: mgc_cronograma_id_cronograma_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_cronograma_id_cronograma_seq
    START WITH 909
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_cronograma; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_cronograma (
    id_cronograma integer DEFAULT nextval('mgc_cronograma_id_cronograma_seq') NOT NULL,
    id_convocatoria integer,
    nombre_actividad character(500),
    descripcion character varying(5000),
    objetivo character varying(5000),
    fecha_inicio date,
    fecha_cierre date,
    id_estado integer,
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date,
    prioridad integer
);


--
-- Name: mgc_cronogramaap_id_cronograma_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_cronogramaap_id_cronograma_seq
    START WITH 5799
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_cronograma_ap; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_cronograma_ap (
    id_cronograma integer DEFAULT nextval('mgc_cronogramaap_id_cronograma_seq') NOT NULL,
    id_aplicar integer,
    nombre_actividad character varying(500),
    descripcion character varying(500),
    objetivo character varying(500),
    fecha_inicio character varying(20),
    fecha_cierre character varying(20),
    id_estado integer,
    usuario_crea character varying(500),
    fecha_crea date,
    usuario_mod character varying(500),
    fecha_mod date,
    id_meta integer,
    id_rolresponsable integer
);


--
-- Name: mgc_evaluador_conv_id_evaluador_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_evaluador_conv_id_evaluador_seq
    START WITH 488
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_evaluador_conv; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_evaluador_conv (
    id_evaluador integer DEFAULT nextval('mgc_evaluador_conv_id_evaluador_seq') NOT NULL,
    id_aplicar integer,
    id_usuario integer,
    descripcion character(1000),
    objetivo character(1000),
    id_estado integer,
    fecha_maxima date
);


--
-- Name: mgc_evaluarcriterio_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_evaluarcriterio_id_seq
    START WITH 2531
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_evaluarcriterio; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_evaluarcriterio (
    id integer DEFAULT nextval('mgc_evaluarcriterio_id_seq') NOT NULL,
    id_criterio integer NOT NULL,
    id_aplicar integer NOT NULL,
    evaluacion_cualitativa character varying(10000),
    id_usuario integer NOT NULL,
    evaluacion_cuantitativa numeric(3,2)
);


--
-- Name: mgc_financiacion_id_financiacion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_financiacion_id_financiacion_seq
    START WITH 528
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_financiacion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_financiacion (
    id_financiacion integer DEFAULT nextval('mgc_financiacion_id_financiacion_seq') NOT NULL,
    id_convocatoria integer,
    id_rubro integer,
    id_fuente integer,
    id_estado integer,
    valor integer,
    descripcion character(5000),
    observaciones character(5000),
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date,
    periodo integer
);


--
-- Name: mgc_financiacion_proy_id_financiacion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_financiacion_proy_id_financiacion_seq
    START WITH 18247
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_financiacion_proy; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_financiacion_proy (
    id_financiacion integer DEFAULT nextval('mgc_financiacion_proy_id_financiacion_seq') NOT NULL,
    id_aplicar integer,
    id_rubro integer,
    id_fuente integer,
    id_estado integer,
    valor integer,
    descripcion character(200),
    observaciones character(200),
    periodo integer
);


--
-- Name: mgc_grupos_participantes_id_grupo_rel_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_grupos_participantes_id_grupo_rel_seq
    START WITH 1307
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_grupos_participantes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_grupos_participantes (
    id_grupo_rel integer DEFAULT nextval('mgc_grupos_participantes_id_grupo_rel_seq') NOT NULL,
    id_aplicar integer,
    id_grupo integer
);


--
-- Name: mgc_gruposaplicari_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_gruposaplicari_id_seq
    START WITH 168
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_gruposaplicari; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_gruposaplicari (
    id integer DEFAULT nextval('mgc_gruposaplicari_id_seq') NOT NULL,
    id_aplicari integer NOT NULL,
    id_grupo integer NOT NULL
);


--
-- Name: mgc_objetivometas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_objetivometas_id_seq
    START WITH 1252
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_objetivometas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_objetivometas (
    id integer DEFAULT nextval('mgc_objetivometas_id_seq') NOT NULL,
    meta character varying(5000),
    id_objetivo integer NOT NULL
);


--
-- Name: mgc_objetivosespecificos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_objetivosespecificos_id_seq
    START WITH 606
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_objetivosespecificos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_objetivosespecificos (
    id integer DEFAULT nextval('mgc_objetivosespecificos_id_seq') NOT NULL,
    objetivo character varying(5000),
    id_aplicar integer NOT NULL
);


--
-- Name: mgc_paresevaluadores_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_paresevaluadores_id_seq
    START WITH 252
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_paresevaluadores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_paresevaluadores (
    id integer DEFAULT nextval('mgc_paresevaluadores_id_seq') NOT NULL,
    id_aplicari integer NOT NULL,
    id_usuario integer
);


--
-- Name: mgc_propuesta_inv_id_archivo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_propuesta_inv_id_archivo_seq
    START WITH 1515
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_propuesta_inv; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_propuesta_inv (
    id_archivo integer DEFAULT nextval('mgc_propuesta_inv_id_archivo_seq') NOT NULL,
    id_aplicar integer,
    archivo character(120),
    id_tipo_archivo integer,
    new_archivo character varying(3),
    nombre character varying(2500),
    descripcion character varying(2500)
);


--
-- Name: mgc_proyectos_investigacion_id_proyecto_inv_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_proyectos_investigacion_id_proyecto_inv_seq
    START WITH 388
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_proyectos_investigacion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_proyectos_investigacion (
    id_proyecto_inv integer DEFAULT nextval('mgc_proyectos_investigacion_id_proyecto_inv_seq') NOT NULL,
    id_convocatoria integer,
    nombre_proyecto character(300),
    cantidad_plazas integer,
    fecha_lim_soporte date,
    plazas_disponibles integer
);


--
-- Name: mgc_requisitos_id_requisito_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_requisitos_id_requisito_seq
    START WITH 556
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_requisitos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_requisitos (
    id_requisito integer DEFAULT nextval('mgc_requisitos_id_requisito_seq') NOT NULL,
    id_convocatoria integer,
    id_tipo_requisito integer,
    id_ponderacion1 integer,
    id_ponderacion2 integer,
    id_estado integer,
    descripcion character varying(5000),
    observaciones character varying(5000),
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date,
    id_tipo_ponderacion integer
);


--
-- Name: mgc_requisitos_doc_id_requisito_doc_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_requisitos_doc_id_requisito_doc_seq
    START WITH 162
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_requisitos_doc; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_requisitos_doc (
    id_requisito_doc integer DEFAULT nextval('mgc_requisitos_doc_id_requisito_doc_seq') NOT NULL,
    id_convocatoria integer,
    id_tipo_doc integer,
    id_documento integer,
    id_ponderacion1 integer,
    id_ponderacion2 integer,
    id_estado integer,
    descripcion character varying(5000),
    observaciones character varying(5000),
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date,
    id_tipo_ponderacion integer,
    fecha_limite date,
    hora_limite character(10),
    responsable character(1)
);


--
-- Name: mgc_requisitosap_id_requisitoap_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_requisitosap_id_requisitoap_seq
    START WITH 11427
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_requisitosap; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_requisitosap (
    id_requisitoap integer DEFAULT nextval('mgc_requisitosap_id_requisitoap_seq') NOT NULL,
    id_aplicar integer,
    id_tipo_requisito integer,
    id_ponderacion2 character(1),
    id_estado integer,
    descripcion character(7000),
    observaciones character(7000),
    id_convocatoria integer,
    id_padre integer
);


--
-- Name: mgc_requisitosap_doc_id_requisitoap_doc_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_requisitosap_doc_id_requisitoap_doc_seq
    START WITH 12891
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_requisitosap_doc; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_requisitosap_doc (
    id_requisitoap_doc integer DEFAULT nextval('mgc_requisitosap_doc_id_requisitoap_doc_seq') NOT NULL,
    id_aplicar integer NOT NULL,
    id_requisito_doc integer NOT NULL,
    id_ponderacion1 integer,
    id_ponderacion2 character(2),
    descripcion character(1200),
    observaciones character(1200),
    archivo character(300),
    fecha_limite date,
    fecha_archivo date,
    new_archivo character varying(3),
    checked character varying(5)
);


--
-- Name: mgc_responsablesap_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_responsablesap_id_seq
    START WITH 319
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_responsablesap; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_responsablesap (
    id integer DEFAULT nextval('mgc_responsablesap_id_seq') NOT NULL,
    id_aplicari integer NOT NULL,
    id_rol integer,
    tipo character varying(10),
    id_padre integer
);


--
-- Name: mgc_roles_id_rol_conv_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_roles_id_rol_conv_seq
    START WITH 104
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_roles (
    id_rol_conv integer DEFAULT nextval('mgc_roles_id_rol_conv_seq') NOT NULL,
    id_convocatoria integer,
    id_rol integer,
    observaciones character(200)
);


--
-- Name: mgc_sesionesestudiantes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_sesionesestudiantes_id_seq
    START WITH 193
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_sesionesestudiantes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_sesionesestudiantes (
    id integer DEFAULT nextval('mgc_sesionesestudiantes_id_seq') NOT NULL,
    id_aplicari integer NOT NULL,
    fecha date,
    id_rol integer,
    sesion integer,
    fecha_fin date
);


--
-- Name: mgc_sesionesformacion_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_sesionesformacion_id_seq
    START WITH 213
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_sesionesformacion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_sesionesformacion (
    id integer DEFAULT nextval('mgc_sesionesformacion_id_seq') NOT NULL,
    id_aplicari integer NOT NULL,
    id_tipo integer,
    fecha date,
    fecha_fin date
);


--
-- Name: mgc_tabla_equipos_id_integrantes_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_tabla_equipos_id_integrantes_seq
    START WITH 2240
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_tabla_equipos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_tabla_equipos (
    id_integrantes integer DEFAULT nextval('mgc_tabla_equipos_id_integrantes_seq') NOT NULL,
    id_aplicar integer,
    id_integrante integer,
    id_rol integer,
    id_tipo_dedicacion integer,
    horas_sol integer,
    horas_apro integer
);


--
-- Name: mgc_url_id_url_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_url_id_url_seq
    START WITH 89
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_url; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_url (
    id_url integer DEFAULT nextval('mgc_url_id_url_seq') NOT NULL,
    id_convocatoria integer,
    url character(500),
    nom_url character varying(500),
    descripcion character(2000)
);


--
-- Name: mgc_urls_id_url_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgc_urls_id_url_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgc_urls; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgc_urls (
    id_url integer DEFAULT nextval('mgc_urls_id_url_seq') NOT NULL,
    id_convocatoria integer,
    direccion character(500),
    id_estado integer,
    descripcion character(200),
    observaciones character(200)
);


--
-- Name: mgi_autorgrupo_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_autorgrupo_id_seq
    START WITH 157
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_autorgrupo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_autorgrupo (
    id integer DEFAULT nextval('mgi_autorgrupo_id_seq') NOT NULL,
    id_grupo integer NOT NULL,
    id_usuario integer NOT NULL,
    seccion character varying(20) NOT NULL,
    id_objeto integer
);


--
-- Name: mgi_capitulosgrupo_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_capitulosgrupo_id_seq
    START WITH 172
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_capitulosgrupo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_capitulosgrupo (
    id integer DEFAULT nextval('mgi_capitulosgrupo_id_seq') NOT NULL,
    titulo character varying(500) NOT NULL,
    paginas character varying(20),
    ano character varying(20) NOT NULL,
    mes character varying(20) NOT NULL,
    pais character varying(200),
    ciudad character varying(200),
    serie character varying(50),
    editorial character varying(500),
    edicion character varying(500),
    isbn character varying(20),
    lugar_publicacion character varying(500),
    medio_divulgacion character varying(500),
    titulo_capitulo character varying(500) NOT NULL,
    numero_capitulo character varying(50) NOT NULL,
    paginas_capitulo character varying(50) NOT NULL,
    pagina_inicio character varying(20) NOT NULL,
    pagina_fin character varying(20) NOT NULL,
    id_grupo integer NOT NULL,
    id_autor integer,
    archivo character varying(500)
);


--
-- Name: mgi_documentosvinculados_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_documentosvinculados_id_seq
    START WITH 3713
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_documentosvinculados; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_documentosvinculados (
    id integer DEFAULT nextval('mgi_documentosvinculados_id_seq') NOT NULL,
    id_grupoinv integer NOT NULL,
    id_usuario integer NOT NULL,
    id_documento integer NOT NULL,
    tipo_documento character varying(30) NOT NULL,
    id_usuario_solicitud integer NOT NULL,
    fecha_solcitud date NOT NULL,
    estado_solicitud character varying(2) NOT NULL,
    modulo character varying(2)
);


--
-- Name: mgi_eventosgru; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_eventosgru (
    id_evento integer NOT NULL,
    id_tipoevento integer,
    id_tipoparticipacion integer,
    nombre_evento character varying(500),
    nombre_trabajo character varying(500),
    id_institucion integer,
    ciudad_trabajo character varying(500),
    fecha_inicio character varying(10),
    fecha_fin character varying(10),
    otra_informacion character varying(4000),
    id_tipomedio integer,
    nombre_trabajo_medio character varying(500),
    id_autor integer,
    id_institucion_medio integer,
    ciudad_medio character varying(500),
    medio_divulgacion character varying(500),
    fecha_medio character varying(10),
    descripcion_medio character varying(4000),
    otra_informacion_medio character varying(4000),
    archivo character varying(500),
    id_red integer
);


--
-- Name: mgi_eventosgru_id_evento_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_eventosgru_id_evento_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_eventosgru_id_evento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE mgi_eventosgru_id_evento_seq OWNED BY mgi_eventosgru.id_evento;


--
-- Name: mgi_gi_archivos_id_archivo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_archivos_id_archivo_seq
    START WITH 5064
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_archivos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_archivos (
    id_archivo integer DEFAULT nextval('mgi_gi_archivos_id_archivo_seq') NOT NULL,
    id_grupo_inv integer,
    archivo character(120),
    id_tipo_archivo integer,
    new_archivo character varying(3)
);


--
-- Name: mgi_gi_articulos_id_gi_articulo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_articulos_id_gi_articulo_seq
    START WITH 501
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_articulos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_articulos (
    id_gi_articulo integer DEFAULT nextval('mgi_gi_articulos_id_gi_articulo_seq') NOT NULL,
    nombre_revista character varying(200),
    nombre_articulo character varying(200),
    id_pais character varying(50),
    fecha date,
    issn character varying(200),
    paginas character varying(10),
    num_paginas integer,
    volumen character varying(10),
    serie character varying(20),
    id_autor integer,
    coautor character varying(200),
    id_grupo_inv integer,
    pagina_inicio integer,
    pagina_fin integer,
    fasciculo integer,
    id_departamento integer,
    id_ciudad character varying(50),
    ano integer,
    mes integer,
    pais character varying(50),
    ciudad character varying(50),
    categoria character varying(10),
    archivo character varying(500)
);


--
-- Name: mgi_gi_asociaciones_id_asociaciones_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_asociaciones_id_asociaciones_seq
    START WITH 33
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_asociaciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_asociaciones (
    id_asociaciones integer DEFAULT nextval('mgi_gi_asociaciones_id_asociaciones_seq') NOT NULL,
    id_grupo_inv integer,
    nombre_asociacion character varying(200),
    archivo character varying(500)
);


--
-- Name: mgi_gi_autores_id_autor_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_autores_id_autor_seq
    START WITH 1973
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_autores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_autores (
    id_autor integer DEFAULT nextval('mgi_gi_autores_id_autor_seq') NOT NULL,
    id_padre integer,
    id_usuario integer,
    nombre_padre character(100),
    id_grupo_inv integer,
    id_rol integer
);


--
-- Name: mgi_gi_bibliograficos_id_bibliografico_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_bibliograficos_id_bibliografico_seq
    START WITH 91
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_bibliograficos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_bibliograficos (
    id_bibliografico integer DEFAULT nextval('mgi_gi_bibliograficos_id_bibliografico_seq') NOT NULL,
    id_grupo_inv integer,
    nombre_documento character varying(120),
    numero_paginas integer,
    instituciones character varying(300),
    ano character varying(10),
    mes character varying(10),
    num_indexacion integer,
    url character varying(120),
    medio_divulgacion character varying(100),
    descripcion character varying(500),
    autores character varying(100),
    id_autor integer,
    pais character varying(50),
    ciudad character varying(50),
    archivo character varying(500)
);


--
-- Name: mgi_gi_grupos_rel_id_grupo_rel_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_grupos_rel_id_grupo_rel_seq
    START WITH 83
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_grupos_rel; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_grupos_rel (
    id_grupo_rel integer DEFAULT nextval('mgi_gi_grupos_rel_id_grupo_rel_seq') NOT NULL,
    id_grupo_inv integer,
    id_grupo integer,
    archivo character varying(500)
);


--
-- Name: mgi_gi_instituciones_id_institucion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_instituciones_id_institucion_seq
    START WITH 112
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_instituciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_instituciones (
    id_institucion integer DEFAULT nextval('mgi_gi_instituciones_id_institucion_seq') NOT NULL,
    id_grupo_inv integer,
    descripcion integer,
    archivo character varying(500)
);


--
-- Name: mgi_gi_integrantes_id_integrantes_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_integrantes_id_integrantes_seq
    START WITH 779
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_integrantes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_integrantes (
    id_integrantes integer DEFAULT nextval('mgi_gi_integrantes_id_integrantes_seq') NOT NULL,
    id_grupo_inv integer,
    id_integrante integer
);


--
-- Name: mgi_gi_libros_id_gi_libro_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_libros_id_gi_libro_seq
    START WITH 246
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_libros; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_libros (
    id_gi_libro integer DEFAULT nextval('mgi_gi_libros_id_gi_libro_seq') NOT NULL,
    titulo_libro character varying(200),
    paginas character varying(200),
    num_paginas integer,
    fecha date,
    serie character varying(200),
    editorial character varying(200),
    edicion character varying(100),
    isbn character varying(100),
    lugar_publicacion character varying(100),
    medio_divulgacion character varying(100),
    autores character varying(100),
    instituciones character varying(200),
    id_grupo_inv integer,
    capitulo_libro character varying(50),
    id_autor integer,
    mes character varying(10),
    ano character varying(10),
    pais character varying(50),
    ciudad character varying(50),
    archivo character varying(500),
    tipo_libro character varying(20)
);


--
-- Name: mgi_gi_lineas_id_linea_inv_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_lineas_id_linea_inv_seq
    START WITH 501
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_lineas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_lineas (
    id_linea_inv integer DEFAULT nextval('mgi_gi_lineas_id_linea_inv_seq') NOT NULL,
    id_grupo_inv integer,
    nombre_linea character varying(120),
    objetivo character varying(5001),
    efectos character varying(5002),
    logros character varying(5003),
    archivo character varying(500)
);


--
-- Name: mgi_gi_otra_prod_id_otra_prod_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_otra_prod_id_otra_prod_seq
    START WITH 68
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_otra_prod; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_otra_prod (
    id_otra_prod integer DEFAULT nextval('mgi_gi_otra_prod_id_otra_prod_seq') NOT NULL,
    id_grupo_inv integer,
    nombre_producto character varying(100),
    descripcion_producto character varying(5000),
    tipo_producto character varying(500),
    fecha date,
    id_pais character varying(50),
    id_ciudad character varying(50),
    instituciones character varying(5000),
    registro character varying(5000),
    autores character varying(200),
    otra_info character varying(5000),
    id_departamento integer,
    mes character varying(10),
    ano character varying(10),
    id_autor integer,
    archivo character varying(500)
);


--
-- Name: mgi_gi_proyectos_ext_id_proyecto_ext_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_proyectos_ext_id_proyecto_ext_seq
    START WITH 55
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_proyectos_ext; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_proyectos_ext (
    id_proyecto_ext integer DEFAULT nextval('mgi_gi_proyectos_ext_id_proyecto_ext_seq') NOT NULL,
    id_grupo_inv integer,
    codigo_proyecto character varying(100),
    tipo_proyecto character varying(100),
    fecha_inicio date,
    fecha_fin date,
    resumen_ejecutivo character varying(5000),
    objetivo_general character varying(5000),
    equipo_trabajo character varying(500),
    productos_derivados character varying(5000),
    nombre_proyecto character varying(300),
    id_rol integer,
    archivo character varying(500)
);


--
-- Name: mgi_gi_proyectosint_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_proyectosint_id_seq
    START WITH 107
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_proyectosint; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_proyectosint (
    id integer DEFAULT nextval('mgi_gi_proyectosint_id_seq') NOT NULL,
    id_grupo_inv integer,
    id_proyecto integer,
    archivo character varying(500)
);


--
-- Name: mgi_gi_reconocimientos_id_reconocimiento_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_reconocimientos_id_reconocimiento_seq
    START WITH 27
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_reconocimientos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_reconocimientos (
    id_reconocimiento integer DEFAULT nextval('mgi_gi_reconocimientos_id_reconocimiento_seq') NOT NULL,
    id_grupo_inv integer,
    descripcion character varying(500),
    valor integer,
    num_acto character varying(120),
    semestre character varying(15),
    nombre character varying(500),
    archivo character varying(500)
);


--
-- Name: mgi_gi_redes_id_redes_inv_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_redes_id_redes_inv_seq
    START WITH 10
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_redes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_redes (
    id_redes_inv integer DEFAULT nextval('mgi_gi_redes_id_redes_inv_seq') NOT NULL,
    id_grupo_inv integer,
    nombre_red character(200)
);


--
-- Name: mgi_gi_redes_tabla_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_redes_tabla_id_seq
    START WITH 7
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_redes_tabla; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_redes_tabla (
    id integer DEFAULT nextval('mgi_gi_redes_tabla_id_seq') NOT NULL,
    id_grupo_inv integer,
    id_red integer,
    archivo character varying(500)
);


--
-- Name: mgi_gi_semilleros_id_semillero_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_semilleros_id_semillero_seq
    START WITH 15
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_semilleros; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_semilleros (
    id_semillero integer DEFAULT nextval('mgi_gi_semilleros_id_semillero_seq') NOT NULL,
    id_grupo_inv integer,
    descripcion character(300)
);


--
-- Name: mgi_gi_semilleros_tabla_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_gi_semilleros_tabla_id_seq
    START WITH 67
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_gi_semilleros_tabla; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_gi_semilleros_tabla (
    id integer DEFAULT nextval('mgi_gi_semilleros_tabla_id_seq') NOT NULL,
    id_grupo_inv integer,
    id_semillero integer,
    archivo character varying(500)
);


--
-- Name: mgi_grupo_inv_id_grupo_inv_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_grupo_inv_id_grupo_inv_seq
    START WITH 236
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_grupo_inv; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_grupo_inv (
    id_grupo_inv integer DEFAULT nextval('mgi_grupo_inv_id_grupo_inv_seq') NOT NULL,
    nombre_grupo character(120),
    fecha_creacion timestamp without time zone,
    id_lider integer,
    id_clasificacion integer,
    estado character(1),
    id_unidad_academica integer,
    id_dependencia_academica integer,
    id_programa_academico integer,
    id_pais character varying(50),
    id_departamento integer,
    id_ciudad character varying(50),
    url character(120),
    id_campo_investigacion integer,
    plan_accion character varying(7500),
    mision character varying(7500),
    vision character varying(7500),
    redes_grupo character(500),
    asociaciones character(500),
    email character(120),
    telefono character(50),
    dir_postal character(60),
    plan_estrategico character varying(7500),
    sectores_aplicacion character varying(7500),
    estado_arte character varying(7500),
    retos character varying(7500),
    archivo character(120),
    cod_grupo character varying(100),
    fecha_creacion_grupo date,
    descripcion character varying(7500),
    descriptores character varying(7500)
);


--
-- Name: mgi_identificadoresgru; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_identificadoresgru (
    id_identificador integer NOT NULL,
    id_tipoidentificador integer,
    id_tipocategoria integer,
    id_field character varying(50),
    fecha_registro character varying(10),
    nombre character varying(500),
    web character varying(500),
    ciudad character varying(500),
    descripcion character varying(4000),
    otra_informacion character varying(4000),
    archivo character varying(500),
    id_red integer
);


--
-- Name: mgi_identificadoresgru_id_identificador_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_identificadoresgru_id_identificador_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_identificadoresgru_id_identificador_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE mgi_identificadoresgru_id_identificador_seq OWNED BY mgi_identificadoresgru.id_identificador;


--
-- Name: mgi_red_inv_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_red_inv_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_red_inv; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_red_inv (
    id integer DEFAULT nextval('mgi_red_inv_id_seq') NOT NULL,
    coordinador_uno integer,
    coordinador_dos integer,
    nombre character(500) NOT NULL,
    codigo character(500) NOT NULL,
    estado character varying(2) NOT NULL,
    fecha_creacion timestamp without time zone NOT NULL,
    vision character varying(2500),
    mision character varying(2500),
    objetivos character varying(2500),
    antecedentes character varying(2500),
    justificacion character varying(2500),
    descripcion character varying(2500),
    lineas_investigacion character varying(2500),
    instituciones_aliadas character varying(2500),
    socios character varying(2500),
    aliados character varying(2500),
    fecha_estado date
);


--
-- Name: mgi_trabajogradogru; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgi_trabajogradogru (
    id_trabajogrado integer NOT NULL,
    nombre_trabajo character varying(500),
    id_tipotrabajo integer,
    id_estadotipotrabajo integer,
    id_tipoparticipacion integer,
    id_autor integer,
    id_institucion integer,
    ciudad_trabajo character varying(500),
    id_unidad integer,
    id_dependencia integer,
    id_programa integer,
    fecha_inicio character varying(10),
    fecha_fin character varying(10),
    descripcion character varying(4000),
    otra_informacion character varying(4000),
    id_formacioninvestigador integer,
    codigo_proyecto character varying(500),
    nombre_proyecto character varying(500),
    id_institucion_proyecto integer,
    ciudad_proyecto character varying(500),
    personas_formadas character varying(30),
    fecha_inicio_proyecto character varying(10),
    fecha_fin_proyecto character varying(10),
    descripcion_proyecto character varying(4000),
    descripcion_formacion character varying(4000),
    otra_informacion_proyecto character varying(4000),
    id_semillero integer,
    id_institucion_semillero integer,
    id_rolparticipacion integer,
    fecha_inicio_semillero character varying(10),
    fecha_fin_semillero character varying(10),
    tematica character varying(4000),
    descripcion_semillero character varying(4000),
    archivo character varying(500),
    id_red integer,
    id_investigador integer
);


--
-- Name: mgi_trabajogradogru_id_trabajogrado_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgi_trabajogradogru_id_trabajogrado_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgi_trabajogradogru_id_trabajogrado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE mgi_trabajogradogru_id_trabajogrado_seq OWNED BY mgi_trabajogradogru.id_trabajogrado;


--
-- Name: mgp_actas_id_archivo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgp_actas_id_archivo_seq
    START WITH 32
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgp_actas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgp_actas (
    id_archivo integer DEFAULT nextval('mgp_actas_id_archivo_seq') NOT NULL,
    id_proyecto integer,
    archivo character(120),
    id_tipo_archivo integer,
    nombre character(150),
    new_archivo character varying(3)
);


--
-- Name: mgp_actas_monitores_id_archivo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgp_actas_monitores_id_archivo_seq
    START WITH 5
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgp_actas_monitores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgp_actas_monitores (
    id_archivo integer DEFAULT nextval('mgp_actas_monitores_id_archivo_seq') NOT NULL,
    id_monitor integer,
    archivo character(120),
    id_tipo_archivo integer,
    nombre character(150)
);


--
-- Name: mgp_entidadesejecutoras; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgp_entidadesejecutoras (
    id_entidadejecutora integer NOT NULL,
    id_entidad integer,
    id_rol integer,
    id_proyecto integer
);


--
-- Name: mgp_entidadesejecutoras_id_entidadejecutora_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgp_entidadesejecutoras_id_entidadejecutora_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgp_entidadesejecutoras_id_entidadejecutora_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE mgp_entidadesejecutoras_id_entidadejecutora_seq OWNED BY mgp_entidadesejecutoras.id_entidadejecutora;


--
-- Name: mgp_financiacion_id_financiacion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgp_financiacion_id_financiacion_seq
    START WITH 4757
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgp_financiacion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgp_financiacion (
    id_financiacion integer DEFAULT nextval('mgp_financiacion_id_financiacion_seq') NOT NULL,
    id_proyecto integer,
    id_rubro integer,
    id_fuente integer,
    id_estado integer,
    valor integer,
    descripcion character(5000),
    observaciones character(5000),
    usuario_crea character(54),
    fecha_crea date,
    usuario_mod character(54),
    fecha_mod date,
    periodo integer
);


--
-- Name: mgp_grupos_id_grupo_proy_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgp_grupos_id_grupo_proy_seq
    START WITH 253
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgp_grupos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgp_grupos (
    id_grupo_proy integer DEFAULT nextval('mgp_grupos_id_grupo_proy_seq') NOT NULL,
    id_proyecto integer,
    id_grupo integer
);


--
-- Name: mgp_informes_id_informe_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgp_informes_id_informe_seq
    START WITH 31
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgp_informes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgp_informes (
    id_informe integer DEFAULT nextval('mgp_informes_id_informe_seq') NOT NULL,
    id_proyecto integer NOT NULL,
    informe character(80) NOT NULL,
    archivo character(300),
    fecha_limite date,
    id_estado integer,
    observaciones character(5000)
);


--
-- Name: mgp_informes_monitores_id_informe_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgp_informes_monitores_id_informe_seq
    START WITH 15
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgp_informes_monitores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgp_informes_monitores (
    id_informe integer DEFAULT nextval('mgp_informes_monitores_id_informe_seq') NOT NULL,
    archivo character(300),
    observaciones character(4000),
    estado character varying(30),
    id_aplicar integer,
    new_archivo character varying(3),
    id_avalcumpliconvo integer
);


--
-- Name: mgp_monitores_id_monitor_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgp_monitores_id_monitor_seq
    START WITH 20
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgp_monitores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgp_monitores (
    id_monitor integer DEFAULT nextval('mgp_monitores_id_monitor_seq') NOT NULL,
    num_codigo integer,
    id_facultad character(200),
    id_programa_curricular character(200),
    id_usuario integer,
    id_aplicar integer,
    id_proyecto integer,
    id_estado integer,
    observaciones character(3000)
);


--
-- Name: mgp_proyectos_inv; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgp_proyectos_inv
    START WITH 457
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgp_proyectos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgp_proyectos (
    id_proyecto integer DEFAULT nextval('mgp_proyectos_inv') NOT NULL,
    codigo_proy character(50),
    nombre_proy character(1500),
    id_investigador integer,
    duracion integer,
    id_campo integer,
    id_linea_inv integer,
    id_unidad_academica integer,
    resumen_ejecutivo character varying(14000),
    objetivo_general character varying(8000),
    id_estado integer,
    usuario_crea character(500),
    fecha_crea date,
    usuario_mod character(500),
    fecha_mod date,
    periodo character(1),
    id_dependencia_academica integer,
    id_programa_academico integer,
    observaciones character(5000),
    id_convocatoria integer,
    tipo_conv character(1),
    id_programa_curricular character(300),
    id_facultad character(300),
    id_aplicar integer,
    fecha_limite date,
    envio character(1),
    primera_vigencia integer,
    stipo character varying(15),
    fecha_inicio date,
    fecha_terminacion date,
    modificaciones_documento character varying(2500),
    documento_formalizacion character varying(2500),
    prorroga character varying(15),
    convocatoria character varying(5000)
);


--
-- Name: mgp_tabla_equipos_id_integrantes_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mgp_tabla_equipos_id_integrantes_seq
    START WITH 117004
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mgp_tabla_equipos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mgp_tabla_equipos (
    id_integrantes integer DEFAULT nextval('mgp_tabla_equipos_id_integrantes_seq') NOT NULL,
    id_proyecto integer,
    id_integrante integer,
    id_rol integer,
    id_tipo_dedicacion integer,
    horas_sol integer,
    horas_apro integer,
    periodo integer,
    ano integer
);


--
-- Name: mri_archivosred_id_archivo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_archivosred_id_archivo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_archivosred; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_archivosred (
    id_archivo integer DEFAULT nextval('mri_archivosred_id_archivo_seq') NOT NULL,
    id_red integer,
    archivo character(200),
    id_tipo_archivo integer,
    new_archivo character varying(3)
);


--
-- Name: mri_articulored_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_articulored_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_articulored; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_articulored (
    id integer DEFAULT nextval('mri_articulored_id_seq') NOT NULL,
    nombre_revista character varying(500) NOT NULL,
    nombre_articulo character varying(20) NOT NULL,
    ano character varying(20) NOT NULL,
    mes character varying(20),
    pais character varying(200),
    ciudad character varying(50),
    issn character varying(500),
    paginas character varying(500) NOT NULL,
    pagina_inicio character varying(10) NOT NULL,
    pagina_fin character varying(10) NOT NULL,
    fasciculo character varying(500),
    volumen character varying(500),
    serie character varying(500),
    id_red integer NOT NULL,
    categoria character varying(10),
    id_autor integer,
    archivo character varying(500)
);


--
-- Name: mri_autorred_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_autorred_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_autorred; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_autorred (
    id integer DEFAULT nextval('mri_autorred_id_seq') NOT NULL,
    id_red integer NOT NULL,
    id_usuario integer NOT NULL,
    seccion character varying(20) NOT NULL,
    id_objeto integer,
    id_rol integer
);


--
-- Name: mri_capitulored_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_capitulored_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_capitulored; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_capitulored (
    id integer DEFAULT nextval('mri_capitulored_id_seq') NOT NULL,
    titulo character varying(500) NOT NULL,
    paginas character varying(20),
    ano character varying(20) NOT NULL,
    mes character varying(20) NOT NULL,
    pais character varying(200),
    ciudad character varying(200),
    serie character varying(50),
    editorial character varying(500),
    edicion character varying(500),
    isbn character varying(20),
    lugar_publicacion character varying(500),
    medio_divulgacion character varying(500),
    titulo_capitulo character varying(500) NOT NULL,
    numero_capitulo character varying(50) NOT NULL,
    paginas_capitulo character varying(50) NOT NULL,
    pagina_inicio character varying(20) NOT NULL,
    pagina_fin character varying(20) NOT NULL,
    id_red integer NOT NULL,
    id_autor integer,
    archivo character varying(500)
);


--
-- Name: mri_contactored_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_contactored_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_contactored; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_contactored (
    id integer DEFAULT nextval('mri_contactored_id_seq') NOT NULL,
    id_red integer NOT NULL,
    telefono_1 character varying(200),
    telefono_2 character varying(200),
    redsocial_1 character varying(200),
    redsocial_2 character varying(200),
    redsocial_3 character varying(200),
    otro_contacto character varying(200),
    correo_electronico character varying(200),
    pagina_web character varying(200),
    archivo character varying(500)
);


--
-- Name: mri_documentosbibliograficos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_documentosbibliograficos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_documentosbibliograficos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_documentosbibliograficos (
    id integer DEFAULT nextval('mri_documentosbibliograficos_id_seq') NOT NULL,
    id_red integer NOT NULL,
    id_autor integer NOT NULL,
    nombre character varying(500) NOT NULL,
    numero_paginas character varying(20),
    ano character varying(20) NOT NULL,
    mes character varying(20),
    pais character varying(200),
    ciudad character varying(200),
    numero_indexacion character varying(20),
    instituciones character varying(2500),
    url character varying(50),
    descripcion character varying(7000),
    medio_divulgacion character varying(2500),
    archivo character varying(500)
);


--
-- Name: mri_equipodirectivo_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_equipodirectivo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_equipodirectivo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_equipodirectivo (
    id integer DEFAULT nextval('mri_equipodirectivo_id_seq') NOT NULL,
    id_red integer NOT NULL,
    cargo character varying(500),
    nombre character varying(500),
    institucion character varying(200),
    pais character varying(200),
    archivo character varying(500)
);


--
-- Name: mri_eventosred; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_eventosred (
    id_evento integer NOT NULL,
    id_tipoevento integer,
    id_tipoparticipacion integer,
    nombre_evento character varying(500),
    nombre_trabajo character varying(500),
    id_institucion integer,
    ciudad_trabajo character varying(500),
    fecha_inicio character varying(10),
    fecha_fin character varying(10),
    otra_informacion character varying(4000),
    id_tipomedio integer,
    nombre_trabajo_medio character varying(500),
    id_autor integer,
    id_institucion_medio integer,
    ciudad_medio character varying(500),
    medio_divulgacion character varying(500),
    fecha_medio character varying(10),
    descripcion_medio character varying(4000),
    otra_informacion_medio character varying(4000),
    archivo character varying(500),
    id_red integer
);


--
-- Name: mri_eventosred_id_evento_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_eventosred_id_evento_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_eventosred_id_evento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE mri_eventosred_id_evento_seq OWNED BY mri_eventosred.id_evento;


--
-- Name: mri_gruposred_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_gruposred_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_gruposred; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_gruposred (
    id integer DEFAULT nextval('mri_gruposred_id_seq') NOT NULL,
    id_red integer NOT NULL,
    id_grupo integer NOT NULL,
    archivo character varying(500)
);


--
-- Name: mri_identificadoresred; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_identificadoresred (
    id_identificador integer NOT NULL,
    id_tipoidentificador integer,
    id_tipocategoria integer,
    id_field character varying(50),
    fecha_registro character varying(10),
    nombre character varying(500),
    web character varying(500),
    ciudad character varying(500),
    descripcion character varying(4000),
    otra_informacion character varying(4000),
    archivo character varying(500),
    id_red integer
);


--
-- Name: mri_identificadoresred_id_identificador_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_identificadoresred_id_identificador_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_identificadoresred_id_identificador_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE mri_identificadoresred_id_identificador_seq OWNED BY mri_identificadoresred.id_identificador;


--
-- Name: mri_integrantesred_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_integrantesred_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_integrantesred; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_integrantesred (
    id integer DEFAULT nextval('mri_integrantesred_id_seq') NOT NULL,
    id_red integer NOT NULL,
    id_integrantered integer NOT NULL,
    tipo_vinculacion character varying(10),
    fecha_vinculacion date,
    fin_vinculacion date,
    archivo character varying(500)
);


--
-- Name: mri_librored_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_librored_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_librored; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_librored (
    id integer DEFAULT nextval('mri_librored_id_seq') NOT NULL,
    titulo character varying(500) NOT NULL,
    paginas character varying(20) NOT NULL,
    ano character varying(20) NOT NULL,
    mes character varying(20) NOT NULL,
    pais character varying(200),
    ciudad character varying(200),
    serie character varying(50),
    editorial character varying(500),
    edicion character varying(500),
    isbn character varying(20),
    lugar_publicacion character varying(500),
    medio_divulgacion character varying(500),
    id_red integer NOT NULL,
    id_autor integer,
    archivo character varying(500),
    tipo_libro character varying(20)
);


--
-- Name: mri_participacioneventos_id_archivo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_participacioneventos_id_archivo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_participacioneventos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_participacioneventos (
    id_archivo integer DEFAULT nextval('mri_participacioneventos_id_archivo_seq') NOT NULL,
    id_red integer,
    archivo character varying(200),
    nombre character varying(200),
    lugar character varying(200),
    tipo_participacion character varying(10),
    fecha date,
    new_archivo character varying(3)
);


--
-- Name: mri_produccionesinvred_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_produccionesinvred_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_produccionesinvred; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_produccionesinvred (
    id integer DEFAULT nextval('mri_produccionesinvred_id_seq') NOT NULL,
    nombre character varying(2500) NOT NULL,
    ano character varying(20) NOT NULL,
    mes character varying(20),
    pais character varying(200),
    ciudad character varying(200),
    descripcion character varying(2500),
    tipo character varying(2500),
    instituciones character varying(2500),
    id_red integer NOT NULL,
    registro character varying(7000),
    otra_informacion character varying(7000),
    id_autor integer,
    archivo character varying(500)
);


--
-- Name: mri_proyectored_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_proyectored_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_proyectored; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_proyectored (
    id integer DEFAULT nextval('mri_proyectored_id_seq') NOT NULL,
    id_red integer NOT NULL,
    nombre character varying(2500) NOT NULL,
    codigo character varying(2500),
    tipo character varying(2500),
    fecha_inicio date,
    fecha_fin date,
    resumen character varying(2500),
    objetivo character varying(2500),
    productos character varying(2500),
    archivo character varying(500)
);


--
-- Name: mri_proyectosint_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_proyectosint_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_proyectosint; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_proyectosint (
    id integer DEFAULT nextval('mri_proyectosint_id_seq') NOT NULL,
    id_grupo_inv integer,
    id_proyecto integer,
    archivo character varying(500)
);


--
-- Name: mri_serviciosred_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_serviciosred_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_serviciosred; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_serviciosred (
    id integer DEFAULT nextval('mri_serviciosred_id_seq') NOT NULL,
    servicios character varying(500),
    noticias character varying(500),
    eventos character varying(500),
    id_red integer NOT NULL,
    archivo character varying(500)
);


--
-- Name: mri_trabajogradored; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE mri_trabajogradored (
    id_trabajogrado integer NOT NULL,
    nombre_trabajo character varying(500),
    id_tipotrabajo integer,
    id_estadotipotrabajo integer,
    id_tipoparticipacion integer,
    id_autor integer,
    id_institucion integer,
    ciudad_trabajo character varying(500),
    id_unidad integer,
    id_dependencia integer,
    id_programa integer,
    fecha_inicio character varying(10),
    fecha_fin character varying(10),
    descripcion character varying(4000),
    otra_informacion character varying(4000),
    id_formacioninvestigador integer,
    codigo_proyecto character varying(500),
    nombre_proyecto character varying(500),
    id_institucion_proyecto integer,
    ciudad_proyecto character varying(500),
    personas_formadas character varying(30),
    fecha_inicio_proyecto character varying(10),
    fecha_fin_proyecto character varying(10),
    descripcion_proyecto character varying(4000),
    descripcion_formacion character varying(4000),
    otra_informacion_proyecto character varying(4000),
    id_semillero integer,
    id_institucion_semillero integer,
    id_rolparticipacion integer,
    fecha_inicio_semillero character varying(10),
    fecha_fin_semillero character varying(10),
    tematica character varying(4000),
    descripcion_semillero character varying(4000),
    archivo character varying(500),
    id_red integer,
    id_investigador integer
);


--
-- Name: mri_trabajogradored_id_trabajogrado_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mri_trabajogradored_id_trabajogrado_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mri_trabajogradored_id_trabajogrado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE mri_trabajogradored_id_trabajogrado_seq OWNED BY mri_trabajogradored.id_trabajogrado;


--
-- Name: msi_archivossemillero_id_archivo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_archivossemillero_id_archivo_seq
    START WITH 10
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_archivossemillero; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_archivossemillero (
    id_archivo integer DEFAULT nextval('msi_archivossemillero_id_archivo_seq') NOT NULL,
    id_semillero integer,
    archivo character(200),
    id_tipo_archivo integer,
    new_archivo character varying(3)
);


--
-- Name: msi_area_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_area_id_seq
    START WITH 144
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_area; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_area (
    id integer DEFAULT nextval('msi_area_id_seq') NOT NULL,
    id_semillero integer NOT NULL,
    tematica character varying(7000),
    archivo character varying(500)
);


--
-- Name: msi_articulosemillero_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_articulosemillero_id_seq
    START WITH 66
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_articulosemillero; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_articulosemillero (
    id integer DEFAULT nextval('msi_articulosemillero_id_seq') NOT NULL,
    nombre_revista character varying(500) NOT NULL,
    nombre_articulo character varying(20) NOT NULL,
    ano character varying(20) NOT NULL,
    mes character varying(20),
    pais character varying(200),
    ciudad character varying(200),
    issn character varying(50),
    paginas character varying(500) NOT NULL,
    pagina_inicio character varying(500) NOT NULL,
    pagina_fin character varying(20) NOT NULL,
    fasciculo character varying(500),
    volumen character varying(500),
    serie character varying(500),
    id_semillero integer NOT NULL,
    categoria character varying(10),
    id_autor integer,
    archivo character varying(500)
);


--
-- Name: msi_autorsemillero_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_autorsemillero_id_seq
    START WITH 68
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_autorsemillero; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_autorsemillero (
    id integer DEFAULT nextval('msi_autorsemillero_id_seq') NOT NULL,
    id_semillero integer NOT NULL,
    id_usuario integer NOT NULL,
    seccion character varying(20) NOT NULL,
    id_objeto integer
);


--
-- Name: msi_capitulosemillero_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_capitulosemillero_id_seq
    START WITH 12
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_capitulosemillero; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_capitulosemillero (
    id integer DEFAULT nextval('msi_capitulosemillero_id_seq') NOT NULL,
    titulo character varying(500) NOT NULL,
    paginas character varying(20),
    ano character varying(20) NOT NULL,
    mes character varying(20) NOT NULL,
    pais character varying(200),
    ciudad character varying(200),
    serie character varying(50),
    editorial character varying(500),
    edicion character varying(500),
    isbn character varying(20),
    lugar_publicacion character varying(500),
    medio_divulgacion character varying(500),
    titulo_capitulo character varying(500) NOT NULL,
    numero_capitulo character varying(50) NOT NULL,
    paginas_capitulo character varying(50) NOT NULL,
    pagina_inicio character varying(20) NOT NULL,
    pagina_fin character varying(20) NOT NULL,
    id_semillero integer NOT NULL,
    id_autor integer,
    archivo character varying(500)
);


--
-- Name: msi_documentosbibliograficos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_documentosbibliograficos_id_seq
    START WITH 22
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_documentosbibliograficos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_documentosbibliograficos (
    id integer DEFAULT nextval('msi_documentosbibliograficos_id_seq') NOT NULL,
    id_semillero integer NOT NULL,
    id_autor integer NOT NULL,
    nombre character varying(500) NOT NULL,
    numero_paginas character varying(20),
    ano character varying(20) NOT NULL,
    mes character varying(20),
    pais character varying(200),
    ciudad character varying(200),
    numero_indexacion character varying(20),
    instituciones character varying(2500),
    url character varying(50),
    descripcion character varying(7000),
    medio_divulgacion character varying(2500),
    archivo character varying(500)
);


--
-- Name: msi_eventossemi; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_eventossemi (
    id_evento integer NOT NULL,
    id_tipoevento integer,
    id_tipoparticipacion integer,
    nombre_evento character varying(500),
    nombre_trabajo character varying(500),
    id_institucion integer,
    ciudad_trabajo character varying(500),
    fecha_inicio character varying(10),
    fecha_fin character varying(10),
    otra_informacion character varying(4000),
    id_tipomedio integer,
    nombre_trabajo_medio character varying(500),
    id_autor integer,
    id_institucion_medio integer,
    ciudad_medio character varying(500),
    medio_divulgacion character varying(500),
    fecha_medio character varying(10),
    descripcion_medio character varying(4000),
    otra_informacion_medio character varying(4000),
    archivo character varying(500),
    id_red integer
);


--
-- Name: msi_eventossemi_id_evento_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_eventossemi_id_evento_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_eventossemi_id_evento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE msi_eventossemi_id_evento_seq OWNED BY msi_eventossemi.id_evento;


--
-- Name: msi_grupossemillero_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_grupossemillero_id_seq
    START WITH 63
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_grupossemillero; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_grupossemillero (
    id integer DEFAULT nextval('msi_grupossemillero_id_seq') NOT NULL,
    id_semillero integer NOT NULL,
    id_grupo integer NOT NULL,
    archivo character varying(500)
);


--
-- Name: msi_identificadoressemi; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_identificadoressemi (
    id_identificador integer NOT NULL,
    id_tipoidentificador integer,
    id_tipocategoria integer,
    id_field character varying(50),
    fecha_registro character varying(10),
    nombre character varying(500),
    web character varying(500),
    ciudad character varying(500),
    descripcion character varying(4000),
    otra_informacion character varying(4000),
    archivo character varying(500),
    id_red integer
);


--
-- Name: msi_identificadoressemi_id_identificador_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_identificadoressemi_id_identificador_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_identificadoressemi_id_identificador_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE msi_identificadoressemi_id_identificador_seq OWNED BY msi_identificadoressemi.id_identificador;


--
-- Name: msi_integrantes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_integrantes_id_seq
    START WITH 871
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_integrantes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_integrantes (
    id integer DEFAULT nextval('msi_integrantes_id_seq') NOT NULL,
    id_semillero integer NOT NULL,
    integrante integer NOT NULL,
    tipo_vinculacion integer NOT NULL,
    fecha_inicio date NOT NULL,
    fecha_fin date,
    rol_participacion character varying(10),
    archivo character varying(500),
    estado character varying(10)
);


--
-- Name: msi_librossemillero_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_librossemillero_id_seq
    START WITH 21
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_librossemillero; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_librossemillero (
    id integer DEFAULT nextval('msi_librossemillero_id_seq') NOT NULL,
    titulo character varying(500) NOT NULL,
    paginas character varying(20) NOT NULL,
    ano character varying(20) NOT NULL,
    mes character varying(20) NOT NULL,
    pais character varying(200),
    ciudad character varying(200),
    serie character varying(50),
    editorial character varying(500),
    edicion character varying(500),
    isbn character varying(20),
    lugar_publicacion character varying(500),
    medio_divulgacion character varying(500),
    id_semillero integer NOT NULL,
    id_autor integer,
    archivo character varying(500),
    tipo_libro character varying(20)
);


--
-- Name: msi_participacioneventos_id_archivo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_participacioneventos_id_archivo_seq
    START WITH 396
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_participacioneventos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_participacioneventos (
    id_archivo integer DEFAULT nextval('msi_participacioneventos_id_archivo_seq') NOT NULL,
    id_semillero integer,
    archivo character varying(200),
    nombre character varying(200),
    lugar character varying(200),
    tipo_participacion character varying(10),
    fecha date,
    new_archivo character varying(3)
);


--
-- Name: msi_produccionesinvsemillero_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_produccionesinvsemillero_id_seq
    START WITH 74
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_produccionesinvsemillero; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_produccionesinvsemillero (
    id integer DEFAULT nextval('msi_produccionesinvsemillero_id_seq') NOT NULL,
    nombre character varying(2500) NOT NULL,
    ano character varying(20) NOT NULL,
    mes character varying(20),
    pais character varying(200),
    ciudad character varying(200),
    descripcion character varying(2500),
    tipo character varying(2500),
    instituciones character varying(2500),
    id_semillero integer NOT NULL,
    registro character varying(7000),
    otra_informacion character varying(7000),
    id_autor integer,
    archivo character varying(500)
);


--
-- Name: msi_proyectosint_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_proyectosint_id_seq
    START WITH 23
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_proyectosint; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_proyectosint (
    id integer DEFAULT nextval('msi_proyectosint_id_seq') NOT NULL,
    id_grupo_inv integer,
    id_proyecto integer,
    archivo character varying(500)
);


--
-- Name: msi_reconocimientos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_reconocimientos_id_seq
    START WITH 11
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_reconocimientos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_reconocimientos (
    id integer DEFAULT nextval('msi_reconocimientos_id_seq') NOT NULL,
    id_semillero integer NOT NULL,
    nombre character varying(200),
    numero_acto character varying(20),
    descripcion character varying(7000),
    valor character varying(50),
    fecha character varying(15),
    archivo character varying(500)
);


--
-- Name: msi_semillerosinv_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_semillerosinv_id_seq
    START WITH 137
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_semillerosinv; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_semillerosinv (
    id integer DEFAULT nextval('msi_semillerosinv_id_seq') NOT NULL,
    coordinador_uno integer,
    coordinador_dos integer,
    nombre character varying(500) NOT NULL,
    codigo character varying(500) NOT NULL,
    estado character varying(2) NOT NULL,
    fecha_creacion date,
    id_unidad_academica integer,
    id_dependencia_academica integer,
    id_programa_academico integer,
    estudiantes character varying(20),
    que_entiende character varying(7000),
    objetivo_general character varying(7000),
    objetivo_especifico character varying(7000),
    actividades character varying(7000),
    relacion_procesos character varying(7000),
    relacion_instituciones character varying(7000),
    fecha_estado date
);


--
-- Name: msi_trabajogradosemi; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE msi_trabajogradosemi (
    id_trabajogrado integer NOT NULL,
    nombre_trabajo character varying(500),
    id_tipotrabajo character varying(30),
    id_estadotipotrabajo integer,
    id_tipoparticipacion integer,
    id_autor integer,
    id_institucion integer,
    ciudad_trabajo character varying(500),
    id_unidad integer,
    id_dependencia integer,
    id_programa integer,
    fecha_inicio character varying(10),
    fecha_fin character varying(10),
    descripcion character varying(4000),
    otra_informacion character varying(4000),
    id_formacioninvestigador integer,
    codigo_proyecto character varying(500),
    nombre_proyecto character varying(500),
    id_institucion_proyecto integer,
    ciudad_proyecto character varying(500),
    personas_formadas character varying(30),
    fecha_inicio_proyecto character varying(10),
    fecha_fin_proyecto character varying(10),
    descripcion_proyecto character varying(4000),
    descripcion_formacion character varying(4000),
    otra_informacion_proyecto character varying(4000),
    id_semillero integer,
    id_institucion_semillero integer,
    id_rolparticipacion integer,
    fecha_inicio_semillero character varying(10),
    fecha_fin_semillero character varying(10),
    tematica character varying(4000),
    descripcion_semillero character varying(4000),
    archivo character varying(500),
    id_red integer,
    id_investigador integer
);


--
-- Name: msi_trabajogradosemi_id_trabajogrado_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE msi_trabajogradosemi_id_trabajogrado_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: msi_trabajogradosemi_id_trabajogrado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE msi_trabajogradosemi_id_trabajogrado_seq OWNED BY msi_trabajogradosemi.id_trabajogrado;


--
-- Name: multimedia; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE multimedia (
    id_multimedia integer NOT NULL,
    url character varying(500)
);


--
-- Name: multimedia_id_multimedia_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE multimedia_id_multimedia_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: multimedia_id_multimedia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

--ALTER SEQUENCE multimedia_id_multimedia_seq OWNED BY multimedia.id_multimedia;


--
-- Name: plan_trab_investig; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE plan_trab_investig (
    emp_codigo character varying(11) NOT NULL,
    nombre character varying(62),
    vinculacion character varying(60) NOT NULL,
    ano numeric(4,0) NOT NULL,
    periodo numeric(2,0) NOT NULL,
    estado character varying(13),
    tipo_proyecto character varying(15),
    tipo_proyecto_plantrab character varying(13),
    codigo_proyecto character varying(16) NOT NULL,
    nombre_proyecto character varying(500) NOT NULL,
    horas_semana_plantrab numeric(2,0) NOT NULL,
    horas_autorizadas numeric(2,0) NOT NULL,
    fecha date NOT NULL
);


--
-- Name: rep_autor_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE rep_autor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: rep_autor; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE rep_autor (
    id integer DEFAULT nextval('rep_autor_id_seq') NOT NULL,
    id_repositorio integer NOT NULL,
    id_usuario integer NOT NULL,
    seccion character varying(20) NOT NULL,
    id_objeto integer
);


--
-- Name: repositorio_id_repositorio_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE repositorio_id_repositorio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: repositorio; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE repositorio (
    id_repositorio integer DEFAULT nextval('repositorio_id_repositorio_seq') NOT NULL,
    archivo character varying(200),
    nombre character varying(500),
    id_tipo integer,
    url character varying(500),
    descripcion character varying(10000),
    otra_informacion character varying(10000),
    fecha_creacion date,
    id_usuario_creador integer
);

--
-- Name: aps_eventosusu id_evento; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE aps_eventosusu ALTER COLUMN id_evento SET DEFAULT nextval('aps_eventosusu_id_evento_seq');


--
-- Name: aps_identificadoresusu id_identificador; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE aps_identificadoresusu ALTER COLUMN id_identificador SET DEFAULT nextval('aps_identificadoresusu_id_identificador_seq');


--
-- Name: aps_trabajogradousu id_trabajogrado; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE aps_trabajogradousu ALTER COLUMN id_trabajogrado SET DEFAULT nextval('aps_trabajogradousu_id_trabajogrado_seq');


--
-- Name: mgi_eventosgru id_evento; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE mgi_eventosgru ALTER COLUMN id_evento SET DEFAULT nextval('mgi_eventosgru_id_evento_seq');


--
-- Name: mgi_identificadoresgru id_identificador; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE mgi_identificadoresgru ALTER COLUMN id_identificador SET DEFAULT nextval('mgi_identificadoresgru_id_identificador_seq');


--
-- Name: mgi_trabajogradogru id_trabajogrado; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE mgi_trabajogradogru ALTER COLUMN id_trabajogrado SET DEFAULT nextval('mgi_trabajogradogru_id_trabajogrado_seq');


--
-- Name: mgp_entidadesejecutoras id_entidadejecutora; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE mgp_entidadesejecutoras ALTER COLUMN id_entidadejecutora SET DEFAULT nextval('mgp_entidadesejecutoras_id_entidadejecutora_seq');


--
-- Name: mri_eventosred id_evento; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE mri_eventosred ALTER COLUMN id_evento SET DEFAULT nextval('mri_eventosred_id_evento_seq');


--
-- Name: mri_identificadoresred id_identificador; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE mri_identificadoresred ALTER COLUMN id_identificador SET DEFAULT nextval('mri_identificadoresred_id_identificador_seq');


--
-- Name: mri_trabajogradored id_trabajogrado; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE mri_trabajogradored ALTER COLUMN id_trabajogrado SET DEFAULT nextval('mri_trabajogradored_id_trabajogrado_seq');


--
-- Name: msi_eventossemi id_evento; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE msi_eventossemi ALTER COLUMN id_evento SET DEFAULT nextval('msi_eventossemi_id_evento_seq');


--
-- Name: msi_identificadoressemi id_identificador; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE msi_identificadoressemi ALTER COLUMN id_identificador SET DEFAULT nextval('msi_identificadoressemi_id_identificador_seq');


--
-- Name: msi_trabajogradosemi id_trabajogrado; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE msi_trabajogradosemi ALTER COLUMN id_trabajogrado SET DEFAULT nextval('msi_trabajogradosemi_id_trabajogrado_seq');


--
-- Name: multimedia id_multimedia; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE multimedia ALTER COLUMN id_multimedia SET DEFAULT nextval('multimedia_id_multimedia_seq');

--
-- PostgreSQL database dump complete
--

