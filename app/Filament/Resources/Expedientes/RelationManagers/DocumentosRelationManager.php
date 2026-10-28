<?php

namespace App\Filament\Resources\Expedientes\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DocumentosRelationManager extends RelationManager
{
    protected static string $relationship = 'expedienteDocumentos';

    protected static ?string $title = 'Cotejo y Expediente Digital';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'sm' => 2])
                    ->schema([
                        Select::make('requisito_id')
                            ->label('Requisito Documental')
                            ->relationship('requisito', 'nombre_documento')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Selecciona documento exigido')
                            ->suffixIcon('heroicon-o-document-check'),

                        DateTimePicker::make('fecha_recepcion')
                            ->label('Fecha y Hora de Recepción')
                            ->default(now())
                            ->suffixIcon('heroicon-o-calendar'),

                        Toggle::make('entregado_fisico')
                            ->label('Entregado en Físico (Original / Copia)')
                            ->default(false)
                            ->inline(false),

                        TextInput::make('archivo_digital_url')
                            ->label('Ruta / Archivo Escaneado PDF')
                            ->maxLength(255)
                            ->placeholder('ej. expedientes/2026/exp0145/identificacion_oficial.pdf')
                            ->suffixIcon('heroicon-o-paper-clip')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['requisito']))
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->recordTitleAttribute('requisito.nombre_documento')
            ->columns([
                TextColumn::make('requisito.nombre_documento')
                    ->label('Documento Requerido')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                IconColumn::make('entregado_fisico')
                    ->label('Físico')
                    ->boolean(),

                TextColumn::make('fecha_recepcion')
                    ->label('Fecha Recepción')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Pendiente'),

                TextColumn::make('archivo_digital_url')
                    ->label('Archivo Digital')
                    ->formatStateUsing(fn ($state) => $state ? 'PDF Adjunto' : 'Sin archivo')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),
            ])
            ->filters([
                TernaryFilter::make('entregado_fisico')
                    ->label('Entregado Físico')
                    ->placeholder('Todos'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Registrar Documento'),
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