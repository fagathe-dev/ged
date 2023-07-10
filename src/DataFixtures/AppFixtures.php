<?php

namespace App\DataFixtures;

use Cocur\Slugify\Slugify;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Folder;
use App\Entity\FileType;
use App\Entity\FolderUser;
use App\Utils\Data\FileTypeData;
use App\Utils\Enum\Colors;
use App\Utils\FakerTrait;
use App\Utils\ServiceTrait;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    use ServiceTrait;
    use FakerTrait;

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $slugify = new Slugify;
        $listUser = [];

        foreach (FileTypeData::getFileTypes() as $key => $f) {
            $fileType = new FileType;

            $fileType->setName($f['name'])
                ->setCreatedAt($this->now())
                ->setColor($f['color'])
                ->setIcon($f['icon'])
                ->setExtensions($f['extensions'])
            ;

            $manager->persist($fileType);
        }

        for ($i = 0; $i < random_int(50, 100); $i++) {
            $user = new User;

            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setRoles(['ROLE_USER'])
                ->setConfirm(true)
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setUsername($faker->userName())
                ->setEmail($faker->email())
                ->setCreatedAt($this->now())
            ;

            $manager->persist($user);

            array_push($listUser, $user);
        }

        for ($i = 0; $i < random_int(50, 200); $i++) {
            $folder = new Folder;
            $folder->setName($faker->words(random_int(2, 4), true))
                ->setSlug($slugify->slugify($folder->getName()))
                ->setCreatedAt($this->now())
            ;

            $users = $this->randomElements($listUser, random_int(1, 5));

            foreach ($users as $k => $user) {
                $folderUser = new FolderUser;

                $folderUser
                    ->setColor($this->randomElement(Colors::colors()))
                    ->setUser($user)
                    ->setRole($k === 0 ? FolderUser::ROLE_ADMIN : FolderUser::ROLE_USER)
                ;

                $folder->addUser($folderUser);
            }

            $manager->persist($folder);
        }

        $manager->flush();
    }
}