<?php

namespace App\Filament\Resources\Clientes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ClientesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('nombre_razon_social')
                    ->label('Nombre o Razón Social')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('tipo_persona')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'moral' => 'Moral',
                        default => 'Física',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'moral' => 'info',
                        default => 'success',
                    }),

                TextColumn::make('rfc')
                    ->label('RFC')
                    ->searchable()
                    ->copyable()
                    ->placeholder('-'),

                TextColumn::make('curp')
                    ->label('CURP')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('-'),

                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->copyable(),

                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->copyable()
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tipo_persona')
                    ->label('Tipo de Persona')
                    ->options([
                        'fisica' => 'Persona Física',
                        'moral' => 'Persona Moral',
                    ])
                    ->placeholder('Todos'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}