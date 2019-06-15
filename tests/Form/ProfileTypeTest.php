<?php


namespace App\Tests\Form;


use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\Form\Test\TypeTestCase;

class ProfileTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $dataForm = [
            'email' => 'jdabok@me.com',
            'password'=> 'D123@123',
            'username' => 'john'
        ];

        $objectToCompare = new User();

        $form = $this->factory->create(ProfileType::class, $objectToCompare);

        $object = new User();
        $object
            ->setEmail('jdabok@me.com')
            ->setPassword('D123@123')
            ->setUsername('john');

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