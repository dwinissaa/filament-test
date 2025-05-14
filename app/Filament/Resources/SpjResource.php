<?php

namespace App\Filament\Resources;

use App\Models\Spj;
use Filament\Forms;
use Filament\Tables;
use App\Models\Satker;
use App\Models\Kategori;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Resources\SpjResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SpjResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SpjResource extends Resource
{
    protected static ?string $model = Spj::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->description('Tuliskan keterangan SPJ Anda disini')
                    ->schema([
                        Select::make('satker_id')
                            ->relationship('satker', 'satker_id')
                            ->searchable(true)
                            ->formatStateUsing(fn($record) => "($record->satker_id) {$record->satker->nama_satker}")
                            ->getSearchResultsUsing(function (string $search) {
                                return Satker::query()
                                    ->where('satkers.id', 'like', "%{$search}%")
                                    ->orWhere('satkers.nama_satker', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn($satker) => [
                                        $satker->id => "($satker->id) $satker->nama_satker"
                                    ])
                                    ->toArray();
                            })->required(),
                        TextInput::make('judul_spj')->label('Judul SPJ')->required(),
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
                        DatePicker::make('tanggal_spj')
                            ->native(false),
                        FileUpload::make('attachment')
                            ->disk('public')
                            ->directory('attachments')
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return (string) 'spj' . uniqid(more_entropy: true) . '.' . str($file->getClientOriginalExtension());
                            })
                            ->acceptedFileTypes(['application/pdf'])
                            ->downloadable(true)
                            // ->imagePreviewHeight('100')
                            // ->image()
                            ->minSize(5)
                            ->maxSize(3000)->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateActions([
                // Action::make('create')
                //     ->label('Buat SPJ')
                //     ->url('spjs/create')
                //     ->icon('heroicon-m-plus')
                //     ->button(),
            ])
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('satker_id')->formatStateUsing(fn($record) => "($record->satker_id) {$record->satker->nama_satker}"),
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
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        '1' => 'Draft',
                        '2' => 'Siap Dikirim',
                        '3' => 'Terkirim',
                        '4' => 'Diterima',
                        '5' => 'Ditolak',
                        default => 'Tidak Diketahui',
                    }),
                TextColumn::make('attachment')->label('Preview')
                    ->url(fn($record) => Storage::url($record->attachment))
                    ->formatStateUsing(fn($state) => '📄 Lihat PDF')
                    ->openUrlInNewTab()
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
                // Action::make('create')
                // ->label('Buataja SPJ')
                // ->url('spjs.create')
                // ->icon('heroicon-m-plus')
                // ->button()
                // ->visible(false),  // Menyembunyikan tombol jika tidak ada data
                ExportAction::make()
                    ->label('Export')
                    ->exporter(SpjExporter::class)
                    ->visible(Spj::query()->count() > 0),
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
