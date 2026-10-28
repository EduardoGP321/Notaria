<?php

namespace App\Filament\Resources\AvisosPreventivos;

use App\Filament\Resources\AvisosPreventivos\Pages\ManageAvisosPreventivos;
use App\Models\AvisosPreventivo;
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

class AvisosPreventivoResource extends Resource
{
    protected static ?string $model = AvisosPreventivo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Trámites y RPP';

    protected static ?string $modelLabel = 'Aviso Preventivo';

    protected static ?string $pluralModelLabel = 'Avisos Preventivos';

    protected static ?string $navigationLabel = 'Avisos Preventivos';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación del Aviso Registral')
                    ->description('Control de protección registral de primer o segundo aviso ante el Registro Público.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                Select::make('expediente_id')
                                    ->label('Expediente Notarial')
                                    ->relationship('expediente', 'numero_expediente')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona expediente')
                                    ->suffixIcon('heroicon-o-folder'),

                                Select::make('tipo_aviso')
                                    ->label('Modalidad del Aviso')
                                    ->options([
                                        'primer_aviso' => 'Primer Aviso (Notarial - Solicitud de Certificado)',
                                        'segundo_aviso' => 'Segundo Aviso (Preventivo / Definitivo de Firma)',
                                    ])
                                    ->required()
                                    ->suffixIcon('heroicon-o-document-check'),

                                TextInput::make('numero_entrada_rpp')
                                    ->label('Número de Entrada / Boleta RPP')
                                    ->maxLength(50)
                                    ->placeholder('ej. RPP-PUE-2026-98124')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                Select::make('estatus')
                                    ->label('Estatus del Aviso')
                                    ->options([
                                        'presentado' => 'Presentado en RPP',
                                        'vigente' => 'Vigente (Con Efectos Protectores)',
                                        'vencido' => 'Vencido',
                                        'concluido' => 'Concluido por Inscripción Definitiva',
                                    ])
                                    ->default('vigente')
                                    ->required()
                                    ->suffixIcon('heroicon-o-check-circle'),
                            ]),
                    ]),

                Section::make('Vigencia Registral y Acuse Digital')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                DatePicker::make('fecha_presentacion_rpp')
                                    ->label('Fecha de Presentación ante RPP')
                                    ->required()
                                    ->default(now())
                                    ->suffixIcon('heroicon-o-calendar'),

                                DatePicker::make('fecha_vencimiento')
                                    ->label('Fecha de Vencimiento de Protección')
                                    ->required()
                                    ->suffixIcon('heroicon-o-calendar-days')
                                    ->helperText('Plazo legal en días naturales según ley registral estatal.'),

                                FileUpload::make('documento_formato_url')
                                    ->label('Boleta o Formato RPP Sellado (PDF)')
                                    ->disk('public')
                                    ->directory('avisos_preventivos')
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

                TextColumn::make('tipo_aviso')
                    ->label('Modalidad')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'primer_aviso' => '1er Aviso',
                        'segundo_aviso' => '2do Aviso',
                        default => ucfirst($state ?? '-'),
                    })
                    ->colors([
                        'info' => 'primer_aviso',
                        'warning' => 'segundo_aviso',
                    ]),

                TextColumn::make('numero_entrada_rpp')
                    ->label('Entrada / Boleta RPP')
                    ->searchable()
                    ->copyable()
                    ->placeholder('-'),

                TextColumn::make('fecha_presentacion_rpp')
                    ->label('Presentación')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('fecha_vencimiento')
                    ->label('Vencimiento')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('estatus')
                    ->label('Estatus')
                    ->badge()
                    ->colors([
                        'info' => 'presentado',
                        'success' => 'vigente',
                        'danger' => 'vencido',
                        'gray' => 'concluido',
                    ])
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?? '-')),

                IconColumn::make('documento_formato_url')
                    ->label('Boleta')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (AvisosPreventivo $record): ?string => $record->documento_formato_url ? asset('storage/' . $record->documento_formato_url) : null, shouldOpenInNewTab: true)
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('tipo_aviso')
                    ->label('Tipo de Aviso')
                    ->options([
                        'primer_aviso' => 'Primer Aviso',
                        'segundo_aviso' => 'Segundo Aviso',
                    ]),

                SelectFilter::make('estatus')
                    ->label('Estatus')
                    ->options([
                        'presentado' => 'Presentado',
                        'vigente' => 'Vigente',
                        'vencido' => 'Vencido',
                        'concluido' => 'Concluido',
                    ]),

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
                'expediente:id,numero_expediente',
            ])
            ->select([
                'id',
                'expediente_id',
                'tipo_aviso',
                'fecha_presentacion_rpp',
                'fecha_vencimiento',
                'numero_entrada_rpp',
                'documento_formato_url',
                'estatus',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAvisosPreventivos::route('/'),
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