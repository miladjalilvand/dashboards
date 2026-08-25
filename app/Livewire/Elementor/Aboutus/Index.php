<?php
namespace App\Livewire\Elementor\Aboutus;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;

    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    public string $aboutus = '';

    public array $images = [];


    /*
    |--------------------------------------------------------------------------
    | Dialog
    |--------------------------------------------------------------------------
    */

    public bool $showDialog = false;


    /*
    |--------------------------------------------------------------------------
    | New Images
    |--------------------------------------------------------------------------
    */

    public array $newImages = [];
    public bool $showImageDialog = false;

    public ?string $selectedImage = null;


    public function openImage(int $index)
    {
        if (!isset($this->images[$index])) {
            return;
        }

        $this->selectedImage = $this->images[$index];

        $this->showImageDialog = true;
    }

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
            ->where('option_id', 2)
            ->first();

        $data = $option?->data ?? [];

        $this->aboutus = $data['aboutus'] ?? '';

        $this->images = $data['images'] ?? [];
    }


    /*
    |--------------------------------------------------------------------------
    | Open Dialog
    |--------------------------------------------------------------------------
    */

    public function openDialog()
    {
        $this->resetValidation();

        $this->newImages = [];

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

        $this->newImages = [];

        $this->resetValidation();
    }


    /*
    |--------------------------------------------------------------------------
    | Save Data
    |--------------------------------------------------------------------------
    */

    public function save()
    {
        $this->validate([
            'aboutus' => [
                'nullable',
                'string',
            ],

            'newImages' => [
                'nullable',
                'array',
                'max:10',
            ],

            'newImages.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Upload Images
        |--------------------------------------------------------------------------
        */

        foreach ($this->newImages as $image) {

            $path = $image->store(
                'aboutus',
                'public'
            );

            $this->images[] = $path;
        }


        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */

        $this->saveData();


        /*
        |--------------------------------------------------------------------------
        | Reset
        |--------------------------------------------------------------------------
        */

        $this->newImages = [];

        $this->closeDialog();
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Existing Image
    |--------------------------------------------------------------------------
    */

    public function deleteImage(int $index)
    {
        if (!isset($this->images[$index])) {
            return;
        }


        $image = $this->images[$index];


        /*
        |--------------------------------------------------------------------------
        | Delete File
        |--------------------------------------------------------------------------
        */

        if (
            $image &&
            str_starts_with($image, 'aboutus/')
        ) {
            Storage::disk('public')->delete($image);
        }


        /*
        |--------------------------------------------------------------------------
        | Remove From Array
        |--------------------------------------------------------------------------
        */

        unset($this->images[$index]);

        $this->images = array_values(
            $this->images
        );


        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */

        $this->saveData();
    }


    /*
    |--------------------------------------------------------------------------
    | Save Data To Database
    |--------------------------------------------------------------------------
    */

    private function saveData()
    {
        $panel = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->firstOrFail();

        $option = $panel->options()
            ->where('option_id', 2)
            ->firstOrFail();


        $option->data = [
            'images' => $this->images,

            'aboutus' => $this->aboutus,
        ];


        $option->save();
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.elementor.aboutus'
        );
    }
}
