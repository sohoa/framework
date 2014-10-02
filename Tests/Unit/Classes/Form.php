<?php

namespace Sohoa\Framework\Form\Tests\Unit;

require_once __DIR__ . '/../Runner.php';

class Form extends \atoum\test
{
    // TODO : Add optgroup on select
    // TODO : Add Button
    // TODO : Add File
    // TODO : <Guile> comment tu définis la méthod du form, et est-il possible que Form ait un moyen implicite de récupérer ses data par la bonne méthode ?
    // TODO : type=numeric, required=required => dans le need()
    public function testRadio()
    {
        $fwk      = new \Sohoa\Framework\Framework();
        $form     = $fwk->form('foo');
        $form
            ->action('/user/')
            ->method('post');

        $form[]   = (new \Sohoa\Framework\Form\Radio())
                    ->name('foo')
                    ->option('doo', 'bar', ['id' => 'hello'])
                    ->option('doo', 'bar');

        $this->string($form->render())->length->isIdenticalTo(410);
    }

    public function testLoad()
    {

        $fwk      = new \Sohoa\Framework\Framework();
        $form     = $fwk->form('foo');
        $theme    = $form->getTheme();
        $validate = $form->getValidator();
        $this
            ->object($form)
                ->isInstanceOf('\Sohoa\Framework\Form\Form')
            ->object($theme)
                ->isInstanceOf('\Sohoa\Framework\Form\Theme\Bootstrap')
            ->object($validate)
                ->isInstanceOf('\Sohoa\Framework\Form\Validate\Check')
            ;

    }

    public function testInitForm()
    {
        $form = $this->_form();
        $this
            ->array($form->getChilds())
            ->hasSize(5);

        $this
            ->object($form['login'])
                ->isInstanceOf('\Sohoa\Framework\Form\Input')
                ->array($form['login']->getNeed())
                    ->hasSize(1)
                ->array($form['password']->getNeed())
                    ->hasSize(2)
            ;

    }

    public function testDataValidation()
    {
        $form       = $this->_form();
        $validate   = $form->getValidator();
        $data       = [
            'login'     => 52,
            'password'  => 'aaaaaaaaaaaa',
            'rpassword' => 'aaaaaaaaaaaa',
            'email'     => 'aaaaaaaaaaaa@bar.fr',
            'name'      => 'aaaaaaaaaaaa@bar.fr'
        ];
        $form->fill($data);
        $this
            ->boolean($validate->isValid())
            ->isTrue();

        $data       = [
            'login'     => 80,
            'password'  => 'aaaaaaaaaaaa',
            'rpassword' => 'aaaaaaaaaaaa',
            'email'     => 'aaaaaaaaaaaa@bar.fr',
            'name'      => 'aaaaaaaaaaaa@bar.fr'
        ];
        $form->fill($data);
        $this
            ->boolean($validate->isValid())
                ->isFalse()
            ->array($validate->getErrors())
                ->hasSize(1)
                ->string['login'][0]['object']->isIdenticalTo('Sohoa\Framework\Form\Validate\Praspel')
                ->string['login'][0]['message']->isIdenticalTo('The given value 80 do not match boundinteger(0, 52)')
                ->string['login'][0]['args']['realdom']->isIdenticalTo('boundinteger(0, 52)')
                ->integer['login'][0]['args']['value']->isIdenticalTo(80);

        $data       = [
            'login'     => 5,
            'password'  => 'aaaaa',
            'rpassword' => 'aaaaa',
            'email'     => 'aaa@bar.fr',
            'name'      => 'aaa@bar.fr'
        ];
        $this
            ->boolean($validate->isValid($data))
            ->isTrue();

        $data       = [
            'login'     => 5,
            'password'  => 'aaa',
            'rpassword' => 'aaaaa',
            'email'     => 'aaa@bar.fr',
            'name'      => 'aaa@bar.fr'
        ];
        $this
            ->boolean($validate->isValid($data))
            ->isFalse()
            ->array($validate->getErrors())
                ->hasSize(1)
                ->string['password'][0]['message']->isIdenticalTo('The given value is too long, need >= 5 char');

        $this
            ->boolean($validate->isValid(['aaaa' => 'a']))
            ->isFalse()
            ->array($validate->getErrors())
                ->hasSize(5)
                ->string['login'][0]['message']->isIdenticalTo('The given value NULL do not match boundinteger(0, 52)')

                ->string['password'][0]['message']->isIdenticalTo('This field is required')

                ->string['password'][1]['message']->isIdenticalTo('The given value is too long, need >= 5 char')
                ->string['password'][1]['object']->isIdenticalTo('Sohoa\Framework\Form\Validate\Length')

                ->integer['password'][1]['args']['length']->isIdenticalTo(0)
                ->string['password'][1]['args']['max']->isIdenticalTo('')
                ->string['password'][1]['args']['min']->isIdenticalTo('5')

                ->string['rpassword'][0]['message']->isIdenticalTo('This field is required')
                ->string['rpassword'][1]['message']->isIdenticalTo('The given value is too long, need >= 5 char')

                ->string['email'][0]['message']->isIdenticalTo('This field is required')
                ->string['email'][1]['message']->isIdenticalTo('The given value is too long, need >= 5 char')
                ->string['email'][2]['message']->isIdenticalTo('The given value is not an valid email address')

                ->string['name'][0]['message']->isIdenticalTo('This field is required')
                ->string['name'][1]['message']->isIdenticalTo('The given value is too long, need >= 5 char')
        ;

    }

    protected function _form()
    {

        $fwk      = new \Sohoa\Framework\Framework();
        $form     = $fwk->form('foo');
        $form[]   = (new \Sohoa\Framework\Form\Input())
                    ->id('login')
                    ->label('Login')
                    ->placeholder('Your Login')
                    ->praspel('boundinteger(0, 52)');

        $form[]   = (new \Sohoa\Framework\Form\Input())
                    ->id('password')
                    ->type('password')
                    ->label('Password')
                    ->placeholder('Your password')
                    ->need('required|length:5:');

        $form[]   = (new \Sohoa\Framework\Form\Input())
                    ->id('rpassword')
                    ->type('password')
                    ->label('Confirm password')
                    ->placeholder('Confirm Your password')
                    ->need('required|length:5:');

        $form[]   = (new \Sohoa\Framework\Form\Input())
                    ->id('email')
                    ->type('email')
                    ->label('E-Mail')
                    ->placeholder('Your email we don\'t send spam !')
                    ->need('required|length:5:|email');

        $form[]   = (new \Sohoa\Framework\Form\Input())
                    ->id('name')
                    ->label('Name')
                    ->placeholder('Your name')
                    ->need('required|length:5:');

        return $form;
    }

}
