<?php
    class Candidatos extends CI_Controller 
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('Candidato_model');
            $this->load->model('Preenchimento_model');
            $this->load->library('email');
            $this->load->helper('password_helper');
        }

        //Página Principal do Sistema de Candidatos
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

        //Formulário de Login
        public function login() 
        {
            $this->form_validation->set_rules('email', 'Email', 'min_length[8]|required|valid_email');
            $this->form_validation->set_rules('senha','Password','trim|required|min_length[6]|max_length[20]');
            
            // .. etc           
            if ($this->form_validation->run() == FALSE) 
            {       
                $arr = array('email' => form_error('email', '', ''),
                              'senha' => form_error('senha', '', ''),
                              'msg' => 'Erro');
                
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
                            'username'  => $email,
                            'logged_in' => true
                        );
                        //Set session userdata
                    $this->session->set_userdata($user_data);
                                        
                    redirect('candidatos/home');
                }
                else
                {
                    $arr = array('email' => 'Os dados digitados não existem ou estão errados!',
                                 'msg' => 'Erro');
                    echo json_encode($arr);    
                }
            }  
        }

        //Página de Cadastro de Currículo
        public function cadastrar()
        {
            $data = '';
            $this->load->view('candidatos/cadastrar',$data);
        }

        //Validação do formulário de cadastro de currículo
        public function valida_cadastrar()
        {
            log_message('debug', 'Inicio de Cadastro');

            $this->form_validation->set_rules('nome', 'Nome Completo', 'min_length[10]|required');
            $this->form_validation->set_rules('email', 'Email', 'min_length[8]|required|valid_email');
            $this->form_validation->set_rules('cpf', 'CPF', 'exact_length[14]|required|cpf_check');
            $this->form_validation->set_rules('senha','Senha','trim|required|min_length[6]|max_length[20]|check_senha_forte');
            $this->form_validation->set_rules('confirma_senha','Confirmação de Senha','trim|required|min_length[6]|max_length[20]|matches[senha]');

            $this->form_validation->set_message('cpf_check', 'CPF digitado é inválido');
            $this->form_validation->set_message('check_senha_forte', 'Senha tem que conter pelos menos 6 caracters, sendo pelo menos uma letra maiúscula, uma minúscula, um número e um caracter especial');

            
            if ($this->form_validation->run() == FALSE) 
            {   
                log_message('debug', 'Falhou na validacao');

                $arr = array('nome' => form_error('nome', '', ''),
                             'email' => form_error('email', '', ''),
                              'cpf' => form_error('cpf', '', ''),
                              'senha' => form_error('senha', '', ''),
                              'confirma_senha' => form_error('confirma_senha', '', ''),
                              'msg' => 'Erro');
                
                echo json_encode($arr);
            }
            else
            {
                $nome = $this->input->post('nome');
                $email = $this->input->post('email');
                $cpf = $this->input->post('cpf');
                $senha = $this->input->post('senha');
                $confirma_senha = $this->input->post('confirma_senha');

                log_message('debug', 'FUNCTION CADASTRO: Funcionou validacao');

                $user_type = $this->Candidato_model->has_user($email, $cpf);    

                if($user_type == 0)
                {
                    log_message('debug', 'FUNCTION CADASTRO: User_Type 0');
                    $user_id = $this->Candidato_model->insere_candidato($nome, $email, $senha, $cpf);

                    if($user_id)
                    {
                        log_message('debug', 'FUNCTION CADASTRO: User_Type 0 e UserID:' .$user_id);

                        echo('OK');
                        //Create array of user data
                        $user_data = array(
                                'user_id'   => $user_id,
                                'user'  => $email,
                                'logged_in' => true
                            );
                        //Set session userdata
                        $this->session->set_userdata($user_data);

                        redirect('candidatos/curriculo');
                    }
                }
                else if($user_type == 1)
                {
                    log_message('debug', 'FUNCTION CADASTRO: User_Type 1');
                    $arr = array('nome' => 'Usuário já existente no cadastro, neste endereço de email',
                                  'msg' => 'Erro');
                    echo json_encode($arr);    
                }
                else if($user_type == 2)
                {
                    log_message('debug', 'FUNCTION CADASTRO: User_Type 2');
                    $arr = array('nome' => 'O usuário é uma empresa. Deseja continuar?',
                                    'msg' => 'Erro');
                    echo json_encode($arr);    
                }
                else if($user_type == 3)
                {
                    log_message('debug', 'FUNCTION CADASTRO: User_Type 3');
                    $arr = array('nome' => 'Usuário já existente no cadastro, neste cpf',
                                    'msg' => 'Erro');
                    echo json_encode($arr);    
                }
            }


        }

        //Página Interna Principal do Candidato
        public function home()
        {
            $data['main_content'] = 'home';
            $data['prof_id'] = $this->session->userdata('user_id');
            $data['prof'] = $this->session->userdata('user');

            $data['profissional'] = $this->Candidato_model->informacao_basica($this->session->userdata('user_id'));

            $this->load->view('candidatos/home',$data);  
        }

        //Botão de Logout
        public function logout(){
            //Unset user data
            $this->session->unset_userdata('logged_in');
            $this->session->unset_userdata('user_id');
            $this->session->unset_userdata('nome');
            $this->session->sess_destroy();

            redirect('candidatos/index');     
        }

        //Página de Cadastro / Edição de Currículo
        public function curriculo()
        {
            if($this->session->userdata('logged_in'))
            {
                $data['dados_pessoais'] = $this->Candidato_model->get_dados_pessoais($this->session->userdata('user_id'));

                $data['sexo'] = $this->Preenchimento_model->get_conteudo('sexo');
                $data['estado_civil'] = $this->Preenchimento_model->get_conteudo('estado_civil');
                $data['beneficio'] = $this->Preenchimento_model->get_conteudo('beneficio');
                $data['formacao'] = $this->Preenchimento_model->get_conteudo('formacao');
                $data['jornada'] = $this->Preenchimento_model->get_conteudo('jornada');
                $data['setor'] = $this->Preenchimento_model->get_conteudo('setor');
                $data['tipo_contrato'] = $this->Preenchimento_model->get_conteudo('tipo_contrato');
                $data['beneficio'] = $this->Preenchimento_model->get_conteudo('beneficio');
                $data['nivel_hierarquico'] = $this->Preenchimento_model->get_conteudo('nivel_hierarquico');
                $data['nivel_idioma'] = $this->Preenchimento_model->get_conteudo('nivel_idioma');

                $this->load->view('candidatos/curriculo',$data);
            }
            else
            {
                $data['main_content'] = 'home';
                $this->load->view('candidatos/login',$data);
            }
        }

        //Página para recuperação de senha
        public function recupera_senha()
        {
            $data = '';
            $this->load->view('candidatos/recupera_senha',$data);  
        }

        //Validação do formulário de recuperação de senha
        public function valida_recupera_senha()
        {

            $this->form_validation->set_rules('email', 'Email', 'min_length[8]|required|valid_email');
            $this->form_validation->set_rules('cpf', 'CPF', 'min_length[14]|max_length[14]|required|cpf_check');

            $this->form_validation->set_message('cpf_check', 'CPF digitado é inválido');
            
            // .. etc           
            if ($this->form_validation->run() == FALSE) 
            {       
                $arr = array('email' => form_error('email', '', ''),
                              'cpf' => form_error('cpf', '', ''),
                              'msg' => 'Erro');
                
                echo json_encode($arr);
            }
            else
            {
                $email = $this->input->post('email');
                $cpf = $this->input->post('cpf');

                $id = $this->Candidato_model->get_user($email, $cpf);

                //Se existir usuário
                if($id)
                {
                    $hash = $this->Candidato_model->registra_acao($id, Acao::MUDOU_SENHA);

                    $this->email->from('your@example.com', 'Your Name');
                    $this->email->to($email);

                    $this->email->subject('Recuperação de senha');
                    $this->email->message('Mensagem');

                    $this->email->send();

                    log_message('debug', 'Link para redefinir senha: ' . base_url() . 'candidatos/redefine_senha/' . $id . '/' . $hash);
                }
                else
                {
                    $arr = array('email' => 'Usuário não cadastrado no banco de dados',
                              'msg' => 'Erro');
                }
                

            }  
        }

        //Página de redefinição de senha. Acesso via email enviado ao profissional
        public function redefine_senha($id = '', $hash = '')
        {
            if($id == '')
            {
                $data['no_data'] = true;
                $this->load->view('candidatos/redefine_senha',$data);  
                return;    
            }

            if($this->Candidato_model->check_pedido_senha($id, $hash))
            {
                $data['no_data'] = false;
                $data['id'] = $id;

                $this->load->view('candidatos/redefine_senha',$data);  
            }
            else
            {
                $data['no_data'] = true;
                $this->load->view('candidatos/redefine_senha',$data);     
            }
        }

        //Validação do formulário de redefinição de senha
        public function valida_redefine_senha()
        {
            log_message('debug', 'valida_redefine_senha');

            $this->form_validation->set_rules('senha','Senha','trim|required|min_length[6]|max_length[20]|check_senha_forte');
            $this->form_validation->set_rules('confirma_senha','Confirmação de Senha','trim|required|min_length[6]|max_length[20]|matches[senha]');

            $this->form_validation->set_message('check_senha_forte', 'Senha tem que conter pelos menos 6 caracters, sendo pelo menos uma letra maiúscula, uma minúscula, um número e um caracter especial');
            
            // .. etc           
            if ($this->form_validation->run() == FALSE) 
            {       
                log_message('debug', 'Validou...');

                $arr = array('senha' => form_error('senha', '', ''),
                            'confirma_senha' => form_error('confirma_senha', '', ''),
                            'msg' => 'Erro');
                
                echo json_encode($arr);
            }
            else
            {
                log_message('debug', 'Nao Validou...');

                $senha = $this->input->post('senha');
                $confirma_senha = $this->input->post('confirma_senha');
                $id = $this->input->post('id');

                log_message('debug', $this->input->raw_input_stream);

                if($this->Candidato_model->atualiza_senha($id, $senha))
                    ;//Senha atualizada com sucesso;
                else
                    ;//Senha não atualizada
            } 
        }

    }
?>