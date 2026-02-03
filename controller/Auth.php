<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // --- 1. AUTHENTICATION METHODS ---

    public function register()
    {
        return view('form/register');
    }

    public function attemptRegister()
    {
        $rules = [
            'fullname' => 'required|min_length[3]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error_pw', 'Sila semak maklumat pendaftaran anda.');
        }

        $model = new UserModel();
        $model->save([
            'fullname' => $this->request->getPost('fullname'),
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ]);

        return redirect()->to('/login')->with('success', 'Akaun berjaya dicipta. Sila log masuk.');
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('form/login');
    }

    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new UserModel();
        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'user_id'     => $user['id'],
                'fullname'    => $user['fullname'],
                'email'       => $user['email'],
                'profile_pic' => $user['profile_pic'], 
                'isLoggedIn'  => true
            ]);
            return redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error_pw', 'Emel atau kata laluan salah.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // --- 2. PROFILE MANAGEMENT METHODS ---

    public function profile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $model = new UserModel();
        $data['user'] = $model->find(session()->get('user_id'));

        return view('form/profile', $data);
    }

    public function updateProfile()
    {
        $model = new UserModel();
        $id = session()->get('user_id');
        $user = $model->find($id);

        $rules = [
            'fullname' => 'required|min_length[3]',
            'email'    => "required|valid_email|is_unique[users.email,id,$id]",
            'profile_pic' => 'is_image[profile_pic]|mime_in[profile_pic,image/jpg,image/jpeg,image/png]|max_size[profile_pic,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error_pw', 'Input tidak sah atau fail terlalu besar.');
        }

        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'email'    => $this->request->getPost('email'),
        ];

        $file = $this->request->getFile('profile_pic');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = WRITEPATH . 'uploads/profile/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if ($user['profile_pic'] && file_exists($uploadPath . $user['profile_pic'])) {
                unlink($uploadPath . $user['profile_pic']);
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            
            $data['profile_pic'] = $newName;
            session()->set('profile_pic', $newName);
        }

        $model->update($id, $data);
        session()->set('fullname', $data['fullname']);

        return redirect()->back()->with('success', 'Profil berjaya dikemaskini.');
    }

    public function updatePassword()
    {
        $model = new UserModel();
        $id = session()->get('user_id');
        $user = $model->find($id);

        $rules = [
            'old_password'  => 'required',
            'new_password'  => 'required|min_length[6]',
            'conf_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error_pw', 'Sila pastikan kata laluan sepadan dan minima 6 aksara.');
        }

        if (!password_verify($this->request->getPost('old_password'), $user['password'])) {
            return redirect()->back()->with('error_pw', 'Kata laluan lama anda salah.');
        }

        $model->update($id, [
            'password' => $this->request->getPost('new_password')
        ]);

        return redirect()->back()->with('success', 'Kata laluan berjaya ditukar.');
    }

    public function getFile($filename)
    {
        $path = rtrim(WRITEPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR . $filename;
        
        if (!file_exists($path)) {
            log_message('error', 'Fail tidak dijumpai di: ' . $path);
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Fail $filename tiada.");
        }

        $file = new \CodeIgniter\Files\File($path);
        $type = $file->getMimeType();

        return $this->response
                    ->setHeader('Content-Type', $type)
                    ->setHeader('Content-Length', filesize($path))
                    ->setBody(readfile($path));
    }

    public function deleteProfilePic()
    {
        $model = new UserModel();
        $id = session()->get('user_id');
        $user = $model->find($id);

        if ($user && $user['profile_pic']) {
            $path = WRITEPATH . 'uploads/profile/' . $user['profile_pic'];
            if (file_exists($path)) {
                unlink($path);
            }
            $model->update($id, ['profile_pic' => null]);
            session()->set('profile_pic', null);
            return redirect()->back()->with('success', 'Gambar profil berjaya dipadam.');
        }
        return redirect()->back();
    }
    
    // --- 3. DIRECT PASSWORD RESET (NO EMAIL REQUIRED) ---

    /**
     * Display the direct reset form
     */
    public function forgotPassword()
    {
        return view('form/forgot_password');
    }

    /**
     * Handle the direct password update via Email input
     */
    public function attemptDirectReset()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $conf_password = $this->request->getPost('confirmpassword');

        // 1. Validation Rules
        $rules = [
            'email'           => 'required|valid_email',
            'password'        => 'required|min_length[6]',
            'confirmpassword' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error_pw', 'Pastikan emel sah, kata laluan minima 6 aksara dan sepadan.');
        }

        $model = new UserModel();
        $user = $model->where('email', $email)->first();

        // 2. Check if user exists in the database
        if (!$user) {
            return redirect()->back()->withInput()->with('error_pw', 'Emel tidak dijumpai dalam sistem.');
        }

        // 3. Update the password immediately
        // Important: Your UserModel must handle password hashing in beforeUpdate
        $model->update($user['id'], [
            'password'         => $password, 
            'reset_token'      => null,
            'reset_expires_at' => null
        ]);

        return redirect()->to('/login')->with('success', 'Kata laluan berjaya ditukar secara terus. Sila log masuk.');
    }
}