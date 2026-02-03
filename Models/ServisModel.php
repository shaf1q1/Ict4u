<?php
namespace App\Models;

use CodeIgniter\Model;

class ServisModel extends Model
{
    protected $table = 'aict4u103dservis';
    protected $primaryKey = 'idservis';

    protected $allowedFields = [
        'namaservis',
        'infourl',
        'mohonurl',
        'status',
        'created_by',
        'created_at',
        'updated_at',
        'imejkad',
        'icon_path'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}