<?php


namespace Application\Multimedia;

use Zend\Form\Form;
use Zend\Form\Element;


class MultimediaForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	parent::setAttribute('method', 'post');
	parent::setAttribute('action ', 'usuario');
	
    $this->add(array(
        'name' => 'url',
        'attributes' => array(
            'type'  => 'text',
            'placeholder'  => 'Identificador',
        ),
        'options' => array(
            'label' => 'Identificador del video en YouTube:',
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