<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class IntegrantesForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   

	   
	   //create field	
	$this->add(array(
            'name' => 'usuario',
            'attributes' => array(
                'type'  => 'text',
		'placeholder'  => 'Ingrese para filtrar el usuario ',
            ),
            'options' => array(
                'label' => 'Filtro Usuario:',
            ),
        ));
		
		$this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text',
		'placeholder'  => 'Ingrese para filtrar el nombre ',
            ),
            'options' => array(
                'label' => 'Filtro Nombre:',
            ),
        ));
		
		$this->add(array(
            'name' => 'apellido',
            'attributes' => array(
                'type'  => 'text',
		'placeholder'  => 'Ingrese para filtrar el apellido ',
            ),
            'options' => array(
                'label' => 'Filtro Apellido:',
            ),
        ));

        $this->add(array(
            'name' => 'documento',
            'attributes' => array(
                'type'  => 'Number',
		'placeholder'  => 'Ingrese para filtrar el documento ',
            ),
            'options' => array(
                'label' => 'Filtro Documento:',
            ),
        ));

	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'class'=>'btn',
			 'value'=>'Buscar',
		  ),
	   ));
	   


    }
}

