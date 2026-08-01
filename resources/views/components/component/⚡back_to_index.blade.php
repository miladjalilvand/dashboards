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

        $this->persianCaptionButton = getPersianModuleCaptions( request()->segment(1));

    }
};
?>

<div>
    <flux:link :href="route($segment.'.'.'index')" wire:navigate>{{ __('بارگشت به '. $persianCaptionButton) }}</flux:link>
</div>