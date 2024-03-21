<?php

namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class AplicariForm extends Form {
	function __construct() {
		parent::__construct ( $name = null );
		
		parent::setAttribute ( 'method', 'post' );
		parent::setAttribute ( 'action ', 'usuario' );

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

        $this->add(array(
            'name' => 'id_categoria',
            'type' => 'Zend\Form\Element\Select',
            'required' => 'required',
            'attributes' => array(
                'id' => 'id_categoria',
                'value' => '1',
                'onchange' => 'modelo();',
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
                'required' => 'required',
                'onkeyup' => 'sumValores()'
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
                'readonly' => 'true'
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
            'name' => 'area_tematica',
            'attributes' => array(
                'type'  => 'textarea',
                'id' => 'area_tematica',
                'maxlength' => 3500,
                'required'=>'required',
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
                'maxlength' => 2000,
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
                'maxlength' => 20000,
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
                'required'=>'required',
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
			'name' => 'fecha',
			'type' => 'date',
			'options' => array (
				'label' => 'Fecha:', 
			) 
		));

		$this->add ( array (
			'name' => 'semestre',
			'type' => 'Zend\Form\Element\Select',
			'options' => array (
				'label' => 'Semestre que cursa:',
				'value_options' => array(
                    '1' => 'Primero',
                    '2' => 'Segundo',
                    '3' => 'Tercero',
                    '4' => 'Cuarto',
                    '5' => 'Quinto',
                    '6' => 'Sexto',
                    '7' => 'Septimo',
                    '8' => 'Octavo',
                    '9' => 'Noveno',
                    '10' => 'Decimo'
                )
			) 
		));
			
		
		$this->add ( array (
				'name' => 'submit',
				'attributes' => array (
						'type' => 'submit',
						'class' => 'btn',
						'value' => 'Continuar' 
				) 
		) );
	}
}

