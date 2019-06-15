<?php


namespace App\Tests\Form;


use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypetest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'content' => 'my comment test'
        ];

        $objectToCompare = new Comment();
        $form = $this->factory->create(CommentType::class, $objectToCompare);
        $object = new Comment();
        $object->setContent('the second test');
        $form->submit($formData);
        $this->assertTrue($form->isValid());

        $this->assertInstanceOf(Comment::class, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}