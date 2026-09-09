<?php

namespace App\Livewire\SocialMediaLinks;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public $branches = [];

    public $branch_id = null;

    public $caption = '';

    public $link = '';

    public array $socialMediaListCaption = [
        'telegram',
        'whatsapp',
        'eeta',
        'instagram',
        'google map',

    ];

    public array $savedLinks = [];

    public function mount()
    {
        $panel = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->firstOrFail();

        $this->branches = $panel->branches()->get();

        $this->branch_id = $this->branches->first()?->id;

        $this->loadData();
    }

    /**
     * تغییر شعبه
     */
    public function updatedBranchId()
    {
        $this->caption = '';
        $this->link = '';

        $this->loadData();
    }

    /**
     * دریافت Option
     */
    private function getOption()
    {
        $panel = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->firstOrFail();

        return $panel->options()
            ->where('option_id', 4)
            ->first();
    }

    /**
     * خواندن اطلاعات
     */
    private function loadData()
    {
        if (!$this->branch_id) {
            $this->savedLinks = [];

            return;
        }

        $option = $this->getOption();

        if (!$option) {
            $this->savedLinks = [];

            return;
        }

        $data = $option->data;

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        $data = is_array($data) ? $data : [];

        $this->savedLinks = collect($data)
            ->filter(function ($item) {
                return isset($item['branch_id'])
                    && (int) $item['branch_id'] === (int) $this->branch_id;
            })
            ->values()
            ->toArray();
    }

    /**
     * ذخیره لینک
     */
    public function saveData()
    {
        $this->validate([
            'branch_id' => [
                'required',
                'exists:branches,id',
            ],

            'caption' => [
                'required',
                'in:telegram,whatsapp,eeta,instagram,google map',
            ],

            'link' => [
                'required',
                'url',
                'max:1000',
            ],
        ], [
            'branch_id.required' => 'انتخاب شعبه الزامی است.',
            'branch_id.exists' => 'شعبه انتخاب شده معتبر نیست.',

            'caption.required' => 'انتخاب شبکه اجتماعی الزامی است.',
            'caption.in' => 'شبکه اجتماعی انتخاب شده معتبر نیست.',

            'link.required' => 'وارد کردن لینک الزامی است.',
            'link.url' => 'لینک وارد شده معتبر نیست.',
            'link.max' => 'لینک نمی‌تواند بیشتر از ۱۰۰۰ کاراکتر باشد.',
        ]);

        $panel = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Option شماره 4
        |--------------------------------------------------------------------------
        */

        $option = $panel->options()
            ->where('option_id', 4)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | اگر Option وجود نداشت
        |--------------------------------------------------------------------------
        */

        if (!$option) {
            $option = $panel->options()->create([
                'option_id' => 4,
                'data' => json_encode([], JSON_UNESCAPED_UNICODE),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | دریافت اطلاعات قبلی
        |--------------------------------------------------------------------------
        */

        $data = $option->data;

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        $data = is_array($data) ? $data : [];

        /*
        |--------------------------------------------------------------------------
        | بررسی وجود لینک
        |--------------------------------------------------------------------------
        */

        $found = false;

        foreach ($data as $key => $item) {

            if (
                isset($item['branch_id'], $item['caption'])
                && (int) $item['branch_id'] === (int) $this->branch_id
                && $item['caption'] === $this->caption
            ) {
                $data[$key]['link'] = $this->link;

                $found = true;

                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ایجاد لینک جدید
        |--------------------------------------------------------------------------
        */

        if (!$found) {
            $data[] = [
                'branch_id' => (int) $this->branch_id,
                'caption' => $this->caption,
                'link' => $this->link,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | ذخیره
        |--------------------------------------------------------------------------
        */

        $option->data = json_encode(
            array_values($data),
            JSON_UNESCAPED_UNICODE
        );

        $option->save();

        /*
        |--------------------------------------------------------------------------
        | رفرش
        |--------------------------------------------------------------------------
        */

        $this->loadData();

        $this->caption = '';
        $this->link = '';

        session()->flash(
            'success',
            'لینک شبکه اجتماعی با موفقیت ذخیره شد.'
        );
    }

    /**
     * حذف لینک
     */
    public function deleteLink($caption)
    {
        if (!$this->branch_id) {
            return;
        }

        $option = $this->getOption();

        if (!$option) {
            return;
        }

        $data = $option->data;

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        $data = is_array($data) ? $data : [];

        $data = collect($data)
            ->reject(function ($item) use ($caption) {
                return isset($item['branch_id'], $item['caption'])
                    && (int) $item['branch_id'] === (int) $this->branch_id
                    && $item['caption'] === $caption;
            })
            ->values()
            ->toArray();

        $option->data = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
        );

        $option->save();

        $this->loadData();

        session()->flash(
            'success',
            'لینک شبکه اجتماعی حذف شد.'
        );
    }

    public function render()
    {
        return view('livewire.social_media_links');
    }
}
