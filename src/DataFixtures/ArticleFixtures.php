<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixture implements DependentFixtureInterface
{
    private static $articleTitle = [
        'BackFlip',
        'The HeelClicker',
        'The Superman',
        'The Double Cancan',
        'Backside Triple Cork 1440',
        'Method Air',
        'Double Backflip One Foot',
        'Double Mc Twist 1260',
        'Double Backside Rodeo 1080',
        'Cork',
        'Revert',
        'The Switch',
        'Backside Air',
        'Crippler',
        'Handplant',
        'The 270',
        'Air to Fakie',
        'Backside Rodeo',
    ];

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

    private static $articleAuthors = [
      'Black Panther',
      'Thor the God',
      'Hulk the Monster',
      'Black Window',
    ];

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Article::class,17, function (Article $article, $count) use ($manager) {

        $article->setTitle($this->faker->unique()->randomElement(self::$articleTitle))
            ->setContent($this->faker->paragraph(10, true));

        // publish most articles
        if ($this->faker->boolean(70)) {

            $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
        }

        $article->setAuthor($this->faker->randomElement(self::$articleAuthors))
            ->setImageFilename($this->faker->unique()->randomElement(self::$articleImages));
        $article->setCategory($this->getRandomReference(Category::class));

    });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
