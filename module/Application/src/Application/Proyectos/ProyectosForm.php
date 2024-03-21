<?php
namespace Application\Proyectos;

use Zend\Form\Form;
use Zend\Form\Element;

class ProyectosForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        // create fields
        
        $this->add(array(
            'name' => 'nombre_proy',
            'attributes' => array(
                'type' => 'text',
                'required' => 'required'                
            ),
            'options' => array(
                'label' => 'Nombre del proyecto o proceso de investigación:'
            )
        ));
        
        $this->add(array(
            'name' => 'primera_vigencia',
            'attributes' => array(
                'type' => 'Number',
                'placeholder' => 'Ingrese el año la primera vigencia presupuestal',
                'required' => 'required',
                'min' => 1989,
                'max' => 2099
            ),
            'options' => array(
                
                'label' => 'Primera Vigencia:'
            )
        ));
        
        $this->add(array(
            'name' => 'codigo_proy',
            'attributes' => array(
                'type' => 'text',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Código del proyecto o proceso de investigación:'
            )
        ));
        
        $this->add(array(
            'name' => 'duracion',
            'attributes' => array(
                'type' => 'Number',
                'min' => 0,
                'required' => 'required',
                'placeholder' => 'Número de periodos académicos y/o meses'
            ),
            'options' => array(
                'label' => 'Duración de la Investigación (Períodos/Meses):'
            )
        ));
        
        $this->add(array(
            'name' => 'resumen_ejecutivo',
            'attributes' => array(
                'type' => 'textarea',
                'required' => 'required',
                'placeholder' => 'Ingrese el resumen ejecutivo',
                'lenght' => 500
            ),
            'options' => array(
                'label' => 'Resumen Ejecutivo :'
            )
        ));
        
        $this->add(array(
            'name' => 'objetivo_general',
            'attributes' => array(
                'type' => 'textarea',
                'required' => 'required',
                'placeholder' => 'Ingrese el objetivo general',
                'lenght' => 500
            ),
            'options' => array(
                'label' => 'Objetivo General:'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn',
                'value' => 'Guardar'
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'tipo_proyecto',
            'required' => 'required',
            'options' => array(
                'label' => 'Tipo de proyecto',
                'value_options' => array(
                    '' => 'Seleccione',
                    'I' => 'Interno',
                    'E' => 'Externo-cofinanciado',
                    'S' => 'Especial'
                )
            ),
            'attributes' => array(
                'value' => '1',
                'onchange' => 'tipoProyecto()'
            ) // set selected to '1'

        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'periodo',
            'required' => 'required',
            'options' => array(
                'label' => 'Período',
                'value_options' => array(
                    '' => '',
                    'M' => 'Meses',
                    'S' => 'Semestres'
                )
            ),
            'attributes' => array(
                'value' => '1'
            ) // set selected to '1'

        ));

        $this->add(array(
            'name' => 'modificaciones_documento',
            'type' => 'textarea',
            'attributes' => array(
                'id' => 'modificaciones_documento',
                'maxlength' => "2500"
            ),
            'options' => array(
                'label' => 'Modificaciones al documento de formalización del proyecto: '
            )
        ));

        $this->add(array(
            'name' => 'documento_formalizacion',
            'type' => 'textarea',
            'attributes' => array(
                'id' => 'documento_formalizacion',
                'maxlength' => "2500"
            ),
            'options' => array(
                'label' => 'Documento de formalización del proyecto: '
            )
        ));

        $this->add(array(
            'name' => 'fecha_inicio',
            'type' => 'date',
            'attributes' => array(
                'id' => 'fecha_inicio',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Fecha de inicio: '
            )
        ));

        $this->add(array(
            'name' => 'fecha_terminacion',
            'type' => 'date',
            'attributes' => array(
                'id' => 'fecha_terminacion'
            ),
            'options' => array(
                'label' => 'Fecha de terminación: '
            )
        ));

        $this->add(array(
            'name' => 'prorroga',
            'type' => 'date',
            'attributes' => array(
                'id' => 'prorroga'
            ),
            'options' => array(
                'label' => 'Prorroga: '
            )
        ));

        $this->add(array(
            'name' => 'convocatoria',
            'type' => 'text',
            'attributes' => array(
                'id' => 'convocatoria',
                'placeholder' => "Registre el nombre de la convocatoria externa donde se aprobó́ el proyecto y/o la iniciativa interinstitucional",
                'maxlength' => "5000"
            ),
            'options' => array(
                'label' => 'Convocatoria externa: '
            )
        ));
    }
}

