<?php
namespace Sohoa\Framework\Form\Theme {

    class Bootstrap extends Generic implements ITheme
    {
        protected $_sizeLabel   = 'col-sm-2';
        protected $_sizeControl = 'col-sm-5';

        public function form(\Sohoa\Framework\Form\Form $form)
        {
            $check = new \Sohoa\Framework\Form\Validate\Check($form);
            if ($check->isValid([], true) === false && $form->getCheckStatus() === true) {
                $this->setErrors($check->getErrors());
            }

            $this->setForm($form);
            $out = '<form class="form-horizontal" role="form" '.$form->getAttributeAsString().'>';
            foreach ($form->getChilds() as $child) {
                $out .= $this->item($child);
            }

            $out .= '</form>';

            return $out;
        }

        public function item($item)
        {
            $class = '';
            if (is_object($item)) {
                $class = get_class($item);
            }

            if (is_string($item)) {
                $class = 'string';
            }

            switch ($class) {
                case 'Sohoa\Framework\Form\Textarea':
                    return $this->textarea($item);
                    break;
                case 'Sohoa\Framework\Form\Checkbox':
                    return $this->check($item);
                    break;
                case 'Sohoa\Framework\Form\Radio':
                    return $this->radio($item);
                    break;
                case 'Sohoa\Framework\Form\Select':
                    return $this->select($item);
                    break;
                case 'Sohoa\Framework\Form\Submit':
                    return $this->submit($item);
                    break;
                case 'Sohoa\Framework\Form\Hidden':
                    return $this->hidden($item);
                    break;
                case 'Sohoa\Framework\Form\Input':
                    return $this->input($item);
                    break;
                case 'Text':
                    return $this->text($item);
                    break;
                default:
                    return $item;
                    break;
            }
        }

        public function input($item)
        {

            $name   = $item->getAttribute('name');
            $value  = $this->getForm()->getData($name);

            if ($value !== null) {
                $item->setAttribute('value', $value);
            }

            $errorLabel = '';
            if (($error = $this->getError($name)) !== null) {
                foreach ($error as $value) {
                    $errorLabel .= '<span class="help-block">'.$value['message'].'</span>';
                }
            }

            return '<div class="form-group '.(($this->hasError($name)) ? 'has-error' : '').'">
            <label for="'.$item->getId().'" class="'.$this->_sizeLabel.' control-label">'.$item->getLabel().'</label>
            <div class="'.$this->_sizeControl.'"><'.$item->getName().' '.$item->getAttributeAsString().' class="form-control" />'.$errorLabel.'</div>
            </div>';
        }

        public function hidden($item)
        {
            return '<div class="form-group"><label for="'.$item->getId().'" class="'.$this->_sizeLabel.' control-label">'.$item->getLabel().'</label><div class="'.$this->_sizeControl.'"><'.$item->getName().' '.$item->getAttributeAsString().' class="form-control" /></div></div>';
        }

        public function submit($item)
        {
            return '<div class="form-group">
            <label for="'.$item->getId().'" class="'.$this->_sizeLabel.' control-label">'.$item->getLabel().'</label>
            <div class="'.$this->_sizeControl.'"><'.$item->getName().' '.$item->getAttributeAsString().' /></div>
            </div>';
        }

        public function text($item)
        {
            $label = $item['label'];
            $value = $item['value'];

            return '<div class="form-group">
            <label class="'.$this->_sizeLabel.' control-label">'.$label.'</label>
            <div class="'.$this->_sizeControl.'"><p class="form-control-static">'.$value.'</p>
            </div></div>';

        }

