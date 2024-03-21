<?php


namespace Application\Index;

use Zend\Form\Form;
use Zend\Form\Element;



class IndexForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'autenticar');
	   
	   //create fields
	  
	   


    }
}

