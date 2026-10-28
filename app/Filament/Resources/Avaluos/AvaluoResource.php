<?php

namespace App\Filament\Resources\Avaluos;

use App\Filament\Resources\Avaluos\Pages\ManageAvaluos;
use App\Models\Avaluo;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AvaluoResource extends Resource
{
    protected static ?string $model = Avaluo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalculator;

    protected static string|\UnitEnum|null $navigationGroup = 'Trámites y RPP';

    protected static ?string $modelLabel = 'Avalúo';

    protected static ?string $pluralModelLabel = 'Avalúos';

    protected static ?string $navigationLabel = 'Avalúos';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Expediente y Perito Asignado')
                    ->description('Asignación del avalúo catastral o comercial al perito del padrón estatal.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                Select::make('expediente_id')
                                    ->label('Expediente Notarial')
                                    ->relationship('expediente', 'numero_expediente')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona expediente notarial')
                                    ->suffixIcon('heroicon-o-folder'),

                                Select::make('perito_id')
                                    ->label('Perito Valuador Autorizado')
                                    ->relationship('perito', 'nombre_completo')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona perito valuador')
                                    ->suffixIcon('heroicon-o-user'),

                                Select::make('estatus')
                                    ->label('Estatus del Avalúo')
                                    ->options([
                                        'solicitado' => 'Solicitado',
                                        'en_proceso' => 'En Proceso / Visita de Campo',
                                        'concluido' => 'Concluido / Dictamen Entregado',
                                        'cancelado' => 'Cancelado',
                                    ])
                                    ->default('solicitado')
                                    ->required()
                                    ->suffixIcon('heroicon-o-check-circle')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Fechas de Control de Entrega')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 3])
                            ->schema([
                                DatePicker::make('fecha_solicitud')
                                    ->label('Fecha de Solicitud')
                                    ->default(now())
                                    ->required()
                                    ->suffixIcon('heroicon-o-calendar'),

                                DatePicker::make('fecha_estimada_entrega')
                                    ->label('Fecha Estimada Entrega')
                                    ->required()
                                    ->suffixIcon('heroicon-o-calendar-days'),

                                DatePicker::make('fecha_recepcion_real')
                                    ->label('Fecha Recepción Real')
                                    ->suffixIcon('heroicon-o-calendar-days'),
                            ]),
                    ]),

                Section::make('Valuación y Expediente Digital')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('valor_catastral')
                                    ->label('Valor Catastral Determinado')
                                    ->numeric()
                                    ->prefix('$')
                                    ->suffix('MXN')
                                    ->placeholder('ej. 1450000.00')
                                    ->suffixIcon('heroicon-o-currency-dollar'),

                                TextInput::make('valor_comercial')
                                    ->label('Valor Comercial Concluido')
                                    ->numeric()
                                    ->prefix('$')
                                    ->suffix('MXN')
                                    ->placeholder('ej. 2100000.00')
                                    ->suffixIcon('heroicon-o-currency-dollar'),

                                FileUpload::make('archivo_avaluo_url')
                                    ->label('Dictamen Valuatorio Digital (PDF)')
                                    ->disk('public')
                                    ->directory('avaluos')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(10240)
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
                TextColumn::make('expediente.numero_expediente')
                    ->label('Expediente')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('perito.nombre_completo')
                    ->label('Perito Valuador')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('fecha_solicitud')
                    ->label('Solicitud')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('fecha_estimada_entrega')
                    ->label('Entrega Estimada')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('fecha_recepcion_real')
                    ->label('Recepción Real')
                    ->date('d/m/Y')
                    ->placeholder('Pendiente')
                    ->sortable(),

                TextColumn::make('valor_catastral')
                    ->label('V. Catastral')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('valor_comercial')
                    ->label('V. Comercial')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('estatus')
                    ->label('Estatus')
                    ->badge()
                    ->colors([
                        'warning' => 'solicitado',
                        'info' => 'en_proceso',
                        'success' => 'concluido',
                        'danger' => 'cancelado',
                    ])
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'solicitado' => 'Solicitado',
                        'en_proceso' => 'En Proceso',
                        'concluido' => 'Concluido',
                        'cancelado' => 'Cancelado',
                        default => ucfirst($state ?? '-'),
                    }),

                IconColumn::make('archivo_avaluo_url')
                    ->label('Dictamen')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (Avaluo $record): ?string => $record->archivo_avaluo_url ? asset('storage/' . $record->archivo_avaluo_url) : null, shouldOpenInNewTab: true)
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('estatus')
                    ->label('Estatus')
                    ->options([
                        'solicitado' => 'Solicitado',
                        'en_proceso' => 'En Proceso',
                        'concluido' => 'Concluido',
                        'cancelado' => 'Cancelado',
                    ]),

                SelectFilter::make('perito_id')
                    ->label('Perito Valuador')
                    ->relationship('perito', 'nombre_completo'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()->modalWidth('2xl'),
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
            ->with([
                'expediente:id,numero_expediente',
                'perito:id,nombre_completo',
            ])
            ->select([
                'id',
                'expediente_id',
                'perito_id',
                'fecha_solicitud',
                'fecha_estimada_entrega',
                'fecha_recepcion_real',
                'valor_catastral',
                'valor_comercial',
                'archivo_avaluo_url',
                'estatus',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAvaluos::route('/'),
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