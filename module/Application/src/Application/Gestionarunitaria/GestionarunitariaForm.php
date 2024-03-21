<?php


namespace Application\Gestionarunitaria;

use Zend\Form\Form;
use Zend\Form\Element;



class GestionarunitariaForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields  
        $this->add(array(
            'name' => 'observacion',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese la observación',
            ),
            'options' => array(
                'label' => 'Observaciones :',
            ),
        ));


        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')
             ->setAttribute('id', 'image-file');
        $this->add($file);
	   		
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_estado',
			'required'=>'required',
            'options' => array(
                'label' => 'Estado :',
                'value_options' => array(

                    '1' => 'Enviado',
                    '2' => 'En gestion',
					'3' => 'Tramitado'
                ),
            ),
            'attributes' => array(
                'value' => '0' //set selected to '1'
            )
        ));
			
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'value'=>'Actualizar',
		  ),
	   ));

    }
}

