<?php

namespace App\View\Components;

use Illuminate\View\Component;

class IconMapper extends Component
{
    public $iconName;
    public $size;
    public $class;

    public function __construct($icon = 'home', $size = '6', $class = '')
    {
        $this->iconName = $icon;
        $this->size = $size;
        $this->class = $class;
    }

    public function getHeroicon()
    {
        $mapping = [
            'bell' => 'heroicon-o-bell',
            'chat-bubble-left' => 'heroicon-o-chat-bubble-left',
            'shopping-cart' => 'heroicon-o-shopping-cart',
            'star' => 'heroicon-o-star',
            'cube' => 'heroicon-o-cube',
            'user' => 'heroicon-o-user',
            'cog' => 'heroicon-o-cog-6-tooth',
            'chart-bar' => 'heroicon-o-chart-bar',
            'document-text' => 'heroicon-o-document-text',
            'arrow-trending-up' => 'heroicon-o-arrow-trending-up',
            'arrow-trending-down' => 'heroicon-o-arrow-trending-down',
            'shopping-bags' => 'heroicon-o-shopping-bag',
            'door-open' => 'heroicon-o-arrow-right-on-rectangle',
            'arrow-left' => 'heroicon-o-arrow-left',
            'envelope' => 'heroicon-o-envelope',
            'phone' => 'heroicon-o-phone',
            'map-pin' => 'heroicon-o-map-pin',
            'home' => 'heroicon-o-home',
            'book-open' => 'heroicon-o-book-open',
            'sparkles' => 'heroicon-o-sparkles',
            'eye' => 'heroicon-o-eye',
            'clipboard' => 'heroicon-o-document-clipboard',
            'package' => 'heroicon-o-cube',
            'box' => 'heroicon-o-cube',
        ];

        return $mapping[$this->iconName] ?? "heroicon-o-{$this->iconName}";
    }

    public function render()
    {
        $heroicon = $this->getHeroicon();
        return "<x-{$heroicon} class=\"w-{$this->size} h-{$this->size} {$this->class}\" />";
    }
}
