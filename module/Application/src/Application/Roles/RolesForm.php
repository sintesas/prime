<?php


namespace Application\Roles;

use Zend\Form\Form;
use Zend\Form\Element;



class RolesForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
'size' => 35,
                'type'  => 'text',
 'required'=>'required',
				'placeholder'=>'ingrese el nombre del rol'
            ),
            'options' => array(
                'label' => 'Nombre :',
            ),
        ));

        $this->add(array(
            'name' => 'observaciones',
            'attributes' => array(
'size' => 35,
                'type'  => 'textarea',
 'required'=>'required',
				'placeholder'=>'ingrese la descripción'
            ),
            'options' => array(
                'label' => 'Descripción :',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'opcion_pantalla',
            'required' => 'required',
            'options' => array(
                'label' => 'Tipo de menú:',
                'value_options' => array(
                    '1' => 'Menú admin',
                    '2' => 'Menú limitado'
                )
            ),
            'attributes' => array(
                'value' => '2'
            ) 
        ));
		
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
'size'=>35,
		     'required'=>'required',
			 'value'=>'Agregar',
			 'class'=>'btn1',
		  ),
	   ));
	   


    }
}

