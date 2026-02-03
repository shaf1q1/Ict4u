<?php

namespace App\Models;

use CodeIgniter\Model;

class ApprovalDokumenModel extends Model
{
    protected $table            = 'aict4u106m_approval_dokumen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true; // Pastikan CI4 tahu id ini auto-increment
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'iddoc', 'status', 'approved_by', 'approved_at'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Mengambil data approval berserta maklumat dokumen menggunakan JOIN
     */
    public function getApprovalWithDoc($iddoc = null)
    {
        // Gunakan alias 'app' dan 'doc' supaya query lebih pendek dan structured
        $builder = $this->db->table($this->table . ' app');
        $builder->select('app.*, doc.nama as nama_dokumen, doc.namafail, doc.idservis, doc.status as status_asal');
        $builder->join('aict4u106mdoc doc', 'doc.iddoc = app.iddoc');

        if ($iddoc) {
            return $builder->where('app.iddoc', $iddoc)->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Fungsi Logic: Simpan status baru (Update jika wujud, Insert jika baru)
     * Ini memastikan row lain tak terkesan.
     */
    public function saveStatus(int $iddoc, array $data)
    {
        // Cari rekod sedia ada berdasarkan iddoc
        $existing = $this->where('iddoc', $iddoc)->first();

        if ($existing) {
            // Jika ada, UPDATE hanya pada primary key 'id' baris tersebut
            return $this->update($existing['id'], $data);
        }

        // Jika tiada, INSERT rekod baru (iddoc akan masuk sekali)
        $data['iddoc'] = $iddoc;
        return $this->insert($data);
    }
} 