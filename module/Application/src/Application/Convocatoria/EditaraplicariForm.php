<?php

namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class EditaraplicariForm extends Form {
	function __construct() {
		parent::__construct ( $name = null );
		
		parent::setAttribute ( 'method', 'post' );
		parent::setAttribute ( 'action ', 'usuario' );
/*
		$this->add(array(
            'name' => 'nombre_proy',
            'attributes' => array(
                'required' => 'required',
                'maxlength' => 300,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Título del proyecto de investigación:'
            )
        ));
*/
        $this->add(array(
            'name' => 'nombre_proy',
            'attributes' => array(
                'type'  => 'textarea',
                'required'=>'required',
                'id' => 'nombre_proy',
                'maxlength' => 300,
                'placeholder'=>'Título del proyecto de investigación'
            ),
            'options' => array(
                'label' => 'Título del proyecto de investigación:',
            ),
        ));

        $this->add(array(
            'name' => 'nombre_modalidad',
            'attributes' => array(
                'disabled' => 'disabled',
                'maxlength' => 5000,
                'type' => 'text'
            )
        ));

        $this->add(array(
            'name' => 'id_categoria',
            'type' => 'Zend\Form\Element\Select',
            'required' => 'required',
            'attributes' => array(
                'id' => 'id_categoria',
                'onchange' => 'modelo();'
            ),
            'options' => array(
                'label' => 'Categoría dentro de la convocatoria:',
                /*
                'value_options' => array(
                    '1' => 'MODALIDAD 1. INVESTIGACIÓN EN LAS LÍNEAS DE LOS GRUPOS CONSOLIDADOS',
                    '2' => 'MODALIDAD 2. INVESTIGACIÓN – CREACIÓN',
                    '3' => 'MODALIDAD 3. PROYECCIÓN Y CONSOLIDACIÓN DE LA INVESTIGACIÓN',
                    '4' => 'MODALIDAD 4. SEMILLEROS DE INVESTIGACIÓN, GRUPOS DE ESTUDIO Y COLECTIVOS
ACADÉMICOS',
                    '6' => 'MODALIDAD 5: EJES ESTRATÉGICOS DE LAS FACULTADES Y EL DIE',
                    '5' => 'MODALIDAD 6: INVESTIGACIÓN FORMATIVA DESARROLLADA CON GRUPOS INFANTILES Y JUVENILES',
                    '7' => 'MODALIDAD 7: PROYECTOS ESTRATÉGICOS DEL IPN Y DE LA ESCUELA MATERNAL'
                )
                */
            )
        ));

        $this->add(array(
            'name' => 'id_area_tematica',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Área temática:'
            ),
            'attributes' => array(
                'required' => 'required' 
            ) 
		));

        $this->add(array(
            'name' => 'id_campo',
            'type' => 'Zend\Form\Element\Select',
            
            'options' => array(
                'label' => 'Campo de investigación: '
               
            ),
            'attributes' => array(
                'id' => 'id_campo',
                'required' => 'required',
                'onchange' => 'myFunction(this.id);'
            )
        ));

        $this->add(array(
            'name' => 'id_linea_inv',
            'type' => 'Zend\Form\Element\Select',
            
            'options' => array(
                'label' => 'Línea investigación: '
            ),
            'attributes' => array(
                'id' => 'id_linea_inv',
                'required' => 'required'
            )
        ));

        $this->add(array(
            'name' => 'recursos_funcion',
            'attributes' => array(
                'type' => 'number',
                'min' => 0,
                'placeholder' => 'Ingrese los recursos de funcionamiento',
                'required' => 'required',
                 'onkeyup' => 'sumValores()'
            ),
            'options' => array(
                'label' => 'Recursos de funcionamiento:'
            )
        ));

        $this->add(array(
            'name' => 'recursos_inversion',
            'attributes' => array(
                'type' => 'number',
                'min' => 0,
                'placeholder' => 'Ingrese los recursos de inversión u otro UPN',
                'required' => 'required',
                'onkeyup' => 'sumValores()'
            )
            ,
            'options' => array(
                'label' => 'Recursos de inversión y/o otro UPN:'
            )
        ));

        $this->add(array(
            'name' => 'total_financia',
            'attributes' => array(
                'type' => 'number',
                'id' => 'total_financia',
                'min' => 0,
                'placeholder' => 'Ingrese el total de cofinanciación',
                'onkeyup' => 'sumValores()',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Recursos de cofinanciación:'
            )
        ));

        $this->add(array(
            'name' => 'total_proy',
            'attributes' => array(
                'type' => 'number',
                'min' => 0,
                'placeholder' => 'Ingrese el total de financiación del proyecto',
                'required' => 'required',
                'readonly' => 'true',
                'pattern'  => '([0-9]{1,3}).([0-9]{1,3})'
            ),
            'options' => array(
                'label' => 'Total financiación del proyecto:'
            )
        ));

        $this->add(array(
            'name' => 'investigador_principal',
            'attributes' => array(
                'disabled' => 'disabled',
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Investigador principal:'
            )
        ));

        $this->add(array(
            'name' => 'tipo_documento',
            'attributes' => array(
                'disabled' => 'disabled',
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Tipo de documento:'
            )
        ));

        $this->add(array(
            'name' => 'numero_documento',
            'attributes' => array(
                'disabled' => 'disabled',
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Número de documento:'
            )
        ));

        $this->add(array(
            'name' => 'tipo_vinculacion',
            'attributes' => array(
                'disabled' => 'disabled',
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Tipo de vinculación:'
            )
        ));

        $this->add(array(
		    'name' => 'id_unidad_academica',
		    'type' => 'Zend\Form\Element\Select',
		    
		    'options' => array(
		        'label' => 'Unidad académica: '
		    ),
		    'attributes' => array(
		        'id' => 'id_unidad_academica',
		        'onchange' => 'myFunction2();',
		        'required' => 'required'
		    ) 
		));

		$this->add(array(
            'name' => 'id_dependencia_academica',
            'type' => 'Zend\Form\Element\Select',
        
            'options' => array(
                'label' => 'Dependencia académica: '
            ),
            'attributes' => array(
                'id' => 'id_dependencia_academica',
                'onchange' => 'myFunction3();',
                'required' => 'required'
            ) // set selected to '1'
        ));

        $this->add(array(
            'name' => 'id_programa_academico',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Programa académico: '
            ),
            'attributes' => array(
                'id' => 'id_programa_academico',
                'required' => 'required',
                'readonly' => 'true'
            ) // set selected to '1'
        ));

        $this->add(array(
            'name' => 'instituciones_coofinanciacion',
            'type' => 'Zend\Form\Element\Select',
        
            'options' => array(
                'label' => 'Instituciones de coofinanciación: '
            ),
            'attributes' => array(
                'id' => 'instituciones_coofinanciacion',
                'required' => 'required'
            ) // set selected to '1'
        ));

        $this->add(array(
            'name' => 'duracion',
            'attributes' => array(
                'type' => 'number',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Duración de la investigación (Periodos/Meses):'
            )
        ));

        $this->add(array(
            'name' => 'semestresano',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Semestres en los cuales se ejecutará el proyecto:'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'periodo',
            'required' => 'required',
            'options' => array(
                'value_options' => array(
                    'M' => 'Meses',
                    'S' => 'Semestres'
                )
            ),
            'attributes' => array(
                'value' => 'M'
            ) 
        ));

        $this->add(array(
            'name' => 'id_semillero',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Nombre del semillero de investigación, grupo de estudio y/o colectivo académico:'
            ),
            'attributes' => array(
                'id' => 'id_semillero',
                'required' => 'required',
                'onchange' => 'myFunction10();',
            ) 
        ));

        $this->add(array(
            'name' => 'area_tematica',
            'attributes' => array(
                'type'  => 'textarea',
                'id' => 'area_tematica',
                'maxlength' => 50000,
                'placeholder'=>'En este apartado presente una breve descripción sobre el aporte que se hará con esta investigación al eje y/o línea de investigación en el cual se inscribe la propuesta. Revise términos de referencia.'
            ),
            'options' => array(
                'label' => 'Éje / Área temática:',
            ),
        ));

        $this->add(array(
            'name' => 'resumen_ejecutivo',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 5000,
                'required'=>'required',
                'placeholder'=>'Presente un resumen ejecutivo, en una página, el  cual debe contener la información necesaria para darle al lector una idea precisa del problema, los propósitos y la pertinencia del proyecto. Se recomienda presentar de manera sucinta el objeto del proyecto, la perspectiva teórica y metodológica.'
            ),
            'options' => array(
                'label' => 'Resumen ejecutivo:'
            ),
        ));

        $this->add(array(
            'name' => 'descriptores',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 1999,
                'required'=>'required',
                'placeholder'=>'Identifique las palabras claves que mejor definan el proyecto y que permitan ubicarlo en sistemas de información. Seleccione hasta 5 descriptores con los cuales se identifica el contenido de su propuesta de investigación.'
            ),
            'options' => array(
                'label' => 'Descriptores / Palabras claves:'
            ),
        ));

        $this->add(array(
            'name' => 'antecedentes',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 44000,
                'required'=>'required',
                'placeholder'=>'Describa la forma mediante la cual la propuesta se articula al trabajo investigativo previo del grupo de investigación, y cómo contribuye a fortalecer las agendas y líneas de investigación del mismo.'
            ),
            'options' => array(
                'label' => 'Antecedentes:'
            ),
        ));

        $this->add(array(
            'name' => 'planteamiento_problema',
            'attributes' => array(
                'id' => 'planteamiento_problema',
                'type'  => 'textarea',
                'maxlength' => 20000,
                'placeholder'=>'Formular de forma explícita el problema u objeto que se propone abordar o responder y, si es el caso, las preguntas que lo delimitan, mostrando la pertinencia en el contexto del área del conocimiento del grupo y de las líneas declaradas por el mismo, en la cual se ubican y en relación directa con la problematización construida. En este apartado, los proponentes deberán enunciar la o las hipótesis de investigación que han elaborado (sólo si son pertinentes dentro de la estrategia de investigación seleccionada), y la justificación de la problemática.'
            ),
            'options' => array(
                'label' => 'Planteamiento del problema:'
            ),
        ));

        $this->add(array(
            'name' => 'objetivo_general',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 2500,
                'required'=>'required',
                'placeholder'=>'Plantee el propósito más amplio que se aspira desarrollar, teniendo en cuenta que en su formulación se condensa la perspectiva conceptual del proyecto. Su formulación es la base para la definición de los objetivos específicos y metas..'
            ),
            'options' => array(
                'label' => 'Objetivo general del proyecto:'
            ),
        ));

        $this->add(array(
            'name' => 'marco_teorico',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 115000,
                'required'=>'required',
                'placeholder'=>'Presente la perspectiva teórica en que se inscribe la propuesta; sustente la pertinencia que ofrece el abordaje conceptual seleccionado para abordar el objeto de la investigación; y evidencie la relación entre el marco teórico y la pregunta o problema de investigación.'
            ),
            'options' => array(
                'label' => 'Marco teórico:'
            ),
        ));

        $this->add(array(
            'name' => 'estado_arte',
            'attributes' => array(
                'id' => 'estado_arte',
                'type'  => 'textarea',
                'maxlength' => 115000,
                'placeholder'=>'(Para los proyectos que lo requieran o que lo exija la convocatoria). Este estado del arte puede adoptar diferentes modalidades: elaboración de un panorama de la trayectoria investigativa o del campo de conocimiento en que se ubica la propuesta; o aquella que ha(n) producido el (los) grupos que se postula(n), así como el balance del desarrollo investigativo generados en torno a los objetos de estudio que se abordan en los programas de formación de la Universidad.'
            ),
            'options' => array(
                'label' => 'Estado del arte:'
            ),
        ));

        $this->add(array(
            'name' => 'bibliografia',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 22000,
                'required'=>'required',
                'placeholder'=>'Incluir las referencias bibliográficas empleadas en la elaboración de la propuesta conforme a la normatividad vigente en el tema.'
            ),
            'options' => array(
                'label' => 'Bibliografía:'
            ),
        ));


        $this->add(array(
            'name' => 'metodologia',
            'attributes' => array(
                'id' => 'metodologia',
                'type'  => 'textarea',
                'maxlength' => 60000,
                'placeholder'=>'Sustente el abordaje metodológico que, en coherencia con la perspectiva conceptual, hace posible el desarrollo de la investigación. Se ha de visibilizar la consistencia y la articulación entre el problema, el marco teórico, y la manera en que se desarrollará este abordaje metodológico. En este mismo apartado se presentarán los instrumentos, estrategias a desarrollar y momentos. En el caso de las investigaciones que lo ameriten, se presenta la población o muestra participante en el estudio.'
            ),
            'options' => array(
                'label' => 'Metodología:'
            ),
        ));

        $this->add(array(
            'name' => 'momentos_proyecto',
            'attributes' => array(
                'id' => 'momentos_proyecto',
                'type'  => 'textarea',
                'maxlength' => 125000,
                'required'=>'required',
                'placeholder'=>'En este módulo se sustenta la perspectiva, las fases o momentos que se consideran como necesarios para el desarrollo del proyecto de investigación-creación. En este mismo apartado se debe incluir una descripción de los medios y procedimientos a través de los cuales se realizará la documentación y/o registro del proceso de investigación–creación (audiovisual o sonoro, partituras, fotografías, bocetos, planos,entre otros), haciendo explícita la pertinencia de los mismos.'
            ),
            'options' => array(
                'label' => 'Momentos del proyecto de investigación - Creación:'
            ),
        ));

        $this->add(array(
            'name' => 'compromisos_conocimiento',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 40000,
                'required'=>'required',
                'placeholder'=>'La apropiación social del conocimiento se construye desde procesos de comprensión e interacción a partir de la participación activa de los diversos grupos y actores sociales que generan conocimiento. Esta apropiación no supone una recepción pasiva, sino que involucra una dinámica interpretativa, de intercambio de saberes y el desarrollo de unas prácticas reflexivas y críticas frente al conocimiento producido; por tanto conlleva traducción y articulación entre marcos de referencia de los grupos participantes, así como procesos de formación en investigación.'
            ),
            'options' => array(
                'label' => 'Compromisos de apropiación social del conocimiento:'
            ),
        ));
		
		$this->add ( array (
				'name' => 'submit',
				'attributes' => array (
						'type' => 'submit',
						'class' => 'btn',
						'value' => 'Actualizar' 
				) 
		) );
	}
}

