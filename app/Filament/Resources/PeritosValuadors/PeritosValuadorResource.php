<?php

namespace App\Filament\Resources\PeritosValuadors;

use App\Filament\Resources\PeritosValuadors\Pages\ManagePeritosValuadors;
use App\Models\PeritosValuador;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeritosValuadorResource extends Resource
{
    protected static ?string $model = PeritosValuador::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogos y Configuración';

    protected static ?string $modelLabel = 'Perito Valuador';

    protected static ?string $pluralModelLabel = 'Peritos Valuadores';

    protected static ?string $navigationLabel = 'Peritos Valuadores';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del Perito Valuador')
                    ->description('Padrón de arquitectos e ingenieros peritos autorizados por el estado.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('nombre_completo')
                                    ->label('Nombre Completo del Perito')
                                    ->required()
                                    ->maxLength(150)
                                    ->placeholder('ej. Arq. Roberto Morales Santos')
                                    ->suffixIcon('heroicon-o-user')
                                    ->columnSpanFull(),

                                TextInput::make('cedula_registro')
                                    ->label('Cédula o Número de Registro Estatal')
                                    ->maxLength(50)
                                    ->placeholder('ej. PUE-VAL-2024-88')
                                    ->suffixIcon('heroicon-o-identification'),

                                TextInput::make('telefono')
                                    ->label('Teléfono de Contacto')
                                    ->tel()
                                    ->maxLength(20)
                                    ->placeholder('ej. 2221234567')
                                    ->suffixIcon('heroicon-o-phone'),

                                TextInput::make('email')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->maxLength(120)
                                    ->placeholder('ej. arq.morales@peritajes.mx')
                                    ->suffixIcon('heroicon-o-envelope')
                                    ->columnSpanFull(),

                                Toggle::make('activo')
                                    ->label('Perito Activo en Padrón')
                                    ->default(true)
                                    ->inline(false)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('nombre_completo')
                    ->label('Nombre del Perito')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('cedula_registro')
                    ->label('Cédula / Registro')
                    ->searchable()
                    ->copyable()
                    ->placeholder('-'),

                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->copyable()
                    ->placeholder('-'),

                TextColumn::make('email')
                    ->label('Correo')
                    ->copyable()
                    ->placeholder('-'),

                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('activo')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()->modalWidth('lg'),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select([
                'id',
                'nombre_completo',
                'cedula_registro',
                'telefono',
                'email',
                'activo',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->orderBy('nombre_completo');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePeritosValuadors::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
