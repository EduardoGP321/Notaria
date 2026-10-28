<?php

namespace App\Filament\Resources\Puestos;

use App\Filament\Resources\Puestos\Pages\ManagePuestos;
use App\Models\Puesto;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
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

class PuestoResource extends Resource
{
    protected static ?string $model = Puesto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogos y Configuración';

    protected static ?string $modelLabel = 'Puesto Notarial';

    protected static ?string $pluralModelLabel = 'Puestos Notariales';

    protected static ?string $navigationLabel = 'Puestos Notariales';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del Puesto o Rol Operativo')
                    ->description('Estructura orgánica de la notaría para asignación a usuarios colaboradores.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('Nombre del Puesto')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(100)
                                    ->placeholder('ej. Abogado Dictaminador')
                                    ->suffixIcon('heroicon-o-briefcase'),

                                TextInput::make('clave')
                                    ->label('Clave Operativa')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(50)
                                    ->placeholder('ej. ABOG_DICT')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                Textarea::make('descripcion')
                                    ->label('Descripción de Responsabilidades')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->placeholder('ej. Responsable de la dictaminación jurídica de antecedentes, elaboración de proyectos y cotejo de requisitos.')
                                    ->columnSpanFull(),

                                Toggle::make('activo')
                                    ->label('Puesto Activo')
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
                TextColumn::make('clave')
                    ->label('Clave')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->copyable(),

                TextColumn::make('nombre')
                    ->label('Puesto')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('descripcion')
                    ->label('Responsabilidades')
                    ->limit(50)
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
                'nombre',
                'clave',
                'descripcion',
                'activo',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->orderBy('nombre');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePuestos::route('/'),
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


