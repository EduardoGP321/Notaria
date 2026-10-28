<?php

namespace App\Filament\Resources\Escrituras\RelationManagers;

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
use Illuminate\Database\Eloquent\Builder;

class TestimoniosRelationManager extends RelationManager
{
    protected static string $relationship = 'testimoniosExpedidos';

    protected static ?string $title = 'Testimonios y Copias Certificadas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                    ->schema([
                        Select::make('tipo_documento')
                            ->label('Tipo de Documento')
                            ->options([
                                'primer_testimonio' => 'Primer Testimonio',
                                'segundo_testimonio' => 'Segundo Testimonio',
                                'copia_certificada' => 'Copia Certificada',
                                'copia_simple' => 'Copia Simple',
                            ])
                            ->default('primer_testimonio')
                            ->required()
                            ->suffixIcon('heroicon-o-document-duplicate'),

                        Select::make('destinatario_tipo')
                            ->label('Destinatario Oficial')
                            ->options([
                                'adquirente_cliente' => 'Cliente Adquirente',
                                'institucion_credito' => 'Institución de Crédito',
                                'archivo_notaria' => 'Archivo de la Notaría',
                                'juzgado' => 'Autoridad Judicial / Juzgado',
                            ])
                            ->default('adquirente_cliente')
                            ->required()
                            ->suffixIcon('heroicon-o-building-office'),

                        Select::make('cliente_id')
                            ->label('Cliente que Recibe')
                            ->relationship('cliente', 'nombre_razon_social')
                            ->searchable()
                            ->preload()
                            ->placeholder('Selecciona el cliente')
                            ->suffixIcon('heroicon-o-user'),

                        TextInput::make('numero_fojas')
                            ->label('Número de Fojas')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->placeholder('ej. 18')
                            ->suffixIcon('heroicon-o-document-text'),

                        DatePicker::make('fecha_expedicion')
                            ->label('Fecha de Expedición')
                            ->required()
                            ->default(now())
                            ->placeholder('ej. 2026-10-27')
                            ->suffixIcon('heroicon-o-calendar'),

                        DatePicker::make('fecha_entrega')
                            ->label('Fecha de Entrega')
                            ->placeholder('ej. 2026-11-05')
                            ->suffixIcon('heroicon-o-calendar-days'),

                        TextInput::make('persona_recibe_nombre')
                            ->label('Nombre de Quien Recibe')
                            ->maxLength(150)
                            ->placeholder('ej. Lic. Alejandro Torres Mendoza')
                            ->suffixIcon('heroicon-o-identification')
                            ->columnSpan(['default' => 1, 'sm' => 2]),

                        Select::make('usuario_entrega_id')
                            ->label('Colaborador que Entrega')
                            ->relationship('usuarioEntrega', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Selecciona colaborador')
                            ->suffixIcon('heroicon-o-academic-cap'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['cliente', 'usuarioEntrega']))
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->recordTitleAttribute('tipo_documento')
            ->columns([
                TextColumn::make('tipo_documento')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'primer_testimonio' => '1er Testimonio',
                        'segundo_testimonio' => '2do Testimonio',
                        'copia_certificada' => 'Copia Certificada',
                        'copia_simple' => 'Copia Simple',
                        default => $state,
                    }),

                TextColumn::make('destinatario_tipo')
                    ->label('Destinatario')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'adquirente_cliente' => 'Adquirente',
                        'institucion_credito' => 'Banco/Instituto',
                        'archivo_notaria' => 'Archivo',
                        'juzgado' => 'Juzgado',
                        default => $state,
                    }),

                TextColumn::make('numero_fojas')
                    ->label('Fojas'),

                TextColumn::make('fecha_expedicion')
                    ->label('Expedición')
                    ->date('d/m/Y'),

                TextColumn::make('fecha_entrega')
                    ->label('Entrega')
                    ->date('d/m/Y')
                    ->placeholder('Pendiente'),

                TextColumn::make('persona_recibe_nombre')
                    ->label('Recibió')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('tipo_documento')
                    ->label('Tipo de Documento')
                    ->options([
                        'primer_testimonio' => 'Primer Testimonio',
                        'segundo_testimonio' => 'Segundo Testimonio',
                        'copia_certificada' => 'Copia Certificada',
                        'copia_simple' => 'Copia Simple',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Expedir Testimonio'),
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