<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConfirmModal extends Component
{
    public string $title;
    public string $message;
    public string $action;
    public string $method;

    public function __construct(
        string $title = 'Are you sure?',
        string $message = 'This action cannot be undone.',
        string $action,
        string $method = 'POST'
    ) {
        $this->title = $title;
        $this->message = $message;
        $this->action = $action;
        $this->method = strtoupper($method);
    }

    public function render()
    {
        return view('components.confirm-modal');
    }
}
