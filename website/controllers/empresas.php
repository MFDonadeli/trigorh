<?php
    class Empresas extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('Empresa_model');
            $this->load->model('Vaga_model');
            $this->load->model('Preenchimento_model');
            $this->load->library('email');
            $this->load->helper('password_helper');
        }

        //Página Principal do Sistema de Candidatos
        public function index()
        {
            if($this->session->userdata('logged_in_empresa'))
            {
                $data['main_content'] = 'home';
                $this->load->view('empresas/home',$data);
            }
            else
            {
                $data['main_content'] = 'home';
                $this->load->view('empresas/main',$data);
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

                $user_id = $this->Empresa_model->login_user($email,$senha);

                //Validate user
                if($user_id)
                {
                    echo('OK');
                    //Create array of user data
                    $user_data = array(
                            'user_id'   => $user_id,
                            'username'  => $email,
                            'logged_in_empresa' => true
                        );
                        //Set session userdata
                    $this->session->set_userdata($user_data);
                                        
                    redirect('empresas/home');
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
            $data['setor'] = $this->Preenchimento_model->get_conteudo('setor');
            $this->load->view('empresas/cadastrar',$data);
        }

        //Validação do formulário de cadastro de empresa
        public function valida_cadastrar()
        {
            log_message('debug', 'Inicio de Cadastro');

            $this->form_validation->set_rules('nome', 'Nome Completo', 'min_length[10]|required');
            $this->form_validation->set_rules('razao_social', 'Razão Social', 'min_length[10]|required');
            $this->form_validation->set_rules('email', 'Email', 'min_length[8]|required|valid_email');
            $this->form_validation->set_rules('cnpj', 'CNPJ', 'exact_length[18]|required|cnpj_check');
            $this->form_validation->set_rules('setor', 'Setor', 'required|greater_than[-1]');
            $this->form_validation->set_rules('nome_responsavel', 'Nome do Responsável', 'min_length[10]|required');
            $this->form_validation->set_rules('telefone', 'Telefone', 'min_length[14]|min_length[15]|required');
            $this->form_validation->set_rules('senha','Senha','trim|required|min_length[6]|max_length[20]|check_senha_forte');
            $this->form_validation->set_rules('confirma_senha','Confirmação de Senha','trim|required|min_length[6]|max_length[20]|matches[senha]');

            $this->form_validation->set_message('cnpj_check', 'CNPJ digitado é inválido');
            $this->form_validation->set_message('check_senha_forte', 'Senha tem que conter pelos menos 6 caracters, sendo pelo menos uma letra maiúscula, uma minúscula, um número e um caracter especial');

            
            if ($this->form_validation->run() == FALSE) 
            {   
                log_message('debug', 'Falhou na validacao');

                $arr = array('nome' => form_error('nome', '', ''),
                             'email' => form_error('email', '', ''),
                             'razao_social' => form_error('razao_social', '', ''),
                              'cnpj' => form_error('cnpj', '', ''),
                              'setor' => form_error('setor', '', ''),
                              'nome_responsavel' => form_error('nome_responsavel', '', ''),
                              'telefone' => form_error('telefone', '', ''),
                              'senha' => form_error('senha', '', ''),
                              'confirma_senha' => form_error('confirma_senha', '', ''),
                              'msg' => 'Erro');
                
                echo json_encode($arr);
            }
            else
            {
                $nova_empresa = array(
                    'email' => $this->input->post('email'),
                    'ativo' => '1',
                    'nome' => $this->input->post('nome'),
                    'razao_social' => $this->input->post('razao_social'),
                    'cnpj' => preg_replace('/[^0-9]/', '', (string) $this->input->post('cnpj')),
                    'idsetor' => $this->input->post('setor'),
                    'nome_responsavel' => $this->input->post('nome_responsavel'),
                    'telefone' => $this->input->post('telefone'),
                    'observacao' => $this->input->post('observacao'),
                    'senha' => $this->input->post('senha')
                );

                log_message('debug', 'FUNCTION CADASTRO: Funcionou validacao');

                
                $user_type = $this->Empresa_model->has_user($nova_empresa['email'], $nova_empresa['cnpj']);    

                if($user_type == 0)
                {
                    log_message('debug', 'FUNCTION CADASTRO: User_Type 0');
                    $user_id = $this->Empresa_model->insere_empresa($nova_empresa);

                    if($user_id)
                    {
                        log_message('debug', 'FUNCTION CADASTRO: User_Type 0 e UserID:' .$user_id);

                        echo('OK');
                        //Create array of user data
                        $user_data = array(
                                'user_id'   => $user_id,
                                'user'  => $nova_empresa['email'],
                                'logged_in_empresa' => true
                            );
                        //Set session userdata
                        $this->session->set_userdata($user_data);

                        redirect('empresa/cadastra_vaga');
                    }
                }
                else if($user_type == 1) //profissional
                {
                    log_message('debug', 'FUNCTION CADASTRO: User_Type 1');
                    $arr = array('nome' => 'O usuário é um profissional. Deseja continuar?',
                                  'msg' => 'Erro');
                    echo json_encode($arr);    
                }
                else if($user_type == 2)
                {
                    log_message('debug', 'FUNCTION CADASTRO: User_Type 2');
                    $arr = array('nome' => 'Empresa já existente no cadastro, neste endereço de email',
                                    'msg' => 'Erro');
                    echo json_encode($arr);    
                }
                else if($user_type == 3)
                {
                    log_message('debug', 'FUNCTION CADASTRO: User_Type 3');
                    $arr = array('nome' => 'Empresa já existente no cadastro, neste cnpj',
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

            //$data['profissional'] = $this->Empresa_model->informacao_basica($this->session->userdata('user_id'));

            $this->load->view('empresas/home',$data);  
        }

        //Botão de Logout
        public function logout(){
            //Unset user data
            $this->session->unset_userdata('logged_in_empresa');
            $this->session->unset_userdata('user_id');
            $this->session->unset_userdata('nome');
            $this->session->sess_destroy();

            redirect('empresas/index');     
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

        public function get_vagas()
        {
            $result = $this->Empresa_model->get_vagas($this->session->userdata("user_id"));
            $html = '';

            if(count($result) > 0)
            {   
                $data['dados'] = $result;
                $html = $this->load->view('vagas/get_vagas.php', $data, true);
            }

            log_message('debug', 'get_vagas. html:' .  $html);

            echo $html;
        }

        public function gerenciar_vagas()
        {
            $this->load->view('empresas/gerenciar_vagas.php');
        }

        public function apaga_vaga()
        {
            $result = $this->Vaga_model->apaga_vaga($this->input->post('idvaga'));
            if(!$result)
            {
                //TODO: MENSAGEM DE NÃO FOI POSSIVEL APAGAR VAGA
            }
        }

        //Página de Cadastro / Edição de Currículo
        public function cadastra_vaga()
        {
            log_message('debug', 'cadastra_vaga');

            if($this->session->userdata('logged_in_empresa'))
            {
                log_message('debug', $this->input->raw_input_stream);

                $vaga = $this->input->post('vaga');
                $id = $this->session->userdata("user_id");

                if(empty($vaga))
                {
                    $vaga = get_vaga_code();
                    $idvaga = $this->Vaga_model->insere_vaga($vaga, $id);
                }
                else
                    $idvaga = $vaga;
            
                
                $result = $this->Vaga_model->get_dados_vaga($idvaga);
                $arr_dados = json_decode(json_encode($result), true);

                $result = $this->Vaga_model->get_beneficios_vaga($idvaga);
                $arr_beneficio = json_decode(json_encode($result), true);
            
                $arr_dados['beneficios'] = $arr_beneficio;

                $data['dados_vaga'] = $arr_dados;
                $data['vaga'] = $idvaga;
                $data['sexo'] = $this->Preenchimento_model->get_conteudo('sexo');
                $data['estado_civil'] = $this->Preenchimento_model->get_conteudo('estado_civil');
                $data['beneficio'] = $this->Preenchimento_model->get_conteudo('beneficio');
                $data['formacao'] = $this->Preenchimento_model->get_conteudo('formacao');
                $data['jornada'] = $this->Preenchimento_model->get_conteudo('jornada');
                $data['escala'] = $this->Preenchimento_model->get_conteudo('escala');
                $data['setor'] = $this->Preenchimento_model->get_conteudo('setor');
                $data['tipo_contrato'] = $this->Preenchimento_model->get_conteudo('tipo_contrato');
                $data['beneficio'] = $this->Preenchimento_model->get_conteudo('beneficio');
                $data['nivel_hierarquico'] = $this->Preenchimento_model->get_conteudo('nivel_hierarquico');
                $data['nivel_idioma'] = $this->Preenchimento_model->get_conteudo('nivel_idioma');

                $this->load->view('empresas/cadastra_vaga',$data);
            }
            else
            {
                $data['main_content'] = 'home';
                $this->load->view('empresas/login',$data);
            }
        }

    }
?>