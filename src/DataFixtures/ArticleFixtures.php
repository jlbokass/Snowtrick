<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixture
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
        'snow4.jpg',
        'snow5.jpg',
        'snow6.jpg',
        'snow7.jpg',
    ];

    private static $articleAuthors = [
      'Black Panther',
      'Thor the God',
      'Hulk the Monster',
      'Black Window',
    ];

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Article::class,17, function (Article $article, $count){

        $article->setTitle($this->faker->unique()->randomElement(self::$articleTitle))
            ->setSlug($this->faker->slug)
            ->setContent($this->faker->paragraph(7, true));

        // publish most articles
        if ($this->faker->boolean(70)) {

            $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
        }

        $article->setAuthor($this->faker->randomElement(self::$articleAuthors))
            ->setImageFilename($this->faker->randomElement(self::$articleImages));

    });
        $manager->flush();
    }
}
