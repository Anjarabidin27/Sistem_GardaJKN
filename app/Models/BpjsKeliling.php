<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpjsKeliling extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    const JENIS_KEGIATAN = [
        'goes_to_village'     => 'Goes To Village',
        'around_city'         => 'Around City',
        'hi_customer'         => 'Hi Customer',
        'corporate_gathering' => 'Corporate Gathering',
        'cfd'                 => 'CFD',
        'other'               => 'Lainnya',
    ];

    const KUADRAN = [
        '1- Engagement'       => 'Engagement',
        '2- Rekrutmen'       => 'Rekrutmen',
        '3- Pembaharuan Data' => 'Pembaharuan Data',
        '4- Iuran'            => 'Iuran',
    ];

    public function participants()
    {
        return $this->hasMany(BpjsKelilingParticipant::class, 'bpjs_keliling_id');
    }

    /**
     * Otomatis hitung ulang rekapitulasi dari tabel detail peserta
     */
    public function recalculateSummaries()
    {
        $participants = $this->participants;
        
        $this->update([
            'jumlah_peserta'       => $participants->count(),
            'layanan_administrasi' => $participants->where('jenis_layanan', 'Administrasi')->count(),
            'layanan_informasi'    => $participants->where('jenis_layanan', 'Informasi')->count(),
            'layanan_pengaduan'    => $participants->where('jenis_layanan', 'Pengaduan')->count(),
            'transaksi_berhasil'   => $participants->where('status', 'Berhasil')->count(),
            'transaksi_gagal'      => $participants->where('status', 'Tidak Berhasil')->count(),
            'kepuasan_puas'        => $participants->where('suara_pelanggan', 'Puas')->count(),
            'kepuasan_tidak_puas'  => $participants->where('suara_pelanggan', 'Tidak puas')->count(),
        ]);
    }

    public function provinsi()
    {
        return $this->belongsTo(Province::class, 'provinsi_id');
    }

    public function kota()
    {
        return $this->belongsTo(City::class, 'kota_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(District::class, 'kecamatan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(AdminUser::class, 'created_by');
    }

    public function getJenisKegiatanLabelAttribute(): string
    {
        return self::JENIS_KEGIATAN[$this->jenis_kegiatan] ?? $this->jenis_kegiatan;
    }

    public function getLokasILengkapAttribute(): string
    {
        return implode(', ', array_filter([
            $this->nama_desa,
            $this->kecamatan?->name,
            $this->kota?->name,
            $this->provinsi?->name,
        ]));
    }

    /**
     * Automatic Status Calculation based on BRD (Logic Step 9)
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'canceled') return 'Dibatalkan';
        if ($this->status === 'completed') return 'Selesai';

        $now = now();
        $tanggal = \Carbon\Carbon::parse($this->tanggal);
        
        if ($tanggal->isFuture()) return 'Terjadwal';
        if ($tanggal->isPast()) return 'Selesai';
        
        // If today, check hours
        $currentTime = $now->format('H:i:s');
        if($this->jam_mulai && $currentTime < $this->jam_mulai) return 'Terjadwal';
        if($this->jam_selesai && $currentTime > $this->jam_selesai) return 'Selesai';
        
        return 'Berlangsung';
    }
}
