<?php

use Livewire\Component;

new class extends Component
{
    //
    public $segment ;
    public $persianCaptionButton ;

    public function mount()
    {
        $this->segment = request()->segment(1);

        $this->persianCaptionButton = getPersianModuleCaptionCreateButtons( request()->segment(1));

        // dd('create'.$this->segment);

    }


};
?>

<div>

@if( $persianCaptionButton )
    <flux:modal.trigger name="{{$segment}}">
        <flux:button variant="primary" x-data="" x-on:click.prevent="$dispatch('open-modal', $segment)" data-test="delete-user-button">
            {{ __($persianCaptionButton)}}
        </flux:button>
    </flux:modal.trigger>
@endif


</div>