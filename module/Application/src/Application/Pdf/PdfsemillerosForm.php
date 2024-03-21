<?php
namespace Application\Pdf;
use Zend\Form\Form;
use Zend\Form\Element;

class PdfsemillerosForm extends Form 
{
    function __construct()
    {
       parent::__construct();
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
    }
}