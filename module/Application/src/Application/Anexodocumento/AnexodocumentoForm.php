<?php


namespace Application\Anexodocumento;

use Zend\Form\Form;
use Zend\Form\Element;



class AnexodocumentoForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields



    }
}

