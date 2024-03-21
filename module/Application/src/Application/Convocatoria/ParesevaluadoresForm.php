<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class ParesevaluadoresForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>'Filtro'
            ),
            'options' => array(
                'label' => 'Filtro nombre del evaluador:',
            ),
        ));

        $this->add(array(
            'name' => 'documento',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>'Ingresar número de documento'
            ),
            'options' => array(
                'label' => 'Filtro número de documento:',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'tema',
            'options' => array(
                'label' => 'Filtro tema/campo/area de actuación: ',
            ),
            'attributes' => array(
                'value' => '',
                'id' => 'tema'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'tipo_evaluador',
            'options' => array(
                'label' => 'Filtro tipo evaluador: ',
                'value_options' => array(
                    '' => '',
                    'Interno' => 'Interno',
                    'Externo' => 'Externo'
                )
            ),
            'attributes' => array(
                'value' => '',
                'id' => 'tipo_evaluador'
            )
        ));

        $this->add(array(
            'name' => 'usuario',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>'Ingresar usuario (Correo eléctronico)'
            ),
            'options' => array(
                'label' => 'Filtro usuario:',
            ),
        ));

        $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
             'class'=>'btn',
			 'value'=>'Filtrar',
		  ),
	   ));
	   


    }
}

