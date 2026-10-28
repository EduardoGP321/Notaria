<?php

namespace App\Filament\Resources\CitasAgendas;

use App\Filament\Resources\CitasAgendas\Pages\ManageCitasAgendas;
use App\Models\CitasAgenda;
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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CitasAgendaResource extends Resource
{
    protected static ?string $model = CitasAgenda::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|\UnitEnum|null $navigationGroup = 'Agenda y Citas';

    protected static ?string $modelLabel = 'Cita de Agenda';

    protected static ?string $pluralModelLabel = 'Citas de Agenda';

    protected static ?string $navigationLabel = 'Agenda de Citas';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles de la Cita Notarial')
                    ->description('Programación de firmas en protocolo, recepción de documentos y asesorías.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('nombre_interesado')
                                    ->label('Nombre del Compareciente / Interesado')
                                    ->required()
                                    ->maxLength(150)
                                    ->placeholder('ej. Ing. Roberto Palacios Domínguez')
                                    ->suffixIcon('heroicon-o-user')
                                    ->columnSpanFull(),

                                Select::make('abogado_id')
                                    ->label('Abogado / Notario Responsable')
                                    ->relationship('abogado', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona abogado')
                                    ->suffixIcon('heroicon-o-academic-cap'),

                                Select::make('expediente_id')
                                    ->label('Expediente Notarial (Opcional)')
                                    ->relationship('expediente', 'numero_expediente')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('ej. EXP-2026-0045')
                                    ->suffixIcon('heroicon-o-folder'),

                                Select::make('tipo_cita')
                                    ->label('Motivo / Tipo de Cita')
                                    ->options([
                                        'asesoria_inicial' => 'Asesoría Inicial / Apertura',
                                        'recepcion_documentos' => 'Recepción de Documentos Físicos',
                                        'firma_escritura' => 'Firma de Escritura (Protocolo)',
                                        'entrega_testimonio' => 'Entrega de Testimonio y Título',
                                        'cotejo_ratificacion' => 'Cotejo y Ratificación de Firmas',
                                    ])
                                    ->required()
                                    ->default('firma_escritura')
                                    ->suffixIcon('heroicon-o-clock'),

                                Select::make('estatus')
                                    ->label('Estatus de la Cita')
                                    ->options([
                                        'programada' => 'Programada',
                                        'confirmada' => 'Confirmada por Cliente',
                                        'atendida' => 'Atendida / Realizada',
                                        'cancelada' => 'Cancelada',
                                        'no_asistio' => 'No Asistió',
                                    ])
                                    ->default('programada')
                                    ->required()
                                    ->suffixIcon('heroicon-o-check-circle'),

                                DateTimePicker::make('fecha_hora_inicio')
                                    ->label('Fecha y Hora de Inicio')
                                    ->required()
                                    ->seconds(false)
                                    ->suffixIcon('heroicon-o-calendar'),

                                DateTimePicker::make('fecha_hora_fin')
                                    ->label('Fecha y Hora de Término Estimada')
                                    ->required()
                                    ->seconds(false)
                                    ->suffixIcon('heroicon-o-calendar'),
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
                TextColumn::make('nombre_interesado')
                    ->label('Interesado / Compareciente')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('tipo_cita')
                    ->label('Tipo de Cita')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'asesoria_inicial' => 'Asesoría Inicial',
                        'recepcion_documentos' => 'Recepción Docs',
                        'firma_escritura' => 'Firma Escritura',
                        'entrega_testimonio' => 'Entrega Testimonio',
                        'cotejo_ratificacion' => 'Cotejo y Ratificación',
                        default => ucfirst($state ?? '-'),
                    }),

                TextColumn::make('abogado.name')
                    ->label('Abogado Asignado')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('expediente.numero_expediente')
                    ->label('Expediente')
                    ->placeholder('Sin expediente')
                    ->sortable(),

                TextColumn::make('fecha_hora_inicio')
                    ->label('Fecha y Hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('estatus')
                    ->label('Estatus')
                    ->badge()
                    ->colors([
                        'warning' => 'programada',
                        'info' => 'confirmada',
                        'success' => 'atendida',
                        'danger' => ['cancelada', 'no_asistio'],
                    ])
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'programada' => 'Programada',
                        'confirmada' => 'Confirmada',
                        'atendida' => 'Atendida',
                        'cancelada' => 'Cancelada',
                        'no_asistio' => 'No Asistió',
                        default => ucfirst($state ?? '-'),
                    }),
            ])
            ->filters([
                SelectFilter::make('estatus')
                    ->label('Estatus')
                    ->options([
                        'programada' => 'Programada',
                        'confirmada' => 'Confirmada',
                        'atendida' => 'Atendida',
                        'cancelada' => 'Cancelada',
                        'no_asistio' => 'No Asistió',
                    ]),

                SelectFilter::make('tipo_cita')
                    ->label('Tipo de Cita')
                    ->options([
                        'asesoria_inicial' => 'Asesoría Inicial',
                        'recepcion_documentos' => 'Recepción Documentos',
                        'firma_escritura' => 'Firma de Escritura',
                        'entrega_testimonio' => 'Entrega Testimonio',
                        'cotejo_ratificacion' => 'Cotejo / Ratificación',
                    ]),

                SelectFilter::make('abogado_id')
                    ->label('Abogado')
                    ->relationship('abogado', 'name'),

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
                'abogado:id,name',
            ])
            ->select([
                'id',
                'expediente_id',
                'abogado_id',
                'nombre_interesado',
                'tipo_cita',
                'fecha_hora_inicio',
                'fecha_hora_fin',
                'estatus',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCitasAgendas::route('/'),
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