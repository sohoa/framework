<?php
namespace Sohoa\Framework\Form {
    class Submit extends Element
    {
        protected $_name = 'input';
        protected $_attributes = [
            'type'  => 'submit',
            'value' => 'Send' ,
            'class' => 'btn btn-primary'
        ];
    }
}
