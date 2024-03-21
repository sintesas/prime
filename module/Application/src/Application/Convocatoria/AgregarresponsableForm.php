<?php
namespace Application\Convocatoria;
use Zend\Form\Form;
use Zend\Form\Element;

class AgregarresponsableForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
   
	    //create field	
        $this->add(array(
            'name' => 'id_rol',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_rol'
            ),
            'options' => array(
                'label' => 'Rol responsable:'
            )
        ));
			
	    $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'class'=>'btn',
			 'value'=>'Agregar',
		  ),
	    ));
	   


    }
}

