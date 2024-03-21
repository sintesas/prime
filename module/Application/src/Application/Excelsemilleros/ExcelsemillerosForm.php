<?php
	namespace Application\Excelsemilleros;

	use Zend\Form\Form;
	use Zend\Form\Element;

	class ExcelsemillerosForm extends Form 
	{
	    function __construct()
	    {
	       parent::__construct( $name = null);
		   parent::setAttribute('method', 'post');
		   parent::setAttribute('action ', 'usuario');
	    }
}