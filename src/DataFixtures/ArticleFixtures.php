<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Article::class,17, function (Article $article, $count){

        $article->setTitle('Trick-' . rand(100, 999))
            ->setSlug('link-for-trick-' . $count)
            ->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer sit amet purus nec 
            diam feugiat consectetur a accumsan orci. Cras tincidunt ex ipsum, vitae tincidunt mi sollicitudin sit amet.
             Duis fermentum elit vitae metus malesuada vehicula. Mauris interdum, neque sit amet posuere ultricies,
              metus augue pellentesque eros, et eleifend nunc justo sed nisl. Quisque tempus venenatis eros, faucibus 
              ullamcorper sapien vehicula id. Donec ac mollis sem. Morbi tempus semper metus ut posuere. Aliquam mi 
              nisl, ultrices a augue in, ultricies fringilla augue. Vestibulum ornare mollis risus, ultricies blandit 
              tellus porta vitae.');

        // publish most articles
        if (rand(1, 10) > 2) {

            $article->setPublishedAt(new \DateTime(sprintf('-%d day', rand(1, 100))));
        }

        $article->setAuthor('Mike Ferengi')
            ->setImageFilename('snow4.jpg');

    });
        $manager->flush();
    }
}
