<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ServisModel;
use App\Models\ModulDescModel;

class PerincianModulController extends BaseController
{
    protected $servisModel;
    protected $descModel;

    public function __construct()
    {
        helper(['url', 'form']);
        $this->servisModel = new ServisModel();
        $this->descModel   = new ModulDescModel();
    }

    /** * Papar halaman utama 
     */
    public function index()
    {
        // Mengambil semua servis
        $servis = $this->servisModel->orderBy('namaservis', 'ASC')->findAll();
        
        // Hantar data ke view. 
        // Pastikan di dalam view 'perincianapp', anda menggunakan foreach($servisList as $s)
        return view('dashboard/pages/perincianapp', [
            'servisList' => $servis
        ]);
    }

    /** * Ambil data servis + description melalui AJAX (untuk Reset & Populate Form)
     */
    public function getServis($idservis)
    {
        $servis = $this->servisModel->find($idservis);

        if (!$servis) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => false,
                'message' => 'Servis tidak ditemui.'
            ]);
        }

        // Ambil description dari table modul_desc (menggunakan 108idservis sebagai foreign key)
        $desc = $this->descModel->where('108idservis', $idservis)->first();
        
        // Optional: Jika anda perlukan data status dokumen (seperti dalam kod asal anda)
        $dokumenModel = model('App\Models\ApprovalDokumenModel');
        $dokumen = $dokumenModel->where('id', $idservis)->findAll();

        $statusSummary = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'total' => count($dokumen)
        ];

        foreach($dokumen as $d){
            if(isset($statusSummary[$d['status']])) $statusSummary[$d['status']]++;
        }

        return $this->response->setJSON([
            'status'         => true,
            'servis'         => $servis,
            'desc'           => $desc,
            'dokumen_status' => $statusSummary,
            'namaservis'     => $servis['namaservis']
        ]);
    }

/** * Simpan atau Kemaskini data (Action dari Form)
     */
    public function save()
    {
        $idservis    = $this->request->getPost('idservis');
        $namaservis  = trim($this->request->getPost('namaservis'));
        $infourl     = trim($this->request->getPost('infourl'));
        $mohonurl    = trim($this->request->getPost('mohonurl'));
        $description = trim($this->request->getPost('description'));

        $errors = [];

        // 1. VALIDASI ID SERVIS
        if (!$idservis || !$this->servisModel->find($idservis)) {
            $errors[] = 'ID Servis tidak sah atau tidak dipilih.';
        }

        // 2. VALIDASI NAMA SERVIS (Perkara 1.1)
        // Regex PHP untuk Keyboard Characters sahaja (ASCII 32-126)
        $keyboardRegex = '/^[\x20-\x7E]*$/'; 
        
        if (empty($namaservis)) {
            $errors[] = 'Nama servis wajib diisi.';
        } elseif (mb_strlen($namaservis) > 145) {
            $errors[] = 'Nama servis tidak boleh melebihi 145 aksara.';
        } elseif (!preg_match($keyboardRegex, $namaservis)) {
            $errors[] = 'Nama servis mengandungi aksara yang tidak dibenarkan (Hanya guna aksara papan kekunci standard).';
        }

        // 3. VALIDASI URL (Perkara 1.2 & 1.3)
        // Kita gunakan regex manual kerana filter_var FILTER_VALIDATE_URL kadang-kadang terlepas protokol ftp
        $urlRegex = '/^(https?|ftp):\/\/[^\s\/$.?#].[^\s]*$/i';

        if (!empty($infourl)) {
            if (!preg_match($urlRegex, $infourl)) {
                $errors[] = 'Format Info URL tidak sah. Mesti bermula dengan http://, https:// atau ftp://';
            }
        }

        if (!empty($mohonurl)) {
            if (!preg_match($urlRegex, $mohonurl)) {
                $errors[] = 'Format Mohon URL tidak sah. Mesti bermula dengan http://, https:// atau ftp://';
            }
        }

        // 4. VALIDASI DESCRIPTION
        if (empty($description) || $description == '&nbsp;') {
            $errors[] = 'Description/Perincian wajib diisi.';
        }

        // Jika ada ralat, kembali ke form dengan mesej ralat
        if (!empty($errors)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', implode('<br>', $errors));
        }

        // ===== PROSES KEMASKINI =====
        try {
            // 1. Update Table Servis
            $this->servisModel->update($idservis, [
                'namaservis' => $namaservis,
                'infourl'    => $infourl ?: null,
                'mohonurl'   => $mohonurl ?: null
            ]);

            // 2. Update/Insert Table Description
            $existingDesc = $this->descModel->where('108idservis', $idservis)->first();
            
            if ($existingDesc) {
                $this->descModel->update($existingDesc['iddesc'], [
                    'description' => $description
                ]);
            } else {
                $this->descModel->insert([
                    '108idservis' => $idservis,
                    'description' => $description
                ]);
            }

            session()->setFlashdata('success', 'Maklumat servis berjaya dikemaskini.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ralat Sistem: ' . $e->getMessage());
        }

        return redirect()->to('/perincianmodul');
    }

    /** * Padam servis (Jika perlu)
     */
    public function delete($idservis)
    {
        if (!$idservis || !$this->servisModel->find($idservis)) {
            return redirect()->back()->with('error', 'Servis tidak ditemui.');
        }

        // Padam servis & description (Jika model guna SoftDelete, ia akan ikut rules model)
        $this->servisModel->delete($idservis);
        $this->descModel->where('108idservis', $idservis)->delete();

        return redirect()->back()->with('success', 'Servis berjaya dipadam.');
    }
}