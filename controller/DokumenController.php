<?php

namespace App\Controllers;

use App\Models\DokumenModel;
use App\Models\ServisModel;
use CodeIgniter\API\ResponseTrait;

class DokumenController extends BaseController
{
    use ResponseTrait;

    protected $dokumenModel;
    protected $servisModel;

    public function __construct()
    {
        helper(['url', 'form', 'filesystem']);
        $this->dokumenModel = new DokumenModel();
        $this->servisModel  = new ServisModel();
        date_default_timezone_set('Asia/Kuala_Lumpur');
    }

    public function index()
    {
        $data['servis'] = $this->servisModel->findAll();
        // Pastikan path view ini betul mengikut folder anda
        return view('dashboard/pages/pengurusan_dokumen', $data);
    }

    public function getDokumen($idservis)
    {
        $dokumen = $this->dokumenModel
            ->where('idservis', $idservis)
            ->orderBy('updated_at', 'DESC')
            ->findAll(); 

        return $this->response->setJSON([
            'status' => true,
            'items'  => $dokumen
        ]);
    }

    public function tambah()
    {
        try {
            $idservis = $this->request->getPost('idservis');
            $nama     = $this->request->getPost('nama');
            $descdoc  = $this->request->getPost('descdoc');
            $file     = $this->request->getFile('file');

            // 1. Validasi Input Asas
            if (empty($idservis) || empty($nama) || !$file || !$file->isValid()) {
                return $this->response->setJSON(['status' => false, 'msg' => 'Maklumat tidak lengkap atau fail tidak sah.']);
            }

            // 2. Validasi Server-side (Security Check)
            if ($file->getMimeType() !== 'application/pdf') {
                return $this->response->setJSON(['status' => false, 'msg' => 'Hanya fail format PDF sahaja dibenarkan.']);
            }

            if ($file->getSizeByUnit('mb') > 10) {
                return $this->response->setJSON(['status' => false, 'msg' => 'Saiz fail melebihi had 10MB.']);
            }

            $uploadPath = WRITEPATH . "uploads/dokumen/{$idservis}/";
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

            $db = \Config\Database::connect();
            $db->transStart();

            $iddoc = $this->dokumenModel->insert([
                'idservis' => $idservis,
                'nama'     => $nama,
                'descdoc'  => $descdoc,
                'status'   => 'pending',
                'namafail' => '', 
                'mime'     => $file->getClientMimeType()
            ], true);

            // Nama fail unik: ID_Timestamp.pdf
            $newFileName = $iddoc . '_' . time() . '.' . $file->getExtension();

            if ($file->move($uploadPath, $newFileName)) {
                $this->dokumenModel->update($iddoc, ['namafail' => $newFileName]);
                $db->transComplete();

                if ($db->transStatus() === false) {
                    return $this->response->setJSON(['status' => false, 'msg' => 'Gagal mengemaskini rekod pangkalan data.']);
                }

                return $this->response->setJSON(['status' => true, 'msg' => 'Dokumen berjaya dimuat naik.']);
            } else {
                $db->transRollback();
                return $this->response->setJSON(['status' => false, 'msg' => 'Gagal mengalihkan fail ke storan.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => false, 'msg' => 'Ralat: ' . $e->getMessage()]);
        }
    }

    public function edit($iddoc)
    {
        $data = $this->dokumenModel->find($iddoc);
        return $this->response->setJSON([
            'status' => !empty($data),
            'data'   => $data
        ]);
    }

    public function kemaskini($iddoc)
    {
        try {
            $dokumen = $this->dokumenModel->find($iddoc);
            if (!$dokumen) return $this->response->setJSON(['status' => false, 'msg' => 'Dokumen tidak dijumpai.']);

            $updateData = [
                'nama'    => $this->request->getPost('nama'),
                'descdoc' => $this->request->getPost('descdoc'),
                'status'  => $this->request->getPost('status') ?? $dokumen['status']
            ];

            $file = $this->request->getFile('file');
            
            // Proses jika ada fail baru dimuat naik
            if ($file && $file->isValid() && !$file->hasMoved()) {
                
                // Validasi fail baru
                if ($file->getMimeType() !== 'application/pdf') {
                    return $this->response->setJSON(['status' => false, 'msg' => 'Hanya PDF sahaja dibenarkan.']);
                }

                $uploadPath = WRITEPATH . "uploads/dokumen/{$dokumen['idservis']}/";
                
                // Padam fail lama jika wujud
                if (!empty($dokumen['namafail']) && file_exists($uploadPath . $dokumen['namafail'])) {
                    unlink($uploadPath . $dokumen['namafail']);
                }

                $newFileName = $iddoc . '_' . time() . '.' . $file->getExtension();
                $file->move($uploadPath, $newFileName);
                
                $updateData['namafail'] = $newFileName;
                $updateData['mime']     = $file->getClientMimeType();
            }

            if ($this->dokumenModel->update($iddoc, $updateData)) {
                return $this->response->setJSON(['status' => true, 'msg' => 'Dokumen berjaya dikemaskini.']);
            }
            
            return $this->response->setJSON(['status' => false, 'msg' => 'Tiada perubahan dilakukan.']);

        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => false, 'msg' => 'Ralat: ' . $e->getMessage()]);
        }
    }

    public function hapus($iddoc)
    {
        try {
            $dokumen = $this->dokumenModel->find($iddoc);
            if (!$dokumen) {
                return $this->response->setJSON(['status' => false, 'msg' => 'Dokumen tidak dijumpai.']);
            }

            // 1. Padam fail fizikal secara kekal
            $filePath = WRITEPATH . "uploads/dokumen/{$dokumen['idservis']}/{$dokumen['namafail']}";
            if (!empty($dokumen['namafail']) && file_exists($filePath)) {
                unlink($filePath);
            }

            // 2. Padam dari database (Hard Delete)
            if ($this->dokumenModel->delete($iddoc, true)) {
                return $this->response->setJSON(['status' => true, 'msg' => 'Dokumen dan fail telah dipadam sepenuhnya.']);
            }

            return $this->response->setJSON(['status' => false, 'msg' => 'Gagal memadam rekod pangkalan data.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => false, 'msg' => 'Ralat: ' . $e->getMessage()]);
        }
    }

    public function viewFile($idservis, $filename)
    {
        $path = WRITEPATH . "uploads/dokumen/{$idservis}/{$filename}";
        
        if (!file_exists($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Fail tidak wujud.");
        }

        $mimeType = mime_content_type($path);

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody(file_get_contents($path));
    }
}