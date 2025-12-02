<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MarketplaceLayout extends Component
{
    public $title;
    public $description;

    public function __construct($title = null, $description = null)
    {
        $this->title = $title;
        $this->description = $description;
    }

    public function render()
    {
        return view('layouts.marketplace');
    }
}