        public function textarea($item)
        {
            $item->defaultAttribute('class', 'form-control');
            $value = $item->extractAttribute('value');
            $name  = $item->getAttribute('name');
            $data  = $this->getForm()->getData($name);

            if ($data !== null) {
                $value = $data;
            }

            $errorLabel = '';
            if (($errors = $this->getError($name)) !== null) {
                foreach ($errors as $error) {
                    $errorLabel .= '<span class="help-block">'.$error['message'].'</span>';
                }
            }

            return '<div class="form-group '.(($this->hasError($name)) ? 'has-error' : '').'">
            <label for="'.$item->getId().'" class="'.$this->_sizeLabel.' control-label">'.$item->getLabel().'</label>
            <div class="'.$this->_sizeControl.'"><'.$item->getName().' '.$item->getAttributeAsString().'>'.$value.'
            </'.$item->getName().'>'.$errorLabel.'</div>
            </div>';

        }

        public function select($item)
        {

            $item->defaultAttribute('class', 'form-control');
            $selectValue = $this->getForm()->getData($item->getAttribute('name'));
            $name = $item->getAttribute('name');
            $errorLabel = '';
            if (($errors = $this->getError($name)) !== null) {
                foreach ($errors as $error) {
                    $errorLabel .= '<span class="help-block">'.$error['message'].'</span>';
                }
            }

            $select = '<'.$item->getName().' '.$item->getAttributeAsString().'>';
            foreach ($item->getOptions() as $value) {
                if ($value[1] !== null) {
                    $select .= '<option value="'.$value[1].'" '.
                    (($selectValue !== null and $selectValue === $value[1]) ? 'selected' : '')
                    .'>'.$value[0].'</option>';
                } else {
                    $select .= '<option'.
                    (($selectValue !== null and $selectValue === $value[0]) ? ' selected="selected"' : '')
                    .'>'.$value[0].'</option>';
                }
            }

            $select .= '</'.$item->getName().'>'.$errorLabel;

            return '<div class="form-group  '.(($this->hasError($name)) ? 'has-error' : '').'">
            <label for="'.$item->getId().'" class="'.$this->_sizeLabel.' control-label">'.$item->getLabel().'</label>
            <div class="'.$this->_sizeControl.'">'.$select.'</div>
            </div>';
        }

        public function check($item)
        {

            $select = '';
            foreach ($item->getOptions() as $value) {
                    $select .= '<label class="checkbox-inline"><'.$item->getName().' '.$item->getAttributeAsString().' name="'.$value[2].'" value="'.$value[1].'" '.
                    (($this->getForm()->getData($value[2]) !== null and $this->getForm()->getData($value[2]) === $value[1]) ? 'checked' : '')
                     .'/>'.$value[0].'</label>';
            }

            return '<div class="form-group">
            <label for="'.$item->getId().'" class="'.$this->_sizeLabel.' control-label">'.$item->getLabel().'</label>
            <div class="'.$this->_sizeControl.'">'.$select.'</div>
            </div>';
        }

        public function radio($item)
        {
            $name = $item->extractAttribute('name');
            $selectValue = $this->getForm()->getData($name);

            $errorLabel = '';
            if (($errors = $this->getError($name)) !== null) {
                foreach ($errors as $error) {
                    $errorLabel .= '<span class="help-block">'.$error['message'].'</span>';
                }
            }

            $select = '';
            foreach ($item->getOptions() as $value) {
                    $select .= '<label class="radio-inline"><'.$item->getName().' '.$item->getAttributeAsString().' name="'.$name.'" value="'.$value[1].'" '.
                    (($selectValue !== null and $selectValue === $value[1]) ? 'checked' : '')
                    .' '.$this->attributeAsString($value[2]).'/>'.$value[0].'</label>';
            }

            return '<div class="form-group '.(($this->hasError($name)) ? 'has-error' : '').'">
            <label for="'.$item->getId().'" class="'.$this->_sizeLabel.' control-label">'.$item->getLabel().'</label>
            <div class="'.$this->_sizeControl.'">'.$select.$errorLabel.'</div>
            </div>';
        }

        protected function attributeAsString(array $array)
        {
            $out = array();

            foreach ($array as $name => $value) {
                if ($value !== null) {
                    $out[] = sprintf('%s="%s"', $name, $value);
                }
            }

            return trim(implode(' ', $out));
        }
    }
}
