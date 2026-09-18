<?php

namespace App\Livewire\Search;

use App\Services\Search\SiteSearchService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    #[Url(as: 'q')]
    public string $query = '';

    #[Url]
    public ?string $type = null;

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function render()
    {
        $results = app(SiteSearchService::class)->search($this->query, $this->type);

        return view('livewire.search.index', [
            'results' => $results,
        ]);
    }
}
