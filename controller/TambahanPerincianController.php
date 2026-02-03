<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ServisModel;
use App\Models\PerincianModulModel;

class TambahanPerincianController extends BaseController
{
    protected $servisModel;
    protected $perincianModel;

    public function __construct()
    {
        $this->servisModel    = new ServisModel();
        $this->perincianModel = new PerincianModulModel();
        helper(['form', 'url']);
    }

    /**
     * MAIN PAGE
     */
    public function index()
    {
        return view('dashboard/pages/TambahanPerincian', [
            'title'      => 'Pengurusan Perincian Modul',
            'servisList' => $this->servisModel
                                ->orderBy('namaservis', 'ASC')
                                ->findAll()
        ]);
    }

    /**
     * GET SINGLE SERVIS + DESCRIPTION
     */
    public function getServis($id)
    {
        $servis = $this->servisModel->find($id);

        if (!$servis) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Servis tidak dijumpai'
            ]);
        }

        $desc = $this->perincianModel
                     ->where('idservis', $id)
                     ->first();

        return $this->response->setJSON([
            'status'      => true,
            'servis'      => $servis,
            'perincian'   => $desc ?? null
        ]);
    }

    /**
     * SAVE / UPDATE SERVIS + DESCRIPTION
     */
    public function saveServis()
    {
        $post = $this->request->getPost();

        $idservis    = $post['idservis'] ?? null;
        $namaservis  = trim($post['namaservis'] ?? '');
        $infourl     = trim($post['infourl'] ?? '');
        $mohonurl    = trim($post['mohonurl'] ?? '');
        $description = $post['description'] ?? '';

        if ($namaservis === '') {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Nama servis diperlukan'
            ]);
        }

        // SAVE / UPDATE SERVIS
        if ($idservis) {
            $this->servisModel->update($idservis, [
                'namaservis' => $namaservis,
                'infourl'    => $infourl,
                'mohonurl'   => $mohonurl
            ]);
        } else {
            $this->servisModel->insert([
                'namaservis' => $namaservis,
                'infourl'    => $infourl,
                'mohonurl'   => $mohonurl
            ]);
            $idservis = $this->servisModel->getInsertID();
        }

        // SAVE / UPDATE PERINCIAN
        $exist = $this->perincianModel->where('idservis', $idservis)->first();
        if ($exist) {
            $this->perincianModel->update($exist['id'], ['description' => $description]);
        } else {
            $this->perincianModel->insert([
                'idservis'    => $idservis,
                'description' => $description
            ]);
        }

        $servis = $this->servisModel->find($idservis);
        $perincian = $this->perincianModel->where('idservis', $idservis)->first();

        return $this->response->setJSON([
            'status'    => true,
            'message'   => 'Servis berjaya disimpan',
            'servis'    => $servis,
            'perincian' => $perincian
        ]);
    }

    /**
     * DELETE SERVIS + DESCRIPTION
     */
    public function deleteServis()
    {
        $idservis = $this->request->getPost('idservis');

        if (!$idservis) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'ID servis tidak sah'
            ]);
        }

        $this->servisModel->delete($idservis);
        $this->perincianModel->where('idservis', $idservis)->delete();

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Servis berjaya dipadam'
        ]);
    }

    /**
     * GET ALL SERVIS + DESCRIPTION
     */
    public function getAll()
    {
        $data = $this->servisModel->orderBy('namaservis', 'ASC')->findAll();

        foreach ($data as &$s) {
            // Find description for this specific service
            $desc = $this->perincianModel->where('idservis', $s['idservis'])->first();
            
            $s['infourl']   = $s['infourl'] ?? '';
            $s['mohonurl']  = $s['mohonurl'] ?? '';
            
            // Use the null coalescing operator to prevent errors if $desc is null
            $s['perincian'] = [
                'description' => $desc['description'] ?? ''
            ];
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $data
        ]);
    }
}
