<table>
    <thead>
        <tr>
            <th>indikator_id</th>
            <th>waktu</th>
            <th>jenis_karakteristik_id</th>
            <th>data</th>
        </tr>
    </thead>
    <tbody>
        @for ($year = (int) $waktu_mulai; $year <= (int) $waktu_selesai; $year++)
            @foreach ($jenis_karakteristik as $jk)
                <tr>
                    <td>{{ $indikator->id }}</td>
                    <td>{{ $year }}</td>
                    <td>{{ $jk->id }}</td>
                    <td></td>
                </tr>
            @endforeach
        @endfor
    </tbody>
</table>