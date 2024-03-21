<?php


namespace Application\Reportes;

use Zend\Form\Form;
use Zend\Form\Element;



class ReportesForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'ContraseñaActual',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Contraseña Actual:',
            ),
        ));
         
        $this->add(array(
            'name' => 'ContraseñaNueva',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Nueva Contraseña:',
            ),
        ));

        $this->add(array(
            'name' => 'ContraseñaNuevaR',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Repetir Nueva Contraseña:',
            ),
        ));
		
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'value'=>'Cambiar Contraseña',
		  ),
	   ));
	   


    }
}

