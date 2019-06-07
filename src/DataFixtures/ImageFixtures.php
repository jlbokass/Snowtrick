<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Image;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ImageFixtures extends BaseFixture implements DependentFixtureInterface
{
    private static $articleImages = [
        'snow1.jpg',
        'snow2.jpg',
        'snow3.jpg',
        'snow4.jpg',
        'snow5.jpg',
        'snow6.jpg',
        'snow7.jpg',
        'snow8.jpg',
        'snow9.jpg',
        'snow10.jpg',
        'snow11.jpg',
        'snow12.jpg',
        'snow13.jpg',
        'snow14.jpg',
        'snow15.jpg',
        'snow16.jpg',
        'snow17.jpg',
        'snow18.jpg',
        'snow19.jpg',
    ];

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Image::class, 17, function (Image $image) {
            $image->setImageFilename($this->faker->unique()->randomElement(self::$articleImages));
            $article = $this->getRandomReferences(Article::class, $this->faker->numberBetween(1, 17));

            foreach ($article as $article) {
                $image->setArticle($article);
            }
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ArticleFixtures::class,
        ];
    }
}
