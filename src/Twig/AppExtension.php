<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'doSomething']),
        ];
    }

    public function doSomething(int $count, string $singular, ?string $plural= null) :string
    {
             
        $plural ??= $singular . 's'; // $plural = $plural ?? $singular . 's'; (c'est la même mais en version plus lisible)

        $result = $count === 1 ? $singular : $plural;
        return "$count $result";
    }
}
