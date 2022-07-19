<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Json extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private string|array|null $data = null, public string $parsed = "")
    {
        $this->parse();
    }

    private function parse()
    {
        if ($this->data) {
            $this->parsed = "{";
            if (is_array($this->data)) {
                foreach ($this->data as $parsed) {
                    $this->parsed .= "{$parsed}, ";
                }
                $this->parsed = rtrim($this->parsed, ", ");
            } else {
                $this->parsed = "{$this->data}";
            }
            $this->parsed .= "}";
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.json');
    }
}
