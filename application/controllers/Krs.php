<?php
class Krs extends CI_Controller
{

    public function index()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            redirect('sso_hmj');
        }
    }

    public function getUbah($nim, $th, $smtr)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['title'] = "KRS - Update Data Mahasiswa";
            $this->data['active'] = "11";
            $id = $_SESSION['user_id'];
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";
            $this->load->model('All_model');
            $this->data['group'] = $this->ion_auth_model->getGroup($id);
            unset($_SESSION['sukses']);
            $data['th'] = $this->All_model->getThn();
            $data['prodis'] = [
                [
                    'id' => 'PTI',
                    'prodi' => 'Pendidikan Teknik Informatika'
                ],
                [
                    'id' => 'SI',
                    'prodi' => 'Sistem Informatika'
                ],
                [
                    'id' => 'ILKOM',
                    'prodi' => 'Ilmu Komputer'
                ],
                [
                    'id' => 'MI',
                    'prodi' => 'Manajemen Informasi'
                ]
            ];
            $data['datas'] = $this->All_model->getMahasiswaById($nim);
            $data['datas2'] = $this->All_model->getSmtr($nim, $th, $smtr);
            //var_dump($data['datas2']);
            $this->load->view("admin/master/header", $this->data);
            $this->load->view("admin/page/krs/m_ubah", $data);
            $this->load->view("admin/master/footer", $this->data);
        }
    }

    public function ubahData($id, $th, $smtr)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            if ($this->input->post('submit') === '') {
                // var_dump($this->All_model->updSmtr($id, $th, $smtr));
                // die;
                if ($this->All_model->updData($id)) {
                    $this->All_model->updSmtr($id, $th, $smtr);
                    $info = [
                        'id-info' => 1,
                        'info' => date('j F Y'),
                        'ket' => date('G \: i \: s')
                    ];
                    $this->All_model->updInfo($info);
                    unset($_SESSION['flash']);
                    $this->session->set_flashdata('sukses', 'Diubah');
                    redirect('krs');
                } else {
                    $this->session->set_flashdata('flash', 'Gagal diubah');
                    redirect('krs/getUbah/' . $id . '/' . $th . '/' . $smtr);
                }
            }
        }
    }


    public function tambah_tahun()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['title'] = "KRS - Tambah Tahun";
            $this->data['active'] = "11";
            $id = $_SESSION['user_id'];
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";
            $data['siswa'] = $this->All_model->getThn();

            unset($_SESSION['sukses']);
            unset($_SESSION['suksesth']);
            $this->data['group'] = $this->ion_auth_model->getGroup($id);
            $this->load->model('All_model');
            $this->load->view("admin/master/header", $this->data);
            $this->load->view("admin/page/krs/tahun", $data);
            $this->load->view("admin/master/footer", $this->data);

            if ($this->input->post('submit') === '') {
                $data = [
                    'id-th' => '',
                    'tahun' => $this->input->post('tahun', true),
                    'ket' => $this->input->post('ket', true)
                ];
                if ($this->All_model->addThn($data)) {
                    $this->session->set_flashdata('suksesth', 'Diubah');
                    unset($_SESSION['flashth']);
                    redirect('krs');
                    $info = [
                        'id-info' => 1,
                        'info' => date('j F Y'),
                        'ket' => date('G \: i \: s')
                    ];
                    $this->All_model->updInfo($info);
                } else {
                    $this->session->set_flashdata('flashth', 'Gagal ditambah');
                    redirect('krs/tambah_tahun');
                }
            }
        }
    }

    public function ubahTahun($tahun)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['title'] = "KRS - Ubah Tahun";
            $this->data['active'] = "11";
            $id = $_SESSION['user_id'];
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";
            $this->load->model('All_model');
            $data['siswa'] = $this->All_model->getThn();

            $data['isi'] = $this->All_model->getoneThn($tahun);
            unset($_SESSION['sukses']);
            unset($_SESSION['suksesth']);
            //var_dump($data['isi']);
            $this->data['group'] = $this->ion_auth_model->getGroup($id);
            $this->load->view("admin/master/header", $this->data);
            $this->load->view("admin/page/krs/t_ubah", $data);
            $this->load->view("admin/master/footer", $this->data);

            if ($this->input->post('submit') === '') {
                $data = [
                    'id-th' => $this->input->post('id-th', true),
                    'tahun' => $this->input->post('tahun', true),
                    'ket' => $this->input->post('ket', true)
                ];
                if ($this->All_model->updThn($data, $tahun)) {
                    $this->session->set_flashdata('suksesth', 'Diubah');
                    unset($_SESSION['flashth']);
                    $info = [
                        'id-info' => 1,
                        'info' => date('j F Y'),
                        'ket' => date('G \: i \: s')
                    ];
                    $this->All_model->updInfo($info);
                    redirect('krs');
                } else {
                    $this->session->set_flashdata('flashth', 'Gagal diubah');
                    redirect('krs/tambah_tahun');
                }
            }
        }
    }

    public function hapus_thn($id)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->load->model('All_model');
            $this->All_model->delThn($id);
            $info = [
                'id-info' => 1,
                'info' => date('j F Y'),
                'ket' => date('G \: i \: s')
            ];
            $this->All_model->updInfo($info);
            redirect('krs/tambah_tahun');
        }
    }

    public function hapus_smtr($id)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->load->model('All_model');
            $this->All_model->delSmtr($id);
            $info = [
                'id-info' => 1,
                'info' => date('j F Y'),
                'ket' => date('G \: i \: s')
            ];
            $this->All_model->updInfo($info);
            redirect('krs/');
        }
    }

    public function printCSV()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            if (isset($_POST['export'])) {
                $this->load->model('Krs_model');
                $this->load->helper('Krs_helper');
                $isi = $this->Krs_model->printCSV();

                // var_dump($isi);
                // $length = count($isi);
                $data = [];

                foreach ($isi as $key => $value) {
                    if ($value['valid'] == 1 && $value['is_rejected'] == 0) {
                        $value['valid'] = 'Valid';
                    } else if ($value['valid'] == 0 && $value['is_rejected'] == 0) {
                        $value['valid'] = 'Belum di Validasi';
                    } else {
                        $value['valid'] = 'Ditolak';
                    }

                    $data[$key] = [
                        'nim' => $value['nim'],
                        'nama_mhs' => $value['nama_mhs'],
                        'prodi' => $value['prodi'],
                        'semester' => getSemesterFromAngkatan($value['angkatan']),
                        'valid' => $value['valid'],
                        'nama_dosen' => $value['nama_dosen'] == null ? 'Belum Ada' : $value['nama_dosen'],
                        'tahun_ajaran' => $value['tahun_ajaran'],
                        'file' => $value['bukti'] == null ? 'Belum Ada' : base_url('assets/upload/Folder_krs/' . $value['bukti'])
                    ];
                }

                // var_dump($data);
                // die;

                $date = date('j F Y');
                $time = date('G\^i\^s');
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=DataPembayaran_' . $date . '_' . $time . '.csv');
                $output = fopen("php://output", "w");
                fputcsv($output, array('NIM', 'Nama', 'Prodi', 'Semester', 'Status', 'Dosen PA', 'Tahun Ajaran', 'File'));

                foreach ($data as $row) {
                    fputcsv($output, $row);
                }
                fclose($output);
            }
        }
    }

    public function printCSVAll()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            if (isset($_POST['export'])) {
                $this->load->model('Krs_model');
                $this->load->helper('Krs_helper');
                $isi = $this->Krs_model->printCSVAll();

                // var_dump($isi);
                // $length = count($isi);
                $data = [];
                foreach ($isi as $key => $value) {
                    if ($value['valid'] == 1 && $value['is_rejected'] == 0) {
                        $value['valid'] = 'Valid';
                    } else if ($value['valid'] == 0 && $value['is_rejected'] == 0) {
                        $value['valid'] = 'Belum divalidasi';
                    } else {
                        $value['valid'] = 'Ditolak';
                    }

                    $data[$key] = [
                        'nim' => $value['nim'],
                        'nama_mhs' => $value['nama_mhs'],
                        'prodi' => $value['prodi'],
                        'semester' => getSemesterFromAngkatan($value['angkatan']),
                        'valid' => $value['valid'],
                        'nama_dosen' => $value['nama_dosen'] == null ? 'Belum Ada' : $value['nama_dosen'],
                        'tahun_ajaran' => $value['tahun_ajaran'],
                        'file' => $value['bukti'] == null ? 'Belum Ada' : base_url('assets/upload/Folder_krs/' . $value['bukti'])
                    ];
                }

                // var_dump($data);
                // die;

                $date = date('j F Y');
                $time = date('G\^i\^s');
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=SemuaDataPembayaran_' . $date . '_' . $time . '.csv');
                $output = fopen("php://output", "w");
                fputcsv($output, array('NIM', 'Nama', 'Prodi', 'Semester', 'Status', 'Dosen PA', 'Tahun Ajaran', 'File'));

                foreach ($data as $row) {
                    fputcsv($output, $row);
                }
                fclose($output);
            }
        }
    }

    public function importCSV()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            if (isset($_POST['input'])) {

                $this->load->model('All_model');
                if ($this->All_model->importCSV()) {
                    $info = [
                        'id-info' => 1,
                        'info' => date('j F Y'),
                        'ket' => date('G \: i \: s')
                    ];
                    $this->All_model->updInfo($info);
                    $this->session->set_flashdata('sukses', 'Ditambahkan');
                    redirect('krs/');
                } else {
                    $this->session->set_flashdata('flash', 'Gagal diupload');
                    redirect('krs/');
                }
            }
        }
    }

    // public function update_info()
    // {
    //     if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
    //         redirect('krs');
    //     } else {
    //         $this->data['title'] = "KRS - Update info";
    //         $this->data['active'] = "11";
    //         $id = $_SESSION['user_id'];
    //         $this->data['flip'] = "false";
    //         $this->data['ckeditor'] = "krs";
    //         $this->load->model('All_model');
    //         $datas['info'] = $this->All_model->infos();
    //         if (isset($data['info']) == null) {
    //             $data = [
    //                 'id-info' => '1',
    //                 'info' => 'Belum ada info terupdate',
    //                 'ket' => 'Klik edit untuk isi info'
    //             ];
    //             $this->All_model->defaultInfo($data);
    //         }

    //         $this->data['group'] = $this->ion_auth_model->getGroup($id);
    //         $this->load->view("admin/master/header", $this->data);
    //         $this->load->view("admin/page/krs/updateInfo", $datas);
    //         $this->load->view("admin/master/footer", $this->data);

    //         if ($this->input->post('submit') === '') {
    //             $info = [
    //                 'id-info' => $this->input->post('id-info', true),
    //                 'info' => $this->input->post('info', true),
    //                 'ket' => $this->input->post('ket', true)
    //             ];
    //             if ($this->All_model->updInfo($info)) {
    //                 redirect('krs');
    //             } else {
    //                 redirect('krs/update_info');
    //             }
    //         }
    //     }
    // }

    // BAGIAN CLIENT SIDE
    // public function Home()
    // {
    // if ($this->ion_auth->logged_in() || $this->ion_auth->in_group(krs)) {
    //     redirect('sso_hmj', 'refresh');
    // } else {

    //     $this->data['title'] = $this->lang->line('login_heading');

    //     // validate form input
    //     $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
    //     $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

    //     if ($this->form_validation->run() === TRUE) {
    //         // check to see if the user is logging in
    //         // check for "remember me"
    //         $remember = (bool) $this->input->post('remember');

    //         if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
    //             //if the login is successful
    //             //redirect them back to the home page
    //             $this->session->set_flashdata('message', $this->ion_auth->messages());
    //             redirect('sso_hmj', 'refresh');
    //         } else {
    //             // if the login was un-successful
    //             // redirect them back to the login page
    //             $this->session->set_flashdata('message', $this->ion_auth->errors());
    //             redirect('login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
    //         }
    //     } else {
    // the user is not logging in so display the login page
    // set the flash data error message if there is one
    // $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

    // $this->data['identity'] = [
    //     'name' => 'identity',
    //     'id' => 'identity',
    //     'type' => 'text',
    //     'value' => $this->form_validation->set_value('identity'),
    // ];

    // $this->data['password'] = [
    //     'name' => 'password',
    //     'id' => 'password',
    //     'type' => 'password',
    // ];

    // $nim = $this->input->post('nim');

    // $this->load->model('All_model');
    // $data['dtMhs'] = $this->All_model->getSmtrWithTahunKRS($nim);
    // $data['mhs'] = $this->All_model->getMahasiswaById($nim);
    // $data['tahun'] = $this->All_model->getThn();
    // $data['updated_info'] = $this->All_model->infos();

    //     $data['title'] = "Home";

    //     $this->load->view("guest/krs/master/header", $data);
    //     $this->load->view("guest/krs/page/index");
    //     $this->load->view("guest/krs/master/footer", $data);
    // }

    // public function Upload_Form () {
    //     $data['updated_info'] = $this->All_model->infos();
    //     $this->load->view("guest/krs/master/header", $data);
    //     $this->load->view("guest/krs/page/form_upload_mhs.php");
    //     // $this->load->view("guest/krs/master/footer", $data);
    // }
    // END CLIENT SIDE


    //tampilkan data mahasiswa aktif TI start-----
    public function mahasiswa()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['title'] = "KRS - Data Mahasiswa";
            $this->data['active'] = "11";
            $id = $_SESSION['user_id'];
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";

            $this->data['group'] = $this->ion_auth_model->getGroup($id);
            $this->load->model('All_model');
            $data['th'] = $this->All_model->getThn();
            $data['info'] = $this->All_model->infos();
            unset($_SESSION['flash']);
            //var_dump($data['info']);
            if (empty($data['info'])) {
                $data = [
                    'id-info' => 1,
                    'info' => 'Data update kosong',
                    'ket' => 'Tidak ada yang di ubah sebelumnya'
                ];
                $this->All_model->defaultInfo($data);
                $data['info'] = $this->All_model->infos();
                $data['infos'] = false;
            } else {
                $data['infos'] = true;
            }
            $data['prodis'] = [
                [
                    'id' => 'PTI',
                    'prodi' => 'Pendidikan Teknik Informatika'
                ],
                [
                    'id' => 'SI',
                    'prodi' => 'Sistem Informatika'
                ],
                [
                    'id' => 'ILKOM',
                    'prodi' => 'Ilmu Komputer'
                ],
                [
                    'id' => 'MI',
                    'prodi' => 'Manajemen Informasi'
                ]
            ];

            //var_dump($this->All_model->getingAll());
            $data['siswa'] = $this->All_model->getMahasiswa();
            $this->load->view("admin/master/header", $this->data);
            $this->load->view("admin/page/krs/data_mhs", $data);
            $this->load->view("admin/master/footer", $this->data);
        }
    }

    //tampilkan data mahasiswa aktif TI end-----

    // Admin Tambah data mahasiswa Krs
    public function tambah_Mahasiswa_Krs()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {

            $this->data['title'] = "KRS - Tambah Data Mahasiswa";
            $this->data['active'] = "11";
            $id = $_SESSION['user_id'];
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";


            unset($_SESSION['sukses']);
            $this->load->model('All_model');
            $this->lang->load('form_validation');
            $data['th'] = ['2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023'];
            $data['semester'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            $data['dosen'] = $this->db->get('s6_dosen')->result_array();


            $data['users'] = $this->db->select("*")->limit(1)->order_by('id', "DESC")->get("users")->row();

            $data['prodis'] = [
                [
                    'id' => 'PTI',
                    'prodi' => 'Pendidikan Teknik Informatika'
                ],
                [
                    'id' => 'SI',
                    'prodi' => 'Sistem Informasi'
                ],
                [
                    'id' => 'ILKOM',
                    'prodi' => 'Ilmu Komputer'
                ],
                [
                    'id' => 'MI',
                    'prodi' => 'Manajemen Informasi'
                ]
            ];
            $this->data['group'] = $this->ion_auth_model->getGroup($id);
            $this->load->library('form_validation');
            $this->lang->load('form_validation_lang');

            $this->form_validation->set_rules('user_id', 'User Id', 'required|numeric|is_unique[s6_mahasiswa.user_id]');
            $this->form_validation->set_rules('nama', 'Nama Mahasiswa', 'required');
            // $this->form_validation->set_rules('nim', 'NIM Mahasiswa', 'required|numeric|is_unique[s6_mahasiswa.nim]');
            $this->form_validation->set_rules('prodi', 'Prodi', 'required');
            $this->form_validation->set_rules('angkatan', 'Angkatan', 'required');
            $this->form_validation->set_rules('smtr', 'Semester', 'required');
            $this->form_validation->set_rules('dosen_pa', 'Pembimbing Akademik', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view("admin/master/header", $this->data);
                $this->load->view("admin/page/krs/input_data_mahasiswa", $data);
                $this->load->view("admin/master/footer", $this->data);
            } else {
                $nama = $this->input->post('nama');
                $nim = $this->input->post('nim');
                $prodi = $this->input->post('prodi');
                $user_id = $this->input->post('user_id');
                $pa_id = $this->input->post('dosen_pa');
                $angkatan = $this->input->post('angkatan');
                $semester = $this->input->post('smtr');

                $data = [
                    'prodi'     =>  $prodi,
                    'user_id'   =>  $user_id,
                    'pa_id'     =>  $pa_id,
                ];
                $this->db->insert('s6_mahasiswa', $data);
                $data['users'] = $this->db->select("*")->limit(1)->order_by('id', "DESC")->get("users")->row();
                $user_id = $data['users']->id;

                echo $this->input->post('userGroup');
                $this->db->where('user_id', $user_id);
                $this->db->update('users_groups', ['group_id' => $this->input->post('userGroup')]);
                redirect('krs/mahasiswa');
            }
        }
    }

    public function update_data_mahasiswa($mhs_id)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['title'] = "KRS - Update Data Mahasiswa";
            $this->data['active'] = "11";
            $id = $_SESSION['user_id'];
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";
            $this->load->model('All_model');
            $this->data['group'] = $this->ion_auth_model->getGroup($id);
            unset($_SESSION['sukses']);
            $data['th'] = $this->All_model->getThn();
            $data['prodis'] = [
                [
                    'id' => 'PTI',
                    'prodi' => 'Pendidikan Teknik Informatika'
                ],
                [
                    'id' => 'SI',
                    'prodi' => 'Sistem Informasi'
                ],
                [
                    'id' => 'ILKOM',
                    'prodi' => 'Ilmu Komputer'
                ],
                [
                    'id' => 'MI',
                    'prodi' => 'Manajemen Informasi'
                ]
            ];
            $data['th'] = ['2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023'];
            $data['semester'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            $data['dosen'] = $this->db->get('s6_dosen')->result_array();
            $data['mahasiswa'] = $this->db->get_where('s6_mahasiswa', ['id_mhs' => $mhs_id])->row_array();

            $this->load->library('form_validation');

            $this->form_validation->set_rules('nama', 'Nama Mahasiswa', 'required');
            $this->form_validation->set_rules('prodi', 'Prodi', 'required');
            $this->form_validation->set_rules('angkatan', 'Angkatan', 'required');
            $this->form_validation->set_rules('smtr', 'Semester', 'required');
            $this->form_validation->set_rules('dosen_pa', 'Pembimbing Akademik', 'required');
            if ($this->form_validation->run() == FALSE) {

                $this->load->view("admin/master/header", $this->data);
                $this->load->view("admin/page/krs/m_ubah_data_mhs", $data);
                $this->load->view("admin/master/footer", $this->data);
            } else {

                redirect('/krs/simpan_data');
            }
        }
    }
    public function simpan_data()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $id = $this->input->post('id');
            $nama = $this->input->post('nama');
            $nim = $this->input->post('nim');
            $prodi = $this->input->post('prodi');
            $user_id = $this->input->post('user_id');
            $pa_id = $this->input->post('dosen_pa');
            $angkatan = $this->input->post('angkatan');
            $semester = $this->input->post('smtr');

            $data = [
                'nama'      => $nama,
                'nim'       =>  $nim,
                'prodi'     =>  $prodi,
                'user_id'   =>  $user_id,
                'pa_id'     =>  $pa_id,
                'angkatan'  =>  $angkatan,
                'semester'  =>  $semester
            ];

            $this->db->where('id_mhs', $id);
            $this->db->update('s6_mahasiswa', $data);
            $this->session->set_flashdata('sukses', "diubah");

            redirect('krs/mahasiswa');
        }
    }
    // End Admin

    //Start User Mahasiswa
    //Halaman Bukti mahasiswa - [Marchel]
    public function halaman_bukti()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['title'] = "KRS - Bukti Pembayaran";
            $this->data['active'] = "11";
            $id = $_SESSION['user_id'];
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";

            $this->data['group'] = $this->ion_auth_model->getGroup($id);
            $this->load->model('All_model');
            $data['th'] = $this->All_model->getThn();
            $data['info'] = $this->All_model->infos();
            unset($_SESSION['flash']);
            unset($_SESSION['suksesup']);
            unset($_SESSION['suksespa']);
            //var_dump($data['info']);
            if (empty($data['info'])) {
                $data = [
                    'id-info' => 1,
                    'info' => 'Data update kosong',
                    'ket' => 'Tidak ada yang di ubah sebelumnya'
                ];
                $this->All_model->defaultInfo($data);
                $data['info'] = $this->All_model->infos();
                $data['infos'] = false;
            } else {
                $data['infos'] = true;
            }

            //Get id Pa dari mahasiswa
            $pa_id = $this->All_model->getMahasiswaByUserId($id)['pa_id'];
            $data['id'] = $this->All_model->getMahasiswaByUserId($id);

            if ($pa_id === NULL) {
                redirect('krs/pilihPA');
            }
            //get_bukti
            $data['bukti'] = $this->All_model->getDataFormBuktiDosen($pa_id);

            $this->load->view("admin/master/header", $this->data);
            $this->load->view("admin/page/krs/mahasiswa/halaman_bukti", $data);
            $this->load->view("admin/master/footer", $this->data);
        }
    }



    //tes load filter
    public function load_data_filter()
    {
    }

    // Method untuk memilih tahun dan semester yang akan dicek
    public function pilih_validasi()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->load->library('form_validation');
            $this->load->model('All_model');

            $this->data['active'] = "11";
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";

            $id = $_SESSION['user_id'];

            $this->data['group'] = $this->ion_auth_model->getGroup($id);

            $mhs_id = $this->All_model->getMahasiswaByUserId($id)['id_mhs'];

            $pa_id = $this->All_model->getMahasiswaByUserId($id)['pa_id'];
            if ($pa_id === NULL) {
                redirect('krs/pilihPA');
            }

            //get_bukti
            $this->data['bukti'] = $this->All_model->getDataBuktiMahasiswa($mhs_id);

            $this->data['title'] = "Tampilan Validasi";
            $this->load->view("admin/master/header", $this->data);
            $this->load->view("admin/page/krs/mahasiswa/pilih_validasi", $this->data);
            $this->load->view("admin/master/footer", $this->data);

            unset($_SESSION['suksesup']);
            unset($_SESSION['suksespa']);
        }
    }

    public function upload_bukti($id_form)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->load->model('All_model');

            $this->data['active'] = "11";
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";


            $id = $_SESSION['user_id'];

            $this->data['mahasiswa'] = $this->All_model->getMahasiswaByUserId($id);
            // $this->data['group'] = "9";
            $this->data['group'] = $this->ion_auth_model->getGroup($id);
            $this->data['title'] = "Upload Bukti Pembayaran";
            $form = $this->All_model->getForm($id_form);
            $this->data['form'] =  $form;
            $bukti_m = $this->All_model->checkBuktiSudahDiKirim($id_form, $this->data['mahasiswa']['id']);

            $pa_id = $this->All_model->getMahasiswaByUserId($id)['pa_id'];
            if ($pa_id === NULL) {
                redirect('krs/pilihPA');
            }
            //cek bukti sudah diupload;
            if ($bukti_m == 1) {
                redirect('formulir');
            }

            $this->load->view("admin/master/header", $this->data);
            $this->load->view("admin/page/krs/mahasiswa/halaman_upload_bukti", $this->data);
            $this->load->view("admin/master/footer", $this->data);
        }
    }


    //handle data bukti from upload bukti start---
    public function simpan_bukti()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->load->model('All_model');

            $this->data['active'] = "11";
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";
            $id = $_SESSION['user_id'];


            // $this->data['group'] = "9";
            $this->data['group'] = $this->ion_auth_model->getGroup($id);
            $this->data['title'] = "Upload Bukti";

            $id_mahasiswa = $this->input->post('mahasiswa_id');
            $id_form = $this->input->post('id_form');
            $nim = $this->input->post('nim');
            $file_bukti = $_FILES['file_bukti'];
            $this->load->helper('string');
            $data_form = $this->All_model->getForm($id_form);

            //generate nama file dan deskripsi
            $file_name = $nim . '_' . random_string('alnum', 20);
            $deskripsi = 'Pembayaran Iuran KRS tahun ' . $data_form['tahun'] . ', semester ' . $data_form['semester'];
            $config = [
                'upload_path'   => './assets/upload/Folder_krs',
                'allowed_types' => 'pdf',
                'max_size'      => 1024,
                'file_name'     =>  $file_name,
            ];

            if ($file_bukti != '') {
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('file_bukti')) {
                    $data['error'] = $this->upload->display_errors();
                    $this->session->set_flashdata('file_error', $data['error']);
                    redirect('Krs/upload_bukti');
                } else {
                    $data = [
                        'mahasiswa_id'  => $id_mahasiswa,
                        'form_bukti_id' => $id_form,
                        'deskripsi'     => $deskripsi,
                        'file_path'     => $file_name . $this->upload->data('file_ext'),
                        'created_at'    => mdate('%Y-%m-%d %H:%i:%s', now())
                    ];
                    $this->session->set_flashdata('suksesup', 'diupload');
                    $this->db->insert('s6_bukti', $data);
                    redirect('krs/halaman_bukti');
                }
            }
        }
    }

    public function delete_bukti()
    {
        $this->load->model('All_model');

        $this->data['active'] = "11";
        $this->data['flip'] = "false";
        $this->data['ckeditor'] = "krs";

        $id = $_SESSION['user_id'];

        $this->data['group'] = "9";
        $this->data['group'] = $this->ion_auth_model->getGroup($id);
        $this->data['title'] = "Edit Bukti";
        // ambil data
        $mhs_id = $this->All_model->getMahasiswaByUserId($id)['id_mhs'];
        $id = $this->All_model->getIdAndPathDataBuktiMahasiswa($mhs_id)['id'];
        $file_path = $this->All_model->getIdAndPathDataBuktiMahasiswa($mhs_id)['file_path'];
        //var_dump($id);
        $this->load->helper("file");
        unlink($file_path);
        delete_files($file_path);

        $this->db->where('s6_bukti.id', $id);
        $this->db->delete('s6_bukti');
        redirect('krs/pilih_validasi');
    }

    //handle data bukti from upload bukti end------


    // Start Of Dosen Section - [Adi Sastrawan]
    // Start View Mahasiswa

    // public function viewValidasiMahasiswa()
    // {
    //     $this->data['title'] = "KRS - Data Mahasiswa";
    //     $this->data['active'] = "11";
    //     $this->data['flip'] = "false";
    //     $this->data['ckeditor'] = "krs";
    //     $id = $_SESSION['user_id'];
    //     $this->data['group'] = $this->ion_auth_model->getGroup($id);
    //     $where = array('user_id' => $id);
    //     $dosen_id['pa_id'] = $this->All_model->findDosen($where)->result_array();
    //     // $find['pa_id'] = $dosen_id['pa_id'][0]['id'];
    //     $mahasiswa['value'] = $this->All_model->gatherAllDataBukti()->result();
    //     // $dosen_id = $this->All_model->findDosen($where)->result_array()[0]['id'];
    //     $this->data['formBukti'] = $this->db->get('s6_form_bukti');
    //     $this->load->view("admin/master/header", $this->data);
    //     $this->load->view("admin/page/krs/admin/validasiMahasiswa", $mahasiswa);
    //     $this->load->view("admin/master/footer", $this->data);
    // }


    // End View Validasi Mahasiswa

    // Start View MintaBukti
    // public function viewMintaBukti()
    // {
    //     $this->load->model('All_model');
    //     $this->data['title'] = "KRS - Data Mahasiswa";
    //     $this->data['active'] = "11";
    //     $this->data['flip'] = "false";
    //     $this->data['ckeditor'] = "krs";
    //     $id = $_SESSION['user_id'];
    //     $this->data['group'] = $this->ion_auth_model->getGroup($id);
    //     $id = $_SESSION['user_id'];
    //     $where = array('user_id' => $id);
    //     $dosen_id = $this->All_model->findDosen($where)->result_array()[0]['id'];
    //     $this->data['formBukti'] = $this->All_model->formBuktiDosen($dosen_id);
    //     $this->load->view("admin/master/header", $this->data);
    //     $this->load->view("admin/page/krs/admin/mintaBukti", $this->data);
    //     $this->load->view("admin/master/footer", $this->data);
    // }
    // End View MintaBukti

    // public function tambahFormPengajuan()
    // {

    //     $this->load->model('All_model');
    //     $id = $_SESSION['user_id'];
    //     $where = array('user_id' => $id);
    //     $dosen_id = $this->All_model->findDosen($where)->result_array();
    //     $data = [
    //         'expire_date' => $this->input->post('expire_date'),
    //         'tahun' => $this->input->post('tahun'),
    //         'semester' => $this->input->post('semester'),
    //         'dosen_id' => $dosen_id[0]['id'],
    //     ];
    //     $this->All_model->insertFormBukti($data);
    //     redirect("Krs/viewMintaBukti");
    // }


    // Start memvalidkanBukti
    public function memvalidkanBukti($id, $valid)
    {
        $this->load->model('All_model');
        $valid = array('valid' => $valid);
        $where = array('id' => $id);
        $this->All_model->validateBukti($valid, $where);
        redirect("Krs/viewValidasiMahasiswa");
    }
    // End memvalidkanBukti

    // Yang Baru #New

    // ********** START Backend User Dosen ***************** //
    // Start Lihat Mahasiswa
    public function listMahasiswa()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['title'] = "KRS - Data Mahasiswa";
            $this->data['active'] = "11";
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";
            $id = $_SESSION['user_id'];
            $this->data['group'] = $this->ion_auth_model->getGroup($id);

            $this->load->model('Krs_model');
            $this->data['mahasiswa'] = $this->Krs_model->getValidMahasiswaThisSemester($id);

            $this->load->view("admin/master/header", $this->data);
            $this->load->view("admin/page/krs/dosen/index", $this->data);
            $this->load->view("admin/master/footer", $this->data);
        }
    }
    // End Lihat Bukti
    // ********** END Backend User Dosen ***************** //

    // Admin Site validasi bukti Pembayaran Iuran

    // Start View Form Buat Iuran
    public function tambahIuran()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['title'] = "KRS - Tambah Iuran";
            $this->data['active'] = "11";
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";
            $id = $_SESSION['user_id'];
            $this->data['group'] = $this->ion_auth_model->getGroup($id);

            $this->load->view("admin/master/header", $this->data);
            $this->load->view("admin/page/krs/admin/formIuran");
            $this->load->view("admin/master/footer", $this->data);
        }
    }
    // End View Form Buat Iuran


    // Start Edit Aktivasi Iuran
    public function editAktivasiIuran($id_iuran)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->load->model('Krs_Model');
            $statusIuran = $this->Krs_Model->getIuranWhereId($id_iuran)['status'];

            if ($this->Krs_Model->getIuranWhereId($id_iuran) > 0 && $this->Krs_Model->getIuranWhereId($id_iuran)['status'] == '0') {
                if ($this->Krs_Model->updateAtivasiIuran($id_iuran)) {
                    $this->session->set_flashdata('berhasil', 'Diaktivasi');
                    redirect("krs/viewMintaBukti");
                } else {
                    $this->session->set_flashdata('gagal', 'Diaktivasi, Terjadi Masalah');
                    redirect('krs/viewMintaBukti');
                }
            } else if ($this->Krs_Model->getIuranWhereId($id_iuran) > 0 && $this->Krs_Model->getIuranWhereId($id_iuran)['status'] == '1') {
                if ($this->Krs_Model->updateAtivasiIuran($id_iuran)) {
                    $this->session->set_flashdata('berhasil', 'Dinonaktivasi');
                    redirect("krs/viewMintaBukti");
                } else {
                    $this->session->set_flashdata('gagal', 'Dinonaktivasi, Terjadi Masalah');
                    redirect('krs/viewMintaBukti');
                }
            } else {
                show_404();
            }
        }
    }
    // End Edit Aktivasi Iuran

    // Start tambah Iuran
    public function simpanIuran()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->load->model('Krs_Model');
            $tahunDepan = $this->input->post("inputTahunDepan");
            $tahunBelakang = $this->input->post("inputTahunBelakang");
            $data = [
                'tahun_ajaran' => $tahunDepan . "/" . $tahunBelakang,
                'semester' => htmlspecialchars($this->input->post('semester')),
                'status' => 1,
            ];
            $this->Krs_Model->insertIuran($data);
            redirect("Krs/viewMintaBukti");
        }
    }
    // End tambah Iuran

    public function viewBukti($id_iuran)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['title'] = "KRS - Data Bukti Pembayaran";
            $this->data['active'] = "11";
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";
            $id = $_SESSION['user_id'];
            $this->data['group'] = $this->ion_auth_model->getGroup($id);

            $status = $this->input->post('validStatus');
            $this->load->model('Krs_Model');

            if ($status  == NULL) {
                $data['bukti'] = $this->Krs_Model->getDataBuktiPembayaran($id_iuran)->result();
                // redirect("krs/viewBukti/" . $id_iuran);
            } else if ($status == 'none') {
                $data['bukti'] = $this->Krs_Model->getDataBuktiPembayaran($id_iuran)->result();
                // redirect("krs/viewBukti/" . $id_iuran);
            } else if ($status == 'tolak') {
                $data['bukti'] = $this->Krs_Model->getBuktiByStatusUnvalid([$id_iuran, 1])->result();
                // redirect("krs/viewBukti/" . $id_iuran);
            } else {
                $data['bukti'] = $this->Krs_Model->getBuktiByStatus([$id_iuran, $status])->result();
                // redirect("krs/viewBukti/" . $id_iuran);
            }

            // unset($_POST['validStatus']);

            $this->load->view("admin/master/header", $this->data);
            $this->load->view("admin/page/krs/admin/validasiMahasiswa", $data);
            $this->load->view("admin/master/footer", $this->data);
        }
    }
    // End Admin Site validasi bukti Pembayaran Iuran

    // Start Admin Site Lihat data Pembayaran Iuran
    public function viewMintaBukti()
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['title'] = "KRS - Data Iuran";
            $this->data['active'] = "11";
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";
            $id = $_SESSION['user_id'];
            $this->data['group'] = $this->ion_auth_model->getGroup($id);
            $id = $_SESSION['user_id'];

            $this->load->model('Krs_Model');
            $data['iuran'] = $this->Krs_Model->getAllIuran()->result_array();
            $this->load->view("admin/master/header", $this->data);
            $this->load->view("admin/page/krs/admin/mintaBukti", $data);
            $this->load->view("admin/master/footer", $this->data);
        }
    }

    public function viewDetailBukti($id_bukti)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['title'] = "KRS - Data Mahasiswa";
            $this->data['active'] = "11";
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";
            $id = $_SESSION['user_id'];

            $this->data['group'] = $this->ion_auth_model->getGroup($id);

            $this->load->model('Krs_Model');

            $data_pembayaran['value'] = $this->Krs_Model->getDataPembayaran($id_bukti)->row_array();
            $this->load->view("admin/master/header", $this->data);
            $this->load->view("admin/page/krs/dosen/detailBukti", $data_pembayaran);
            $this->load->view("admin/master/footer", $this->data);
        }
    }

    public function tolakBukti($id_bukti)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['active'] = "11";
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";
            $id = $_SESSION['user_id'];

            $this->data['group'] = $this->ion_auth_model->getGroup($id);

            $this->load->model('Krs_Model');

            $param = $this->Krs_Model->getDataPembayaran($id_bukti)->row_array()['id_iuran'];
            if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
                redirect('/');
            } else {
                if ($this->Krs_Model->getDataPembayaran($id_bukti)->row_array() > 0) {
                    if ($this->Krs_Model->updateBukti($id_bukti)) {
                        $this->session->set_flashdata('berhasil', 'Ditolak');
                        redirect("krs/viewBukti/" . $param);
                    } else {
                        $this->session->set_flashdata('gagal', 'Ditolak, Terjadi Masalah');
                        redirect('krs/viewBukti/' . $param);
                    }
                } else {
                    show_404();
                }
            }
        }
    }

    public function validasiBukti($id_bukti)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
            redirect('krs/checkNim');
        } else {
            $this->data['active'] = "11";
            $this->data['flip'] = "false";
            $this->data['ckeditor'] = "krs";
            $id = $_SESSION['user_id'];

            $this->data['group'] = $this->ion_auth_model->getGroup($id);

            $this->load->model('Krs_Model');

            if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(krs)) {
                redirect('/');
            } else {
                $param = $this->Krs_Model->getDataPembayaran($id_bukti)->row_array()['id_iuran'];

                if ($this->Krs_Model->getDataPembayaran($id_bukti)->row_array() > 0) {
                    if ($this->Krs_Model->terimaBukti($id_bukti)) {
                        $this->session->set_flashdata('berhasil', 'Divalidasi');
                        redirect("krs/viewBukti/" . $param);
                    } else {
                        $this->session->set_flashdata('gagal', 'Divalidasi, Terjadi Masalah');
                        redirect('krs/viewBukti/' .  $param);
                    }
                } else {
                    show_404();
                }
            }
        }
    }
    // End Admin Site Lihat data Pembayaran Iuran
    // Start Mahasiswa Page
    public function checkNim()
    {
        $this->data['title'] = "Iuran HMJ TI";
        $this->load->model('Krs_model');
        $nim = $this->input->post('nim');
        $data['mhs'] = $this->Krs_model->findMahasiswaNim($nim);
        $data['dosen'] = $this->Krs_model->loadDosen();
        // var_dump($data['dosen']);
        $data['nim'] = $nim;
        $data['isExist'] = $this->session->flashdata('exist');
        $this->load->view("guest/krs/master/header", $this->data);
        $this->load->view("guest/krs/page/index", $data);
        $this->load->view("guest/krs/master/footer");
    }

    public function createPembayaran()
    {
        if (($this->input->post('name') !== null)) {
            redirect('krs/checkNim');
        } else {
            $this->load->model('Krs_model');
            $data['isExist'] = null;
            $nim = $this->input->post('nim');
            if (!empty($this->Krs_model->findMahasiswaNim($nim))) {
                $this->session->set_flashdata('exist', 1);
                redirect('krs/checkNim/');
            }
            $nama = htmlspecialchars($this->input->post('nama'));
            $prodi = htmlspecialchars($this->input->post('prodi'));
            $angkatan = htmlspecialchars($this->input->post('angkatan'));
            $dosen = htmlspecialchars($this->input->post('pa'));
            $file = $_FILES['file'];
            $id_iuran = $this->Krs_model->findActiveIuran();
            $date = new DateTime();
            $namafile = md5($date->format('Y-m-d H:i:s'));

            if (!empty($file)) {
                // Set preference
                $config['upload_path'] = './assets/upload/Folder_krs';
                $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                $config['max_size'] = '1000'; // max_size in kb
                $config['file_name'] = $namafile;

                // Load upload library
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('file')) {
                    // Get data about the file

                    $data['response'] = 'failed';
                } else {
                    // unlink('./assets/upload/Folder_krs'. $bukti);
                    $uploadData = $this->upload->data('file_name');
                }
            } else {
                $data['response'] = 'failed';
            }

            // mengambil extension dari file
            $fileWithExt = explode('.', $file['name']);

            $mhs = [
                'nama_mhs' => $nama,
                'nim' => $nim,
                'prodi' => $prodi,
                'angkatan' => $angkatan,
                'bukti' => $namafile . '.' . $fileWithExt[1],
                'id_dosen' => $dosen,
                'id_iuran' => $id_iuran[0]["id"]
            ];
            $this->Krs_model->storePembayaran($mhs);
            $this->load->view("guest/krs/master/header");
            $this->load->view("guest/krs/page/index", $data);
            $this->load->view("guest/krs/master/footer");
        }
    }
}
