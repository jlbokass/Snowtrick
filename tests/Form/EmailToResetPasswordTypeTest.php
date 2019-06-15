<?php


namespace App\Tests\Form;


use App\Entity\User;
use App\Form\EmailToResetPasswordType;
use Symfony\Component\Form\Test\TypeTestCase;

class EmailToResetPasswordTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $dataForm = [
            'email' => 'jdabok@me.com',
        ];

        $objectToCompare = new User();

        $form = $this->factory->create(EmailToResetPasswordType::class, $objectToCompare);

        $object = new User();
        $object
            ->setEmail('jdabok@me.com');

        $form->submit($dataForm);

        $this->assertTrue($form->isValid());

        $this->assertEquals($object->getUsername(), $objectToCompare->getUsername());


        $this->assertInstanceOf(User::class, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($dataForm) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}