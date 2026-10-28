<?php

namespace App\Filament\Resources\Expedientes\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ComparecientesRelationManager extends RelationManager
{
    protected static string $relationship = 'expedienteComparecientes';

    protected static ?string $title = 'Comparecientes y Partes Intervinientes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'sm' => 2])
                    ->schema([
                        Select::make('cliente_id')
                            ->label('Cliente / Sujeto Obligado')
                            ->relationship('cliente', 'nombre_razon_social')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Selecciona el cliente compareciente')
                            ->suffixIcon('heroicon-o-user'),

                        Select::make('rol_compareciente')
                            ->label('Rol en el Acto Notarial')
                            ->options([
                                'comprador' => 'Comprador(a) / Adquirente',
                                'vendedor' => 'Vendedor(a) / Enajenante',
                                'acreditado' => 'Acreditado(a) / Mutuatario',
                                'garante_hipotecario' => 'Garante Hipotecario',
                                'apoderado' => 'Apoderado(a) Legal',
                                'conyuge' => 'Cónyuge con Consentimiento',
                            ])
                            ->required()
                            ->placeholder('Selecciona rol jurídico')
                            ->suffixIcon('heroicon-o-identification'),

                        Toggle::make('recibe_factura_catastro')
                            ->label('Recibe Factura / Comprobante Catastral')
                            ->default(false)
                            ->inline(false),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['cliente']))
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->recordTitleAttribute('rol_compareciente')
            ->columns([
                TextColumn::make('cliente.nombre_razon_social')
                    ->label('Nombre o Razón Social')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('cliente.rfc')
                    ->label('RFC')
                    ->copyable()
                    ->placeholder('-'),

                TextColumn::make('rol_compareciente')
                    ->label('Rol Jurídico')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'comprador' => 'success',
                        'vendedor' => 'info',
                        'acreditado' => 'warning',
                        'garante_hipotecario' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'comprador' => 'Comprador(a)',
                        'vendedor' => 'Vendedor(a)',
                        'acreditado' => 'Acreditado(a)',
                        'garante_hipotecario' => 'Garante Hipotecario',
                        'apoderado' => 'Apoderado(a)',
                        'conyuge' => 'Cónyuge',
                        default => $state,
                    }),

                IconColumn::make('recibe_factura_catastro')
                    ->label('Factura Catastro')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('rol_compareciente')
                    ->label('Filtrar por Rol')
                    ->options([
                        'comprador' => 'Comprador',
                        'vendedor' => 'Vendedor',
                        'acreditado' => 'Acreditado',
                        'garante_hipotecario' => 'Garante Hipotecario',
                        'apoderado' => 'Apoderado',
                        'conyuge' => 'Cónyuge',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Agregar Compareciente'),
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