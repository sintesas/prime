<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class hgrupoinvestigacionForm extends Form 
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
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Nombre grupo:',
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

