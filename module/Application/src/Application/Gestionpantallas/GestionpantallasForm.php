<?php


namespace Application\Gestionpantallas;

use Zend\Form\Form;
use Zend\Form\Element;


class GestionpantallasForm extends Form 
{
    function __construct()
    {
		parent::__construct( $name = null);

		parent::setAttribute('method', 'post');
		parent::setAttribute('action ', 'usuario');

	    $this->add(array(
			'name'=>'submit',
			'attributes'=>array(
				'type'=>'submit',
				'class'=>'btn',
				'value'=>'Agregar',
		  ),
	   ));
    }
}

