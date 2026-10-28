<?php

namespace App\Filament\Resources\ConceptosCatalogos;

use App\Filament\Resources\ConceptosCatalogos\Pages\ManageConceptosCatalogos;
use App\Models\ConceptosCatalogo;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConceptosCatalogoResource extends Resource
{
    protected static ?string $model = ConceptosCatalogo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogos y Configuración';

    protected static ?string $modelLabel = 'Concepto de Cobro';

    protected static ?string $pluralModelLabel = 'Conceptos de Cobro';

    protected static ?string $navigationLabel = 'Conceptos de Cobro';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Definición del Concepto Notarial')
                    ->description('Especifica la clave, denominación y destino de cada impuesto, derecho o gasto.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('clave')
                                    ->label('Clave del Concepto')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(30)
                                    ->placeholder('ej. ISABI o RPP_INSC')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                Select::make('categoria')
                                    ->label('Categoría Financiera')
                                    ->options([
                                        'impuesto' => 'Impuesto Estatal / Municipal',
                                        'derecho' => 'Derecho Registral / Catastral',
                                        'gasto_gestoria' => 'Gasto de Gestoría Operativa',
                                        'avaluo' => 'Avalúo Pericial',
                                        'honorarios_notaria' => 'Honorarios Notariales',
                                    ])
                                    ->required()
                                    ->suffixIcon('heroicon-o-tag'),

                                TextInput::make('nombre')
                                    ->label('Nombre Completo del Concepto')
                                    ->required()
                                    ->maxLength(150)
                                    ->placeholder('ej. Impuesto Sobre Adquisición de Bienes Inmuebles')
                                    ->suffixIcon('heroicon-o-document-text')
                                    ->columnSpanFull(),

                                TextInput::make('dependencia_destino')
                                    ->label('Dependencia Destino')
                                    ->maxLength(100)
                                    ->placeholder('ej. Catastro Municipal o Registro Público')
                                    ->suffixIcon('heroicon-o-building-office-2'),

                                TextInput::make('dias_resolucion_est')
                                    ->label('Días Estimados de Resolución')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required()
                                    ->default(3)
                                    ->suffix(' días naturales')
                                    ->placeholder('3')
                                    ->suffixIcon('heroicon-o-clock'),

                                Toggle::make('activo')
                                    ->label('Concepto Habilitado')
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
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('nombre')
                    ->label('Concepto')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('categoria')
                    ->label('Categoría')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'impuesto' => 'Impuesto',
                        'derecho' => 'Derecho',
                        'gasto_gestoria' => 'Gestoría',
                        'avaluo' => 'Avalúo',
                        'honorarios_notaria' => 'Honorarios',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'impuesto' => 'danger',
                        'derecho' => 'warning',
                        'gasto_gestoria' => 'info',
                        'avaluo' => 'primary',
                        'honorarios_notaria' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('dependencia_destino')
                    ->label('Dependencia')
                    ->placeholder('-'),

                TextColumn::make('dias_resolucion_est')
                    ->label('Plazo Estimado')
                    ->numeric()
                    ->suffix(' días naturales')
                    ->sortable(),

                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('categoria')
                    ->label('Categoría')
                    ->options([
                        'impuesto' => 'Impuesto',
                        'derecho' => 'Derecho',
                        'gasto_gestoria' => 'Gasto de Gestoría',
                        'avaluo' => 'Avalúo',
                        'honorarios_notaria' => 'Honorarios Notaría',
                    ])
                    ->placeholder('Todas'),

                TernaryFilter::make('activo')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()->modalWidth('xl'),
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
                'clave',
                'nombre',
                'categoria',
                'dependencia_destino',
                'dias_resolucion_est',
                'activo',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->orderBy('clave');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageConceptosCatalogos::route('/'),
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
