<?php

namespace App\Filament\Resources\DeclaranotEnvios;

use App\Filament\Resources\DeclaranotEnvios\Pages\ManageDeclaranotEnvios;
use App\Models\DeclaranotEnvio;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeclaranotEnvioResource extends Resource
{
    protected static ?string $model = DeclaranotEnvio::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|\UnitEnum|null $navigationGroup = 'Fiscal y SAT';

    protected static ?string $modelLabel = 'Envío Declaranot (SAT)';

    protected static ?string $pluralModelLabel = 'Envíos Declaranot (SAT)';

    protected static ?string $navigationLabel = 'Declaranot (SAT ISR)';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Instrumento y Contribuyente Enajenante')
                    ->description('Datos de la retención de Impuesto Sobre la Renta (ISR) por enajenación de inmuebles.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                Select::make('escritura_id')
                                    ->label('Escritura Pública Notarial')
                                    ->relationship('escritura', 'numero_escritura')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona escritura')
                                    ->suffixIcon('heroicon-o-book-open'),

                                Select::make('vendedor_cliente_id')
                                    ->label('Parte Enajenante (Vendedor / Contribuyente)')
                                    ->relationship('vendedorCliente', 'nombre_razon_social')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona cliente vendedor')
                                    ->suffixIcon('heroicon-o-user'),

                                TextInput::make('monto_isr_retenido')
                                    ->label('Monto de ISR Retenido')
                                    ->numeric()
                                    ->prefix('$')
                                    ->suffix('MXN')
                                    ->required()
                                    ->placeholder('ej. 85400.00')
                                    ->suffixIcon('heroicon-o-currency-dollar'),

                                TextInput::make('folio_declaranot_sat')
                                    ->label('Folio Oficial Declaranot SAT')
                                    ->maxLength(60)
                                    ->placeholder('ej. DEC-SAT-2026-883921')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                DateTimePicker::make('fecha_envio')
                                    ->label('Fecha y Hora de Declaración ante SAT')
                                    ->default(now())
                                    ->seconds(false)
                                    ->suffixIcon('heroicon-o-calendar'),

                                Toggle::make('enviado_por_correo')
                                    ->label('Constancia Entregada / Enviada al Enajenante')
                                    ->default(false)
                                    ->inline(false),

                                FileUpload::make('formato_pdf_url')
                                    ->label('Acuse de Declaración SAT y Hoja de Liquidación (PDF)')
                                    ->disk('public')
                                    ->directory('declaranot')
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
                TextColumn::make('escritura.numero_escritura')
                    ->label('Escritura')
                    ->prefix('Esc. ')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('vendedorCliente.nombre_razon_social')
                    ->label('Enajenante / Vendedor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('vendedorCliente.rfc')
                    ->label('RFC Enajenante')
                    ->badge()
                    ->placeholder('-'),

                TextColumn::make('folio_declaranot_sat')
                    ->label('Folio SAT')
                    ->searchable()
                    ->copyable()
                    ->placeholder('Pendiente de folio'),

                TextColumn::make('monto_isr_retenido')
                    ->label('ISR Retenido')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('fecha_envio')
                    ->label('Fecha Declaración')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('No enviado'),

                IconColumn::make('enviado_por_correo')
                    ->label('Notificado')
                    ->boolean(),

                IconColumn::make('formato_pdf_url')
                    ->label('Acuse')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (DeclaranotEnvio $record): ?string => $record->formato_pdf_url ? asset('storage/' . $record->formato_pdf_url) : null, shouldOpenInNewTab: true)
                    ->placeholder('-'),
            ])
            ->filters([
                TernaryFilter::make('enviado_por_correo')
                    ->label('Notificado a Cliente')
                    ->placeholder('Todos')
                    ->trueLabel('Notificados')
                    ->falseLabel('Pendientes'),

                TernaryFilter::make('folio_declaranot_sat')
                    ->label('Estatus SAT')
                    ->placeholder('Todos')
                    ->nullable()
                    ->trueLabel('Declarados con folio')
                    ->falseLabel('Sin folio SAT'),

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
            ->with([
                'escritura:id,numero_escritura',
                'vendedorCliente:id,nombre_razon_social,rfc',
            ])
            ->select([
                'id',
                'escritura_id',
                'vendedor_cliente_id',
                'monto_isr_retenido',
                'folio_declaranot_sat',
                'formato_pdf_url',
                'enviado_por_correo',
                'fecha_envio',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDeclaranotEnvios::route('/'),
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