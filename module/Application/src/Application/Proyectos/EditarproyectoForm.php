<?php


namespace Application\Proyectos;

use Zend\Form\Form;


class EditarproyectoForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields


	    $this->add(array(
			'name'=>'submit',
			'attributes'=>array(
				'type'=>'submit',
				'class'=>'btn1',
				'value'=>'Actualizar',
		  ),
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
                'id' => 'fecha_inicio'
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

