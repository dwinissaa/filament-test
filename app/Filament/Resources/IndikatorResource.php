<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Satker;
use App\Models\Kategori;
use Filament\Forms\Form;
use App\Models\Frekuensi;
use App\Models\Indikator;
use Filament\Tables\Table;
use App\Models\Karakteristik;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\IndikatorResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\IndikatorResource\RelationManagers;

class IndikatorResource extends Resource
{
    protected static ?string $model = Indikator::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Play Data';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('satker_id')->options(Satker::all()->pluck('nama_satker', 'id')->toArray())->getOptionLabelsUsing(fn($record) => "($record->id) $record->nama_satker"),
                Select::make('kategori_id')->options(Kategori::all()->pluck('nama_kategori', 'id')->toArray())->getOptionLabelsUsing(fn($record) => "($record->id) $record->nama_kategori"),
                TextInput::make('nama_indikator'),
                Select::make('karakteristik_id')->options(Karakteristik::all()->pluck('nama_karakteristik', 'id')->toArray()),
                Select::make('tipe_indikator')->options(options: ['string' => 'string', 'integer' => 'integer', 'float' => 'float']),
                Select::make('frekuensi_id')->options(Frekuensi::all()->pluck('frekuensi', 'id')->toArray()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('satker_id')
                    ->getStateUsing(fn($record) => "({$record->satker_id}) {$record->satker->nama_satker}"),
                TextColumn::make('nama_indikator')->searchable(isIndividual: true),
                TextColumn::make('karakteristik.nama_karakteristik'),
                TextColumn::make('tipe_indikator'),
                TextColumn::make('frekuensi.frekuensi'),

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
            'index' => Pages\ListIndikators::route('/'),
            'create' => Pages\CreateIndikator::route('/create'),
            'edit' => Pages\EditIndikator::route('/{record}/edit'),
        ];
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->with(['satker', 'karakteristik', 'frekuensi']);
    // }
}
