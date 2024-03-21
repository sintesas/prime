<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class GestionproymForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
        $this->add(array(
            'name' => 'id_aplicar',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el ID del proyecto',
            ),
            'options' => array(
                'label' => 'ID proyecto :',
            ),
        ));


        $this->add(array(
            'name' => 'nombre_proy',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el nombre del proyecto',
            ),
            'options' => array(
                'label' => 'Nombre del Proyecto :',
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

