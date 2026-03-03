<?php

namespace App\Traits;

use App\Enums\BannerType;
use App\Enums\ConfigurationValueType;
use App\Enums\CronRepeatType;
use App\Enums\Gender;
use App\Enums\IssueStatus;
use App\Enums\IssueType;
use App\Enums\PersonType;
use App\Models\Category;
use App\Models\Permission;
use App\Models\Province;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkType;

trait SelectOption
{
    public function personType()
    {
        $selectOptions = PersonType::values();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => __('common.' . $selectOption),
                'value' => $selectOption
            ];
        }
        return $selections;
    }

    public function yesNo()
    {
        $selections = [[
            'id' => 1,
            'label' => __('common.yes'),
            'value' => '1'
        ], [
            'id' => 2,
            'label' => __('common.no'),
            'value' => '0'
        ]];
        return $selections;
    }

    public function onOff()
    {
        $selections = [[
            'id' => 1,
            'label' => __('common.on'),
            'value' => '1'
        ], [
            'id' => 2,
            'label' => __('common.off'),
            'value' => '0'
        ]];
        return $selections;
    }

    public function publicStatus()
    {
        $selections = [[
            'id' => 1,
            'label' => __('common.public'),
            'value' => '1'
        ], [
            'id' => 2,
            'label' => __('common.private'),
            'value' => '0'
        ]];
        return $selections;
    }

    public function gender()
    {
        $selectOptions = Gender::values();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => __('common.' . $selectOption),
                'value' => $selectOption
            ];
        }
        return $selections;
    }

    public function role()
    {
        $selectOptions = Role::query()->get();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => $selectOption->name,
                'value' => $selectOption->id
            ];
        }
        return $selections;
    }

    public function permission()
    {
        $selectOptions = Permission::query()->get();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => __('common.permission-' . $selectOption->slug),
                'value' => $selectOption->id
            ];
        }
        return $selections;
    }

    public function bannerType()
    {
        $selectOptions = BannerType::values();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => __('banner.type-' . $selectOption),
                'value' => $selectOption
            ];
        }
        return $selections;
    }

    public function issueStatus()
    {
        $selectOptions = IssueStatus::values();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => __('issue.status-' . $selectOption),
                'value' => $selectOption
            ];
        }
        return $selections;
    }

    public function issueType()
    {
        $selectOptions = IssueType::values();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => __('issue.type-' . $selectOption),
                'value' => $selectOption
            ];
        }
        return $selections;
    }

    public function cronRepeatType()
    {
        $selectOptions = CronRepeatType::values();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => __('common.cron_repeat_type-' . $selectOption),
                'value' => $selectOption
            ];
        }
        return $selections;
    }

    public function weekDay()
    {
        $selectOptions = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => __('common.weekday-' . strtolower($selectOption)),
                'value' => strtolower($selectOption)
            ];
        }
        return $selections;
    }

    public function date()
    {
        $selectOptions = range(1, 25);
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => $selectOption,
                'value' => $selectOption
            ];
        }
        return $selections;
    }

    public function configurationValueType()
    {
        $selectOptions = ConfigurationValueType::values();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => __('common.value-type-' . $selectOption),
                'value' => $selectOption
            ];
        }
        return $selections;
    }

    public function province()
    {
        $selectOptions = Province::query()->get();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => $selectOption->name_th,
                'value' => $selectOption->id
            ];
        }
        return $selections;
    }

    public function workType()
    {
        $selectOptions = WorkType::query()->get();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => $selectOption->title,
                'value' => $selectOption->id
            ];
        }
        return $selections;
    }

    public function user()
    {
        $selectOptions = User::query()->get();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => $selectOption->email,
                'value' => $selectOption->id
            ];
        }
        return $selections;
    }

    public function category()
    {
        $selectOptions = Category::query()->get();
        $selections = [];
        foreach ($selectOptions as $key => $selectOption) {
            $selections[] = [
                'id' => $key,
                'label' => $selectOption->name,
                'value' => $selectOption->id
            ];
        }
        return $selections;
    }
}
