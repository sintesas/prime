<?php
namespace Application\Convocatoria;
use Zend\Form\Form;
use Zend\Form\Element;

class SesionesperiodicasformacionForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
   
	    //create field	
        $this->add(array(
            'name' => 'id_tipo',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_tipo',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Tipo de espacio:'
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

