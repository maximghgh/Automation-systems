<?php

namespace App\Filament\Pages;

use App\Models\AboutPage;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AboutPageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'О компании';

    protected static ?string $title = 'О компании';

    protected static ?int $navigationSort = 100;

    protected string $view = 'filament.pages.about-page-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $about = AboutPage::query()->find(1);

        if (! $about) {
            $about = AboutPage::query()->create([
                'id' => 1,
                'banner_title' => 'О компании',
                'banner_text' => null,
                'company_text' => null,
                'advantages_items' => $this->getDefaultAdvantagesItems(),
                'catalog_title' => 'Перейти в каталог',
                'catalog_text' => null,
            ]);
        }

        $this->form->fill([
            'banner_title' => $about->banner_title,
            'banner_text' => $about->banner_text,
            'company_text' => $about->company_text,
            'advantages_items' => $about->advantages_items ?: $this->getDefaultAdvantagesItems(),
            'catalog_title' => $about->catalog_title,
            'catalog_text' => $about->catalog_text,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Баннер')
                    ->schema([
                        TextInput::make('banner_title')
                            ->label('Заголовок')
                            ->required(),

                        Textarea::make('banner_text')
                            ->label('Текст')
                            ->rows(4)
                            ->nullable(),
                    ]),

                Section::make('О компании')
                    ->schema([
                        Textarea::make('company_text')
                            ->label('Текст')
                            ->rows(8)
                            ->nullable(),
                    ]),

                Section::make('Преимущества')
                    ->schema([
                        Repeater::make('advantages_items')
                            ->label('Карточки')
                            ->default($this->getDefaultAdvantagesItems())
                            ->minItems(4)
                            ->maxItems(4)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable()
                            ->collapsible()
                            ->schema([
                                TextInput::make('title')
                                    ->label('Заголовок')
                                    ->required(),

                                Textarea::make('text')
                                    ->label('Текст')
                                    ->rows(3)
                                    ->nullable(),

                                FileUpload::make('icon')
                                    ->label('Иконка')
                                    ->image()
                                    ->disk('public')
                                    ->directory('about-page/icons')
                                    ->visibility('public')
                                    ->openable()
                                    ->downloadable()
                                    ->helperText('Загрузите изображение и при необходимости скачайте его.')
                                    ->nullable(),
                            ]),
                    ]),

                Section::make('Блок перехода в каталог')
                    ->schema([
                        TextInput::make('catalog_title')
                            ->label('Заголовок')
                            ->required(),

                        Textarea::make('catalog_text')
                            ->label('Текст')
                            ->rows(4)
                            ->nullable(),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        AboutPage::query()->updateOrCreate(['id' => 1], $data);

        Notification::make()
            ->title('Сохранено')
            ->success()
            ->send();
    }

    protected function getDefaultAdvantagesItems(): array
    {
        return [
            ['title' => 'Широкий ассортимент', 'text' => null, 'icon' => null],
            ['title' => 'Контроль качества', 'text' => null, 'icon' => null],
            ['title' => 'Продуманная логистика', 'text' => null, 'icon' => null],
            ['title' => 'Прием заявок', 'text' => null, 'icon' => null],
        ];
    }
}
