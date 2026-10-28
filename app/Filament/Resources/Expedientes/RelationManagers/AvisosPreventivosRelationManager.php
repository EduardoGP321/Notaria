<?php

namespace App\Filament\Resources\Expedientes\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AvisosPreventivosRelationManager extends RelationManager
{
    protected static string $relationship = 'avisosPreventivos';

    protected static ?string $title = 'Avisos Preventivos en RPP';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                    ->schema([
                        Select::make('tipo_aviso')
                            ->label('Tipo de Aviso')
                            ->options([
                                'primero' => 'Primer Aviso Preventivo',
                                'segundo' => 'Segundo Aviso Preventivo',
                            ])
                            ->default('primero')
                            ->required()
                            ->suffixIcon('heroicon-o-shield-check'),

                        Select::make('estatus')
                            ->label('Estatus del Aviso')
                            ->options([
                                'generado' => 'Generado en Notaría',
                                'presentado' => 'Presentado en RPP',
                                'autorizado' => 'Autorizado / Asentado',
                                'vencido' => 'Vencido por Plazo Legal',
                            ])
                            ->default('presentado')
                            ->required()
                            ->suffixIcon('heroicon-o-clock'),

                        TextInput::make('numero_entrada_rpp')
                            ->label('Número de Entrada RPP')
                            ->maxLength(60)
                            ->placeholder('ej. ENT-2026-98124')
                            ->suffixIcon('heroicon-o-hashtag'),

                        DatePicker::make('fecha_presentacion_rpp')
                            ->label('Fecha de Presentación')
                            ->required()
                            ->default(now())
                            ->placeholder('ej. 2026-10-27')
                            ->suffixIcon('heroicon-o-calendar'),

                        DatePicker::make('fecha_vencimiento')
                            ->label('Fecha de Vencimiento Legal')
                            ->required()
                            ->placeholder('ej. 2026-11-26')
                            ->suffixIcon('heroicon-o-calendar-days'),

                        TextInput::make('documento_formato_url')
                            ->label('Ruta / Formato Escaneado')
                            ->maxLength(255)
                            ->placeholder('ej. avisos/2026/aviso_exp0145_sello.pdf')
                            ->suffixIcon('heroicon-o-paper-clip')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->recordTitleAttribute('tipo_aviso')
            ->columns([
                TextColumn::make('tipo_aviso')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'primero' => '1er Aviso',
                        'segundo' => '2do Aviso',
                        default => $state,
                    }),

                TextColumn::make('numero_entrada_rpp')
                    ->label('Entrada RPP')
                    ->placeholder('-')
                    ->copyable(),

                TextColumn::make('fecha_presentacion_rpp')
                    ->label('Presentación')
                    ->date('d/m/Y'),

                TextColumn::make('fecha_vencimiento')
                    ->label('Vencimiento')
                    ->date('d/m/Y')
                    ->weight('bold'),

                TextColumn::make('estatus')
                    ->label('Estatus')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'generado' => 'gray',
                        'presentado' => 'info',
                        'autorizado' => 'success',
                        'vencido' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'generado' => 'Generado',
                        'presentado' => 'Presentado',
                        'autorizado' => 'Autorizado',
                        'vencido' => 'Vencido',
                        default => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('estatus')
                    ->label('Estatus')
                    ->options([
                        'generado' => 'Generado',
                        'presentado' => 'Presentado',
                        'autorizado' => 'Autorizado',
                        'vencido' => 'Vencido',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Registrar Aviso'),
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