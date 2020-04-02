<?php

namespace App\DataFixtures;

use App\Domain\Entity\Trick;
use App\Domain\Entity\TrickImage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;

class ImageTrickFixture extends BaseFixture implements DependentFixtureInterface
{
    /**
     * @var string
     */
    private $uploadPath;

    /**
     * @var string
     */
    private $trickpicturePath;

    private static $imageFileNames = [
        'snowtrick_image_one',
        'snowtrick_image_two',
        'snowtrick_image_three',
    ];

    /**
     * ImageTrickFixture constructor.
     * @param string $uploadPath
     * @param string $trickpicturePath
     */public function __construct(string $uploadPath, string $trickpicturePath)
    {
        $this->uploadPath = $uploadPath;
        $this->trickpicturePath = $trickpicturePath;
    }

    public function loadData(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $random = rand(0, 2);
            $imageFileName = self::$imageFileNames[$random];
            $generateFileName = $imageFileName . '-' . uniqid() . '.jpg';
            //$path = $this->faker->image($dir = '/', $width = 640, $height = 480);

            $alt = $this->faker->sentence;
            $bool = true;
            $trickImage = new TrickImage($generateFileName, $bool, $alt);

            $filesystem = new Filesystem();
            $filesystem->copy(
                $this->trickpicturePath . $imageFileName . '.jpg',
                $this->uploadPath . '/trick_picture/' . $generateFileName
            );

            $manager->persist($trickImage);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference(TrickImage::class . '_' . $i, $trickImage);
            $trickImage->setTrick($this->getReference(Trick::class.'_'.$i));
        }

        //$filesystem->copy('hello',$this->trickPicturesDir . $path);
        //$trickImage->setFirstImage($this->faker->image($dir = '/images', $width = 640, $height = 480));

        $manager->flush();
    }

    public function getDependencies()
    {
        return [TrickFixtures::class];
    }
}
