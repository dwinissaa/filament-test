<?php

namespace App\Filament\Pages;

use Dom\Text;
use Carbon\Carbon;
use App\Models\Data;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Kategori;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Frekuensi;
use App\Models\Indikator;
use Filament\Tables\Table;
use App\Imports\DatasImport;
use App\Models\Karakteristik;
use Illuminate\Validation\Rule;
use App\Models\JenisKarakteristik;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Validator;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Components\Actions\Action as Act;
use Maatwebsite\Excel\Validators\ValidationException;

class ExploreData extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.explore-data';
    protected static ?string $navigationGroup = 'Explore Data';

    public $judul = null;
    public $datas = [
        'waktu_mulai' => null,
        'waktu_selesai' => null,
        'file' => null,
    ];

    public bool $showImport = false;

    public function mount(): void
    {
        $this->updateImportVisibility();
    }


    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Pilih Data')->schema([

                Grid::make([
                    'default' => 2,
                    'sm' => 2,
                    'md' => 2,
                    'lg' => 2,
                    'xl' => 2,
                    '2xl' => 2,
                ])->schema([
                            Select::make('kategori_id')
                                ->label('Kategori')
                                ->options(Kategori::all()
                                    ->pluck('nama_kategori', 'id'))
                                ->reactive()
                                ->required()
                                ->afterStateUpdated(function (Set $set) {
                                    $set('indikator_id', null);
                                    $set('jenis_karakteristik_id', null);
                                }),
                            Select::make('indikator_id')->options(
                                function (Get $get) {
                                    $kategoriId = $get('kategori_id');
                                    if (!$kategoriId)
                                        return [];
                                    return Indikator::query()
                                        ->where('kategori_id', '=', $kategoriId)
                                        ->pluck('nama_indikator', 'id');
                                }
                            )
                                ->reactive()
                                ->required(),
                            // Select::make('jenis_karakteristik_id')
                            //     ->options(
                            //         function (Get $get) {
                            //             $kategoriId = $get('kategori_id');
                            //             $indikatorId = $get('indikator_id');
                            //             if (!$kategoriId)
                            //                 return [];
                            //             if (!$indikatorId)
                            //                 return [];
                            //             $indikator = Indikator::find($indikatorId);
                            //             if ($indikator) {
                            //                 return JenisKarakteristik::query()
                            //                     ->where(
                            //                         'karakteristik_id',
                            //                         '=',
                            //                         $indikator->karakteristik_id
                            //                     )
                            //                     ->pluck('jenis_karakteristik', 'id');
                            //             } else {
                            //                 return [];
                            //             }
                            //         }
                            //     )->reactive()->required(),
                            Select::make('waktu_mulai')
                                ->label('Tahun Mulai')
                                ->options(
                                    collect(range(date('Y'), 2000))->mapWithKeys(fn($year) => [$year => $year])
                                )->searchable()
                                ->visible(function (Get $get) {
                                    $indikator = Indikator::find($get('indikator_id'));
                                    return strtolower(Frekuensi::find($indikator?->frekuensi_id)?->frekuensi) === 'tahunan';
                                })
                                ->reactive()
                                ->required(),
                            Select::make('waktu_selesai')
                                ->label('Tahun Selesai')
                                ->options(
                                    collect(range(date('Y'), 2000))->mapWithKeys(fn($year) => [$year => $year])
                                )->searchable()
                                ->visible(function (Get $get) {
                                    $indikator = Indikator::find($get('indikator_id'));
                                    return strtolower(Frekuensi::find($indikator?->frekuensi_id)?->frekuensi) === 'tahunan';
                                })
                                ->reactive()
                                ->required(),
                        ])
            ])->statePath('datas')->reactive(),
        ]);
    }


    public function getHeaderActions(): array
    {
        // dd($this->datas);
        $data_ = $this->datas;

        return [
            \Filament\Actions\Action::make('importData')
                ->form([
                    \Filament\Forms\Components\Actions::make([
                        \Filament\Forms\Components\Actions\Action::make('downloadTemplate')
                            ->label('Download Template')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->link()
                            ->url(function () use ($data_) {
                                return route('download.template', [
                                    'indikator_id' => $data_['indikator_id'] ?? null,
                                    'waktu_mulai' => $data_['waktu_mulai'] ?? null,
                                    'waktu_selesai' => $data_['waktu_selesai'] ?? null,
                                ]);
                            }) // arahkan ke route download
                            ->openUrlInNewTab(),
                    ]),
                    FileUpload::make('file')
                        ->label('File Excel')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            '.xlsx',
                            '.xls',
                        ]),
                ])
                ->visible(fn() => $this->showImport)
                ->action(function (array $data) use ($data_) {
                    // dd($data);
                    $filePath = $data['file'];
                    // Insert!
                    try {
                        // Data::insert($validated);
                        Excel::import(new DatasImport(Indikator::find($data_['indikator_id'])), storage_path("app/public/{$filePath}"));

                        Notification::make()
                            ->title('Data Berhasil Disimpan!')
                            ->success()
                            ->color('success')
                            ->send();
                    } catch (\Throwable $th) {

                        if ($th instanceof ValidationException) {
                            $failures = $th->failures();

                            $messages = collect($failures)->map(function ($failure) {
                                return '- Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
                            })->implode("\n"); // newline di notifikasi Filament
                        }

                        Notification::make()
                            ->title('Data Gagal Disimpan!')
                            ->body($messages)
                            ->danger()
                            ->color('danger')
                            ->send();
                    }
                }),
        ];
    }

    public function updatedDatasIndikatorId($value)
    {
        $indikator = Indikator::find($value);
        $this->judul = $indikator?->nama_indikator ?? null;
        $this->updateImportVisibility();
    }

    public function updatedDatasWaktuMulai()
    {
        $this->updateImportVisibility();
    }

    public function updatedDatasWaktuSelesai()
    {
        $this->updateImportVisibility();
    }

    public function updateImportVisibility()
    {
        $this->showImport =
            filled($this->datas['indikator_id'] ?? null) &&
            filled($this->datas['waktu_mulai'] ?? null) &&
            filled($this->datas['waktu_selesai'] ?? null);
    }



    public function table(Table $table): Table
    {

        return $table
            ->query(function () {
                $query = Data::query();

                if (!empty($this->datas['indikator_id'])) {
                    $query->where('indikator_id', '=', $this->datas['indikator_id']);
                }

                return $query->orderBy('waktu')->orderBy('jenis_karakteristik_id'); // ganti dengan kolom yang memang ada;
    
            })
            ->columns([
                TextColumn::make('waktu')->sortable(),
                TextColumn::make('jenis_karakteristik.jenis_karakteristik')->sortable(),
                TextColumn::make('data')->sortable(),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('info')
                    ->mountUsing(function ($form, $record) {
                        $data_ = Data::where('indikator_id', $record->indikator_id)
                            ->where('jenis_karakteristik_id', $record->jenis_karakteristik_id)
                            ->where('waktu', $record->waktu)
                            ->first();

                        $form->fill([
                            'id' => $data_->id,
                            'indikator_id' => $data_->indikator->nama_indikator,
                            'jenis_karakteristik_id' => $data_->jenis_karakteristik->jenis_karakteristik,
                            'waktu' => $data_->waktu,
                            'data' => $data_->data,
                        ]);
                    })
                    ->form([
                        Hidden::make('id'),
                        TextInput::make('indikator_id')->disabled(),
                        TextInput::make('jenis_karakteristik_id')->disabled(),
                        TextInput::make('waktu')->disabled(),
                        TextInput::make('data')->required(),
                    ])
                    ->action(function (array $data) {
                        Data::where('id', $data['id'])->update([
                            'data' => $data['data'],
                        ]);

                        Notification::make()
                            ->title('Data berhasil diupdate')
                            ->success()
                            ->send();
                    }),

                Action::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn($record) => $this->delete($record)),
            ]);
    }

    public function delete($record)
    {
        Data::where('indikator_id', $record->indikator_id)
            ->where('jenis_karakteristik_id', $record->jenis_karakteristik_id)
            ->where('waktu', '=', $record->waktu)
            ->delete();

        Notification::make()
            ->title('Data Berhasil Dihapus')
            ->success()
            ->send();
    }



}
