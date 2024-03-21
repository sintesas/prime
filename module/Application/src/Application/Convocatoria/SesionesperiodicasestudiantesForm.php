<?php
namespace Application\Convocatoria;
use Zend\Form\Form;
use Zend\Form\Element;

class SesionesperiodicasestudiantesForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
   
	    //create field	
        $this->add(array(
			'name' => 'sesion',
			'attributes' => array(
				'type' => 'number',
				'required'=>'required'
			),
			'options' => array(
			'label' => 'Sesión No.:'
			)
        ));

        $this->add(array(
			'name' => 'fecha',
			'attributes' => array(
				'type' => 'Date',
				'required'=>'required',
				'placeholder'=>'YYYY-MM-DD',
			),
			'options' => array(
			'label' => 'Fecha inicio:'
			)
        ));

        $this->add(array(
			'name' => 'fecha_fin',
			'attributes' => array(
				'type' => 'Date',
				'required'=>'required',
				'placeholder'=>'YYYY-MM-DD',
			),
			'options' => array(
			'label' => 'Fecha fin:'
			)
        ));

        $this->add(array(
            'name' => 'id_rol',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_rol'
            ),
            'options' => array(
                'label' => 'Rol responsable:'
            )
        ));
			
	    $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'class'=>'btn',
			 'value'=>'Agregar',
		  ),
	    ));
	   


    }
}

