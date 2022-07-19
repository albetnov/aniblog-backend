<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Card extends Component
{
    public string $addonClass = "";
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public string $title, private array|string $addon = "")
    {
        $this->parse();
    }

    private function parse()
    {
        if ($this->addon) {
            if (is_array($this->addon)) {
                foreach ($this->addon as $addon) {
                    $this->addonClass .= "{$addon} ";
                }
                $this->addonClass = rtrim($this->addonClass, " ");
            } else {
                $this->addonClass = $this->addon;
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card');
    }
}
