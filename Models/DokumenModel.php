<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    // Table name
    protected $table = 'aict4u106mdoc';
    
    // Primary key
    protected $primaryKey = 'iddoc';
    
    // Allowed fields for insert/update
    protected $allowedFields = [
        'idservis',      // Kolom utama untuk servis
        'nama',          // Nama dokumen
        'namafail',      // Nama file
        'mime',          // MIME type
        'descdoc',       // Description
        'uploaded_by',   // User yang upload
        'status',        // Status dokumen
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
    ];
    
    // Enable automatic timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    // Enable soft deletes
    protected $useSoftDeletes = false;
    protected $deletedField  = 'deleted_at';

    // =========================
    // DOKUMEN STATUS CONSTANTS
    // =========================
    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    // =========================
    // OPTIONAL: Default validation rules
    // =========================

    protected $validationRules = [
    'nama'     => 'required|string|max_length[255]',
    'namafail' => 'permit_empty|string|max_length[255]', // Tukar ke permit_empty
    'mime'     => 'permit_empty|string|max_length[100]', // Tukar ke permit_empty
    'status'   => 'in_list[pending,approved,rejected]'
    ];

    protected $validationMessages = [
        'nama'     => ['required' => 'Nama dokumen harus diisi.'],
        'namafail' => ['required' => 'Nama file harus diisi.'],
        'mime'     => ['required' => 'MIME type harus diisi.'],
        'status'   => ['in_list' => 'Status dokumen harus salah satu: pending, approved, rejected.']
    ];
}
