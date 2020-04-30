<?php

namespace App\DataFixtures;

use App\Domain\Entity\GroupTrick;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixtures extends BaseFixture implements DependentFixtureInterface
{
    private static $trickTitles = [
        'Ollie',
        'Nollie',
        'Switch Ollie',
        'Fakie Ollie',
        'Shifty',
        'Air-to-fakie',
        'Grabs - Beef Carpaccio',
        'Beef - Curtains',
        'Bloody Dracula',
        'Crail',
        'Drunk Driver',
        'Gorilla',
        'Japan Air',
    ];

    private static $trickContents = [
        'Une rotation peut être frontside ou backside : 
        une rotation frontside correspond à une rotation orientée vers la carre backside. 
        Cela peut paraître incohérent mais l\'origine étant que dans un halfpipe ou une rampe de skateboard, 
        une rotation frontside se déclenche naturellement depuis une position frontside (i.e. l\'appui se fait sur la carre frontside), 
        et vice-versa. Ainsi pour un rider qui a une position regular (pied gauche devant), une rotation frontside se fait dans le sens inverse des aiguilles d\'une montre.',
        'Une rotation peut être agrémentée d\'un grab, ce qui rend le saut plus esthétique mais aussi plus difficile car la position tweakée a tendance à déséquilibrer le rideur et désaxer la rotation. De plus, le sens de la rotation a tendance à favoriser un sens de grab plutôt qu\'un autre. Les rotations de plus de trois tours existent mais sont plus rares, d\'abord parce que les modules assez gros pour lancer un tel saut sont rares, et ensuite parce que la vitesse de rotation est tellement élevée qu\'un grab devient difficile, ce qui rend le saut considérablement moins esthétique.',
        'Une rotation désaxée est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier qui désaxe la rotation. 
        Il existe différents types de rotations désaxées (corkscrew ou cork, rodeo, misty, etc.) en fonction de la manière dont est lancé le buste. Certaines de ces rotations, bien qu\'initialement horizontales, font passer la tête en bas. Bien que certaines de ces rotations soient plus faciles à faire sur un certain nombre de tours (ou de demi-tours) que d\'autres, il est en théorie possible de d\'attérir n\'importe quelle rotation désaxée avec n\'importe quel nombre de tours, en jouant sur la quantité de désaxage afin de se retrouver à la position verticale au moment voulu. Il est également possible d\'agrémenter une rotation désaxée par un grab.',
        'Les tricks sont pour la plupart du temps, des rotations qui peuvent être de plusieurs types, combinées ou non avec un grab, et effectuées en position normal ou switch. La dénomination des figures ainsi créées répond à l\'usage suivant : d\'abord le mot « switch » si la figure est effectuée du côté non naturel ensuite le nom du type de désaxage de la rotation si la figure est une rotation désaxée puis le nom de la rotation elle-même, c’est-à-dire le nombre de degrés effectués si la figure est grabée, le nom du grab Par exemple, un « switch rodeo cinq tail grab » est un saut dans lequel le rider part de son côté non naturel, fait une rotation horizontale d\'un tour et demi avec un désaxage de type rodeo, et attrape l\'arrière de sa planche pendant la rotation.',
    ];

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Trick::class, 10, function (Trick $trick, $i) {
            $title = $trick->setTitle(self::$trickTitles[$i]);

            $trick->setSlug($title);
            $trick->setContent($this->faker->randomElement(self::$trickContents));
            $trick->setUpdatedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));

            $trick->setCreator($this->getRandomReference(User::class));

            $trick->setLastUser($this->getRandomReference(User::class));
            $trick->setGroupTrick($this->getRandomReference(GroupTrick::class));
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            GroupFixture::class,
        ];
    }
}
