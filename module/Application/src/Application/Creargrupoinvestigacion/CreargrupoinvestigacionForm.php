<?php


namespace Application\Creargrupoinvestigacion;

use Zend\Form\Form;
use Zend\Form\Element;


class CreargrupoinvestigacionForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	parent::setAttribute('method', 'post');
	parent::setAttribute('action ', 'usuario');
	   
	//Creacion de campos
	
        	$this->add(array(
            		'name' => 'nombre_grupo',
            		'attributes' => array(
			'type'  => 'text',
			'placeholder'=>'Ingrese el nombre del grupo de investigación',
			'required'=>'required',
            		),
            		'options' => array(
		'label' => 'Nombre del Grupo :',
            		),
        	));

        	$this->add(array(
            		'name' => 'cod_grupo',
            		'attributes' => array(
			'type'  => 'text',
			'placeholder'=>'Ingrese el código del grupo de investigación',
			'required'=>'required',
            		),
            		'options' => array(
		'label' => 'Código del Grupo :',
            		),
        	));

        	$this->add(array(
            		'type' => 'Zend\Form\Element\Select',
            		'name' => 'estado',
		'required'=>'required',
            		'options' => array(
                			'label' => 'Estado :',
                			'value_options' => array(
                   			'' => 'Seleccione estado',
                    			'A' => 'Activo',
                    			'I' => 'Inactivo'
                			),
            		),
            		'attributes' => array(
            			'value' => '' //set selected to '1'
            		)
        	));

        	$this->add(array(
            		'name' => 'url',
            		'attributes' => array(
			'type'  => 'text',
			'placeholder'=>'Ingrese la url del grupo de investigacion',
			'required'=>'required',
            		),
            		'options' => array(
		'label' => 'Url :',
            		),
        	));
	
        	$this->add(array(
            		'name' => 'plan_accion',
            		'attributes' => array(
                			'type'  => 'textarea',
			'required'=>'required',
			'placeholder'=>'Ingrese el plan de acción',
			'lenght' => 500,
            		),
            		'options' => array(
                			'label' => 'Plan de Acción :',
            		),
        	));

        	$this->add(array(
            		'name' => 'mision',
            		'attributes' => array(
                			'type'  => 'textarea',
			'required'=>'required',
			'placeholder'=>'Ingrese la misión',
			'lenght' => 500,
            		),
            		'options' => array(
                			'label' => 'Misión :',
            		),
        	));	

        	$this->add(array(
            		'name' => 'vision',
            		'attributes' => array(
                			'type'  => 'textarea',
			'required'=>'required',
			'placeholder'=>'Ingrese la visión',
			'lenght' => 500,
            		),
            		'options' => array(
                			'label' => 'Visión :',
            		),
        	));	
	

        	$this->add(array(
            		'name' => 'redes_grupo',
            		'attributes' => array(
                			'type'  => 'textarea',
			'required'=>'required',
			'placeholder'=>'Ingrese las redes del grupo',
			'lenght' => 500,
            		),
            		'options' => array(
                			'label' => 'Redes del grupo :',
            		),
        	));	

        	$this->add(array(
            		'name' => 'asociaciones',
            		'attributes' => array(
                			'type'  => 'textarea',
			'required'=>'required',
			'placeholder'=>'Ingrese las asociaciones del grupo',
			'lenght' => 500,
            		),
            		'options' => array(
                			'label' => 'Asociaciones :',
            		),
        	));	

        	$this->add(array(
            		'name' => 'email',
            		'attributes' => array(
			'type'  => 'text',
			'placeholder'=>'Ingrese el email del grupo de investigación',
			'required'=>'required',
            		),
            		'options' => array(
		'label' => 'Email :',
            		),
        	));

        	$this->add(array(
            		'name' => 'telefono',
            		'attributes' => array(
			'type'  => 'text',
			'placeholder'=>'Ingrese el telefono del grupo de investigacion',
			'required'=>'required',
            		),
            		'options' => array(
		'label' => 'Teléfono :',
            		),
        	));

        	$this->add(array(
            		'name' => 'dir_postal',
            		'attributes' => array(
			'type'  => 'text',
			'placeholder'=>'Ingrese la dirección postal',
			'required'=>'required',
            		),
            		'options' => array(
		'label' => 'Dirección Postal :',
            		),
        	));

        	$this->add(array(
            		'name' => 'plan_estrategico',
            		'attributes' => array(
                			'type'  => 'textarea',
			'required'=>'required',
			'placeholder'=>'Ingrese el plan estrategico del grupo',
			'lenght' => 500,
            		),
            		'options' => array(
                			'label' => 'Plan estratégico :',
            		),
        	));

        	$this->add(array(
            		'name' => 'sectores_aplicacion',
            		'attributes' => array(
                			'type'  => 'textarea',
			'required'=>'required',
			'placeholder'=>'Ingrese los sectores de aplicación del grupo',
			'lenght' => 500,
            		),
            		'options' => array(
                			'label' => 'Sectores de aplicación :',
            		),
        	));

	$this->add(array(
		'name'=>'submit',
		'attributes'=>array(
			'type'=>'submit',
			'class'=>'btn',
			'value'=>'Guardar',
		  ),
	));
	   


    }
}