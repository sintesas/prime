<?php
	namespace Application\Excelredes;

	use Zend\Form\Form;
	use Zend\Form\Element;

	class ExcelredesForm extends Form 
	{
	    function __construct()
	    {
	       parent::__construct( $name = null);
		   parent::setAttribute('method', 'post');
		   parent::setAttribute('action ', 'usuario');
	    }
}