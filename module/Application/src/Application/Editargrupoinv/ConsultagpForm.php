<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class ConsultagpForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
        $this->add(array(
            'name' => 'nombre_grupo',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Nombre grupo',
            ),
            'options' => array(
                'label' => 'Nombre grupo:',
            ),
        ));

        $this->add(array(
            'name' => 'cod_grupo',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Código grupo',
            ),
            'options' => array(
                'label' => 'Código grupo:',
            ),
        ));

        $this->add(array(
            'name' => 'codigo_proy',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Código proyecto',
            ),
            'options' => array(
                'label' => 'Código proyecto:',
            ),
        ));

        $this->add(array(
            'name' => 'nombre_proy',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Nombre proyecto',
            ),
            'options' => array(
                'label' => 'Nombre del proyecto:',
            ),
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
                'value' => '1'
            ) // set selected to '1'

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

