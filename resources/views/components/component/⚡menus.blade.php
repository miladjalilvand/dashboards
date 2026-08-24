<?php

use App\Models\Branch;
use App\Models\MenuType;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    //
    public $menus , $menuTypes ;

    public $show_menu ;

    public $options=[];



 public  $branchesCount ;
 public function mount()
{
    $table_menu = Auth::user()->type == 'admin' ?
    'menus' : 'customer_menus' ;
    $this->menuTypes = MenuType::with($table_menu)->get();

    $this->branchesCount = Branch::count();

    $this->show_menu = app('show-menu-all');
    $panel = Auth::user()
        ->panels()
        ->where('dashboard_id', 1)
        ->first();

    if ($panel) {
        foreach ($panel->options as $panel_option) {
            $this->options[] = [
                'caption' => $panel_option->option->caption,
                'slug' => $panel_option->option->slug,
                'id' => $panel_option->option->id,
            ];
        }
    }
//    $panels_option =


}

};
?>

<div>


@foreach($menuTypes as $menuType)
    <span class=" font-semibold">
        {{ $menuType->caption }}
</sapn>


@if(true)
    @foreach($menuType->menus as $menu)
    @if($menu->slug == 'branches' || $branchesCount)
        <flux:sidebar.item

            :href="route($menu->slug.'.index')"

            :current="request()->is('/'.$menu->slug)"
            wire:navigate
            icon="{{$menu->icon}}"
        >
            {{ $menu->caption }}
        </flux:sidebar.item>
        @endif
    @endforeach
    @else

      @foreach($menuType->customer_menus as $menu)

        <flux:sidebar.item

            :href="route($menu->slug.'.index')"

            :current="request()->is('/'.$menu->slug)"
            wire:navigate
            icon="{{$menu->icon}}"
        >
            {{ $menu->caption }}
        </flux:sidebar.item>

    @endforeach
 @endif
@endforeach
        @foreach($options as $option)

                <flux:sidebar.item

                    :href="route($option['slug'].'.index')"

                    :current="request()->is('/'.$option['slug'])"
                    wire:navigate
                    icon="plus"
                >
            {{$option['caption'] }}
        </flux:sidebar.item>

    @endforeach
</div>

<!-- :href="url('/'.$menu->slug)"  -->
