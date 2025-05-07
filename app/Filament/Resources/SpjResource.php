<?php

namespace App\Filament\Resources;

use App\Models\Spj;
use Filament\Forms;
use Filament\Tables;
use App\Models\Satker;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Exports\SpjExporter;
use App\Filament\Imports\SpjImporter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Resources\SpjResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SpjResource\RelationManagers;
use App\Models\Kategori;

class SpjResource extends Resource
{
    protected static ?string $model = Spj::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Section::make()
                    ->description('Tuliskan keterangan SPJ Anda disini')
                    ->schema([
                        Select::make('kode_satker')
                            ->relationship('satker', 'kode_satker')
                            ->searchable(true)
                            ->getOptionLabelFromRecordUsing(fn($record) => "($record->kode_satker) $record->nama_satker")
                            ->getSearchResultsUsing(function (string $search) {
                                return Satker::query()
                                    ->where('satkers.kode_satker', 'like', "%{$search}%")
                                    ->orWhere('satkers.nama_satker', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn($satker) => [
                                        $satker->kode_satker => "($satker->kode_satker) $satker->nama_satker"
                                    ])
                                    ->toArray();
                            }),
                        TextInput::make('judul_spj')->label('Judul SPJ'),
                        Select::make('kode_kategori')
                            ->relationship('kategori', 'nama_kategori'),
                        TextArea::make('deskripsi'),
                        ToggleButtons::make('status')
                            ->gridDirection('row')->inline()
                            ->options([
                                '1' => 'Draft',
                                '2' => 'Siap Dikirim',
                                '3' => 'Terkirim',
                                '4' => 'Diterima',
                                '5' => 'Ditolak',
                            ])
                            ->icons([
                                '1' => 'heroicon-o-pencil',
                                '2' => 'heroicon-o-clock',
                                '3' => 'heroicon-o-check-circle',
                                '4' => 'heroicon-o-check-circle',
                                '5' => 'heroicon-o-check-circle',
                            ])
                            ->colors([
                                '1' => 'primary',
                                '2' => 'warning',
                                '3' => 'primary',
                                '4' => 'success',
                                '5' => 'danger',
                            ])
                            ->default('Draft') // bisa menetapkan status default
                            ->required(),
                        FileUpload::make('attachment')
                            ->label('Lampiran')
                            ->disk('public') // WAJIB, karena kita pakai storage:link
                            ->directory('attachments')
                            ->visibility('public')
                            ->downloadable()
                            ->previewable(true),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('judul_spj'),
                TextColumn::make('deskripsi'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'primary',
                        '2' => 'warning',
                        '3' => 'primary',
                        '4' => 'success',
                        '5' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'Draft',
                        '2' => 'Siap Dikirim',
                        '3' => 'Terkirim',
                        '4' => 'Diterima',
                        '5' => 'Ditolak',
                        default => 'Tidak Diketahui',
                    })                    ,
                ImageColumn::make('attachment')->label('Preview')
                    ->disk('public')  // Pastikan menggunakan disk 'public'
                    ->visibility('public')  // File harus dapat diakses secara publik
                    ->height(100)
                    ->width(100)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export')
                    ->exporter(SpjExporter::class),
                ImportAction::make()
                    ->importer(SpjImporter::class)
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
            'index' => Pages\ListSpjs::route('/'),
            'create' => Pages\CreateSpj::route('/create'),
            'edit' => Pages\EditSpj::route('/{record}/edit'),
        ];
    }
}
