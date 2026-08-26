<?php

namespace App\Livewire\Elementor\Banners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;

    public array $banners = [];

    public bool $showDialog = false;

    public ?int $editingIndex = null;

    public string $title = '';

    public string $caption = '';

    public $image = null;

    public $branch_selected ;

    public function closeImage()
    {
        $this->showImageDialog = false;

        $this->selectedImage = null;
    }

    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $panel = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->firstOrFail();

        $option = $panel->options()
            ->where('option_id', 1)
            ->first();

        $this->banners = $option?->data ?? [];
    }


    /*
    |--------------------------------------------------------------------------
    | Open Dialog
    |--------------------------------------------------------------------------
    */

    public function openDialog(?int $index = null)
    {
        $this->resetValidation();

        $this->editingIndex = $index;

        /*
        |--------------------------------------------------------------------------
        | Edit
        |--------------------------------------------------------------------------
        */

        if ($index !== null && isset($this->banners[$index])) {

            $banner = $this->banners[$index];

            $this->title = $banner['title'] ?? '';

            $this->caption = $banner['caption'] ?? '';

            // فایل جدید
            $this->image = null;

        }

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        else {

            $this->title = '';

            $this->caption = '';

            $this->image = null;
        }

        $this->showDialog = true;
    }


    /*
    |--------------------------------------------------------------------------
    | Close Dialog
    |--------------------------------------------------------------------------
    */

    public function closeDialog()
    {
        $this->showDialog = false;

        $this->reset([
            'title',
            'caption',
            'image',
            'editingIndex',
        ]);

        $this->resetValidation();
    }


    /*
    |--------------------------------------------------------------------------
    | Save Banners To Database
    |--------------------------------------------------------------------------
    */

    private function saveBanners()
    {
        $panel = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->firstOrFail();

        $option = $panel->options()
            ->where('option_id', 1)
            ->firstOrFail();

        $option->data = $this->banners;

        $option->save();
    }


    /*
    |--------------------------------------------------------------------------
    | Save / Update
    |--------------------------------------------------------------------------
    */

    public function save()
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $this->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'caption' => [
                'required',
                'string',
            ],

            'image' => [
                $this->editingIndex === null
                    ? 'required'
                    : 'nullable',

                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Upload New Image
        |--------------------------------------------------------------------------
        */

        $imagePath = null;

        if ($this->image) {

            $imagePath = $this->image->store(
                'banners',
                'public'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Edit Existing Banner
        |--------------------------------------------------------------------------
        */

        if ($this->editingIndex !== null) {

            $oldImage = $this->banners[$this->editingIndex]['image'] ?? null;


            // Title
            $this->banners[$this->editingIndex]['title']
                = $this->title;


            // Caption
            $this->banners[$this->editingIndex]['caption']
                = $this->caption;


            /*
            |--------------------------------------------------------------------------
            | Replace Image
            |--------------------------------------------------------------------------
            */

            if ($imagePath) {

                /*
                |--------------------------------------------------------------------------
                | Delete Old Image
                |--------------------------------------------------------------------------
                */

                if (
                    $oldImage &&
                    str_starts_with($oldImage, 'banners/')
                ) {

                    Storage::disk('public')->delete($oldImage);
                }


                /*
                |--------------------------------------------------------------------------
                | Set New Image
                |--------------------------------------------------------------------------
                */

                $this->banners[$this->editingIndex]['image']
                    = $imagePath;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Create New Banner
        |--------------------------------------------------------------------------
        */

        else {

            $this->banners[] = [

                'title' => $this->title,

                'caption' => $this->caption,

                'image' => $imagePath,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Save JSON
        |--------------------------------------------------------------------------
        */

        $this->saveBanners();


        /*
        |--------------------------------------------------------------------------
        | Close Dialog
        |--------------------------------------------------------------------------
        */

        $this->closeDialog();
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Banner
    |--------------------------------------------------------------------------
    */

    public function delete(int $index)
    {
        /*
        |--------------------------------------------------------------------------
        | Check Index
        |--------------------------------------------------------------------------
        */

        if (!isset($this->banners[$index])) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Get Image
        |--------------------------------------------------------------------------
        */

        $image = $this->banners[$index]['image'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Delete Image From Storage
        |--------------------------------------------------------------------------
        */

        if (
            $image &&
            str_starts_with($image, 'banners/')
        ) {

            Storage::disk('public')->delete($image);
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Banner
        |--------------------------------------------------------------------------
        */

        unset($this->banners[$index]);


        /*
        |--------------------------------------------------------------------------
        | Reset Array Indexes
        |--------------------------------------------------------------------------
        */

        $this->banners = array_values(
            $this->banners
        );


        /*
        |--------------------------------------------------------------------------
        | Save Updated JSON
        |--------------------------------------------------------------------------
        */

        $this->saveBanners();
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.elementor.banners'
        );
    }
}
