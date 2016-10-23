<?php
    class Candidatos extends CI_Controller 
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('Candidato_model');
        }

        public function index()
        {
            if($this->session->userdata('logged_in'))
            {
                $data['main_content'] = 'home';
                $this->load->view('candidatos/home',$data);
            }
            else
            {
                $data['main_content'] = 'home';
                $this->load->view('candidatos/main',$data);
            }
        }

        public function login() 
        {
            $this->form_validation->set_rules('email', 'Email', 'min_length[8]|required|valid_email');
            $this->form_validation->set_rules('senha','Password','trim|required|min_length[6]|max_length[20]');
            
            // .. etc           
            if ($this->form_validation->run() == FALSE) 
            {       
                $arr = array('email' => form_error('email', '', ''),
                              'senha' => form_error('senha', '', ''));
                
                echo json_encode($arr);
            }
            else
            {
                $email = $this->input->post('email');
                $senha = $this->input->post('senha');

                $user_id = $this->Candidato_model->login_user($email,$senha);

                //Validate user
                if($user_id)
                {
                    echo('OK');
                    //Create array of user data
                    $user_data = array(
                            'user_id'   => $user_id,
                            'username'  => $username,
                            'logged_in' => true
                        );
                        //Set session userdata
                    $this->session->set_userdata($user_data);
                                        
                    redirect('candidatos/home');
                }
                else
                {
                    $arr = array('email' => 'Os dados digitados não existem ou estão errados!');
                    echo json_encode($arr);    
                }
            }  
        }

        public function cadastrar()
        {
            $data = '';
            $this->load->view('candidatos/cadastrar',$data);
        }

        public function cadastro()
        {
            $this->form_validation->set_rules('nome', 'Nome Completo', 'min_length[10]|required');
            $this->form_validation->set_rules('email', 'Email', 'min_length[8]|required|valid_email');
            $this->form_validation->set_rules('cpf', 'CPF', 'min_length[14]|max_length[14]|required|callback_cpf_check');
            $this->form_validation->set_rules('senha','Senha','trim|required|min_length[6]|max_length[20]');
            $this->form_validation->set_rules('confirma_senha','Confirmação de Senha','trim|required|min_length[6]|max_length[20]|matches[senha]');

            $this->form_validation->set_message('cpf_check', 'CPF digitado é inválido');

            
            if ($this->form_validation->run() == FALSE) 
            {       
                $arr = array('nome' => form_error('nome', '', ''),
                             'email' => form_error('email', '', ''),
                              'cpf' => form_error('cpf', '', ''),
                              'senha' => form_error('senha', '', ''),
                              'confirma_senha' => form_error('confirma_senha', '', ''));
                
                echo json_encode($arr);
            }
            else
            {
                $nome = $this->input->post('nome');
                $email = $this->input->post('email');
                $cpf = $this->input->post('cpf');
                $senha = $this->input->post('senha');
                $confirma_senha = $this->input->post('confirma_senha');

                $user_type = $this->Candidato_model->has_user($email);    

                if($user_type == 1)
                {
                    $arr = array('nome' => 'Usuário já existente no cadastro');
                    echo json_encode($arr);    
                }
                else if($user_type == 2)
                {
                    $arr = array('nome' => 'O usuário é uma empresa. Deseja continuar?');
                    echo json_encode($arr);    
                }
                else
                {
                    $user_id = $this->Candidato_model->insere_candidato($nome, $email, $senha, $cpf);

                    if($user_id)
                    {
                        echo('OK');
                        //Create array of user data
                        $user_data = array(
                                'user_id'   => $user_id,
                                'nome'  => $nome,
                                'logged_in' => true
                            );
                        //Set session userdata
                        $this->session->set_userdata($user_data);

                        redirect('candidatos/curriculo');
                    }
                }
            }


        }

        public function home()
        {
            $data = '';
            redirect('candidatos/home');    
        }

        public function logout(){
            //Unset user data
            $this->session->unset_userdata('logged_in');
            $this->session->unset_userdata('user_id');
            $this->session->unset_userdata('nome');
            $this->session->sess_destroy();
            
            $data['main_content'] = 'home';
            $this->load->view('candidatos/index',$data);   
        }

        public function cpf_check($str)
        {
            $cpf = preg_replace('/[^0-9]/', '', (string) $str);

            // Valida tamanho
            if (strlen($cpf) != 11)
                return false;

            // Calcula e confere primeiro dígito verificador
            for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--)
                $soma += $cpf{$i} * $j;

            $resto = $soma % 11;

            if ($cpf{9} != ($resto < 2 ? 0 : 11 - $resto))
                return false;

            // Calcula e confere segundo dígito verificador
            for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--)
                $soma += $cpf{$i} * $j;

            $resto = $soma % 11;

            return $cpf{10} == ($resto < 2 ? 0 : 11 - $resto);
        }
    }
?>