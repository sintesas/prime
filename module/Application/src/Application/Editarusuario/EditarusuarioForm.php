<?php


namespace Application\Editarusuario;

use Zend\Form\Form;
use Zend\Form\Element;

class EditarusuarioForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione la imagen para su perfil')
             ->setAttribute('id', 'image-file');
        $this->add($file);

        $this->add(array(
            'name' => 'cargo_actual',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Cargo actual-Categoría:'
            ),
            'attributes' => array(
                'id' => 'cargo_actual',
            )
        ));

        $this->add(array(
	        'type' => 'Zend\Form\Element\Select',
	        'name' => 'evaluador',
	        'options' => array(
	            'label' => 'Evaluador: ',
	            'value_options' => array(
	                'No' => 'No',
	                'Si' => 'Si'
	            )
	        ),
	        'attributes' => array(
	            'value' => 'No',
	            'id' => 'evaluador',
	            'onchange' => 'myFunction4();',
	        )
	    )); // set selected to '1'

	    $this->add(array(
	        'type' => 'Zend\Form\Element\Select',
	        'name' => 'tipo_evaluador',
	        'options' => array(
	            'label' => 'Tipo evaluador: ',
	            'value_options' => array(
	                '' => '',
	                'Interno' => 'Interno',
	                'Externo' => 'Externo'
	            )
	        ),
	        'attributes' => array(
	            'value' => '',
	            'id' => 'tipo_evaluador'
	        )
	    )); // set selected to '1'

	    $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'institucion',
            'options' => array(
                'label' => 'Institución: ',
            ),
            'attributes' => array(
                'value' => '',
                'id' => 'institucion'
            )
        )); // set selected to '1'
	   
	   //create field				
	   $this->add(array(
	      'name'=>'submit',

		  'attributes'=>array(
		    'type'=>'submit',
		    'required'=>'required',
			'class'=>'btn1',
			'value'=>'Actualizar',
		  ),
	   ));
	   


    }
}

