<?php

namespace App\Filament\Concerns;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * For CreateRecord pages: renders the form as a full-width card and moves the
 * Create / Cancel buttons into the footer of the card (the last top-level Section).
 */
trait HasActionsInsideFormCard
{
    protected ?bool $actionsInsideCard = null;

    public function form(Schema $schema): Schema
    {
        $schema = parent::form($schema);

        $sections = array_values(array_filter(
            $schema->getComponents(withActions: false),
            fn ($component) => $component instanceof Section,
        ));

        foreach ($sections as $section) {
            $section->columnSpanFull();
        }

        if ($section = end($sections)) {
            $section->footerActions($this->getFormActions());
            $this->actionsInsideCard = true;
        }

        return $schema;
    }

    public function getFormContentComponent(): Component
    {
        if ($this->actionsInsideCard === false) {
            return parent::getFormContentComponent();
        }

        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler($this->getSubmitFormLivewireMethodName());
    }
}
