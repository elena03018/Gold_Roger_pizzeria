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
        $setting->setPhone("+39 370 120 9376");
        $setting->setAddress("Via Cavour 16/18, 09016 Iglesias, Italie");
        $setting->setHours("Du lundi au dimanche: 18h00-23h00");
        $setting->setInstagram("https://www.instagram.com/goldrogerpizzeria_/");
        $setting->setFacebook("https://www.facebook.com/profile.php?id=61559835743248&locale=it_IT");
        $setting->setUser(null);
        $setting->setCreatedAt(new DateTimeImmutable());
        $setting->setUpdatedAt(new DateTimeImmutable());

        return $setting;
    }
}
