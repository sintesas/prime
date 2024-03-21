<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class ConsulproyectosForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
        $this->add(array(
            'name' => 'id_convocatoria',
            'attributes' => array(
                'type' => 'number',
		'placeholder'=>'Ingrese el ID de la convocatoria',
            ),
            'options' => array(
                'label' => 'ID de la convocatoria:',
            ),
        ));

        $this->add(array(
            'name' => 'id_aplicar',
            'attributes' => array(
                'type' => 'number',
		'placeholder'=>'Ingrese el ID de la propuesta',
            ),
            'options' => array(
                'label' => 'ID de la propuesta:',
            ),
        ));

        $this->add(array(
            'name' => 'codigo_proy',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el código del proyecto',
            ),
            'options' => array(
                'label' => 'Código del proyecto:',
            ),
        ));

        $this->add(array(
            'name' => 'nombre_proy',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el nombre de la propuesta',
            ),
            'options' => array(
                'label' => 'Nombre de la propuesta:',
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

