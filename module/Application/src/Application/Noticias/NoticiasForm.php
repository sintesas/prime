<?php


namespace Application\Noticias;

use Zend\Form\Form;
use Zend\Form\Element;


class NoticiasForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'titulo',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'=>'Ingrese el título para filtrar',
	
            ),
            'options' => array(
				'label' => 'Filtro Título :',
            ),
        ));
		
        $this->add(array(
            'name' => 'noticia',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'=>'Ingrese el nombre de la noticia',

            ),
            'options' => array(
				'label' => 'Filtro Noticia :',
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

