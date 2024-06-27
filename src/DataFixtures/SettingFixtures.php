<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SettingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $setting =  $this->createSetting();

        $manager->persist($setting);

        $manager->flush();
    }

    private function createSetting(): Setting
    {
        $setting = new Setting ();

        $setting->setEmail("goldrogerpizzeria@gmail.com");
        $setting->setPhone("06 01 01 01 01 078");
        $setting->setAddress("via Cavour 1, 09016 Iglesias, Italie");
        $setting->setInstagram("https://www.instagram.com/goldrogerpizzeria_/");
        $setting->setUser(null);
        $setting->setCreatedAt(new DateTimeImmutable());
        $setting->setUpdatedAt(new DateTimeImmutable());

        return $setting;
    }
}
