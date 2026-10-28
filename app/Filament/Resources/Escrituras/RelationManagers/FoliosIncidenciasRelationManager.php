<?php

namespace App\Filament\Resources\Escrituras\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FoliosIncidenciasRelationManager extends RelationManager
{
    protected static string $relationship = 'foliosIncidencias';

    protected static ?string $title = 'Incidencias y Folios Inutilizados';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                    ->schema([
                        Select::make('lote_folio_id')
                            ->label('Libro / Dotación de Folio')
                            ->relationship('loteFolio', 'numero_volumen_libro')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Selecciona libro')
                            ->suffixIcon('heroicon-o-book-open'),

                        TextInput::make('numero_folio')
                            ->label('Número de Folio Afectado')
                            ->numeric()
                            ->required()
                            ->placeholder('ej. 71012')
                            ->suffixIcon('heroicon-o-hashtag'),

                        Select::make('motivo')
                            ->label('Motivo de Incidencia')
                            ->options([
                                'error_impresion' => 'Error de Impresión',
                                'hoja_danada_o_rota' => 'Hoja Dañada o Rota',
                                'escritura_cancelada_no_paso' => 'Escritura Cancelada (No Pasó)',
                                'folio_extraviado' => 'Folio Extraviado',
                            ])
                            ->required()
                            ->suffixIcon('heroicon-o-exclamation-triangle'),

                        Select::make('usuario_reporta_id')
                            ->label('Usuario que Reporta')
                            ->relationship('usuarioReporta', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Selecciona usuario')
                            ->suffixIcon('heroicon-o-user'),

                        DateTimePicker::make('fecha_incidencia')
                            ->label('Fecha y Hora de la Incidencia')
                            ->required()
                            ->default(now())
                            ->suffixIcon('heroicon-o-clock'),

                        Textarea::make('descripcion_detalle')
                            ->label('Detalle de los Hechos')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('ej. Se atascó la hoja en la impresora durante el reverso de la foja 12, sufriendo rasgadura irreparable.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['loteFolio', 'usuarioReporta']))
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->recordTitleAttribute('numero_folio')
            ->columns([
                TextColumn::make('numero_folio')
                    ->label('Folio')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('loteFolio.numero_volumen_libro')
                    ->label('Libro')
                    ->formatStateUsing(fn ($state) => "Libro {$state}"),

                TextColumn::make('motivo')
                    ->label('Motivo')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'error_impresion' => 'warning',
                        'hoja_danada_o_rota' => 'danger',
                        'escritura_cancelada_no_paso' => 'gray',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'error_impresion' => 'Error Impresión',
                        'hoja_danada_o_rota' => 'Hoja Rota',
                        'escritura_cancelada_no_paso' => 'No Pasó',
                        'folio_extraviado' => 'Extraviado',
                        default => $state,
                    }),

                TextColumn::make('fecha_incidencia')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i'),

                TextColumn::make('usuarioReporta.name')
                    ->label('Reportó'),
            ])
            ->filters([
                SelectFilter::make('motivo')
                    ->label('Motivo')
                    ->options([
                        'error_impresion' => 'Error de Impresión',
                        'hoja_danada_o_rota' => 'Hoja Dañada o Rota',
                        'escritura_cancelada_no_paso' => 'Escritura Cancelada',
                        'folio_extraviado' => 'Folio Extraviado',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Reportar Incidencia'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
