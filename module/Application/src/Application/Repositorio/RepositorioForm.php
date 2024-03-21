<?php


namespace Application\Repositorio;

use Zend\Form\Form;
use Zend\Form\Element;


class RepositorioForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'  => 'Ingrese el nombre del documento',
            ),
            'options' => array(
				'label' => 'Filtro nombre del documento:',
            ),
        ));

     $this->add(array(
        'name' => 'id_autor',
        'attributes' => array(
            'type'  => 'text',
            'id' => 'id_autor',
            'placeholder'=>'Filtro',
        ),
        'options' => array(
            'label' => 'Autor del documento:'
        ),
    ));
				
	    $this->add(array(
			'name'=>'submit',
			'attributes'=>array(
				'type'=>'submit',
				'class'=>'btn',
				'value'=>'Buscar',
		  ),
	   ));
    }
}

