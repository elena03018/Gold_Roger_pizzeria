<?php
namespace App\Service;

use App\Entity\Setting;
use App\Repository\SettingRepository;

    class SettingService
    {

        public Setting $setting;

        public function __construct(
            public SettingRepository $settingRepository
        )
        {
            $settings = $settingRepository->findAll();
            $setting = $settings[0];
            $this->setting = $setting;
        }

        // public function getSetting(): Setting
        // {
        //     $settings = $this->settingRepository->findAll();

        //     $setting = $settings[0];

        //     return $setting;
        // }
    }