<?php
	namespace Application\Excelsemillerostodo;

	use Zend\Form\Form;
	use Zend\Form\Element;

	class ExcelsemillerostodoForm extends Form 
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
	                'label' => 'Código semillero:'
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
	            'name' => 'unidad',
	            'type' => 'Zend\Form\Element\Select',
	            'attributes' => array(
	                'id' => 'unidad'
	            ),
	            'options' => array(           
	                'label' => 'Unidad acádemica:'
	            )
	        ));

	        $this->add(array(
	            'name' => 'dependencia',
	            'type' => 'Zend\Form\Element\Select',
	            'attributes' => array(
	                'id' => 'dependencia'
	            ),
	            'options' => array(           
	                'label' => 'Dependencia acádemica:'
	            )
	        ));

	        $this->add(array(
	            'name' => 'programa',
	            'type' => 'Zend\Form\Element\Select',
	            'attributes' => array(
	                'id' => 'programa'
	            ),
	            'options' => array(           
	                'label' => 'Programa acádemico:'
	            )
	        ));

	        $this->add(array(
	            'name' => 'nombre',
	            'type' => 'Zend\Form\Element\Select',
	            'attributes' => array(
	                'id' => 'nombre'
	            ),
	            'options' => array(           
	                'label' => 'Nombre del semillero:'
	            )
	        ));

	        $this->add(array(
	            'name' => 'coordinador_1',
	            'type' => 'Zend\Form\Element\Select',
	            'attributes' => array(
	                'id' => 'coordinador_1'
	            ),
	            'options' => array(           
	                'label' => 'Coordinador 1:'
	            )
	        ));

	        $this->add(array(
	            'name' => 'coordinador_2',
	            'type' => 'Zend\Form\Element\Select',
	            'attributes' => array(
	                'id' => 'coordinador_2'
	            ),
	            'options' => array(           
	                'label' => 'Coordinador 2:'
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