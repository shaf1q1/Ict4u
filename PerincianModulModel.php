<?php

namespace App\Models;

use CodeIgniter\Model;

class PerincianModulModel extends Model
{
    protected $table      = 'aict4u103dperincianmodul';
    protected $primaryKey = 'id';

    // Pastikan semua field baru didaftarkan di sini supaya boleh di-insert/update
    protected $allowedFields = [
        'idservis',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'uploaded_by'
    ];

    // Mengaktifkan pengurusan masa automatik
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengaktifkan ciri Soft Deletes
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    /**
     * Dapatkan perincian berdasarkan ID servis
     */
    public function getByServis($idservis)
    {
        // Guna first() jika satu servis hanya ada satu perincian
        // Guna findAll() jika satu servis boleh ada banyak perincian
        return $this->where('idservis', $idservis)->first();
    }

    /**
     * Join dengan table servis untuk dapatkan maklumat penuh
     */
    public function getWithServis($id)
    {
        return $this->select('aict4u103dperincianmodul.*, aict4u103dservis.namaservis')
                    ->join('aict4u103dservis', 'aict4u103dservis.idservis = aict4u103dperincianmodul.idservis')
                    ->find($id);
    }
} 