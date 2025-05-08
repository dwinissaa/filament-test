<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Karakteristik;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KarakteristikResource\Pages;
use App\Filament\Resources\KarakteristikResource\RelationManagers;

class KarakteristikResource extends Resource
{
    protected static ?string $model = Karakteristik::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Play Data';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id')->readOnly(),
                TextInput::make('nama_karakteristik')->required(),
                Repeater::make('jenis_karakteristik')
                    ->relationship()
                    ->grid([
                        'default' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->required()
                    ->schema([
                        Hidden::make('id')
                        ->columnSpan([
                            'default' => 1,
                            'md' => 1,
                            'lg' => 1,
                            'xl' => 1,
                            '2xl' => 1,
                        ]),
                        TextInput::make('jenis_karakteristik')
                        ->columnSpan([
                            'default' => 2,
                            'md' => 2,
                            'lg' => 2,
                            'xl' => 2,
                            '2xl' => 2,
                        ])
                        ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('nama_karakteristik'),
                TextColumn::make('jenis_karakteristik_id')
                    ->getStateUsing(fn($record) => $record->jenis_karakteristik->pluck('jenis_karakteristik')->implode(',')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKarakteristiks::route('/'),
            'create' => Pages\CreateKarakteristik::route('/create'),
            'edit' => Pages\EditKarakteristik::route('/{record}/edit'),
        ];
    }
}
