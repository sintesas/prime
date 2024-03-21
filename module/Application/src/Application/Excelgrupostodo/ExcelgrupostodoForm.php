<?php
	namespace Application\Excelgrupostodo;

	use Zend\Form\Form;
	use Zend\Form\Element;

	class ExcelgrupostodoForm extends Form 
	{
	    function __construct()
	    {
	       	parent::__construct( $name = null);
		   	parent::setAttribute('method', 'post');
		   	parent::setAttribute('action ', 'usuario');

		   	$this->add(array(
	            'name' => 'codigo',
	            'type' => 'Zend\Form\Element\Select',
	            'attributes' => array(
	                'id' => 'codigo'
	            ),
	            'options' => array(           
	                'label' => 'Código del grupo:'
	            )
	        ));

	        $this->add(array(
	            'name' => 'ano',
	            'attributes' => array(
	                 'type'  => 'number',
	                 'maxlength' => 20
	            ),
	            'options' => array(
	                'label' => 'Año de creación',
	            )
	        ));

	        $this->add(array(
	            'name' => 'nombre',
	            'type' => 'Zend\Form\Element\Select',
	            'attributes' => array(
	                'id' => 'nombre'
	            ),
	            'options' => array(           
	                'label' => 'Nombre del grupo:'
	            )
	        ));

	        $this->add(array(
	            'name' => 'lider',
	            'type' => 'Zend\Form\Element\Select',
	            'attributes' => array(
	                'id' => 'lider'
	            ),
	            'options' => array(           
	                'label' => 'Lider:'
	            )
	        ));

	        $this->add(array(
	            'name' => 'clasificacion',
	            'type' => 'Zend\Form\Element\Select',
	            'attributes' => array(
	                'id' => 'clasificacion'
	            ),
	            'options' => array(           
	                'label' => 'Clasificación:'
	            )
	        ));

	       	$this->add(array(
	            'name' => 'estado',
	            'type' => 'Zend\Form\Element\Select',
	            'attributes' => array(
	                'id' => 'estado'
	            ),
	            'options' => array(           
	                'label' => 'Estado del grupo:',
	                'value_options' => array(
	                     '' => '',
	                     'A' => 'Activo',
	                     'I' => 'Inactivo'
                     ),
	            )
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